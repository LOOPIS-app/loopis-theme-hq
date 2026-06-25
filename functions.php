<?php
/**
 * Theme bootstrap for LOOPIS HQ (main site)
 *
 * Loads all frontend core files.
 */

// Prevent direct access
if (!defined('ABSPATH')) { exit; }

// Maintenance?
if (defined('LOOPIS_MAINTENANCE') && LOOPIS_MAINTENANCE) { require_once __DIR__ . '/includes/maintenance/maintenance.php'; }

// Define theme version
define('LOOPIS_THEME_HQ_VERSION', '1.02'); // Update version number here + in style.css

// Theme folder constants are provided by MU plugin "LOOPIS Constants".

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
    // Enqueue shared CSS from "LOOPIS Theme".
    wp_enqueue_style( 'loopis-theme-hq-style', LOOPIS_THEME_URI . '/assets/css/base.css', array(), filemtime( LOOPIS_THEME_DIR . '/assets/css/base.css' ) );
    wp_enqueue_style( 'loopis-theme-hq-forms', LOOPIS_THEME_URI . '/assets/css/forms.css', array( 'loopis-theme-hq-style' ), filemtime( LOOPIS_THEME_DIR . '/assets/css/forms.css' ) );
    wp_enqueue_style( 'loopis-theme-hq-responsive', LOOPIS_THEME_URI . '/assets/css/responsive.css', array(), filemtime( LOOPIS_THEME_DIR . '/assets/css/responsive.css' ) );
    
    // Enqueue extra CSS for "LOOPIS Theme HQ".
    wp_enqueue_style( 'loopis-theme-hq-extra', LOOPIS_THEME_HQ_URI . '/assets/css/extra.css', array(), filemtime( LOOPIS_THEME_HQ_DIR . '/assets/css/extra.css' ) );

    // Enqueue jQuery (default Wordpress version) + theme scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('loopis-theme-hq-scripts', LOOPIS_THEME_URI . '/assets/js/general.js', array('jquery'), filemtime(LOOPIS_THEME_DIR . '/assets/js/general.js'), true);

    // Enqueue CSS styles and JS for admin
    if (current_user_can('manage_options') || current_user_can('loopis_admin')) {
        wp_enqueue_style('loopis-theme-hq-admin', LOOPIS_THEME_URI . '/assets/css/admin.css', array(), filemtime( LOOPIS_THEME_DIR . '/assets/css/admin.css' )); 
        wp_enqueue_script('loopis-theme-hq-admin', LOOPIS_THEME_URI . '/assets/js/admin.js', array('jquery'), filemtime(LOOPIS_THEME_DIR . '/assets/js/admin.js'), true);
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

// Define folders to load (shared from LOOPIS Theme)
function loopis_theme_hq_load_files() {
    // For everyone
    loopis_theme_hq_include_folder('filters', LOOPIS_THEME_DIR);
    loopis_theme_hq_include_folder('functions/everyone', LOOPIS_THEME_DIR);
    // HQ-only additions
    loopis_theme_hq_include_folder('filters', LOOPIS_THEME_HQ_DIR);
    loopis_theme_hq_include_folder('functions/payment', LOOPIS_THEME_HQ_DIR);

    if (is_user_logged_in()) { 
        // For user
        loopis_theme_hq_include_folder('functions/user', LOOPIS_THEME_DIR);
    } else {
        // For visitor
        loopis_theme_hq_include_folder('functions/visitor', LOOPIS_THEME_DIR);
    }
}
add_action('after_setup_theme', 'loopis_theme_hq_load_files');