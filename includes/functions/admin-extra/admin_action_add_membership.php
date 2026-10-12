<?php
/**
 * Manual registration of membership payment
 *
 * Included in add-payment.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function admin_action_add_membership(int $user_id) {
    if ($user_id === 0) {
        wp_die('Invalid user ID.');
    }

    // Get user data
    $user = get_userdata($user_id);
    if (!$user) {
        wp_die('User not found.');
    }

    // Add payment
    add_membership($user_id,['description'=>'swish']);

    error_log("LOOPIS: add_membership success using Swish: {$user->user_login} (ID {$user_id})");

	// Refresh page
	refresh_page();
}