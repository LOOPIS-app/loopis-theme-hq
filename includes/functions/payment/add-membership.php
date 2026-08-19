<?php
/**
 * Add membership entitlements after successful Stripe payment.
 *
 * Sets member role, stores payment entry, and sends welcome email.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function add_membership($user_id = null, $method = 'stripe') {

    // Get user ID (either passed parameter or current logged-in user).
    if ($user_id === null) {
        $user_id = get_current_user_id();
    }

    // Get user data.
    $user = get_userdata($user_id);
    if (!$user) {
        error_log("LOOPIS: add_membership failed - User {$user_id} not found");
        return false;
    }

    // Retrieve the current wpum_payments field value.
    $current_payments = get_user_meta($user_id, 'wpum_payments', true);
    if (!is_array($current_payments)) {
        $current_payments = array();
    }

    // Create the new payment detail array
    $current_payments[] = array(
        'wpum_payment_date' => array(array('value' => date('Y-m-d'))),
        'wpum_payment_type' => array(array('value' => 'Medlemskap')),
        'wpum_payment_method' => array(array('value' => $method)),
        'wpum_payment_amount' => array(array('value' => '50')),
        'wpum_received_coins' => array(array('value' => '5')),
    );

    update_user_meta($user_id, 'wpum_payments', $current_payments);
    loopis_ledger_add_payment($user_id, ['type' => 'medlemskap', 'description'=>$method]);

    // Check if both member data and membership payment are complete.
    include LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php';
    member_pending_check($user_id);

    return true;
}
