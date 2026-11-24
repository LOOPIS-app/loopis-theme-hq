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

    <h1>👥 Postområde</h1>
    <hr>
    <p class="small">💡 Antal medlemmar per postområde.</p>
    
    <p>Det behövs 100 medlemmar i ditt postområde för att starta LOOPIS.</p>
    
    <?php if ( is_user_logged_in() ) : ?>
    <p>Ditt postområde <strong><?php echo esc_html($user_postarea); ?></strong> har just nu <strong><?php echo isset($postarea_counts[$user_postarea]) ? esc_html($postarea_counts[$user_postarea]) : 0; ?> medlemmar.</strong></p>
    <?php endif; ?>

    <span class="mega-link"><a href="<?php echo esc_url('https://staging.loopis.app/faq/hur-startar-man-loopis');?>">📌 Hur startar man LOOPIS?</a></span><br>
    <span class="mega-link"><a href="<?php echo esc_url('https://staging.loopis.app/faq/rekrytering');?>">📡 Hjälp till att rekrytera</a></span><br>

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
