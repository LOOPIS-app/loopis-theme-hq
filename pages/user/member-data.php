<?php
/**
 * User page member form.
 * 
 * Dynamic content of page-user.php
 * Reached on /user/?option=member-form
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include member form
include LOOPIS_THEME_HQ_DIR . '/templates/forms/member-form.php'; ?>

<p>Läs hur föreningen hanterar dina uppgifter: <span class="big-link"><a href="<?php echo esc_url(home_url('/privacy/')); ?>">🗄 Integritet</a></span></p>