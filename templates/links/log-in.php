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
echo '<p><span class="big-link"><a href="' . esc_url($target_url) . '">👤 Logga in</a></span></p>';