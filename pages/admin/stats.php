<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php $admin_path = home_url('/admin/'); ?>
<h1>📊 Statistik</h1>
<hr>
<p class="small">💡 Välj vilken statistik du vill se.</p>

<p>
<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/demography'), $admin_path) ); ?>">👯 Demografi</a></span>&emsp;
</p>