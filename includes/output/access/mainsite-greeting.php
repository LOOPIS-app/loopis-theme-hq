<?php
/**
 * Mainsite greeting depending on role.
 * 
 * Passed from page-start.php:
 * $user_roles
 * $user_firstname
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_user_logged_in()) { 

    // Member
    if (in_array('member', $user_roles, true)) {
        echo '<h5>Hej ' . $user_firstname . '! 👋</h5>';
    }

    // Member pending
    elseif (in_array('member_pending', $user_roles, true)) { 
        echo '<h5>Hej ' . $user_firstname . '! 👋</h5>';
    }

    // Member earlier
    elseif (in_array('member_earlier', $user_roles, true)) {
        echo '<h5>Välkommen tillbaka ' . $user_firstname . '! 💚</h5>';
    }

    // Member support
    elseif (in_array('member_support', $user_roles, true)) {
        echo '<h5>Hej ' . $user_firstname . '! 💚</h5>';
    }

    // Super Admin (capability)
    elseif (is_super_admin()) {
        echo '<h5>Hej webmaster!</h5>';
    }

    // Administrator
    elseif (in_array('administrator', $user_roles, true)) {
        echo '<h5>Hej ' . $user_firstname . '!</h5>';
    }

} else {
    // Not logged in
    echo '<h5>Det nya sättet att ge & få saker</h5>';
    echo '<hr>';
    echo '<p class="small">💡 Paxa i telefonen, hämta i skåpet.</p>';
}
