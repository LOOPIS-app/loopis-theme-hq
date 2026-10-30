<?php
/**
 * Tab showing user settings
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_id = get_current_user_id();
$user = wp_get_current_user();
?>

<h3>⚙ Mina inställningar</h3>
<hr>
<p class="small">💡 Inställningar för ditt medlemskap.</p>

<div class="wrapped link" style="text-align: left; min-width: 300px;" onclick="location.href='<?php echo esc_url(home_url('/user/?view=member-data')); ?>'">
<h5>📋 Medlemsregister</h5>
<p class="small">↓ Dina uppgifter<span class="right blue">Redigera →</span></p>
<hr>
<p>👤 Namn: <b><?php echo esc_html(trim($user->first_name . ' ' . $user->last_name)); ?></b></p>
<p>✉ E-postadress: <b><?php echo antispambot($user->user_email); ?></b></p>
<p>📱 Mobilnummer: <b><?php echo antispambot($user->wpum_phone); ?></b></p>
<p>🗺️ Postnummer: <b><?php echo antispambot($user->wpum_postcode); ?></b></p>
<p>🚦 Medlemskap: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-active.php'; ?></b></p>
</div>

<div class="span-list">
<span class="mega-link"><a href="<?php echo esc_url(home_url('/user/?view=member-data')); ?>">📋 Medlemsregister</a></span><br>
<span class="mega-link"><a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">👥 Se din profil</a></span><br>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/wp-login.php?action=lostpassword')); ?>">🔑 Byt lösenord</a></span><br>
<span class="mega-link"><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">🚪 Logga ut</a></span><br>