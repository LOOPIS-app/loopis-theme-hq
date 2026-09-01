<?php
/**
 * Custom content for default /wp-signup.php
 *
 * Loaded from wp-signup-filters.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<h1>1⃣ Skapa konto</h1>
<hr>
<p style="opacity: 0.5;">
2⃣ Bekräfta e-post<br>
3⃣ Logga in<br>
4⃣ Bli medlem<br>

<?php
// Check cookie for special invite code
if (empty($_COOKIE['special_invite_payload'])) { ?> 
<p><b>Bli medlem i LOOPIS genom att skapa ett konto och betala 50 kronor.</b></p>
<div class="loopis-message warning">
<p>⚠ Du kan bara använda LOOPIS om du bor nära våra skåp.</p>
<p><span class="big-link"><a href="<?php echo esc_url(home_url('/faq/var-finns-loopis/')); ?>">📌 Var finns LOOPIS?</a></span></p>
</div>
<?php } else { ?>
<p><b>Skapa konto för att få ett gratis medlemskap.</b></p>
<p class="info">Du har fått en specialinbjudan! ✨</p>
<?php } ?>

<!-- FAQ -->
<p><span class="big-link"><a href="<?php echo esc_url(home_url('/faq/varfor-medlemskap/')); ?>">📌 Varför måste jag vara medlem?</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url(home_url('/faq/loopis-stadgar/')); ?>">📜 Stadgar</a></span>&nbsp; <span class="big-link"><a href="<?php echo esc_url(home_url('/privacy/')); ?>">🗄 Integritet</a></span></p>