<?php
/**
 * Template Name: Add Business Page
 */

get_header();
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl mb-4">
            <?php esc_html_e('Join the Top Directory', 'north-west-baltimore'); ?>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            <?php esc_html_e('List your Northwest Baltimore Business in our directory!', 'north-west-baltimore'); ?>
        </p>
    </div>

    <!-- Two Column Layout -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column - Pricing -->
        <div class="lg:w-1/3 lg:sticky lg:top-4 lg:self-start">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Pricing Header -->
                <div class="px-6 py-8 bg-blue-600">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-white">
                            <?php esc_html_e('Free Listing', 'north-west-baltimore'); ?>
                        </h2>
                        <div class="mt-4 flex justify-center items-baseline">
                            <span class="text-5xl font-extrabold text-white tracking-tight">FREE</span>
                            <span class="ml-1 text-xl font-semibold text-blue-100">/ for a limited time</span>
                        </div>
                    </div>
                </div>

                <!-- Feature List -->
                <div class="px-6 pt-6 pb-8">
                    <ul class="mt-6 space-y-4">
                        <?php
                        $features = array(
                            'Address, Phone & Fax',
                            'Map with Directions',
                            'Hours of Operation',
                            'Areas Served',
                            '"Contact Us" Form',
                            'Link to Website',
                            '"About Us" Description',
                            'One Category Listing',
                            'Free Website Analysis'
                        );

                        foreach ($features as $feature) : ?>
                            <li class="flex space-x-3">
                                <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-base text-gray-700"><?php echo esc_html($feature); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column - Form -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                    <?php esc_html_e('Submit Your Business Information', 'north-west-baltimore'); ?>
                </h2>

                <?php
                if (shortcode_exists('contact-form-7')) {
                    echo do_shortcode('[contact-form-7 id="656b30d" title="Add Business Form"]');
                } else {
                    ?>
                    <div class="text-center p-4 bg-yellow-50 rounded-md">
                        <p class="text-yellow-800">
                            <?php esc_html_e('Please contact the site administrator to submit your business information.', 'north-west-baltimore'); ?>
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Support Contact -->
            <div class="text-center mt-6 text-gray-600">
                <p><?php esc_html_e('Need help? Call us at (443) 852-1000', 'north-west-baltimore'); ?></p>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
