<?php
/**
 * Filter which redirects user after counting qr location.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

$uid = isset($_GET['x']) ? (string) $_GET['x'] : '';


if($location!==''){
    global $wpdb;
    $table = $wpdb->base_prefix . 'loopis_qr_visits';
    $table2 = $wpdb->base_prefix . 'loopis_qr_codes';
    $row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table2} WHERE uid = %s",
            $uid
        ),
    ARRAY_A
    );
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

wp_safe_redirect( $row['redirect'] ?? get_home_url( 1, '/' ) );
exit;
