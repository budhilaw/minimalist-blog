<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Budhilaw_Blog
 */

// Get theme options to check sidebar position
global $budhilaw_blog_theme_options;
$sidebar_position = 'right'; // Default position
if (isset($budhilaw_blog_theme_options) && method_exists($budhilaw_blog_theme_options, 'get_options')) {
    $options = $budhilaw_blog_theme_options->get_options();
    if (isset($options['sidebar_position']) && $options['sidebar_position'] === 'none') {
        // Don't display sidebar at all if "Without Sidebar" is selected
        return;
    }
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

