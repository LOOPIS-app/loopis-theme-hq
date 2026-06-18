<?php
/**
 * Password reset mail content.
 *
 * @param string $first_name Recipient first name.
 * @param string $reset_url Reset password URL.
 * @return string HTML mail body.
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_password_reset_mail(string $first_name = '', string $reset_url = ''): string {
    $first_name = sanitize_text_field($first_name);
    $reset_url = esc_url($reset_url);

    $greeting = '' !== $first_name ? 'Hej ' . esc_html($first_name) : 'Hej';

    return '<h3>' . $greeting . '</h3>'
        . '<p>Vi fick en begäran om att återställa ditt lösenord.</p>'
        . '<p style="padding: 10px;font-size: 18px;font-style: italic;background: #f5f5f5;border-radius: 10px">'
        . 'Tryck på länken för att välja ett nytt lösenord:<br>'
        . '<a href="' . $reset_url . '">' . esc_html($reset_url) . '</a>'
        . '</p>'
        . '<p>Om du inte begärde detta kan du ignorera mailet.</p>';
}
