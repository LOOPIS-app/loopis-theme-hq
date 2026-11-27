<?php
/**
 * Tab showing user settings
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h3>⚙ Mina inställningar</h3>
<hr>
<p class="small">💡 Inställningar för ditt medlemskap.</p>

<div class="span-list">
<span class="mega-link"><a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">👥 Se din profil</a></span><br>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/wp-login.php?action=lostpassword')); ?>">🔑 Byt lösenord</a></span><br>
<span class="mega-link"><a href="<?php echo esc_url(get_loopis_logout_url()); ?>">🚪 Logga ut</a></span><br>
</div>

<!-- Member registry -->
<div class="wrapped">
<h5>📋 Medlemsregister</h5>
<hr>
<div style="font-size: 16px; text-align: left; padding: 5px">
<p>👤 Namn: <b><?php echo esc_html(trim($user->first_name . ' ' . $user->last_name)); ?></b></p>
<p>✉ E-postadress: <b><?php echo antispambot($user->user_email); ?></b></p>
<p>📱 Mobilnummer: <b><?php echo antispambot($user->wpum_phone); ?></b></p>
<p>🗺️ Postnummer: <b><?php echo antispambot($user->wpum_postcode); ?></b></p>
<p>🚦 Medlemskap: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-active.php'; ?></b></p>
</div>
<p><span class="mega-link white"><a href="<?php echo esc_url(home_url('/user/?view=member-data')); ?>">🖊 Ändra uppgifter</a></span></p>
</div>

<div class="clear"></div>

<!-- Area section -->
<?php if (in_array('member', $user_roles, true)) : ?>
<div class="wrapped">
<h5>📍 Område</h5>
<hr>
<?php
    // Member with a subsite as primary blog
    if ( $user_primary_blog > '1' ) { ?>
	<p>✅ Ditt valda LOOPIS-område: 📍 <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-primary-blog.php'; ?></b></p>
    
    <?php 
    // Member with the mainsite as primary blog
    } elseif ( $user_primary_blog == '1' ) { ?>
    <p>⏳ Du väntar på ett nytt LOOPIS-område...</p>
    <p><span class="mega-link white"><a href="<?php echo esc_url(home_url('/user/?view=member-area')); ?>">📍 Välj område</a></span></p>

    <?php
     // Member with no primary blog (should not happen)
    } elseif ( empty($user_primary_blog) ) { ?>
    <p>⏳ Du har inte valt något LOOPIS-område...</p>
	<p><span class="mega-link white"><a href="<?php echo esc_url(home_url('/user/?view=member-area')); ?>">📍 Välj område</a></span></p>
    <?php } ?>
	
</div>
<?php if ( $user_primary_blog > '1' ) { ?>
<p class="info">🚛 För att byta område, kontakta <span class="link"><a href="mailto:admin@loopis.app">✉️ admin@loopis.app</a></span></p>
<?php } ?>

<?php endif; ?>