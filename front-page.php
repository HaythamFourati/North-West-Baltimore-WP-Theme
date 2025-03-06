<?php
/**
 * The front page template file
 *
 * This is the template for the site's front page
 */

get_header(); ?>

<div class="relative bg-blue-700">
    <!-- Hero section -->
    <div class="relative pt-24 pb-32 px-4 sm:px-6 lg:px-8">
        <div class="relative max-w-7xl mx-auto">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl text-center">
                North West Baltimore Business Directory
            </h1>
            <p class="mt-6 max-w-3xl mx-auto text-xl text-blue-100 text-center">
                Discover and support local businesses in our vibrant community
            </p>
        </div>
    </div>
</div>

<!-- Business Categories Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Business Categories</h2>
    <?php
    $categories = get_terms(array(
        'taxonomy' => 'business_category',
        'hide_empty' => false,
    ));

    if (!empty($categories) && !is_wp_error($categories)) : ?>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($categories as $category) : 
                $category_image = get_term_meta($category->term_id, 'category_image', true); ?>
                <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                   class="relative rounded-lg overflow-hidden bg-white shadow-md hover:shadow-xl transition-shadow duration-300">
                    <?php if ($category_image) : ?>
                        <img src="<?php echo esc_url($category_image); ?>" 
                             alt="<?php echo esc_attr($category->name); ?>"
                             class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900"><?php echo esc_html($category->name); ?></h3>
                        <?php if ($category->description) : ?>
                            <p class="mt-2 text-gray-600"><?php echo esc_html($category->description); ?></p>
                        <?php endif; ?>
                        <div class="mt-4 flex items-center text-sm text-gray-500">
                            <span><?php echo esc_html($category->count); ?> businesses</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Featured Businesses Section -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Featured Businesses</h2>
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
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <?php while ($featured_businesses->have_posts()) : $featured_businesses->the_post(); 
                    get_template_part('template-parts/business/card');
                endwhile; ?>
            </div>
        <?php 
        wp_reset_postdata();
        endif; ?>
    </div>
</div>

<!-- Recent Listings Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Recent Listings</h2>
    <?php
    $recent_businesses = new WP_Query(array(
        'post_type' => 'business',
        'posts_per_page' => 6,
    ));

    if ($recent_businesses->have_posts()) : ?>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <?php while ($recent_businesses->have_posts()) : $recent_businesses->the_post(); 
                get_template_part('template-parts/business/card');
            endwhile; ?>
        </div>
    <?php 
    wp_reset_postdata();
    endif; ?>
</div>

<?php get_footer(); ?>
