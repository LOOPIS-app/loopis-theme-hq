<?php
/**
 * LOOPIS main site front page
 * 
 * Displays user options + list available areas.
 */

get_header(); ?>

<div class="page-padding center">

    <?php 
    // Get current user and roles
    $user_id = get_current_user_id();
    $user_roles = array();
    if ( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $user_roles = (array) $user->roles;
    $user_firstname = $user->first_name;
    }

    // For member_pending; check member data and payment
    if ( is_user_logged_in() && in_array('member_pending', $user_roles, true) )  {
        include LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php'; 
        $user_id = get_current_user_id();
        $member_status = member_pending_check($user_id);
        }
    
    // Output greeting + options
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/role-greeting-main.php';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/role-options-main.php';

    // Display areas based on user login status
    if ( is_user_logged_in() ) {
        // List all areas the user has access to
        include LOOPIS_THEME_HQ_DIR . '/includes/output/areas/user-areas.php';
    } else {
        // List all areas available to visitor
        echo '<h2>📍 Var finns LOOPIS?</h2>';
        echo '<p class="small">💡 Områden där LOOPIS finns - och är på gång.</p>';
        include LOOPIS_THEME_HQ_DIR . '/includes/output/areas/all-areas.php';
    }
?>

</div><!--page-padding center-->

<?php get_footer(); ?>