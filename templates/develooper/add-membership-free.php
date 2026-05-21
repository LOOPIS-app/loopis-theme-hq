<?php
/**
 * Button for activating membership, for developers.
 *
 * Included in membership-stripe.php when WP_TEST is true
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

    <?php if(isset($_POST['activate'])) { add_membership(); refresh_page(); } ?>
		<form method="post" class="arb" action=""><button name="activate" type="submit" class="purple small">Ge mig medlemskap</button></form>
		<p class="info">Aktivera medlemskapet för test.</p>