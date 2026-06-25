<?php
/**
 * Template for single FAQ post.
 * 
 * Copy from local theme FAQ post layout.
 */

get_header(); ?>

<?php
$faq_terms = get_the_terms(get_the_ID(), 'faq-tag');
?>

<div class="page-padding center">
    <p><span class="rounded"><a href="<?php echo get_post_type_archive_link('faq'); ?>">💡 Frågor & Svar</a></span>
    <?php
    if (!empty($faq_terms) && !is_wp_error($faq_terms)) {
        foreach ($faq_terms as $faq_term) {
            echo '<span class="rounded"><i class="fas fa-hashtag"></i>' . esc_html($faq_term->name) . '</span>';
        }
    }
    ?>
    <div class="faq-post-wrapper">
        <div class="post-content">
        <?php the_content(); ?>
        </div>
    </div>

<div class="clear"></div>

<!-- More questions? -->
<?php include LOOPIS_THEME_HQ_DIR . '/templates/faq/questions-faq.php'; ?>

</div><!--page-padding center-->

<?php get_footer(); ?>