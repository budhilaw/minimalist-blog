<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Budhilaw_Blog
 */

// Get theme options
global $budhilaw_blog_theme_options;
$thumbnail_position = 'top'; // Default position
$thumbnail_size = 'medium'; // Default size

if (isset($budhilaw_blog_theme_options) && method_exists($budhilaw_blog_theme_options, 'get_options')) {
    $options = $budhilaw_blog_theme_options->get_options();
    if (isset($options['thumbnail_position'])) {
        $thumbnail_position = $options['thumbnail_position'];
    }
    if (isset($options['thumbnail_size'])) {
        $thumbnail_size = $options['thumbnail_size'];
    }
}

// Add class based on thumbnail position
$post_class = 'post';
if (!is_singular() && has_post_thumbnail() && $thumbnail_position == 'beside') {
    $post_class .= ' has-thumbnail-beside';
}

// Force style refresh by adding timestamp
$style_refresh = time();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?> data-thumbnail-position="<?php echo esc_attr($thumbnail_position); ?>" data-thumbnail-size="<?php echo esc_attr($thumbnail_size); ?>" data-refresh="<?php echo $style_refresh; ?>">
	<?php if (has_post_thumbnail() && !is_singular() && $thumbnail_position == 'top') : ?>
		<div class="post-thumbnail post-thumbnail-top">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail($thumbnail_size, array('alt' => the_title_attribute('echo=0'), 'class' => 'thumbnail-image thumbnail-' . esc_attr($thumbnail_size))); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-wrapper<?php echo ($thumbnail_position == 'beside' && has_post_thumbnail() && !is_singular()) ? ' has-beside-layout' : ' has-top-layout'; ?>">
		<?php if (has_post_thumbnail() && !is_singular() && $thumbnail_position == 'beside') : ?>
			<div class="post-thumbnail thumbnail-beside">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail($thumbnail_size, array('alt' => the_title_attribute('echo=0'), 'class' => 'thumbnail-image thumbnail-' . esc_attr($thumbnail_size))); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="post-content-wrapper">
			<header class="post-header">
				<?php
				if (!is_singular()) :
					$categories = get_the_category();
					if (!empty($categories)) :
						echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category">' . esc_html($categories[0]->name) . '</a>';
					endif;
					?>
					<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php
				else :
					$categories = get_the_category();
					if (!empty($categories)) :
						echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category">' . esc_html($categories[0]->name) . '</a>';
					endif;
					?>
					<h1 class="post-title"><?php the_title(); ?></h1>
				<?php endif; ?>

				<div class="post-meta">
					<div class="post-meta-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
						<?php the_author(); ?>
					</div>
					<div class="post-meta-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
						<?php echo get_the_date(); ?>
					</div>
					<div class="post-meta-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
						<?php
						$content = get_post_field('post_content', get_the_ID());
						$word_count = str_word_count(strip_tags($content));
						$reading_time = ceil($word_count / 200); // Assuming 200 words per minute reading speed
						printf(
							_n(
								'%d min read',
								'%d min read',
								$reading_time,
								'budhilaw-blog'
							),
							$reading_time
						);
						?>
					</div>
				</div>
			</header>

			<?php if (has_post_thumbnail() && is_singular()) : ?>
				<div class="post-thumbnail">
					<?php the_post_thumbnail('large', array('alt' => the_title_attribute('echo=0'))); ?>
				</div>
			<?php endif; ?>

			<div class="post-content blog-content">
				<?php
				if (is_singular()) :
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'budhilaw-blog'),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post(get_the_title())
						)
					);

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__('Pages:', 'budhilaw-blog'),
							'after'  => '</div>',
						)
					);
				else :
					the_excerpt();
					?>
					<a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read More', 'budhilaw-blog'); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if (is_singular()) : ?>
		<footer class="post-footer">
			<?php
			// Display tags
			$tags_list = get_the_tag_list('', ' ');
			if ($tags_list) {
				echo '<div class="post-tags">' . $tags_list . '</div>';
			}
			?>
		</footer>
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> --> 