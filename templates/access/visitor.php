<?php
/**
 * Messages for user/visitor.
 * 
 * Improvements:
 * - Revise to work with WordPress multisite and membership on different sites.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize message
$message = '';
$renew_link = home_url("/renew/");


// Not logged in
$message = '<p><span class="big-link"><a href="'.esc_url(wp_login_url(home_url())).'">👤 Logga in</a></span> om du är medlem.</p>
            <p><span class="big-link"><a href="'.esc_url(wp_registration_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>
            <p><span class="big-link"><a href="/faq/hur-funkar-LOOPIS">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';

// Output the message if it exists
if (!empty($message)) {
    echo $message;
}