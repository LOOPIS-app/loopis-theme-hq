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

    // Member pending
    if (current_user_can('member_pending')) {
        $message = '<div class="wpum-message information">
                    <p>🙏 Tack för din ansökan om medlemskap!</p>
                    <p class="small">När vi har registrerat din medlemsavgift får du ett mail.</p>
                    </div>';
    }

    // Member earlier
    elseif (current_user_can('member_earlier')) {
        $message = '<div class="wpum-message warning">
                    <p>Du behöver förnya ditt medlemskap för att fortsätta använda LOOPIS. ✨</p>
                    <p><span class="big-link"><a href="'.esc_url( $renew_link ).'">🌈 Förnya medlemskap</a></span></p>
                    </div>';
    }

    // Member outside
    elseif (current_user_can('member_outside')) {
        $message = '<div class="wpum-message information">
                    <p>🙏 Tack för att du stöttar LOOPIS med ditt medlemskap!</p>
                    <p>Vi hoppas att du i framtiden kan använda föreningens tjänster där du bor.</p>
                    <p><span class="link"><a href="'.esc_url( $bagis_link ).'">📌 Varför måste jag bo i Bagarmossen?</a></span></p>
                    </div>';
    }

} else {
    // Not logged in
    $message = '<p><span class="link"><a href="'.esc_url(wp_login_url(home_url())).'">👤 Logga in</a></span> om du är medlem.</p>
                <p><span class="link"><a href="'.esc_url(wp_registration_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>
                <p><span class="link"><a href="'.esc_url( $bagis_link ).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
}

// Output the message if it exists
if (!empty($message)) {
    echo $message;
}