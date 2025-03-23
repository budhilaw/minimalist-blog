<?php
/**
 * The template for displaying the footer
 *
 * @package Budhilaw_Blog
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-widgets">
                <div class="footer-widget">
                    <h3 class="footer-widget-title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h3>
                    <div class="footer-widget-content">
                        <p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
                        <p>A modern, minimalist blog focused on sharing insights about web development, design, and technology with a clean, elegant approach.</p>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>
                            </a>
                            <a href="#" class="social-link" aria-label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                            <a href="#" class="social-link" aria-label="GitHub">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                            </a>
                            <a href="#" class="social-link" aria-label="LinkedIn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="footer-widget">
                    <h3 class="footer-widget-title"><?php esc_html_e( 'Categories', 'budhilaw-blog' ); ?></h3>
                    <div class="footer-widget-content">
                        <ul class="footer-menu">
                            <?php
                            $categories = get_categories( array(
                                'orderby' => 'name',
                                'order'   => 'ASC',
                                'number'  => 5,
                            ) );
                            
                            foreach ( $categories as $category ) {
                                printf(
                                    '<li><a href="%1$s">%2$s</a></li>',
                                    esc_url( get_category_link( $category->term_id ) ),
                                    esc_html( $category->name )
                                );
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                
                <div class="footer-widget">
                    <h3 class="footer-widget-title"><?php esc_html_e( 'Quick Links', 'budhilaw-blog' ); ?></h3>
                    <div class="footer-widget-content">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer-1',
                                'menu_class'     => 'footer-menu',
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            )
                        );
                        ?>
                    </div>
                </div>
                
                <div class="footer-widget">
                    <h3 class="footer-widget-title"><?php esc_html_e( 'Subscribe', 'budhilaw-blog' ); ?></h3>
                    <div class="footer-widget-content">
                        <p><?php esc_html_e( 'Stay updated with our latest articles and news.', 'budhilaw-blog' ); ?></p>
                        <form class="footer-form">
                            <input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'budhilaw-blog' ); ?>" required>
                            <button type="submit"><?php esc_html_e( 'Subscribe', 'budhilaw-blog' ); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-copyright">
                    <?php
                    $options = budhilaw_blog_get_options();
                    $custom_copyright = isset($options['footer_copyright']) ? $options['footer_copyright'] : '';
                    
                    if (!empty($custom_copyright)) {
                        echo wp_kses_post($custom_copyright);
                    } else {
                        ?>
                        <p>
                            &copy; <?php echo esc_html(date('Y')); ?> 
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>. 
                            <?php esc_html_e('All rights reserved.', 'budhilaw-blog'); ?>
                        </p>
                        <?php
                    }
                    ?>
                </div>
                <div class="footer-back-to-top">
                    <a href="#page" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'budhilaw-blog'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

