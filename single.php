<?php
/**
 * Template for single post (area).
 */

get_header();

// Get current user
$user_id = get_current_user_id();
$user_roles = wp_get_current_user()->roles;

// Get post meta (custom fields)
$post_id = get_the_ID();
$thumbnail_id = get_post_thumbnail_id($post_id);
$area_subdirectory = get_post_meta($post_id, 'area_subdirectory', true) ?: '#';
$area_blog_id = get_post_meta($post_id, 'area_blog_id', true) ?: '';
$area_city = get_post_meta($post_id, 'area_city', true) ?: 'Stad saknas';
$area_launch_date = get_post_meta($post_id, 'area_launch_date', true) ?: 'Lanseringsdatum saknas';
$locker_postal_code = get_post_meta($post_id, 'locker_postal_code', true) ?: 'Postnummer saknas';
$locker_address = get_post_meta($post_id, 'locker_address', true) ?: 'Adress saknas';
$locker_google_maps = get_post_meta($post_id, 'locker_google_maps', true) ?: 'URL saknas';
$locker_model = get_post_meta($post_id, 'locker_model', true) ?: 'Modell saknas';
$area_circulated_things_raw = get_post_meta($post_id, 'area_circulated_things', true);
$area_circulated_things = '' === trim((string) $area_circulated_things_raw) ? 'Antal saknas' : $area_circulated_things_raw;
$area_launch_timestamp = strtotime($area_launch_date);
$current_timestamp = current_time('timestamp');

// Format launch date text for display
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

// Count subsite members
include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/visitor-extra/subsite-member-count.php';
$area_members_count = subsite_member_count($area_blog_id);

// Check user access to private areas
$can_access_private_area = current_user_can('manage_options') || current_user_can('loopis_admin');
if (!$can_access_private_area && is_user_logged_in() && $area_blog_id !== '') {
	$can_access_private_area = is_user_member_of_blog(get_current_user_id(), (int) $area_blog_id);
}
?>

<!-- THE POST -->
<div class="page-padding center">
	<p><span class="rounded"><a href="<?php echo esc_url(home_url('/areas')); ?>">📍 Områden</a></span>
	
	<!-- Post categories -->
	<?php
	$categories = array_reverse(get_the_category());
	if (!empty($categories)) :
		foreach ($categories as $category) :
			$category_link = get_category_link($category->term_id);
			?>
			<span class="rounded"><a href="<?php echo esc_url($category_link); ?>"><?php echo esc_html($category->name); ?></a></span>
		<?php endforeach;
	endif; ?>
	</p>

	<!-- Post title-->
	<h1 class="wrap"><?php the_title(); ?></h1>
	<hr>

	<!-- Post meta-->
	<div class="post-meta">
		<p>🗺 <?php echo esc_html($area_city); ?> (<?php echo esc_html($locker_postal_code); ?>)<br>
		🎉 <?php echo esc_html($area_launch_text); ?><br>
		👤 Medlemmar: <?php echo esc_html($area_members_count); ?><br>
		<?php if (false) : ?>
		🎁 Loopade saker: <?php echo esc_html($area_circulated_things); ?><br>
		<?php endif; ?>
	</div><!--post-meta-->

	<!-- Post content-->
	<div class="post-content">
		<?php the_content(); ?>

		<!-- Join -->
		<?php if (!is_user_logged_in()) : ?>
			<?php if (has_category('private') ) { ?>
			<p><b>💡 Admin för LOOPIS på <?php the_title(); ?> ger dig en länk för gratis medlemskap.</b></p>
			<?php } else { ?>
			<p><a class="button" href="<?php echo esc_url(get_signup_url()); ?>">Bli medlem</a></p>
			<?php } ?>
		<?php endif; ?>

		<!-- Go to area -->
		<?php if (!has_category('private') ) { ?>
		<p><span class="mega-link" style="background:#f5f5f5;"><a href="<?php echo esc_url(home_url('/' . $area_subdirectory)); ?>">→ Gå till område</a></span></p>
		<?php } ?>
		
		<!-- Go to private area -->
		<?php if (has_category('private') && $can_access_private_area) { ?>
		<p><span class="mega-link" style="background:#f5f5f5;"><a href="<?php echo esc_url(home_url('/' . $area_subdirectory)); ?>">→ Gå till område</a></span></p>
		<?php } ?>
			
		<!-- Area photo (featured image) -->
		<?php if ($thumbnail_id){ echo wp_get_attachment_image($thumbnail_id, 'large'); } ?>

		<!-- Post options -->
		<a href="#" id="copy_url" class="option">🔗 Kopiera länk</a>

	</div><!--post-content-->				
</div><!--post-padding-->								

<?php get_footer(); ?>