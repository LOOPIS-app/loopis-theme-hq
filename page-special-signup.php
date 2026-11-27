<?php
/**
 * Set up access to private area via QR Code
 * 
 * 1. User scans QR code /qr/?x=123 → redirects to /special-signup/?spt=CODE
 * 2. This page validates the code and grants access to the private area
 * 
 * For logged-in users: Add/update access to the private area
 * For non-logged-in users: Set a cookie and redirect to signup
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

// Extract and sanitize the invite code from URL parameter.
$code = isset($_GET['spt']) ? (string) $_GET['spt'] : '';
$code = preg_replace('/[^A-Za-z0-9_-]/', '', $code);
if ($code === '') {
    wp_safe_redirect(home_url('/start/'));
    exit;
}

// Validate the invite code against the hash stored in wp_options.
$expected_hash = get_option('special_invite_hash', '');
if (!$expected_hash) {
    wp_safe_redirect(home_url('/start/'));
    exit;
}
if (!hash_equals($expected_hash, hash('sha256', $code))) {
    wp_safe_redirect(home_url('/start/'));
    exit;
}

// Get the blog_id associated with the invite code.
global $wpdb;
$table = $wpdb->base_prefix . 'loopis_qr_codes';
$qr_config = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT blog_id FROM {$table} WHERE redirect LIKE %s LIMIT 1",
        '%spt=' . $code
    ),
    ARRAY_A
);

// Get subsite details if QR config is valid.
if (!$qr_config || empty($qr_config['blog_id'])) {
    wp_safe_redirect(home_url('/start/'));
    exit;
} else {
    $blog_id = (int) $qr_config['blog_id'];
    $blog_name = get_blog_option( $blog_id, 'blogname' );
    $blog_url = get_blog_option( $blog_id, 'home' );
}

/**
 * LOGGED IN?
 * Check member registry and payment.
 * Add/update access to the private subsite.
 */
if (is_user_logged_in()){
    $user_id = get_current_user_id();

    require_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/area-access-setup-private.php';
    loopis_setup_private_area_access( $user_id, $blog_id, $blog_name );

    // Redirect to the private area
    wp_safe_redirect(network_site_url('/' . $blog_url . '/'));
    exit;
}

/**
 * NOT LOGGED IN?
 * Create a cookie with the private area info.
 * Cookie will be detected to skip the normal area selector and run the special signup flow.
 */
$payload = $blog_id; // Currently stores blog_id; can be extended to include role

setcookie(
    'special_invite_payload',
    base64_encode($payload),
    [
        'expires'  => time() + 60 * 60 * 24,  // 24 hours
        'path'     => '/',
        'secure'   => is_ssl(),               // Only send over HTTPS
        'httponly' => true,                   // Not accessible via JavaScript
        'samesite' => 'Lax',                  // CSRF protection
    ]
);

// Redirect to the signup page.
wp_safe_redirect(network_site_url('/platform24/'));
exit;