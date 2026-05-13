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
$bagis_link = home_url("/faq/varfor-bagis/");

if (is_user_logged_in()) { 

    $current_user = wp_get_current_user();
    $user_roles = (array) $current_user->roles;

    // Member
    if (in_array('member', $user_roles, true)) {
        $message = '<div class="wpum-message information">
                    <p>Gå till ditt område: <span class="link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span>&nbsp;<span class="link"><a href="'.esc_url( home_url( '/12833/' ) ).'">📍 Skarpnäck</a></span></p>
                    </div>';
    }

    // Member pending
    if (in_array('member_pending', $user_roles, true)) {
        $message = '<div class="wpum-message information">
                    <p>🙏 Tack för din ansökan om medlemskap!</p>
                    <p class="small">När vi har registrerat din medlemsavgift får du ett mail.</p>
                    </div>';
    }

    // Member earlier
    elseif (in_array('member_earlier', $user_roles, true)) {
        $message = '<div class="wpum-message warning">
                    <p>Du behöver förnya ditt medlemskap för att fortsätta använda LOOPIS. ✨</p>
                    <p><span class="big-link"><a href="'.esc_url( $renew_link ).'">🌈 Förnya medlemskap</a></span></p>
                    </div>';
    }

    // Member outside
    elseif (in_array('member_outside', $user_roles, true)) {
        $message = '<div class="wpum-message information">
                    <p>🙏 Tack för att du stöttar LOOPIS med ditt medlemskap!</p>
                    <p>Vi hoppas att du snart kan använda LOOPIS där du bor.</p>
                    <p><span class="link"><a href="'.esc_url( $bagis_link ).'">📌 Varför måste jag bo i Bagarmossen?</a></span></p>
                    </div>';
    }

    // Admin
    elseif (in_array('administrator', $user_roles, true)) {
        $message = '<div class="wpum-message information">
                    <p>🐙 Du är inloggad som administratör.</p>
                    <p><span class="link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span>&nbsp;<span class="link"><a href="'.esc_url( home_url( '/12833/' ) ).'">📍 Skarpnäck</a></span>&nbsp;<span class="link"><a href="'.esc_url( home_url( '/wp-admin/' ) ).'">🔧 WP-admin</a></span></p>
                    </div>';
    }

} else {
    // Not logged in
    $message = '<p><span class="big-link"><a href="'.esc_url(wp_login_url(home_url())).'">👤 Logga in</a></span></p>
                <p><span class="big-link"><a href="'.esc_url(wp_registration_url()).'">📋 Bli medlem</a></span></p>';
}

// Output the message if it exists
if (!empty($message)) {
    echo $message;
}