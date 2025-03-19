<?php
/**
 * The template for displaying all pages
 *
 * @package Budhilaw_Blog
 */

get_header();
?>

<div class="container">
    <div class="site-content">
        <main id="primary" class="content-area">

            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post single-post' ); ?>>
                    <header class="post-header">
                        <h1 class="post-title"><?php the_title(); ?></h1>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail( 'large', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-content blog-content">
                        <?php
                        the_content();

                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'budhilaw-blog' ),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div>
                </article>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>

        </main><!-- #primary -->

        <?php get_sidebar(); ?>
    </div>
</div>

<?php
get_footer();

