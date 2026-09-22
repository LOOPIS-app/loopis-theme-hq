<?php
/**
 * Archive template
 *
 * Displays category archives for area posts
 * 
 * Reached at: https://loopis.app/category/x/
 */

get_header(); ?>

<div class="page-padding center">

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

        // Build the list of subsites the current user can access.
        $user_access_blog_ids = array();
        $current_user_id = get_current_user_id();
        if ($current_user_id > 0) {
            $user_blogs = get_blogs_of_user($current_user_id, true);
            foreach ($user_blogs as $user_blog) {
                $site_blog_id = isset($user_blog->userblog_id) ? (int) $user_blog->userblog_id : 0;
                if ($site_blog_id > 0 && !is_main_site($site_blog_id)) {
                    $user_access_blog_ids[] = $site_blog_id;
                }
            }
        }
        $user_access_blog_ids = array_values(array_unique(array_map('intval', $user_access_blog_ids)));
        $can_access_all_private_areas = current_user_can('manage_options') || current_user_can('loopis_admin');

        // Remove private areas the current user cannot access from the archive query.
        $visible_posts = array();
        foreach ($wp_query->posts as $archive_post) {
            if (
                has_category('private', $archive_post)
                && !$can_access_all_private_areas
                && !in_array(
                    (int) get_post_meta($archive_post->ID, 'area_blog_id', true),
                    $user_access_blog_ids,
                    true
                )
            ) {
                continue;
            }

            $visible_posts[] = $archive_post;
        }
        $wp_query->posts = $visible_posts;
        $wp_query->post_count = count($visible_posts);
        $wp_query->found_posts = $wp_query->post_count;

        // Count only posts that will actually render
        $count_total = $wp_query->post_count;
        rewind_posts();
        ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count_total; ?> <?php echo $count_total === 1 ? 'område' : 'områden'; ?></div>
            <div class="column2"><a href="<?php echo esc_url( home_url( '/areas/' ) ); ?>">→ Visa alla</a></div>
        </div>
        <hr>

        <!-- Posts -->
        <div class="post-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('templates/post-list/area-post'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->

        <?php if ($GLOBALS['wp_query']->found_posts > 50) { get_template_part('templates/post-list/pagination'); } ?>

        <?php else : ?>
            <p>💢 Inga områden hittades</p>
        <?php endif; ?>

        <?php if ( current_user_can('manage_options') || current_user_can('loopis_admin') || has_category('private') ) : ?>
            <p class="info">💡 Privata områden visas bara för de som har tillgång.</p>
        <?php endif; ?>

</div><!--page-padding-->


<?php get_footer(); ?>