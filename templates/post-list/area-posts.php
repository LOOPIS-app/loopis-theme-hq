<?php
/**
 * Template part for displaying big posts, with all post meta, in list view.
 * Copy from "LOOPIS Theme" and modified for "LOOPIS Theme HQ".
 */

if (!defined('ABSPATH')) {
    exit;
}

if (in_category('private') && !current_user_can('manage_options') && !current_user_can('loopis_admin')) {
    return;
}

$post_opacity_style = in_category('private') ? ' style="opacity: 0.6; filter: grayscale(100%);"' : '';

if (!isset($count_total)) {
    $count_total = 0;
}
$count_total++;

// Get variables
$area_city = get_post_meta(get_the_ID(), 'area_city', true) ?: 'Stad saknas';
$area_launch_date = get_post_meta(get_the_ID(), 'area_launch_date', true) ?: 'Lanseringsdatum saknas';
$area_members_raw = get_post_meta(get_the_ID(), 'area_members', true);
$area_members = '' === trim((string) $area_members_raw) ? 'Antal saknas' : $area_members_raw;
$locker_postal_code = get_post_meta(get_the_ID(), 'locker_postal_code', true) ?: 'Postnummer saknas';
$locker_address = get_post_meta(get_the_ID(), 'locker_address', true) ?: 'Adress saknas';
$locker_link = get_post_meta(get_the_ID(), 'locker_link', true) ?: '#';
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
        <p>👥 <?php echo esc_html($area_members); ?> medlemmar</p>
     </div>
</div>
