<?php
/**
 * Popular Articles Widget
 *
 * @package Budhilaw_Blog
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Popular Articles Widget Class
 */
class Budhilaw_Blog_Popular_Articles_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'budhilaw_blog_popular_articles_widget', // Base ID
            esc_html__( 'Budhilaw Blog: Popular Articles', 'budhilaw-blog' ), // Name
            array( 
                'description' => esc_html__( 'Display popular or recent articles in a visually appealing layout', 'budhilaw-blog' ),
                'classname' => 'widget_budhilaw_blog_popular_articles_widget',
            ) // Args
        );

        // Enqueue styles only when widget is active
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    /**
     * Enqueue widget-specific styles
     */
    public function enqueue_styles() {
        // Only enqueue if widget is active
        if (is_active_widget(false, false, $this->id_base, true)) {
            wp_enqueue_style(
                'budhilaw-blog-popular-articles-widget',
                get_template_directory_uri() . '/assets/css/popular-articles-widget.css',
                array(),
                BUDHILAW_BLOG_VERSION
            );
        }
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
        
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
        $post_source = isset( $instance['post_source'] ) ? $instance['post_source'] : 'popular';
        
        if ($post_source === 'popular') {
            // Popular posts query (by view count)
            $posts_query = new WP_Query( array(
                'posts_per_page' => $number,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'ignore_sticky_posts' => 1
            ) );
            
            // Fallback to recent posts if no popular posts (or view count not available)
            if ( ! $posts_query->have_posts() ) {
                $posts_query = new WP_Query( array(
                    'posts_per_page' => $number,
                    'ignore_sticky_posts' => 1
                ) );
            }
        } else {
            // Recent posts query
            $posts_query = new WP_Query( array(
                'posts_per_page' => $number,
                'ignore_sticky_posts' => 1
            ) );
        }
        
        if ( $posts_query->have_posts() ) :
        ?>
            <div class="widget-content popular-articles">
                <ul class="popular-posts-list">
                    <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
                        <li class="popular-post-item">
                            <a href="<?php the_permalink(); ?>" class="popular-post-link"><?php the_title(); ?></a>
                            <?php if ( $show_date ) : ?>
                                <div class="popular-post-date"><?php echo get_the_date(); ?></div>
                            <?php endif; ?>
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
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
        $post_source = isset( $instance['post_source'] ) ? $instance['post_source'] : 'popular';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'budhilaw-blog' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" max="10" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'budhilaw-blog' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'post_source' ) ); ?>"><?php esc_html_e( 'Post source:', 'budhilaw-blog' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'post_source' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_source' ) ); ?>" class="widefat">
                <option value="popular" <?php selected( $post_source, 'popular' ); ?>><?php esc_html_e( 'Popular posts (by views)', 'budhilaw-blog' ); ?></option>
                <option value="recent" <?php selected( $post_source, 'recent' ); ?>><?php esc_html_e( 'Recent posts', 'budhilaw-blog' ); ?></option>
            </select>
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
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['post_source'] = isset( $new_instance['post_source'] ) ? sanitize_text_field( $new_instance['post_source'] ) : 'popular';

        return $instance;
    }
} 