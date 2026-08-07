<?php
/**
 * Admin panels (default page of page-admin.php)
 * 
 * Admin overview with statistics, tools, and quick links
 */

if (!defined('ABSPATH')) {
    exit;
}
$admin_url = home_url('/admin/');

?>

<div class="columns">
    <div class="column1">
        <h1>🦀 Admin HQ</h1>
    </div>
    <div class="column2"></div>
</div>
<hr>
<p class="small">💡 Visar verktyg tillgängliga för <span class="small-link"><a href="<?php echo esc_url( home_url('/user/') ); ?>">👤<?php echo wp_get_current_user()->user_login; ?></a></span></p>

<!-- App traffic -->
<div class="wrapped">
    <h5>📲 Trafik i app</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/traffic-app.php'; ?>
    </p>
</div>

<!-- Pending members count -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_board')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'activation', $admin_url) ); ?>'">
        <h5>👻 Nya medlemmar?</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/panels/members-pending.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Economy -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_economy')) : ?>
    <div>
        <h3>💰 Ekonomi</h3>
        <hr>
        <div>
            <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'economy/payments', $admin_url) ); ?>">📒 Alla köp</a></span>&nbsp;
            <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'economy/coins', $admin_url) ); ?>">🪙 Köp av mynt</a></span>&nbsp;
        </div>
    </div>
<?php endif; ?>

<!-- Member Info Section -->
<?php if (current_user_can('board')) : ?>
    <h3>👤 Styrelse</h3>
    <hr>
    <div>
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/registry', $admin_url) ); ?>">🗃 Medlemsregister</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/email-list', $admin_url) ); ?>">✉ Epost-adresser</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/reward', $admin_url) ); ?>">🙏 Belöna</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/rewards', $admin_url) ); ?>">🌟 Belöningar</a></span>&nbsp;
    </div>
<?php endif; ?>

<!-- Access List -->
 <p>&nbsp;</p>
    <div class="wrapped">
        <h5>🚧 Vilka har tillgång?</h5>
        <hr>
        <?php include __DIR__ . '/panels/access.php'; ?>
    </div>