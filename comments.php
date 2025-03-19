<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Budhilaw_Blog
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php
    // You can start editing here -- including this comment!
    if ( have_comments() ) :
        ?>
        <h2 class="comments-title">
            <?php
            $budhilaw_blog_comment_count = get_comments_number();
            if ( '1' === $budhilaw_blog_comment_count ) {
                printf(
                    /* translators: 1: title. */
                    esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'budhilaw-blog' ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            } else {
                printf( 
                    /* translators: 1: comment count number, 2: title. */
                    esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $budhilaw_blog_comment_count, 'comments title', 'budhilaw-blog' ) ),
                    number_format_i18n( $budhilaw_blog_comment_count ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 60,
                    'reply_text'  => esc_html__( 'Reply', 'budhilaw-blog' ),
                    'max_depth'   => 5,
                )
            );
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() ) :
            ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'budhilaw-blog' ); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $html_req = ( $req ? " required='required'" : '' );
    
    $fields = array(
        'author' => sprintf(
            '<p class="comment-form-author">%s %s</p>',
            sprintf(
                '<label for="author">%s%s</label>',
                esc_html__( 'Name', 'budhilaw-blog' ),
                ( $req ? ' <span class="required">*</span>' : '' )
            ),
            sprintf(
                '<input id="author" name="author" type="text" value="%s" size="30" maxlength="245"%s />',
                esc_attr( $commenter['comment_author'] ),
                $html_req
            )
        ),
        'email' => sprintf(
            '<p class="comment-form-email">%s %s</p>',
            sprintf(
                '<label for="email">%s%s</label>',
                esc_html__( 'Email', 'budhilaw-blog' ),
                ( $req ? ' <span class="required">*</span>' : '' )
            ),
            sprintf(
                '<input id="email" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s />',
                esc_attr( $commenter['comment_author_email'] ),
                $html_req
            )
        ),
        'url' => sprintf(
            '<p class="comment-form-url">%s %s</p>',
            sprintf(
                '<label for="url">%s</label>',
                esc_html__( 'Website', 'budhilaw-blog' )
            ),
            sprintf(
                '<input id="url" name="url" type="url" value="%s" size="30" maxlength="200" />',
                esc_attr( $commenter['comment_author_url'] )
            )
        ),
    );
    
    $comments_args = array(
        'title_reply'          => esc_html__( 'Leave a Reply', 'budhilaw-blog' ),
        'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'budhilaw-blog' ),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'cancel_reply_link'    => esc_html__( 'Cancel Reply', 'budhilaw-blog' ),
        'label_submit'         => esc_html__( 'Post Comment', 'budhilaw-blog' ),
        'comment_field'        => sprintf(
            '<p class="comment-form-comment">%s %s</p>',
            sprintf(
                '<label for="comment">%s%s</label>',
                esc_html__( 'Comment', 'budhilaw-blog' ),
                ( $req ? ' <span class="required">*</span>' : '' )
            ),
            '<textarea id="comment" name="comment" cols="45" rows="8" required="required"></textarea>'
        ),
        'fields'               => $fields,
        'comment_notes_before' => sprintf(
            '<p class="comment-notes">%s%s</p>',
            sprintf(
                '<span id="email-notes">%s</span>',
                esc_html__( 'Your email address will not be published.', 'budhilaw-blog' )
            ),
            ( $req ? sprintf( ' <span class="required-field-message">%s</span>', esc_html__( 'Required fields are marked *', 'budhilaw-blog' ) ) : '' )
        ),
        'logged_in_as'         => sprintf(
            '<div class="logged-in-as">%s%s</div>',
            sprintf(
                /* translators: 1: edit user link, 2: accessibility text, 3: user name, 4: logout URL */
                __( '<span>Logged in as <a href="%1$s">%3$s</a></span><a href="%4$s">Log out?</a>', 'budhilaw-blog' ),
                get_edit_user_link(),
                '',
                wp_get_current_user()->display_name,
                wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) )
            ),
            ''
        ),
    );
    
    comment_form( $comments_args );
    ?>

</div><!-- #comments -->

