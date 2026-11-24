<?php
/**
 * Dynamic content for pages using url /user/?view=filename
 * 
 * Content is shared to "LOOPIS Theme"
 */
?>

<?php get_header(); ?>

<div class="page-padding center">
    <h1>📋 Mitt medlemskap</h1>
    
    <?php if ( is_user_logged_in() ) : 

    // Get current user ID
    $user_id = get_current_user_id();
    $user = wp_get_current_user();
    $user_roles = (array) $user->roles;

    // Dynamic page loader 
    $page_dir = LOOPIS_THEME_HQ_DIR . '/pages/user/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['view']) ? sanitize_file_name($_GET['view']) : 'start';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
        echo '<hr>';
        include LOOPIS_THEME_DIR . '/includes/output/access/loopis-404.php';
    }
    ?>
    <div class="clear"></div>

<?php else :
// Not logged in message
echo '<hr>';
include LOOPIS_THEME_DIR . '/includes/output/access/only-user.php';
endif; ?>

</div><!--page-padding center-->

<?php get_footer();