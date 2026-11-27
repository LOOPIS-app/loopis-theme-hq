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
<p><span class="big-link"><a href="<?php echo esc_url(network_home_url( '/faq/hur-funkar-beloningar' )); ?>">📌 Hur funkar belöningar?</a></span></p>

<!--Payments-->	
<h3>📒 Mina kvitton</h3>
<hr>
<p class="small">💡 Dina betalningar till föreningen.</p>
<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-payments.php'; ?>
<p><span class="big-link"><a href="<?php echo esc_url(network_home_url( '/faq/vad-gor-loopis-med-inkomster' )); ?>">📌 Varför betalar jag?</a></span></p>