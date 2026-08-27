<?php
/**
 * Archive template
 *
 * Displays category archives for area posts
 * 
 * Reached at: https://loopis.app/category/x/
 */

get_header(); ?>

<div class="page-padding">

        <?php if (is_category()) : ?>
            <h1><?php single_cat_title(); ?></h1>
        <?php elseif (is_tag()) : ?>
            <h1><i class="fas fa-hashtag"></i><?php single_tag_title(); ?></h1>
        <?php else : ?>
            <h1>Arkiv</h1>
        <?php endif; ?>
		<hr>
		<p class="small">💡 Alla områden <?php if (is_category()) { echo 'med status <span class="label">'; echo single_cat_title('', false); echo '</span>'; } elseif (is_tag()) { echo 'i kategorin <span class="label"><i class="fas fa-hashtag"></i>'; echo single_tag_title('', false); echo '</span>'; } else { echo 'arkivet'; } ?></p>

        <!-- Search Form -->
        <?php get_template_part('templates/forms/search-form'); ?>

        <?php
        global $wp_query;
        $wp_query->set('orderby', 'date');
        $wp_query->set('order', 'ASC');
        $wp_query->get_posts();

        // Count only posts that will actually render
        $count_total = 0;
        if (have_posts()) {
            foreach ($GLOBALS['wp_query']->posts as $archive_post) {
                if (in_category('private', $archive_post) && !current_user_can('manage_options') && !current_user_can('loopis_admin')) {
                    continue;
                }

                $count_total++;
            }
            rewind_posts();
        }
        ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count_total; ?> <?php echo $count_total === 1 ? 'område' : 'områden'; ?></div>
            <div class="column2 small"></div>
        </div>
        <hr>

        <!-- Posts -->
        <div class="post-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('templates/post-list/area-posts'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->

        <?php if ($GLOBALS['wp_query']->found_posts > 50) { get_template_part('templates/post-list/pagination'); } ?>

        <?php else : ?>
            <p>💢 Inga områden hittades</p>
        <?php endif; ?>

</div><!--page-padding-->


<?php get_footer(); ?>