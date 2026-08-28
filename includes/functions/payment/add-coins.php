<?php
/**
 * Automatic additions of coins initiated by Stripe payment.
 * 
 * Stores payment entry to the user's account.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function add_coins($user_id = null,$options=[]) {
    $settings = [
        'type' => $options['type'] ?? 'mynt',
        'description' => $options['description'] ?? 'stripe',
        'location' => $options['location'] ?? 'digital',
        'blog_id' => $options['blog_id'] ?? 1,
        'payment' => $options['payment'] ?? 50,
        'coins' => $options['coins'] ?? 5,
        'clovers'=>$options['clovers'] ?? 0,
    ];
    // Get user ID (either passed parameter or current logged-in user)
    if ($user_id === null) {
        $user_id = get_current_user_id();
    }

    // Get user data
    $user = get_userdata($user_id);
    if (!$user) {
        error_log("LOOPIS: add_coins failed - User {$user_id} not found");
        return false;
    }

    // Retrieve the current wpum_payments field value
    $current_payments = get_user_meta($user_id, 'wpum_payments', true);
    if (!is_array($current_payments)) {
        $current_payments = array();
    }

    // Create the new payment detail array
    $current_payments[] = array(
        'wpum_payment_date' => array(array('value' => date('Y-m-d'))),
        'wpum_payment_type' => array(array('value' => ucfirst($settings['type']))),
        'wpum_payment_method' => array(array('value' => $settings['description'])),
        'wpum_payment_amount' => array(array('value' => $settings['payment'])),
        'wpum_received_coins' => array(array('value' => $settings['coins'])),
    );

    loopis_ledger_add_payment($user_id,$settings);
    // Add the new payment detail to the existing array
    $updated_payments = array_merge($current_payments);

    // Update the wpum_payments field with the modified array
    $updated = update_user_meta($user_id, 'wpum_payments', $updated_payments);
    if (false === $updated) {
        error_log("LOOPIS: add_coins failed - Could not update wpum_payments for user {$user_id}");
        return false;
    }

    return true;
}