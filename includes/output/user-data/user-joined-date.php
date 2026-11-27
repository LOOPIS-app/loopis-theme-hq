<?php
/**
 * Output date for user account creation
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user account creation date
$joined_date = get_userdata($user_id)->user_registered;
$joined_date = date('Y-m-d', strtotime($joined_date));

// Output
echo esc_html($joined_date);