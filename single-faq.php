<?php
/**
 * Template for single FAQ post.
 * 
 * Copy from local theme FAQ post layout.
 */

get_header(); ?>

<div class="page-padding center">

<?php the_content(); ?>
<div class="clear"></div>

<!-- More questions? -->
<?php include LOOPIS_THEME_HQ_DIR . '/templates/faq/questions-faq.php'; ?>

</div><!--page-padding center-->

<?php get_footer(); ?>