<?php
/**
 * Dynamic content for pages using url /user/?option=
 * 
 * Content is fetched from "LOOPIS Theme"
 */
?>

<?php get_header(); ?>

<div class="page-padding center">
    <h1>👤 Min profil</h1>
<?php if ( is_user_logged_in() ) : 
    // Shared dynamic page loader from LOOPIS Theme.
    $page_dir = LOOPIS_THEME_DIR . '/pages/user/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['option']) ? sanitize_file_name($_GET['option']) : 'tabs';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
        echo '<h1>👤 Min profil</h1><hr>';
        include LOOPIS_THEME_DIR . '/includes/output/access/loopis-404.php';
    }
    ?>
    <div class="clear"></div>

<?php else :
// Not logged in message
echo '<hr>';
include LOOPIS_THEME_DIR . '/includes/output/access/only-user.php';
include LOOPIS_THEME_HQ_DIR . '/includes/output/access/role-options.php';
endif; ?>

</div><!--page-padding center-->

<?php get_footer();