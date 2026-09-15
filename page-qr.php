<?php
/**
 * Filter which redirects user after counting qr location.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

$id = isset($_GET['id']) ? (string) $_GET['id'] : '';


if($location!==''){
    global $wpdb;
    $table = $wpdb->base_prefix . 'loopis_qr_visits';
    $table2 = $wpdb->base_prefix . 'loopis_qr_codes';
    $row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table2} WHERE id = %d",
            $id
        ),
    ARRAY_A
    );
    $wpdb->insert($table, 
        [
        'location' => $row['location'] ?? '',
        'name' => $row['name'] ?? '',
        'timestamp' =>current_time('Y-m-d H:i:s'),
        ],
        ['%s', '%s', '%s']
    );
}

wp_safe_redirect( $row['redirect'] ?? get_home_url( 1, '/' ) );
exit;
