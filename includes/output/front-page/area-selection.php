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
?>
<!-- Output -->    
<div class="columns">
    <div class="column1"><h3>💚 Välkommen!</h3></div>
    <div class="column2"></div>
</div>
<hr>
<p>Nu är du medlem i föreningen LOOPIS! 🥳</p>
<p>Om du bor i Bagarmossen eller Skarpnäck:</p>
<p><span class="mega-link"><a href="<?php echo esc_url(home_url('/user/?view=member-area')); ?>">📍 Välj område</a></span></p> 
<p>Om du bor någon annanstans:</p>
    <span class="mega-link"><a href="<?php echo esc_url(home_url('/postareas')); ?>">⚡️ Stötta område</a></span>
</p>
