<?php
/**
 * User page member form.
 * 
 * Dynamic content of page-user.php
 * Reached on /user/?option=member-form
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current user iD
$user_id = get_current_user_id();
$user = wp_get_current_user();

// Include member form
include LOOPIS_THEME_HQ_DIR . '/templates/forms/member-form.php'; ?>

