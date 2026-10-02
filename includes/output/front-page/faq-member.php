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
    <div class="column1">↓ 5 exempel</div>
    <div class="column2"><a href="<?php echo esc_url(home_url('/faq/'));?>">→ Visa alla</a></div>
</div>
<hr>
<div class="post-list">
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-loopis'));?>">📌 Hur funkar LOOPIS?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-loopis-skap'));?>">📌 Hur funkar LOOPIS skåp?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt'));?>">📌 Hur funkar regnbågsmynt?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/hur-angrar-jag-en-paxning'));?>">📌 Hur ångrar jag en paxning?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/hjalpa-till'));?>">📌 Hur kan jag hjälpa till?</a></span>
</div> 