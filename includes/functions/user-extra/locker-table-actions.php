<?php
/**
 * Handle tables
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function reconstitute_locker(){
    global $wpdb;
    $blog_ids = get_sites([
        'fields' => 'ids',
    ]);
    $lockers = array();

    foreach($blog_ids as $blog_id){
        $blog_id = (int) $blog_id;
        if($blog_id===1){
            continue;
        }
        switch_to_blog($blog_id);
        $locker = get_locker();
        restore_current_blog();

        if (!empty($locker) && is_array($locker)) {
            $locker['blog_id'] = $blog_id;
            $lockers[] = $locker;
        }
    }

    $table = $wpdb->base_prefix.'loopis_areas';

    foreach ($lockers as $locker) {
        if (empty($locker['id'])) {
            continue;
        }

        $wpdb->replace(
            $table,
            array(
                'blog_id'     => $locker['blog_id'],
                'locker_id'   => $locker['id'],
                'locker_name' => $locker['name'] ?? '',
                'postal_code' => $locker['postal_code'] ?? '',
                'privacy'     => $locker['privacy'],
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%d',
            )
        );
    }
}

function get_area_privacy() {
    global $wpdb;

    $table = $wpdb->base_prefix . 'loopis_areas';

    $rows = $wpdb->get_results(
        "SELECT blog_id, locker_name, privacy
         FROM {$table}
         ORDER BY blog_id ASC",
        ARRAY_A
    );

    $areas = array();

    foreach ($rows as $row) {
        $blog_id = (int) $row['blog_id'];

        $areas[$blog_id] = array(
            'name'    => $row['locker_name'],
            'privacy' => (int) $row['privacy'],
        );
    }

    return $areas;
}


