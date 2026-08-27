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

    // Get the email templates from the options
    $subject = loopis_get_setting('welcome_email_subject', 'Content missing...');
    $greeting = loopis_get_setting('welcome_email_greeting', 'Content missing...');
    $message = loopis_get_setting('welcome_email_message', 'Content missing...');
    $footer = loopis_get_setting('welcome_email_footer', 'Content missing...');

    $email_content = <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
    <title>{$subject}</title>
    </head>
    <body>
    <div style="text-align: center;">
    <h1 style="font-size: 24px;">{$greeting}</h1>
    </div>
    <div style="padding: 10px;margin-bottom: 20px;text-align: center; font-size: 18px;background: #f5f5f5;border-radius: 10px">
    {$message}
    </div>
    {$footer}
    </body>
    </html>
    EOT;

    // Replace [user_first_name] with the actual first name
    $email_content = str_replace('[user_first_name]', $user->first_name, $email_content);

    // Send the activation email
    $to = $user->user_email;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $email_content, $headers);

    error_log("LOOPIS: add_membership success using Swish: {$new_username} (ID {$user_id})");

	// Refresh page
	refresh_page();
}