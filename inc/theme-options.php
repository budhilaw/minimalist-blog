<?php
/**
 * Budhilaw Blog Theme Options
 *
 * @package Budhilaw_Blog
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Options Class
 */
class Budhilaw_Blog_Theme_Options {

    /**
     * Option name in the database
     */
    private $option_name = 'budhilaw_blog_options';

    /**
     * Default theme options
     */
    private $default_options = array(
        'sidebar_position' => 'right',
        'disable_sidebar_slugs' => '',
        'disable_sidebar_singles' => false,
        'thumbnail_position' => 'top',
        'thumbnail_size' => 'medium',
    );

    /**
     * Active tab
     */
    private $active_tab = 'layout';

    /**
     * Theme options
     */
    private $options = array();

    /**
     * Constructor
     */
    public function __construct() {
        $this->option_name = 'budhilaw_blog_options';
        $this->default_options = array(
            'sidebar_position' => 'right',
            'disable_sidebar_slugs' => '',
            'disable_sidebar_singles' => false,
            'thumbnail_position' => 'top',
            'thumbnail_size' => 'medium',
        );
        $this->options = get_option($this->option_name, $this->default_options);
        
        // Add menu item
        add_action('admin_menu', array($this, 'add_theme_options_page'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Enqueue admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Add sidebar position class to body
        add_filter('body_class', array($this, 'sidebar_position_body_class'));
    }

    /**
     * Add theme options page to admin menu
     */
    public function add_theme_options_page() {
        add_menu_page(
            esc_html__('Budhilaw Blog Theme Options', 'budhilaw-blog'),
            esc_html__('Theme Options', 'budhilaw-blog'),
            'manage_options',
            'budhilaw-blog-options',
            array($this, 'render_options_page'),
            'dashicons-admin-appearance',
            60
        );
    }

    /**
     * Register settings and fields
     */
    public function register_settings() {
        register_setting(
            'budhilaw_blog_options',
            $this->option_name,
            array($this, 'validate_options')
        );

        // Add sections for each tab
        
        // Layout Section
        add_settings_section(
            'layout_section',
            '', // No title, we'll handle this manually
            '__return_false', // No callback, we'll handle this manually
            'budhilaw-blog-options-layout'
        );

        // Add fields
        add_settings_field(
            'sidebar_position',
            esc_html__('Sidebar Position', 'budhilaw-blog'),
            array($this, 'render_sidebar_position_field'),
            'budhilaw-blog-options-layout',
            'layout_section'
        );
        
        add_settings_field(
            'disable_sidebar_singles',
            esc_html__('Disable Sidebar on Single Posts', 'budhilaw-blog'),
            array($this, 'render_disable_sidebar_singles_field'),
            'budhilaw-blog-options-layout',
            'layout_section'
        );
        
        add_settings_field(
            'disable_sidebar_slugs',
            esc_html__('Disable Sidebar on Specific Pages', 'budhilaw-blog'),
            array($this, 'render_disable_sidebar_field'),
            'budhilaw-blog-options-layout',
            'layout_section'
        );
        
        add_settings_field(
            'thumbnail_position',
            esc_html__('Post Thumbnail Position', 'budhilaw-blog'),
            array($this, 'render_thumbnail_position_field'),
            'budhilaw-blog-options-layout',
            'layout_section'
        );
        
        add_settings_field(
            'thumbnail_size',
            esc_html__('Post Thumbnail Size', 'budhilaw-blog'),
            array($this, 'render_thumbnail_size_field'),
            'budhilaw-blog-options-layout',
            'layout_section'
        );
        
        // Typography Section (for future use)
        add_settings_section(
            'typography_section',
            '', // No title, we'll handle this manually
            '__return_false', // No callback, we'll handle this manually
            'budhilaw-blog-options-typography'
        );
        
        // Colors Section (for future use)
        add_settings_section(
            'colors_section',
            '', // No title, we'll handle this manually
            '__return_false', // No callback, we'll handle this manually
            'budhilaw-blog-options-colors'
        );
    }

    /**
     * Render sidebar position field
     */
    public function render_sidebar_position_field() {
        $options = $this->get_options();
        $sidebar_position = $options['sidebar_position'];
        ?>
        <div class="sidebar-position-options">
            <label class="sidebar-position-option">
                <input type="radio" name="<?php echo esc_attr($this->option_name); ?>[sidebar_position]" value="right" <?php checked($sidebar_position, 'right'); ?>>
                <span class="sidebar-layout-preview right-sidebar">
                    <span class="layout-content"></span>
                    <span class="layout-sidebar"></span>
                </span>
                <span class="layout-label"><?php esc_html_e('Right Sidebar', 'budhilaw-blog'); ?></span>
            </label>
            
            <label class="sidebar-position-option">
                <input type="radio" name="<?php echo esc_attr($this->option_name); ?>[sidebar_position]" value="left" <?php checked($sidebar_position, 'left'); ?>>
                <span class="sidebar-layout-preview left-sidebar">
                    <span class="layout-sidebar"></span>
                    <span class="layout-content"></span>
                </span>
                <span class="layout-label"><?php esc_html_e('Left Sidebar', 'budhilaw-blog'); ?></span>
            </label>
        </div>
        <p class="description"><?php esc_html_e('Choose the position of the sidebar in your theme layout.', 'budhilaw-blog'); ?></p>
        <?php
    }

    /**
     * Render disable sidebar field
     */
    public function render_disable_sidebar_field() {
        $options = $this->get_options();
        $value = isset($options['disable_sidebar_slugs']) ? $options['disable_sidebar_slugs'] : '';
        ?>
        <div class="disable-sidebar-field">
            <textarea name="budhilaw_blog_options[disable_sidebar_slugs]" id="disable_sidebar_slugs" rows="4" class="large-text code" placeholder="about-me
contact
privacy-policy"><?php echo esc_textarea($value); ?></textarea>
            <p class="description">
                <?php _e('Enter the slugs of pages where you want to disable the sidebar, one per line.', 'budhilaw-blog'); ?>
                <br>
                <?php _e('For example, to disable sidebar on your About page with URL <code>https://example.com/about-me/</code>, enter <code>about-me</code>.', 'budhilaw-blog'); ?>
            </p>
            <div class="sidebar-preview">
                <div class="preview-label"><?php _e('Preview:', 'budhilaw-blog'); ?></div>
                <div class="preview-container">
                    <div class="preview-content"></div>
                    <div class="preview-sidebar"></div>
                </div>
                <div class="preview-description">
                    <?php _e('When you disable the sidebar for a page, the content will expand to use the full width of the container.', 'budhilaw-blog'); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render field for disabling sidebar on single posts
     */
    public function render_disable_sidebar_singles_field() {
        $options = $this->get_options();
        $disabled = isset($options['disable_sidebar_singles']) ? $options['disable_sidebar_singles'] : false;
        ?>
        <div class="disable-sidebar-singles-field">
            <label>
                <input type="checkbox" name="<?php echo esc_attr($this->option_name); ?>[disable_sidebar_singles]" value="1" <?php checked($disabled, true); ?>>
                <?php esc_html_e('Disable sidebar on all single posts', 'budhilaw-blog'); ?>
            </label>
            <p class="description">
                <?php esc_html_e('Check this option to hide the sidebar on all individual post pages.', 'budhilaw-blog'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Render thumbnail position field
     */
    public function render_thumbnail_position_field() {
        $options = $this->get_options();
        $position = isset($options['thumbnail_position']) ? $options['thumbnail_position'] : 'top';
        ?>
        <div class="thumbnail-position-options">
            <label class="thumbnail-position-option">
                <input type="radio" name="<?php echo esc_attr($this->option_name); ?>[thumbnail_position]" value="top" <?php checked($position, 'top'); ?>>
                <span class="thumbnail-layout-preview top-thumbnail">
                    <span class="layout-thumbnail"></span>
                    <span class="layout-excerpt"></span>
                </span>
                <span class="layout-label"><?php esc_html_e('Above Content', 'budhilaw-blog'); ?></span>
            </label>
            
            <label class="thumbnail-position-option">
                <input type="radio" name="<?php echo esc_attr($this->option_name); ?>[thumbnail_position]" value="beside" <?php checked($position, 'beside'); ?>>
                <span class="thumbnail-layout-preview beside-thumbnail">
                    <span class="layout-thumbnail"></span>
                    <span class="layout-excerpt"></span>
                </span>
                <span class="layout-label"><?php esc_html_e('Beside Content', 'budhilaw-blog'); ?></span>
            </label>
        </div>
        <p class="description"><?php esc_html_e('Choose the position of the post thumbnail in blog/archive pages.', 'budhilaw-blog'); ?></p>
        <?php
    }
    
    /**
     * Render thumbnail size field
     */
    public function render_thumbnail_size_field() {
        $options = $this->get_options();
        $size = isset($options['thumbnail_size']) ? $options['thumbnail_size'] : 'medium';
        ?>
        <select name="<?php echo esc_attr($this->option_name); ?>[thumbnail_size]">
            <option value="thumbnail" <?php selected($size, 'thumbnail'); ?>><?php esc_html_e('Small (150x150)', 'budhilaw-blog'); ?></option>
            <option value="medium" <?php selected($size, 'medium'); ?>><?php esc_html_e('Medium (300x300)', 'budhilaw-blog'); ?></option>
            <option value="medium_large" <?php selected($size, 'medium_large'); ?>><?php esc_html_e('Medium Large (768x0)', 'budhilaw-blog'); ?></option>
            <option value="large" <?php selected($size, 'large'); ?>><?php esc_html_e('Large (1024x1024)', 'budhilaw-blog'); ?></option>
            <option value="post-thumbnail" <?php selected($size, 'post-thumbnail'); ?>><?php esc_html_e('Post Thumbnail (1200x675)', 'budhilaw-blog'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Select the size for post thumbnails in blog/archive pages.', 'budhilaw-blog'); ?></p>
        <?php
    }

    /**
     * Render options page
     */
    public function render_options_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Set active tab
        $this->active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'layout';

        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'budhilaw_blog_options_messages',
                'budhilaw_blog_options_updated',
                esc_html__('Settings saved successfully.', 'budhilaw-blog'),
                'updated'
            );
        }
        ?>
        <div class="wrap theme-options-wrap">
            <div class="theme-options-header">
                <h1><?php esc_html_e('Theme Options', 'budhilaw-blog'); ?></h1>
                <p class="theme-options-description"><?php esc_html_e('Configure your theme settings to customize the appearance and functionality of your website.', 'budhilaw-blog'); ?></p>
            </div>
            
            <?php settings_errors('budhilaw_blog_options_messages'); ?>
            
            <h2 class="nav-tab-wrapper">
                <a href="?page=budhilaw-blog-options&tab=layout" class="nav-tab <?php echo $this->active_tab === 'layout' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-layout"></span>
                    <?php esc_html_e('Layout', 'budhilaw-blog'); ?>
                </a>
                <a href="?page=budhilaw-blog-options&tab=typography" class="nav-tab <?php echo $this->active_tab === 'typography' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-editor-textcolor"></span>
                    <?php esc_html_e('Typography', 'budhilaw-blog'); ?>
                </a>
                <a href="?page=budhilaw-blog-options&tab=colors" class="nav-tab <?php echo $this->active_tab === 'colors' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-admin-appearance"></span>
                    <?php esc_html_e('Colors', 'budhilaw-blog'); ?>
                </a>
            </h2>
            
            <form method="post" action="options.php">
                <?php settings_fields('budhilaw_blog_options'); ?>
                
                <?php if ($this->active_tab === 'layout') : ?>
                    <div class="theme-options-section">
                        <h2><?php esc_html_e('Layout Options', 'budhilaw-blog'); ?></h2>
                        <p class="section-description"><?php esc_html_e('Customize the layout of your website.', 'budhilaw-blog'); ?></p>
                        
                        <div class="option-group">
                            <h3 class="option-title"><?php esc_html_e('Sidebar Position', 'budhilaw-blog'); ?></h3>
                            <div class="option-control">
                                <?php $this->render_sidebar_position_field(); ?>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <h3 class="option-title"><?php esc_html_e('Disable Sidebar on Single Posts', 'budhilaw-blog'); ?></h3>
                            <div class="option-control">
                                <?php $this->render_disable_sidebar_singles_field(); ?>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <h3 class="option-title"><?php esc_html_e('Disable Sidebar on Specific Pages', 'budhilaw-blog'); ?></h3>
                            <div class="option-control">
                                <?php $this->render_disable_sidebar_field(); ?>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <h3 class="option-title"><?php esc_html_e('Post Thumbnail Position', 'budhilaw-blog'); ?></h3>
                            <div class="option-control">
                                <?php $this->render_thumbnail_position_field(); ?>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <h3 class="option-title"><?php esc_html_e('Post Thumbnail Size', 'budhilaw-blog'); ?></h3>
                            <div class="option-control">
                                <?php $this->render_thumbnail_size_field(); ?>
                            </div>
                        </div>
                    </div>
                <?php elseif ($this->active_tab === 'typography') : ?>
                    <div class="theme-options-section">
                        <h2><?php esc_html_e('Typography Options', 'budhilaw-blog'); ?></h2>
                        <p class="section-description"><?php esc_html_e('Customize the fonts and typography of your website.', 'budhilaw-blog'); ?></p>
                        
                        <div class="option-placeholder">
                            <p><?php esc_html_e('Typography options will be available in future updates.', 'budhilaw-blog'); ?></p>
                        </div>
                    </div>
                <?php elseif ($this->active_tab === 'colors') : ?>
                    <div class="theme-options-section">
                        <h2><?php esc_html_e('Color Options', 'budhilaw-blog'); ?></h2>
                        <p class="section-description"><?php esc_html_e('Customize the colors of your website.', 'budhilaw-blog'); ?></p>
                        
                        <div class="option-placeholder">
                            <p><?php esc_html_e('Color options will be available in future updates.', 'budhilaw-blog'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($this->active_tab === 'layout') : ?>
                    <?php submit_button(esc_html__('Save Settings', 'budhilaw-blog'), 'primary theme-options-save'); ?>
                <?php endif; ?>
            </form>
            
            <div class="theme-options-footer">
                <p>
                    <?php 
                    printf(
                        /* translators: %s: Theme name */
                        esc_html__('Thank you for using %s theme!', 'budhilaw-blog'),
                        '<strong>Budhilaw Blog</strong>'
                    ); 
                    ?>
                </p>
                <div class="theme-version">
                    <span><?php printf(esc_html__('Version %s', 'budhilaw-blog'), BUDHILAW_BLOG_VERSION); ?></span>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Validate options
     *
     * @param array $input Options to validate
     * @return array Validated options
     */
    public function validate_options($input) {
        $validated = array();

        if (isset($input['sidebar_position']) && in_array($input['sidebar_position'], array('right', 'left'))) {
            $validated['sidebar_position'] = $input['sidebar_position'];
        } else {
            $validated['sidebar_position'] = $this->default_options['sidebar_position'];
        }
        
        // Validate disable sidebar on singles option
        $validated['disable_sidebar_singles'] = isset($input['disable_sidebar_singles']) ? true : false;
        
        if (isset($input['disable_sidebar_slugs'])) {
            // Sanitize by converting to array, trimming each line, then back to string
            $slugs = explode("\n", $input['disable_sidebar_slugs']);
            $sanitized_slugs = array();
            
            foreach ($slugs as $slug) {
                $slug = trim($slug);
                if (!empty($slug)) {
                    $sanitized_slugs[] = sanitize_title($slug);
                }
            }
            
            $validated['disable_sidebar_slugs'] = implode("\n", $sanitized_slugs);
        } else {
            $validated['disable_sidebar_slugs'] = '';
        }
        
        // Validate thumbnail position
        if (isset($input['thumbnail_position']) && in_array($input['thumbnail_position'], array('top', 'beside'))) {
            $validated['thumbnail_position'] = $input['thumbnail_position'];
        } else {
            $validated['thumbnail_position'] = $this->default_options['thumbnail_position'];
        }
        
        // Validate thumbnail size
        $valid_sizes = array('thumbnail', 'medium', 'medium_large', 'large', 'post-thumbnail');
        if (isset($input['thumbnail_size']) && in_array($input['thumbnail_size'], $valid_sizes)) {
            $validated['thumbnail_size'] = $input['thumbnail_size'];
        } else {
            $validated['thumbnail_size'] = $this->default_options['thumbnail_size'];
        }

        return $validated;
    }

    /**
     * Get all theme options
     *
     * @return array All theme options
     */
    public function get_options() {
        if (isset($this->options)) {
            return $this->options;
        }
        
        return get_option('budhilaw_blog_options', $this->default_options);
    }

    /**
     * Check if sidebar should be disabled for current page
     *
     * @return bool Whether sidebar should be disabled
     */
    public function is_sidebar_disabled() {
        $options = $this->get_options();
        
        // Check if we should disable sidebar on all single posts
        if (isset($options['disable_sidebar_singles']) && $options['disable_sidebar_singles'] && is_single()) {
            return true;
        }
        
        // Check if we should disable sidebar based on slug
        $disable_slugs = isset($options['disable_sidebar_slugs']) ? $options['disable_sidebar_slugs'] : '';
        
        if (empty($disable_slugs)) {
            return false;
        }
        
        // Get current page slug
        global $post;
        if (!$post) {
            return false;
        }
        
        $current_slug = $post->post_name;
        $disable_slugs_array = array_map('trim', explode("\n", $disable_slugs));
        
        return in_array($current_slug, $disable_slugs_array);
    }

    /**
     * Add sidebar position class to body
     *
     * @param array $classes Body classes
     * @return array Updated body classes
     */
    public function sidebar_position_body_class($classes) {
        $options = $this->get_options();
        
        // Add sidebar position class
        if (isset($options['sidebar_position'])) {
            $classes[] = 'sidebar-' . esc_attr($options['sidebar_position']);
        }
        
        // Add no-sidebar class if sidebar is disabled for this page
        if ($this->is_sidebar_disabled()) {
            $classes[] = 'no-sidebar';
        }
        
        return $classes;
    }

    /**
     * Enqueue admin scripts and styles for the theme options page
     *
     * @param string $hook_suffix The current admin page
     */
    public function enqueue_admin_scripts($hook_suffix) {
        if ('toplevel_page_budhilaw-blog-options' !== $hook_suffix) {
            return;
        }
        
        // Enqueue styles
        wp_enqueue_style(
            'budhilaw-blog-admin-style',
            get_template_directory_uri() . '/assets/css/admin-style.css',
            array(),
            BUDHILAW_BLOG_VERSION
        );
    }
}

// Initialize the theme options
$budhilaw_blog_theme_options = new Budhilaw_Blog_Theme_Options();

/**
 * Helper function to get theme options
 *
 * @return array Theme options
 */
function budhilaw_blog_get_options() {
    global $budhilaw_blog_theme_options;
    return $budhilaw_blog_theme_options->get_options();
} 