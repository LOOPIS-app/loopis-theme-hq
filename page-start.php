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
        $user_primary_blog = get_usermeta($user_id, 'primary_blog', true);
        
        // Check member data and payment
        if (array_intersect(array('member_pending', 'member_earlier', 'member_archived', 'member_support'), $user_roles)) {
            include LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php'; 
            $member_status = member_pending_check($user_id);
            }
        }
    
    // Greeting & message
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-greeting.php';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-message.php';

    // Member output
    if (in_array('member', $user_roles, true)) { 
        // New member without primary blog selection
        if ( empty($user_primary_blog) ) {
            include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/area-selection.php';
            include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/user-zipcode.php';
            include LOOPIS_THEME_HQ_DIR . '/includes/output/faq/faq-member.php';
        }

        // Member with mainsite selected
        elseif ( $user_primary_blog == '1' ) {
            include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/user-zipcode.php';
            include LOOPIS_THEME_HQ_DIR . '/includes/output/faq/faq-member.php';
        }

        // Member with subsite selected
        elseif ( $user_primary_blog > '1' ) {
            include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/areas-user.php';
            include LOOPIS_THEME_HQ_DIR . '/includes/output/faq/faq-user.php';
        }
    }

    // Member-pending output
    if (array_intersect(array('member_pending', 'member_earlier', 'member_archived', 'member_support'), $user_roles)) {
        include LOOPIS_THEME_HQ_DIR . '/includes/output/faq/faq-member-pending.php';
    }

    // Administrator output
    if (current_user_can('manage_options')) { 
        include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/areas-user.php';
        include LOOPIS_THEME_HQ_DIR . '/includes/output/faq/faq-user.php';
    }

// Visitor output
if ( !is_user_logged_in() ) {    
    echo '<h2>📍Var finns LOOPIS?</h2>';
    echo '<hr>';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/areas-all.php';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/faq/faq-visitor.php';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/supporters.php';
}
?>    

</div><!--page-padding-->

<?php get_footer(); ?>