<?php
/**
 * The main template file.
 *
 * This is the fallback template in WordPress.
 * It is used to display content when no specific template matches a query.
 *
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">
        <h1>Hoppsan!</h1>
        <p>Det verkar inte finnas något här. Prova att gå tillbaka till <a href="<?php echo esc_url(home_url('/')); ?>">förstasidan</a>.</p>

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>