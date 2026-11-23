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
<p class="small">💡 För dig som bor i eller nära ett av LOOPIS områden.</p>

<?php if (in_array('member', $user_roles, true)) {

// Get current area selection
$primary_blog = get_usermeta($user_id, 'primary_blog', true); ?>

<?php if ($primary_blog) : ?>
	<p>✅ Ditt område: <b><?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-primary-blog.php'; ?></b></p>
    <p class="info">🚛 För att byta område, kontakta <span class="link"><a href="mailto:admin@loopis.app">✉️ admin@loopis.app</a></span></p>
    
    <?php else :
    // Render area selector form
    include_once LOOPIS_THEME_HQ_DIR . '/includes/forms/area-selector.php';
    loopis_render_area_selector_form(); ?>

    <h3>🙋 Stötta nytt område</h3>
    <hr>
    <p class="small">💡 För dig som vill ha LOOPIS i ett nytt område.</p>
    <span class="mega-link"><a href="<?php echo esc_url(home_url('/postareas')); ?>">⚡️ Stötta nytt område</a></span>

    <!--p><span class="link"><a href="<?php echo esc_url( home_url( '/faq/hur-startar-man-loopis' ) ); ?>">📌 Hur startar man LOOPIS?</a></span></p-->
    <?php endif; ?>
        <p class="info">Frågor om våra områden? Skriv till <span class="link"><a href="mailto:admin@loopis.app">✉️ admin@loopis.app</a></span></p>
<?php } else { 
    // Not yet member
     include LOOPIS_THEME_HQ_DIR . '/includes/output/access/mainsite-message.php'; 
} 