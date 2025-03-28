<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Budhilaw_Blog
 */

get_header();

// Get the sidebar position option from theme
global $budhilaw_blog_theme_options;
$sidebar_position = 'right'; // Default position
if (isset($budhilaw_blog_theme_options) && method_exists($budhilaw_blog_theme_options, 'get_options')) {
    $options = $budhilaw_blog_theme_options->get_options();
    if (isset($options['sidebar_position'])) {
        $sidebar_position = $options['sidebar_position'];
    }
}

// Check if sidebar should be disabled for this page
$sidebar_disabled = isset($budhilaw_blog_theme_options) && 
    method_exists($budhilaw_blog_theme_options, 'is_sidebar_disabled') && 
    $budhilaw_blog_theme_options->is_sidebar_disabled();

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
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header><!-- .page-header -->

		<?php
		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			/*
				* Include the Post-Type-specific template for the content.
				* If you want to override this in a child theme, then include a file
				* called content-___.php (where ___ is the Post Type name) and that will be used instead.
				*/
			get_template_part( 'template-parts/content', get_post_type() );

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