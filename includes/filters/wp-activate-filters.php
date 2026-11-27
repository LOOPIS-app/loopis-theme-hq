<?php
/**
 * Customized multisite user activation on /wp-activate.php
 * 
 * Created by CoPilot, prompted by Johan.
 */

// Skip loading activation-only hooks outside activation screen.
if (!isset($GLOBALS['pagenow']) || 'wp-activate.php' !== $GLOBALS['pagenow']) {
    return;
}

/**
 * Render an activation template from templates/wp-signup.
 */
function loopis_activate_render_template($template_file, $vars = array()) {
    $template = get_stylesheet_directory() . '/templates/wp-signup/' . ltrim((string) $template_file, '/');
    if (!file_exists($template)) {
        return '';
    }

    if (!empty($vars)) {
        extract($vars, EXTR_SKIP);
    }

    ob_start();
    include $template;

    return trim((string) ob_get_clean());
}

/**
 * Get activation signup details from the current activation key when available.
 */
function loopis_activate_get_signup_details() {
    global $wpdb;

    $activate_cookie = 'wp-activate-' . COOKIEHASH;
    $activation_key = '';

    if (!empty($_GET['key'])) {
        $activation_key = sanitize_text_field(wp_unslash($_GET['key']));
    } elseif (!empty($_POST['key'])) {
        $activation_key = sanitize_text_field(wp_unslash($_POST['key']));
    } elseif (!empty($_COOKIE[$activate_cookie])) {
        $activation_key = sanitize_text_field(wp_unslash($_COOKIE[$activate_cookie]));
    }

    if ('' === $activation_key) {
        return array(
            'user_login' => '',
            'user_email' => '',
            'password' => '',
        );
    }

    $signup = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT user_login, user_email, meta FROM {$wpdb->signups} WHERE activation_key = %s LIMIT 1",
            $activation_key
        )
    );

    if (!$signup) {
        return array(
            'user_login' => '',
            'user_email' => '',
            'password' => '',
        );
    }

    $password = '';
    $signup_meta = maybe_unserialize($signup->meta);

    if (
        is_array($signup_meta)
        && !empty($signup_meta['loopis_signup_password_enc'])
        && function_exists('loopis_signup_decrypt_password')
    ) {
        $password = loopis_signup_decrypt_password((string) $signup_meta['loopis_signup_password_enc']);
    }

    return array(
        'user_login' => isset($signup->user_login) ? (string) $signup->user_login : '',
        'user_email' => isset($signup->user_email) ? (string) $signup->user_email : '',
        'password' => $password,
    );
}

// Enqueue LOOPIS styles on /wp-activate.php only
function loopis_activate_enqueue_assets() {
    wp_enqueue_style('loopis-theme-hq-activate', LOOPIS_THEME_HQ_URI . '/assets/css/wp-activate.css', array(), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/css/wp-activate.css'));
}
add_action('wp_enqueue_scripts', 'loopis_activate_enqueue_assets');

/**
 * Replace default wp-activate success output with the themed login prompt.
 */
function loopis_activate_login_bridge() {
    $login_url = esc_url(wp_login_url(home_url('/start/')));
    $signup_details = loopis_activate_get_signup_details();

    $template_html = loopis_activate_render_template('loopis-activate-screen.php', array(
        'login_url' => $login_url,
        'user_login' => $signup_details['user_login'],
        'user_email' => $signup_details['user_email'],
        'password' => $signup_details['password'],
    ));

    if ('' === $template_html) {
        return;
    }

    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.querySelector('.wp-activate-container');
        if (!container || !container.querySelector('#signup-welcome')) {
            return;
        }

        var heading = container.querySelector('h2');
        var signupWelcome = container.querySelector('#signup-welcome');
        var viewParagraph = container.querySelector('p.view');

        if (heading) { heading.style.display = 'none'; }
        if (signupWelcome) { signupWelcome.style.display = 'none'; }
        if (viewParagraph) { viewParagraph.style.display = 'none'; }

        var existingStage = container.querySelector('.loopis-activate-login-stage');
        if (existingStage) {
            existingStage.remove();
        }

        var pagePadding = document.createElement('div');
        pagePadding.className = 'page-padding center';

        var wrapper = document.createElement('div');
        wrapper.className = 'loopis-activate-login-stage';
        wrapper.innerHTML = <?php echo wp_json_encode($template_html); ?>;

        pagePadding.appendChild(wrapper);
        container.appendChild(pagePadding);
    });
    </script>
    <?php
}
add_action('activate_wp_head', 'loopis_activate_login_bridge');

/**
 * WPMU Activation Actions
 * Run when user clicks activation link on /wp-activate.php
 */

/**
 * Apply chosen signup password when multisite account is activated.
 * Priority 1: Must happen first before other operations
 */
function loopis_activate_apply_password($user_id, $password, $meta) {
    if (!is_array($meta) || empty($meta['loopis_signup_password_enc'])) {
        return;
    }

    $decrypted_password = loopis_signup_decrypt_password((string) $meta['loopis_signup_password_enc']);
    if ('' === $decrypted_password) {
        return;
    }

    wp_set_password($decrypted_password, (int) $user_id);
}
add_action('wpmu_activate_user', 'loopis_activate_apply_password', 1, 3);

/**
 * Persist first name, last name, postcode, and postarea from signup meta into user meta.
 * Priority 2: Store user data early
 */
function loopis_activate_store_names($user_id, $password, $meta) {
    if (!is_array($meta)) {
        return;
    }

    if (!empty($meta['first_name'])) {
        update_user_meta((int) $user_id, 'first_name', loopis_signup_normalize_name((string) $meta['first_name']));
    }

    if (!empty($meta['last_name'])) {
        update_user_meta((int) $user_id, 'last_name', loopis_signup_normalize_name((string) $meta['last_name']));
    }

    if (!empty($meta['wpum_postcode'])) {
        $postcode = loopis_signup_normalize_postcode((string) $meta['wpum_postcode']);
        if (preg_match('/^[0-9]{5}$/', $postcode)) {
            update_user_meta((int) $user_id, 'wpum_postcode', $postcode);
            // Get postarea from postcode
            include LOOPIS_USERS_DIR . '/includes/functions/loopis-get-city.php';
            $postarea = loopis_get_city($postcode);
            update_user_meta((int) $user_id, 'wpum_postarea', $postarea);
        }
    }
}
add_action('wpmu_activate_user', 'loopis_activate_store_names', 2, 3);

/**
 * Add user to main site with role 'member_pending'.
 * Priority 5: Setup site membership
 */
function loopis_activate_add_to_main_site(
    $user_id,
    $password,
    $meta
) {

    $user_id = (int) $user_id;
    $main_site_id = 1;

    add_user_to_blog($main_site_id, $user_id, 'member_pending');
    update_user_meta($user_id, 'primary_blog', '');
}
add_action('wpmu_activate_user', 'loopis_activate_add_to_main_site', 5, 3);

/**
 * Set display name from first and last name.
 * Priority 20: Final profile setup
 */
function loopis_activate_set_display_name( $user_id, $password, $meta ) {
    if ( ! is_array( $meta ) ) {
        error_log( 'Signup display name: activation meta was not an array.' );
        return;
    }

    $first_name = isset( $meta['first_name'] )
        ? loopis_signup_normalize_name( (string) $meta['first_name'] )
        : '';

    $last_name = isset( $meta['last_name'] )
        ? loopis_signup_normalize_name( (string) $meta['last_name'] )
        : '';

    
    $display_name = trim( ucfirst($first_name) . ' ' . ucfirst($last_name) );

    $updated = wp_update_user( [
        'ID'           => (int) $user_id,
        'display_name' => $display_name,
    ] );

    if ( is_wp_error( $updated ) ) {
        error_log( 'Signup display name update failed: ' . $updated->get_error_message() );
    }
}
add_action( 'wpmu_activate_user', 'loopis_activate_set_display_name', 20, 3 );
