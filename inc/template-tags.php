<?php
/**
 * Custom template tags for this theme
 *
 * @package Budhilaw_Blog
 */

if ( ! function_exists( 'budhilaw_blog_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function budhilaw_blog_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        echo '<span class="posted-on">' . $time_string . '</span>';
    }
endif;

if ( ! function_exists( 'budhilaw_blog_posted_by' ) ) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function budhilaw_blog_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x( 'by %s', 'post author', 'budhilaw-blog' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>';
    }
endif;

if ( ! function_exists( 'budhilaw_blog_entry_footer' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function budhilaw_blog_entry_footer() {
        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( esc_html__( ', ', 'budhilaw-blog' ) );
            if ( $categories_list ) {
                /* translators: 1: list of categories. */
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'budhilaw-blog' ) . '</span>', $categories_list );
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'budhilaw-blog' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'budhilaw-blog' ) . '</span>', $tags_list );
            }
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'budhilaw-blog' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'budhilaw-blog' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if ( ! function_exists( 'budhilaw_blog_post_thumbnail' ) ) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function budhilaw_blog_post_thumbnail() {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) :
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->
        <?php else : ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
            </a>
            <?php
        endif;
    }
endif;

if ( ! function_exists( 'budhilaw_blog_reading_time' ) ) :
    /**
     * Calculate and display estimated reading time for a post.
     *
     * @param int $post_id Post ID.
     * @return string Estimated reading time.
     */
    function budhilaw_blog_reading_time( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $content = get_post_field( 'post_content', $post_id );
        $word_count = str_word_count( strip_tags( $content ) );
        $reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed

        return sprintf( 
            _n( 
                '%d min read', 
                '%d min read', 
                $reading_time, 
                'budhilaw-blog' 
            ), 
            $reading_time 
        );
    }
endif;

