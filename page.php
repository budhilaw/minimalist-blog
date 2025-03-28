<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
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

