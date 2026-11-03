<?php
/**
 * Admin panels (default page of page-admin.php)
 * 
 * Admin overview with statistics, tools, and quick links
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<?php $admin_url = home_url('/admin/'); ?>

<div class="columns">
    <div class="column1">
        <h1>🐙 Admin HQ</h1>
    </div>
    <div class="column2"></div>
</div>
<hr>
<p class="small">💡 Visar verktyg tillgängliga för <span class="small-label">👤<?php echo wp_get_current_user()->user_login; ?></span></p>

<!-- Statistics -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/stats/overview', $admin_url) ); ?>'">
    <h5>📊 Statistik</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/stats.php'; ?>
    </p>
</div>

<!-- App traffic -->
<div class="wrapped">
    <h5>📲 Trafik i app</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/traffic-app.php'; ?>
    </p>
</div>

<!-- Pending members count -->
<?php if (current_user_can('loopis_admin') || current_user_can('loopis_board') || current_user_can('loopis_economy')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/members', $admin_url) ); ?>'">
        <h5>🎉 Nya medlemmar</h5>
        <hr>
        <p>
            <?php include __DIR__ . '/panels/members.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Pending members count -->
<?php if (current_user_can('loopis_board')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/ledger', $admin_url) ); ?>'">
        <h5>📕 Loggbok</h5>
        <hr>
        <p>
            <?php include __DIR__ . '/panels/ledger.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Economy -->
<?php if (current_user_can('loopis_economy')) : ?>
    <div>
        <h3>💰 Ekonomi</h3>
        <hr>
        <div class="span-list">
            <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/payments', $admin_url) ); ?>">📒 Visa köp</a></span>
            <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/payment-add', $admin_url) ); ?>">💸 Registrera köp</a></span>
        </div>
    </div>
<?php endif; ?>

<!-- Member Info Section -->
<?php if (current_user_can('loopis_admin') || current_user_can('loopis_board')) : ?>
    <h3>👤 Medlemmar</h3>
    <hr>
    <div class="span-list">
        <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/search-member', $admin_url) ); ?>">🔍 Sök medlem</a></span>
        <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/email-list', $admin_url) ); ?>">✉ Epost-adresser</a></span>
        <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/reward', $admin_url) ); ?>">🙏 Belöna</a></span>
        <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/rewards', $admin_url) ); ?>">🌟 Belöningar</a></span>
    </div>
<?php endif; ?>

<!-- More Section -->
<h3>🛠 Mer</h3>
<hr>
<div class="span-list">
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/access', $admin_url) ); ?>">🚧 Tillgång</a></span>
</div> 
