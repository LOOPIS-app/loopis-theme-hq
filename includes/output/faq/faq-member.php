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
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/var-finns-loopis'));?>">📌 Var finns LOOPIS?</a></span>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hur-startar-man-loopis'));?>">📌 Hur startar man LOOPIS?</a></span>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-loopis'));?>">📌 Hur funkar LOOPIS?</a></span>
</div> 