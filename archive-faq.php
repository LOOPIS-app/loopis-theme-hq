<?php
/**
 * Archive for custom post type 'faq' reached on URL /faq
 * 
 * Also handles search and filtering by FAQ tags.
 */

get_header(); ?>

<div class="page-padding center">

<h1>💡 Vanliga frågor</h1>
<hr>

<p class="small">🤓 Svar på vanliga frågor om LOOPIS.</p>

<?php
$faq_search = isset($_GET['faq-search']) ? sanitize_text_field(wp_unslash($_GET['faq-search'])) : '';
$faq_search_processed = isset($_GET['faq-search-submitted']) || isset($_GET['faq-search']) || isset($_GET['faq-tag']);
$selected_faq_tag = $faq_search_processed && isset($_GET['faq-tag'])
    ? sanitize_title(wp_unslash($_GET['faq-tag']))
    : ($faq_search_processed ? '' : 'basics');
$faq_tags = get_terms([
    'taxonomy'   => 'faq-tag',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);
if (is_wp_error($faq_tags)) {
    $faq_tags = array();
}
$faq_tag_slugs = wp_list_pluck($faq_tags, 'slug');
if ($faq_search_processed && !in_array($selected_faq_tag, $faq_tag_slugs, true)) {
    $selected_faq_tag = '';
}
$selected_faq_tag_name = '';
foreach ($faq_tags as $faq_tag) {
    if ($faq_tag->slug === $selected_faq_tag) {
        $selected_faq_tag_name = $faq_tag->name;
        break;
    }
}

$faq_args = array(
    'post_type'      => 'faq',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
);
if ($faq_search !== '') {
    $faq_args['s'] = $faq_search;
}
if ($selected_faq_tag !== '') {
    $faq_args['tax_query'] = array(
        array(
            'taxonomy' => 'faq-tag',
            'field'    => 'slug',
            'terms'    => $selected_faq_tag,
        ),
    );
}
$faq_query = new WP_Query($faq_args);
$all_faqs_url = add_query_arg(
    array(
        'post_type' => 'faq',
        'faq-search-submitted' => '1',
        'faq-search' => '',
        'faq-tag' => '',
    ),
    get_post_type_archive_link('faq')
);
?>

<form class="loopis-form" id="search-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('faq')); ?>">
    <input type="hidden" name="post_type" value="faq">
    <input type="hidden" name="faq-search-submitted" value="1">
    <input type="search" name="faq-search" value="<?php echo esc_attr($faq_search); ?>" placeholder="🔍 Skriv sökord">
    <?php if (!empty($faq_tags)) : ?>
        <select name="faq-tag">
            <option value=""><?php echo esc_html__('Alla taggar', 'loopis'); ?></option>
            <?php foreach ($faq_tags as $faq_tag) : ?>
                <option value="<?php echo esc_attr($faq_tag->slug); ?>" <?php selected($selected_faq_tag, $faq_tag->slug); ?>>
                    <?php echo esc_html($faq_tag->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
    <input type="submit" class="green small" value="Sök">
</form>

<h3 style="text-align: left;">
    <?php if ($selected_faq_tag_name !== '') : ?>
        <i class="fas fa-hashtag"></i><?php echo esc_html($selected_faq_tag_name); ?>
    <?php elseif ($faq_search_processed) : ?>
        📋 Sökresultat
    <?php endif; ?>
</h3>
<div class="columns">
    <div class="column1">↓ <?php echo (int) $faq_query->found_posts; ?> frågor</div>
    <div class="column2 small"><a href="<?php echo esc_url($all_faqs_url); ?>">→ Visa alla</a></div>
</div>
<hr>

<div class="post-list" style="line-height: 2.2;">
    <?php if ($faq_query->have_posts()) : ?>
        <?php while ($faq_query->have_posts()) : $faq_query->the_post(); ?>
            <span class="big-link"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></span>&nbsp;
        <?php endwhile; ?>
    <?php else : ?>
        <p>💢 Inga FAQ hittades.</p>
    <?php endif; ?>
</div>
<?php wp_reset_postdata(); ?>

<?php if (!$faq_search_processed) : ?>
<!--For members-->
<?php if ( is_user_logged_in() ) : ?>
<div class="wrapped">
    <h5>För medlemmar</h5>
    <hr>
    <p><span class="big-link"><i class="fab fa-google-drive"></i> <a href="https://drive.google.com/drive/folders/1l1B43flky-zXgQ2wFD24s_32N_pfWHvd?usp=drive_link">Google Drive</a></span> för föreningens protokoll</p>
    <p><span class="big-link"><i class="fab fa-discord"></i> <a href="https://discord.com/channels/1480883243740954626/1480883244449927231" target="_blank" rel="noreferrer noopener">Discord</a></span> för diskussion, volontärer och årsmöten</p>
</div><!--wrapped-->
<?php endif; ?>

<div class="wrapped">
<h5>Sociala medier</h5>
<hr>
<p><span class="big-link"><i class="fab fa-facebook-square"></i> <a href="https://www.facebook.com/loopis.org">Facebook</a></span>&nbsp;
<span class="big-link"><i class="fab fa-instagram"></i> <a href="https://www.instagram.com/loopis_org">Instagram</a></span>&nbsp;
<span class="big-link"><i class="fab fa-linkedin"></i> <a href="https://www.linkedin.com/company/loopis/">Linkedin</a></span></p>
</div><!--wrapped-->
<?php endif; ?>

</div><!--page-padding center-->

<?php get_footer(); ?>