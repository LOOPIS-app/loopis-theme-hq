<?php
/**
 * Prompt user with no primary blog selected to choose an area.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check invite cookie for private areas first
require_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/area-access-setup-private.php';
if ( loopis_has_special_invite_cookie() ) {
    loopis_process_special_invite_cookie();
} 

// Show welcome message if no primary blog is selected
if ( empty($user_primary_blog) ) { ?>
<h3>💚 Välkommen!</h3>
<hr>
<p>Nu är du medlem i föreningen LOOPIS.<br>
Vi är så glada att du är med! 🥳</p>
<?php } ?>

<?php
// Get current user's postarea
$user_postarea = get_user_meta($user_id, 'wpum_postarea', true);

// Check if a blog post with the exact name of the user's post area exists
$blog_posts = get_posts(array(
    'title'     => $user_postarea,
    'post_type' => 'post',
    'number'    => 1,
));
$blog_post = !empty($blog_posts) ? $blog_posts[0] : null;

// Blog post found?
if ($blog_post) { ?>
    <p>LOOPIS finns i ditt område! För att ansluta dig:<br>
    <span class="mega-link"><a href="<?php echo esc_url(home_url('/user/?view=member-area')); ?>">📍 Välj område</a></span></p> 
<?php } else { ?>
    <p>LOOPIS finns inte i ditt område ännu...<br>
        Hjälp till att bli fler medlemmar! 🥰<br>
    <span class="mega-link"><a href="<?php echo esc_url(home_url('stats/?view=pages/postareas')); ?>">⚡️ Stötta område</a></span></p>
<?php 

// If the user has no primary blog, set the post area to '1'
if ( empty($user_primary_blog) ) { update_user_meta($user_id, 'primary_blog', '1'); }
} ?>

