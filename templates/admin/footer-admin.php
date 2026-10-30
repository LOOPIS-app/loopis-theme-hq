</div><!--container-->
</div><!--wrapper-->
<?php $admin_url = home_url('/admin/'); ?>
<footer id="footer" class="footer-admin">
    <nav>
        
        <a href="<?php echo  esc_url($admin_url); ?>" class="footer-item">
        <span class="emoji">🐙</span>
        <span class="text">Översikt</span>
        </a>
        
        <a href="<?php echo esc_url( add_query_arg('view', 'pages/search-member', $admin_url) ); ?>" class="footer-item">
        <span class="emoji">👤</span>
        <span class="text">Sök medlem</span>
        </a>
                
        <a href="<?php echo esc_url( add_query_arg('view', 'pages/stats/overview', $admin_url) ); ?>" class="footer-item">
        <span class="emoji">📊</span>
        <span class="text">Statistik</span>
        </a>

    </nav>

<div class="footer-backdoor" onclick="location.href='<?php echo esc_url(home_url('/start/')); ?>'">🚪</div>

</footer><!--footer-->
<?php wp_footer(); ?>

</body>
</html>