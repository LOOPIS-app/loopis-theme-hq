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
 * Render an activation template from templates/signup.
 */
function loopis_theme_hq_render_activate_template($template_file, $vars = array()) {
    $template = get_stylesheet_directory() . '/templates/signup/' . ltrim((string) $template_file, '/');
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

// Enqueue signup styles on activation page as well.
function loopis_theme_hq_activate_assets() {
    wp_enqueue_style('loopis-theme-hq-signup', LOOPIS_THEME_HQ_URI . '/assets/css/wp-signup.css', array(), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/css/wp-signup.css'));
}
add_action('wp_enqueue_scripts', 'loopis_theme_hq_activate_assets');

/**
 * Replace default wp-activate success output with the themed login prompt.
 */
function loopis_theme_hq_activation_login_bridge() {
    $login_url = esc_url(wp_login_url(home_url('/shop/?option=membership-stripe')));
    $template_html = loopis_theme_hq_render_activate_template('loopis-activate-title.php', array(
        'login_url' => $login_url,
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
