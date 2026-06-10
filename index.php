<?php
/**
 * The main template file.
 *
 * This is the fallback template in WordPress.
 * It is used to display content when no specific template matches a query.
 *
 */

get_header(); ?>

    <div class="page-padding center">
        <h1>Hoppsan!</h1>
        <p>Det verkar inte finnas något här.</p>
        <?php include LOOPIS_THEME_HQ_DIR . '/templates/links/go-back.php'; ?>

    </div><!--page-padding center-->

<?php get_footer(); ?>