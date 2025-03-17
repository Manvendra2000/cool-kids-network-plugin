# Cool Kids Network Plugin

Welcome to the **Cool Kids Network** plugin! This plugin is designed to manage user registration, create custom user roles, and control access to user data based on roles. With the **Cool Kids Network**, you can easily create and manage users with different levels of access, from basic users to more advanced ones, all while ensuring data security and user privacy.

## Features

- **Custom User Roles**: Create roles like "Cool Kid", "Cooler Kid", and "Coolest Kid" with different permissions.
- **User Registration**: A simple email-based registration form with random character data generation.
- **Login System**: A custom login form based on email authentication.
- **Role-Based Access Control**: Restrict access to user data based on roles.
- **Custom API for Role Management**: Programmatically change user roles using a REST API.

## Live Demo

You can see the **Cool Kids Network** plugin in action on the live website:  
[https://manvendra.blog](https://manvendra.blog)

Visit the site to explore the plugin’s functionality, including registration, login, profile display, and role-based access control.

## Installation

1. Download the plugin files.
2. Upload the `cool-kids-network` folder to your `wp-content/plugins/` directory.
3. Activate the plugin from the WordPress admin dashboard.
4. Go to `functions.php` file of your current theme and apply the custom header code provided below, then initiate that in the `header.html` file of your theme.
   ```
   function ckn_custom_header() {
    if (is_user_logged_in()) {
        // Header for logged-in users
        $user_id = get_current_user_id();
        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);
        $country = get_user_meta($user_id, 'country', true);
        $email = get_user_meta($user_id, 'email', true);
        $role = get_user_meta($user_id, 'role', true);

        // Get current user role
        $current_user = wp_get_current_user();
        $current_user_role = $current_user->roles[0];
        ?>
        <header id="logged-in-header">
            <h1>Welcome, <?php echo esc_html($first_name) . ' ' . esc_html($last_name); ?>!</h1>
            <!-- <p>Country: <?php echo esc_html($country); ?></p>
            <p>Email: <?php echo esc_html($email); ?></p>
            <p>Role: <?php echo esc_html($role); ?></p> -->
            <nav>
                <a href="<?php echo wp_logout_url('/'); ?>">Logout</a>
				<a href="<?php echo home_url('/profile'); ?>">Profile</a>
                <?php if ($current_user_role === 'cooler_kid' || $current_user_role === 'coolest_kid') : ?>
                    <a href="<?php echo home_url('/user-list'); ?>">User List</a>
                <?php endif; ?>
                <?php if ($current_user_role === 'coolest_kid') : ?>
                    <a href="<?php echo home_url('/full-user-list/'); ?>">Full User List</a>
                <?php endif; ?>
            </nav>
        </header>
        <?php
    } else {
        // Header for non-logged-in users
        ?>
        <header id="logged-out-header">
            <h1>Welcome to Cool Kids Network!</h1>
            <nav>
				<a href="<?php echo home_url('/'); ?>">Home</a>
                <a href="<?php echo home_url('/signup'); ?>">Sign Up</a>
                <a href="<?php echo home_url('/login'); ?>">Login</a>
            </nav>
        </header>
        <?php
    }
   }
   add_action('wp_head', 'ckn_custom_header');
    ```

## User Perspective

1. Go to the website.
2. Sign up and log in. (Sample ID: `user@example.com`)
3. Limited options are available as you are a "Cool Kid". To change your role, use Postman and send a POST request to: https://manvendra.blog/wp-json/coolkids/v1/change-role
As the body of the request (raw JSON):
```json
{
  "email": "user@example.com",
  "role": "coolest_kid"
}
   ```
5. Hit enter in Postman and refresh the website to see the role change.
