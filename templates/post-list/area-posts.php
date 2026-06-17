<?php
/**
 * Template part for displaying big posts, with all post meta, in list view.
 * Copy from "LOOPIS Theme" and modified for "LOOPIS Theme HQ".
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get variables
$area_city = get_post_meta(get_the_ID(), 'area_city', true) ?: 'Stad saknas';
$area_launch_date = get_post_meta(get_the_ID(), 'area_launch_date', true) ?: 'Lanseringsdatum saknas';
$active_members_raw = get_post_meta(get_the_ID(), 'active_members', true);
$active_members = '' === trim((string) $active_members_raw) ? 'Antal saknas' : $active_members_raw;
$locker_postal_code = get_post_meta(get_the_ID(), 'locker_postal_code', true) ?: 'Postnummer saknas';
$locker_address = get_post_meta(get_the_ID(), 'locker_address', true) ?: 'Adress saknas';
$locker_link = get_post_meta(get_the_ID(), 'locker_link', true) ?: '#';
?>

<div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
    <div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
    <div class="post-list-post-title-big"><?php the_title(); ?></div>
    <div class="post-list-post-meta">
        <p>🗺 <?php echo esc_html($area_city); ?></p>
        <p><?php the_category(' '); ?></p>
        <p>👥 <?php echo esc_html($active_members); ?> medlemmar</p>
     </div>
</div>
