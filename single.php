<?php
/**
 * The template for displaying all single posts
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
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="post-category">' . esc_html( $categories[0]->name ) . '</a>';
                        }
                        ?>
                        <h1 class="post-title"><?php the_title(); ?></h1>
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
                                $content = get_post_field( 'post_content', get_the_ID() );
                                $word_count = str_word_count( strip_tags( $content ) );
                                $reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed
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

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail( 'large', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-content blog-content">
                        <?php
                        the_content(
                            sprintf(
                                wp_kses(
                                    /* translators: %s: Name of current post. Only visible to screen readers */
                                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'budhilaw-blog' ),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post( get_the_title() )
                            )
                        );

                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'budhilaw-blog' ),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div>

                    <footer class="post-footer">
                        <?php
                        // Display tags
                        $tags_list = get_the_tag_list( '', ' ' );
                        if ( $tags_list ) {
                            echo '<div class="post-tags">' . $tags_list . '</div>';
                        }
                        ?>
                    </footer>
                </article>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

                // Previous/next post navigation.
                the_post_navigation(
                    array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'budhilaw-blog' ) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'budhilaw-blog' ) . '</span> <span class="nav-title">%title</span>',
                    )
                );

            endwhile; // End of the loop.
            ?>

        </main><!-- #primary -->

        <?php get_sidebar(); ?>
    </div>
</div>

<?php
get_footer();

