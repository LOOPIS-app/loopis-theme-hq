<?php
/**
 * Block with FAQ info below faq post
 *
 * Included in single-faq.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="wrapped">
<h5>⚠ Fler frågor?</h5>
<hr>
<p>→ Titta på <span class="big-link white"><a href="<?php echo esc_url(home_url('/faq/'));?>">💡 Vanliga frågor</a></span></p>
<?php if ( is_user_logged_in() ) { ?>
<p>→ Gå till ditt område</p>
<?php } else { ?>
<p>→ Skriv till <span class="big-link white"><a rel="noreferrer noopener" href="mailto:info@loopis.app" target="_blank">✉️ info@loopis.app</a></span></p>
<?php } ?>
</div>