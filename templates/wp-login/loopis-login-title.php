<?php
/**
 * Login page heading block.
 *
 * Loaded from wp-login-filters.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<header id="header">
    <div class="group">
        <div class="header-back" onclick="history.back()"><i class="fas fa-chevron-left" aria-hidden="true"></i></div>
        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo LOOPIS_THEME_HQ_URI; ?>/assets/img/LOOPIS_logo.png" alt="LOOPIS-logo" id="header-img"></a>
        <div class="header-faq" onclick="location.href='<?php echo esc_url(home_url('/faq/')); ?>'">💡</div>
    </div>
</header>

<div class="page-padding center" style="padding-top: 80px;"> <!-- Padding for fixed header -->
<h1>👤 Logga in</h1>
<hr>
<p class="small">💡 För medlemmar.</p>
