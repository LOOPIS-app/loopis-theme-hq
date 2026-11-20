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
loopis_get_mail();

function admin_action_add_coins(int $user_id) {
    // add the coins! 
    add_coins($user_id,['description'=>'swish']);
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

    // Get templates
    $headers = loopis_mail_headers();
    $footer = loopis_mail_footer('Information från <a href="/">LOOPIS.app</a> <br> angående ditt användarkonto.');
    $email_content = loopis_mail_template($greeting,'', $message) . $footer;

    // Send the confirmation email
    $to = $user->user_email;
    wp_mail($to, $subject, $email_content, $headers);

    error_log("LOOPIS: add_coins success using Swish: {$user->user_login} (ID {$user_id})");
}
