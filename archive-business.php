<?php
/**
 * The template for displaying business archive pages
 */

get_header(); ?>

<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Business Directory</h1>
            
            <?php
            // Get current filters
            $current_category = isset($_GET['business_category']) ? sanitize_text_field($_GET['business_category']) : '';
            $current_city = isset($_GET['business_city']) ? sanitize_text_field($_GET['business_city']) : '';
            
            // Show active filters
            if ($current_category || $current_city) :
                echo '<div class="flex flex-wrap gap-2 mb-6">';
                
                if ($current_category) {
                    $category = get_term_by('slug', $current_category, 'business_category');
                    if ($category) {
                        echo '<div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">';
                        echo '<span class="mr-2">' . esc_html($category->name) . '</span>';
                        echo '<a href="' . esc_url(remove_query_arg('business_category')) . '" class="text-blue-600 hover:text-blue-800">';
                        echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                        echo '</svg>';
                        echo '</a>';
                        echo '</div>';
                    }
                }
                
                if ($current_city) {
                    echo '<div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">';
                    echo '<span class="mr-2">' . esc_html($current_city) . '</span>';
                    echo '<a href="' . esc_url(remove_query_arg('business_city')) . '" class="text-blue-600 hover:text-blue-800">';
                    echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                    echo '</svg>';
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<a href="' . esc_url(remove_query_arg(['business_category', 'business_city'])) . '" ';
                echo 'class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-600 hover:bg-gray-200">';
                echo 'Clear All Filters';
                echo '</a>';
                echo '</div>';
            endif;
            ?>
            
            <p class="text-lg text-gray-600">
                <?php
                if (have_posts()) {
                    global $wp_query;
                    echo 'Found ' . $wp_query->found_posts . ' businesses';
                    if ($current_category || $current_city) {
                        echo ' matching your filters';
                    }
                } else {
                    echo 'No businesses found';
                    if ($current_category || $current_city) {
                        echo ' matching your filters';
                    }
                }
                ?>
            </p>
        </header>

        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <?php
                while (have_posts()) : 
                    the_post();
                    get_template_part('template-parts/business/card');
                endwhile;
                ?>
            </div>

            <?php
            $pagination = paginate_links(array(
                'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>',
                'next_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
                'type' => 'array'
            ));

            if ($pagination) : ?>
                <nav class="mt-12 flex justify-center" aria-label="Pagination">
                    <ul class="inline-flex items-center space-x-2">
                        <?php
                        foreach ($pagination as $key => $page_link) : ?>
                            <li>
                                <?php
                                // Convert the HTML string to a DOM element to extract classes
                                $doc = new DOMDocument();
                                $doc->loadHTML($page_link);
                                $links = $doc->getElementsByTagName('a');
                                $spans = $doc->getElementsByTagName('span');
                                
                                $classes = 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-md';
                                
                                if ($links->length > 0) {
                                    // It's a link
                                    $element = $links->item(0);
                                    $classes .= ' text-gray-700 bg-white hover:bg-gray-50';
                                } else {
                                    // It's the current page
                                    $element = $spans->item(0);
                                    $classes .= ' text-white bg-blue-600';
                                }
                                
                                echo preg_replace('/class=".*?"/', 'class="' . $classes . '"', $page_link);
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else : ?>
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Businesses Found</h3>
                <p class="text-gray-500">
                    <?php
                    if ($current_category || $current_city) {
                        echo 'Try adjusting your filters or ';
                    }
                    ?>
                    <a href="<?php echo esc_url(remove_query_arg(['business_category', 'business_city'])); ?>" class="text-blue-600 hover:text-blue-800">view all businesses</a>.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
