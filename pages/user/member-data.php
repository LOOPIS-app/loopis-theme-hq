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
?>

<h1>📋 Medlemsregister</h1>
<hr>
<p class="small">💡 Ange dina aktuella uppgifter.</p>

<?php
// Include member form
include LOOPIS_THEME_HQ_DIR . '/templates/forms/member-form.php'; ?>

<p>Läs hur föreningen LOOPIS hanterar dina uppgifter: <span class="big-link"><a href="<?php echo esc_url(home_url('/privacy/')); ?>">🗄 Integritet</a></span></p>