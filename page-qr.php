<?php
/**
 * Landing page for QR codes that logs scans and redirects to the target URL
 */

/**
 * Extract UID from the URL parameter.
 */
$uid = isset($_GET['x']) ? (string) $_GET['x'] : '';

/**
 * Get database configuration if the UID is not empty.
 */
if($uid!==''){
    global $wpdb;
    $table = $wpdb->base_prefix . 'loopis_qr_visits';
    $table2 = $wpdb->base_prefix . 'loopis_qr_codes';
    
    /**
     * Get the specific QR code configuration from the database.
     * - uid: Unique identifier for the QR code
     * - name: Name/description of the QR code
     * - blog_id: Area this QR code is associated with
     * - redirect: Target URL to redirect to (area page, special signup, etc.)
     */
    $row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table2} WHERE uid = %s",
            $uid
        ),
    ARRAY_A
    );

    /**
     * Log the visit to track QR code usage.
     */
    $wpdb->insert($table, 
        [
        'blog_id' => $row['blog_id'] ?? '',
        'name' => $row['name'] ?? '',
        'timestamp' =>current_time('Y-m-d H:i:s'),
        'redirect' =>$row['redirect'],
        ],
        ['%s', '%s', '%s', '%s']
    );
}

/**
 * Redirect to the configured target URL (or home if QR code is invalid)
 */
wp_safe_redirect( $row['redirect'] ?? get_home_url( 1, '/' ) );
exit;
