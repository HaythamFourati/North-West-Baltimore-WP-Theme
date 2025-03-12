<?php get_header(); ?>

<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                <?php
                if (is_home() && !is_front_page()) {
                    single_post_title();
                } else {
                    _e('Latest News', 'north-west-baltimore');
                }
                ?>
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Stay updated with the latest news and updates from our community
            </p>
        </div>

        <div class="max-w-7xl mx-auto">
            <main>
                <?php if (have_posts()) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php while (have_posts()) : the_post(); ?>
                            <article <?php post_class('bg-white rounded-xl shadow-sm overflow-hidden transition-all hover:shadow-md h-full flex flex-col'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="block h-48 md:h-56 lg:h-64 overflow-hidden">
                                        <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover']); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <div class="flex-1 p-6">
                                    <header class="mb-4">
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <?php
                                            $categories = get_the_category();
                                            if ($categories) {
                                                foreach($categories as $category) {
                                                    echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' 
                                                        . esc_html($category->name) . '</span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <h2 class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h2>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <time datetime="<?php echo get_the_date('c'); ?>">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                            <span class="mx-2">&bull;</span>
                                            <span><?php echo get_the_author(); ?></span>
                                        </div>
                                    </header>

                                    <div class="prose prose-sm max-w-none text-gray-500">
                                        <?php the_excerpt(); ?>
                                    </div>

                                    <footer class="mt-6">
                                        <a href="<?php the_permalink(); ?>" 
                                           class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                            Read full article
                                            <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </a>
                                    </footer>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <?php if (get_the_posts_pagination()) : ?>
                        <nav class="mt-12 border-t border-gray-200 pt-12">
                            <?php
                            the_posts_pagination(array(
                                'mid_size' => 2,
                                'prev_text' => sprintf(
                                    '<span class="flex items-center"><svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> %s</span>',
                                    __('Previous', 'north-west-baltimore')
                                ),
                                'next_text' => sprintf(
                                    '<span class="flex items-center">%s <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>',
                                    __('Next', 'north-west-baltimore')
                                ),
                                'class' => 'flex justify-between items-center',
                            ));
                            ?>
                        </nav>
                    <?php endif; ?>

                <?php else : ?>
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h2 class="mt-4 text-xl font-semibold text-gray-900">No Posts Found</h2>
                        <p class="mt-2 text-gray-500">We couldn't find any posts matching your criteria. Try adjusting your search or browse our recent articles.</p>
                        <div class="mt-6">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Return Home
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<?php get_footer(); ?>