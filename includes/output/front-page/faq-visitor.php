<?php
/**
 * Output of five FAQ posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<!-- Output -->    
<h2>💡 Vanliga frågor</h2>
<div class="columns">
    <div class="column1">↓ 5 exempel</div>
    <div class="column2"><a href="<?php echo esc_url(home_url('/faq/'));?>">→ Visa alla</a></div>
</div>
<hr>
<div class="post-list">
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-loopis'));?>">📌 Hur funkar LOOPIS?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/hur-startar-man-loopis'));?>">📌 Hur startar man LOOPIS?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/tips-till-ny-medlem'));?>">📌 Tips till ny medlem?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/varfor-medlemskap'));?>">📌 Varför medlemskap?</a></span>
<span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php echo esc_url(home_url('/faq/kontakt'));?>">📌 Kontakt med föreningen</a></span>
</div> 