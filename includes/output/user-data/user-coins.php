<?php
/**
 * Output summary of user coins
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrapped">
<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="Mynt:" class="symbol"> <?php echo $coins; ?></h1>
<p class="small">Du kan just nu paxa och hämta <?php echo $coins; ?> saker.</p>
<hr>
<p class="small">💚 <?php echo $count_given; ?> saker lämnade</p>
<p class="small">❤ <?php echo $count_booked; ?> saker hämtade (inkl. paxade)</p>
<p class="small">🍀 <?php echo $clovers; ?> fyrklöver</p>
<p class="small">🌟 <?php echo $stars; ?> guldstjärnor</p>
</div><!-- wrapped -->

<!--FAQ-->
<p><span class="link"><a href="<?php echo esc_url(network_home_url('/faq/hur-funkar-regnbagsmynt')); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>