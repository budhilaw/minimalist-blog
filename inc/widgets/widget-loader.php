<?php
/**
 * Widget loader for Budhilaw Blog theme
 *
 * @package Budhilaw_Blog
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load all widget files
 */
require get_template_directory() . '/inc/widgets/popular-articles-widget.php';
// Add more widget includes here as they are created

/**
 * Register all widgets
 */
function budhilaw_blog_register_widgets() {
    register_widget( 'Budhilaw_Blog_Popular_Articles_Widget' );
    // Register other widgets here
}
add_action( 'widgets_init', 'budhilaw_blog_register_widgets' ); 