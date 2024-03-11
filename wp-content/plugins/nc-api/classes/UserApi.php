<?php

/**
 * Class UserApi
 */
class UserApi
{
// Custom authentication callback function
    function custom_auth_callback($request)
    {
        // Get email and password from request
        $email = $request->get_param('email');
        $password = $request->get_param('password');

        // Validate email and password
        if (empty($email) || empty($password)) {
            return new WP_Error('invalid_credentials', 'Email and password are required.', array('status' => 400));
        }

        // Attempt authentication
        $user = wp_authenticate($email, $password);

        // Check if authentication was successful
        if (is_wp_error($user)) {
            return new WP_Error('invalid_credentials', 'Invalid email or password.', array('status' => 401));
        }

        // Check if the user already has a token
        $existing_token = get_user_meta($user->ID, 'custom_auth_token', true);

        // If a token already exists, return it
        if ($existing_token) {
            return array(
                'token' => $existing_token,
            );
        }

        // If no token exists, generate and return a new one
        $new_token = wp_generate_password(32, false);
        update_user_meta($user->ID, 'custom_auth_token', $new_token);

        return array(
            'token' => $new_token,
        );
    }

}
