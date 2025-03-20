// Set container class based on sidebar position
$container_class = 'site-content';
if (!$sidebar_disabled) {
    $container_class .= ' has-sidebar sidebar-' . esc_attr($sidebar_position);
}
?>

<div id="primary" class="<?php echo esc_attr($container_class); ?>">
    <main id="main" class="site-main">

    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf( esc_html__( 'Search Results for: %s', 'budhilaw-blog' ), '<span>' . get_search_query() . '</span>' );
                ?>
            </h1>
        </header><!-- .page-header -->

        <?php
        /* Start the Loop */
        while ( have_posts() ) :
            the_post();

            /**
             * Run the loop for the search to output the results.
             * If you want to overwrite this in a child theme then include a file
             * called content-search.php and that will be used instead.
             */
            get_template_part( 'template-parts/content', 'search' );

        endwhile;

        // Replace the default navigation with custom pagination
        if ( function_exists( 'budhilaw_blog_pagination' ) ) :
            budhilaw_blog_pagination( array(
                'total' => $wp_query->max_num_pages,
            ) );
        else :
            the_posts_navigation();
        endif;

    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;
    ?>

    </main><!-- #main -->

    <?php
    // Load sidebar if not disabled (inline)
    if (!$sidebar_disabled && is_active_sidebar('sidebar-1')) : ?>
        <aside id="secondary" class="widget-area">
            <?php dynamic_sidebar('sidebar-1'); ?>
        </aside><!-- #secondary -->
    <?php endif; ?>
</div><!-- #primary -->

<?php
get_footer(); 