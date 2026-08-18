<?php
/**
 * Manual addition of coins (after confirming Swish payment).
 * 
 * Adding payment and sending confirmation email.
 * 
 * Included in coins.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function admin_action_add_coins(int $user_id) {
    // add the coins! 
    add_coins($user_id);
    // Get user data
    $user = get_userdata($user_id);
    if (!$user) {
        error_log("LOOPIS: add_coins failed, user not found (ID {$user_id})");
        return;
    }

    // Set email content
    $user_first_name = $user->first_name;
    $subject = "✅ Köp av 5 mynt";
    $greeting = "Hej {$user_first_name}!";
    $message = "Vi har nu lagt till 5 regnbågsmynt på ditt konto. Tack för att du loopar! 💚";
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

    // Send the confirmation email
    $to = $user->user_email;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $email_content, $headers);

    error_log("LOOPIS: add_coins success using Swish: {$user->user_login} (ID {$user_id})");
}
