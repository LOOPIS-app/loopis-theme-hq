<?php
/**
 * Filters and actions affecting multisite login /wp-login.php.
 * 
 * Created by CoPilot, prompted by Johan.
 * 
 * Notice: Heavy customization bdecause the default WP login page is using login_head instead of wp_head.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

// Skip loading login-only hooks on non-login requests.
$loopis_current_script = isset($_SERVER['SCRIPT_NAME']) ? (string) wp_unslash($_SERVER['SCRIPT_NAME']) : '';
$loopis_is_login_request = (isset($GLOBALS['pagenow']) && 'wp-login.php' === $GLOBALS['pagenow']) || false !== strpos($loopis_current_script, 'wp-login.php');
if (!$loopis_is_login_request) {
    return;
}

// Render a named slot from dedicated templates in templates/wp-login.
function loopis_theme_hq_login_template_slot($slot) {
    $slot_map = array(
        'message' => 'loopis-login-title.php',
        'custom_html_1' => 'loopis-login-custom-1.php',
        'custom_html_2' => 'loopis-login-custom-2.php',
    );

    $template_file = isset($slot_map[$slot]) ? $slot_map[$slot] : '';
    if ('' === $template_file) {
        return '';
    }

    $template = get_stylesheet_directory() . '/templates/wp-login/' . $template_file;
    if (!file_exists($template)) {
        return '';
    }

    ob_start();
    include $template;

    return trim((string) ob_get_clean());
}


// Enqueue LOOPIS styles on wp-login.php only.
function loopis_theme_hq_login_assets() {
    $shared_theme_slug = 'loopis-theme';
    $shared_theme_dir  = trailingslashit( get_theme_root() ) . $shared_theme_slug;
    $shared_theme_uri  = trailingslashit( get_theme_root_uri() ) . $shared_theme_slug;

    wp_enqueue_style('loopis-theme-hq-style', $shared_theme_uri . '/assets/css/base.css', array(), filemtime($shared_theme_dir . '/assets/css/base.css'));
    wp_enqueue_style('loopis-theme-hq-responsive', $shared_theme_uri . '/assets/css/responsive.css', array('loopis-theme-hq-style'), filemtime($shared_theme_dir . '/assets/css/responsive.css'));
    wp_enqueue_style('loopis-theme-hq-fa', LOOPIS_THEME_HQ_URI . '/assets/fonts/css/fontawesome.min.css', array(), LOOPIS_THEME_HQ_VERSION);
    wp_enqueue_style('loopis-theme-hq-fa-solid', LOOPIS_THEME_HQ_URI . '/assets/fonts/css/solid.min.css', array('loopis-theme-hq-fa'), LOOPIS_THEME_HQ_VERSION);
    wp_enqueue_style('loopis-theme-hq-forms', $shared_theme_uri . '/assets/css/forms.css', array('loopis-theme-hq-style'), filemtime($shared_theme_dir . '/assets/css/forms.css'));
    wp_enqueue_style('loopis-theme-hq-login', LOOPIS_THEME_HQ_URI . '/assets/css/wp-login.css', array('loopis-theme-hq-forms'), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/css/wp-login.css'));
}
add_action('login_enqueue_scripts', 'loopis_theme_hq_login_assets');

// Set logo link target on login page.
function loopis_theme_hq_login_headerurl() {
    return home_url('/');
}
add_filter('login_headerurl', 'loopis_theme_hq_login_headerurl');

// Set logo link title text on login page.
function loopis_theme_hq_login_headertext() {
    return 'Logga in';
}
add_filter('login_headertext', 'loopis_theme_hq_login_headertext');

/**
 * Redirect all users after login unless an explicit redirect target is provided.
 */
function loopis_theme_hq_login_redirect($redirect_to, $requested_redirect_to, $user) {
    if (!($user instanceof WP_User)) {
        return $redirect_to;
    }

    if (!empty($requested_redirect_to)) {
        return $requested_redirect_to;
    }

    return home_url('/');
}
add_filter('login_redirect', 'loopis_theme_hq_login_redirect', 10, 3);

// Inject LOOPIS heading and helper text above the login form.
function loopis_theme_hq_login_message($message) {
    $action = isset($_REQUEST['action']) ? sanitize_key($_REQUEST['action']) : 'login';
    $supported_actions = array('login', 'lostpassword', 'rp', 'resetpass', 'register');
    if (!in_array($action, $supported_actions, true)) {
        return $message;
    }

    $template_message = loopis_theme_hq_login_template_slot('message');

    if ('' === $template_message) {
        $template_message = '<h1>👤 Logga in</h1><hr><p class="small">💡 För medlemmar.</p>';
    }

    return $template_message . $message;
}
add_filter('login_message', 'loopis_theme_hq_login_message');

// wp-login.php uses login_head/login_footer, so load Twemoji through login_head.
add_action('login_head', 'use_twemoji');

// Optional custom HTML block rendered between "Forgot password" and register link.
function loopis_theme_hq_login_custom_html_1() {
    $template_html = loopis_theme_hq_login_template_slot('custom_html_1');

    if ('' === $template_html) {
        return '<br><h3>💔 Inte medlem?</h3><hr><p class="small">💡 Registrera dig och betala 50 kr.</p>';
    }

    return $template_html;
}

// Optional custom HTML block rendered between register link and back-to-blog.
function loopis_theme_hq_login_custom_html_2() {
    $template_html = loopis_theme_hq_login_template_slot('custom_html_2');

    if ('' === $template_html) {
        return '<p><span class="big-link"><a href="/faq/varför-medlemskap">📌 Varför måste jag vara medlem?</a></span></p>';
    }

    return $template_html;
}

// Post-process login page markup to add icons and improve nav/button structure.
function loopis_theme_hq_login_nav_icons() {
    ?>
    <script type="text/javascript">
    (function() {
        var customHtml1 = <?php echo wp_json_encode(loopis_theme_hq_login_custom_html_1()); ?>;
        var customHtml2 = <?php echo wp_json_encode(loopis_theme_hq_login_custom_html_2()); ?>;
        var nav = document.getElementById('nav');
        var registerLink = document.querySelector('#nav a.wp-login-register');
        var lostPasswordLink = document.querySelector('#nav a.wp-login-lost-password');
        var backToBlogLink = document.querySelector('#backtoblog a');
        var linksContainer = document.getElementById('loopis-login-links');
        var customHtmlBlock1 = document.getElementById('loopis-login-custom-html-1');
        var customHtmlBlock2 = document.getElementById('loopis-login-custom-html-2');
        var registerParagraph = document.getElementById('loopis-login-register');
        var lostPasswordParagraph = document.getElementById('loopis-login-lostpassword');
        var backToBlog = document.getElementById('backtoblog');
        var rememberMe = document.getElementById('rememberme');
        var languageSubmit = document.querySelector('#language-switcher input.button[type="submit"]');
        var languageButton = document.querySelector('#language-switcher button.button[type="submit"]');

        function wrapInBigLink(link) {
            if (!link || (link.parentElement && link.parentElement.classList.contains('big-link'))) {
                return;
            }

            var wrapper = document.createElement('span');
            wrapper.className = 'big-link';
            link.parentNode.insertBefore(wrapper, link);
            wrapper.appendChild(link);
        }

        function getBigLinkNode(link) {
            if (link && link.parentElement && link.parentElement.classList.contains('big-link')) {
                return link.parentElement;
            }

            return link;
        }

        // Keep "Remember me" checked by default on the login form.
        if (rememberMe && !rememberMe.checked) {
            rememberMe.checked = true;
        }

        // Prefix action links with icons once.
        if (registerLink && !registerLink.dataset.loopisEmojiApplied) {
            registerLink.prepend(document.createTextNode('📋 '));
            registerLink.dataset.loopisEmojiApplied = '1';
        }

        if (lostPasswordLink && !lostPasswordLink.dataset.loopisEmojiApplied) {
            lostPasswordLink.prepend(document.createTextNode('🤔 '));
            lostPasswordLink.dataset.loopisEmojiApplied = '1';
        }

        wrapInBigLink(registerLink);
        wrapInBigLink(lostPasswordLink);
        wrapInBigLink(backToBlogLink);

        // Move register link into its own paragraph.
        if (registerLink && !registerParagraph) {
            registerParagraph = document.createElement('p');
            registerParagraph.id = 'loopis-login-register';
            registerParagraph.appendChild(getBigLinkNode(registerLink));
        }

        // Move lost-password link into its own paragraph.
        if (lostPasswordLink && !lostPasswordParagraph) {
            lostPasswordParagraph = document.createElement('p');
            lostPasswordParagraph.id = 'loopis-login-lostpassword';
            lostPasswordParagraph.appendChild(getBigLinkNode(lostPasswordLink));
        }

        // Build and order a dedicated block below the form.
        if ((registerParagraph || lostPasswordParagraph) && !linksContainer) {
            linksContainer = document.createElement('div');
            linksContainer.id = 'loopis-login-links';

            if (backToBlog) {
                backToBlog.insertAdjacentElement('beforebegin', linksContainer);
            } else if (nav) {
                nav.insertAdjacentElement('afterend', linksContainer);
            }
        }

        if (linksContainer) {
            if (lostPasswordParagraph) {
                linksContainer.appendChild(lostPasswordParagraph);
            }

            if (!customHtmlBlock1 && customHtml1) {
                customHtmlBlock1 = document.createElement('div');
                customHtmlBlock1.id = 'loopis-login-custom-html-1';
                customHtmlBlock1.className = 'loopis-login-custom-html';
            }

            if (customHtmlBlock1) {
                customHtmlBlock1.innerHTML = customHtml1;
                linksContainer.appendChild(customHtmlBlock1);
            }

            if (registerParagraph) {
                linksContainer.appendChild(registerParagraph);
            }

            if (!customHtmlBlock2 && customHtml2) {
                customHtmlBlock2 = document.createElement('div');
                customHtmlBlock2.id = 'loopis-login-custom-html-2';
                customHtmlBlock2.className = 'loopis-login-custom-html';
            }

            if (customHtmlBlock2) {
                customHtmlBlock2.innerHTML = customHtml2;
                linksContainer.appendChild(customHtmlBlock2);
            }
        }

        // Remove default nav paragraph to get rid of core separator formatting.
        if (nav && (registerParagraph || lostPasswordParagraph)) {
            nav.remove();
        }

        // Replace input submit with button so emoji becomes a parseable text node.
        if (languageSubmit && !languageButton) {
            var replacementButton = document.createElement('button');
            replacementButton.type = 'submit';
            replacementButton.className = languageSubmit.className;
            replacementButton.textContent = '🌍 ' + languageSubmit.value;
            languageSubmit.parentNode.replaceChild(replacementButton, languageSubmit);
            languageButton = replacementButton;
        }

        // Parse newly inserted/moved content so LOOPIS Twemoji rules apply.
        if (linksContainer && window.twemoji) {
            twemoji.parse(linksContainer, {
                folder: 'svg',
                ext: '.svg'
            });
        }

        if (languageButton && window.twemoji) {
            twemoji.parse(languageButton, {
                folder: 'svg',
                ext: '.svg'
            });
        }
    }());
    </script>
    <?php
}
add_action('login_footer', 'loopis_theme_hq_login_nav_icons');

// Translate "Register" link text to Swedish on the login page.
function loopis_theme_hq_login_register_text($translated, $text, $domain) {
    if ('Register' === $text && 'default' === $domain) {
        return 'Bli medlem';
    }
    return $translated;
}
add_filter('gettext', 'loopis_theme_hq_login_register_text', 10, 3);
