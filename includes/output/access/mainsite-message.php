<?php
/**
 * Mainsite message depending on role.
 * 
 * Passed from page-start.php:
 * $user_roles
 * $member_status
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_user_logged_in()) { 

    // Member
    if (in_array('member', $user_roles, true)) {
        echo '<div class="loopis-message success">';
        echo '<p>Detta är LOOPIS startsida. Här hittar du info om ditt medlemskap och vanliga frågor.</p>';
        echo '</div>';
        echo '<p>Vill du loopa? Tryck på ditt område nedan.</p>';
    }

    // Member pending
    elseif (in_array('member_pending', $user_roles, true)) {
        echo '<div class="loopis-message information">';
        echo '<p>⏳ Du behöver komplettera ditt medlemskap.</p>';
            // Missing payment prompt
            if (!$member_status['member_payment_complete']) {
                echo '<p><span class="big-link">💳 <a href="'.esc_url(home_url( '/shop/?option=membership-stripe' )).'">Betala medlemskap</a></span> för att börja loopa.</p>';
            }
            // Missing member data prompt
            if (!$member_status['member_data_complete']) {
                echo '<p><span class="big-link">📋 <a href="'.esc_url(home_url('/user/?view=member-data')) . '">Medlemsregister</a></span> saknar uppgifter.</p>';
            }
        echo '</div>';
    }

    // Member archived (or earlier)
    elseif (array_intersect(array('member_archived', 'member_earlier'), $user_roles)) {
        echo '<div class="loopis-message information">';
        echo '<p>🕸️ Du behöver förnya ditt medlemskap.</p>';
            // Missing payment prompt
            if (!$member_status['member_payment_complete']) {
                echo '<p><span class="big-link">💳 <a href="'.esc_url(home_url( '/shop/?option=membership-stripe' )).'">Betala medlemskap</a></span> för att börja loopa.</p>';
            }
            // Missing member data prompt
            if (!$member_status['member_data_complete']) {
                echo '<p><span class="big-link">📋 <a href="'.esc_url(home_url('/user/?view=member-data')) . '">Medlemsregister</a></span> saknar uppgifter.</p>';
            }
        echo '</div>';
    }

    // Member supporting
    elseif (in_array('member_support', $user_roles, true)) { 
        echo '<div class="loopis-message information">';
        echo '<p>🙏 Du är registrerad som stödmedlem. Stort TACK för ditt stöd!</p>';
        echo '<p><span class="link"><a href="'.esc_url(home_url('/faq/var-finns-loopis/')).'">📌 Var finns LOOPIS?</a></span></p>';
        echo '</div>';
        echo '<p>Vi hoppas att du snart kan använda LOOPIS där du bor.</p>';
    }

    // Super Admin
    elseif (is_super_admin()) {
        echo '<div class="admin-block">';
        echo '<p>😈 Du är inloggad som multisite <strong>Super Admin</strong>.</p>';
        echo '<p><span class="big-link"><a href="'.esc_url( home_url( '/wp-admin/' ) ).'">🔧 WP-admin</a></span> <span class="big-link"><a href="'.esc_url( wp_logout_url(home_url()) ).'">🚪 Logga ut</a></span></p>';
        echo '</div>';
    } 

     // Administrator
    elseif (in_array('administrator', $user_roles, true)) {
        echo '<div class="admin-block">';
        echo '<p>🤖 Du bör inte göra någonting med detta konto.</p>';
        echo '<p>Så: <span class="link"><a href="'.esc_url( wp_logout_url(home_url()) ).'">🚪 Logga ut!</a></span></p>';
        echo '</div>';
    }

    // Not logged in
    } else {
    echo '<p><span class="big-link"><a href="'.esc_url(get_loopis_login_url()).'">👤 Logga in</a></span> om du är medlem.</p>';
    echo '<p><span class="big-link"><a href="'.esc_url(get_signup_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>';
    echo '<p><span class="big-link"><a href="'.esc_url(home_url('/faq/hur-funkar-loopis/')).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
    include LOOPIS_THEME_HQ_DIR . '/templates/faq/loopis-concept.php';
}