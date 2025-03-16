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
