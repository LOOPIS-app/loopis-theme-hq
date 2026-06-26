<?php
/**
 * Setup site access and roles for member
 * 
 * TODO: Replace the hardcoded sub site ID (2) with the users' choice of area.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function member_access_setup($user_id) {
    // Add user and set role on main site
    $main_site_id = get_main_site_id() ?: 1;

    if (!is_user_member_of_blog((int) $user_id, $main_site_id)) {
            $added = add_user_to_blog($main_site_id, (int) $user_id, 'member');
            if (is_wp_error($added)) {
                error_log("LOOPIS: member_access_setup failed adding user {$user_id} to main site {$main_site_id}: " . $added->get_error_message());
                return false;
            }
        }

    switch_to_blog($main_site_id);
    $site_user = new WP_User((int) $user_id);
    if (!$site_user || 0 === (int) $site_user->ID) {
            restore_current_blog();
            error_log("LOOPIS: member_access_setup failed loading user {$user_id} on main site {$main_site_id}");
            return false;
        }

    $site_user->set_role('member');

    $updated_site_user = get_userdata((int) $user_id);
    if (!$updated_site_user || !in_array('member', (array) $updated_site_user->roles, true)) {
            restore_current_blog();
            error_log("LOOPIS: member_access_setup failed assigning role 'member' for user {$user_id} on main site {$main_site_id}");
            return false;
        }

    restore_current_blog();

    // Add user and set role on subsite (currently hardcoded ID 2).
    $subsite_id = 2;
        if (!is_user_member_of_blog((int) $user_id, $subsite_id)) {
            add_user_to_blog($subsite_id, (int) $user_id, 'member');
        } else {
            switch_to_blog($subsite_id);
            $subsite_user = new WP_User((int) $user_id);
            if ($subsite_user && 0 !== (int) $subsite_user->ID) {
                $subsite_user->set_role('member');
            }
            restore_current_blog();
        }
}
