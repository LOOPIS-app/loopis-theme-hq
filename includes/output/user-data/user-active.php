<?php
/**
 * Output user status (wpum_active true or false)
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user active status
$active = get_user_meta($user_id, 'wpum_active', true);
$active_bool = filter_var($active, FILTER_VALIDATE_BOOLEAN);

// Determine output based on active status
if ($active_bool) {
    $output = "<span class='approved'>Aktivt</span>";
} else {
    $output = "<span class='required'>Ej aktivt</span>";
}


// Output
echo $output;