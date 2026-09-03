<?php
/**
 * Handle tables
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
//little heavy
function reconstitute_locker(){
    global $wpdb;
    include_once LOOPIS_THEME_DIR . '/includes/functions/everyone/get-locker.php';
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

        $result = $wpdb->update(
            $table,
            array(
                'locker_id'   => $locker['id'],
                'locker_name' => $locker['name'] ?? '',
                'postal_code' => $locker['postal_code'] ?? '',
                'privacy'     => $locker['privacy'],
            ),
            array(
                'blog_id'     => $locker['blog_id'],
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%d',
            ),
            array(
                '%d',
            )
        );
        if ($result === 0) {
            $exists = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT 1 FROM {$table} WHERE blog_id = %d LIMIT 1",
                    $locker['blog_id']
                )
            );

            if (!$exists) {
                $wpdb->insert(
                    $table,
                    array(
                        'blog_id'     => $locker['blog_id'],
                        'locker_id'   => $locker['id'],
                        'locker_name' => $locker['name'] ?? '',
                        'postal_code' => $locker['postal_code'] ?? '',
                        'privacy'     => $locker['privacy'] ?? 0,
                    ),
                    array('%d', '%s', '%s', '%s', '%d')
                );
            }
        }
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


