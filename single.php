<?php
/**
 * Template for single post (area).
 */

get_header(); ?>

<!-- Get variables -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$current = get_current_user_id();
$author = get_the_author_meta('ID');
$post_id = get_the_ID();

// Get post meta
$thumbnail_id = get_post_thumbnail_id($post_id);
$area_city = get_post_meta($post_id, 'area_city', true) ?: 'Stad saknas';
$area_launch_date = get_post_meta($post_id, 'area_launch_date', true) ?: 'Lanseringsdatum saknas';
$active_members_raw = get_post_meta($post_id, 'active_members', true);
$active_members = '' === trim((string) $active_members_raw) ? 'Antal saknas' : $active_members_raw;
$circulated_things_raw = get_post_meta($post_id, 'circulated_things', true);
$circulated_things = '' === trim((string) $circulated_things_raw) ? 'Antal saknas' : $circulated_things_raw;
$locker_postal_code = get_post_meta($post_id, 'locker_postal_code', true) ?: 'Postnummer saknas';
$locker_address = get_post_meta($post_id, 'locker_address', true) ?: 'Adress saknas';
$locker_link = get_post_meta($post_id, 'locker_link', true) ?: '#';

$area_launch_timestamp = strtotime($area_launch_date);
$current_timestamp = current_time('timestamp');

// Format launch date text
if (!$area_launch_timestamp) {
	$area_launch_text = 'Lanseringsdatum saknas';
} elseif ($area_launch_timestamp > $current_timestamp) {
	$area_launch_text = sprintf(
		'Öppnar %s (om %s)',
		wp_date('Y-m-d', $area_launch_timestamp),
		human_time_diff($current_timestamp, $area_launch_timestamp)
	);
} else {
	$area_launch_text = sprintf(
		'Öppnade %s (för %s sen)',
		wp_date('Y-m-d', $area_launch_timestamp),
		human_time_diff($area_launch_timestamp, $current_timestamp)
	);
}
?>	

<!-- POST CONTENT -->
    <div class="post-wrapper">
        <div class="page-padding center">
			<p><span class="rounded"><a href="<?php echo get_post_type_archive_link('post'); ?>">📍 Områden</a></span> <span class="rounded"><?php the_category(' ');?></span></p>
            <h1 class="wrap"><?php the_title(); ?></h1>
			<hr>
			<div class="post-meta">
				<p>🗺 <?php echo esc_html($area_city); ?> (<?php echo esc_html($locker_postal_code); ?>)<br>
				🎉 <?php echo esc_html($area_launch_text); ?></p>
			</div><!--post-meta-->

			<?php if (has_term('active', 'category')) { ?>
			<h5><a href="<?php echo esc_url(home_url('/' . $locker_postal_code)); ?>">→ Gå till område</a></h5>
			<?php } ?>

				<div class="post-content">
				<div class="wrapped">
					<p>👤 Antal aktiva medlemmar: <?php echo esc_html($active_members); ?></p>
					<p>🎁 Antal cirkulerade saker: <?php echo esc_html($circulated_things); ?></p>
				</div>
			
				<?php the_content(); ?>
				<?php if ($thumbnail_id){ echo wp_get_attachment_image($thumbnail_id, 'large'); } ?>

<!-- POST OPTIONS -->
<a href="#" id="copy_url" class="option">🔗 Kopiera länk</a>

			</div><!--post-content-->				
		</div><!--post-padding-->				
	</div><!--post-wrapper-->							

<?php get_footer(); ?>