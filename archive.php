<?php
/**
 * The template for displaying archive pages
 */

get_header();
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if (have_posts()) : ?>
        <header class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                <?php
                if (is_day()) {
                    printf(esc_html__('Daily Archives: %s', 'north-west-baltimore'), get_the_date());
                } elseif (is_month()) {
                    printf(esc_html__('Monthly Archives: %s', 'north-west-baltimore'), get_the_date(_x('F Y', 'monthly archives date format', 'north-west-baltimore')));
                } elseif (is_year()) {
                    printf(esc_html__('Yearly Archives: %s', 'north-west-baltimore'), get_the_date(_x('Y', 'yearly archives date format', 'north-west-baltimore')));
                } else {
                    the_archive_title();
                }
                ?>
            </h1>
            <?php the_archive_description('<div class="mt-3 text-lg text-gray-600">', '</div>'); ?>
        </header>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aspect-w-16 aspect-h-9">
                            <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="p-6">
                        <header class="mb-4">
                            <h2 class="text-xl font-semibold text-gray-900 hover:text-blue-600">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="mt-2 text-sm text-gray-500">
                                <?php echo get_the_date(); ?>
                            </div>
                        </header>

                        <div class="prose prose-sm max-w-none text-gray-600">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="mt-4">
                            <a href="<?php the_permalink(); ?>" 
                               class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                                <?php esc_html_e('Read more', 'north-west-baltimore'); ?>
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <div class="mt-10">
            <?php
            the_posts_pagination(array(
                'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>',
                'next_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                'class' => 'flex justify-center gap-2',
            ));
            ?>
        </div>

    <?php else : ?>
        <div class="text-center py-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                <?php esc_html_e('No posts found', 'north-west-baltimore'); ?>
            </h2>
            <p class="text-gray-600">
                <?php esc_html_e('It seems we can't find what you're looking for.', 'north-west-baltimore'); ?>
            </p>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
