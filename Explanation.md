# **Cool Kids Network Plugin - Explanation**

## **Why This Approach?**

Instead of tweaking WordPress themes or relying on third-party plugins, I chose to build a **custom plugin** for the **Cool Kids Network**. This ensures:

1. **Independence from WordPress Core**: The plugin operates separately from themes and WordPress updates, reducing conflicts.
2. **Maintainability**: The plugin’s modular structure makes future updates easy without affecting the rest of the site.
3. **Security**: A custom plugin offers better control over user data, input validation, and access rights, ensuring robust security.

---

## **Problem Overview**

The goal was to build a user management system that includes:

- Custom user registration
- Role-based access control
- A secure login system
- API endpoint for role assignments

---

## **Key Features**

1. **Custom User Roles**
   - Roles like "Cool Kid", "Cooler Kid", and "Coolest Kid" are created during plugin activation.
   - Each role has different levels of access (view own data, view others, or see all users).

2. **User Registration**
   - A custom form collects emails and generates random data (name, country) via the [randomuser.me API].
   - The `ckn_create_user_and_character()` function handles user creation and data storage.

3. **Login System**
   - Users can log in via email, with automatic redirection to their profile page after successful authentication.

4. **Role Management and Access Control**
   - Role-based access ensures only authorized users can view certain data.
   - An API endpoint allows role assignments to be changed programmatically.

5. **User Profile Display**
   - Shortcodes display user profiles and lists based on roles (e.g., "Coolest Kid" can see all users).

---

## **Technical Decisions**

1. **Naming Convention**
   - Functions and variables are prefixed with `ckn_` to avoid conflicts and keep the codebase organized.

2. **Custom Plugin vs. Theme Modification**
   - Using a plugin ensures that user management is independent of the theme, offering stability even with future WordPress updates.

3. **OOP Structure**
   - The plugin is designed with OOP to keep the code modular, maintainable, and scalable.

---

## **Achieving the Admin’s Goals**

1. **Role-Based Access Control**: Only authorized users can view or modify sensitive data.
2. **Custom Registration and Login**: Users sign up with just an email, and their profiles are automatically generated.
3. **API for Role Management**: Administrators can easily change user roles via an API.
4. **Security**: Input is sanitized and validated, with role-based permissions ensuring only authorized access.

---

## **Future Security Enhancements**

1. **Nonce Protection**: To further prevent CSRF attacks, nonces can be added to forms.
2. **API Security**: Secure the API by restricting access to authorized users only.
3. **Input Sanitization**: Ensure all inputs are properly sanitized and validated to prevent malicious data.

---

## **Conclusion**

This custom plugin approach offers a secure, maintainable, and flexible solution for managing user roles and data on the **Cool Kids Network**. It ensures a clean separation from the theme and allows for easy future updates and extensions.
