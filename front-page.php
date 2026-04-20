<?php
/**
 * Front page template
 * Displays sweden map + form for sign-up.
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">
<p>Vill du ge &amp; få saker på ett enkelt &amp; roligt sätt? LOOPIS fixar det.</p>
<hr>

<?php include_once LOOPIS_HQ_THEME_DIR . '/templates/access/message.php'; ?>


<div class="frontpage-map">
            <div class="frontpage-map__legend">
                <p>❤ Här finns LOOPIS</p>
                <p>🧡 Här öppnar snart LOOPIS</p>
                <p>💚 Här finns intresse för LOOPIS</p>
            </div>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sweden.svg" alt="Sverige" class="sweden-map">
        </div><!-- frontpage-map -->

        <div class="frontpage-interest-form">
            <h2>Vill du ha LOOPIS i ditt område?</h2>
            <hr>
            <p>Fyll i formuläret! När 100 personer med ditt postnummer har anmält intresse kan vi börja planera för att öppna LOOPIS och meddelar dig.</p>
            <form class="interest-form" method="post" action="" novalidate>
                <?php wp_nonce_field( 'loopis_interest_form', 'loopis_interest_nonce' ); ?>
                <div class="interest-form__field">
                    <label for="interest-postal" class="interest-form__label">Mitt postnummer</label>
                    <input type="text" id="interest-postal" name="loopis_postal" class="interest-form__input" placeholder="12345" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" required>
                </div>
                <div class="interest-form__field">
                    <label for="interest-email" class="interest-form__label">Min e-postadress</label>
                    <input type="email" id="interest-email" name="loopis_email" class="interest-form__input" placeholder="namn@epost.se" required>
                </div>
                <button type="submit">Anmäl intresse</button>
            </form>
        </div><!-- frontpage-interest-form -->

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>