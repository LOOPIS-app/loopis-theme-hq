<?php
/**
 * Custom content for default /wp-activate.php
 *
 * Loaded from wp-activate-filters.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$login_url = isset($login_url) ? (string) $login_url : wp_login_url(home_url('/shop/?option=membership-stripe'));
?>
<p style="opacity: 0.5;">✅ Skapa konto</p>
<p style="opacity: 0.5;">✅ Bekräfta e-post</p>
<h1>3⃣ Logga in</h1>
<hr>
<p>Ditt konto är aktiverat! 🥳<br>
Logga in för att fortsätta.</p>
<?php include LOOPIS_THEME_HQ_DIR . '/templates/links/log-in-button.php'; ?>

<p style="opacity: 0.5;">4⃣ Bli medlem</p>