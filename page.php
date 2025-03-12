<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 */

get_header(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-gray-50 min-h-screen'); ?>>
    <?php while (have_posts()) : the_post(); ?>
        <?php if (has_post_thumbnail()) : ?>
            <div class="relative">
                <div class="w-full h-[400px] md:h-[500px] overflow-hidden">
                    <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-gray-900/50 to-gray-900/80"></div>
                </div>
                <div class="absolute inset-x-0 bottom-0 pb-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-3xl">
                            <?php if (!is_front_page() && function_exists('yoast_breadcrumb')) : ?>
                                <div class="mb-6 text-gray-300 text-sm">
                                    <?php yoast_breadcrumb('<div id="breadcrumbs">', '</div>'); ?>
                                </div>
                            <?php endif; ?>
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                                <?php the_title(); ?>
                            </h1>
                            <?php if (!is_front_page()) : ?>
                                <div class="mt-6 flex items-center text-gray-300 text-sm">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?php echo get_the_modified_date(); ?>
                                    </span>
                                    <?php if (get_edit_post_link()) : ?>
                                        <span class="ml-4">
                                            <?php
                                            edit_post_link(
                                                sprintf(
                                                    __('Edit<span class="screen-reader-text"> "%s"</span>', 'north-west-baltimore'),
                                                    get_the_title()
                                                ),
                                                '<span class="text-blue-200 hover:text-blue-100 transition-colors">',
                                                '</span>'
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="pt-12 pb-6 bg-gradient-to-br from-blue-600 to-blue-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-3xl">
                        <?php if (!is_front_page() && function_exists('yoast_breadcrumb')) : ?>
                            <div class="mb-6 text-blue-100 text-sm">
                                <?php yoast_breadcrumb('<div id="breadcrumbs">', '</div>'); ?>
                            </div>
                        <?php endif; ?>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                            <?php the_title(); ?>
                        </h1>
                        <?php if (!is_front_page()) : ?>
                            <div class="mt-6 flex items-center text-blue-100 text-sm">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo get_the_modified_date(); ?>
                                </span>
                                <?php if (get_edit_post_link()) : ?>
                                    <span class="ml-4">
                                        <?php
                                        edit_post_link(
                                            sprintf(
                                                __('Edit<span class="screen-reader-text"> "%s"</span>', 'north-west-baltimore'),
                                                get_the_title()
                                            ),
                                            '<span class="text-blue-200 hover:text-white transition-colors">',
                                            '</span>'
                                        );
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="relative -mt-10 md:-mt-16 pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-xl px-6 py-8 md:p-12 lg:p-16">
                    <div class="prose prose-lg md:prose-xl prose-blue max-w-none">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<nav class="not-prose my-8 p-4 bg-gray-50 rounded-xl text-center" aria-label="' . esc_attr__('Page navigation', 'north-west-baltimore') . '">',
                            'after'  => '</nav>',
                            'link_before' => '<span class="inline-flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">',
                            'link_after'  => '</span>',
                            'separator'   => '<span class="mx-2">&bull;</span>',
                        ));
                        ?>
                    </div>

                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="mt-16 pt-16 border-t border-gray-100">
                            <div class="prose prose-lg max-w-none">
                                <?php comments_template(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</article>

<?php get_footer(); ?>
