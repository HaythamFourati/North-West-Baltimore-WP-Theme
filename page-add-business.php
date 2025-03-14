<?php
/**
 * Template Name: Add Business Page
 */

get_header();

// Handle form submission
$form_submitted = false;
$form_errors = array();
$form_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_business'])) {
    // Verify nonce
    if (!wp_verify_nonce($_POST['business_submission_nonce'], 'submit_business_action')) {
        $form_errors[] = 'Security verification failed. Please try again.';
    } else {
        // Validate required fields
        $required_fields = array(
            'business_name', 'business_email', 'business_phone', 'business_address', 'business_category', 'business_city',
            'owner_first_name', 'owner_last_name', 'owner_email', 'owner_password', 'owner_password_confirm'
        );
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $form_errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
            }
        }

        // Validate email addresses
        if (!empty($_POST['business_email']) && !is_email($_POST['business_email'])) {
            $form_errors[] = 'Please enter a valid business email address.';
        }
        if (!empty($_POST['owner_email']) && !is_email($_POST['owner_email'])) {
            $form_errors[] = 'Please enter a valid owner email address.';
        }

        // Validate password
        if (!empty($_POST['owner_password'])) {
            if (strlen($_POST['owner_password']) < 8) {
                $form_errors[] = 'Password must be at least 8 characters long.';
            }
            if ($_POST['owner_password'] !== $_POST['owner_password_confirm']) {
                $form_errors[] = 'Passwords do not match.';
            }
        }

        // If no errors, create user and business post
        if (empty($form_errors)) {
            // Create user account
            $user_data = array(
                'user_login'    => sanitize_email($_POST['owner_email']),
                'user_email'    => sanitize_email($_POST['owner_email']),
                'user_pass'     => $_POST['owner_password'],
                'first_name'    => sanitize_text_field($_POST['owner_first_name']),
                'last_name'     => sanitize_text_field($_POST['owner_last_name']),
                'display_name'  => sanitize_text_field($_POST['owner_first_name'] . ' ' . $_POST['owner_last_name']),
                'role'          => 'business_owner'
            );

            $user_id = wp_insert_user($user_data);

            if (!is_wp_error($user_id)) {
                // Create business post
                $business_data = array(
                    'post_title'    => sanitize_text_field($_POST['business_name']),
                    'post_content'  => wp_kses_post($_POST['business_description']),
                    'post_status'   => 'pending',
                    'post_type'     => 'business',
                    'post_author'   => $user_id
                );

                $post_id = wp_insert_post($business_data);

                if (!is_wp_error($post_id)) {
                    // Save business meta data
                    update_post_meta($post_id, 'business_email', sanitize_email($_POST['business_email']));
                    update_post_meta($post_id, 'business_phone', sanitize_text_field($_POST['business_phone']));
                    update_post_meta($post_id, 'business_address', sanitize_textarea_field($_POST['business_address']));
                    
                    if (!empty($_POST['business_website'])) {
                        update_post_meta($post_id, 'business_website', esc_url_raw($_POST['business_website']));
                    }
                    if (!empty($_POST['business_hours'])) {
                        update_post_meta($post_id, 'business_hours', sanitize_textarea_field($_POST['business_hours']));
                    }

                    // Save owner meta data
                    update_post_meta($post_id, 'owner_first_name', sanitize_text_field($_POST['owner_first_name']));
                    update_post_meta($post_id, 'owner_last_name', sanitize_text_field($_POST['owner_last_name']));
                    update_post_meta($post_id, 'owner_email', sanitize_email($_POST['owner_email']));
                    update_post_meta($post_id, 'owner_phone', sanitize_text_field($_POST['owner_phone']));

                    // Set taxonomies
                    wp_set_object_terms($post_id, (int)$_POST['business_category'], 'business_category');
                    wp_set_object_terms($post_id, (int)$_POST['business_city'], 'business_city');

                    // Send notification email to admin
                    $admin_email = get_option('admin_email');
                    $subject = 'New Business Listing Submission: ' . $_POST['business_name'];
                    $message = "A new business listing has been submitted and is waiting for review:\n\n";
                    $message .= "Business Name: " . $_POST['business_name'] . "\n";
                    $message .= "Business Email: " . $_POST['business_email'] . "\n";
                    $message .= "Business Phone: " . $_POST['business_phone'] . "\n\n";
                    $message .= "Owner Name: " . $_POST['owner_first_name'] . ' ' . $_POST['owner_last_name'] . "\n";
                    $message .= "Owner Email: " . $_POST['owner_email'] . "\n";
                    $message .= "\nPlease review this submission in the WordPress admin panel.";
                    
                    wp_mail($admin_email, $subject, $message);

                    // Send welcome email to business owner
                    $owner_subject = 'Welcome to North West Baltimore Business Directory';
                    $owner_message = "Dear " . $_POST['owner_first_name'] . ",\n\n";
                    $owner_message .= "Thank you for submitting your business listing for " . $_POST['business_name'] . ".\n\n";
                    $owner_message .= "Your listing is currently under review. You can log in to your account using:\n";
                    $owner_message .= "Email: " . $_POST['owner_email'] . "\n";
                    $owner_message .= "Password: The password you provided during registration\n\n";
                    $owner_message .= "We will notify you once your listing has been approved.\n\n";
                    $owner_message .= "Best regards,\nNorth West Baltimore Business Directory Team";

                    wp_mail($_POST['owner_email'], $owner_subject, $owner_message);

                    $form_success = true;
                    $form_submitted = true;
                } else {
                    $form_errors[] = 'Error creating business listing. Please try again.';
                }
            } else {
                $form_errors[] = 'Error creating user account. This email may already be registered.';
            }
        }
    }
}
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
            <div class="bg-white rounded-lg overflow-hidden">
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
            <div class="bg-gradient-to-b from-white to-gray-50 rounded-xl border border-gray-200 p-8">
                <?php if ($form_submitted && $form_success) : ?>
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-md mb-8">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-green-800">Success!</h3>
                                <p class="text-base text-green-700 mt-1">Your business has been submitted successfully. We'll review it shortly.</p>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <?php if (!empty($form_errors)) : ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-md mb-8">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-red-800">Please fix the following errors</h3>
                                    <div class="mt-2 text-base text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <?php foreach ($form_errors as $error) : ?>
                                                <li><?php echo esc_html($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="space-y-10">
                        <?php wp_nonce_field('submit_business_action', 'business_submission_nonce'); ?>

                        <!-- Business Owner Information -->
                        <div class="bg-white rounded-xl p-8 border border-blue-100">
                            <h3 class="text-xl font-bold text-blue-900 mb-8 pb-4 border-b border-blue-100">Business Owner Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label for="owner_first_name" class="block text-sm font-semibold text-gray-700">First Name *</label>
                                    <input type="text" name="owner_first_name" id="owner_first_name" required
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        value="<?php echo isset($_POST['owner_first_name']) ? esc_attr($_POST['owner_first_name']) : ''; ?>">
                                </div>
                                <div class="space-y-2">
                                    <label for="owner_last_name" class="block text-sm font-semibold text-gray-700">Last Name *</label>
                                    <input type="text" name="owner_last_name" id="owner_last_name" required
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        value="<?php echo isset($_POST['owner_last_name']) ? esc_attr($_POST['owner_last_name']) : ''; ?>">
                                </div>
                                <div class="space-y-2">
                                    <label for="owner_email" class="block text-sm font-semibold text-gray-700">Email *</label>
                                    <input type="email" name="owner_email" id="owner_email" required
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        value="<?php echo isset($_POST['owner_email']) ? esc_attr($_POST['owner_email']) : ''; ?>">
                                </div>
                                <div class="space-y-2">
                                    <label for="owner_phone" class="block text-sm font-semibold text-gray-700">Phone</label>
                                    <input type="tel" name="owner_phone" id="owner_phone"
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        value="<?php echo isset($_POST['owner_phone']) ? esc_attr($_POST['owner_phone']) : ''; ?>">
                                </div>
                                <div class="space-y-2">
                                    <label for="owner_password" class="block text-sm font-semibold text-gray-700">Password *</label>
                                    <input type="password" name="owner_password" id="owner_password" required
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        minlength="8">
                                    <p class="text-xs text-gray-500">Minimum 8 characters</p>
                                </div>
                                <div class="space-y-2">
                                    <label for="owner_password_confirm" class="block text-sm font-semibold text-gray-700">Confirm Password *</label>
                                    <input type="password" name="owner_password_confirm" id="owner_password_confirm" required
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        minlength="8">
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="bg-white rounded-xl p-8 border border-blue-100">
                            <h3 class="text-xl font-bold text-blue-900 mb-8 pb-4 border-b border-blue-100">Business Information</h3>
                            <div class="space-y-8">
                                <div class="space-y-2">
                                    <label for="business_name" class="block text-sm font-semibold text-gray-700">Business Name *</label>
                                    <input type="text" name="business_name" id="business_name" required
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        value="<?php echo isset($_POST['business_name']) ? esc_attr($_POST['business_name']) : ''; ?>">
                                </div>
                                <div class="space-y-2">
                                    <label for="business_description" class="block text-sm font-semibold text-gray-700">Business Description</label>
                                    <textarea name="business_description" id="business_description" rows="4"
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                    ><?php echo isset($_POST['business_description']) ? esc_textarea($_POST['business_description']) : ''; ?></textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label for="business_email" class="block text-sm font-semibold text-gray-700">Business Email *</label>
                                        <input type="email" name="business_email" id="business_email" required
                                            class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                            value="<?php echo isset($_POST['business_email']) ? esc_attr($_POST['business_email']) : ''; ?>">
                                    </div>
                                    <div class="space-y-2">
                                        <label for="business_phone" class="block text-sm font-semibold text-gray-700">Business Phone *</label>
                                        <input type="tel" name="business_phone" id="business_phone" required
                                            class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                            value="<?php echo isset($_POST['business_phone']) ? esc_attr($_POST['business_phone']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="business_address" class="block text-sm font-semibold text-gray-700">Business Address *</label>
                                    <textarea name="business_address" id="business_address" rows="3" required
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                    ><?php echo isset($_POST['business_address']) ? esc_textarea($_POST['business_address']) : ''; ?></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label for="business_website" class="block text-sm font-semibold text-gray-700">Business Website</label>
                                    <input type="url" name="business_website" id="business_website"
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        value="<?php echo isset($_POST['business_website']) ? esc_attr($_POST['business_website']) : ''; ?>">
                                </div>
                                <div class="space-y-2">
                                    <label for="business_hours" class="block text-sm font-semibold text-gray-700">Business Hours</label>
                                    <textarea name="business_hours" id="business_hours" rows="3"
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                        placeholder="e.g. Monday-Friday: 9:00 AM - 5:00 PM"
                                    ><?php echo isset($_POST['business_hours']) ? esc_textarea($_POST['business_hours']) : ''; ?></textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label for="business_category" class="block text-sm font-semibold text-gray-700">Business Category *</label>
                                        <select name="business_category" id="business_category" required
                                            class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                                            <option value="">Select a category</option>
                                            <?php
                                            $categories = get_terms(array(
                                                'taxonomy' => 'business_category',
                                                'hide_empty' => false,
                                            ));
                                            foreach ($categories as $category) {
                                                $selected = isset($_POST['business_category']) && $_POST['business_category'] == $category->term_id ? 'selected' : '';
                                                echo '<option value="' . esc_attr($category->term_id) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="business_city" class="block text-sm font-semibold text-gray-700">City *</label>
                                        <select name="business_city" id="business_city" required
                                            class="w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                                            <option value="">Select a city</option>
                                            <?php
                                            $cities = get_terms(array(
                                                'taxonomy' => 'business_city',
                                                'hide_empty' => false,
                                            ));
                                            foreach ($cities as $city) {
                                                $selected = isset($_POST['business_city']) && $_POST['business_city'] == $city->term_id ? 'selected' : '';
                                                echo '<option value="' . esc_attr($city->term_id) . '" ' . $selected . '>' . esc_html($city->name) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="submit_business"
                            class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-lg font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out flex items-center justify-center space-x-2 cursor-pointer">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Submit Business Listing</span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Support Contact -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-600">Need help? <a href="tel:4438521000" class="text-blue-600 hover:text-blue-700 font-medium">Call us at (443) 852-1000</a></p>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
