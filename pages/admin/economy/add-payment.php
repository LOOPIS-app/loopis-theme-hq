<?php
/**
 * Page for manually registering purchases
 * 
 * Should be improved with selecting cash payments as well.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extra php functions
include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/admin-extra/admin_action_add_coins.php';
include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/admin-extra/admin_action_add_membership.php';
?>

<h1>💸 Registrera köp</h1>
<hr>
<p class="small">💡 Här registrerar du köp manuellt, utan Stripe.</p>

<!-- Register payment for coins -->
<h3>🪙 Köp av mynt</h3>
<hr>

<?php
// Get all members
$member_users = get_users(array('role' => 'member'));
?>

<div class="loopis-form loopis-filter">
<form method="post" class="arb" action="" style="display: flex; align-items: center;">
    <select id="selected_member" name="selected_member" style="max-width: 175px; margin-right: 10px;">
        <option value="">Välj medlem</option>
        <?php foreach ($member_users as $member_user) : ?>
            <option value="<?php echo esc_attr($member_user->ID); ?>">
                <?php echo esc_html($member_user->display_name); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button name="add_payment" type="submit" class="blue small" style="display: none;">☑ Betalat</button>
</form>
</div>

<?php
// Process payment
if (isset($_POST['add_payment']) && isset($_POST['selected_member'])) {
    $user_id = intval($_POST['selected_member']);
    admin_action_add_coins($user_id);
}
?>

<!-- Show button when member is selected -->
<script>
document.getElementById('selected_member').addEventListener('change', function() {
    var paymentButton = document.querySelector('button[name="add_payment"]');
    if (this.value !== '') {
        paymentButton.style.display = 'inline-block';
    } else {
        paymentButton.style.display = 'none';
    }
});
</script>

<!-- Register payment for membership -->
<h3>👤 Köp av medlemskap</h3>
<hr>

<?php
$pending_members = get_users(array(
    'role'    => 'member_pending',
    'blog_id' => get_current_blog_id(),
    'orderby' => 'registered',
    'order'   => 'DESC',
));
?>

<div class="loopis-form loopis-filter">
    <form method="post" action="">
        <?php wp_nonce_field('admin_add_membership'); ?>
        <select id="selected_pending_member" name="selected_pending_member">
            <option value="">Välj konto</option>
            <?php foreach ($pending_members as $pending_member) : ?>
                <option value="<?php echo esc_attr($pending_member->ID); ?>">
                    <?php echo esc_html($pending_member->display_name . ' (' . $pending_member->user_email . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button name="add_membership" type="submit" class="blue small" style="display: none;">☑ Betalat</button>
    </form>
</div>

<?php
if (isset($_POST['add_membership'])) {
    check_admin_referer('admin_add_membership');

    $user_id = isset($_POST['selected_pending_member'])
        ? absint(wp_unslash($_POST['selected_pending_member']))
        : 0;
    $pending_member_ids = wp_list_pluck($pending_members, 'ID');

    if (!$user_id || !in_array($user_id, array_map('intval', $pending_member_ids), true)) {
        wp_die(esc_html__('Välj en giltig blivande medlem.', 'loopis'));
    }

    admin_action_add_membership($user_id);
}
?>

<!-- Show button when user is selected -->
<script>
document.getElementById('selected_pending_member').addEventListener('change', function() {
    var paymentButton = this.form.querySelector('button[name="add_membership"]');
    if (this.value !== '') {
        paymentButton.style.display = 'inline-block';
    } else {
        paymentButton.style.display = 'none';
    }
});
</script>

<!-- List of Payments -->
<?php
$member_users = get_users(array(
    'role__in' => array('member', 'member_pending'),
    'fields' => array('ID', 'display_name')
));

$count = 0;
$today = date('Y-m-d');
$all_payments = array();

foreach ($member_users as $user) {
    $meta_key = 'wpum_payments';
    $meta_values = get_user_meta($user->ID, $meta_key, true);

    if (!empty($meta_values)) {
        foreach ($meta_values as $row) {
            $payment_method = $row['wpum_payment_method'][0]['value'] ?? '';
            if ($payment_method !== 'stripe') {
                $payment_type = $row['wpum_payment_type'][0]['value'];
                $payment_date = $row['wpum_payment_date'][0]['value'];
                $payment_amount = $row['wpum_payment_amount'][0]['value'];
                $payment_received_coins = $row['wpum_received_coins'][0]['value'];
                
                $all_payments[] = array(
                    'user_id'                 => $user->ID,
                    'display_name'            => $user->display_name,
                    'payment_date'            => $payment_date,
                    'payment_type'            => $payment_type,
                    'payment_amount'          => $payment_amount,
                    'payment_method'          => $payment_method,
                    'payment_received_coins'  => $payment_received_coins
                );
                $count++;
            }
        }
    }
}

// Sort by date (newest first)
usort($all_payments, function($a, $b) {
    return strtotime($b['payment_date']) - strtotime($a['payment_date']);
});
?>

<h3>🗃 Registrerade köp</h3>
<div class="columns">
    <div class="column1">↓ <?php echo $count; ?> köp</div>
    <div class="column2 small">💡 Senaste överst</div>
</div>
<hr>

<?php if ($count > 0) : ?>
    <?php foreach ($all_payments as $payment) : ?>
        <?php
        $author_url = get_author_posts_url($payment['user_id']);
        $payment_date = date('Y-m-d', strtotime($payment['payment_date']));
        $blue = ($payment_date === $today) ? 'blue_light' : '';
        ?>
        <p>
            <span class="link grey <?php echo esc_attr($blue); ?>">
                <a href="<?php echo esc_url($author_url); ?>" class="link grey">
                    <i class="fas fa-receipt"></i><?php echo esc_html($payment['payment_date']); ?>: 
                    <?php echo esc_html($payment['display_name']); ?> - 
                    <?php echo esc_html($payment['payment_type']); ?> - 
                    <?php echo esc_html($payment['payment_amount']); ?>kr 
                    (<?php echo esc_html($payment['payment_method']); ?>)
                </a>
            </span>
        </p>
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Hittade inga manuellt registrerade betalningar.</p>
<?php endif; ?>