<?php
/**
 * Customized multisite user signup on /wp-signup.php
 * 
 * Created by CoPilot, prompted by Johan, complemented by Hubert.
 */

// Load on both signup and activation screens so activation hooks are available.
$loopis_current_script = isset($_SERVER['SCRIPT_NAME']) ? (string) wp_unslash($_SERVER['SCRIPT_NAME']) : '';
$loopis_is_signup_request = (isset($GLOBALS['pagenow']) && in_array($GLOBALS['pagenow'], array('wp-signup.php', 'wp-activate.php'), true)) || false !== strpos($loopis_current_script, 'wp-signup.php') || false !== strpos($loopis_current_script, 'wp-activate.php');
if (!$loopis_is_signup_request) {
    return;
}

// Enqueue LOOPIS styles
function loopis_signup_enqueue_assets() {
    wp_enqueue_style('dashicons');

    wp_enqueue_style('loopis-theme-hq-style', LOOPIS_THEME_URI . '/assets/css/base.css', array(), filemtime(LOOPIS_THEME_DIR . '/assets/css/base.css'));
    wp_enqueue_style('loopis-theme-hq-forms', LOOPIS_THEME_URI . '/assets/css/forms.css', array('loopis-theme-hq-style'), filemtime(LOOPIS_THEME_DIR . '/assets/css/forms.css'));
    wp_enqueue_style('loopis-theme-hq-responsive', LOOPIS_THEME_URI . '/assets/css/responsive.css', array('loopis-theme-hq-style'), filemtime(LOOPIS_THEME_DIR . '/assets/css/responsive.css'));
    wp_enqueue_style('loopis-theme-hq-signup', LOOPIS_THEME_HQ_URI . '/assets/css/wp-signup.css', array('loopis-theme-hq-forms'), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/css/wp-signup.css'));
}
add_action('wp_enqueue_scripts', 'loopis_signup_enqueue_assets');

/**
 * Helper function to buffer custom content from templates/wp-signup.
 */
function loopis_signup_buffer_template($template_file, $vars = array()) {
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
 * Suppress the default "Get your own … account in seconds" heading on wp-signup.php.
 */
function loopis_signup_hide_default_heading() {
    ?>
    <style>
        #signup-content h2:first-of-type {
            display: none;
        }
    </style>
    <?php
}
add_action('signup_header', 'loopis_signup_hide_default_heading');


/**
 * Open LOOPIS page wrapper and output custom signup title block.
 */
function loopis_signup_render_header() {
    echo '<div class="page-padding center">';
    echo '<div id="loopis-signup-title">';
    $template_html = loopis_signup_buffer_template('loopis-signup-title.php');
    if ('' !== $template_html) {
        echo $template_html;
    }
    echo '</div>';
}
add_action('before_signup_form', 'loopis_signup_render_header');


/**
 * Close LOOPIS page wrapper.
 */
function loopis_signup_render_footer() {
    echo '<p class="info">PS. LOOPIS är en ideell förening för dig som vill ha en glad & hållbar framtid. Läs mer här:</p>';
    echo '<p><span class="link"><a href="' . esc_url(home_url('/faq/varfor-medlemskap/')) . '">📌 Varför medlemskap?</a></span>&nbsp; <span class="link"><a href="' . esc_url(home_url('/faq/loopis-stadgar/')) . '">📜 Stadgar</a></span>&nbsp; <span class="link"><a href="' . esc_url(home_url('/privacy/')) . '">🗄 Integritet</a></span></p>';
    echo '</div><!-- .page-padding center -->';
}
add_action('after_signup_form', 'loopis_signup_render_footer');


/**
 * Change to custom verification title block on signup confirmation.
 */
function loopis_signup_render_verification() {
    if ('wp-signup.php' !== ($GLOBALS['pagenow'] ?? '')) {
        return;
    }

    $template_html = loopis_signup_buffer_template('loopis-verify-title.php');
    if ( '' === $template_html ) {
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
add_action('signup_finished', 'loopis_signup_render_verification');

/**
 * Render first and last name fields on /wp-signup.php
 */
function loopis_signup_render_name_fields($errors) {
    $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
    $postcode = isset($_POST['wpum_postcode']) ? sanitize_text_field(wp_unslash($_POST['wpum_postcode'])) : '';
    $signup_password = isset($_POST['signup_password']) ? (string) wp_unslash($_POST['signup_password']) : '';
    $signup_password_confirm = isset($_POST['signup_password_confirm']) ? (string) wp_unslash($_POST['signup_password_confirm']) : '';
    $first_name_error = is_wp_error($errors) ? $errors->get_error_message('first_name') : '';
    $last_name_error = is_wp_error($errors) ? $errors->get_error_message('last_name') : '';
    $postcode_error = is_wp_error($errors) ? $errors->get_error_message('wpum_postcode') : '';
    ?>
    <p>
        <label for="first_name"><?php echo esc_html__('First name', 'loopis-theme-hq'); ?></label>
        <?php if ('' !== $first_name_error) : ?>
            <span class="error"><?php echo esc_html($first_name_error); ?></span>
        <?php endif; ?>
        <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($first_name); ?>" autocomplete="given-name" required />
    </p>
    <p>
        <label for="last_name"><?php echo esc_html__('Last name', 'loopis-theme-hq'); ?></label>
        <?php if ('' !== $last_name_error) : ?>
            <span class="error"><?php echo esc_html($last_name_error); ?></span>
        <?php endif; ?>
        <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($last_name); ?>" autocomplete="family-name" required />
    </p>
    <p>
        <label for="signup_password"><?php echo esc_html__('Password', 'loopis-theme-hq'); ?></label>
        <input type="text" name="signup_password" id="signup_password" value="<?php echo esc_attr($signup_password); ?>" autocomplete="new-password" required />
        <span class="description">Minst 8 tecken.</span>
    </p>
    <p>
        <label for="signup_password_confirm"><?php echo esc_html__('Confirm password', 'loopis-theme-hq'); ?></label>
        <input type="text" name="signup_password_confirm" id="signup_password_confirm" value="<?php echo esc_attr($signup_password_confirm); ?>" autocomplete="new-password" required />
    </p>
    <p>
        <label for="wpum_postcode"><?php esc_html_e('Postnummer', 'loopis-theme-hq'); ?></label>
        <?php if ('' !== $postcode_error) : ?>
            <span class="error"><?php echo esc_html($postcode_error); ?></span>
        <?php endif; ?>
        <input type="text" name="wpum_postcode" id="wpum_postcode" value="<?php echo esc_attr($postcode); ?>" autocomplete="postal-code" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" required />
        <span class="description">Ange ditt postnummer (fem siffror).</span>
    </p>
    <?php
}
add_action('signup_extra_fields', 'loopis_signup_render_name_fields');

/**
 * Hide the default username input and keep it auto-generated in the form.
 */
function loopis_signup_hide_username_input() {
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

        function initSignupFormUi() {
            moveNameFieldsToTop();
            normalizeEmailLabel();
            hideUsernameRow();
            wrapSignupErrors();
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
add_action('wp_head', 'loopis_signup_hide_username_input');

/**
 * Build a username from first name and last name.
 *
 * Format: firstname-lastname
 */
function loopis_signup_build_username($first_name, $last_name) {
    // Clean incoming values from unsafe characters.
    $first_name = sanitize_text_field($first_name);
    $last_name = sanitize_text_field($last_name);

    // Join names with a hyphen and normalize accented letters (e.g. å -> a).
    $raw = remove_accents($first_name . '-' . $last_name);

    // Replace any whitespace runs with a single hyphen.
    $raw = preg_replace('/\s+/', '-', $raw);

    // Collapse repeated hyphens to keep the slug clean.
    $raw = preg_replace('/-+/', '-', $raw);

    // Remove leading and trailing hyphens.
    $raw = trim($raw, '-');

    // Force lowercase for consistent usernames.
    $raw = strtolower($raw);
    
    // Apply WordPress username sanitization rules.
    return sanitize_user($raw, true);
}

/**
 * Check whether a generated signup username is already taken.
 *
 * Includes existing users and pending multisite signups.
 */
function loopis_signup_username_exists($username) {
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
 * Build a standardized username with numerical suffix if needed.
 *
 * Format: firstname-lastname (-2, -3, ... )
 */
function loopis_signup_generate_available_username($first_name, $last_name) {
    $base_username = loopis_signup_build_username($first_name, $last_name);
    if ('' === $base_username) {
        return '';
    }

    if (!loopis_signup_username_exists($base_username)) {
        return $base_username;
    }

    $suffix = 2;
    while ($suffix <= 9999) {
        $candidate = sanitize_user($base_username . '-' . $suffix, true);
        if ('' !== $candidate && !loopis_signup_username_exists($candidate)) {
            return $candidate;
        }
        $suffix++;
    }

    return '';
}

/**
 * Add username-specific validation errors for generated signup usernames.
 */
function loopis_signup_validate_username($errors, $generated_username) {
    // Allow lowercase letters and numbers separated by single hyphens.
    if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $generated_username)) {
        $errors->add('user_name', __('This username is invalid. Please use only lowercase letters, numbers, and hyphens.', 'loopis-theme-hq'));
    }

    if (strlen($generated_username) < 4) {
        $errors->add('user_name', __('Username must be at least 4 characters long after generation.', 'loopis-theme-hq'));
    }

    $illegal_logins = (array) apply_filters('illegal_user_logins', array());
    if (in_array($generated_username, $illegal_logins, true)) {
        $errors->add('user_name', __('Sorry, that username is not allowed.', 'loopis-theme-hq'));
    }
}

/**
 * Normalize person names for storage in user meta.
 *
 * Example: "anna" => "Anna", "karl-erik" => "Karl-Erik"
 */
function loopis_signup_normalize_name($name) {
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
function loopis_signup_normalize_postcode($postcode) {
    return preg_replace('/\D+/', '', (string) $postcode);
}

/**
 * Keep user_name in sync on submit so core multisite validation runs on generated value.
/**
 * Generate and populate username from first and last names during signup.
 */
function loopis_signup_prepare_username() {
    if ('POST' !== strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET')) {
        return;
    }

    $first_name = isset($_POST['first_name']) ? wp_unslash($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? wp_unslash($_POST['last_name']) : '';

    $generated_username = loopis_signup_generate_available_username($first_name, $last_name);
    if ('' !== $generated_username) {
        $_POST['user_name'] = $generated_username;
    }
}
add_action('init', 'loopis_signup_prepare_username', 0);

/**
 * Validate generated username and required first/last names server-side.
 */
function loopis_signup_validate_fields($result) {
    $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
    $postcode = isset($_POST['wpum_postcode']) ? loopis_signup_normalize_postcode(wp_unslash($_POST['wpum_postcode'])) : '';
    $signup_password = isset($_POST['signup_password']) ? (string) wp_unslash($_POST['signup_password']) : '';
    $signup_password_confirm = isset($_POST['signup_password_confirm']) ? (string) wp_unslash($_POST['signup_password_confirm']) : '';

    if ('' === $first_name) {
        $result['errors']->add('first_name', __('Please enter your first name.', 'loopis-theme-hq'));
    }

    if ('' === $last_name) {
        $result['errors']->add('last_name', __('Please enter your last name.', 'loopis-theme-hq'));
    }

    if (!preg_match('/^[0-9]{5}$/', $postcode)) {
        $result['errors']->add('wpum_postcode', __('Please enter a five-digit postcode.', 'loopis-theme-hq'));
    }

    if ('' === $signup_password) {
        $result['errors']->add('generic', __('Please enter a password.', 'loopis-theme-hq'));
    } elseif (strlen($signup_password) < 8) {
        $result['errors']->add('generic', __('Password must be at least 8 characters long.', 'loopis-theme-hq'));
    }

    if ($signup_password !== $signup_password_confirm) {
        $result['errors']->add('generic', __('Password confirmation does not match.', 'loopis-theme-hq'));
    }

    $generated_username = loopis_signup_generate_available_username($first_name, $last_name);
    if ('' === $generated_username) {
        $result['errors']->remove('user_name');
        if ('' !== $first_name && '' !== $last_name) {
            $result['errors']->add('generic', __('Please provide a valid first and last name to generate a username.', 'loopis-theme-hq'));
        }
        return $result;
    }

    // Replace core username errors so our generated pattern can include hyphens.
    $result['errors']->remove('user_name');

    $result['user_name'] = $generated_username;
    loopis_signup_validate_username($result['errors'], $generated_username);

    return $result;
}
add_filter('wpmu_validate_user_signup', 'loopis_signup_validate_fields');


/**
 * Persist signup form data into meta for activation processing.
 */
function loopis_signup_prepare_meta($meta) {
    $first_name = isset($_POST['first_name']) ? loopis_signup_normalize_name(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? loopis_signup_normalize_name(wp_unslash($_POST['last_name'])) : '';
    $postcode = isset($_POST['wpum_postcode']) ? loopis_signup_normalize_postcode(wp_unslash($_POST['wpum_postcode'])) : '';
    $signup_password = isset($_POST['signup_password']) ? (string) wp_unslash($_POST['signup_password']) : '';

    if ('' !== $first_name) {
        $meta['first_name'] = $first_name;
    }

    if ('' !== $last_name) {
        $meta['last_name'] = $last_name;
    }

    if (preg_match('/^[0-9]{5}$/', $postcode)) {
        $meta['wpum_postcode'] = $postcode;
    }

    $encrypted_password = loopis_signup_encrypt_password($signup_password);
    if ('' !== $encrypted_password) {
        $meta['loopis_signup_password_enc'] = $encrypted_password;
    }

    return $meta;
}
add_filter('signup_user_meta', 'loopis_signup_prepare_meta');

/**
 * Disable default multisite verification email
 */
add_filter('wpmu_signup_user_notification', '__return_false');

/**
 * Send customized multisite verification email
 */
function loopis_theme_hq_send_custom_signup_verification_email($user_login, $user_email, $key, $meta) {
    loopis_get_mail();

    // Get user data
    $activation_url = esc_url(site_url('wp-activate.php?key=' . rawurlencode((string) $key)));
    $first_name = is_array($meta) && !empty($meta['first_name']) ? sanitize_text_field((string) $meta['first_name']) : '';

    // Build mail
    $message = loopis_signup_activation_mail($first_name, $activation_url);
    $message .= loopis_mail_footer();
    $subject = '🔔 Verifiera ditt konto på LOOPIS';

    // Send mail
    wp_mail($user_email, wp_specialchars_decode($subject), $message, loopis_mail_headers());
}
add_action('after_signup_user', 'loopis_signup_send_verification_email', 20, 4);


/**
 * Encrypt signup password for temporary storage in signup meta.
 */
function loopis_signup_encrypt_password($password) {
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
function loopis_signup_decrypt_password($encrypted_password) {
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
 * Disable default welcome mail when a chosen signup password exists.
 */
function loopis_signup_disable_welcome_notification($user_id, $password, $meta) {
    if (is_array($meta) && !empty($meta['loopis_signup_password_enc'])) {
        return false;
    }

    return $user_id;
}
add_filter('wpmu_welcome_user_notification', 'loopis_signup_disable_welcome_notification', 10, 3);