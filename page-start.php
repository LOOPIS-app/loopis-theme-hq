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
        if (array_intersect(array('member_pending', 'member_earlier', 'member_archived'), $user_roles)) {
            include LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php'; 
            $member_status = member_pending_check($user_id);
            }
        }
    
    // Output greeting + message
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-greeting.php';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-message.php';

    // Display areas
    if ( is_user_logged_in() ) {
        include LOOPIS_THEME_HQ_DIR . '/includes/output/areas/user-areas.php';
    } else {
        echo '<h2>📍 Var finns LOOPIS?</h2>';
        echo '<p class="small">💡 Områden där LOOPIS finns - och är på gång.</p>';
        include LOOPIS_THEME_HQ_DIR . '/includes/output/areas/all-areas.php';
    }
    ?>

</div><!--page-padding center-->

<?php get_footer(); ?>