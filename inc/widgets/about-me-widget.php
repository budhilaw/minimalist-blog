<?php
/**
 * About Me Widget
 *
 * @package Budhilaw_Blog
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * About Me Widget Class
 */
class Budhilaw_Blog_About_Me_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'budhilaw_blog_about_me_widget', // Base ID
            esc_html__( 'Budhilaw Blog: About Me', 'budhilaw-blog' ), // Name
            array( 
                'description' => esc_html__( 'Display your profile information with name, picture, bio and a read more button', 'budhilaw-blog' ),
                'classname' => 'widget_budhilaw_blog_about_me_widget',
            ) // Args
        );

        // Enqueue styles only when widget is active
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        
        // Enqueue admin scripts for media uploader
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Add script to footer to initialize media uploader
        add_action('admin_footer', array($this, 'media_uploader_script'));
    }

    /**
     * Enqueue widget-specific styles
     */
    public function enqueue_styles() {
        // Only enqueue if widget is active
        if (is_active_widget(false, false, $this->id_base, true)) {
            wp_enqueue_style(
                'budhilaw-blog-about-me-widget',
                get_template_directory_uri() . '/assets/css/about-me-widget.css',
                array(),
                BUDHILAW_BLOG_VERSION
            );
        }
    }
    
    /**
     * Enqueue admin scripts for media uploader
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on widgets admin page
        if ('widgets.php' !== $hook) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        
        // Enqueue admin styles for widgets
        wp_enqueue_style(
            'budhilaw-blog-widget-admin',
            get_template_directory_uri() . '/assets/css/widget-admin.css',
            array(),
            BUDHILAW_BLOG_VERSION
        );
        
        // Enqueue widget admin script for iframe style injection
        wp_enqueue_script(
            'budhilaw-blog-widget-admin-js',
            get_template_directory_uri() . '/assets/js/widget-admin.js',
            array(),
            BUDHILAW_BLOG_VERSION,
            true
        );
    }
    
    /**
     * Add media uploader script to admin footer
     */
    public function media_uploader_script() {
        // Only output on widgets admin page
        $screen = get_current_screen();
        if (!$screen || 'widgets' !== $screen->id) {
            return;
        }
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Store the original send_to_editor function
                var originalSendToEditor = window.send_to_editor;
                var currentUploadButton = null;
                
                // Handle click on upload button
                $(document).on('click', '.upload-image-button', function(e) {
                    e.preventDefault();
                    currentUploadButton = $(this);
                    
                    // If the media frame already exists, reopen it
                    if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                        wp.media.editor.open();
                        
                        // When the upload is complete, update the URL field and preview
                        window.send_to_editor = function(html) {
                            var targetInputId = currentUploadButton.data('target');
                            var imgUrl = null;
                            
                            // Try to extract the URL from the provided HTML
                            if (html.indexOf('<img') !== -1) {
                                var $img = $(html).filter('img');
                                if ($img.length) {
                                    imgUrl = $img.attr('src');
                                }
                            } else if ($(html).is('img')) {
                                imgUrl = $(html).attr('src');
                            } else if (html.indexOf('http') === 0) {
                                // Sometimes a direct URL is returned
                                imgUrl = html;
                            }
                            
                            if (imgUrl) {
                                console.log('Image URL found:', imgUrl);
                                console.log('Target input ID:', targetInputId);
                                
                                // Find the input field - try with CSS escaped ID first
                                var $input = $('#' + CSS.escape(targetInputId));
                                
                                if ($input.length === 0) {
                                    // Try more directly with the actual ID as a selector
                                    $input = $('#' + targetInputId);
                                }
                                
                                if ($input.length === 0) {
                                    // Fallback to find the closest input field to the button
                                    $input = currentUploadButton.closest('.media-upload-container').find('input[type="text"]');
                                }
                                
                                if ($input.length) {
                                    $input.val(imgUrl).trigger('change');
                                    console.log('Input found and value set to:', imgUrl);
                                    
                                    // Update the preview
                                    var $container = $input.closest('.media-upload-container');
                                    var $previewContainer = $container.next('.image-preview');
                                    
                                    if ($previewContainer.length === 0) {
                                        $container.after('<div class="image-preview"><img src="' + imgUrl + '" alt="<?php esc_attr_e('Profile Image Preview', 'budhilaw-blog'); ?>" style="max-width: 100%; margin-top: 10px;"></div>');
                                    } else {
                                        $previewContainer.html('<img src="' + imgUrl + '" alt="<?php esc_attr_e('Profile Image Preview', 'budhilaw-blog'); ?>" style="max-width: 100%; margin-top: 10px;">');
                                    }
                                    
                                    // Auto-save the widget form
                                    setTimeout(function() {
                                        var $saveButton = $input.closest('form').find('.widget-control-save');
                                        if ($saveButton.length) {
                                            $saveButton.prop('disabled', false).trigger('click');
                                        }
                                    }, 100);
                                } else {
                                    console.error('Input element not found for ID:', targetInputId);
                                }
                            }
                            
                            // Restore the original send_to_editor function
                            window.send_to_editor = originalSendToEditor;
                        };
                    }
                    return false;
                });
                
                // Re-initialize when widgets are added or updated
                $(document).on('widget-added widget-updated', function() {
                    // Nothing specific needed here, the event handlers are global
                });
            });
        </script>
        <?php
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

        $full_name = ! empty( $instance['full_name'] ) ? $instance['full_name'] : '';
        $bio = ! empty( $instance['bio'] ) ? $instance['bio'] : '';
        $image_url = ! empty( $instance['image_url'] ) ? $instance['image_url'] : '';
        $read_more_url = ! empty( $instance['read_more_url'] ) ? $instance['read_more_url'] : '';
        $read_more_text = ! empty( $instance['read_more_text'] ) ? $instance['read_more_text'] : esc_html__('Read More', 'budhilaw-blog');
        
        // Determine if this is in admin context
        $is_admin = defined('WP_ADMIN') && WP_ADMIN;
        
        // Add inline styles for admin preview
        $admin_styles = $is_admin ? 'style="background-color: #fff; color: #333; padding: 15px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin: 0 auto; max-width: 90%; text-align: center; border: 1px solid #e5e5e5;"' : '';
        $admin_name_styles = $is_admin ? 'style="color: #333; font-size: 1.5rem; font-weight: 600; margin-bottom: 0.75rem;"' : '';
        $admin_bio_styles = $is_admin ? 'style="color: #666; margin-bottom: 1.5rem; line-height: 1.6; font-size: 0.95rem;"' : '';
        $admin_button_styles = $is_admin ? 'style="display: inline-block; background-color: #0073aa; color: #fff; padding: 0.5rem 1.5rem; border-radius: 4px; font-weight: 500; text-decoration: none;"' : '';
        $admin_image_styles = $is_admin ? 'style="display: flex; justify-content: center; margin-bottom: 1.5rem;"' : '';
        $admin_img_styles = $is_admin ? 'style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1); border: 4px solid #fff;"' : '';
        
        ?>
        <div class="widget-content about-me-widget" <?php echo $admin_styles; ?>>
            <?php if ( $image_url ) : ?>
                <div class="about-me-image" <?php echo $admin_image_styles; ?>>
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $full_name ); ?>" <?php echo $admin_img_styles; ?>>
                </div>
            <?php endif; ?>
            
            <?php if ( $full_name ) : ?>
                <h3 class="about-me-name" <?php echo $admin_name_styles; ?>><?php echo esc_html( $full_name ); ?></h3>
            <?php endif; ?>
            
            <?php if ( $bio ) : ?>
                <div class="about-me-bio" <?php echo $admin_bio_styles; ?>>
                    <?php echo wp_kses_post( wpautop( $bio ) ); ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $read_more_url ) : ?>
                <div class="about-me-read-more">
                    <a href="<?php echo esc_url( $read_more_url ); ?>" class="button about-me-button" <?php echo $admin_button_styles; ?>>
                        <?php echo esc_html( $read_more_text ); ?>
                    </a>
                </div>
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
        $full_name = ! empty( $instance['full_name'] ) ? $instance['full_name'] : '';
        $bio = ! empty( $instance['bio'] ) ? $instance['bio'] : '';
        $image_url = ! empty( $instance['image_url'] ) ? $instance['image_url'] : '';
        $read_more_url = ! empty( $instance['read_more_url'] ) ? $instance['read_more_url'] : '';
        $read_more_text = ! empty( $instance['read_more_text'] ) ? $instance['read_more_text'] : esc_html__('Read More', 'budhilaw-blog');
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'full_name' ) ); ?>"><?php esc_html_e( 'Full Name:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'full_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'full_name' ) ); ?>" type="text" value="<?php echo esc_attr( $full_name ); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>"><?php esc_html_e( 'Profile Image URL:', 'budhilaw-blog' ); ?></label>
            <div class="media-upload-container">
                <input class="widefat image-url-field" id="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="text" value="<?php echo esc_url( $image_url ); ?>">
                <button type="button" class="button upload-image-button" data-target="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>">
                    <?php esc_html_e( 'Select Image', 'budhilaw-blog' ); ?>
                </button>
            </div>
            <?php if ( $image_url ) : ?>
                <div class="image-preview">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Profile Image Preview', 'budhilaw-blog' ); ?>" style="max-width: 100%; margin-top: 10px;">
                </div>
            <?php endif; ?>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>"><?php esc_html_e( 'Bio:', 'budhilaw-blog' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bio' ) ); ?>" rows="5"><?php echo esc_textarea( $bio ); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'read_more_url' ) ); ?>"><?php esc_html_e( 'Read More URL:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'read_more_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more_url' ) ); ?>" type="text" value="<?php echo esc_url( $read_more_url ); ?>" placeholder="<?php esc_attr_e( 'e.g., /about-me', 'budhilaw-blog' ); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'read_more_text' ) ); ?>"><?php esc_html_e( 'Read More Button Text:', 'budhilaw-blog' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'read_more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $read_more_text ); ?>">
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
        $instance['full_name'] = ( ! empty( $new_instance['full_name'] ) ) ? sanitize_text_field( $new_instance['full_name'] ) : '';
        $instance['bio'] = ( ! empty( $new_instance['bio'] ) ) ? wp_kses_post( $new_instance['bio'] ) : '';
        $instance['image_url'] = ( ! empty( $new_instance['image_url'] ) ) ? esc_url_raw( $new_instance['image_url'] ) : '';
        $instance['read_more_url'] = ( ! empty( $new_instance['read_more_url'] ) ) ? esc_url_raw( $new_instance['read_more_url'] ) : '';
        $instance['read_more_text'] = ( ! empty( $new_instance['read_more_text'] ) ) ? sanitize_text_field( $new_instance['read_more_text'] ) : esc_html__('Read More', 'budhilaw-blog');
        
        return $instance;
    }
} 