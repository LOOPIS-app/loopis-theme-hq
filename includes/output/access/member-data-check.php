<?php
/**
 * Prompt for users with missing data in member registry.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize message
$message = '';

if (is_user_logged_in()) { 

    $current_user = wp_get_current_user();
    $user_roles = (array) $current_user->roles;

if (in_array('member', $user_roles, true) || in_array('member_pending', $user_roles, true)) {

    $user_id = get_current_user_id();

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
    $is_valid_postcode = (bool) preg_match('/^\d{5}$/', $postcode_digits);
    $is_valid_phone = (bool) preg_match('/^\d{10}$/', $phone_digits);
    $is_valid_birthyear = (bool) preg_match('/^\d{4}$/', $birthyear_digits)
        && (int) $birthyear_digits >= 1900
        && (int) $birthyear_digits <= $current_year;
    $is_valid_gender = in_array($gender, $allowed_genders, true);
    $is_valid_area = in_array($area, $allowed_areas, true);
    $is_valid_active = in_array(strtolower($active), $allowed_active, true);

    // Determine if all member data is complete and valid
    $is_member_data_complete = $is_valid_postcode
        && $is_valid_phone
        && $is_valid_birthyear
        && $is_valid_gender
        && $is_valid_area
        && $is_valid_active;

    // Request missing data?
    if (!$is_member_data_complete) {
        $message = '<div class="loopis-message information">
                    <p>⏳ Du behöver komplettera vårt medlemsregister.</p>
                    <p><span class="big-link">📋 <a href="' . esc_url(home_url('/user/?option=member-data')) . '">Tryck här</a></span> för att fylla i uppgifter.</p>
                    </div>';
    }

}
}

// Output the message if it exists
if (!empty($message)) {
    echo $message;
}