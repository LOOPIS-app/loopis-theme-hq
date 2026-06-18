<?php
/**
 * Signup welcome mail content.
 *
 * @param string $first_name Recipient first name.
 * @param string $username Activated username.
 * @param string $password Chosen password.
 * @return string HTML mail body.
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_signup_welcome_mail(string $first_name = '', string $username = '', string $password = ''): string {
    $first_name = sanitize_text_field($first_name);
    $username = sanitize_user($username, true);
    $password = sanitize_text_field($password);

    $greeting = '' !== $first_name ? 'Hej ' . esc_html($first_name) : 'Hej';

    return '<h3>' . $greeting . '</h3>'
        . '<p>🎉 Ditt konto på LOOPIS.app har nu aktiverats och du kan logga in.</p>'
        . '<p style="padding: 10px;font-size: 18px;font-style: italic;background: #f5f5f5;border-radius: 10px">'
        . 'Användarnamn: <strong>' . esc_html($username) . '</strong><br>'
        . 'Lösenord: <strong>' . esc_html($password) . '</strong>'
        . '</p>'
        . '<p>→ Logga in med din vanliga webbläsare.</p>';
}
