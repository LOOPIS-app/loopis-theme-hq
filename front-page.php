<?php
/**
 * LOOPIS main site front page
 * 
 * Displays user options + list available areas.
 */

get_header(); ?>

<div class="page-padding center">

    <?php 
    // Check member data and payment for member_pending
    if (current_user_can('member_pending'))  {
        include LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php'; 
        $user_id = get_current_user_id();
        $member_status = member_pending_check($user_id);
        }
    
    // Greeting and options for users and visitors
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/role-greeting-main.php';
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/role-options-main.php';

    // Show list of areas
        wp_reset_postdata();        
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

        <?php wp_reset_postdata();

?>

</div><!--page-padding center-->

<?php get_footer(); ?>