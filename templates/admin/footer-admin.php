</div><!--container-->
</div><!--wrapper-->
<?php $admin_url = home_url('/admin/'); ?>
<footer id="footer" class="footer-admin">
    <nav>
        
        <a href="<?php echo  esc_url($admin_url); ?>" class="footer-item">
        <span class="emoji">🦀</span>
        <span class="text">Översikt</span>
        </a>

        <a href="<?php echo esc_url( add_query_arg('view', 'members', $admin_url) ); ?>" class="footer-item">
        <span class="emoji">👥</span>
        <span class="text">Medlemmar</span>
        </a>
        
        <a href="<?php echo esc_url( add_query_arg('view', 'economy', $admin_url) ); ?>" class="footer-item">
        <span class="emoji">💰</span>
        <span class="text">Ekonomi</span>
        </a>
                
        <a href="<?php echo esc_url( add_query_arg('view', 'stats', $admin_url) ); ?>" class="footer-item">
        <span class="emoji">📊</span>
        <span class="text">Statistik</span>
        </a>

    </nav>
</footer><!--footer-->
<?php wp_footer(); ?>

</body>
</html>