<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Budhilaw_Blog
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    // If no active widgets in sidebar, let's create our custom widgets
    ?>
    <aside id="secondary" class="widget-area">
        <section class="widget profile-widget">
            <h2 class="widget-title"><?php esc_html_e( 'About Me', 'budhilaw-blog' ); ?></h2>
            <div class="widget-content">
                <div class="profile-avatar">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/profile-placeholder.jpg' ); ?>" alt="<?php esc_attr_e( 'Profile', 'budhilaw-blog' ); ?>">
                </div>
                <h3 class="profile-name"><?php bloginfo( 'name' ); ?></h3>
                <p class="profile-bio"><?php esc_html_e( 'Web developer, writer, and minimalist design enthusiast. Sharing thoughts on technology, design, and life.', 'budhilaw-blog' ); ?></p>
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>" class="read-more"><?php esc_html_e( 'Read more', 'budhilaw-blog' ); ?></a>
            </div>
        </section>

        <section class="widget">
            <h2 class="widget-title"><?php esc_html_e( 'Popular Articles', 'budhilaw-blog' ); ?></h2>
            <div class="widget-content">
                <ul class="popular-posts-list">
                    <?php
                    $popular_posts = new WP_Query( array(
                        'posts_per_page' => 3,
                        'meta_key' => 'post_views_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'ignore_sticky_posts' => 1
                    ) );

                    if ( $popular_posts->have_posts() ) :
                        while ( $popular_posts->have_posts() ) : $popular_posts->the_post();
                            ?>
                            <li class="popular-post-item">
                                <a href="<?php the_permalink(); ?>" class="popular-post-title"><?php the_title(); ?></a>
                                <div class="popular-post-date"><?php echo get_the_date(); ?></div>
                            </li>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Fallback if no popular posts
                        $recent_posts = new WP_Query( array(
                            'posts_per_page' => 3,
                            'ignore_sticky_posts' => 1
                        ) );

                        while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
                            ?>
                            <li class="popular-post-item">
                                <a href="<?php the_permalink(); ?>" class="popular-post-title"><?php the_title(); ?></a>
                                <div class="popular-post-date"><?php echo get_the_date(); ?></div>
                            </li>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </ul>
            </div>
        </section>

        <section class="widget">
            <h2 class="widget-title"><?php esc_html_e( 'Recent Comments', 'budhilaw-blog' ); ?></h2>
            <div class="widget-content recent-comments">
                <ul>
                    <?php
                    $recent_comments = get_comments( array(
                        'number' => 3,
                        'status' => 'approve',
                    ) );

                    if ( $recent_comments ) :
                        foreach ( $recent_comments as $comment ) :
                            $comment_author = $comment->comment_author;
                            $initials = '';
                            $words = explode(' ', $comment_author);
                            foreach ($words as $word) {
                                $initials .= strtoupper(substr($word, 0, 1));
                            }
                            ?>
                            <li>
                                <div class="comment-header">
                                    <div class="comment-avatar<?php echo get_avatar($comment) ? '' : ' no-avatar'; ?>">
                                        <?php if (get_avatar($comment)) : ?>
                                            <?php echo get_avatar($comment, 40); ?>
                                        <?php else : ?>
                                            <?php echo esc_html($initials); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-meta">
                                        <div class="comment-author-link"><?php echo esc_html($comment_author); ?></div>
                                        <time class="comment-timestamp">
                                            <?php echo esc_html(human_time_diff(strtotime($comment->comment_date), current_time('timestamp'))) . ' ' . __('ago', 'budhilaw-blog'); ?>
                                        </time>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <?php echo wp_kses_post(wp_trim_words($comment->comment_content, 10)); ?>
                                </div>
                            </li>
                            <?php
                        endforeach;
                    else :
                        echo '<li>' . esc_html__( 'No comments yet.', 'budhilaw-blog' ) . '</li>';
                    endif;
                    ?>
                </ul>
            </div>
        </section>
    </aside><!-- #secondary -->
    <?php
} else {
    ?>
    <aside id="secondary" class="widget-area">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </aside><!-- #secondary -->
    <?php
}

