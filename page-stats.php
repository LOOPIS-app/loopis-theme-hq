<?php
/**
 * Dynamic content for pages using url /stats/?view=filename
 */
?>

<?php get_header(); ?>

<div class="page-padding center">
    
    <?php if ( is_user_logged_in() ) : 
    // Get current user ID
    $user_id = get_current_user_id();
    $user = wp_get_current_user();
    $user_roles = (array) $user->roles;
    endif;

    // Dynamic page loader 
    $page_dir = LOOPIS_THEME_HQ_DIR . '/pages/stats/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'start';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
        echo '<h1>📊 Statistik</h1>';
        echo '<hr>';
        include LOOPIS_THEME_DIR . '/includes/output/access/loopis-404.php';
    }
    ?>

</div><!--page-padding center-->

<?php get_footer();