<?php
/**
 * Status messages for user/visitor.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize message
$message = '';
$target_url = isset($login_url) && '' !== (string) $login_url
    ? (string) $login_url
    : wp_login_url(home_url());

if (is_user_logged_in()) { 

    $current_user = wp_get_current_user();
    $user_roles = (array) $current_user->roles;

    // Member
    if (in_array('member', $user_roles, true)) {
        $message = '<p>Det här är LOOPIS startsida. Här ser du alla områden, din profil, samt frågor & svar.</p>
                    <div class="loopis-message success">
                    <p>Gå till ditt område för att loopa:</p>
                    <p><span class="big-link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span>
                    </div>';
    }

    // Super Admin (multisite capability, not a role slug)
    elseif (is_super_admin()) {
        $message = '<div class="loopis-message information">
                    <p>😈 Du är inloggad som WordPress super-admin.</p>
                    <p>Du har tillgång till alla områden:</p>
                    <p><span class="link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span> <span class="link"><a href="'.esc_url( home_url( '/12833/' ) ).'">📍 Skarpnäck</a></span></p>
                    <p><span class="link"><a href="'.esc_url( home_url( '/wp-admin/' ) ).'">🔧 WP-admin</a></span> <span class="link"><a href="'.esc_url( wp_logout_url(home_url()) ).'">🚪 Logga ut</a></span></p>
                    </div>';
    } 
    } else {
    // Not logged in
    $message = '<p><span class="big-link"><a href="'.esc_url(get_loopis_login_url()).'">👤 Logga in</a></span> om du är medlem.</p>
                <p><span class="big-link"><a href="'.esc_url(get_signup_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>
                <p><span class="big-link"><a href="'.esc_url(home_url('/faq/hur-funkar-loopis/')).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
}

// Output the message if it exists
if (!empty($message)) {
    echo $message;
}