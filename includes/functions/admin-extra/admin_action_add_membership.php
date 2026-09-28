<?php
/**
 * Manual activation of new account (after confirming Swish payment).
 * 
 * Setting username, display name, role, adding payment and sending welcome email.
 *
 * Included in activation.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

include_once  LOOPIS_THEME_HQ_DIR . '/includes/functions/mail/loopis-mail-footer.php';
include_once  LOOPIS_THEME_HQ_DIR . '/includes/functions/mail/loopis-mail-template.php';
include_once  LOOPIS_THEME_HQ_DIR . '/includes/functions/mail/loopis-mail-headers.php';

function admin_action_add_membership(int $user_id) {
    if ($user_id === 0) {
        wp_die('Invalid user ID.');
    }

    // Get user data
    $user = get_userdata($user_id);
    if (!$user) {
        wp_die('User not found.');
    }

    // Add payment
    add_membership($user_id,['description'=>'swish']);

    // Update the user's role, nicename, and display_name 
    $updated_user = wp_update_user([
        'ID' => $user_id,
        'role' => 'member',
    ]);

    // Define email constants
    $user_first_name = $user->first_name;
    $subject = '💚 Välkommen!';
    $greeting = "Hej {$user_first_name}!";
    $message = '🎉 Ditt Medlemskap på LOOPIS.app är nu aktiverat.<br>→  Logga in med din vanliga webbläsare.<br>';
    $outro = '<a href="/faq/tips-till-ny-medlem/">📌 Tips till ny medlem!</a>;';

    // Get templates
    $headers = loopis_mail_headers();
    $footer = loopis_mail_footer('Information från <a href="/">LOOPIS.app</a> <br> angående ditt användarkonto.');
    $email_content = loopis_mail_template($greeting, $outro, $message) . $footer;

    // Send the activation email
    $to = $user->user_email;
    wp_mail($to, $subject, $email_content, $headers);

    error_log("LOOPIS: add_membership success using Swish: {$new_username} (ID {$user_id})");

	// Refresh page
	refresh_page();
}