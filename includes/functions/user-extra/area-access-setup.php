<?php
/**
 * Public Area Access Setup
 * 
 * Handles manual selection and access to public areas by logged-in members.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add user to the specified subsites with role "member"
 */
function area_access_setup($user_id, $blog_id) {
    // Add user and set role on subsite.
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
    // Set the area as the user's primary blog.
    update_user_meta( $user_id, 'primary_blog', $blog_id );
}