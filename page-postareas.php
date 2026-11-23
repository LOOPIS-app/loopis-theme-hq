<?php
/**
 * Postareas page
 * 
 * Outputs a list of "postområden" where our members live.
 */

get_header();

// Get current user ID 
$user_id = get_current_user_id();
$postarea = get_user_meta($user_id, 'wpum_postarea', true);
$user_primary_blog = get_user_meta($user_id, 'primary_blog', true);

// Get all users with their postarea and count them
$users = get_users(array('fields' => 'ID'));

// Count users per postarea
$postarea_counts = array();
foreach ($users as $user_id) {
    $postarea_meta = get_user_meta($user_id, 'wpum_postarea', true);
    
    if (!empty($postarea_meta)) {
        if (!isset($postarea_counts[$postarea_meta])) {
            $postarea_counts[$postarea_meta] = 0;
        }
        $postarea_counts[$postarea_meta]++;
    }
}

// Sort by count descending
arsort($postarea_counts);
?>

<div class="page-padding center">

    <h1>👥 Postområden</h1>
    <hr>
    <p class="small">💡 Antal medlemmar per postområde.</p>
    
    <p>Ett postområde behöver hundra medlemmar för att bli ett LOOPIS-område.</p>
    <p>Ditt område <strong><?php echo esc_html($postarea); ?></strong> har just nu <?php echo isset($postarea_counts[$postarea]) ? esc_html($postarea_counts[$postarea]) : 0; ?> medlemmar.</p>
    <?php if ( empty($user_primary_blog) ) : ?>
    <span class="mega-link"><a href="<?php echo esc_url(home_url('/postareas')); ?>">⚡️ Stötta ditt område!</a></span><br>
    <?php endif; ?>
    <span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/hur-startar-man-loopis'));?>">📌 Hur startar man LOOPIS?</a></span><br>
    <span class="mega-link"><a href="<?php echo esc_url(home_url('/faq/rekrytering'));?>">📡 Hjälp till att rekrytera</a></span><br>

    <div class="columns"><div class="column1">↓ Postområden</div>
    <div class="column2 small">Medlemmar ↓</div></div>
    <hr>

    <?php foreach ($postarea_counts as $postarea => $count) : ?>
        <div style="display: flex; justify-content: space-between;">
            <span><?php echo ($count >= 100) ? '✅' : '⏳'; ?> <?php echo esc_html($postarea); ?></span>
            <span><?php echo esc_html($count); ?></span>
        </div>
    <?php endforeach; ?>

</div><!--page-padding-->

<?php get_footer(); ?>