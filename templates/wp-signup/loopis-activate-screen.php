<?php
/**
 * Custom content for default /wp-activate.php
 *
 * Loaded from wp-activate-filters.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check cookie for special invite code
if (empty($_COOKIE['special_invite_payload'])) {
$login_url = isset($login_url) ? (string) $login_url : wp_login_url(home_url('/shop/?option=membership-stripe'));
} else {
    $login_url = isset($login_url) ? (string) $login_url : wp_login_url(home_url('/special-signup/'));
}

$user_login = isset($user_login) ? (string) $user_login : '';
$user_email = isset($user_email) ? (string) $user_email : '';
$password = isset($password) ? (string) $password : '';
?>

<p style="opacity: 0.5;">✅ Skapa konto<br>
✅ Bekräfta e-post</p>
<h1>3⃣ Logga in</h1>
<hr>
<p style="opacity: 0.5;">4⃣ Bli medlem</p>
<div class="wp-activate-message">
<p>Ditt konto är aktiverat! 🥳<br>
Logga in för att avsluta din registrering.</p>
<p>E-post: <b><?php echo esc_html($user_email); ?></b></p>
<p>Användarnamn: <b><?php echo esc_html($user_login); ?></b></p>
<p>Lösenord: <b><?php echo esc_html($password); ?></b></p>
<?php include LOOPIS_THEME_HQ_DIR . '/templates/links/log-in-button.php'; ?>
</div>