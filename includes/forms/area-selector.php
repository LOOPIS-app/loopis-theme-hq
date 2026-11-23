<?php
/**
 * Area selection form
 *
 * Provides area selection for logged-in users without an area.
 *
 * @package LOOPIS_Theme
 * @subpackage Forms
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Handle area selector form submission via AJAX.
 */
function loopis_handle_area_selector_submission_ajax() {
    error_log('LOOPIS AJAX: Request received');
    error_log('LOOPIS AJAX: POST data: ' . print_r($_POST, true));

    // Check nonce
    if ( !isset($_POST['nonce']) ) {
        error_log('LOOPIS AJAX: Nonce missing');
        wp_send_json_error( 'Nonce missing' );
    }

    $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
    error_log('LOOPIS AJAX: Nonce value: ' . $nonce);

    if ( !wp_verify_nonce( $nonce, 'loopis_area_selector_nonce' ) ) {
        error_log('LOOPIS AJAX: Nonce verification failed');
        wp_send_json_error( 'Nonce verification failed' );
    }

    error_log('LOOPIS AJAX: Nonce verified');

    if ( ! isset( $_POST['blog_id'] ) ) {
        error_log('LOOPIS AJAX: blog_id missing');
        wp_send_json_error( 'Missing blog_id' );
    }

    $user_id = get_current_user_id();
    if ( $user_id <= 0 ) {
        error_log('LOOPIS AJAX: User not logged in');
        wp_send_json_error( 'Not logged in' );
    }

    $blog_id = absint( wp_unslash( $_POST['blog_id'] ) );
    error_log('LOOPIS AJAX: User ' . $user_id . ' attempting to join blog ' . $blog_id);

    if ( ! loopis_is_valid_signup_location( $blog_id ) ) {
        error_log('LOOPIS AJAX: Invalid blog_id ' . $blog_id);
        wp_send_json_error( 'Invalid blog_id' );
    }

    error_log('LOOPIS AJAX: Calling area_access_setup');
    require_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/area-access-setup.php';
    area_access_setup( $user_id, $blog_id );

    error_log('LOOPIS AJAX: Success');
    wp_send_json_success( array( 'message' => 'Area selected successfully' ) );
}
add_action( 'wp_ajax_loopis_select_area', 'loopis_handle_area_selector_submission_ajax' );

/**
 * Return private subsites (areas).
 */
function loopis_get_private_sites() {
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
function loopis_get_signup_locations() {
    if ( ! is_multisite() ) {
        return array();
    }
    $not_in = loopis_get_private_sites();
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
function loopis_is_valid_signup_location( $blog_id ) {
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
 * Render area selector form.
 *
 * Shows public areas available for logged-in users to select.
 * This form handles manual area selection only - private area access via cookies
 * is handled separately in area-access-setup-private.php
 *
 * @param WP_Error|null $errors Optional errors object for displaying validation errors.
 */
function loopis_render_area_selector_form( $errors = null ) {
    $selected_blog_id = isset( $_POST['loopis_location_blog_id'] ) ? absint( wp_unslash( $_POST['loopis_location_blog_id'] ) ) : 0;
    $location_error = ( $errors && is_wp_error( $errors ) ) ? $errors->get_error_message('loopis_location_blog_id') : '';

    $locations = loopis_get_signup_locations();
    ?>

    <div class="loopis-form-wrapper">
        <form class="loopis-form" method="POST" id="loopis-area-selector-form">
            <p class="red">⚠️ Du ska kunna hämta och lämna saker inom 1-3 dygn. Du bör därför bo eller arbeta i eller nära området.</p>
                <label for="loopis_location_blog_id">
                    <?php esc_html_e( 'Dags att välja område?', 'loopis-theme-hq' ); ?>
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
                        <?php esc_html_e( 'Välj område', 'loopis-theme-hq' ); ?>
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
                </select>
            
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
