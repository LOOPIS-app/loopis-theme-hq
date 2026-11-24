<?php
/**
 * Output the current user's post area and member count.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current user's post area
$user_postarea = get_user_meta($user_id, 'wpum_postarea', true);

// Check if a post exists with the exact name of the user's post area
$blog_posts = get_posts(array(
    'title'     => $user_postarea,
    'post_type' => 'post',
    'number'    => 1,
));
$blog_post = !empty($blog_posts) ? $blog_posts[0] : null;
    
if ($blog_post) {
// Setup post data context for the functions to work
setup_postdata($blog_post);

// Get area details from post meta
$area_blog_id = get_post_meta($blog_post->ID, 'area_blog_id', true) ?: 0;
$area_city = get_post_meta($blog_post->ID, 'area_city', true) ?: 'Stad saknas';
$area_subdirectory = get_post_meta($blog_post->ID, 'area_subdirectory', true) ?: '#';
$area_url = home_url('/' . trim($area_subdirectory, '/') . '/');

// Count subsite members
include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/visitor-extra/subsite-member-count.php';
$area_members_count = subsite_member_count($area_blog_id);
?>

<!-- Existing area -->
<div class="columns">
    <div class="column1"><h3>📍 Mitt område</h3></div>
    <div class="column2"><a href="<?php echo esc_url( home_url( '/areas/' ) ); ?>">→ Visa alla</a></div>
</div>
<hr>
<div class="post-list">
    <div class="post-list-post-big" onclick="window.location.href='<?php echo esc_js(esc_url($area_url)); ?>';">
        <div class="post-list-post-thumbnail-big"><?php echo get_the_post_thumbnail($blog_post->ID, 'thumbnail'); ?></div>
        <div class="post-list-post-title-big"><?php echo esc_html($blog_post->post_title); ?></div>
        <div class="post-list-post-meta">
        <p>🗺 <?php echo esc_html($area_city); ?></p>
        <p><?php
            $post_categories = array_reverse(get_the_category($blog_post->ID));
            if (!empty($post_categories)) {
                foreach ($post_categories as $category) {
                    echo '<span>' . esc_html($category->name) . '</span>&nbsp;';
                }
            }
        ?></p>
        <p>👥 <?php echo esc_html($area_members_count); ?> medlemmar</p>
        </div>
    </div>
</div><!--post-list-->

<?php wp_reset_postdata(); 

} else {

// Get postarea member count for the current user
include LOOPIS_THEME_HQ_DIR . '/includes/functions/stats/postarea-member-count.php';
$postarea_members_count = get_postarea_member_count($user_postarea);
?>

<!-- Requested area -->
<div class="columns">
    <div class="column1"><h3>📈 Mitt område...</h3></div>
    <div class="column2"><a href="<?php echo esc_url( home_url( '/areas/' ) ); ?>">→ Visa aktiva</a></div>
</div>
<hr>
<div class="post-list">
    <div class="post-list-post-big">
        <div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
        <div class="post-list-post-title-big"><?php echo esc_html($user_postarea); ?></div>
        <div class="post-list-post-meta">
            <p>🧡 Efterfrågat</p>
            <p>👥 <?php echo esc_html($postarea_members_count); ?> medlemmar</p>
        </div>
    </div>
</div><!--post-list-->
<?php } ?>

<?php
error_log('DEBUG: user_postarea = ' . print_r($user_postarea, true));
error_log('DEBUG: blog_post = ' . print_r($blog_post, true));
?>
