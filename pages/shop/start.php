<?php
/**
 * Shop overview page
 * 
 * Dynamic content of page-shop.php
 * Reached on /shop
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🛒 Shoppen</h1>
<hr>
<p class="small">💡 Vår avdelning för hantering av vanliga pengar</p>

<h3>Välj vad du vill köpa</h3>
<hr>

<?php
$can_buy_membership = current_user_can('member_pending');
$can_buy_coins = current_user_can('member');

// Build membership link
$membership_href = $can_buy_membership
    ? add_query_arg('option', 'membership-stripe', home_url('/shop/'))
    : '#';
$membership_disabled_attrs = $can_buy_membership
    ? ''
    : ' aria-disabled="true" tabindex="-1" style="' . esc_attr('opacity: 0.5; pointer-events: none;') . '"';

// Build coins link
$coins_href = $can_buy_coins
    ? add_query_arg('option', 'coins-stripe', home_url('/shop/'))
    : '#';
$coins_disabled_attrs = $can_buy_coins
    ? ''
    : ' aria-disabled="true" tabindex="-1" style="' . esc_attr('opacity: 0.5; pointer-events: none;') . '"';
?>

<p>
    <span class="mega-link">
        <a href="<?php echo esc_url($membership_href); ?>"<?php echo $membership_disabled_attrs; ?>>💳 Medlemskap</a>
    </span>
</p>
<p>
    <span class="mega-link">
        <a href="<?php echo esc_url($coins_href); ?>"<?php echo $coins_disabled_attrs; ?>>💳 Regnbågsmynt</a>
    </span>
</p>