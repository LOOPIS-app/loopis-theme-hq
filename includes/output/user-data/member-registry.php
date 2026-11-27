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
    <h5>📋 Medlemsregister</h5>
    <hr>
    <p>🗺 Postområde: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-postarea.php'; ?></b> (<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-zipcode.php'; ?>)</p>
    <p>📍 Huvudområde: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-primary-blog.php'; ?></b></p>
    <p>🚼 Ålder: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-age.php'; ?></b></p>
    <p>⚧ Kön: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-gender.php'; ?></b></p>
    <p>📧 E-post: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-email.php'; ?></b></p>
    <p>📱 Mobilnummer: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-phone.php'; ?></b></p>
    <p>🚦 Medlemskap: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-active.php'; ?></b></p>
</div><!--wrapped-->