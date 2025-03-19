<?php
/**
 * Budhilaw Blog functions and definitions
 *
 * @package Budhilaw_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define theme version
define( 'BUDHILAW_BLOG_VERSION', '1.0.0' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function budhilaw_blog_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Set default thumbnail size
    set_post_thumbnail_size( 1200, 675, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'budhilaw-blog' ),
        'footer-1' => esc_html__( 'Footer Menu 1', 'budhilaw-blog' ),
        'footer-2' => esc_html__( 'Footer Menu 2', 'budhilaw-blog' ),
    ) );

    // Switch default core markup to output valid HTML5.
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );

    // Add support for responsive embeds.
    add_theme_support( 'responsive-embeds' );

    // Add support for custom logo.
    add_theme_support( 'custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ) );
}
add_action( 'after_setup_theme', 'budhilaw_blog_setup' );

/**
 * Register widget areas.
 */
function budhilaw_blog_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'budhilaw-blog' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'budhilaw-blog' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'budhilaw-blog' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here to appear in the first footer column.', 'budhilaw-blog' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'budhilaw-blog' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here to appear in the second footer column.', 'budhilaw-blog' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'budhilaw-blog' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here to appear in the third footer column.', 'budhilaw-blog' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 4', 'budhilaw-blog' ),
        'id'            => 'footer-4',
        'description'   => esc_html__( 'Add widgets here to appear in the fourth footer column.', 'budhilaw-blog' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'budhilaw_blog_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function budhilaw_blog_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style( 'budhilaw-blog-style', get_stylesheet_uri(), array(), BUDHILAW_BLOG_VERSION );
    
    // Enqueue theme JavaScript
    wp_enqueue_script( 'budhilaw-blog-navigation', get_template_directory_uri() . '/js/navigation.js', array(), BUDHILAW_BLOG_VERSION, true );
    wp_enqueue_script( 'budhilaw-blog-theme-toggle', get_template_directory_uri() . '/js/theme-toggle.js', array(), BUDHILAW_BLOG_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'budhilaw_blog_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom widgets for this theme.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load custom nav walker.
 */
require get_template_directory() . '/inc/class-budhilaw-blog-nav-walker.php';

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function budhilaw_blog_body_classes( $classes ) {
    // Add a class if there is a custom header.
    if ( has_header_image() ) {
        $classes[] = 'has-header-image';
    }

    // Add a class if there is a custom background.
    if ( get_background_image() || get_background_color() !== 'ffffff' ) {
        $classes[] = 'has-custom-background';
    }

    return $classes;
}
add_filter( 'body_class', 'budhilaw_blog_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function budhilaw_blog_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'budhilaw_blog_pingback_header' );

/**
 * Limit excerpt length.
 */
function budhilaw_blog_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'budhilaw_blog_excerpt_length' );

/**
 * Change excerpt more string.
 */
function budhilaw_blog_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'budhilaw_blog_excerpt_more' );

