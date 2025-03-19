<?php
/**
 * The main template file
 *
 * @package Budhilaw_Blog
 */

get_header();
?>

<div class="container">
    <div class="site-content">
        <main id="primary" class="content-area">
            <?php if ( have_posts() ) : ?>
                <header class="page-header">
                    <?php
                    if ( is_home() && ! is_front_page() ) :
                        ?>
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                        <?php
                    else :
                        ?>
                        <h1 class="page-title"><?php esc_html_e( 'Latest Articles', 'budhilaw-blog' ); ?></h1>
                        <?php
                    endif;
                    ?>
                </header><!-- .page-header -->

                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <header class="post-header">
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="post-category">' . esc_html( $categories[0]->name ) . '</a>';
                            }
                            ?>
                            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </header>

                        <div class="post-content">
                            <?php the_excerpt(); ?>
                            <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Read More', 'budhilaw-blog' ); ?></a>
                        </div>

                        <footer class="post-meta">
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
                        </footer>
                    </article>
                    <?php
                endwhile;

                the_posts_pagination( array(
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>' . __( 'Previous', 'budhilaw-blog' ),
                    'next_text' => __( 'Next', 'budhilaw-blog' ) . '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                ) );

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>
        </main><!-- #primary -->

        <?php get_sidebar(); ?>
    </div>
</div>

<?php
get_footer();

