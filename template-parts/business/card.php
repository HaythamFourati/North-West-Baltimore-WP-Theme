<?php
/**
 * Template part for displaying business listing cards
 */
?>

<article <?php post_class('business-card bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="aspect-w-16 aspect-h-9">
            <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
        </div>
    <?php endif; ?>

    <div class="p-6">
        <header class="mb-4">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-xl font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h3>
                <?php 
                $google_reviews = get_cached_google_reviews(get_the_ID());
                if ($google_reviews && $google_reviews['rating'] > 0) : ?>
                    <a href="https://search.google.com/local/reviews?placeid=<?php echo esc_attr($google_reviews['place_id']); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="shrink-0 inline-flex items-center text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            <?php echo number_format_i18n($google_reviews['rating'], 1); ?>
                            <span class="ml-1 text-yellow-600">(<?php echo $google_reviews['total_reviews']; ?>)</span>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
            <?php 
            $categories = get_the_terms(get_the_ID(), 'business_category');
            if ($categories && !is_wp_error($categories)) : ?>
                <div class="mt-2 flex flex-wrap gap-2">
                    <?php foreach ($categories as $category) : ?>
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                           class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="business-meta space-y-2 text-sm text-gray-600">
            <?php
            $address = get_post_meta(get_the_ID(), '_business_address', true);
            $phone = get_post_meta(get_the_ID(), '_business_phone', true);
            $website = get_post_meta(get_the_ID(), '_business_website', true);
            
            if ($address) : ?>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span><?php echo esc_html($address); ?></span>
                </div>
            <?php endif;

            if ($phone) : ?>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <a href="tel:<?php echo esc_attr($phone); ?>" class="hover:text-blue-600 transition-colors">
                        <?php echo esc_html($phone); ?>
                    </a>
                </div>
            <?php endif;

            if ($website) : ?>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <a href="<?php echo esc_url($website); ?>" class="hover:text-blue-600 transition-colors" target="_blank" rel="noopener noreferrer">
                        Visit Website
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if (has_excerpt()) : ?>
            <div class="mt-4 text-sm text-gray-600 line-clamp-3">
                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
            </div>
        <?php endif; ?>

        <footer class="mt-6 flex items-center justify-between">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                View Details
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <?php if (get_post_meta(get_the_ID(), 'featured', true) === 'yes') : ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Featured
                </span>
            <?php endif; ?>
        </footer>
    </div>
</article>
