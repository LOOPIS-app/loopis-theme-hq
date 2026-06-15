<?php
/**
 * Theme bootstrap for LOOPIS HQ (main site)
 *
 * Loads all frontend core files.
 */

// Prevent direct access
if (!defined('ABSPATH')) { exit; }

// Maintenance?
if (defined('LOOPIS_MAINTENANCE') && LOOPIS_MAINTENANCE) { require_once __DIR__ . '/includes/maintenance/bootstrap.php'; }

// Define theme version
define('LOOPIS_THEME_HQ_VERSION', '0.06'); // Update version number here + in style.css

// Theme folder constants are provided by MU plugin: LOOPIS Constants.

/**
 * Load theme translations.
 */
function loopis_theme_hq_load_textdomain() {
    load_theme_textdomain('loopis-theme-hq', LOOPIS_THEME_HQ_DIR . '/languages');
}
add_action('after_setup_theme', 'loopis_theme_hq_load_textdomain', 0);

/** 
 * Enqueue theme CSS and JavaScript
 */

function loopis_theme_hq_assets() {
    // Enqueue shared CSS from "LOOPIS Theme" (single source of truth).
    $shared_theme_dir  = LOOPIS_THEME_DIR;
    $shared_theme_uri  = LOOPIS_THEME_URI;

    $shared_style_path      = $shared_theme_dir . '/assets/css/base.css';
    $shared_forms_path      = $shared_theme_dir . '/assets/css/forms.css';
    $shared_responsive_path = $shared_theme_dir . '/assets/css/responsive.css';
    $local_extra_path       = LOOPIS_THEME_HQ_DIR . '/assets/css/extra.css';

    wp_enqueue_style( 'loopis-theme-hq-style', $shared_theme_uri . '/assets/css/base.css', array(), filemtime( $shared_style_path ) );
    wp_enqueue_style( 'loopis-theme-hq-forms', $shared_theme_uri . '/assets/css/forms.css', array( 'loopis-theme-hq-style' ), filemtime( $shared_forms_path ) );
    wp_enqueue_style( 'loopis-theme-hq-responsive', $shared_theme_uri . '/assets/css/responsive.css', array(), filemtime( $shared_responsive_path ) );
    wp_enqueue_style( 'loopis-theme-hq-extra', LOOPIS_THEME_HQ_URI . '/assets/css/extra.css', array( 'loopis-theme-hq-style', 'loopis-theme-hq-forms', 'loopis-theme-hq-responsive' ), filemtime( $local_extra_path ) );
    
    // Enqueue jQuery (default Wordpress version) + theme scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('loopis-theme-hq-scripts', LOOPIS_THEME_HQ_URI . '/assets/js/general.js', array('jquery'), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/js/general.js'), true);

    // Enqueue CSS styles and JS for admin
    if (current_user_can('manage_options') || current_user_can('loopis_admin')) {
        $shared_admin_path = $shared_theme_dir . '/assets/css/admin.css';
        wp_enqueue_style('loopis-theme-hq-admin', $shared_theme_uri . '/assets/css/admin.css', array(), filemtime( $shared_admin_path )); 
        wp_enqueue_script('loopis-theme-hq-admin', LOOPIS_THEME_HQ_URI . '/assets/js/admin.js', array('jquery'), filemtime(LOOPIS_THEME_HQ_DIR . '/assets/js/admin.js'), true);
    }
}
add_action('wp_enqueue_scripts', 'loopis_theme_hq_assets');

/**
 * Include PHP files
 */

// Utility function to include all PHP files in a folder.
function loopis_theme_hq_include_folder($folder_name, $base_theme_dir = LOOPIS_THEME_HQ_DIR) {
    $absolute_path = $base_theme_dir . '/includes/' . $folder_name;
    if (is_dir($absolute_path)) {
        foreach (glob($absolute_path . '/*.php') as $file) {
            include_once $file;
        }
    } else {
        loopis_log_level1("LOOPIS Theme HQ failed to include folder: {$folder_name} ({$base_theme_dir})");
    }
}
// Define folders to load
function loopis_theme_hq_load_files() {
    // Shared from LOOPIS Theme
    loopis_theme_hq_include_folder('filters', LOOPIS_THEME_DIR);
    loopis_theme_hq_include_folder('functions/everyone', LOOPIS_THEME_DIR);
    loopis_theme_hq_include_folder('functions/payment', LOOPIS_THEME_DIR);

    // HQ-only additions
    loopis_theme_hq_include_folder('filters', LOOPIS_THEME_HQ_DIR);

    if (is_user_logged_in()) {
        loopis_theme_hq_include_folder('functions/user', LOOPIS_THEME_HQ_DIR);
    }
}
add_action('after_setup_theme', 'loopis_theme_hq_load_files');