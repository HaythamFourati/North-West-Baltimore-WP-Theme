<?php
/**
 * Template for displaying single business listings
 */

get_header(); ?>

<article <?php post_class(); ?>>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden">
                    <div class="business-slider h-[250px] sm:h-[350px] md:h-[400px] lg:h-[450px] xl:h-[500px] relative">
                        <div class="slider-track absolute top-0 left-0 w-full h-full flex">
                            <?php 
                            // Add last slide clone at the beginning
                            $last_slide = end($slides);
                            if ($last_slide['type'] === 'thumbnail') {
                                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'business-featured');
                                if ($featured_image) {
                                ?>
                                <div class="slide min-w-full h-full flex-shrink-0">
                                    <div class="business-featured-image w-full h-full flex items-center justify-center">
                                        <img src="<?php echo esc_url($featured_image[0]); ?>" 
                                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                                             class="max-w-full max-h-full object-contain" 
                                             loading="lazy">
                                    </div>
                                </div>
                                <?php 
                                }
                            } else {
                                $image = wp_get_attachment_image_src($last_slide['id'], 'business-gallery');
                                if ($image) {
                                ?>
                                <div class="slide min-w-full h-full flex-shrink-0">
                                    <div class="business-gallery-image-<?php echo esc_attr($last_slide['id']); ?> w-full h-full flex items-center justify-center">
                                        <img src="<?php echo esc_url($image[0]); ?>" 
                                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                                             class="max-w-full max-h-full object-contain" 
                                             loading="lazy">
                                    </div>
                                </div>
                                <?php 
                                }
                            }

                            // Main slides
                            foreach ($slides as $index => $slide) {
                                if ($slide['type'] === 'thumbnail') {
                                    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'business-featured');
                                    if ($featured_image) {
                                    ?>
                                    <div class="slide min-w-full h-full flex-shrink-0 <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <div class="business-featured-image w-full h-full flex items-center justify-center">
                                            <img src="<?php echo esc_url($featured_image[0]); ?>" 
                                                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                                                 class="max-w-full max-h-full object-contain" 
                                                 loading="lazy">
                                        </div>
                                    </div>
                                    <?php 
                                    }
                                } else {
                                    $image = wp_get_attachment_image_src($slide['id'], 'business-gallery');
                                    if ($image) {
                                    ?>
                                    <div class="slide min-w-full h-full flex-shrink-0 <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <div class="business-gallery-image-<?php echo esc_attr($slide['id']); ?> w-full h-full flex items-center justify-center">
                                            <img src="<?php echo esc_url($image[0]); ?>" 
                                                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                                                 class="max-w-full max-h-full object-contain" 
                                                 loading="lazy">
                                        </div>
                                    </div>
                                    <?php 
                                    }
                                }
                            }

                            // Add first slide clone at the end
                            $first_slide = reset($slides);
                            if ($first_slide['type'] === 'thumbnail') {
                                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'business-featured');
                                if ($featured_image) {
                                ?>
                                <div class="slide min-w-full h-full flex-shrink-0">
                                    <div class="business-featured-image w-full h-full flex items-center justify-center">
                                        <img src="<?php echo esc_url($featured_image[0]); ?>" 
                                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                                             class="max-w-full max-h-full object-contain" 
                                             loading="lazy">
                                    </div>
                                </div>
                                <?php 
                                }
                            } else {
                                $image = wp_get_attachment_image_src($first_slide['id'], 'business-gallery');
                                if ($image) {
                                ?>
                                <div class="slide min-w-full h-full flex-shrink-0">
                                    <div class="business-gallery-image-<?php echo esc_attr($first_slide['id']); ?> w-full h-full flex items-center justify-center">
                                        <img src="<?php echo esc_url($image[0]); ?>" 
                                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                                             class="max-w-full max-h-full object-contain" 
                                             loading="lazy">
                                    </div>
                                </div>
                                <?php 
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative h-[250px] sm:h-[350px] md:h-[400px] lg:h-[450px] xl:h-[500px]">
                    <?php 
                    if (has_post_thumbnail()) {
                        $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'business-featured');
                        if ($featured_image) {
                            $featured_class = 'business-featured-image';
                        ?>
                        <div class="<?php echo esc_attr($featured_class); ?> w-full h-full flex items-center justify-center">
                            <img src="<?php echo esc_url($featured_image[0]); ?>" 
                                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                                 class="max-w-full max-h-full object-contain" 
                                 loading="lazy">
                        </div>
                        <?php 
                        }
                    } else if (!empty($gallery_images) && is_array($gallery_images)) {
                        $image = wp_get_attachment_image_src($gallery_images[0], 'business-gallery');
                        if ($image) {
                        ?>
                        <div class="slider-slide business-gallery-image-<?php echo esc_attr($gallery_images[0]); ?> w-full h-full flex-shrink-0 bg-gray-100 flex items-center justify-center">
                            <img src="<?php echo esc_url($image[0]); ?>" 
                                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                                 class="max-w-full max-h-full object-contain" 
                                 loading="lazy">
                        </div>
                        <?php 
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
    
    <!-- Business Details -->
    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <header class="mb-8">
            <div class="block md:flex md:space-x-8">
                <div class="w-full md:w-3/5 mb-6 md:mb-0">
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
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?php echo nl2br(esc_html($address)); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($phone) : ?>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:<?php echo esc_attr($phone); ?>" class="text-blue-600 hover:text-blue-700">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="text-blue-600 hover:text-blue-700">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if ($address) : ?>
                        <div id="business-map" class="w-full h-80 rounded-lg mt-4 shadow-sm"></div>
                        <?php 
                            $maps_api_key = get_option('google_places_api_key');
                            if ($maps_api_key) : 
                        ?>
                            <script>
                                function initMap() {
                                    const geocoder = new google.maps.Geocoder();
                                    const mapOptions = {
                                        zoom: 15,
                                        mapTypeControl: false,
                                        streetViewControl: false,
                                        styles: [
                                            {
                                                featureType: "poi",
                                                elementType: "labels",
                                                stylers: [{ visibility: "off" }]
                                            }
                                        ]
                                    };
                                    
                                    const map = new google.maps.Map(document.getElementById("business-map"), mapOptions);
                                    
                                    geocoder.geocode({ address: <?php echo json_encode($address); ?> }, (results, status) => {
                                        if (status === "OK") {
                                            const location = results[0].geometry.location;
                                            map.setCenter(location);
                                            
                                            new google.maps.Marker({
                                                map: map,
                                                position: location,
                                                animation: google.maps.Animation.DROP
                                            });
                                        }
                                    });
                                }
                            </script>
                            <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($maps_api_key); ?>&callback=initMap" async defer></script>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="w-full md:w-2/5">
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-end gap-4 mb-4">
                            <?php
                            $website = get_post_meta(get_the_ID(), '_business_website', true);
                            $phone = get_post_meta(get_the_ID(), '_business_phone', true);
                            ?>
                            <a href="tel:<?php echo esc_attr($phone); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Call Now
                            </a>
                            <?php if ($website) : ?>
                            <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Visit Website
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <?php
                        // Get social media links
                        $facebook = get_post_meta(get_the_ID(), '_business_facebook', true);
                        $linkedin = get_post_meta(get_the_ID(), '_business_linkedin', true);
                        $youtube = get_post_meta(get_the_ID(), '_business_youtube', true);
                        $twitter = get_post_meta(get_the_ID(), '_business_twitter', true);
                        $instagram = get_post_meta(get_the_ID(), '_business_instagram', true);

                        if ($facebook || $linkedin || $youtube || $twitter || $instagram) :
                        ?>
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden w-full md:w-4/5 md:ml-auto mb-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-2.5 py-1.5 border-b border-gray-200">
                                <h2 class="text-xs font-semibold text-gray-900 flex items-center">
                                    <svg class="w-3.5 h-3.5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    Connect With Us
                                </h2>
                            </div>
                            <div class="px-4 py-3 flex flex-wrap gap-3 justify-center">
                                <?php if ($facebook) : ?>
                                <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white transition-all" title="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.77,7.46H14.5v-1.9c0-.9.6-1.1,1-1.1h3V.5h-4.33C10.24.5,9.5,3.44,9.5,5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4Z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                <?php if ($twitter) : ?>
                                <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-400 hover:text-white transition-all" title="X (Twitter)">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                <?php if ($instagram) : ?>
                                <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-[#E1306C] hover:text-white transition-all" title="Instagram">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.228-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                <?php if ($linkedin) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-700 hover:text-white transition-all" title="LinkedIn">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                <?php if ($youtube) : ?>
                                <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-red-600 hover:text-white transition-all" title="YouTube">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.5 6.186C0 8.07 0 12 0 12s0 3.93.5 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.5-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php 
                        // Get business hours
                        $business_details = get_business_details(get_the_ID());
                        $hours = $business_details['hours'];
                        if ($hours) : ?>
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden w-full md:w-4/5 md:ml-auto">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-2.5 py-1.5 border-b border-gray-200">
                                    <h2 class="text-xs font-semibold text-gray-900 flex items-center">
                                        <svg class="w-3.5 h-3.5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Business Hours
                                        <?php if ($business_details['source'] === 'google') : ?>
                                            <span class="ml-1 inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-2 h-2 mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0z"/>
                                                    <path fill="#fff" d="M9.5 17l-5-5 1.41-1.41L9.5 14.17l8.09-8.09L19 7.5 9.5 17z"/>
                                                </svg>
                                                Verified
                                            </span>
                                        <?php endif; ?>
                                    </h2>
                                </div>
                                <div class="px-2.5 py-1.5 text-xs">
                                    <?php echo format_business_hours_display($hours); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 gap-8">
            <div>
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
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
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
<?php endif; ?>
