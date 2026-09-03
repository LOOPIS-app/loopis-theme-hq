<?php
/**
 * List all subsite for a multisite network.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wpdb;

// Get links for all subsites in the multisite network.
$subsite_links = array();
if ( is_multisite() ) {
    $network_sites = get_sites(
        array(
            'number'   => 0,
            'archived' => 0,
            'spam'     => 0,
            'deleted'  => 0,
        )
    );

    foreach ( $network_sites as $network_site ) {
        $site_blog_id = (int) $network_site->blog_id;
        if ( is_main_site( $site_blog_id ) ) {
            continue;
        }
        // Get the options table for the current subsite.
        $options_table = $wpdb->get_blog_prefix( $site_blog_id ) . 'options';
        
        // Get the site URL for the current subsite.
        $site_url = (string) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT option_value FROM {$options_table} WHERE option_name = %s LIMIT 1",
                'siteurl'
            )
        );

        // Get the site name for the current subsite.
        $site_name = (string) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT option_value FROM {$options_table} WHERE option_name = %s LIMIT 1",
                'blogname'
            )
        );

        // Add the subsite link to the array.
        $subsite_links[] = '<span class="mega-link"><a href="' . esc_url( $site_url ) . '">📍 ' . esc_html( $site_name ) . '</a></span>';
    }
}

if ( ! empty( $subsite_links ) ) {
    echo implode( ' ', $subsite_links );
}
