<?php
/**
 * Status messages for user/visitor.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_user_logged_in()) { 

    // Member
    if (current_user_can('member')) { 
        echo '<p>Det här är LOOPIS startsida. Här ser du alla områden, din profil, samt frågor & svar.</p>';
        echo '<div class="loopis-message success">';
        echo '<p>Gå till ditt område för att loopa:</p>';
        echo '<p><span class="big-link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span></p>';
        echo '</div>';
    }

    // Member pending or earlier
    elseif (current_user_can('member_pending') || current_user_can('member_earlier')) { 
        // Output prompts
        echo '<div class="loopis-message information">';
        echo '<p>⏳ Du behöver komplettera ditt medlemskap.</p>';
            // Missing membership payment prompt
            if (!$member_status['member_payment_complete']) {
                echo '<p><span class="big-link">💳 <a href="'.esc_url(network_site_url( '/shop/?option=membership-stripe' )).'">Betala medlemskap</a></span> för att börja loopa.</p>';
            }
            // Missing member data prompt
            if (!$member_status['member_data_complete']) {
                echo '<p><span class="big-link">📋 <a href="' . esc_url(home_url('/user/?option=member-data')) . '">Medlemsregister</a></span> saknar uppgifter.</p>';
            }
        echo '</div>';
    }

    // Member outside
    elseif (current_user_can('member_outside')) { 
        $options = '<p>🙏 Tack för att du stöttar LOOPIS med ditt medlemskap!</p>
                    <p>Vi hoppas att du i framtiden kan använda föreningens tjänster där du bor.</p>
                    <p><span class="link"><a href="'.esc_url(network_site_url('/faq/varfor-bagis/')).'">📌 Varför måste jag bo i Bagarmossen?</a></span></p>';
    }

    // Super Admin
    elseif (is_super_admin()) {
        echo '<div class="loopis-message information">';
        echo '<p>😈 Du är inloggad som WordPress super-admin.</p>';
        echo '<p>Du har tillgång till alla områden:</p>';
        echo '<p><span class="link"><a href="'.esc_url( home_url( '/12845/' ) ).'">📍 Bagarmossen</a></span> <span class="link"><a href="'.esc_url( home_url( '/12833/' ) ).'">📍 Skarpnäck</a></span></p>';
        echo '<p><span class="link"><a href="'.esc_url( home_url( '/wp-admin/' ) ).'">🔧 WP-admin</a></span> <span class="link"><a href="'.esc_url( wp_logout_url(home_url()) ).'">🚪 Logga ut</a></span></p>';
        echo '</div>';
    } 

    // Not logged in
    } else {
    echo '<p><span class="big-link"><a href="'.esc_url(get_loopis_login_url()).'">👤 Logga in</a></span> om du är medlem.</p>';
    echo '<p><span class="big-link"><a href="'.esc_url(get_signup_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>';
    echo '<p><span class="big-link"><a href="'.esc_url(home_url('/faq/hur-funkar-loopis/')).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
    include LOOPIS_THEME_HQ_DIR . '/templates/faq/loopis-concept.php';
}