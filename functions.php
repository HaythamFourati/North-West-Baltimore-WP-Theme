<?php

function boilerplate_load_assets() {
  wp_enqueue_script('ourmainjs', get_theme_file_uri('/build/index.js'), array('wp-element', 'react-jsx-runtime'), '1.0', true);
  wp_enqueue_style('ourmaincss', get_theme_file_uri('/build/index.css'));
}

add_action('wp_enqueue_scripts', 'boilerplate_load_assets');

function boilerplate_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'boilerplate_admin_scripts');

function boilerplate_add_support() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'boilerplate_add_support');

// Register Custom Post Type for Businesses
function register_business_post_type() {
    $labels = array(
        'name'                  => 'Business Listings',
        'singular_name'         => 'Business Listing',
        'menu_name'            => 'Business Listings',
        'add_new'              => 'Add New Business Listing',
        'add_new_item'         => 'Add New Business Listing',
        'edit_item'            => 'Edit Business Listing',
        'new_item'             => 'New Business Listing',
        'view_item'            => 'View Business Listing',
        'search_items'         => 'Search Business Listings',
        'not_found'            => 'No business listings found',
        'not_found_in_trash'   => 'No business listings found in Trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true, // Enable Gutenberg editor
        'menu_icon'           => 'dashicons-store',
        'supports'            => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'revisions'
        ),
        'rewrite'            => array('slug' => 'business-listings'),
        'menu_position'      => 5,
    );

    register_post_type('business', $args);
}
add_action('init', 'register_business_post_type');

// Register Business Category Taxonomy
function register_business_taxonomies() {
    // Business Category Taxonomy
    $category_labels = array(
        'name'              => 'Business Categories',
        'singular_name'     => 'Business Category',
        'search_items'      => 'Search Business Categories',
        'all_items'         => 'All Business Categories',
        'parent_item'       => 'Parent Business Category',
        'parent_item_colon' => 'Parent Business Category:',
        'edit_item'         => 'Edit Business Category',
        'update_item'       => 'Update Business Category',
        'add_new_item'      => 'Add New Business Category',
        'new_item_name'     => 'New Business Category Name',
        'menu_name'         => 'Categories'
    );

    register_taxonomy('business_category', 'business', array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'business-category'),
    ));

    // Business Location/City Taxonomy
    $location_labels = array(
        'name'              => 'Cities',
        'singular_name'     => 'City',
        'search_items'      => 'Search Cities',
        'all_items'         => 'All Cities',
        'parent_item'       => 'Parent City',
        'parent_item_colon' => 'Parent City:',
        'edit_item'         => 'Edit City',
        'update_item'       => 'Update City',
        'add_new_item'      => 'Add New City',
        'new_item_name'     => 'New City Name',
        'menu_name'         => 'Cities'
    );

    register_taxonomy('business_city', 'business', array(
        'hierarchical'      => true,
        'labels'            => $location_labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'city'),
    ));
}
add_action('init', 'register_business_taxonomies');

// Add custom query vars for business filtering
function add_business_query_vars($vars) {
    $vars[] = 'city';
    $vars[] = 'sort';
    $vars[] = 'business_category';
    $vars[] = 'business_city';
    return $vars;
}
add_filter('query_vars', 'add_business_query_vars');

// Modify business category queries
function modify_business_category_query($query) {
    if (!is_admin() && $query->is_main_query() && is_tax('business_category')) {
        // Set posts per page
        $query->set('posts_per_page', 12);

        // Handle city filter
        $city = get_query_var('city');
        if (!empty($city)) {
            $tax_query = array(
                array(
                    'taxonomy' => 'business_city',
                    'field' => 'slug',
                    'terms' => $city
                )
            );
            $query->set('tax_query', array_merge($query->get('tax_query'), $tax_query));
        }

        // Handle sorting
        $sort = get_query_var('sort');
        switch ($sort) {
            case 'oldest':
                $query->set('orderby', 'date');
                $query->set('order', 'ASC');
                break;
            case 'name_asc':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                break;
            case 'name_desc':
                $query->set('orderby', 'title');
                $query->set('order', 'DESC');
                break;
            default: // newest
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
        }
    }
}
add_action('pre_get_posts', 'modify_business_category_query');

// Modify business archive queries
function modify_business_archive_query($query) {
    if (!is_admin() && $query->is_main_query() && (is_post_type_archive('business') || is_tax('business_category'))) {
        // Set posts per page
        $query->set('posts_per_page', 12);

        // Initialize tax query array
        $tax_query = array();

        // Handle category filter
        $category = get_query_var('business_category');
        if (!empty($category)) {
            $tax_query[] = array(
                'taxonomy' => 'business_category',
                'field' => 'slug',
                'terms' => $category
            );
        }

        // Handle city filter
        $city = get_query_var('business_city');
        if (!empty($city)) {
            $tax_query[] = array(
                'taxonomy' => 'business_city',
                'field' => 'slug',
                'terms' => $city
            );
        }

        // If we have tax queries, add the relation
        if (!empty($tax_query)) {
            $tax_query['relation'] = 'AND';
            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'modify_business_archive_query');

// Custom Nav Walker for Desktop Menu
class Custom_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= '<li class="relative">';
        
        $classes = 'text-base font-medium text-gray-500 hover:text-gray-900 transition-colors duration-200';
        if ($item->current) {
            $classes .= ' text-blue-600';
        }
        
        $output .= '<a href="' . esc_url($item->url) . '" class="' . esc_attr($classes) . '">';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }
}

// Custom Nav Walker for Mobile Menu
class Mobile_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= '<li>';
        
        $classes = 'block px-3 py-2 text-base font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors duration-200';
        if ($item->current) {
            $classes .= ' text-blue-600 bg-blue-50';
        }
        
        $output .= '<a href="' . esc_url($item->url) . '" class="' . esc_attr($classes) . '">';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }
}

// Register Navigation Menus
function register_theme_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'north-west-baltimore'),
        'footer' => __('Footer Menu', 'north-west-baltimore'),
    ));
}
add_action('init', 'register_theme_menus');

// Add Custom Meta Boxes for Business Details
function add_business_meta_boxes() {
    add_meta_box(
        'business_details',
        'Business Details',
        'render_business_details_meta_box',
        'business',
        'normal',
        'high'
    );
    
    add_meta_box(
        'business_gallery',
        'Business Gallery',
        'render_business_gallery_meta_box',
        'business',
        'normal',
        'high'
    );
    
    add_meta_box(
        'google_business_profile',
        'Google Business Profile',
        'render_google_business_meta_box',
        'business',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_business_meta_boxes');

// Render Business Details Meta Box
function render_business_details_meta_box($post) {
    // Add nonce for security
    wp_nonce_field('business_details_nonce', 'business_details_nonce');
    
    // Get existing values
    $address = get_post_meta($post->ID, '_business_address', true);
    $phone = get_post_meta($post->ID, '_business_phone', true);
    $email = get_post_meta($post->ID, '_business_email', true);
    $website = get_post_meta($post->ID, '_business_website', true);
    $hours = get_post_meta($post->ID, '_business_hours', true);
    $featured = get_post_meta($post->ID, 'featured', true);
    
    // Default hours template if empty
    if (empty($hours)) {
        $hours = "Monday: 9:00 AM - 5:00 PM\nTuesday: 9:00 AM - 5:00 PM\nWednesday: 9:00 AM - 5:00 PM\nThursday: 9:00 AM - 5:00 PM\nFriday: 9:00 AM - 5:00 PM\nSaturday: Closed\nSunday: Closed";
    }
    ?>
    <div class="business-details-form">
        <p>
            <label for="business_address">Address:</label><br>
            <textarea id="business_address" name="business_address" class="widefat" rows="3"><?php echo esc_textarea($address); ?></textarea>
        </p>

        <p>
            <label for="business_phone">Phone Number:</label><br>
            <input type="tel" id="business_phone" name="business_phone" class="widefat" value="<?php echo esc_attr($phone); ?>">
        </p>

        <p>
            <label for="business_email">Email Address:</label><br>
            <input type="email" id="business_email" name="business_email" class="widefat" value="<?php echo esc_attr($email); ?>">
        </p>

        <p>
            <label for="business_website">Website:</label><br>
            <input type="url" id="business_website" name="business_website" class="widefat" value="<?php echo esc_attr($website); ?>">
        </p>

        <p>
            <label for="business_hours">Business Hours:</label><br>
            <small class="description">Enter hours in format "Day: HH:MM AM - HH:MM PM" (one day per line)</small><br>
            <textarea id="business_hours" name="business_hours" class="widefat code" rows="7" style="font-family: monospace;"><?php echo esc_textarea($hours); ?></textarea>
        </p>

        <p>
            <label>
                <input type="checkbox" name="business_featured" value="yes" <?php checked($featured, 'yes'); ?>>
                Feature this business
            </label>
        </p>
    </div>
    <?php
}

// Render Business Gallery Meta Box
function render_business_gallery_meta_box($post) {
    wp_nonce_field('business_gallery_nonce', 'business_gallery_nonce');
    
    // Get existing gallery images
    $gallery_images = get_post_meta($post->ID, '_business_gallery', true);
    $gallery_images = $gallery_images ? $gallery_images : array();
    ?>
    <div class="business-gallery-fields">
        <div id="business-gallery-container">
            <?php
            if (!empty($gallery_images)) {
                foreach ($gallery_images as $image_id) {
                    $image = wp_get_attachment_image($image_id, 'thumbnail');
                    if ($image) {
                        echo '<div class="gallery-image">';
                        echo $image;
                        echo '<input type="hidden" name="business_gallery[]" value="' . esc_attr($image_id) . '">';
                        echo '<button type="button" class="remove-image">Remove</button>';
                        echo '</div>';
                    }
                }
            }
            ?>
        </div>
        <button type="button" class="button" id="add-gallery-image">Add Image</button>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var frame;
        $('#add-gallery-image').on('click', function(e) {
            e.preventDefault();
            
            if (frame) {
                frame.open();
                return;
            }
            
            frame = wp.media({
                title: 'Select Images',
                button: {
                    text: 'Add to Gallery'
                },
                multiple: true
            });
            
            frame.on('select', function() {
                var attachments = frame.state().get('selection').toJSON();
                attachments.forEach(function(attachment) {
                    var html = '<div class="gallery-image">';
                    html += '<img src="' + attachment.sizes.thumbnail.url + '" alt="">';
                    html += '<input type="hidden" name="business_gallery[]" value="' + attachment.id + '">';
                    html += '<button type="button" class="remove-image">Remove</button>';
                    html += '</div>';
                    $('#business-gallery-container').append(html);
                });
            });
            
            frame.open();
        });
        
        $(document).on('click', '.remove-image', function() {
            $(this).parent().remove();
        });
    });
    </script>
    <style>
    .business-gallery-fields {
        margin: 15px 0;
    }
    .gallery-image {
        display: inline-block;
        margin: 5px;
        position: relative;
    }
    .gallery-image img {
        max-width: 150px;
        height: auto;
    }
    .remove-image {
        position: absolute;
        top: 0;
        right: 0;
        background: #dc3545;
        color: white;
        border: none;
        padding: 2px 5px;
        cursor: pointer;
    }
    </style>
    <?php
}

/**
 * Search for Google Business Profile
 */
function search_google_places() {
    check_ajax_referer('google_places_search', 'nonce');
    
    $search = sanitize_text_field($_POST['search']);
    $api_key = get_option('google_places_api_key');
    
    if (!$api_key) {
        wp_send_json_error('API key not found');
    }

    // Add location bias for Baltimore to improve local search results
    $baltimore_location = '39.2904,-76.6122'; // Baltimore coordinates
    $search_url = "https://maps.googleapis.com/maps/api/place/textsearch/json"
                . "?query=" . urlencode($search)
                . "&location=" . $baltimore_location
                . "&radius=20000" // 20km radius
                . "&key=" . $api_key;
    
    error_log('Google Places Search URL: ' . $search_url);
    $response = wp_remote_get($search_url);
    
    if (is_wp_error($response)) {
        error_log('Google Places API Error: ' . $response->get_error_message());
        wp_send_json_error('Failed to fetch results: ' . $response->get_error_message());
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    error_log('Google Places API Response: ' . print_r($data, true));
    
    if ($data['status'] === 'OK' && !empty($data['results'])) {
        // Format the results
        $formatted_results = array_map(function($place) {
            return array(
                'place_id' => $place['place_id'],
                'name' => $place['name'],
                'formatted_address' => $place['formatted_address'],
                'rating' => $place['rating'] ?? 0,
                'user_ratings_total' => $place['user_ratings_total'] ?? 0
            );
        }, $data['results']);
        
        wp_send_json_success($formatted_results);
    } else {
        $error_message = 'No results found. Status: ' . $data['status'];
        if (isset($data['error_message'])) {
            $error_message .= ' - ' . $data['error_message'];
        }
        error_log('Google Places API Error: ' . $error_message);
        wp_send_json_error($error_message);
    }
}
add_action('wp_ajax_search_google_places', 'search_google_places');

/**
 * Get place details from Google Places API
 */
function get_place_details() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'google_places_search')) {
        wp_send_json_error('Invalid nonce');
    }

    if (!isset($_POST['place_id'])) {
        wp_send_json_error('Place ID is required');
    }

    $api_key = get_option('google_places_api_key');
    if (!$api_key) {
        wp_send_json_error('Google Places API key is not configured');
    }

    $place_id = sanitize_text_field($_POST['place_id']);
    $fields = 'formatted_phone_number,website,formatted_address,url';
    $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$place_id}&fields={$fields}&key={$api_key}";

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        wp_send_json_error('Failed to fetch place details');
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($data['result'])) {
        wp_send_json_error('Invalid response from Google Places API');
    }

    // Add debug logging
    error_log('Google Places API Response: ' . print_r($data['result'], true));

    wp_send_json_success($data['result']);
}
add_action('wp_ajax_get_place_details', 'get_place_details');

/**
 * Add Google Business Profile meta box
 */
function add_google_business_meta_box() {
    add_meta_box(
        'google_business_profile',
        'Google Business Profile',
        'render_google_business_meta_box',
        'business',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_google_business_meta_box');

/**
 * Render Google Business Profile meta box
 */
function render_google_business_meta_box($post) {
    wp_nonce_field('google_business_profile_nonce', 'google_business_profile_nonce');
    
    $place_id = get_post_meta($post->ID, '_google_place_id', true);
    $place_name = get_post_meta($post->ID, '_google_place_name', true);
    $place_address = get_post_meta($post->ID, '_google_place_address', true);
    ?>
    <div class="google-places-search">
        <p class="description">Search for your business on Google to establish the connection:</p>
        <div class="search-container" style="margin-bottom: 15px;">
            <input type="text" 
                   id="google-places-search" 
                   class="widefat" 
                   placeholder="Enter business name..."
                   style="margin-bottom: 10px;">
            <button type="button" 
                    id="search-places" 
                    class="button">
                Search Google Business Profile
            </button>
        </div>
        
        <div id="search-results" class="hidden" style="margin: 15px 0; padding: 10px; background: #f8f9fa; border-radius: 4px;">
            <!-- Results will be populated here -->
        </div>

        <div id="selected-place" <?php echo $place_id ? '' : 'class="hidden"'; ?> 
             style="margin: 15px 0; padding: 15px; background: #f0f9ff; border: 1px solid #93c5fd; border-radius: 4px;">
            <h4 style="margin: 0 0 10px;">Connected Business Profile:</h4>
            <div id="place-details">
                <?php if ($place_id): ?>
                <p><strong>Name:</strong> <span id="saved-name"><?php echo esc_html($place_name); ?></span></p>
                <p><strong>Address:</strong> <span id="saved-address"><?php echo esc_html($place_address); ?></span></p>
                <p><strong>Place ID:</strong> <span id="saved-place-id"><?php echo esc_html($place_id); ?></span></p>
                <?php endif; ?>
            </div>
            <button type="button" 
                    id="remove-place" 
                    class="button" 
                    <?php echo $place_id ? '' : 'style="display: none;"'; ?>>
                Remove Connection
            </button>
        </div>

        <input type="hidden" id="google_place_id" name="_google_place_id" value="<?php echo esc_attr($place_id); ?>">
        <input type="hidden" id="google_place_name" name="_google_place_name" value="<?php echo esc_attr($place_name); ?>">
        <input type="hidden" id="google_place_address" name="_google_place_address" value="<?php echo esc_attr($place_address); ?>">
    </div>

    <script>
    jQuery(document).ready(function($) {
        var searchTimeout;
        var nonce = '<?php echo wp_create_nonce('google_places_search'); ?>';

        $('#search-places').on('click', function() {
            var search = $('#google-places-search').val();
            if (!search) return;

            $(this).prop('disabled', true).text('Searching...');
            
            $.post(ajaxurl, {
                action: 'search_google_places',
                search: search,
                nonce: nonce
            }, function(response) {
                $('#search-places').prop('disabled', false).text('Search Google Business Profile');
                
                if (response.success && response.data.length) {
                    var results = response.data;
                    var html = '<h4>Search Results:</h4><div class="results-list">';
                    
                    results.forEach(function(place) {
                        html += `
                            <div class="place-result" style="padding: 10px; border-bottom: 1px solid #ddd; cursor: pointer;"
                                 data-place-id="${place.place_id}"
                                 data-name="${place.name}"
                                 data-address="${place.formatted_address}">
                                <strong>${place.name}</strong><br>
                                ${place.formatted_address}<br>
                                ${place.rating ? `Rating: ${place.rating} (${place.user_ratings_total} reviews)` : 'No ratings yet'}
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    $('#search-results').html(html).removeClass('hidden');
                } else {
                    $('#search-results').html('<p>No results found</p>').removeClass('hidden');
                }
            });
        });

        $(document).on('click', '.place-result', function() {
            var $this = $(this);
            var placeId = $this.data('place-id');
            var name = $this.data('name');
            var address = $this.data('address');

            console.log('Place selected:', { placeId, name, address });

            // Update Google Place fields
            $('#google_place_id').val(placeId);
            $('#google_place_name').val(name);
            $('#google_place_address').val(address);

            $('#saved-name').text(name);
            $('#saved-address').text(address);
            $('#saved-place-id').text(placeId);

            // Auto-fill business details fields
            console.log('Attempting to fill address field:', address);
            $('#business_address').val(address);
            $('#business_phone').val(''); // Will be filled by Places Details API
            $('#business_website').val(''); // Will be filled by Places Details API

            // Get additional details using Place Details API
            console.log('Fetching additional details for place ID:', placeId);
            $.post(ajaxurl, {
                action: 'get_place_details',
                place_id: placeId,
                nonce: nonce
            }, function(response) {
                console.log('Place details response:', response);
                if (response.success && response.data) {
                    if (response.data.formatted_phone_number) {
                        console.log('Setting phone number:', response.data.formatted_phone_number);
                        $('#business_phone').val(response.data.formatted_phone_number);
                    }
                    if (response.data.website) {
                        console.log('Setting website:', response.data.website);
                        $('#business_website').val(response.data.website);
                    }
                }
            });

            $('#selected-place').removeClass('hidden');
            $('#remove-place').show();
            $('#search-results').addClass('hidden');

            // Debug: Check if fields were filled
            setTimeout(function() {
                console.log('Field values after update:', {
                    address: $('#business_address').val(),
                    phone: $('#business_phone').val(),
                    website: $('#business_website').val()
                });
            }, 1000);
        });

        $('#remove-place').on('click', function() {
            $('#google_place_id').val('');
            $('#google_place_name').val('');
            $('#google_place_address').val('');
            $('#selected-place').addClass('hidden');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * Save Google Business Profile meta box data
 */
function save_google_business_meta_box($post_id) {
    if (!isset($_POST['google_business_profile_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['google_business_profile_nonce'], 'google_business_profile_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('_google_place_id', '_google_place_name', '_google_place_address');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        } else {
            delete_post_meta($post_id, $field);
        }
    }
}
add_action('save_post_business', 'save_google_business_meta_box');

/**
 * Get Google Business Profile reviews for a place
 */
function get_google_place_reviews($business_name, $address) {
    // You'll need to replace this with your actual API key
    $api_key = get_option('google_places_api_key');
    if (!$api_key) {
        return false;
    }

    // First get the Place ID
    $search_query = urlencode($business_name . ' ' . $address);
    $place_search_url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$search_query}&inputtype=textquery&fields=place_id,rating,user_ratings_total&key={$api_key}";
    
    $response = wp_remote_get($place_search_url);
    if (is_wp_error($response)) {
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($data['candidates'][0])) {
        return false;
    }

    return array(
        'rating' => isset($data['candidates'][0]['rating']) ? $data['candidates'][0]['rating'] : 0,
        'total_reviews' => isset($data['candidates'][0]['user_ratings_total']) ? $data['candidates'][0]['user_ratings_total'] : 0,
        'place_id' => $data['candidates'][0]['place_id']
    );
}

/**
 * Add Google Places API key setting to WordPress admin
 */
function add_google_places_api_settings() {
    add_settings_section(
        'google_places_api_section',
        'Google Places API Settings',
        function() {
            echo '<p>Enter your Google Places API key here. You can get one from the <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console</a>.</p>';
        },
        'general'
    );

    add_settings_field(
        'google_places_api_key',
        'API Key',
        function() {
            $api_key = get_option('google_places_api_key');
            echo '<input type="text" name="google_places_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
        },
        'general',
        'google_places_api_section'
    );

    register_setting('general', 'google_places_api_key');
}
add_action('admin_init', 'add_google_places_api_settings');

// Save Business Meta Box Data
function save_business_meta_box_data($post_id) {
    // Check if nonce is set and valid
    if (!isset($_POST['business_details_nonce']) || !wp_verify_nonce($_POST['business_details_nonce'], 'business_details_nonce')) {
        return;
    }

    if (!isset($_POST['business_gallery_nonce']) || !wp_verify_nonce($_POST['business_gallery_nonce'], 'business_gallery_nonce')) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save fields
    $fields = array(
        'business_address' => '_business_address',
        'business_phone' => '_business_phone',
        'business_email' => '_business_email',
        'business_website' => '_business_website',
        'business_hours' => '_business_hours'
    );

    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }

    // Save featured status
    $featured = isset($_POST['business_featured']) ? 'yes' : 'no';
    update_post_meta($post_id, 'featured', $featured);

    // Save gallery images
    if (isset($_POST['business_gallery'])) {
        $gallery_images = array_map('absint', $_POST['business_gallery']);
        update_post_meta($post_id, '_business_gallery', $gallery_images);
    } else {
        delete_post_meta($post_id, '_business_gallery');
    }
}
add_action('save_post_business', 'save_business_meta_box_data');

/**
 * Get cached Google reviews for a business
 */
function get_cached_google_reviews($post_id) {
    $place_id = get_post_meta($post_id, '_google_place_id', true);
    if (!$place_id) {
        return false;
    }

    // Check for cached reviews
    $cache_key = 'google_reviews_' . $place_id;
    $cached_reviews = get_transient($cache_key);
    
    if ($cached_reviews !== false) {
        return $cached_reviews;
    }

    // If no cache, fetch fresh data
    $api_key = get_option('google_places_api_key');
    if (!$api_key) {
        return false;
    }

    $place_url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($place_id) . 
                 "&fields=rating,user_ratings_total&key=" . $api_key;
    
    $response = wp_remote_get($place_url);
    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!isset($data['result'])) {
        return false;
    }

    $reviews = array(
        'rating' => $data['result']['rating'] ?? 0,
        'total_reviews' => $data['result']['user_ratings_total'] ?? 0,
        'place_id' => $place_id
    );

    // Cache for 12 hours
    set_transient($cache_key, $reviews, 12 * HOUR_IN_SECONDS);

    return $reviews;
}

/**
 * Format business hours into a structured array
 */
function parse_business_hours($hours_string) {
    if (empty($hours_string)) {
        return array();
    }

    $days = array(
        'Monday', 'Tuesday', 'Wednesday', 'Thursday',
        'Friday', 'Saturday', 'Sunday'
    );
    
    $hours_array = array();
    $lines = explode("\n", $hours_string);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // Try to match "Day: HH:MM AM - HH:MM PM" format
        foreach ($days as $day) {
            if (stripos($line, $day) === 0) {
                $hours_array[$day] = trim(str_ireplace($day . ':', '', $line));
                break;
            }
        }
    }
    
    return $hours_array;
}

/**
 * Format hours for display
 */
function format_business_hours_display($hours_string) {
    $hours_array = parse_business_hours($hours_string);
    $days = array(
        'Monday', 'Tuesday', 'Wednesday', 'Thursday',
        'Friday', 'Saturday', 'Sunday'
    );
    
    $html = '<div class="business-hours grid grid-cols-1 gap-2">';
    
    foreach ($days as $day) {
        $is_today = (strtolower($day) === strtolower(date('l')));
        $hours = isset($hours_array[$day]) ? $hours_array[$day] : 'Closed';
        
        $html .= sprintf(
            '<div class="flex items-center justify-between py-2 %s">
                <span class="font-medium %s">%s</span>
                <span class="text-gray-600">%s</span>
            </div>',
            $is_today ? 'bg-blue-50 px-3 rounded' : '',
            $is_today ? 'text-blue-600' : 'text-gray-900',
            esc_html($day),
            esc_html($hours)
        );
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Get business details from Google Places API with fallback to local data
 */
function get_business_details($post_id) {
    $place_id = get_post_meta($post_id, '_google_place_id', true);
    $details = array(
        'address' => get_post_meta($post_id, '_business_address', true),
        'phone' => get_post_meta($post_id, '_business_phone', true),
        'website' => get_post_meta($post_id, '_business_website', true),
        'hours' => get_post_meta($post_id, '_business_hours', true),
        'email' => get_post_meta($post_id, '_business_email', true),
        'source' => 'local'
    );

    if (!empty($place_id)) {
        $cache_key = 'google_place_details_' . $place_id;
        $cached_details = get_transient($cache_key);

        if ($cached_details !== false) {
            return array_merge($details, $cached_details, array('source' => 'google'));
        }

        $api_key = get_option('google_places_api_key');
        if (empty($api_key)) {
            return $details;
        }

        $url = sprintf(
            'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=formatted_address,formatted_phone_number,website,opening_hours,url&key=%s',
            urlencode($place_id),
            $api_key
        );

        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            return $details;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data['status'] === 'OK' && !empty($data['result'])) {
            $result = $data['result'];
            $google_details = array();

            // Format address
            if (!empty($result['formatted_address'])) {
                $google_details['address'] = $result['formatted_address'];
            }

            // Format phone
            if (!empty($result['formatted_phone_number'])) {
                $google_details['phone'] = $result['formatted_phone_number'];
            }

            // Format website
            if (!empty($result['website'])) {
                $google_details['website'] = $result['website'];
            }

            // Format hours
            if (!empty($result['opening_hours']['periods'])) {
                $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                $hours_text = '';
                
                foreach ($result['opening_hours']['periods'] as $period) {
                    if (isset($period['open']) && isset($period['close'])) {
                        $day = $days[$period['open']['day']];
                        $open_time = date('g:i A', strtotime($period['open']['time']));
                        $close_time = date('g:i A', strtotime($period['close']['time']));
                        $hours_text .= "$day: $open_time - $close_time\n";
                    }
                }
                
                if (!empty($hours_text)) {
                    $google_details['hours'] = trim($hours_text);
                }
            }

            $google_details['source'] = 'google';
            set_transient($cache_key, $google_details, 12 * HOUR_IN_SECONDS);
            
            return array_merge($details, $google_details);
        }
    }

    return $details;
}

/**
 * Get detailed Google reviews for a business
 */
function get_google_place_detailed_reviews($place_id) {
    if (!$place_id) {
        return false;
    }

    // Check for cached detailed reviews
    $cache_key = 'google_detailed_reviews_' . $place_id;
    $cached_reviews = get_transient($cache_key);
    
    if ($cached_reviews !== false) {
        return $cached_reviews;
    }

    $api_key = get_option('google_places_api_key');
    if (!$api_key) {
        return false;
    }

    $place_url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($place_id) . 
                 "&fields=reviews&key=" . $api_key;
    
    $response = wp_remote_get($place_url);
    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (!isset($data['result']['reviews'])) {
        return false;
    }

    $reviews = array_filter($data['result']['reviews'], function($review) {
        return $review['rating'] >= 4;
    });

    // Sort by rating (highest first) and then by time (newest first)
    usort($reviews, function($a, $b) {
        if ($a['rating'] === $b['rating']) {
            return $b['time'] - $a['time'];
        }
        return $b['rating'] - $a['rating'];
    });

    // Take only the first 5 reviews
    $reviews = array_slice($reviews, 0, 5);

    // Cache for 12 hours
    set_transient($cache_key, $reviews, 12 * HOUR_IN_SECONDS);

    return $reviews;
}