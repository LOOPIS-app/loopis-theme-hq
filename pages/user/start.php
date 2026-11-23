<?php
/**
 * User page tabs.
 * 
 * Dynamic content of page-user.php
 * Reached on /user
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get profile economy
$profile_economy = loopis_ledger_economy($user_id);
$payments_membership = $profile_economy['payments_membership'];
$payments_coins = $profile_economy['payments_coins'];
$membership_coins = $profile_economy['membership_coins'];
$bought_coins = $profile_economy['bought_coins'];
$count_given = $profile_economy['count_given'];
$count_booked = $profile_economy['count_booked'];
$count_cancelled = $profile_economy['count_regret'];
$count_submitted = $profile_economy['count_submitted'];
$count_deleted = $profile_economy['count_deleted'];
$stars = $profile_economy['stars'];
$star_coins = $profile_economy['star_coins'];
$clovers = $profile_economy['clovers'];
$clover_coins = $profile_economy['clover_coins'];
$coins = $profile_economy['coins'];

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', LOOPIS_THEME_URI . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<!-- Tab Navigation -->
<div class="tab-nav">
  <nav class="tab-navbar">
    <a href="#" class="tab-link" data-tab="tab-settings">⚙</a>
    <a href="#" class="tab-link" data-tab="tab-coins">👛</a>
    <a href="#" class="tab-link" data-tab="tab-activity">🧮</a>
  </nav>
</div><!--tab-nav-->

<!-- Tab Content -->
<div class="tab-content">

<!-- Tab: Settings -->
<div id="tab-settings" class="tab-panel">
  <?php include_once __DIR__ . '/tabs/settings.php'; ?>
</div>

<!-- Tab: Activity -->
<div id="tab-activity" class="tab-panel">
  <?php include_once __DIR__ . '/tabs/activity.php'; ?>
</div>

<!-- Tab: Coins -->
<div id="tab-coins" class="tab-panel">
  <?php include_once __DIR__ . '/tabs/coins.php'; ?>
</div>

</div><!--tab-content-->