<?php
/**
 * Filters and actions affecting multisite signup on /wp-signup.php
 * 
 * Created by CoPilot, prompted by Johan.
 *
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

// Load on both signup and activation screens so activation hooks are available.
$loopis_current_script = isset($_SERVER['SCRIPT_NAME']) ? (string) wp_unslash($_SERVER['SCRIPT_NAME']) : '';
$loopis_is_signup_request = (isset($GLOBALS['pagenow']) && in_array($GLOBALS['pagenow'], array('wp-signup.php', 'wp-activate.php'), true)) || false !== strpos($loopis_current_script, 'wp-signup.php') || false !== strpos($loopis_current_script, 'wp-activate.php');
if (!$loopis_is_signup_request) {
    return;
}

/**
 * Render a signup template from templates/wp-signup.
 */
function loopis_theme_hq_render_signup_template($template_file, $vars = array()) {
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

// Enqueue LOOPIS styles on wp-signup.php only.
function loopis_theme_hq_signup_assets() {
    wp_enqueue_style('dashicons');

    $shared_theme_slug = 'loopis-theme';
    $shared_theme_dir  = trailingslashit( get_theme_root() ) . $shared_theme_slug;
    $shared_theme_uri  = trailingslashit( get_theme_root_uri() ) . $shared_theme_slug;

    wp_enqueue_style('loopis-theme-hq-style', $shared_theme_uri . '/assets/css/base.css', array(), filemtime($shared_theme_dir . '/assets/css/base.css'));
    wp_enqueue_style('loopis-theme-hq-forms', $shared_theme_uri . '/assets/css/forms.css', array('loopis-theme-hq-style'), filemtime($shared_theme_dir . '/assets/css/forms.css'));
    wp_enqueue_style('loopis-theme-hq-responsive', $shared_theme_uri . '/assets/css/responsive.css', array('loopis-theme-hq-style'), filemtime($shared_theme_dir . '/assets/css/responsive.css'));
    wp_enqueue_style('loopis-theme-hq-signup', LOOPIS_THEME_HQ_URI . '/assets/css/wp-signup.css', array('loopis-theme-hq-forms'), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/css/wp-signup.css'));
}
add_action('wp_enqueue_scripts', 'loopis_theme_hq_signup_assets');

/**
 * Disable default multisite verification email so we can send our styled HTML variant.
 */
add_filter('wpmu_signup_user_notification', '__return_false');

/**
 * Load shared mail helper templates once.
 */
function loopis_theme_hq_load_mail_templates() {
    $base = get_stylesheet_directory() . '/templates/mail/';

    $required_files = array(
        'mail-headers.php',
        'mail-template.php',
        'mail-footer.php',
    );

    foreach ($required_files as $file) {
        $path = $base . $file;
        if (file_exists($path)) {
            include_once $path;
        }
    }
}

/**
 * Send styled verification email for multisite user signup.
 */
function loopis_theme_hq_send_custom_signup_verification_email($user_login, $user_email, $key, $meta) {
    loopis_theme_hq_load_mail_templates();

    if (!function_exists('loopis_mail_headers') || !function_exists('loopis_mail_template') || !function_exists('loopis_mail_footer')) {
        return;
    }

    $activation_url = esc_url(site_url('wp-activate.php?key=' . rawurlencode((string) $key)));
    $first_name = is_array($meta) && !empty($meta['first_name']) ? sanitize_text_field((string) $meta['first_name']) : '';

    if ('' !== $first_name) {
        $mail_intro = 'Hej ' . esc_html($first_name);
    } else {
        $mail_intro = 'Hej';
    }

    $mail_content = 'Bekräfta din e-postadress genom att klicka på länken nedan:<br><a href="' . $activation_url . '">' . $activation_url . '</a>';
    $mail_outro = 'När ditt konto är aktiverat får du ett nytt mail med inloggningsuppgifter.';

    $message = loopis_mail_template($mail_intro, $mail_outro, $mail_content) . loopis_mail_footer();

    $subject = sprintf('[%s] Bekräfta ditt konto', get_bloginfo('name'));
    wp_mail($user_email, wp_specialchars_decode($subject), $message, loopis_mail_headers());
}
add_action('after_signup_user', 'loopis_theme_hq_send_custom_signup_verification_email', 20, 4);

/**
 * Build a username from first and last name.
 *
 * Format: firstname-lastname
 */
function loopis_theme_hq_build_signup_username($first_name, $last_name) {
    $first_name = sanitize_text_field($first_name);
    $last_name = sanitize_text_field($last_name);

    $raw = strtolower(trim(remove_accents($first_name . '-' . $last_name)));
    $raw = preg_replace('/\s+/', '-', $raw);
    $raw = preg_replace('/-+/', '-', $raw);
    $raw = trim($raw, '-');

    return sanitize_user($raw, true);
}

/**
 * Check whether a generated signup username is already taken.
 *
 * Includes existing users and pending multisite signups.
 */
function loopis_theme_hq_is_signup_username_taken($username) {
    global $wpdb;

    if ('' === $username) {
        return true;
    }

    if (username_exists($username)) {
        return true;
    }

    $pending_signup = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s AND active = 0 LIMIT 1",
            $username
        )
    );

    return !empty($pending_signup);
}

/**
 * Generate first available username from first/last name.
 *
 * Format: firstname-lastname, then firstname-lastname-2, -3, ...
 */
function loopis_theme_hq_generate_available_signup_username($first_name, $last_name) {
    $base_username = loopis_theme_hq_build_signup_username($first_name, $last_name);
    if ('' === $base_username) {
        return '';
    }

    if (!loopis_theme_hq_is_signup_username_taken($base_username)) {
        return $base_username;
    }

    $suffix = 2;
    while ($suffix <= 9999) {
        $candidate = sanitize_user($base_username . '-' . $suffix, true);
        if ('' !== $candidate && !loopis_theme_hq_is_signup_username_taken($candidate)) {
            return $candidate;
        }
        $suffix++;
    }

    return '';
}

/**
 * Normalize person names for storage in user meta.
 *
 * Example: "anna" => "Anna", "karl-erik" => "Karl-Erik"
 */
function loopis_theme_hq_normalize_person_name($name) {
    $name = trim(sanitize_text_field($name));
    if ('' === $name) {
        return '';
    }

    if (function_exists('mb_convert_case')) {
        return mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    }

    return ucwords(strtolower($name));
}

/**
 * Normalize postcode input to digits only.
 */
function loopis_theme_hq_normalize_postcode($postcode) {
    return preg_replace('/\D+/', '', (string) $postcode);
}

/**
 * Keep user_name in sync on submit so core multisite validation runs on generated value.
 */
function loopis_theme_hq_signup_prepare_username() {
    if ('POST' !== strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET')) {
        return;
    }

    $first_name = isset($_POST['first_name']) ? wp_unslash($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? wp_unslash($_POST['last_name']) : '';

    $generated_username = loopis_theme_hq_generate_available_signup_username($first_name, $last_name);
    if ('' !== $generated_username) {
        $_POST['user_name'] = $generated_username;
    }
}
add_action('init', 'loopis_theme_hq_signup_prepare_username', 0);

/**
 * Render first and last name fields on wp-signup.php.
 */
function loopis_theme_hq_signup_extra_name_fields() {
    $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
    ?>
    <p>
        <label for="first_name"><?php echo esc_html__('First name', 'loopis-theme-hq'); ?></label>
        <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($first_name); ?>" autocomplete="given-name" required />
    </p>
    <p>
        <label for="last_name"><?php echo esc_html__('Last name', 'loopis-theme-hq'); ?></label>
        <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($last_name); ?>" autocomplete="family-name" required />
    </p>
    <p>
        <label for="signup_password"><?php echo esc_html__('Password', 'loopis-theme-hq'); ?></label>
        <input type="password" name="signup_password" id="signup_password" value="" autocomplete="new-password" required />
    </p>
    <p>
        <label for="signup_password_confirm"><?php echo esc_html__('Confirm password', 'loopis-theme-hq'); ?></label>
        <input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" autocomplete="new-password" required />
    </p>
    <?php
}
add_action('signup_extra_fields', 'loopis_theme_hq_signup_extra_name_fields');

/**
 * Encrypt signup password for temporary storage in signup meta.
 */
function loopis_theme_hq_encrypt_signup_password($password) {
    if ('' === $password || !function_exists('openssl_encrypt')) {
        return '';
    }

    $method = 'aes-256-cbc';
    $iv_length = openssl_cipher_iv_length($method);
    if ($iv_length <= 0) {
        return '';
    }

    $key = hash('sha256', wp_salt('auth'), true);

    try {
        $iv = random_bytes($iv_length);
    } catch (\Exception $e) {
        return '';
    }

    $ciphertext = openssl_encrypt($password, $method, $key, OPENSSL_RAW_DATA, $iv);
    if (false === $ciphertext) {
        return '';
    }

    return base64_encode($iv . $ciphertext);
}

/**
 * Decrypt signup password from signup meta.
 */
function loopis_theme_hq_decrypt_signup_password($encrypted_password) {
    if ('' === $encrypted_password || !function_exists('openssl_decrypt')) {
        return '';
    }

    $payload = base64_decode($encrypted_password, true);
    if (false === $payload) {
        return '';
    }

    $method = 'aes-256-cbc';
    $iv_length = openssl_cipher_iv_length($method);
    if ($iv_length <= 0 || strlen($payload) <= $iv_length) {
        return '';
    }

    $key = hash('sha256', wp_salt('auth'), true);
    $iv = substr($payload, 0, $iv_length);
    $ciphertext = substr($payload, $iv_length);

    $password = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);

    return false === $password ? '' : $password;
}

/**
 * Hide the default username input and keep it auto-generated in the form.
 */
function loopis_theme_hq_signup_username_ui_bridge() {
    if ('wp-signup.php' !== ($GLOBALS['pagenow'] ?? '')) {
        return;
    }

    ?>
    <style>
        #setupform #user_name,
        #setupform label[for="user_name"],
        #setupform #wp-signup-username-description,
        #setupform .loopis-signup-hidden {
            display: none;
        }
    </style>
    <script type="text/javascript">
    (function() {
        function toUsernamePart(value) {
            return value
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function syncUsername() {
            var firstName = document.getElementById('first_name');
            var lastName = document.getElementById('last_name');
            var userName = document.getElementById('user_name');

            if (!firstName || !lastName || !userName) {
                return;
            }

            var first = toUsernamePart(firstName.value || '');
            var last = toUsernamePart(lastName.value || '');
            var composed = (first && last) ? (first + '-' + last) : (first || last);

            userName.value = composed;
        }

        function moveNameFieldsToTop() {
            var form = document.getElementById('setupform');
            var firstNameInput = document.getElementById('first_name');
            var lastNameInput = document.getElementById('last_name');

            if (!form || !firstNameInput || !lastNameInput) {
                return;
            }

            var firstNameRow = firstNameInput.closest('p');
            var lastNameRow = lastNameInput.closest('p');
            var userNameLabel = document.querySelector('#setupform label[for="user_name"]');
            var userEmailLabel = document.querySelector('#setupform label[for="user_email"]');
            var anchorNode = userNameLabel || userEmailLabel;

            if (!firstNameRow || !lastNameRow) {
                return;
            }

            if (anchorNode && firstNameRow !== anchorNode.previousElementSibling) {
                form.insertBefore(firstNameRow, anchorNode);
            }

            if (firstNameRow.nextElementSibling !== lastNameRow) {
                form.insertBefore(lastNameRow, firstNameRow.nextElementSibling);
            }
        }

        function normalizeEmailLabel() {
            var userEmailLabel = document.querySelector('#setupform label[for="user_email"]');
            if (!userEmailLabel) {
                return;
            }

            userEmailLabel.innerHTML = userEmailLabel.innerHTML.replace(/:\s*$/, '');
        }

        function hideUsernameRow() {
            var userNameInput = document.getElementById('user_name');
            var userNameDescription = document.getElementById('wp-signup-username-description');

            var userNameLabel = document.querySelector('#setupform label[for="user_name"]');
            if (userNameLabel) {
                userNameLabel.classList.add('loopis-signup-hidden');
            }

            if (userNameInput) {
                userNameInput.classList.add('loopis-signup-hidden');
            }

            if (userNameDescription) {
                userNameDescription.classList.add('loopis-signup-hidden');
            }
        }

        function wrapSignupErrors() {
            var loginError = document.getElementById('login_error');
            if (loginError && !loginError.classList.contains('loopis-message')) {
                loginError.classList.add('loopis-message', 'error');
                loginError.removeAttribute('id');
            }

            var errorBlocks = document.querySelectorAll('#signup-content .error, #setupform .error');
            errorBlocks.forEach(function(block) {
                if (block.classList.contains('loopis-message')) {
                    return;
                }

                var wrapper = document.createElement('div');
                wrapper.className = 'loopis-message error';
                wrapper.innerHTML = block.innerHTML;
                block.parentNode.replaceChild(wrapper, block);
            });
        }

        function addPasswordVisibilityToggles() {
            var passwordFieldIds = ['signup_password', 'signup_password_confirm'];

            passwordFieldIds.forEach(function(fieldId) {
                var input = document.getElementById(fieldId);
                if (!input || input.dataset.loopisPasswordToggleReady === '1') {
                    return;
                }

                var wrapper = document.createElement('div');
                wrapper.className = 'loopis-password-toggle-wrap wp-pwd';

                var parent = input.parentNode;
                parent.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                var button = document.createElement('button');
                button.type = 'button';
                button.className = 'button button-secondary wp-hide-pw hide-if-no-js';
                button.setAttribute('data-toggle', '0');
                button.setAttribute('aria-label', 'Visa lösenord');
                button.setAttribute('aria-pressed', 'false');
                button.innerHTML = '<span class="dashicons dashicons-visibility" aria-hidden="true"></span>';

                var icon = button.querySelector('.dashicons');

                button.addEventListener('click', function() {
                    var isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.setAttribute('data-toggle', isPassword ? '1' : '0');
                    button.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                    button.setAttribute('aria-label', isPassword ? 'Dölj lösenord' : 'Visa lösenord');
                    if (icon) {
                        icon.className = isPassword ? 'dashicons dashicons-hidden' : 'dashicons dashicons-visibility';
                    }
                });

                wrapper.appendChild(button);
                input.dataset.loopisPasswordToggleReady = '1';
            });
        }

        function initSignupFormUi() {
            moveNameFieldsToTop();
            normalizeEmailLabel();
            hideUsernameRow();
            wrapSignupErrors();
            addPasswordVisibilityToggles();
            syncUsername();
        }

        document.addEventListener('input', function(event) {
            if (event.target && (event.target.id === 'first_name' || event.target.id === 'last_name')) {
                syncUsername();
            }
        });

        document.addEventListener('submit', function(event) {
            if (event.target && event.target.id === 'setupform') {
                syncUsername();
            }
        });

        document.addEventListener('DOMContentLoaded', initSignupFormUi);
        initSignupFormUi();
    }());
    </script>
    <?php
}
add_action('wp_head', 'loopis_theme_hq_signup_username_ui_bridge');

/**
 * Validate generated username and required first/last names server-side.
 */
function loopis_theme_hq_validate_signup_name_fields($result) {
    $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
    $signup_password = isset($_POST['signup_password']) ? (string) wp_unslash($_POST['signup_password']) : '';
    $signup_password_confirm = isset($_POST['signup_password_confirm']) ? (string) wp_unslash($_POST['signup_password_confirm']) : '';

    if ('' === $first_name) {
        $result['errors']->add('first_name', __('Please enter your first name.', 'loopis-theme-hq'));
    }

    if ('' === $last_name) {
        $result['errors']->add('last_name', __('Please enter your last name.', 'loopis-theme-hq'));
    }

    if ('' === $signup_password) {
        $result['errors']->add('generic', __('Please enter a password.', 'loopis-theme-hq'));
    } elseif (strlen($signup_password) < 8) {
        $result['errors']->add('generic', __('Password must be at least 8 characters long.', 'loopis-theme-hq'));
    }

    if ($signup_password !== $signup_password_confirm) {
        $result['errors']->add('generic', __('Password confirmation does not match.', 'loopis-theme-hq'));
    }

    $generated_username = loopis_theme_hq_generate_available_signup_username($first_name, $last_name);
    if ('' === $generated_username) {
        $result['errors']->add('user_name', __('Please provide a valid first and last name to generate a username.', 'loopis-theme-hq'));
        return $result;
    }

    // Replace core username errors so our generated pattern can include hyphens.
    $result['errors']->remove('user_name');

    $result['user_name'] = $generated_username;

    // Allow lowercase letters and numbers separated by single hyphens.
    if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $generated_username)) {
        $result['errors']->add('user_name', __('This username is invalid. Please use only lowercase letters, numbers, and hyphens.', 'loopis-theme-hq'));
    }

    if (strlen($generated_username) < 4) {
        $result['errors']->add('user_name', __('Username must be at least 4 characters long after generation.', 'loopis-theme-hq'));
    }

    $illegal_logins = (array) apply_filters('illegal_user_logins', array());
    if (in_array($generated_username, $illegal_logins, true)) {
        $result['errors']->add('user_name', __('Sorry, that username is not allowed.', 'loopis-theme-hq'));
    }

    return $result;
}
add_filter('wpmu_validate_user_signup', 'loopis_theme_hq_validate_signup_name_fields');

/**
 * Persist first and last name into signup meta for activation.
 */
function loopis_theme_hq_signup_user_meta($meta) {
    $first_name = isset($_POST['first_name']) ? loopis_theme_hq_normalize_person_name(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? loopis_theme_hq_normalize_person_name(wp_unslash($_POST['last_name'])) : '';
    $signup_password = isset($_POST['signup_password']) ? (string) wp_unslash($_POST['signup_password']) : '';

    if ('' !== $first_name) {
        $meta['first_name'] = $first_name;
    }

    if ('' !== $last_name) {
        $meta['last_name'] = $last_name;
    }

    $encrypted_password = loopis_theme_hq_encrypt_signup_password($signup_password);
    if ('' !== $encrypted_password) {
        $meta['loopis_signup_password_enc'] = $encrypted_password;
    }

    return $meta;
}
add_filter('signup_user_meta', 'loopis_theme_hq_signup_user_meta');

/**
 * Apply chosen signup password when multisite account is activated.
 */
function loopis_theme_hq_apply_signup_password_on_activation($user_id, $password, $meta) {
    if (!is_array($meta) || empty($meta['loopis_signup_password_enc'])) {
        return;
    }

    $decrypted_password = loopis_theme_hq_decrypt_signup_password((string) $meta['loopis_signup_password_enc']);
    if ('' === $decrypted_password) {
        return;
    }

    wp_set_password($decrypted_password, (int) $user_id);
}
add_action('wpmu_activate_user', 'loopis_theme_hq_apply_signup_password_on_activation', 1, 3);

/**
 * Persist first and last name from signup meta into user meta on activation.
 */
function loopis_theme_hq_store_signup_names_on_activation($user_id, $password, $meta) {
    if (!is_array($meta)) {
        return;
    }

    if (!empty($meta['first_name'])) {
        update_user_meta((int) $user_id, 'first_name', loopis_theme_hq_normalize_person_name((string) $meta['first_name']));
    }

    if (!empty($meta['last_name'])) {
        update_user_meta((int) $user_id, 'last_name', loopis_theme_hq_normalize_person_name((string) $meta['last_name']));
    }

    if (!empty($meta['wpum_postcode'])) {
        $postcode = loopis_theme_hq_normalize_postcode((string) $meta['wpum_postcode']);
        if (preg_match('/^[0-9]{5}$/', $postcode)) {
            update_user_meta((int) $user_id, 'wpum_postcode', $postcode);
        }
    }
}
add_action('wpmu_activate_user', 'loopis_theme_hq_store_signup_names_on_activation', 2, 3);

/**
 * Ensure newly activated users are added to the main site in multisite.
 */
function loopis_theme_hq_add_activated_user_to_main_site($user_id, $password, $meta) {
    if (!is_multisite()) {
        return;
    }

    $main_site_id = function_exists('get_main_site_id') ? (int) get_main_site_id() : 1;
    if ($main_site_id <= 0) {
        return;
    }

    if (is_user_member_of_blog((int) $user_id, $main_site_id)) {
        return;
    }

    $role = 'member_pending';
    if (!get_role($role)) {
        $role = (string) get_option('default_role', 'subscriber');
        if ('' === $role) {
            $role = 'subscriber';
        }
    }

    $added = add_user_to_blog($main_site_id, (int) $user_id, $role);
    if (is_wp_error($added)) {
        error_log('LOOPIS: Failed adding activated user ' . (int) $user_id . ' to site ' . $main_site_id . ': ' . $added->get_error_message());
    }
}
add_action('wpmu_activate_user', 'loopis_theme_hq_add_activated_user_to_main_site', 5, 3);

/**
 * Disable default welcome mail when a chosen signup password exists.
 */
function loopis_theme_hq_disable_default_welcome_user_notification($user_id, $password, $meta) {
    if (is_array($meta) && !empty($meta['loopis_signup_password_enc'])) {
        return false;
    }

    return $user_id;
}
add_filter('wpmu_welcome_user_notification', 'loopis_theme_hq_disable_default_welcome_user_notification', 10, 3);

/**
 * Send welcome mail using the chosen signup password instead of core's random one.
 */
function loopis_theme_hq_send_custom_welcome_user_notification($user_id, $password, $meta) {
    if (!is_array($meta) || empty($meta['loopis_signup_password_enc'])) {
        return;
    }

    $decrypted_password = loopis_theme_hq_decrypt_signup_password((string) $meta['loopis_signup_password_enc']);
    if ('' === $decrypted_password) {
        return;
    }

    remove_filter('wpmu_welcome_user_notification', 'loopis_theme_hq_disable_default_welcome_user_notification', 10);
    wpmu_welcome_user_notification((int) $user_id, $decrypted_password, $meta);
    add_filter('wpmu_welcome_user_notification', 'loopis_theme_hq_disable_default_welcome_user_notification', 10, 3);
}
add_action('wpmu_activate_user', 'loopis_theme_hq_send_custom_welcome_user_notification', 10, 3);

/**
 * Suppress the default "Get your own … account in seconds" heading on wp-signup.php.
 */
function loopis_theme_hq_hide_default_signup_heading() {
    ?>
    <style>
        #signup-content h2:first-of-type {
            display: none;
        }
    </style>
    <?php
}
add_action('signup_header', 'loopis_theme_hq_hide_default_signup_heading');

/**
 * Output custom signup page header from isolated template, then open page-padding center wrapper.
 */
function loopis_theme_hq_signup_custom_header() {
    echo '<div class="page-padding center">';
    echo '<div id="loopis-signup-title">';
    $template_html = loopis_theme_hq_render_signup_template('loopis-signup-title.php');
    if ('' !== $template_html) {
        echo $template_html;
    }
    echo '</div>';
}
add_action('before_signup_form', 'loopis_theme_hq_signup_custom_header');

/**
 * Close the page-padding center wrapper opened before the signup form.
 */
function loopis_theme_hq_signup_close_page_padding() {
    echo '</div><!-- .page-padding center -->';
}
add_action('after_signup_form', 'loopis_theme_hq_signup_close_page_padding');

/**
 * Swap title block to verification stage once signup confirmation is shown.
 */
function loopis_theme_hq_signup_activation_confirmation() {
    if ('wp-signup.php' !== ($GLOBALS['pagenow'] ?? '')) {
        return;
    }

    $template_html = loopis_theme_hq_render_signup_template('loopis-verify-title.php');
    if ('' === $template_html) {
        return;
    }

    ?>
    <script type="text/javascript">
    (function() {
        var verifyTitleHtml = <?php echo wp_json_encode($template_html); ?>;

        function applyVerifyTitle() {
            var titleContainer = document.getElementById('loopis-signup-title');
            var defaultConfirmationHeading = document.querySelector('#signup-content h2:first-of-type');

            if (defaultConfirmationHeading) {
                // Keep core confirmation context visible below the custom step title.
                defaultConfirmationHeading.style.display = 'block';
            }

            if (!titleContainer) {
                return;
            }

            titleContainer.innerHTML = verifyTitleHtml;

            if (window.twemoji) {
                twemoji.parse(titleContainer, {
                    folder: 'svg',
                    ext: '.svg'
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyVerifyTitle);
            return;
        }

        applyVerifyTitle();
    }());
    </script>
    <?php
}
add_action('signup_finished', 'loopis_theme_hq_signup_activation_confirmation');
