<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-gray-50'); ?>>
<?php wp_body_open(); ?>

<header class="bg-white shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block">
                    <img class="h-12 w-auto" src="<?php echo esc_url(get_template_directory_uri() . './src/assets/logo-nwb.png'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'flex space-x-8',
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                    'fallback_cb' => false,
                    'walker' => new Custom_Nav_Walker(),
                ));
                ?>
                <div class="ml-8">
                    <a href="<?php echo esc_url(home_url('/add-business')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Get Started
                    </a>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" 
                        class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" 
                        aria-controls="mobile-menu" 
                        aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu open icon -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Menu close icon -->
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden" id="mobile-menu">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'pt-2 pb-3 space-y-1',
                'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                'fallback_cb' => false,
                'walker' => new Mobile_Nav_Walker(),
            ));
            ?>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <a href="<?php echo esc_url(home_url('/add-business')); ?>" 
                   class="block w-full px-4 py-2 text-center text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Get Started
                </a>
            </div>
        </div>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuOpenIcon = menuButton.querySelector('svg:first-child');
    const menuCloseIcon = menuButton.querySelector('svg:last-child');

    menuButton.addEventListener('click', function() {
        const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';
        
        mobileMenu.classList.toggle('hidden');
        menuButton.setAttribute('aria-expanded', !isExpanded);
        menuOpenIcon.classList.toggle('hidden');
        menuCloseIcon.classList.toggle('hidden');
    });
});
</script>

<div id="page" class="min-h-screen">
    <div id="content">
      
    </div>