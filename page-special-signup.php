<?php
/**
 * Page which redirects potential private user after adding special location.
 * 
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

$code = isset($_GET['spt']) ? (string) $_GET['spt'] : '';
$code = preg_replace('/[^A-Za-z0-9_-]/', '', $code);
if ($code === '') {
    wp_safe_redirect(home_url('/start/'));
    exit;
}
$expected_hash = get_option('special_invite_hash', '');
if (!$expected_hash) {
    wp_safe_redirect(home_url('/start/'));
    exit;
}
if (!hash_equals($expected_hash, hash('sha256', $code))) {
    wp_safe_redirect(home_url('/start/'));
    exit;
}

$blog_id = 4;
$role_slug = 'member_pending';
$already_a_member = false;
if(is_user_logged_in()){
    $user_id = get_current_user_id();

    include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/user-extra/member-pending-check.php';

    $membership = member_pending_check($user_id);
    $role_slug=  $membership['member_data_complete'] ? 'member' :  $role_slug;
    $payments = loopis_ledger_user_payments($user_id);
    foreach($payments as $entry){
        if($entry['type'] === 'medlemskap'){
            $already_a_member = true;
            break;
        }
    }
    
    if(!$already_a_member){
        add_membership($user_id,['description'=>'platform24']);
    }
    switch_to_blog($blog_id);
    add_user_to_blog($blog_id, $user_id, $role_slug);
    restore_current_blog();
    update_user_meta($user_id,'primary_blog',$blog_id);
    wp_safe_redirect(network_site_url('/p24/'));
    exit;
}
$payload = $blog_id; // placeholder currently blog + role
setcookie(
    'special_invite_payload',
    base64_encode($payload),
    [
        'expires'  => time() + 60 * 60 * 24,
        'path'     => '/',
        'secure'   => is_ssl(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]
);
wp_safe_redirect(network_site_url('/platform24/'));
exit;