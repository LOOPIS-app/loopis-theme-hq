<?php
/**
 * Show button to log in.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$target_url = isset($login_url) && '' !== (string) $login_url
    ? (string) $login_url
    : wp_login_url(home_url());

// Output
echo '<p><button type="button" class="green" onclick="window.location.href=\'' . esc_url($target_url) . '\'">Logga in</button></p>';