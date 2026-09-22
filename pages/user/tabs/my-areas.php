<?php
/**
 * Tab showing areas where user has access
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_id = get_current_user_id();
$user = wp_get_current_user();
?>

<h3>📍 Mina områden</h3>
<hr>
<p class="small">💡 Områden du har tillgång till.</p>

<?php
// Use template part for listing user's accessible areas
include LOOPIS_THEME_HQ_DIR . '/templates/post-list/my-area-posts.php';
?>

<p class="info">💡 Vi kommer senare möjliggöra tillgång till fler än ett område.</p>