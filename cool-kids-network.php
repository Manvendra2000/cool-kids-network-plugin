<?php
/*
Plugin Name: Cool Kids Network
Description: A plugin for managing user registration, character creation, and role-based access for the Cool Kids Network.
Version: 1.0.0
Author: Manvendra
*/

// Create custom roles on plugin activation
function ckn_create_roles() {
    if (!get_role('cool_kid')) {
        add_role('cool_kid', 'Cool Kid', array(
            'read' => true, // Basic capabilities
        ));
    }
    if (!get_role('cooler_kid')) {
        add_role('cooler_kid', 'Cooler Kid', array(
            'read' => true,
            'edit_users' => true, // Allow viewing other users
        ));
    }
    if (!get_role('coolest_kid')) {
        add_role('coolest_kid', 'Coolest Kid', array(
            'read' => true,
            'edit_users' => true, // Allow viewing other users
            'list_users' => true, // Allow listing users
        ));
    }
}
register_activation_hook(__FILE__, 'ckn_create_roles');

// Register a shortcode for the registration form
function ckn_registration_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ckn_email'])) {
        $email = sanitize_email($_POST['ckn_email']);
        $user_id = ckn_create_user_and_character($email);
        if ($user_id) {
            echo "<p>Registration successful! Check your email for login details.</p>";
        } else {
            echo "<p>Email already registered or an error occurred.</p>";
        }
    }
    ?>
    <form method="post">
        <label for="ckn_email">Email:</label>
        <input type="email" name="ckn_email" id="ckn_email" required>
        <button type="submit">Confirm</button>
    </form>
    <?php
}
add_shortcode('ckn_registration', 'ckn_registration_form');


// Function to create a user and generate a character
function ckn_create_user_and_character($email) {
    if (!email_exists($email)) {
        // Generate random user data from randomuser.me API
        $response = wp_remote_get('https://randomuser.me/api/');
        if (!is_wp_error($response)) {
            $body = json_decode($response['body'], true);
            $user_data = $body['results'][0];

            // Create WordPress user
            $user_id = wp_create_user($email, 'default_password', $email);

            if (!is_wp_error($user_id)) {
                // Set the default role to "Cool Kid"
                $user = new WP_User($user_id);
                $user->set_role('cool_kid'); // Make sure the role slug is correct

                // Add user meta for character data
                update_user_meta($user_id, 'first_name', $user_data['name']['first']);
                update_user_meta($user_id, 'last_name', $user_data['name']['last']);
                update_user_meta($user_id, 'country', $user_data['location']['country']);
                update_user_meta($user_id, 'role', 'Cool Kid'); // Store role in user meta (optional)

                return $user_id;
            } else {
                error_log('User creation failed: ' . print_r($user_id, true));
            }
        } else {
            error_log('API request failed: ' . print_r($response, true));
        }
    } else {
        error_log('Email already exists: ' . $email);
    }
    return false;
}

// Register a shortcode for the login form
function ckn_login_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ckn_login_email'])) {
        $email = sanitize_email($_POST['ckn_login_email']);
        $user = get_user_by('email', $email);
        if ($user) {
            wp_set_auth_cookie($user->ID);
            echo "<p>Login successful! Redirecting...</p>";
            wp_redirect(home_url('/profile')); // Redirect to profile page
            exit;
        } else {
            echo "<p>Invalid email address.</p>";
        }
    }
    ?>
    <form method="post">
        <label for="ckn_login_email">Email:</label>
        <input type="email" name="ckn_login_email" id="ckn_login_email" required>
        <button type="submit">Login</button>
    </form>
    <?php
}
add_shortcode('ckn_login', 'ckn_login_form');

// Register a shortcode for the profile page
function ckn_profile_page() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);
        $country = get_user_meta($user_id, 'country', true);
        $email = get_user_meta($user_id, 'email', true);
        $role = get_user_meta($user_id, 'role', true);

        echo "<h2>Your Character</h2>";
        echo "<p>Name: $first_name $last_name</p>";
        echo "<p>Country: $country</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Role: $role</p>";
    } else {
        echo "<p>Please log in to view your profile.</p>";
    }
}
add_shortcode('ckn_profile', 'ckn_profile_page');

// Register a shortcode for the user list (Cooler Kid and Coolest Kid only)
function ckn_user_list() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $current_user_role = $current_user->roles[0]; // Get the user's role slug

        if ($current_user_role === 'cooler_kid' || $current_user_role === 'coolest_kid') {
            $users = get_users();
            foreach ($users as $user) {
                $first_name = get_user_meta($user->ID, 'first_name', true);
                $last_name = get_user_meta($user->ID, 'last_name', true);
                $country = get_user_meta($user->ID, 'country', true);

                echo "<p>Name: $first_name $last_name, Country: $country</p>";
            }
        } else {
            echo "<p>You do not have permission to view this page.</p>";
        }
    } else {
        echo "<p>Please log in to view this page.</p>";
    }
}
add_shortcode('ckn_user_list', 'ckn_user_list');

// Register a shortcode for the user list with email and role (Coolest Kid only)
function ckn_user_list_full() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $current_user_role = $current_user->roles[0]; // Get the user's role slug

        if ($current_user_role === 'coolest_kid') {
            $users = get_users();
            foreach ($users as $user) {
                $first_name = get_user_meta($user->ID, 'first_name', true);
                $last_name = get_user_meta($user->ID, 'last_name', true);
                $country = get_user_meta($user->ID, 'country', true);
                $email = $user->user_email; // Get email directly from the user object
                $role = $user->roles[0]; // Get role directly from the user object

                echo "<p>Name: $first_name $last_name, Country: $country, Email: $email, Role: $role</p>";
            }
        } else {
            echo "<p>You do not have permission to view this page.</p>";
        }
    } else {
        echo "<p>Please log in to view this page.</p>";
    }
}
add_shortcode('ckn_user_list_full', 'ckn_user_list_full');

// Register a REST API endpoint for role assignment
function ckn_change_user_role(WP_REST_Request $request) {
    $email = $request->get_param('email');
    $new_role = $request->get_param('role');

    // Validate the new role
    $valid_roles = array('cool_kid', 'cooler_kid', 'coolest_kid');
    if (!in_array($new_role, $valid_roles)) {
        return new WP_REST_Response('Invalid role', 400);
    }

    // Get the user by email
    $user = get_user_by('email', $email);
    if (!$user) {
        return new WP_REST_Response('User not found', 404);
    }

    // Update the user's role
    $user->set_role($new_role);

    return new WP_REST_Response('Role updated successfully', 200);
}
add_action('rest_api_init', function () {
    register_rest_route('coolkids/v1', '/change-role', array(
        'methods' => 'POST',
        'callback' => 'ckn_change_user_role',
        'permission_callback' => '__return_true', // Allow access to everyone (for testing only)
    ));
});

// throws 401
// add_action('rest_api_init', function () {
//     register_rest_route('coolkids/v1', '/change-role', array(
//         'methods' => 'POST',
//         'callback' => 'ckn_change_user_role',
//         'permission_callback' => function () {
//             return current_user_can('manage_options'); // Only admins can access
//         },
//     ));
// });