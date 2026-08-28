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

    // Normalize and reject invalid IDs early.
    $subsite_id = (int) $subsite_id;
    if ($subsite_id <= 0) {
        return 0;
    }

    // Avoid switching to missing or disabled blogs.
    $blog = get_blog_details($subsite_id, false);
    if (!$blog || (int) $blog->deleted === 1 || (int) $blog->archived === 1 || (int) $blog->spam === 1) {
        return 0;
    }

    // Switch context to target blog, count role, then restore caller context.
    switch_to_blog($subsite_id);
    $user_count = count_users();
    restore_current_blog();

    return isset($user_count['avail_roles']['member']) ? (int) $user_count['avail_roles']['member'] : 0;
}