<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php $admin_url = home_url('/admin/'); ?>

<h1>📊 Statistik</h1>
<hr>
<p class="small">💡 Välj vilken statistik du vill se.</p>

<p>I nuläget finns bara statistik för områden, på subsites.</p>
<p>Här ska liknande läggas till, statistik baserad på ledger och nya smarta funktioner.</p> 

<p>
<span class="big-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/demography'), $admin_url) ); ?>">👯 Demografi</a></span>&emsp;
</p>