<?php
/**
 * Private Area Access Setup
 * 
 * Handles access to private areas via special invite cookies and QR codes.
 * This is completely separate from public area selection.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check whether the special invite cookie is set.
 */
function loopis_has_special_invite_cookie() {
    return isset( $_COOKIE['special_invite_payload'] );
}

/**
 * Get blog_id from special invite cookie.
 * Safely decodes and validates the base64-encoded blog_id.
 */
function loopis_get_blog_id_from_cookie() {
    if ( ! loopis_has_special_invite_cookie() ) {
        return 0;
    }

    $encoded_blog_id = wp_unslash( $_COOKIE['special_invite_payload'] );
    $decoded_blog_id = base64_decode( $encoded_blog_id, true );

    if ( false === $decoded_blog_id || ! ctype_digit( (string) $decoded_blog_id ) ) {
        return 0;
    }

    return absint( $decoded_blog_id );
}

/**
 * Set up user access to a private area.
 * 
 * This handles the complete private area access setup:
 * 1. Check member status (registry + payment)
 * 2. Add payment entry if needed
 * 3. Set appropriate role (member_pending or member)
 * 4. Add/update user on the private blog
 * 5. Set as primary blog
 * 
 * @param int $user_id The user to grant access to
 * @param int $blog_id The private blog to grant access to
 * @param string $blog_name Optional blog name for payment description
 */
function loopis_setup_private_area_access( $user_id, $blog_id, $blog_name = '' ) {
    $user_id = (int) $user_id;
    $blog_id = (int) $blog_id;

    if ( $user_id <= 0 || $blog_id <= 0 ) {
        return false;
    }

    if ( ! is_user_logged_in() || get_current_user_id() !== $user_id ) {
        return false;
    }

    // Get member status (registry + payment).
    require_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php';
    $member_status = member_pending_check( $user_id );

    // Determine role based on member data completion.
    $role_slug = $member_status['member_data_complete'] ? 'member' : 'member_pending';

    // Add payment entry if the user doesn't have one.
    if ( ! $member_status['member_payment_complete'] ) {
        require_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-form-handler.php';
        add_membership( $user_id, [
            'description' => $blog_name ?: get_blog_option( $blog_id, 'blogname' ),
            'blog_id'     => $blog_id,
            'payment'     => 0,
        ] );
    }

    // Update user's role on main site.
    $user = get_userdata( $user_id );
    if ( $user ) {
        $user->set_role( $role_slug );
    }

    // Add or update user's role on private blog.
    switch_to_blog( $blog_id );
    if ( is_user_member_of_blog( $user_id, $blog_id ) ) {
        $user = get_userdata( $user_id );
        if ( $user ) {
            $user->add_role( $role_slug );
        }
    } else {
        add_user_to_blog( $blog_id, $user_id, $role_slug );
    }
    restore_current_blog();

    // Set the private area as the user's primary blog.
    update_user_meta( $user_id, 'primary_blog', $blog_id );

    return true;
}

/**
 * Process special invite cookie and add user to private area.
 * 
 * This function handles the complete private area access flow for logged-in users:
 * 1. User already logged in after signup
 * 2. Detects the special_invite_payload cookie from signup redirect
 * 3. Calls loopis_setup_private_area_access() to grant access
 * 4. Clears the cookie after processing
 * 
 * Only call this function if the special invite cookie is present.
 */
function loopis_process_special_invite_cookie() {
    if ( ! is_user_logged_in() ) {
        return;
    }

    $blog_id = loopis_get_blog_id_from_cookie();

    if ( $blog_id <= 0 ) {
        return;
    }

    $user_id = get_current_user_id();
    if ( $user_id <= 0 ) {
        return;
    }

    loopis_setup_private_area_access( $user_id, $blog_id );

    setcookie( 'special_invite_payload', '', time() - 3600, '/' );
}
