<?php
/**
 * Output of five FAQ posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<!-- Output -->    
<h3 style="text-align: left;">💡 Vanliga frågor</h3>
<div class="columns">
    <div class="column1">↓ 4 exempel</div>
    <div class="column2"><a href="<?php echo esc_url(home_url('/faq/'));?>">→ Visa alla</a></div>
</div>
<hr>
<div class="post-list" style="line-height: 2.2;">
<span class="big-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-loopis'));?>">📌 Hur funkar LOOPIS?</a></span>
<span class="big-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-skapet'));?>">📌 Hur funkar skåpet?</a></span>
<span class="big-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt'));?>">📌 Hur funkar regnbågsmynt?</a></span>
<span class="big-link"><a href="<?php echo esc_url(home_url('/faq/hjalpa-till'));?>">📌 Hur kan jag hjälpa till?</a></span>
</div> 