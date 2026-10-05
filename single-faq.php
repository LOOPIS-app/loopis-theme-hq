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
    <p><span class="rounded"><a href="<?php echo get_post_type_archive_link('faq'); ?>">💡 Vanliga frågor</a></span>
    <?php
    if (!empty($faq_terms) && !is_wp_error($faq_terms)) {
        foreach ($faq_terms as $faq_term) {
            $faq_tag_url = add_query_arg(
                array(
                    'post_type' => 'faq',
                    'faq-search-submitted' => '1',
                    'faq-search' => '',
                    'faq-tag' => $faq_term->slug,
                ),
                get_post_type_archive_link('faq')
            );
            echo '<span class="rounded"><a href="' . esc_url($faq_tag_url) . '"><i class="fas fa-hashtag"></i>' . esc_html($faq_term->name) . '</a></span>';
        }
    }
    ?>
    <div class="faq-post-wrapper">
        <div class="faq-post-content">
        <?php the_content(); ?>
        </div>
    </div>

<div class="clear"></div>

<!-- More questions? -->
<?php include LOOPIS_THEME_HQ_DIR . '/templates/faq/questions-faq.php'; ?>

</div><!--page-padding center-->

<?php get_footer(); ?>