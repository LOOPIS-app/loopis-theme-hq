<?php
/**
 * Output of FAQ posts for visitors.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<!-- Output -->    
<h2>💡 Vanliga frågor</h2>
<hr>
<div class="span-list">
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-loopis'));?>">📌 Hur funkar LOOPIS?</a></span>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/tips-till-ny-medlem'));?>">📌 Tips till ny medlem?</a></span>
<span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/varfor-medlemskap'));?>">📌 Varför medlemskap?</a></span>
<span class="mega-link red_light"><a href="<?php echo esc_url(home_url('/faq/'));?>">→ Visa alla...</a></span>
</div> 