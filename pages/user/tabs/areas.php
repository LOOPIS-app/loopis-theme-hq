<?php
/**
 * Tab showing user areas (not yet used)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h3>📍 Områden</h3>
<hr>
<p class="small">💡 Områden du har tillgång till.</p>

<?php
// Use template part for listing user's accessible areas
include LOOPIS_THEME_HQ_DIR . '/includes/output/front-page/areas-user.php';
?>
<p class="info">🚛 För att byta område, skicka ett mail till <span class="link"><a href="mailto:admin@loopis.app">✉️ admin@loopis.app</a></span></p>
<p class="info">💡 Vi kommer senare möjliggöra tillgång till fler än ett område.</p>