<?php
/**
 * Tab showing user coins
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h3>👛 Mina mynt</h3>
<hr>
<p class="small">💡 Information om dina mynt.</p>

<div class="wrapped">
<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="Mynt:" class="symbol"> <?php echo $coins; ?></h1>
<p class="small">Du kan just nu paxa och hämta <?php echo $coins; ?> saker.</p>
<hr>
<p class="small">💚 <?php echo $count_given; ?> saker lämnade</p>
<p class="small">❤ <?php echo ($count_booked-$count_cancelled); ?> saker hämtade (inkl. paxade)</p>
<p class="small">🍀 <?php echo $clovers; ?> fyrklöver</p>
<p class="small">🌟 <?php echo $stars; ?> guldstjärnor</p>
</div><!-- wrapped -->

<!--Info-->
<p class="small">💡 Detaljerad lista över din aktivitet finns på nästa flik</p>


<!--Buy coins-->
<p><button type="button" class="green" onclick="window.location.href='<?php echo esc_url(add_query_arg('option', 'coins-stripe', network_home_url('/shop/'))); ?>'">Köp mynt</button></p>

<!--FAQ-->
<p><span class="big-link"><a href="<?php echo esc_url(network_home_url('/faq/hur-funkar-regnbagsmynt')); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>