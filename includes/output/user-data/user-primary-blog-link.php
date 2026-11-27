<?php
/**
 * Output user primary blog - with link.
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get primary blog
$primary_blog_id = get_user_meta($user_id, 'primary_blog', true);
$primary_blog_name = $primary_blog_id ? get_blog_details($primary_blog_id) : null;
$primary_blog_url = $primary_blog_id ? get_home_url($primary_blog_id) : null;

// Output
if ( empty($primary_blog_id) ) {
    echo esc_html('Ej valt');
} elseif ( $primary_blog_id == 1 ) {
    echo esc_html('Väntar...');
} else {
    echo '<a href="' . esc_url($primary_blog_url) . '">' . esc_html($primary_blog_name->blogname) . '</a>';
}