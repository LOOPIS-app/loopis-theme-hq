<?php
/**
 * Form for collecting interest in new areas.
 *
 * Might be used on front-page.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<h2>Vill du ha LOOPIS i ditt område?</h2>
<hr>
<p>Vi behöver 100 personer med ditt postnummer för att börja planera.</p>

<div class="loopis-form">
    <form method="post" action="" novalidate>
        <?php wp_nonce_field( 'loopis_interest_form', 'loopis_interest_nonce' ); ?>

        <div>
            <label for="interest-postal">Mitt postnummer</label>
            <input type="text" id="interest-postal" name="loopis_postal" placeholder="12345" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" required>
        </div>

        <div>
            <label for="interest-email">Min e-postadress</label>
            <input type="email" id="interest-email" name="loopis_email" placeholder="jag@internet.se" required>
        </div>

        <button type="submit">Anmäl intresse</button>
    </form>
</div><!-- loopis-form -->