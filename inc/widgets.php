<?php
/**
 * Custom widgets for this theme
 *
 * @package Budhilaw_Blog
 */

/**
 * Profile Widget
 */
class Budhilaw_Blog_Profile_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'budhilaw_blog_profile_widget', // Base ID
            esc_html__( 'Budhilaw Blog: Profile', 'budhilaw-blog' ), // Name
            array( 'description' => esc_html__( 'Display author profile information', 'budhilaw-blog' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        ?>
        <div class="widget-content profile-widget">
            <div class="profile-avatar">
                <?php if ( ! empty( $instance['avatar'] ) ) : ?>
                    <img src="<?php echo esc_url( $instance['avatar'] ); ?>" alt="<?php echo esc_attr( $instance['name'] ); ?>">
                <?php else : ?>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/profile-placeholder.jpg' ); ?>" alt="<?php echo esc_attr( $instance['name'] ); ?>">
                <?php endif; ?>
            </div>
            <h3 class="profile-name"><?php echo esc_html( $instance['name'] ); ?></h3>
            <p class="profile-bio"><?php echo esc_html( $instance['bio'] ); ?></p>
            <?php if ( ! empty( $instance['read_more_url'] ) ) : ?>
                <a href="<?php echo esc_url( $instance['read_more_url'] ); ?>" class="read-more"><?php esc_html_e( 'Read more', 'budhilaw-blog' ); ?></a>
            <?php endif; ?>
        </div>
        <?php
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'About Me', 'budhilaw-blog' );
        $name = ! empty( $instance['name'] ) ? $instance['name'] : '';
        $bio = ! empty( $instance['bio'] ) ? $instance['bio'] : '';
        $avatar = ! empty( $instance['avatar'] ) ? $instance['avatar'] : '';
        $read_more_url = ! empty( $instance['read_more_url'] ) ? $instance['read_more_url'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>"><?php esc_html_e( 'Bio:', 'budhilaw-blog' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bio' ) ); ?>" rows="4"><?php echo esc_textarea( $bio ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>"><?php esc_html_e( 'Avatar URL:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'avatar' ) ); ?>" type="url" value="<?php echo esc_attr( $avatar ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'read_more_url' ) ); ?>"><?php esc_html_e( 'Read More URL:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'read_more_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more_url' ) ); ?>" type="url" value="<?php echo esc_attr( $read_more_url ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['name'] = ( ! empty( $new_instance['name'] ) ) ? sanitize_text_field( $new_instance['name'] ) : '';
        $instance['bio'] = ( ! empty( $new_instance['bio'] ) ) ? sanitize_textarea_field( $new_instance['bio'] ) : '';
        $instance['avatar'] = ( ! empty( $new_instance['avatar'] ) ) ? esc_url_raw( $new_instance['avatar'] ) : '';
        $instance['read_more_url'] = ( ! empty( $new_instance['read_more_url'] ) ) ? esc_url_raw( $new_instance['read_more_url'] ) : '';

        return $instance;
    }
}

/**
 * Popular Posts Widget
 */
class Budhilaw_Blog_Popular_Posts_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'budhilaw_blog_popular_posts_widget', // Base ID
            esc_html__( 'Budhilaw Blog: Popular Posts', 'budhilaw-blog' ), // Name
            array( 'description' => esc_html__( 'Display popular posts', 'budhilaw-blog' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
        
        $popular_posts = new WP_Query( array(
            'posts_per_page' => $number,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'ignore_sticky_posts' => 1
        ) );
        
        if ( ! $popular_posts->have_posts() ) {
            // Fallback to recent posts if no popular posts
            $popular_posts = new WP_Query( array(
                'posts_per_page' => $number,
                'ignore_sticky_posts' => 1
            ) );
        }
        
        if ( $popular_posts->have_posts() ) :
            ?>
            <div class="widget-content">
                <ul class="popular-posts-list">
                    <?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>
                        <li class="popular-post-item">
                            <a href="<?php the_permalink(); ?>" class="popular-post-title"><?php the_title(); ?></a>
                            <div class="popular-post-date"><?php echo get_the_date(); ?></div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <?php
            wp_reset_postdata();
        endif;
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Popular Articles', 'budhilaw-blog' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'budhilaw-blog' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 3;

        return $instance;
    }
}

/**
 * Recent Comments Widget
 */
class Budhilaw_Blog_Recent_Comments_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'budhilaw_blog_recent_comments_widget', // Base ID
            esc_html__( 'Budhilaw Blog: Recent Comments', 'budhilaw-blog' ), // Name
            array( 'description' => esc_html__( 'Display recent comments', 'budhilaw-blog' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
        
        $recent_comments = get_comments( array(
            'number' => $number,
            'status' => 'approve',
        ) );
        
        if ( $recent_comments ) :
            ?>
            <div class="widget-content">
                <ul class="recent-comments-list">
                    <?php foreach ( $recent_comments as $comment ) : ?>
                        <li class="recent-comment-item">
                            <div class="comment-author"><?php echo esc_html( $comment->comment_author ); ?></div>
                            <div class="comment-text"><?php echo wp_kses_post( wp_trim_words( $comment->comment_content, 10 ) ); ?></div>
                            <div>
                                <a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) ); ?>" class="comment-post">
                                    <?php echo esc_html( get_the_title( $comment->comment_post_ID ) ); ?>
                                </a>
                                <span class="comment-date">• <?php echo esc_html( human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) ) . ' ' . __( 'ago', 'budhilaw-blog' ); ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        else :
            ?>
            <div class="widget-content">
                <p><?php esc_html_e( 'No comments yet.', 'budhilaw-blog' ); ?></p>
            </div>
            <?php
        endif;
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Comments', 'budhilaw-blog' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of comments to show:', 'budhilaw-blog' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 3;

        return $instance;
    }
}

/**
 * Register custom widgets
 */
function budhilaw_blog_register_widgets() {
    register_widget( 'Budhilaw_Blog_Profile_Widget' );
    register_widget( 'Budhilaw_Blog_Popular_Posts_Widget' );
    register_widget( 'Budhilaw_Blog_Recent_Comments_Widget' );
}
add_action( 'widgets_init', 'budhilaw_blog_register_widgets' );

