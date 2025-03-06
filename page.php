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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-sm overflow-hidden'); ?>>
            <?php if (has_post_thumbnail()) : ?>
                <div class="aspect-w-16 aspect-h-9">
                    <?php the_post_thumbnail('full', ['class' => 'w-full h-64 object-cover']); ?>
                </div>
            <?php endif; ?>

            <div class="p-8">
                <header class="max-w-3xl mx-auto mb-8 text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        <?php the_title(); ?>
                    </h1>
                    <?php if (!is_front_page()) : ?>
                        <div class="flex justify-center items-center text-sm text-gray-500">
                            <span class="mr-4">
                                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <?php echo get_the_modified_date(); ?>
                            </span>
                            <?php
                            if (get_edit_post_link()) : ?>
                                <span>
                                    <?php
                                    edit_post_link(
                                        sprintf(
                                            /* translators: %s: Post title */
                                            __('Edit<span class="screen-reader-text"> "%s"</span>', 'north-west-baltimore'),
                                            get_the_title()
                                        ),
                                        '<span class="text-blue-600 hover:text-blue-800">',
                                        '</span>'
                                    );
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="max-w-3xl mx-auto">
                    <div class="prose prose-blue max-w-none">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links my-4">' . esc_html__('Pages:', 'north-west-baltimore'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="max-w-3xl mx-auto mt-12 pt-12 border-t border-gray-200">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
