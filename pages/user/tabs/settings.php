<?php
/**
 * Template for displaying LOOPIS user tab content.
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
<p class="small">💡 Inställningar för ditt konto.</p>

<div class="wrapped">
<p>👤 Användarnamn: <b><?php echo $user->user_login ?></b></p>
<p>✉ E-post: <b><?php echo antispambot($user->user_email); ?></b></p>
<p>📱 Mobilnummer: <b><span class="unclickable"><?php echo antispambot($user->wpum_phone); ?></span></b></p>
<p>📍 Område: <b><?php include_once LOOPIS_THEME_HQ_DIR . '/templates/user/profile/user-area.php'; ?></b></p>
</div>

<p><span class="big-link"><a href="/user/?option=member-form">📋 Medlemsregister</a></span> för dina aktuella uppgifter</p>
<p><span class="big-link"><a href="<?php echo get_author_posts_url($user_id) ?>">👥 Din profil</a></span> som den visas för andra</p>
<p><span class="big-link"><a href="<?php echo wp_logout_url(home_url()) ?>">🚪 Logga ut</a></span> från LOOPIS.app</p>

<!--h3>Integritet</h3>
<hr>
<p class="info"><span>👤<?php echo $user->display_name ?></span>visas för alla när du 1⃣ lägger upp en annons och 2⃣ visar intresse för en annons.</p>
<p class="info"><span>📱<?php echo $user->wpum_phone ?></span> visas endast för 1⃣ givare/mottagare vid hämtning på annan plats än skåpet och 2⃣ utlånaren när du vill låna något.</p>
<p class="info"><span>✉<?php echo antispambot($user->user_email); ?></span> visas inte för någon.</p>
<p>Gå till <a href="../../integritetspolicy"><span class="big-link">🗄 Integritetspolicy</span></a></p-->