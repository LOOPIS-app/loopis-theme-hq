<?php
/**
 * Status messages for user/visitor.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_user_logged_in()) { 

    // Member
    if (in_array('member', $user_roles, true)) {
        echo '<h5>Hej ' . $user_firstname . '!</h5>';
    }

    // Member pending
    elseif (in_array('member_pending', $user_roles, true)) { 
        echo '<h5>Välkommen ' . $user_firstname . '!</h5>';
    }

    // Member earlier
    elseif (in_array('member_earlier', $user_roles, true)) {
        echo '<h5>Välkommen tillbaka ' . $user_firstname . '!</h5>';
    }

    // Member outside
    elseif (in_array('member_outside', $user_roles, true)) {
        echo '<h5>Hej ' . $user_firstname . '!</h5>';
    }

    // Super Admin (multisite capability, not a role slug)
    elseif (is_super_admin()) {
        echo '<h5>Hej webmaster!</h5>';
    }

    // Admin
    elseif (in_array('administrator', $user_roles, true)) {
        echo '<h5>Hej admin ' . $user_firstname . '!</h5>';
    }

} else {
    // Not logged in
    echo '<h5>Det nya sättet att ge & få saker</h5>
        <hr>
        <p class="small">💡 Paxa i telefonen, hämta i skåpet.</p>';
}
