<?php
/**
 * LOOPIS mainsite front page
 * 
 * Displays message + list of available areas.
 */

get_header(); ?>

<div class="page-padding center">

    <?php 
    // Get current user
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $user = wp_get_current_user();
        $user_roles = (array) $user->roles;
        $user_firstname = $user->first_name;
        
        // Check member data and payment
        if (array_intersect(array('member_pending', 'member_earlier', 'member_archived', 'member_support'), $user_roles)) {
            include LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php'; 
            $member_status = member_pending_check($user_id);
            }
        }
    
    // Greeting & message for everyone
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-greeting.php';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-message.php';

    // Display areas
    if ( is_user_logged_in() ) {
        include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/areas-user.php';
    } else {
        echo '<h2>📍Var finns LOOPIS?</h2>';
        include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/areas-all.php';
    }

    // Display FAQ posts
    if ( is_user_logged_in() ) {
    include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/faq-member.php';
    } else {
        include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/faq-visitor.php';
    }  

    // Display supporters
    if ( !is_user_logged_in() ) {
        include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/supporters.php';
    }
    ?>

</div><!--page-padding center-->

<?php get_footer(); ?>