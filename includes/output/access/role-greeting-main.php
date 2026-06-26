<?php
/**
 * Status messages for user/visitor.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize greeting
$role_greeting = '';

if (is_user_logged_in()) { 
    
    $user_firstname = wp_get_current_user()->first_name;

    // Member
    if (current_user_can('member')) { 
        $role_greeting = '<h5>Hej ' . $user_firstname . '!</h5>';
    }

    // Member pending
    if (current_user_can('member_pending')) { 
        $role_greeting = '<h5>Välkommen ' . $user_firstname . '!</h5>';
    }

    // Member earlier
    elseif (current_user_can('member_earlier')) {
        $role_greeting = '<h5>Välkommen tillbaka ' . $user_firstname . '!</h5>';
    }

    // Member outside
    elseif (current_user_can('member_outside')) {
        $role_greeting = '<h5>Hej ' . $user_firstname . '!</h5>';
    }

    // Super Admin (multisite capability, not a role slug)
    elseif (is_super_admin()) {
        $role_greeting = '<h5>Hej webmaster!</h5>';
    }

    // Admin
    elseif (current_user_can('administrator')) {
        $role_greeting = '<h5>Hej admin ' . $user_firstname . '!</h5>';
    }

} else {
    // Not logged in
    $role_greeting .= '<h5>Det nya sättet att ge & få saker</h5>
                <hr>
                <p class="small">💡 Paxa i telefonen, hämta i skåpet.</p>';
}

// Output the message if it exists
if (!empty($role_greeting)) {
    echo $role_greeting;
}