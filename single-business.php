<?php
/**
 * Template for displaying single business listings
 */

get_header(); ?>

<article <?php post_class('bg-white'); ?>>
    <?php 
    $gallery_images = get_post_meta(get_the_ID(), '_business_gallery', true);
    $has_multiple_images = (!empty($gallery_images) && is_array($gallery_images)) || (has_post_thumbnail() && !empty($gallery_images) && is_array($gallery_images));
    
    if (has_post_thumbnail() || (!empty($gallery_images) && is_array($gallery_images))) : 
        if ($has_multiple_images) {
            // Prepare slides array
            $slides = array();
            if (has_post_thumbnail()) {
                $slides[] = array(
                    'type' => 'thumbnail',
                    'id' => get_post_thumbnail_id()
                );
            }
            if (!empty($gallery_images) && is_array($gallery_images)) {
                foreach ($gallery_images as $image_id) {
                    $slides[] = array(
                        'type' => 'gallery',
                        'id' => $image_id
                    );
                }
            }
    ?>
        <div class="w-full bg-gray-100">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden">
                    <div class="business-slider h-[250px] sm:h-[350px] md:h-[400px] lg:h-[450px] xl:h-[500px] relative">
                        <div class="slider-track absolute top-0 left-0 w-full h-full flex">
                            <?php 
                            // Add last slide clone at the beginning
                            $last_slide = end($slides);
                            if ($last_slide['type'] === 'thumbnail') {
                                echo '<div class="slide min-w-full h-full flex items-center justify-center">';
                                echo get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover']);
                                echo '</div>';
                            } else {
                                $image_url = wp_get_attachment_image_url($last_slide['id'], 'large');
                                $image_alt = get_post_meta($last_slide['id'], '_wp_attachment_image_alt', true);
                                if ($image_url) {
                                    echo '<div class="slide min-w-full h-full flex items-center justify-center">';
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="w-full h-full object-cover">';
                                    echo '</div>';
                                }
                            }

                            // Original slides
                            foreach ($slides as $slide) {
                                echo '<div class="slide min-w-full h-full flex items-center justify-center">';
                                if ($slide['type'] === 'thumbnail') {
                                    echo get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover']);
                                } else {
                                    $image_url = wp_get_attachment_image_url($slide['id'], 'large');
                                    $image_alt = get_post_meta($slide['id'], '_wp_attachment_image_alt', true);
                                    if ($image_url) {
                                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="w-full h-full object-cover">';
                                    }
                                }
                                echo '</div>';
                            }

                            // Add first slide clone at the end
                            $first_slide = reset($slides);
                            if ($first_slide['type'] === 'thumbnail') {
                                echo '<div class="slide min-w-full h-full flex items-center justify-center">';
                                echo get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover']);
                                echo '</div>';
                            } else {
                                $image_url = wp_get_attachment_image_url($first_slide['id'], 'large');
                                $image_alt = get_post_meta($first_slide['id'], '_wp_attachment_image_alt', true);
                                if ($image_url) {
                                    echo '<div class="slide min-w-full h-full flex items-center justify-center">';
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="w-full h-full object-cover">';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- Navigation Arrows -->
                    <button class="slider-nav prev absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 bg-black/70 hover:bg-black text-white rounded-full w-10 h-10 flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all z-20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button class="slider-nav next absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 bg-black/70 hover:bg-black text-white rounded-full w-10 h-10 flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all z-20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <!-- Slider Dots -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
                    </div>
                    <?php if (get_post_meta(get_the_ID(), 'featured', true) === 'yes') : ?>
                        <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Featured Business
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php 
        } else { 
            // Show single image
    ?>
        <div class="w-full bg-gray-100">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative h-[250px] sm:h-[350px] md:h-[400px] lg:h-[450px] xl:h-[500px]">
                    <?php 
                    if (has_post_thumbnail()) {
                        echo get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover']);
                    } else if (!empty($gallery_images) && is_array($gallery_images)) {
                        $image_url = wp_get_attachment_image_url($gallery_images[0], 'large');
                        $image_alt = get_post_meta($gallery_images[0], '_wp_attachment_image_alt', true);
                        if ($image_url) {
                            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="w-full h-full object-cover">';
                        }
                    }
                    ?>
                    <?php if (get_post_meta(get_the_ID(), 'featured', true) === 'yes') : ?>
                        <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Featured Business
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php 
        }
    endif; ?>

    <div class="max-w-6xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <header class="mb-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h1>
                    <?php
                    $categories = get_the_terms(get_the_ID(), 'business_category');
                    $city = get_the_terms(get_the_ID(), 'business_city');
                    $address = get_post_meta(get_the_ID(), '_business_address', true);
                    $phone = get_post_meta(get_the_ID(), '_business_phone', true);
                    $email = get_post_meta(get_the_ID(), '_business_email', true);
                    ?>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php if ($categories && !is_wp_error($categories)) : 
                            foreach ($categories as $category) : ?>
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach;
                        endif; ?>
                        <?php if ($city && !is_wp_error($city)) : 
                            foreach ($city as $location) : ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <?php echo esc_html($location->name); ?>
                                </span>
                            <?php endforeach;
                        endif; ?>
                        
                        <?php 
                        // Get Google Reviews using stored Place ID
                        $place_id = get_post_meta(get_the_ID(), '_google_place_id', true);
                        
                        if ($place_id) {
                            $api_key = get_option('google_places_api_key');
                            $place_url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($place_id) . 
                                       "&fields=rating,user_ratings_total&key=" . $api_key;
                            
                            $response = wp_remote_get($place_url);
                            $google_reviews = false;
                            
                            if (!is_wp_error($response)) {
                                $data = json_decode(wp_remote_retrieve_body($response), true);
                                if (isset($data['result'])) {
                                    $google_reviews = array(
                                        'rating' => $data['result']['rating'] ?? 0,
                                        'total_reviews' => $data['result']['user_ratings_total'] ?? 0,
                                        'place_id' => $place_id
                                    );
                                }
                            }
                        
                            if ($google_reviews && $google_reviews['rating'] > 0) : ?>
                                <a href="https://search.google.com/local/reviews?placeid=<?php echo esc_attr($google_reviews['place_id']); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    <?php 
                                    echo sprintf(
                                        '%s (%d %s)', 
                                        number_format_i18n($google_reviews['rating'], 1), 
                                        $google_reviews['total_reviews'],
                                        _n('review', 'reviews', $google_reviews['total_reviews'], 'north-west-baltimore')
                                    ); 
                                    ?>
                                </a>
                            <?php endif;
                        } ?>
                    </div>
                    <?php if ($address || $phone || $email) : ?>
                    <div class="space-y-2">
                        <?php if ($address) : ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?php echo nl2br(esc_html($address)); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($phone) : ?>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:<?php echo esc_attr($phone); ?>" class="text-blue-600 hover:text-blue-700">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="text-blue-600 hover:text-blue-700">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="flex gap-3">
                    <?php
                    $website = get_post_meta(get_the_ID(), '_business_website', true);
                    
                    if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr($phone); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call Now
                        </a>
                    <?php endif;
                    
                    if ($website) : ?>
                        <a href="<?php echo esc_url($website); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Visit Website
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="prose max-w-none">
                    <?php 
                    the_content();
                    ?>
                </div>

                <?php
                $place_id = get_post_meta(get_the_ID(), '_google_place_id', true);
                $high_rated_reviews = get_google_place_detailed_reviews($place_id);
                
                if ($high_rated_reviews && !empty($high_rated_reviews)) : ?>
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <svg class="w-6 h-6 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Top Reviews
                        </h2>

                        <div class="grid gap-6">
                            <?php foreach ($high_rated_reviews as $review) : ?>
                                <div class="bg-white rounded-lg shadow p-6">
                                    <div class="flex items-start">
                                        <?php if (isset($review['profile_photo_url'])) : ?>
                                            <img src="<?php echo esc_url($review['profile_photo_url']); ?>" 
                                                 alt="<?php echo esc_attr($review['author_name']); ?>" 
                                                 class="w-10 h-10 rounded-full mr-4">
                                        <?php else : ?>
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                                <span class="text-blue-600 font-medium text-lg">
                                                    <?php echo esc_html(substr($review['author_name'], 0, 1)); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    <?php echo esc_html($review['author_name']); ?>
                                                </h3>
                                                <a href="<?php echo esc_url($review['author_url']); ?>" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer" 
                                                   class="text-sm text-blue-600 hover:text-blue-800">
                                                    View Profile
                                                </a>
                                            </div>

                                            <div class="flex items-center mt-1">
                                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                                    <svg class="w-5 h-5 <?php echo $i < $review['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" 
                                                         fill="currentColor" 
                                                         viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                <?php endfor; ?>
                                                <span class="ml-2 text-sm text-gray-600">
                                                    <?php 
                                                    error_log('Review time format: ' . print_r($review['time'], true));
                                                    $timestamp = strtotime($review['time']);
                                                    error_log('Parsed timestamp: ' . print_r($timestamp, true));
                                                    
                                                    // Check if the timestamp is a Unix timestamp (integer)
                                                    if (is_numeric($review['time'])) {
                                                        $timestamp = intval($review['time']);
                                                    } else {
                                                        $timestamp = strtotime($review['time']);
                                                    }
                                                    
                                                    if ($timestamp && $timestamp > 0) {
                                                        echo human_time_diff($timestamp) . ' ago';
                                                    } else {
                                                        echo '(date unavailable)';
                                                    }
                                                    ?>
                                                </span>
                                            </div>

                                            <div class="mt-3 text-gray-700">
                                                <?php echo esc_html($review['text']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <?php 
                $business_details = get_business_details(get_the_ID());
                $hours = $business_details['hours'];
                if ($hours) : ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Business Hours
                                <?php if ($business_details['source'] === 'google') : ?>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0z"/>
                                            <path fill="#fff" d="M9.5 17l-5-5 1.41-1.41L9.5 14.17l8.09-8.09L19 7.5 9.5 17z"/>
                                        </svg>
                                        Google Verified
                                    </span>
                                <?php endif; ?>
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <?php echo format_business_hours_display($hours); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
        // Get related businesses in the same category
        $current_categories = wp_get_post_terms(get_the_ID(), 'business_category', array('fields' => 'ids'));
        if ($current_categories && !is_wp_error($current_categories)) :
            $related_args = array(
                'post_type' => 'business',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'business_category',
                        'field' => 'id',
                        'terms' => $current_categories
                    )
                )
            );
            $related_query = new WP_Query($related_args);

            if ($related_query->have_posts()) : ?>
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Similar Businesses</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php while ($related_query->have_posts()) : $related_query->the_post();
                            get_template_part('template-parts/business/card');
                        endwhile; ?>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
            endif;
        endif; ?>
    </div>
</article>

<?php get_footer(); ?>
