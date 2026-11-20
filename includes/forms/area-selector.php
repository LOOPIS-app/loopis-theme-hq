<?php
/**
 * Area/Location Selection Form
 *
 * Provides area selection functionality for both signup and logged-in users.
 *
 * @package LOOPIS_Theme
 * @subpackage Forms
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Handle area selector form submission.
 */
function loopis_theme_hq_handle_area_selector_submission() {
    if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) ) {
        return;
    }

    if ( ! isset( $_POST['loopis_area_selector_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['loopis_area_selector_nonce'] ) ), 'loopis_area_selector_nonce' ) ) {
        wp_die( 'Security check failed.' );
    }

    if ( ! isset( $_POST['loopis_location_blog_id'] ) ) {
        return;
    }

    $user_id = get_current_user_id();
    if ( $user_id <= 0 ) {
        return;
    }

    $blog_id = absint( wp_unslash( $_POST['loopis_location_blog_id'] ) );

    if ( ! loopis_theme_hq_is_valid_signup_location( $blog_id ) ) {
        return;
    }

    require_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/area-access-setup.php';
    area_access_setup( $user_id, $blog_id );

    wp_safe_remote_post( wp_get_referer() );
    wp_safe_redirect( wp_get_referer() );
    exit;
}
add_action( 'init', 'loopis_theme_hq_handle_area_selector_submission' );

/**
 * Return private subsites (areas).
 */
function loopis_theme_hq_get_private_sites() {
    global $wpdb;

    $table = $wpdb->base_prefix . 'loopis_areas';

    $site_ids = $wpdb->get_col(
        "SELECT DISTINCT blog_id
         FROM {$table}
         WHERE privacy = 1"
    );

    return array_map('absint', $site_ids ?: array());
}

/**
 * Return selectable subsites (areas).
 */
function loopis_theme_hq_get_signup_locations() {
    if ( ! is_multisite() ) {
        return array();
    }
    $not_in = loopis_theme_hq_get_private_sites();
    $not_in[] = get_main_site_id();
    return get_sites(
        array(
            'network_id' => get_current_network_id(),
            'public'     => 1,
            'archived'   => 0,
            'spam'       => 0,
            'deleted'    => 0,
            'site__not_in' => $not_in,
            'number'     => 100,
            'orderby'    => 'domain',
            'order'      => 'ASC',
        )
    );
}

/**
 * Check whether a subsite is an allowed signup area.
 */
function loopis_theme_hq_is_valid_signup_location( $blog_id ) {
    $blog_id = absint( $blog_id );

    if ( $blog_id <= 0 || ! is_multisite() ) {
        return false;
    }

    $site = get_site( $blog_id );

    if ( ! $site ) {
        return false;
    }

    if ( (int) $site->network_id !== (int) get_current_network_id() ) {
        return false;
    }

    if ( $site->archived || $site->spam || $site->deleted ) {
        return false;
    }

    return true;
}

/**
 * Check whether the special invite cookie is set.
 */
function loopis_theme_hq_has_special_location_cookie() {
    return isset( $_COOKIE['special_invite_payload'] );
}

/**
 * Get location safely from cookie or POST data.
 */
function loopis_theme_hq_get_location_safe() {
    if ( loopis_theme_hq_has_special_location_cookie() ) {
        $encoded_blog_id = wp_unslash(
            $_COOKIE['special_invite_payload']
        );

        $decoded_blog_id = base64_decode( $encoded_blog_id, true );

        if (
            false === $decoded_blog_id ||
            ! ctype_digit( (string) $decoded_blog_id )
        ) {
            return 0;
        }

        return absint( $decoded_blog_id );
    }else{
        return isset( $_POST['loopis_location_blog_id'] )
        ? absint( wp_unslash( $_POST['loopis_location_blog_id'] ) )
        : 0;
    }
}

/**
 * Render area selector form.
 *
 * @param WP_Error|null $errors Optional errors object for displaying validation errors.
 * @param bool          $skip_if_cookie If true, skip rendering if special invite cookie is set. Default true.
 */
function loopis_theme_hq_render_area_selector_form( $errors = null, $skip_if_cookie = true ) {
    if ( $skip_if_cookie && loopis_theme_hq_has_special_location_cookie() ) {
        return;
    }

    $selected_blog_id = loopis_theme_hq_get_location_safe();
    $location_error = ( $errors && is_wp_error( $errors ) ) ? $errors->get_error_message('loopis_location_blog_id') : '';

    $locations = loopis_theme_hq_get_signup_locations();
    ?>

    <div class="loopis-form-wrapper">
        <form class="loopis-form" method="POST" id="loopis-area-selector-form">
            <p>
                <label for="loopis_location_blog_id">
                    <?php esc_html_e( 'Område', 'loopis-theme-hq' ); ?>
                </label>
                <?php if ('' !== $location_error) : ?>
                    <span class="error"><?php echo esc_html($location_error); ?></span>
                <?php endif; ?>

                <select
                    name="loopis_location_blog_id"
                    id="loopis_location_blog_id"
                    required
                >
                    <option value="">
                        <?php esc_html_e( 'Välj ditt område', 'loopis-theme-hq' ); ?>
                    </option>

                    <?php foreach ( $locations as $location ) : ?>
                        <?php
                        $blog_id  = (int) $location->blog_id;
                        $blogname = get_blog_option( $blog_id, 'blogname' );
                        ?>

                        <option
                            value="<?php echo esc_attr( $blog_id ); ?>"
                            <?php selected( $selected_blog_id, $blog_id ); ?>
                        >
                            <?php echo esc_html( $blogname ); ?>
                        </option>
                    <?php endforeach; ?>

                    <option value="1">
                        <?php esc_html_e( 'Stödmedlemskap/annat område', 'loopis-theme-hq' ); ?>
                    </option>
                </select>
            </p>
            <?php wp_nonce_field( 'loopis_area_selector_nonce', 'loopis_area_selector_nonce' ); ?>
            <button type="submit" class="loopis-button" id="loopis-area-submit" style="display: <?php echo $selected_blog_id > 0 ? 'inline-block' : 'none'; ?>;">
                <?php esc_html_e( 'Välj område', 'loopis-theme-hq' ); ?>
            </button>
        </form>
    </div>

    <script>
    (function() {
        const selectField = document.getElementById('loopis_location_blog_id');
        const submitButton = document.getElementById('loopis-area-submit');

        if (!selectField || !submitButton) {
            return;
        }

        function toggleSubmitButton() {
            if (selectField.value && selectField.value !== '') {
                submitButton.style.display = 'inline-block';
            } else {
                submitButton.style.display = 'none';
            }
        }

        selectField.addEventListener('change', toggleSubmitButton);
    }());
    </script>

    <?php
}
