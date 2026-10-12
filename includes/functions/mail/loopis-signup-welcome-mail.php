<?php
/**
 * Signup welcome mail content.
 *
 * @param string $first_name Recipient first name.
 * @param string $username Activated username.
 * @return string HTML mail body.
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_signup_welcome_mail(string $first_name = '', string $username = ''): string {
    if(!function_exists('loopis_mail_template')){
        include_once  LOOPIS_THEME_HQ_DIR . '/includes/functions/mail/loopis-mail-template.php';
    }
    $first_name = sanitize_text_field($first_name);
    $username = sanitize_user($username, true);

    $greeting = '' !== $first_name ? 'Hej ' . esc_html($first_name) : 'Hej';
    $ingress = '🎉 Ditt konto på LOOPIS.app har nu aktiverats och du kan logga in.';
    $content = '→ Logga in med din vanliga webbläsare';
    $outro = '<a href="https://loopis.app/faq/tips-till-ny-medlem/">📌 Tips till ny medlem</a>';

    return  loopis_mail_template($greeting, $outro, $content, $ingress);
}
