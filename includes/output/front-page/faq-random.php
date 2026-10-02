<?php
/**
 * Output of five random FAQ posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => 5,
        'orderby' => 'rand'
    );
    $faq_query = new WP_Query($args);
?>

<!-- Output -->    
<h3 style="text-align: left;">💡 Vanliga frågor</h3>
<div class="columns">
    <div class="column1">↓ 5 exempel</div>
    <div class="column2"><a href="<?php echo esc_url(home_url('/faq/'));?>">→ Visa alla</a></div>
</div>
<hr>
<div class="post-list">
<?php if ($faq_query->have_posts()) : ?>
    <?php while ($faq_query->have_posts()) : $faq_query->the_post(); ?>
            <span class="big-link" style="display: inline-block; margin-bottom: 10px;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>&nbsp;
        <?php endwhile; ?>

    <?php wp_reset_postdata(); ?>
<?php endif; ?>
</div> 