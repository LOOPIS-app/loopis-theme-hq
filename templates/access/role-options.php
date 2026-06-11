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

    // Member pending
    elseif (in_array('member_pending', $user_roles, true)) {
        $message = '<div class="loopis-message warning">
                    <p>⏳ Du har inte betalat medlemsavgiften ännu?</p>
                    <p><span class="big-link">💳 <a href="'.esc_url(home_url('/shop/?option=membership-stripe')).'">Tryck här</a></span> för att betala medlemskap.</p>
                    </div>';
    }

    // Member earlier
    elseif (in_array('member_earlier', $user_roles, true)) {
        $message = '<div class="loopis-message warning">
                    <p>Du behöver förnya ditt medlemskap för att fortsätta använda LOOPIS. ✨</p>
                    <p><span class="big-link"><a href="'.esc_url( home_url( '/renew/' ) ).'">🌈 Förnya medlemskap</a></span></p>
                    </div>';
    }

    // Member outside
    elseif (in_array('member_outside', $user_roles, true)) {
        $message = '<div class="loopis-message information">
                    <p>🙏 Tack för att du stöttar LOOPIS med ditt medlemskap!</p>
                    <p>Vi hoppas att du snart kan använda LOOPIS där du bor.</p>
                    <p><span class="link"><a href="'.esc_url( home_url( '/faq/varfor-bagis/' ) ).'">📌 Varför måste jag bo i Bagarmossen?</a></span></p>
                    </div>';
    }

    // Super Admin (multisite capability, not a role slug)
    elseif (is_multisite() && is_super_admin($current_user->ID)) {
        $message = '<div class="loopis-message information">
                    <p>😈 Du är inloggad som WordPress super-admin.</p>
                    <p>Du har tillgång till alla områden:</p>
                    <p><span class="link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span> <span class="link"><a href="'.esc_url( home_url( '/12833/' ) ).'">📍 Skarpnäck</a></span></p>
                    <p><span class="link"><a href="'.esc_url( home_url( '/wp-admin/' ) ).'">🔧 WP-admin</a></span> <span class="link"><a href="'.esc_url( wp_logout_url(home_url()) ).'">🚪 Logga ut</a></span></p>
                    </div>';
    }

    // Admin
    elseif (in_array('administrator', $user_roles, true)) {
        $message = '<div class="loopis-message information">
                    <p>🛠️ Du är inloggad som WordPress administrator.</p>
                    <p><span class="link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span>&nbsp;<span class="link"><a href="'.esc_url( home_url( '/12833/' ) ).'">📍 Skarpnäck</a></span>&nbsp;<span class="link"><a href="'.esc_url( home_url( '/wp-admin/' ) ).'">🔧 WP-admin</a></span></p>
                    </div>';
    }

} else {
    // Not logged in
    $message .= '<p><span class="big-link"><a href="/faq/hur-funkar-LOOPIS">📌 Hur funkar LOOPIS?</a></span></p>
                <p><span class="big-link"><a href="' . esc_url($target_url) . '">👤 Logga in</a></span></p>
                <p><span class="big-link"><a href="' . esc_url(wp_registration_url()) . '">📋 Bli medlem</a></span></p>';
}

// Output the message if it exists
if (!empty($message)) {
    echo $message;
}