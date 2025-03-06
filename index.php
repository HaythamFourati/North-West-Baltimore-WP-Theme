<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 */

get_header(); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-12">
        <main class="lg:col-span-1">
            <?php if (have_posts()) : ?>
                <div class="space-y-10">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('bg-white rounded-lg shadow-sm overflow-hidden'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="aspect-w-16 aspect-h-9">
                                    <?php the_post_thumbnail('large', ['class' => 'w-full h-64 object-cover']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <header class="mb-4">
                                    <h2 class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <time datetime="<?php echo get_the_date('c'); ?>">
                                            <?php echo get_the_date(); ?>
                                        </time>
                                        <span class="mx-2">&bull;</span>
                                        <?php
                                        $categories = get_the_category();
                                        if ($categories) {
                                            $output = '<span>' . esc_html($categories[0]->name) . '</span>';
                                            echo $output;
                                        }
                                        ?>
                                    </div>
                                </header>

                                <div class="prose max-w-none text-gray-500">
                                    <?php the_excerpt(); ?>
                                </div>

                                <footer class="mt-6">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                        Read more
                                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </footer>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php if (get_the_posts_pagination()) : ?>
                    <nav class="mt-12">
                        <?php
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => '&larr; Previous',
                            'next_text' => 'Next &rarr;',
                            'class' => 'flex justify-between items-center',
                        ));
                        ?>
                    </nav>
                <?php endif; ?>

            <?php else : ?>
                <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                    <h2 class="text-xl font-medium text-gray-900 mb-4">No Posts Found</h2>
                    <p class="text-gray-500">It seems we can't find what you're looking for.</p>
                </div>
            <?php endif; ?>
        </main>

        
    </div>
</div>

<?php get_footer(); ?>