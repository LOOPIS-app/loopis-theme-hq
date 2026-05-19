<?php
/**
 * Error message for 404 page.
 * 
 * This template is used when a user tries to access a page that doesn't exist.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the full requested URL for display
$scheme = is_ssl() ? 'https://' : 'http://';
$host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
$full_request_url = $scheme . $host . $request_uri;
?>
        <p>Den här sidan finns inte: <span class="link">🔗 <?php echo esc_url($full_request_url); ?></span></p>
		
		<?php include LOOPIS_THEME_HQ_DIR . '/templates/links/go-back.php'; ?>