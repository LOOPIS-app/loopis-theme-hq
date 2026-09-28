<?php
/**
 * Setup site access and roles for member
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Promote a member from pending access to active member access.
 *
 * The user receives the member role on the main site and on every subsite
 * where they currently have the member_pending role.
 */
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

    // Switch context before changing and verifying the main-site role.
    switch_to_blog($main_site_id);
    $site_user = new WP_User((int) $user_id);
    if (!$site_user || 0 === (int) $site_user->ID) {
            restore_current_blog();
            error_log("LOOPIS: member_access_setup failed loading user {$user_id} on main site {$main_site_id}");
            return false;
        }

    // Replace any existing main-site role with the active member role.
    $site_user->set_role('member');

    // Verify the role update before restoring the original blog context.
    $updated_site_user = get_userdata((int) $user_id);
    if (!$updated_site_user || !in_array('member', (array) $updated_site_user->roles, true)) {
            restore_current_blog();
            error_log("LOOPIS: member_access_setup failed assigning role 'member' for user {$user_id} on main site {$main_site_id}");
            return false;
        }

    restore_current_blog();

    // Find subsites where the user still has pending membership access.
    $blogs = get_blogs_where_user_has_role($user_id, 'member_pending');

    // Add user and set role on subsite.
    foreach($blogs as $blog_id){
        if (!is_user_member_of_blog((int) $user_id, $blog_id)) {
            $added = add_user_to_blog($blog_id, (int) $user_id, 'member');
        } else {
            // Existing subsite members only need their role updated.
            switch_to_blog($blog_id);
            $subsite_user = new WP_User((int) $user_id);
            if ($subsite_user && 0 !== (int) $subsite_user->ID) {
                $subsite_user->set_role('member');
            }
            restore_current_blog();
        }

    }
}

/**
 * Find public, active subsites where a user has a specific role.
 *
 * WordPress stores each subsite's capabilities in separate user-meta keys,
 * so the helper checks the capabilities key belonging to each site.
 */
function get_blogs_where_user_has_role( $user_id, $role = 'member_pending' ) {
    global $wpdb;

    $blogs = get_sites(
        array(
            'number'   => 0,
            'public'   => null,
            'archived' => 0,
            'deleted'  => 0,
            'spam'     => 0,
        )
    );

    // Collect only sites whose stored capabilities include the requested role.
    $matching_blogs = array();

    foreach ( $blogs as $blog ) {
        $capabilities_key = $wpdb->get_blog_prefix( $blog->blog_id ) . 'capabilities';
        $capabilities     = get_user_meta( $user_id, $capabilities_key, true );

        if ( is_array( $capabilities ) && ! empty( $capabilities[ $role ] ) ) {
            $matching_blogs[] = $blog->blog_id ;
        }
    }

    return $matching_blogs;
}