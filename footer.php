</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

<!-- Support form for on all pages except support posts? -->
<?php if ((current_user_can('member') || current_user_can('administrator'))) : ?>
    <?php get_template_part('templates/support/create-ticket'); ?>
<?php endif; ?>
<?php $site_url = home_url("/"); ?>
</div><!--wrapper-->

<footer id="footer">
    <nav>
            <a href="<?php echo $site_url ?>" class="footer-item">
                <span class="emoji">🗺</span>
                <span class="text">Start</span>
            </a>

            <a href="<?php echo $site_url . 'faq/' ?>" class="footer-item">
                <span class="emoji">💡</span>
                <span class="text">Frågor & svar</span>
            </a>

            <a href="<?php echo $site_url . 'news/' ?>" class="footer-item">
                <span class="emoji">📰</span>
                <span class="text">Nyheter</span>
            </a>

            <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo $site_url . 'user/' ?>" class="footer-item">
                        <span class="emoji">👤️</span>
                        <span class="text">Min profil</span>
                    </a>
            <?php else : ?>
                <a href="<?php echo $site_url . 'wp-login.php' ?>" class="footer-item">
                    <span class="emoji">👤️</span>
                    <span class="text">Logga in</span>
                </a>
            <?php endif; ?>

            <?php if (current_user_can('loopis_admin') || current_user_can('manage_options')) : ?>
                <a href="<?php echo esc_url( home_url('/admin/') ); ?>" class="footer-item">
                    <span class="emoji">🦀</span>
                    <span class="text"><b>Admin HQ</b></span>
                </a>
            <?php endif; ?>
    </nav>
</footer><!--footer-->

<?php wp_footer(); ?>
</body>
</html>