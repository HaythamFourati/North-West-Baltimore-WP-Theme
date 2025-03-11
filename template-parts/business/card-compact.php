<?php
/**
 * Template part for displaying business cards in a compact format
 */

$place_id = get_post_meta(get_the_ID(), '_google_place_id', true);
$place_name = get_post_meta(get_the_ID(), '_google_place_name', true);
$place_address = get_post_meta(get_the_ID(), '_google_place_address', true);
$business_details = get_business_details(get_the_ID());

// Get featured status
$is_featured = get_post_meta(get_the_ID(), 'featured', true) === 'yes';
?>

<div class="relative bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer transform hover:-translate-y-1 group overflow-hidden <?php echo $is_featured ? 'ring-2 ring-yellow-400' : ''; ?>" 
     data-place-id="<?php echo esc_attr($place_id); ?>">
    
    <?php if ($is_featured) : ?>
        <div class="absolute top-3 right-3 z-10">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Featured
            </span>
        </div>
    <?php endif; ?>

    <div class="flex items-start p-4">
        <?php if (has_post_thumbnail()) : ?>
            <div class="flex-shrink-0 relative">
                <div class="w-20 h-20 rounded-lg overflow-hidden transform group-hover:scale-105 transition-transform duration-300">
                    <?php echo get_the_post_thumbnail(null, 'thumbnail', ['class' => 'w-full h-full object-cover']); ?>
                </div>
                <?php if (!empty($business_details['rating'])) : ?>
                    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-white rounded-full px-2 py-1 shadow-md">
                        <div class="flex items-center space-x-1">
                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xs font-medium text-gray-600"><?php echo number_format($business_details['rating'], 1); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="flex-1 min-w-0 ml-4">
            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-1">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="hover:text-blue-600">
                    <?php echo esc_html(get_the_title()); ?>
                </a>
            </h3>
            
            <?php if ($place_address) : ?>
                <p class="mt-1 text-sm text-gray-600 line-clamp-2 group-hover:text-gray-900 transition-colors duration-200">
                    <svg class="inline-block w-4 h-4 mr-1 -mt-1 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <?php echo esc_html($place_address); ?>
                </p>
            <?php endif; ?>

            <div class="mt-3 flex flex-wrap gap-1.5">
                <?php
                // Get business categories
                $categories = get_the_terms(get_the_ID(), 'business_category');
                if ($categories && !is_wp_error($categories)) :
                    foreach ($categories as $category) : ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10 group-hover:bg-blue-100 transition-colors duration-200">
                            <svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <?php echo esc_html($category->name); ?>
                        </span>
                    <?php endforeach;
                endif;

                // Get business city
                $cities = get_the_terms(get_the_ID(), 'business_city');
                if ($cities && !is_wp_error($cities)) :
                    foreach ($cities as $city) : ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-700/10 group-hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-3 h-3 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <?php echo esc_html($city->name); ?>
                        </span>
                    <?php endforeach;
                endif; ?>
            </div>

            <?php if (!empty($business_details['rating'])) : ?>
                <div class="mt-2 flex items-center">
                    <div class="flex items-center">
                        <?php
                        $rating = $business_details['rating'];
                        $full_stars = floor($rating);
                        $half_star = ($rating - $full_stars) >= 0.5;
                        
                        // Full stars
                        for ($i = 0; $i < $full_stars; $i++) : ?>
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endfor;

                        // Half star
                        if ($half_star) : ?>
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-path="inset(0 50% 0 0)"/>
                            </svg>
                        <?php endif;

                        // Empty stars
                        for ($i = 0; $i < (5 - $full_stars - ($half_star ? 1 : 0)); $i++) : ?>
                            <svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <span class="ml-1.5 text-xs text-gray-600">
                        <?php echo number_format($business_details['total_reviews']); ?> reviews
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
