<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl mb-4">
            <?php esc_html_e('Page Not Found', 'north-west-baltimore'); ?>
        </h1>
        
        <div class="mt-2 text-lg text-gray-600">
            <?php esc_html_e('The page you\'re looking for doesn\'t exist or has been moved.', 'north-west-baltimore'); ?>
        </div>

        <div class="mt-8 flex justify-center gap-4">
            <a href="<?php echo esc_url(home_url('/')); ?>" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <?php esc_html_e('Go to Homepage', 'north-west-baltimore'); ?>
            </a>
            
            <a href="<?php echo esc_url(get_post_type_archive_link('business')); ?>" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <?php esc_html_e('Browse Businesses', 'north-west-baltimore'); ?>
            </a>
        </div>

        <!-- <?php get_search_form(); ?> -->
    </div>
</main>

<?php
get_footer();
