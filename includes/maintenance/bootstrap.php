<?php
/**
 * Minimal maintenance gate for LOOPIS HQ frontend.
 */

if (!defined('ABSPATH')) { exit; }

add_action('wp_loaded', function() {
    global $pagenow;

    if ($pagenow === 'wp-login.php' || is_user_logged_in()) {
        return;
    }

    header('HTTP/1.1 Service Unavailable', true, 503);
    header('Content-Type: text/html; charset=utf-8');
    header('Retry-After: 3600');

    $maintenance_file = WP_CONTENT_DIR . '/maintenance.php';
    if (file_exists($maintenance_file)) {
        $maintenance_result = require $maintenance_file;
        if ($maintenance_result === false) {
            return;
        }
    } else {
        echo '<h1>Maintenance</h1><p>The site is temporarily unavailable.</p>';
    }

    exit;
});
