<?php
/**
 * The front page template file
 *
 * This is the template for the site's front page
 */

get_header(); ?>

<!-- Google Fonts for Montserrat and Open Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- DotLottie Player Script -->
<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

<!-- Custom Styles -->
<link rel="stylesheet" href="<?php echo get_theme_file_uri('./src/custom-styles.css'); ?>">

<!-- Hero Section with Search -->
<div class="relative">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="<?php echo get_theme_file_uri('./src/assets/Hero-banner-collage.jpg'); ?>" alt="Baltimore Cityscape">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-blue-800/75"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto py-20 px-4 sm:py-24 sm:px-6 lg:px-8">
        <!-- Hero Content -->
        <div class="max-w-5xl mx-auto text-center">
            <span class="inline-block px-3 py-1 text-sm font-medium text-blue-100 bg-yellow-700 rounded-full mb-5 backdrop-blur-sm open-sans">Northwest Baltimore's Premier Business Directory</span>
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl montserrat leading-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">The Best of Northwest Baltimore</span>
            </h1>
            <h3 class="mt-4 text-2xl font-semibold text-blue-100 sm:text-3xl montserrat tracking-wide">
                Shop Local, Live Local, Love ❤️ Local
            </h3>
            <p class="mt-4 text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed open-sans">
                Supporting the Pikesville, Owings Mills, Reisterstown, Glyndon, 
                <br/>Finksburg, Westminster & Surrounding Areas
            </p>
        </div>
        
        <!-- Search & Filter Bar -->
        <div class="mt-10 max-w-2xl mx-auto">
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl shadow-xl">
                <form action="<?php echo esc_url(get_post_type_archive_link('business')); ?>" method="get" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="business_category" class="block text-sm font-medium text-blue-100 mb-1 ml-1 open-sans">Category</label>
                        <select id="business_category" name="business_category" class="w-full px-4 py-3 text-base bg-white bg-opacity-90 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200 cursor-pointer open-sans">
                            <option value="">All Categories</option>
                            <?php
                            // Get only categories that have businesses
                            $categories = get_terms(array(
                                'taxonomy' => 'business_category',
                                'hide_empty' => true,
                                'orderby' => 'name',
                                'order' => 'ASC'
                            ));
                            if (!empty($categories) && !is_wp_error($categories)) {
                                foreach ($categories as $category) {
                                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="business_city" class="block text-sm font-medium text-blue-100 mb-1 ml-1 open-sans">Location</label>
                        <select id="business_city" name="business_city" class="w-full px-4 py-3 text-base bg-white bg-opacity-90 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200 cursor-pointer open-sans">
                            <option value="">All Cities</option>
                            <?php
                            // Get cities from taxonomy
                            $cities = get_terms(array(
                                'taxonomy' => 'business_city',
                                'hide_empty' => true,
                                'orderby' => 'name',
                                'order' => 'ASC'
                            ));
                            
                            if (!empty($cities) && !is_wp_error($cities)) {
                                foreach ($cities as $city) {
                                    echo '<option value="' . esc_attr($city->slug) . '">' . esc_html($city->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="sm:self-end">
                        <button type="submit" class="w-full h-[46px] sm:w-auto bg-yellow-500 px-6 py-3 text-white font-semibold hover:bg-yellow-600 transition duration-200 rounded-lg shadow-lg flex items-center justify-center cursor-pointer montserrat">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Browse Directory
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="<?php echo esc_url(home_url('/add-business')); ?>" class="inline-flex items-center px-5 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-lg transition-all duration-200 montserrat">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Your Business
            </a>
            <a href="<?php echo esc_url(get_post_type_archive_link('business')); ?>" class="inline-flex items-center px-5 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-lg transition-all duration-200 montserrat">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                View All Businesses
            </a>
            <a href="#featured" class="inline-flex items-center px-5 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-lg transition-all duration-200 montserrat">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Featured Businesses
            </a>
        </div>
    </div>
</div>

<!-- Business Categories Section -->
<div class="bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl montserrat">Explore Business Categories</h2>
            <p class="mt-4 text-lg text-gray-600 open-sans">Find exactly what you're looking for in our diverse business community</p>
        </div>
        
        <?php
        $categories = get_terms(array(
            'taxonomy' => 'business_category',
            'hide_empty' => false,
        ));

        if (!empty($categories) && !is_wp_error($categories)) : ?>
            <div class="mt-12 flex flex-wrap justify-center gap-5">
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                       class="group">
                        <div class="relative bg-white hover:bg-blue-50 transform transition duration-300 rounded-xl px-6 py-4 text-center min-w-[150px] shadow-md hover:shadow-lg border border-gray-100 hover:border-blue-200">
                            <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 transition-colors montserrat"><?php echo esc_html($category->name); ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Featured Businesses Section -->
<div id="featured" class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-block px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded-full mb-3 montserrat">Spotlight</span>
            <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl montserrat">Featured Businesses</h2>
            <p class="mt-4 text-lg text-gray-600 open-sans">Discover our most popular and highly-rated local establishments</p>
        </div>
        
        <?php
        $featured_businesses = new WP_Query(array(
            'post_type' => 'business',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'featured',
                    'value' => 'yes',
                    'compare' => '=',
                ),
            ),
        ));

        if ($featured_businesses->have_posts()) : ?>
            <div class="mt-16 grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                <?php while ($featured_businesses->have_posts()) : $featured_businesses->the_post(); 
                    get_template_part('template-parts/business/card');
                endwhile; ?>
            </div>
            <div class="mt-16 text-center">
                <a href="<?php echo esc_url(get_post_type_archive_link('business')); ?>" 
                   class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg transition-all duration-300 montserrat">
                    View All Businesses
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php 
        wp_reset_postdata();
        endif; ?>
    </div>
</div>

<!-- Latest Additions Section -->
<div class="bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-3 py-1 text-sm font-medium text-emerald-700 bg-emerald-100 rounded-full mb-3 montserrat">New Arrivals</span>
            <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl montserrat">Latest Additions</h2>
            <p class="mt-4 text-lg text-gray-600 open-sans">Discover the newest businesses that have joined our community</p>
        </div>
        
        <div class="relative">
            <!-- Decorative elements -->
            <div class="hidden lg:block absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full opacity-50 mix-blend-multiply"></div>
            <div class="hidden lg:block absolute -bottom-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full opacity-50 mix-blend-multiply"></div>
            
            <div class="relative">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Map -->
                    <div class="lg:col-span-7 order-2 lg:order-1">
                        <div class="bg-white rounded-2xl overflow-hidden shadow-xl h-full border border-gray-100">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold montserrat">Business Locations</h3>
                                </div>
                                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Interactive Map</span>
                            </div>
                            <div id="business-map" class="w-full h-[562px]"></div>
                        </div>
                    </div>
                    
                    <!-- Business Listings -->
                    <div class="lg:col-span-5 order-1 lg:order-2">
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <h3 class="text-lg font-semibold montserrat">Recently Added</h3>
                                    </div>
                                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full"><?php echo wp_count_posts('business')->publish; ?> Total Businesses</span>
                                </div>
                                <p class="text-sm text-emerald-50 mt-1 open-sans">Click on a business to see its location on the map</p>
                            </div>
                            
                            <div class="overflow-y-auto max-h-[480px] custom-scrollbar divide-y divide-gray-100">
                                <?php
                                $latest_businesses = new WP_Query(array(
                                    'post_type' => 'business',
                                    'posts_per_page' => 8,
                                    'orderby' => 'date',
                                    'order' => 'DESC'
                                ));

                                if ($latest_businesses->have_posts()) :
                                    while ($latest_businesses->have_posts()) : 
                                        $latest_businesses->the_post();
                                        $place_id = get_post_meta(get_the_ID(), '_google_place_id', true);
                                        $place_name = get_post_meta(get_the_ID(), '_google_place_name', true);
                                        $place_address = get_post_meta(get_the_ID(), '_google_place_address', true);
                                        get_template_part('template-parts/business/card-compact');
                                    endwhile;
                                else: ?>
                                    <div class="p-6 text-center">
                                        <p class="text-gray-500 open-sans">No new businesses added recently.</p>
                                    </div>
                                <?php
                                endif;
                                wp_reset_postdata(); 
                                ?>
                            </div>
                            
                            <div class="bg-gray-50 p-4 border-t border-gray-100">
                                <a href="<?php echo esc_url(get_post_type_archive_link('business')); ?>" 
                                   class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition-colors duration-200 montserrat">
                                    View All Businesses
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="pattern" width="100" height="100" patternUnits="userSpaceOnUse">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="white" stroke-width="2"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#pattern)"/>
        </svg>
    </div>
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8 relative z-10">
        <div class="lg:grid lg:grid-cols-2 lg:gap-8 items-center">
            <div>
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl montserrat">
                    <span class="block text-5xl">Own a Business?</span>
                    <span class="block text-blue-100 mt-2">Join our growing community today.</span>
                </h2>
                <p class="mt-4 text-lg text-blue-100 max-w-md open-sans">
                    Get discovered by local customers and become part of Northwest Baltimore's premier business directory.
                </p>
                <div class="mt-8">
                    <a href="<?php echo esc_url(home_url('/add-business')); ?>" 
                       class="inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 shadow-lg hover:shadow-xl transition-all duration-300 montserrat">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        List Your Business
                    </a>
                </div>
            </div>
            <div class="lg:mt-0 hidden lg:block">
                <div class="transform rotate-2 hover:rotate-0 transition-transform duration-300">
                    <div class="flex items-center justify-center">
                        <div class="w-120 h-120 overflow-hidden">
                            <dotlottie-player
                              src="https://lottie.host/4feb7fb7-921b-42f8-8cd1-1478b7dcd9ea/8M1RSyMX4K.json"
                              background="transparent"
                              speed="1"
                              style="width: 100%; height: 100%"
                              loop
                              autoplay
                            ></dotlottie-player>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps Integration -->
<script>
// Define the businesses data for the map
const businesses = <?php
    $map_businesses = array();
    if ($latest_businesses->have_posts()) : while ($latest_businesses->have_posts()) : $latest_businesses->the_post();
        $place_id = get_post_meta(get_the_ID(), '_google_place_id', true);
        $place_name = get_post_meta(get_the_ID(), '_google_place_name', true);
        $place_address = get_post_meta(get_the_ID(), '_google_place_address', true);
        if ($place_id && $place_name && $place_address) {
            $map_businesses[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'place_id' => $place_id,
                'name' => $place_name,
                'address' => $place_address,
                'url' => get_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
            );
        }
    endwhile; endif;
    wp_reset_postdata();
    echo json_encode($map_businesses);
?>;
</script>
<script src="<?php echo get_theme_file_uri('./src/scripts/google-maps.js'); ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr(get_option('google_places_api_key')); ?>&libraries=places&callback=initMap" async defer></script>

<?php get_footer(); ?>
