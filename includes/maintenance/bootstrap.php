<?php
/**
 * Minimal maintenance gate for LOOPIS HQ frontend.
 */

if (!defined('ABSPATH')) { exit; }

add_action('wp_loaded', function() {
    header('HTTP/1.1 Service Unavailable', true, 503);
    header('Content-Type: text/html; charset=utf-8');
    header('Retry-After: 3600');

    $maintenance_file = __DIR__ . '/maintenance.php';
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
