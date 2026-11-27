<?php
/**
 * User page area selector
 * 
 * Dynamic content of page-user.php
 * Reached on /user/?view=member-area
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle area selector form submission
if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['loopis_location_blog_id'] ) ) {
    if ( !isset($_POST['loopis_area_selector_nonce']) || !wp_verify_nonce( $_POST['loopis_area_selector_nonce'], 'loopis_area_selector_nonce' ) ) {
        wp_die( 'Security check failed.' );
    }

    $user_id = get_current_user_id();
    $blog_id = absint( $_POST['loopis_location_blog_id'] );

    if ( $user_id > 0 && $blog_id > 0 ) {
        require_once LOOPIS_THEME_HQ_DIR . '/includes/forms/area-selector.php';
        
        if ( loopis_is_valid_signup_location( $blog_id ) ) {
            require_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/area-access-setup.php';
            area_access_setup( $user_id, $blog_id );
            
            // Redirect to stay on same page (prevents form resubmission)
            wp_safe_redirect( $_SERVER['REQUEST_URI'] );
            exit;
        }
    }
}
?>

<h3>📍 Välj område</h3>
<hr>
<p class="small">💡 För dig som har ett LOOPIS-område nära.</p>

<?php
// Member output
if (in_array('member', $user_roles, true)) : ?>

    <?php
    // Member with a subsite as primary blog
    if ( $user_primary_blog > '1' ) { ?>
    <div class="loopis-message success">
	<p>✅ Ditt valda LOOPIS-område:</p>
    <p><span class="mega-link">📍<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-primary-blog-link.php'; ?></span></p>
    </div>
    <p class="info">🚛 För att byta område behöver du kontakta <span class="link"><a href="mailto:admin@loopis.app">✉️ admin@loopis.app</a></span></p>

    <?php 
    // Member with the mainsite as primary blog
    } elseif ( $user_primary_blog == '1' ) { ?>
    <div class="loopis-message success">
    <p><b>⏳ Du väntar på ett nytt LOOPIS-område?</b></p>
    </div>
        <?php
        include_once LOOPIS_THEME_HQ_DIR . '/includes/forms/area-selector.php';
        loopis_render_area_selector_form(); ?>

    <?php
     // Member with no primary blog
     // Should not happen because it is set to 1 on start page if the users postarea doesn't match any existing blog
    } elseif ( empty($user_primary_blog) ) { ?>
    <div class="loopis-message success">
    <p>⏳ Du har inte valt något LOOPIS-område...</p>
    </div>
            <?php
        include_once LOOPIS_THEME_HQ_DIR . '/includes/forms/area-selector.php';
        loopis_render_area_selector_form(); ?>

    <?php } ?>

    <?php
    // Info about choosing 
    if ( $user_primary_blog == '1' || empty($user_primary_blog) ) { ?>
        
        <div class="loopis-message warning">
        <h5>⚠️ Innan du väljer!</h5>
        <hr>
        <p><b>Välj bara ett område om du vill använda det!</b></p>
        <p>Du ska kunna hämta och lämna saker i ditt område inom 1-3 dygn.</p>
        </div>

        <p>Vänta med att välja om du vill ha ett område närmre dig:</p>
        <p><span class="mega-link"><a href="<?php echo esc_url(home_url('stats/?view=pages/postareas')); ?>">⚡️ Stötta område</a></span></p>

        <p class="info">💡 Frågor om våra områden? Skriv till <span class="link"><a href="mailto:admin@loopis.app">✉️ admin@loopis.app</a></span></p>
    <?php } ?>

    <?php else : 
    // Not yet member output
    include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-message.php'; 
    endif; 