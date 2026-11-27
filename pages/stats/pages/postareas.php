<?php
/**
 * Postareas page
 * 
 * Dynamic content of page-stats.php
 * Reached on /stats/?view=pages/postareas
 * 
 * Outputs a list of "postområden" where our members live.
 */

get_header();

if ( is_user_logged_in() ) {
// Get current user ID 
$user_id = get_current_user_id();
$user_postarea = get_user_meta($user_id, 'wpum_postarea', true);
$user_primary_blog = get_user_meta($user_id, 'primary_blog', true);
}

// Get all users with their postarea and count them
$all_users = get_users(array('fields' => 'ID'));

// Count users per postarea
$postarea_counts = array();
foreach ($all_users as $user_id) {
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

if ( is_user_logged_in() ) {
// Get the count for the current user's postarea
$user_postarea_count = isset($postarea_counts[$user_postarea]) ? $postarea_counts[$user_postarea] : 0;
$user_postarea_remaining = 100 - $user_postarea_count;
} ?>

    <h1>📈 Områden</h1>
    <hr>
    <p class="small">💡 Antal medlemmar per postområde.</p>
    
    <p>Det behövs 100 medlemmar i ett postområde för att starta LOOPIS.</p>
    
    <?php if ( is_user_logged_in() ) : ?>
    <div class="wrapped">
        <h5>📍 <?php echo esc_html($user_postarea); ?></h5>
        <hr>
        <p>👥 Ditt postområde har <b><?php echo esc_html($user_postarea_count . ' ' . ($user_postarea_count != 1 ? 'medlemmar' : 'medlem')); ?></b>.</p>
        <?php if ($user_postarea_count < 100) { ?>
        <p>⚡️ Ni behöver bli ytterligare <b><?php echo esc_html($user_postarea_remaining) . ' ' . ($user_postarea_remaining != 1 ? 'medlemmar' : 'medlem'); ?></b>.</p>
        <?php } ?>
        <?php if ($user_postarea_count >= 100) { ?>
        <p>💚 Hurra för dig och dina grannar!</p>
        <?php } ?>
    </div>
    <?php endif; ?>

    <?php if ( !is_user_logged_in() || $user_postarea_count < 100 ) : ?>
    <div class="span-list">
        <span class="mega-link"><a href="<?php echo esc_url('https://staging.loopis.app/faq/rekrytering');?>">📡 Hjälp till att rekrytera</a></span><br>
        <span class="mega-link"><a href="<?php echo esc_url('https://staging.loopis.app/faq/hur-startar-man-loopis');?>">📌 Hur startar man LOOPIS?</a></span>
    </div>
    <?php endif; ?>

    <div class="columns"><div class="column1">↓ Postområde</div>
    <div class="column2 small">Medlemmar ↓</div></div>
    <hr>

    <?php foreach ($postarea_counts as $postarea => $count) : ?>
        <div style="display: flex; justify-content: space-between; <?php echo ($postarea === $user_postarea) ? 'font-weight: bold;' : ''; ?>">
            <span><?php echo ($count >= 100) ? '✅' : '⏳'; ?> <span style="<?php echo ($postarea === $user_postarea) ? 'font-size: 20px;' : ''; ?>"><?php echo esc_html($postarea); ?></span></span>
            <span><?php echo esc_html($count); ?></span>
        </div>
        <hr style="border: none; background-color: #E5E5E5 !important; border-top: 1px solid #E5E5E5 !important; margin: 2px 0;">
    <?php endforeach; ?>
