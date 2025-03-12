<?php
/**
 * The front page template file
 *
 * This is the template for the site's front page
 */

get_header(); ?>

<!-- Hero Section with Search -->
<div class="relative">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="<?php echo get_theme_file_uri('./src/assets/Hero-banner-collage.jpg'); ?>" alt="Baltimore Cityscape">
        <div class="absolute inset-0 bg-blue-900 opacity-75"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl text-center">
        The Best of Northwest Baltimore:<br/>
        </h1>
        <h3 class="text-2xl font-bold text-white sm:text-5xl lg:text-5xl text-center">
        Shop Local, Live Local, Love ❤️ Local
        </h3>
        <p class="mt-6 max-w-3xl mx-auto text-xl text-blue-100 text-center">
        Supporting the Pikesville, Owings Mills, Reisterstown, <br/>Glyndon, Finksburg, Westminster & Surrounding Areas
        </p>
        
        <!-- Filter Bar -->
        <div class="mt-10 max-w-2xl mx-auto">
            <form action="<?php echo esc_url(get_post_type_archive_link('business')); ?>" method="get" class="flex flex-col sm:flex-row gap-4 justify-center">
                <div class="flex-1">
                    <select name="business_category" class="w-full px-6 py-4 text-lg bg-white rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
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
                    <select name="business_city" class="w-full px-6 py-4 text-lg bg-white rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
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
                <button type="submit" class="w-full sm:w-auto bg-yellow-500 px-8 py-4 text-white font-semibold hover:bg-yellow-600 transition duration-200 rounded-lg shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Directory
                </button>
            </form>
        </div>
    </div>
</div>


<!-- Business Categories Section -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Explore Business Categories</h2>
            <p class="mt-4 text-lg text-gray-600">Find exactly what you're looking for in our diverse business community</p>
        </div>
        
        <?php
        $categories = get_terms(array(
            'taxonomy' => 'business_category',
            'hide_empty' => false,
        ));

        if (!empty($categories) && !is_wp_error($categories)) : ?>
            <div class="mt-12 flex flex-wrap justify-center gap-4">
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                       class="group">
                        <div class="relative bg-blue-600 hover:bg-blue-700 transform transition duration-300 rounded-full px-6 py-3 text-center min-w-[150px] shadow-lg">
                            <h3 class="text-lg font-semibold text-white"><?php echo esc_html($category->name); ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Featured Businesses Section -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Featured Businesses</h2>
            <p class="mt-4 text-lg text-gray-600">Discover our most popular and highly-rated local establishments</p>
        </div>
        
        <?php
        $featured_businesses = new WP_Query(array(
            'post_type' => 'business',
            'posts_per_page' => 3,
            'meta_query' => array(
                array(
                    'key' => 'featured',
                    'value' => 'yes',
                    'compare' => '=',
                ),
            ),
        ));

        if ($featured_businesses->have_posts()) : ?>
            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <?php while ($featured_businesses->have_posts()) : $featured_businesses->the_post(); 
                    get_template_part('template-parts/business/card');
                endwhile; ?>
            </div>
            <div class="mt-12 text-center">
                <a href="<?php echo esc_url(get_post_type_archive_link('business')); ?>" 
                   class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
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
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Latest Additions</h2>
            <p class="mt-4 text-lg text-gray-600">Check out the newest businesses in our community</p>
        </div>
        
        <div class="mt-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Map -->
                <div class="lg:col-span-2 rounded-lg overflow-hidden shadow-lg h-[600px]">
                    <div id="business-map" class="w-full h-full"></div>
                </div>
                
                <!-- Business Listings -->
                <div class="space-y-6 overflow-y-auto max-h-[600px] pr-4">
                    <?php
                    $latest_businesses = new WP_Query(array(
                        'post_type' => 'business',
                        'posts_per_page' => 10,
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
                    endif;
                    wp_reset_postdata(); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-blue-100">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-bold text-blue-800 sm:text-4xl">
            <span class="block text-5xl">Own a Business?</span>
            <span class="block text-gray-800">Join our growing community today.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="<?php echo esc_url(home_url('/add-business')); ?>" 
                   class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                    List Your Business
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps Integration -->
<script>
let map;
let markers = {};  // Changed to object to store markers by place_id
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

function initMap() {
    // Initialize the map centered on Baltimore
    map = new google.maps.Map(document.getElementById('business-map'), {
        center: { lat: 39.3086, lng: -76.6869 },
        zoom: 11,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    const bounds = new google.maps.LatLngBounds();
    const service = new google.maps.places.PlacesService(map);

    // Create info window
    const infoWindow = new google.maps.InfoWindow();

    // Process each business
    businesses.forEach(business => {
        // Use Places Service to get location details
        service.getDetails({
            placeId: business.place_id,
            fields: ['geometry', 'formatted_address']
        }, (place, status) => {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                const marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    title: business.title,
                    animation: google.maps.Animation.DROP,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: '#3B82F6',
                        fillOpacity: 0.7,
                        strokeColor: '#1E40AF',
                        strokeWeight: 2
                    }
                });

                bounds.extend(place.geometry.location);
                markers[business.place_id] = marker;  // Store marker by place_id

                // Create info window content
                const content = `
                    <div class="p-4 max-w-sm bg-white rounded-lg shadow-sm">
                        ${business.thumbnail ? `
                            <div class="w-full h-32 mb-4 overflow-hidden rounded-lg">
                                <img src="${business.thumbnail}" alt="${business.title}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                            </div>
                        ` : ''}
                        <div class="space-y-2">
                            <h3 class="font-bold text-lg text-gray-900 leading-tight">${business.title}</h3>
                            <p class="text-sm text-gray-600 flex items-start">
                                <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="flex-1">${place.formatted_address}</span>
                            </p>
                            <div class="pt-2">
                                <a href="${business.url}" 
                                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                    View Details
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                // Add click listener to marker
                marker.addListener('click', () => {
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                });

                // Highlight marker on card hover
                const card = document.querySelector(`[data-place-id="${business.place_id}"]`);
                if (card) {
                    card.addEventListener('mouseenter', () => {
                        marker.setIcon({
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 12,
                            fillColor: '#2563EB',
                            fillOpacity: 1,
                            strokeColor: '#1E40AF',
                            strokeWeight: 3
                        });
                        marker.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                    });

                    card.addEventListener('mouseleave', () => {
                        marker.setIcon({
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 10,
                            fillColor: '#3B82F6',
                            fillOpacity: 0.7,
                            strokeColor: '#1E40AF',
                            strokeWeight: 2
                        });
                        marker.setZIndex(undefined);
                    });

                    // Click on card to open info window
                    card.addEventListener('click', () => {
                        map.panTo(marker.getPosition());
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });
                }

                // Fit map to markers after last business is processed
                if (Object.keys(markers).length === businesses.length) {
                    map.fitBounds(bounds);
                    if (map.getZoom() > 15) map.setZoom(15);
                }
            }
        });
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr(get_option('google_places_api_key')); ?>&libraries=places&callback=initMap" async defer></script>

<?php get_footer(); ?>
