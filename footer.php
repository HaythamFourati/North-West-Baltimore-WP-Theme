<?php
/**
 * The template for displaying the footer
 */
?>

<footer class="bg-gray-900">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto pt-16 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12 pb-12 border-b border-gray-800">
            <!-- Brand Section -->
            <div class="space-y-6">
                <?php if (has_custom_logo()) : ?>
                    <div class="max-w-[180px]">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <div class="text-2xl uppercase font-bold text-white">
                        <?php bloginfo('name'); ?>
                    </div>
                <?php endif; ?>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Empowering and connecting businesses in North West Baltimore. Join our thriving community and grow your business with us.
                </p>
                <!-- Social Links -->
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-6">Quick Links</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/businesses')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors">
                            All Listings
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/blog')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/add-business')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Add Your Business
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Business Categories -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-6">Business Categories</h3>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'business_category',
                    'hide_empty' => true,
                    'number' => 6
                ));

                if (!empty($categories) && !is_wp_error($categories)) : ?>
                    <ul class="space-y-4">
                        <?php foreach ($categories as $category) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                                   class="text-gray-400 hover:text-blue-400 transition-colors">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-6">Stay Updated</h3>
                <p class="text-gray-400 text-sm mb-4">Subscribe to our newsletter for the latest updates and business opportunities.</p>
                <?php if (function_exists('mc4wp_show_form')) : ?>
                    <?php mc4wp_show_form(); ?>
                <?php else : ?>
                    <form class="space-y-3">
                        <div>
                            <label for="email" class="sr-only">Email address</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter your email">
                        </div>
                        <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Subscribe
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
                </div>
                <div class="flex space-x-6 text-sm">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-2',
                        'container' => false,
                        'menu_class' => 'flex space-x-6',
                        'fallback_cb' => false,
                        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                        'link_before' => '<span class="text-gray-400 hover:text-blue-400 transition-colors">',
                        'link_after' => '</span>'
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
