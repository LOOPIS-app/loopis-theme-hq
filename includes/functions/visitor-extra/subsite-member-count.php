<?php
/**
 * Count members on subsite
 * @param int $subsite_id The ID of the subsite.
 * @return int The number of members on the subsite.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function subsite_member_count($subsite_id) {
    if (!is_multisite()) {
        return 0;
    }

    switch_to_blog($subsite_id);
    $user_count = count_users();
    restore_current_blog();

    return isset($user_count['avail_roles']['member']) ? (int) $user_count['avail_roles']['member'] : 0;
}