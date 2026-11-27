<?php
/**
 * Output user account data
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="wrapped">
    <h5>⚙️ Användarkonto</h5>
    <hr>
    <p>👤 Användarnamn: <b><?php echo $user->user_login ?></b></p>
    <p>🔧 User-ID: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-id.php'; ?></b></p>
    <p>🧩 Roll: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-roles.php'; ?></b></p>
</div><!--wrapped-->