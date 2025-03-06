<?php

function boilerplate_load_assets() {
  wp_enqueue_script('ourmainjs', get_theme_file_uri('/build/index.js'), array('wp-element', 'react-jsx-runtime'), '1.0', true);
  wp_enqueue_style('ourmaincss', get_theme_file_uri('/build/index.css'));
}

add_action('wp_enqueue_scripts', 'boilerplate_load_assets');

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

    // Output form fields
    ?>
    <div class="business-meta-fields">
        <p>
            <label for="business_address">Address:</label><br>
            <textarea id="business_address" name="business_address" rows="3" style="width: 100%;"><?php echo esc_textarea($address); ?></textarea>
        </p>
        <p>
            <label for="business_phone">Phone:</label><br>
            <input type="tel" id="business_phone" name="business_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="business_email">Email:</label><br>
            <input type="email" id="business_email" name="business_email" value="<?php echo esc_attr($email); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="business_website">Website:</label><br>
            <input type="url" id="business_website" name="business_website" value="<?php echo esc_url($website); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="business_hours">Business Hours:</label><br>
            <textarea id="business_hours" name="business_hours" rows="3" style="width: 100%;"><?php echo esc_textarea($hours); ?></textarea>
        </p>
        <p>
            <label>
                <input type="checkbox" name="business_featured" value="yes" <?php checked($featured, 'yes'); ?>>
                Featured Business
            </label>
        </p>
    </div>
    <?php
}

// Save Business Meta Box Data
function save_business_meta_box_data($post_id) {
    // Check if nonce is set and valid
    if (!isset($_POST['business_details_nonce']) || !wp_verify_nonce($_POST['business_details_nonce'], 'business_details_nonce')) {
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
}
add_action('save_post_business', 'save_business_meta_box_data');