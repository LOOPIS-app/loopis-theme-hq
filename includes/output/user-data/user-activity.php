<?php
/**
 * Output summary of user activity
 * 
 * Variables are passed from context!
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- COINS -->
<div class="economy wrapped">
<p><span class="left text-left">Regnbågsmynt</span> <span class="right"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;" /></span></p>
<hr>
<p class="group"><span class="left"><b><?php echo $payments_membership; ?></b> köp av medlemskap</span> <span class="plus right">+<?php echo $membership_coins; ?></span></p>
<p class="group"><span class="left"><b><?php echo $payments_coins; ?></b> köp av mynt</span> <span class="plus right">+<?php echo $bought_coins; ?></span></p>
<p class="group"><span class="left"><b><?php echo $count_given; ?></b> saker lämnade</span> <span class="plus right">+<?php echo $count_given; ?></span></p>
<p class="group"><span class="left"><b><?php echo $count_booked; ?></b> saker hämtade/paxade</span>&nbsp; &nbsp;<span class="minus right">–<?php echo $count_booked; ?></span></p>
<p class="group"><span class="left"><b><?php echo $clovers; ?></b> fyrklöver</span> <span class="plus right">+<?php echo $clover_coins; ?></span></p>
<p class="group"><span class="left"><b><?php echo $stars; ?></b> guldstjärnor</span> <span class="plus right">+<?php echo $star_coins; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $coins; ?></b></span></p>
</div>

<!-- CLOVERS -->
<div class="economy wrapped">
<p><span class="left">Fyrklöver</span> <span class="right">🍀</span></p>
<hr>
<p><span class="left"><b><?php echo $count_submitted; ?></b> annonser skapade</span>&nbsp; &nbsp;<span class="plus right">+<?php echo $count_submitted; ?></span></p>
<p><span class="left"><b><?php echo $count_booked; ?></b> saker hämtade</span> <span class="plus right">+<?php echo $count_booked; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $clovers; ?></b></span></p>

<p class="small">
<?php if ($clover_coins > 0) { ?>
→ <b><?php echo $clover_coins; ?> mynt</b> i belöning! 🎉
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

<!-- STARS -->
<div class="economy wrapped">
<p><span class="left">Guldstjärnor</span><span class="right">🌟</span></p>
<hr>
<?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-rewards.php'; ?>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $stars; ?></b></span></p>

<p class="small">
<?php if ($star_coins > 0) { ?>
→ <b><?php echo $star_coins; ?> mynt</b> i belöning! 🎉 
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

<!--Info-->
<p class="small">
<?php if ($clovers >= 10) {  $remainder = $clovers % 10; $remaining = 10 - $remainder; ?>
💡 Samla <?php echo $remaining; ?> fyrklöver för att få nästa mynt.<br>
<?php } ?>
<?php if ($clovers < 10) { $remaining = 10 - $clovers; ?>
💡 Samla <?php echo $remaining; ?> fyrklöver så får du ett mynt!<br>
<?php } ?>
</p>

<!--FAQ-->	
<p><span class="link"><a href="<?php echo esc_url(network_home_url( '/faq/hur-funkar-beloningar' )); ?>">📌 Hur funkar belöningar?</a></span></p>

<!--Payments-->	
<h3>📒 Mina kvitton</h3>
<hr>
<p>Dina betalningar till föreningen.</p>
<?php include_once LOOPIS_THEME_DIR . '/includes/output/user-data/user-payments.php'; ?>

<!--FAQ-->	
<p><span class="link"><a href="<?php echo esc_url(network_home_url( '/faq/varför-medlemskap' )); ?>">📌 Vad gör LOOPIS med inkomsterna?</a></span></p>