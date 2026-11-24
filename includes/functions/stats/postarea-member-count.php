<?php
/**
 * Output count of members in a post area.
 * 
 * Retrieves the number of members in the specified post area.
 * 
 * $user_id is passed from context
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function get_postarea_member_count($postarea) {
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $wpdb->usermeta WHERE meta_key = 'wpum_postarea' AND meta_value = %s",
        $postarea
    ));
    return $count;
}