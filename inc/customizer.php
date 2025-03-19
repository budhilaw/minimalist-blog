<?php
/**
 * Budhilaw Blog Theme Customizer
 *
 * @package Budhilaw_Blog
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function budhilaw_blog_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'budhilaw_blog_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'budhilaw_blog_customize_partial_blogdescription',
            )
        );
    }

    // Add section for social media links
    $wp_customize->add_section(
        'budhilaw_blog_social_links',
        array(
            'title'    => __( 'Social Media Links', 'budhilaw-blog' ),
            'priority' => 120,
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'budhilaw_blog_twitter_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'budhilaw_blog_twitter_link',
        array(
            'label'   => __( 'Twitter URL', 'budhilaw-blog' ),
            'section' => 'budhilaw_blog_social_links',
            'type'    => 'url',
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'budhilaw_blog_facebook_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'budhilaw_blog_facebook_link',
        array(
            'label'   => __( 'Facebook URL', 'budhilaw-blog' ),
            'section' => 'budhilaw_blog_social_links',
            'type'    => 'url',
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'budhilaw_blog_instagram_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'budhilaw_blog_instagram_link',
        array(
            'label'   => __( 'Instagram URL', 'budhilaw-blog' ),
            'section' => 'budhilaw_blog_social_links',
            'type'    => 'url',
        )
    );

    // GitHub
    $wp_customize->add_setting(
        'budhilaw_blog_github_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'budhilaw_blog_github_link',
        array(
            'label'   => __( 'GitHub URL', 'budhilaw-blog' ),
            'section' => 'budhilaw_blog_social_links',
            'type'    => 'url',
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'budhilaw_blog_linkedin_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'budhilaw_blog_linkedin_link',
        array(
            'label'   => __( 'LinkedIn URL', 'budhilaw-blog' ),
            'section' => 'budhilaw_blog_social_links',
            'type'    => 'url',
        )
    );

    // Add section for footer settings
    $wp_customize->add_section(
        'budhilaw_blog_footer_settings',
        array(
            'title'    => __( 'Footer Settings', 'budhilaw-blog' ),
            'priority' => 130,
        )
    );

    // Footer text
    $wp_customize->add_setting(
        'budhilaw_blog_footer_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'budhilaw_blog_footer_text',
        array(
            'label'   => __( 'Footer Text', 'budhilaw-blog' ),
            'section' => 'budhilaw_blog_footer_settings',
            'type'    => 'textarea',
        )
    );
}
add_action( 'customize_register', 'budhilaw_blog_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function budhilaw_blog_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function budhilaw_blog_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function budhilaw_blog_customize_preview_js() {
    wp_enqueue_script( 'budhilaw-blog-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), BUDHILAW_BLOG_VERSION, true );
}
add_action( 'customize_preview_init', 'budhilaw_blog_customize_preview_js' );

