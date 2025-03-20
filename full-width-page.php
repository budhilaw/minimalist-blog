<?php
/**
 * Template Name: Full Width Page
 * Template Post Type: page
 *
 * A custom page template for full width pages without sidebar.
 *
 * @package Minimalist Blog
 */

get_header();
?>

<div id="primary" class="content-area full-width">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', 'page' );

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); 