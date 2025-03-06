<?php
/**
 * Template for displaying single business listings
 */

get_header(); ?>

<article <?php post_class('bg-white'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="relative h-72 md:h-96">
            <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
            <?php if (get_post_meta(get_the_ID(), 'featured', true) === 'yes') : ?>
                <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    Featured Business
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <header class="mb-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h1>
                    <?php
                    $categories = get_the_terms(get_the_ID(), 'business_category');
                    $city = get_the_terms(get_the_ID(), 'business_city');
                    ?>
                    <div class="flex flex-wrap gap-2">
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
                                    <?php echo esc_html($location->name); ?>
                                </span>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
                <div class="flex gap-3">
                    <?php
                    $phone = get_post_meta(get_the_ID(), '_business_phone', true);
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
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
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
                    <?php the_content(); ?>
                </div>
            </div>

            <div class="space-y-6">
                <?php
                $address = get_post_meta(get_the_ID(), '_business_address', true);
                $hours = get_post_meta(get_the_ID(), '_business_hours', true);
                $email = get_post_meta(get_the_ID(), '_business_email', true);
                ?>

                <?php if ($address) : ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Location
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <address class="not-italic text-gray-600 text-sm leading-relaxed">
                                <?php echo nl2br(esc_html($address)); ?>
                            </address>
                            <div class="mt-4 flex items-center justify-between">
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($address); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                    Get Directions
                                </a>
                                <button onclick="navigator.clipboard.writeText('<?php echo esc_js($address); ?>')" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-600 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                    Copy
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($hours) : 
                    $hours_array = explode("\n", $hours);
                ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Business Hours
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="divide-y divide-gray-200">
                                <?php foreach ($hours_array as $hour_line) :
                                    $parts = explode(':', $hour_line, 2);
                                    if (count($parts) === 2) :
                                        $day = trim($parts[0]);
                                        $time = trim($parts[1]);
                                    ?>
                                        <div class="py-3 flex justify-between items-center text-sm">
                                            <span class="font-medium text-gray-900"><?php echo esc_html($day); ?></span>
                                            <span class="text-gray-600"><?php echo esc_html($time); ?></span>
                                        </div>
                                    <?php else : ?>
                                        <div class="py-3 text-sm text-gray-600">
                                            <?php echo esc_html($hour_line); ?>
                                        </div>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($email) : ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Contact
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <a href="mailto:<?php echo esc_attr($email); ?>" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                <?php echo esc_html($email); ?>
                            </a>
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
