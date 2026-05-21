<?php
/**
 * Button for adding coins, for developers.
 *
 * Included in coins-stripe.php when WP_TEST is true
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

    <?php if(isset($_POST['activate'])) { add_coins(); refresh_page(); } ?>
		<form method="post" class="arb" action=""><button name="activate" type="submit" class="purple small">Ge mig 5 mynt</button></form>
		<p class="info">Registera mynt för test.</p>