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
        if ( empty($user_primary_blog) ) {
            // Member with no primary blog (new member)
            echo '<div class="loopis-message success">';
            echo '<p>✅ Ditt medlemskap är aktiverat!</p>';
            echo '</div>';
        } elseif ( $user_primary_blog == '1' ) {
            // Member with mainsite as primary blog
            echo '<div class="loopis-message success">';
            echo '<p>LOOPIS finns inte i ditt område ännu. Vi meddelar dig när ni är 100 medlemmar. Hjälp till att bli fler! </p>';
            echo '<span class="mega-link"><a href="'.esc_url(home_url('stats/?view=pages/postareas')).'">⚡️ Stötta område</a></span></p>';
            echo '</div>';
        } elseif ( $user_primary_blog > '1' ) {
            // Member with a subsite as primary blog
            echo '<div class="loopis-message success">';
            echo '<p>Detta är LOOPIS startsida. Här hittar du info om ditt medlemskap och vanliga frågor.</p>';
            echo '</div>';
        }
    }

    // Member pending
    elseif (in_array('member_pending', $user_roles, true)) {
        echo '<div class="loopis-message information">';
        echo '<p>⏳ Du behöver komplettera ditt medlemskap.</p>';
            // Missing payment prompt
            if (!$member_status['member_payment_complete']) {
                echo '<p><span class="big-link">💳 <a href="'.esc_url(home_url( '/shop/?option=membership-stripe' )).'">Medlemsavgift</a></span> är ej betalad.</p>';
            }
            // Missing member data prompt
            if (!$member_status['member_data_complete']) {
                echo '<p><span class="big-link">📋 <a href="'.esc_url(home_url('/user/?view=member-data')) . '">Medlemsregister</a></span> saknar uppgifter.</p>';
            }
        echo '</div>';
    }

    // Member archived/earlier
    elseif (array_intersect(array('member_archived', 'member_earlier'), $user_roles)) {
        echo '<div class="loopis-message information">';
        echo '<p>⌛️ Du behöver förnya ditt medlemskap.</p>';
            // Missing payment prompt
            if (!$member_status['member_payment_complete']) {
                echo '<p><span class="big-link">💳 <a href="'.esc_url(home_url( '/shop/?option=membership-stripe' )).'">Medlemsavgift</a></span> är ej betalad.</p>';
            }
            // Missing member data prompt
            if (!$member_status['member_data_complete']) {
                echo '<p><span class="big-link">📋 <a href="'.esc_url(home_url('/user/?view=member-data')) . '">Medlemsregister</a></span> saknar uppgifter.</p>';
            }
        echo '</div>';
    }

    // Super Admin
    elseif (is_super_admin()) {
        echo '<div class="admin-block">';
        echo '<p>😈 Du är inloggad som multisite super admin.</p>';
        echo '<p><span class="big-link"><a href="'.esc_url( home_url( '/wp-admin/' ) ).'">🔧 WP-admin</a></span> <span class="big-link"><a href="'.esc_url( get_loopis_logout_url() ).'">🚪 Logga ut</a></span></p>';
        echo '</div>';
    } 

     // Administrator
    elseif (in_array('administrator', $user_roles, true)) {
        echo '<div class="admin-block">';
        echo '<p>🤖 Du bör inte göra någonting med detta konto.</p>';
        echo '<p>Så: <span class="link"><a href="'.esc_url( get_loopis_logout_url() ).'">🚪 Logga ut!</a></span></p>';
        echo '</div>';
    }

    // Not logged in
    } else {
    echo '<p><span class="mega-link"><a href="'.esc_url(get_loopis_login_url()).'">👤 Logga in</a></span> om du är medlem.<br>';
    echo '<span class="mega-link"><a href="'.esc_url(get_signup_url()).'">📋 Bli medlem</a></span> för att kunna logga in.<br>';
    echo '<span class="mega-link"><a href="'.esc_url(home_url('/faq/hur-funkar-loopis/')).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
}