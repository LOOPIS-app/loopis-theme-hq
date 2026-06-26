<?php
/**
 * Check if member_pending has completed member data and membership payment.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function member_pending_check($user_id) {
    // Initialize flags for member data and payment completion
    $member_data_complete = false;
    $member_payment_complete = false;

    // Get current member data
    $postcode = (string) get_user_meta($user_id, 'wpum_postcode', true);
    $phone = (string) get_user_meta($user_id, 'wpum_phone', true);
    $birthyear = (string) get_user_meta($user_id, 'wpum_birthyear', true);
    $gender = (string) get_user_meta($user_id, 'wpum_gender', true);
    $area = (string) get_user_meta($user_id, 'wpum_area', true);
    $active = (string) get_user_meta($user_id, 'wpum_active', true);


    // Set validation rules
    $postcode_digits = preg_replace('/\D+/', '', $postcode);
    $phone_digits = preg_replace('/\D+/', '', $phone);
    $birthyear_digits = preg_replace('/\D+/', '', $birthyear);
    $allowed_genders = array('female', 'male', 'nonbinary', 'other', 'secret');
    $allowed_areas = array('1', '2', '3', '4', '5', 'other');
    $allowed_active = array('true', 'false', '1', '0', 'yes', 'no', 'on', 'off');
    $current_year = (int) wp_date('Y');

    // Validate data
    $valid_postcode = (bool) preg_match('/^\d{5}$/', $postcode_digits);
    $valid_phone = (bool) preg_match('/^\d{10}$/', $phone_digits);
    $valid_birthyear = (bool) preg_match('/^\d{4}$/', $birthyear_digits)
        && (int) $birthyear_digits >= 1900
        && (int) $birthyear_digits <= $current_year;
    $valid_gender = in_array($gender, $allowed_genders, true);
    $valid_area = in_array($area, $allowed_areas, true);
    $valid_active = in_array(strtolower($active), $allowed_active, true);

    // Check if all member data is complete and valid
    if ($valid_postcode
        && $valid_phone
        && $valid_birthyear
        && $valid_gender
        && $valid_area
        && $valid_active) {
        $member_data_complete = true;
    }

    // Check if the user has completed membership payment
    $payment_info = loopis_ledger_user_payments($user_id);
    if (!empty($payment_info)) {
        foreach ($payment_info as $row) {
            $payment_date = date('Y-m-d',strtotime($row['timestamp']));
            $payment_type = loopis_ledger_type_output($row['type']);
            $payment_amount = $row['payment'];
            $payment_method = $row['description'];
        }
        if (!empty($payment_type) && $payment_type === "medlemskap") {
            $member_payment_complete = true;
        }
    } 

    // Setup roles if both member data and membership payment are complete
    if ($member_data_complete && $member_payment_complete) {
        include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-role-setup.php';
        member_access_setup($user_id);
    }

    // Return the results as an associative array
    return array(
        'member_data_complete' => $member_data_complete,
        'member_payment_complete' => $member_payment_complete,
    );
}
