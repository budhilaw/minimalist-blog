<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Budhilaw_Blog
 */

// Check if sidebar should be disabled by slug
global $budhilaw_blog_theme_options;
if (isset($budhilaw_blog_theme_options) && method_exists($budhilaw_blog_theme_options, 'is_sidebar_disabled') && $budhilaw_blog_theme_options->is_sidebar_disabled()) {
    // Add the no-sidebar class via the body class filter (already done in theme options class)
    return;
}

// Check if sidebar is active (has widgets)
$has_widgets = is_active_sidebar('sidebar-1');

// If sidebar is not active, add a class to the body tag
if (!$has_widgets) {
    add_filter('body_class', function($classes) {
        $classes[] = 'no-sidebar';
        return $classes;
    });
    return; // Don't output the sidebar at all
}

// Output the sidebar with active widgets
?>
<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside><!-- #secondary -->

