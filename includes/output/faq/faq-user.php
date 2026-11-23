<?php
/**
 * Output of FAQ posts for members.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<!-- Output -->    
<div class="columns">
    <div class="column1"><h3>💡 Vanliga frågor</h3></div>
    <div class="column2"><a href="<?php echo esc_url(home_url('/faq/'));?>">→ Visa alla</a></div>
</div>
<hr>
<div class="span-list">
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-loopis'));?>">📌 Hur funkar LOOPIS?</a></span>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-skapet'));?>">📌 Hur funkar skåpet?</a></span>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt'));?>">📌 Hur funkar regnbågsmynt?</a></span>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hjalpa-till'));?>">📌 Hur kan jag hjälpa till?</a></span>
</div> 