<?php
/**
 * Filters and actions affecting multisite activation on /wp-activate.php
 * 
 * Created by CoPilot, prompted by Johan.
 *
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

// Skip loading activation-only hooks outside activation screen.
if (!isset($GLOBALS['pagenow']) || 'wp-activate.php' !== $GLOBALS['pagenow']) {
    return;
}

/**
 * Render an activation template from templates/wp-signup.
 */
function loopis_theme_hq_render_activate_template($template_file, $vars = array()) {
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
function loopis_theme_hq_get_activation_signup_details() {
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
        && function_exists('loopis_theme_hq_decrypt_signup_password')
    ) {
        $password = loopis_theme_hq_decrypt_signup_password((string) $signup_meta['loopis_signup_password_enc']);
    }

    return array(
        'user_login' => isset($signup->user_login) ? (string) $signup->user_login : '',
        'user_email' => isset($signup->user_email) ? (string) $signup->user_email : '',
        'password' => $password,
    );
}

// Enqueue LOOPIS styles on /wp-activate.php only
function loopis_theme_hq_activate_assets() {
    wp_enqueue_style('loopis-theme-hq-activate', LOOPIS_THEME_HQ_URI . '/assets/css/wp-activate.css', array(), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/css/wp-activate.css'));
}
add_action('wp_enqueue_scripts', 'loopis_theme_hq_activate_assets');

/**
 * Replace default wp-activate success output with the themed login prompt.
 */
function loopis_theme_hq_activation_login_bridge() {
    $login_url = esc_url(wp_login_url(home_url('/shop/?option=membership-stripe')));
    $signup_details = loopis_theme_hq_get_activation_signup_details();

    $template_html = loopis_theme_hq_render_activate_template('loopis-activate-screen.php', array(
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
add_action('activate_wp_head', 'loopis_theme_hq_activation_login_bridge');
