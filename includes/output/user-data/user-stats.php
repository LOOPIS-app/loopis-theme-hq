<?php
/**
 * Output member data
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="wrapped">
    <h5>📊 Statistik</h5>
    <hr>
    <p>♻ <?php echo $given_percentage; ?>% av <?php echo $count_submitted; ?> annonser lämnade</p>
    <p>🛟 <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-posts-support-count.php'; ?> supportfrågor skapade</p>
    <p>⏱ Senaste inloggning: <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-last-login.php'; ?></p>
</div><!--wrapped-->