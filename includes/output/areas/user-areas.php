<?php
/**
 * Outputs blog posts matching subsites where the current user has access, linking to their respective subsite.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Store blog IDs (database suffixes) for sites that the current user can access.
    $user_access_blog_ids = array();
    if ( $user_id > 0 ) {
        $user_blogs = get_blogs_of_user( $user_id, true );
        foreach ( $user_blogs as $user_blog ) {
            $site_blog_id = isset( $user_blog->userblog_id ) ? (int) $user_blog->userblog_id : 0;
            if ( $site_blog_id > 0 && ! is_main_site( $site_blog_id ) ) {
                $user_access_blog_ids[] = $site_blog_id;
            }
        }
    }

    $user_access_blog_ids = array_values( array_unique( array_map( 'intval', $user_access_blog_ids ) ) );

    // Get posts with matching "area_blog_id" for the sites the current user can access.
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'area_blog_id',
                'value'   => ! empty( $user_access_blog_ids ) ? $user_access_blog_ids : array( 0 ),
                'compare' => 'IN',
                'type'    => 'NUMERIC',
            ),
        ),
    );

    $the_query = new WP_Query( $args );

    // Count total posts for the current user's accessible areas.
    $count_total = 0;
    if ( $the_query->have_posts() ) {
        foreach ( $the_query->posts as $area_post ) {
            $count_total++;
        }
    }
    ?>

    <!-- Output -->
     <?php if ( $count_total === 1 ) { ?>
    <div class="columns">
        <div class="column1"><h3>📍 Mitt område</h3></div>
        <div class="column2"><a href="<?php echo esc_url( home_url( '/areas/' ) ); ?>">→ Visa alla</a></div>
    </div>
<?php } else { ?>
    <h3 style="text-align: left;">📍 Mina områden</h3>
    <div class="columns">
        <div class="column1"><?php if ( $count_total > 1 ) { ?>↓ <?php echo $count_total; ?> <?php echo $count_total === 1 ? 'område' : 'områden'; ?><?php } ?></div>
        <div class="column2"><a href="<?php echo esc_url( home_url( '/areas/' ) ); ?>">→ Visa alla</a></div>
    </div>
    <?php } ?>
    <hr>

    <!-- Area posts -->
    <div class="post-list">
        <?php if ( $the_query->have_posts() ) { ?>
            <?php while ( $the_query->have_posts() ) { $the_query->the_post(); ?>
                <?php get_template_part( 'templates/post-list/area-post-linked' ); ?>
            <?php } ?>
        <?php } else { ?>
        <p>💢 Du är inte medlem i något område.</p>
        <?php } ?>
    </div><!--post-list-->

    <?php wp_reset_postdata();