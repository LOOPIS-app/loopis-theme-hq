<?php
/**
 * Status messages for user/visitor.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize message
$message = '';

if (is_user_logged_in()) { 

    $user = wp_get_current_user();
    $user_roles = (array) $user->roles;

    // Member
    if (in_array('member', $user_roles, true)) {
        $message = '<h5>Välkommen ' . esc_html($user->first_name) . '!</h5>';
    }

    // Member pending
    if (in_array('member_pending', $user_roles, true)) {
        $message = '<h5>Hej ' . esc_html($user->first_name) . '!</h5>';
    }

    // Member earlier
    elseif (in_array('member_earlier', $user_roles, true)) {
        $message = '<h5>Hej ' . esc_html($user->first_name) . '!</h5>';
    }

    // Member outside
    elseif (in_array('member_outside', $user_roles, true)) {
        $message = '<h5>Hej ' . esc_html($user->first_name) . '!</h5>';
    }

    // Admin
    elseif (in_array('administrator', $user_roles, true)) {
        $message = '<h5>Hej ' . esc_html($user->first_name) . '!</h5>';
    }

} else {
    // Not logged in
    $message .= '<h5>Det nya sättet att ge & få saker</h5>
                <hr>
                <p class="small">💡 Paxa i appen, hämta i skåpet.</p>';
}

// Output the message if it exists
if (!empty($message)) {
    echo $message;
}