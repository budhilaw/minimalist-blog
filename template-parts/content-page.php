<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Budhilaw_Blog
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post single-post'); ?>>
	<header class="post-header">
		<h1 class="post-title"><?php the_title(); ?></h1>
	</header>

	<?php if (has_post_thumbnail()) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail('large', array('alt' => the_title_attribute('echo=0'))); ?>
		</div>
	<?php endif; ?>

	<div class="post-content blog-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'budhilaw-blog'),
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article><!-- #post-<?php the_ID(); ?> --> 