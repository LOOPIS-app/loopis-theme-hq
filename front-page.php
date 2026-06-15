<?php
/**
 * Front page template
 * Displays sweden map + form for sign-up.
 */
?>

<?php get_header(); ?>
<div class="page-padding center">

<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/access/role-greeting-main.php'; ?>
<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/access/role-options-main.php'; ?>
<?php include LOOPIS_THEME_DIR . '/includes/output/access/role-options.php'; ?>
<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/access/member-data.php'; ?>

<?php
        wp_reset_postdata();        
        // Fetch and count available posts
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 50,
            'order'          => 'ASC',
            'orderby'        => 'date',
        );

        $the_query = new WP_Query($args);
        $count_total = $the_query->found_posts;
        ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1"><h3>📍 Områden</h3></div>
            <div class="column2"></div>
        </div>
        <hr>

        <!-- Posts output -->
        <div class="post-list">
            <?php if ($the_query->have_posts()) : ?>
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                    <?php get_template_part('templates/post-list/area-posts'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

<!--?php include_once LOOPIS_THEME_HQ_DIR . '/templates/front-page/map.php'; ?-->

<!--?php if (!is_user_logged_in()) { 
include LOOPIS_THEME_HQ_DIR . '/templates/forms/interest-form.php'; 
} ?-->

</div><!--page-padding center-->

<?php get_footer(); ?>