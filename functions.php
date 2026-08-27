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
define('LOOPIS_THEME_HQ_VERSION', '1.04'); // Update version number here + in style.css

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


add_action('init', function () {
    update_option('special_invite_hash', hash('sha256', 'code'));
});

add_action('init', function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') return;

    $path = wp_parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($path !== '/special-signup/') return;

    $code = isset($_GET['spt']) ? (string) $_GET['spt'] : '';
    $code = preg_replace('/[^A-Za-z0-9_-]/', '', $code);
    if ($code === '') wp_die('Invalid code/URL.');

    $expected_hash = get_option('special_invite_hash', '');
    if (!$expected_hash) wp_die('No invite set for right now.');

    if (!hash_equals($expected_hash, hash('sha256', $code))) {
      wp_die('Invalid invite.');
    }
    $blog_id = 4;
    $role_slug = 'member';
    $already_a_member = false;
    if(is_user_logged_in()){
        $user_id = get_current_user();

        $payments = loopis_ledger_user_payments($user_id);
        foreach($payments as $entry){
            if($entry['type'] === 'medlemskap'){
                $already_a_member = true;
                break;
            }
        }
        
        if(!$already_a_member){
            add_membership($user_id,['description'=>'platform24']);
        }

        switch_to_blog($blog_id);
        add_user_to_blog($blog_id, $user_id, $role_slug);
        restore_current_blog();
        wp_safe_redirect(home_url('/p24/'));
        exit;
    }

    $payload = $blog_id .'|'.$role_slug; // placeholder currently blog + role

    setcookie(
        'special_invite_payload',
        base64_encode($payload),
        [
            'expires'  => time() + 60 * 60 * 24,
            'path'     => '/',
            'secure'   => is_ssl(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]
    );

    wp_safe_redirect(network_site_url('wp-signup.php'));
    exit;
});

add_action('user_register', function ($user_id) {
  if (empty($_COOKIE['special_invite_payload'])) return;

  $decoded = base64_decode($_COOKIE['special_invite_payload'], true);
  if (!$decoded) return;

  $parts = explode('|', $decoded, 2);
  if (count($parts) !== 2) return;

  $blog_id = (int) $parts[0];
  $role_slug = sanitize_key($parts[1]) ?: 'member';

  setcookie('special_invite_payload', '', time() - 3600, '/', '', is_ssl(), true);

  if (!is_multisite() || $blog_id <= 0) return;

  add_membership($user_id,'platform24');

  switch_to_blog($blog_id);
  add_user_to_blog($blog_id, $user_id, $role_slug);
  restore_current_blog();
  setcookie(
    'skip_pay_screen',
    base64_encode(1),
    [
      'expires'  => time() + 60 * 60,
      'path'     => '/',
      'secure'   => is_ssl(),
      'httponly' => true,
      'samesite' => 'Lax',
    ]
  );

});
