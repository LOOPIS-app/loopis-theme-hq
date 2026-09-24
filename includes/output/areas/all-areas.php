<?php
/**
 * List of area blog posts corresponding to subsites available to the current user.
 * 
 * The posts link to the blog post.
 * 
 * Posts in category 'private' only appear to admin and users with access to the respective subsite.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Build the list of subsites the current user can access.
$user_id = get_current_user_id();
$user_access_blog_ids = array();

if ($user_id > 0) {
    $user_blogs = get_blogs_of_user($user_id, true);
    foreach ($user_blogs as $user_blog) {
        $site_blog_id = isset($user_blog->userblog_id) ? (int) $user_blog->userblog_id : 0;
        if ($site_blog_id > 0 && !is_main_site($site_blog_id)) {
            $user_access_blog_ids[] = $site_blog_id;
        }
    }
}

$user_access_blog_ids = array_values(array_unique(array_map('intval', $user_access_blog_ids)));
$can_access_all_private_areas = current_user_can('manage_options') || current_user_can('loopis_admin');

// Output all available areas, hiding private areas without user access.
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'orderby'        => 'date',
    );

    $the_query = new WP_Query($args);

    $visible_posts = array();
    foreach ($the_query->posts as $area_post) {
        if (
            has_category('private', $area_post)
            && !$can_access_all_private_areas
            && !in_array(
                (int) get_post_meta($area_post->ID, 'area_blog_id', true),
                $user_access_blog_ids,
                true
            )
        ) {
            continue;
        }

        $visible_posts[] = $area_post;
    }

    $the_query->posts = $visible_posts;
    $the_query->post_count = count($visible_posts);
    $count_total = $the_query->post_count;
    ?>

<!-- Output -->    
<div class="columns">
    <div class="column1">↓ <?php echo $count_total; ?> <?php echo $count_total === 1 ? 'område' : 'områden'; ?></div>
    <div class="column2"></div>
</div>
<hr>

<!-- Area posts -->
<div class="post-list">
    <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <?php get_template_part('templates/post-list/area-post'); ?>
        <?php endwhile; ?>
</div><!--post-list-->
<?php endif; ?>

<?php wp_reset_postdata(); ?>