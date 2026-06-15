</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

<!-- Support form for on all pages except support posts? -->
<?php if ((current_user_can('member') || current_user_can('administrator'))) : ?>
    <?php get_template_part('templates/support/create-ticket'); ?>
<?php endif; ?>

</div><!--wrapper-->

<footer id="footer">
    <nav>
            <a href="<?php echo home_url('/'); ?>" class="footer-item">
                <span class="emoji">🗺</span>
                <span class="text">Start</span>
            </a>

            <a href="<?php echo home_url('/faq/'); ?>" class="footer-item">
                <span class="emoji">💡</span>
                <span class="text">Frågor & svar</span>
            </a>

            <!--a href="<?php echo home_url('/news/'); ?>" class="footer-item">
                <span class="emoji">📰</span>
                <span class="text">Nyheter</span>
            </a-->

            <?php if (is_user_logged_in()) : ?>

                <a href="<?php echo home_url('/user/'); ?>" class="footer-item">
                    <span class="emoji">👤️</span>
                    <span class="text">Min profil</span>
                </a>

            <?php else : ?>

                <a href="<?php echo home_url('/wp-login.php'); ?>" class="footer-item">
                    <span class="emoji">👤️</span>
                    <span class="text">Logga in</span>
                </a>

            <?php endif; ?>
    </nav>

<?php if (current_user_can('loopis_admin') || current_user_can('manage_options')) : ?>
    <div class="footer-backdoor" onclick="location.href='<?php echo esc_url(home_url('/admin/')); ?>'">🦀</div>
<?php endif; ?>

</footer>

<?php wp_footer(); ?>
</body>
</html>