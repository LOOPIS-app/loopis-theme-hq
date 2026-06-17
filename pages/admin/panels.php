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
<p class="small">💡 Visar verktyg tillgängliga för <span class="small-link"><a href="<?php echo esc_url( home_url('/profile/') ); ?>">👤<?php echo wp_get_current_user()->user_login; ?></a></span></p>

<!-- Dashboard Cards -->

<!-- Activity -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'activity', $admin_url) ); ?>'">
        <h5>📑 Aktivitet</h5>
        <hr>
        <p class="small">
            💢 To be developed...
        </p>
    </div>
<?php endif; ?>

<!-- Statistics -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'stats', $admin_url) ); ?>'">
        <h5>📊 Statistik</h5>
        <hr>
        <p class="small">
            💢 To be developed...
        </p>
    </div>
<?php endif; ?>

<!-- Member registry -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_board')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'activation', $admin_url) ); ?>'">
        <h5>👥 Medlemmar</h5>
        <hr>
        <p class="small">
            💢 To be developed...
        </p>
    </div>
<?php endif; ?>

<!-- Pending members count -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_board')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'activation', $admin_url) ); ?>'">
        <h5>👻 Medlemmar?</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/panels/members-pending.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Economy -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_board')) : ?>
    <div>
        <h3>💰 Ekonomi</h3>
        <hr>
        <div>
            <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'payments', $admin_url) ); ?>">📒 Alla köp</a></span>&nbsp;
            <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'coins', $admin_url) ); ?>">🪙 Köp av mynt</a></span>&nbsp;
        </div>
    </div>
<?php endif; ?>

<!-- Access List -->
 <p>&nbsp;</p>
    <div class="wrapped">
        <h5>🚧 Vilka har tillgång?</h5>
        <hr>
        <?php include __DIR__ . '/panels/access.php'; ?>
    </div>