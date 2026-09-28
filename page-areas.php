<?php
/**
 * Areas page
 * 
 * Outputs blog posts matching subsites available to the current user, linking to the blog post.
 */

get_header(); ?>

<div class="page-padding center">

    <h1>📍 Områden</h1>
    <p class="small">💡 Områden där LOOPIS finns - och är på gång.</p>
    
    <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/areas/all-areas.php'; ?>

    <?php if ( current_user_can('manage_options') || current_user_can('loopis_admin') ) : ?>
        <p class="small">💡 Privata områden visas bara för de som har tillgång.</p>
    <?php endif; ?>
</div><!--page-padding-->

<?php get_footer(); ?>