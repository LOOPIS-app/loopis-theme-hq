<?php
/**
 * Custom maintenance page content.
 */

// Allow bypass for trusted office IPs configured in wp-config.php.
$loopis_get_client_ip = static function() {
    $candidates = array();

    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $candidates[] = (string) $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded = explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($forwarded as $ip) {
            $candidates[] = trim($ip);
        }
    }

    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $candidates[] = (string) $_SERVER['REMOTE_ADDR'];
    }

    foreach ($candidates as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    return '';
};

$allowed_ips_config = defined('LOOPIS_MAINTENANCE_ALLOWED_IPS') ? (string) LOOPIS_MAINTENANCE_ALLOWED_IPS : '';
$allowed_ips = array_filter(array_map('trim', explode(',', $allowed_ips_config)));
$client_ip = $loopis_get_client_ip();

if ($client_ip !== '' && in_array($client_ip, $allowed_ips, true)) {
    // Return false so caller can continue normal page execution.
    return false;
}

$assets_url = trailingslashit(get_template_directory_uri()) . 'includes/maintenance/';

// Set ready timestamp (Unix timestamp when site will be ready). Set to 0 to disable timer.
$ready_timestamp = strtotime('2026-06-16 12:00:00'); // Site ready at this date/time
$ready_timestamp = isset($ready_timestamp) ? (int) $ready_timestamp : 0;
?><!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,600;1,300;1,400&subset=latin&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $assets_url; ?>maintenance.css">
    <script src="<?php echo $assets_url; ?>loopis-timer.js"></script>
    <title>LOOPIS | Underhåll</title>
</head>

<body>

    <div class="container">

    <header class="header">
        <h1>Uppdatering pågår!</h1>
        <p>Ta en kopp te - snart kan du loopa igen.</p>
    </header>

    <?php if ($ready_timestamp > 0): ?>
        <section class="timer">
            <p class="timer-heading">Vi beräknas vara klara om:</p>
            <div class="timer-item">
                <div class="timer-data" id="timerResultHours"></div>
                <div class="timer-type">Timmar</div>
            </div>:
            <div class="timer-item">
                <div class="timer-data" id="timerResultMinutes"></div>
                <div class="timer-type">Minuter</div>
            </div>:
            <div class="timer-item">
                <div class="timer-data" id="timerResultSeconds"></div>
                <div class="timer-type">Sekunder</div>
            </div>
        </section>
        <script type="application/javascript">
            startCountdown(<?php echo (int) $ready_timestamp; ?>);
        </script>
    <?php endif; ?>

</div>

<footer class="footer">
    <div class="footer-content">
        <img src="<?php echo $assets_url; ?>LOOPIS_mail_header.png" alt="LOOPIS-logo" class="logo">
        <p><span class="big-link"><a href="mailto:info@loopis.app">info@loopis.app</a></span></p>
    </div>
</footer>

</body>
</html>
