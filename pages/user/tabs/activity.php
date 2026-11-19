<?php
/**
 * Tab showing user activity
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h3>🧮 Min aktivitet</h3>
<hr>
<p class="small">💡 Information om din aktivitet.</p>

<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-activity.php'; ?>