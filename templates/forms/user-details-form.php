<?php
/**
 * Form for providing details and becoming an active member in a locker area.
 *
 * Work in progress... Add:
 * wpum_postcode (number 5 digits)
 * wpum_phone (10 digits + optional -)
 * wpum_birthyear (number 4 digits)
 * wpum_gender (dropdown options: female, male, nonbinary, other, secret)
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
        <div class="loopis-form">
            <h2>Välj ditt område för LOOPIS</h2>
            <hr>
            <p>Vi behöver 100 personer med ditt postnummer för att börja planera.</p>
            <form class="area-form" method="post" action="" novalidate>
                <?php wp_nonce_field( 'loopis_area_form', 'loopis_area_nonce' ); ?>
                <div class="area-form__field">
                    <label for="area-postal" class="area-form__label">Mitt postnummer</label>
                    <input type="text" id="area-postal" name="loopis_postal" class="area-form__input" placeholder="12345" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" required>
                </div>
                <button type="submit">Registrera</button>
            </form>
        </div><!-- loopis-form -->