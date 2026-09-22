<?php
/**
 * Template for displaying area blog posts, linking to the blog post.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get variables
$area_blog_id = get_post_meta(get_the_ID(), 'area_blog_id', true) ?: 0;
$area_city = get_post_meta(get_the_ID(), 'area_city', true) ?: 'Stad saknas';

// Count subsite members
include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/visitor-extra/subsite-member-count.php';
$area_members_count = subsite_member_count($area_blog_id);

// Set post opacity style for private posts
$post_opacity_style = in_category('private') ? ' style="opacity: 0.6; filter: grayscale(100%);"' : '';
?>

<div class="post-list-post-big"<?php echo $post_opacity_style; ?> onclick="location.href='<?php the_permalink(); ?>';">
    <div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
    <div class="post-list-post-title-big"><?php the_title(); ?></div>
    <div class="post-list-post-meta">
        <p>🗺 <?php echo esc_html($area_city); ?></p>
        <p><?php
            $post_categories = array_reverse(get_the_category());
            if (!empty($post_categories)) {
                foreach ($post_categories as $category) {
                    echo '<span>' . esc_html($category->name) . '</span>&nbsp;';
                }
            }
        ?></p>
        <p>👥 <?php echo esc_html($area_members_count); ?> medlemmar</p>
     </div>
</div>
