<?php
/**
 * Template for displaying business category archives
 */

get_header(); 

$current_category = get_queried_object();
?>

<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:py-16 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">
                <?php echo esc_html($current_category->name); ?>
            </h1>
            <?php if ($current_category->description) : ?>
                <p class="mt-3 max-w-md mx-auto text-xl text-blue-100 sm:text-2xl md:mt-5 md:max-w-3xl">
                    <?php echo esc_html($current_category->description); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:py-16 lg:px-8">
    <?php if (have_posts()) : ?>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <?php while (have_posts()) : 
                the_post();
                get_template_part('template-parts/business/card');
            endwhile; ?>
        </div>

        <?php
        $total_pages = $wp_query->max_num_pages;
        if ($total_pages > 1) : ?>
            <div class="mt-12">
                <nav class="flex justify-center" aria-label="Pagination">
                    <div class="flex flex-1 justify-center">
                        <?php
                        echo paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $total_pages,
                            'prev_text' => '&laquo; Previous',
                            'next_text' => 'Next &raquo;',
                            'type' => 'list',
                            'before_page_number' => '<span class="px-4 py-2">',
                            'after_page_number' => '</span>',
                        ));
                        ?>
                    </div>
                </nav>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No businesses found</h3>
            <p class="mt-1 text-sm text-gray-500">There are currently no businesses listed in this category.</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
