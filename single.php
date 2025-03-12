<?php

get_header(); ?>

<article <?php post_class('bg-gray-50'); ?>>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <?php if (has_post_thumbnail()) : ?>
        <div class="relative w-full h-[400px] bg-gray-900">
            <?php the_post_thumbnail('full', ['class' => 'absolute inset-0 w-full h-full object-cover opacity-90']); ?>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/75 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            foreach($categories as $category) {
                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">' 
                                    . esc_html($category->name) . '</a>';
                            }
                        }
                        ?>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4"><?php the_title(); ?></h1>
                    <div class="flex items-center text-gray-300 text-sm">
                        <span class="flex items-center">
                            <?php echo get_avatar(get_the_author_meta('ID'), 40, '', '', ['class' => 'rounded-full mr-2']); ?>
                            <?php the_author(); ?>
                        </span>
                        <span class="mx-2">&bull;</span>
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                        <?php if (get_the_tags()) : ?>
                            <span class="mx-2">&bull;</span>
                            <span class="flex items-center gap-2">
                                <?php the_tags('<span class="sr-only">Tags:</span>', ', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else : ?>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-wrap gap-2 mb-4">
                <?php
                $categories = get_the_category();
                if ($categories) {
                    foreach($categories as $category) {
                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">' 
                            . esc_html($category->name) . '</a>';
                    }
                }
                ?>
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4"><?php the_title(); ?></h1>
            <div class="flex items-center text-gray-500 text-sm">
                <span class="flex items-center">
                    <?php echo get_avatar(get_the_author_meta('ID'), 40, '', '', ['class' => 'rounded-full mr-2']); ?>
                    <?php the_author(); ?>
                </span>
                <span class="mx-2">&bull;</span>
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
                <?php if (get_the_tags()) : ?>
                    <span class="mx-2">&bull;</span>
                    <span class="flex items-center gap-2">
                        <?php the_tags('<span class="sr-only">Tags:</span>', ', '); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-xl shadow-sm px-6 py-8 sm:p-10 prose prose-lg max-w-none">
                <?php the_content(); ?>
            </div>

            

            <nav class="mt-12 border-t border-gray-200 pt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <?php if ($prev_post) : ?>
                    <a href="<?php echo get_permalink($prev_post); ?>" class="group flex items-center text-left">
                        <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-500">Previous Post</div>
                            <div class="mt-1 text-base font-medium text-gray-900 group-hover:text-blue-600">
                                <?php echo get_the_title($prev_post); ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                
                <?php if ($next_post) : ?>
                    <a href="<?php echo get_permalink($next_post); ?>" class="group flex items-center text-right <?php echo $prev_post ? 'md:justify-end' : ''; ?>">
                        <div>
                            <div class="text-sm text-gray-500">Next Post</div>
                            <div class="mt-1 text-base font-medium text-gray-900 group-hover:text-blue-600">
                                <?php echo get_the_title($next_post); ?>
                            </div>
                        </div>
                        <svg class="ml-3 w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </nav>
        </div>

    <?php endwhile; endif; ?>
</article>

<?php get_footer(); ?>