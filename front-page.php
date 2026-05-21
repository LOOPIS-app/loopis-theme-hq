<?php
/**
 * Front page template
 * Displays sweden map + form for sign-up.
 */
?>

<?php get_header(); ?>
<div class="page-padding">

<?php include_once LOOPIS_THEME_HQ_DIR . '/templates/access/role-welcome.php'; ?>
<?php include_once LOOPIS_THEME_HQ_DIR . '/templates/access/role-status.php'; ?>

<div class="frontpage-map">
            <div class="frontpage-map__legend">
                <h3>🗺 Karta</h3>
                <hr>
                <p>❤ Här finns LOOPIS</p>
                <p>🧡 Här öppnar snart LOOPIS</p>
                <!--p>💚 Här finns intresse för LOOPIS</p-->
            </div>
            <img src="<?php echo esc_url(LOOPIS_THEME_HQ_URI . '/assets/img/map_sweden.svg'); ?>" alt="Sverige" class="sweden-map">
        </div><!-- frontpage-map -->

</div><!--page-padding-->

<?php get_footer(); ?>