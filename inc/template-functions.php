<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Budhilaw_Blog
 */

if ( ! function_exists( 'budhilaw_blog_pagination' ) ) :
    /**
     * Custom pagination function
     * 
     * @param array $args Optional arguments to customize the pagination.
     * @return void
     */
    function budhilaw_blog_pagination( $args = array() ) {
        global $wp_query;
        
        $defaults = array(
            'total'     => isset($args['total']) ? $args['total'] : (isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1),
            'current'   => max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page')),
            'show_all'  => false,
            'prev_text' => __( 'Previous', 'budhilaw-blog' ),
            'next_text' => __( 'Next', 'budhilaw-blog' ),
            'end_size'  => 1,
            'mid_size'  => 2,
            'type'      => 'list',
            'add_args'  => false,
        );

        $args = wp_parse_args( $args, $defaults );

        // Don't print empty markup if there's only one page.
        if ( $args['total'] <= 1 ) {
            return;
        }
        
        $links = paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $args['total'],
            'current'      => $args['current'],
            'format'       => '',
            'show_all'     => $args['show_all'],
            'prev_next'    => true,
            'prev_text'    => $args['prev_text'],
            'next_text'    => $args['next_text'],
            'type'         => $args['type'],
            'end_size'     => $args['end_size'],
            'mid_size'     => $args['mid_size'],
            'add_args'     => $args['add_args'],
        ) );
        
        if ( $links ) {
            echo '<nav class="navigation pagination" role="navigation" aria-label="Posts">';
            echo '<div class="nav-links">';
            echo $links;
            echo '</div>';
            echo '</nav>';
        }
    }
endif; 