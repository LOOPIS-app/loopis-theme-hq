</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

<?php if ((current_user_can('member') || current_user_can('administrator'))) : ?>
    <?php get_template_part('templates/support/create-ticket'); ?>
<?php endif; ?>
<?php $site_url = home_url("/"); ?>
</div><!--wrapper-->

<footer id="footer">
    <div class="footer-menu">
        <nav>
            <a href="<?php echo $site_url ?>" class="footer-button">
                <span class="emoji">️🗺</span>
                <span class="text">Karta</span>
            </a>
            
            <a href="<?php echo esc_url($site_url . 'submit/'); ?>" class="footer-button">
                <span class="emoji">📈</span>
                <span class="text">Nyheter</span>
            </a>

            <a href="<?php echo $site_url . 'faq/' ?>" class="footer-button">
                <span class="emoji">💡</span>
                <span class="text">Frågor & svar</span>
            </a>

            <?php if (is_user_logged_in()) : ?>
                <?php if (current_user_can('administrator')) : ?>
                    <a href="<?php echo $site_url . 'admin/' ?>" class="footer-button">
                        <span class="emoji">🐙️</span>
                        <span class="text">Admin</span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo $site_url . 'profile/' ?>" class="footer-button">
                        <span class="emoji">👤️</span>
                        <span class="text">Min profil</span>
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <a href="<?php echo $site_url . 'log-in/' ?>" class="footer-button">
                    <span class="emoji">👤️</span>
                    <span class="text">Logga in</span>
                </a>
            <?php endif; ?>
        </nav>
    </div>
</footer><!--footer-->

<?php wp_footer(); ?>
</body>
</html>