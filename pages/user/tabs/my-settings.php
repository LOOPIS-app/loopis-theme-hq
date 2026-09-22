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

<div class="wrapped">
<p>👤 Användarnamn: <b><?php echo $user->user_login ?></b></p>
<p>✉ E-post: <b><?php echo antispambot($user->user_email); ?></b></p>
<p>📱 Mobilnummer: <b><?php echo antispambot($user->wpum_phone); ?></b></p>
</div>

<p><span class="big-link"><a href="<?php echo esc_url(home_url('/user/?view=member-data')); ?>">🖊 Medlemsregister</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">👥 Se din profil</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url(home_url('/wp-login.php?action=lostpassword')); ?>">🔑 Byt lösenord</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">🚪 Logga ut</a></span></p>