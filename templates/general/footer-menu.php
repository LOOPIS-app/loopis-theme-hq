<?php
$site_url = home_url("/");
?>
<div class="footer-menu">
    <nav>
        <a href="<?php echo esc_url($site_url); ?>" class="footer-button">
            <span class="emoji">🗺</span>
            <span class="text">Start</span>
        </a>

        <a href="<?php echo esc_url($site_url . 'faq/'); ?>" class="footer-button">
            <span class="emoji">💡</span>
            <span class="text">Frågor & svar</span>
        </a>

        <a href="<?php echo esc_url($site_url . 'news/'); ?>" class="footer-button">
            <span class="emoji">📰</span>
            <span class="text">Nyheter</span>
        </a>

        <?php if (is_user_logged_in()) : ?>
            <?php if (current_user_can('administrator')) : ?>
                <a href="<?php echo esc_url($site_url . 'admin/'); ?>" class="footer-button">
                    <span class="emoji">🐙️</span>
                    <span class="text">Admin</span>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url($site_url . 'profile/'); ?>" class="footer-button">
                    <span class="emoji">👤️</span>
                    <span class="text">Min profil</span>
                </a>
            <?php endif; ?>
        <?php else : ?>
            <a href="<?php echo esc_url($site_url . '/wp-login.php'); ?>" class="footer-button">
                <span class="emoji">👤</span>
                <span class="text">Logga in</span>
            </a>
        <?php endif; ?>
    </nav>
</div>
