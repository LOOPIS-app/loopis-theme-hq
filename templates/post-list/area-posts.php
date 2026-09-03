<?php
/**
 * Template part for displaying big area posts in list view.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Skip private posts for users without private area access
$area_blog_id = get_post_meta(get_the_ID(), 'area_blog_id', true) ?: 0;
$can_access_private_area = current_user_can('manage_options') || current_user_can('loopis_admin');
if (!$can_access_private_area && is_user_logged_in() && (int) $area_blog_id > 0) {
    $can_access_private_area = is_user_member_of_blog(get_current_user_id(), (int) $area_blog_id);
}

if (in_category('private') && !$can_access_private_area) {
    return;
}
// Set post opacity style for private posts
$post_opacity_style = in_category('private') ? ' style="opacity: 0.6; filter: grayscale(100%);"' : '';

// Count total posts in the list
if (!isset($count_total)) {
    $count_total = 0;
}
$count_total++;

// Get variables
$area_city = get_post_meta(get_the_ID(), 'area_city', true) ?: 'Stad saknas';
$area_launch_date = get_post_meta(get_the_ID(), 'area_launch_date', true) ?: 'Lanseringsdatum saknas';
$locker_postal_code = get_post_meta(get_the_ID(), 'locker_postal_code', true) ?: 'Postnummer saknas';
$locker_address = get_post_meta(get_the_ID(), 'locker_address', true) ?: 'Adress saknas';
$locker_link = get_post_meta(get_the_ID(), 'locker_link', true) ?: '#';

// Count subsite members
include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/visitor-extra/subsite-member-count.php';
$area_members_count = subsite_member_count($area_blog_id);
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
