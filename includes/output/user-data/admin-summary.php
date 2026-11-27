<?php
/**
 * Output user summary for admin on author.php
 * 
 * $user and $user_id is passed from context
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', LOOPIS_THEME_URI . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<div class="admin-block">
<?php include LOOPIS_THEME_DIR . '/templates/links/admin-link.php'; ?>

    <!-- Tab Navigation -->
    <div class="tab-nav">
        <nav class="tab-navbar">
            <a href="#" class="tab-link" data-tab="tab-info">👤</a>
            <a href="#" class="tab-link" data-tab="tab-payments">💰</a>
            <a href="#" class="tab-link" data-tab="tab-activity">🧮</a>
            <a href="#" class="tab-link" data-tab="tab-ledger">📕</a>
            <a href="#" class="tab-link" data-tab="tab-posts">🎁</a>
            <a href="#" class="tab-link" data-tab="tab-support">🛟</a>
        </nav>
    </div><!--tab-nav-->

    <div class="tab-content"> 

        <!-- Info Tab -->
        <div id="tab-info" class="tab-panel">
        <p class="small">💡 <?php echo $first_name;?>s användardata.</p>

            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/member-registry.php'; ?>
            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-stats.php'; ?>
            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-account.php'; ?>

        </div><!--tab-panel-->

        <!-- Payments Tab -->
        <div id="tab-payments" class="tab-panel">
            <p class="small">💡 <?php echo $first_name;?>s betalningar till föreningen.</p>
            <h7>📒 <?php echo $first_name;?>s kvitton</h7>
            <hr>
            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-payments.php'; ?>
        </div>

        <!-- Activity Tab -->
        <div id="tab-activity" class="tab-panel">
            <p class="small">💡 <?php echo $first_name;?>s aktivitet.</p>
            <h7>🧮 <?php echo $first_name;?>s aktivitet</h7>
            <hr>
            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-activity.php'; ?>
        </div>

        <!-- Ledger Tab -->
        <div id="tab-ledger" class="tab-panel">
            <p class="small">💡 <?php echo $first_name;?>s bokföring.</p>
            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-ledger.php'; ?>
        </div>

        
        <!-- Posts Tab -->
        <div id="tab-posts" class="tab-panel">
            <p class="small">💡 <?php echo $first_name;?>s annonser och paxningar.</p>
            <?php if (is_main_site()) : ?>
            <p>Användarens annonser är inte tillgängliga på LOOPIS startsida.</p>
            <p>Gå till användarens område: <span class="big-link white">📍<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-primary-blog-link.php'; ?></span></p>
            <?php else : ?>
            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-posts.php'; ?>
            <?php endif; ?>
        </div>

        <!-- Support Tab -->
        <div id="tab-support" class="tab-panel">
            <p class="small">💡 <?php echo $first_name;?>s supportfrågor.</p>
            <?php if (is_main_site()) : ?>
            <p>Användarens foruminlägg är inte tillgängliga på LOOPIS startsida.</p>
            <p>Gå till användarens område: <span class="big-link white">📍<?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-primary-blog-link.php'; ?></span></p>
            <?php else : ?>
            <?php include LOOPIS_THEME_HQ_DIR . '/includes/output/user-data/user-posts-support.php'; ?>
            <?php endif; ?>
        </div>

    </div><!--tab-content-->

</div><!--admin-block-->