# Budhilaw Blog Widgets

This directory contains modular WordPress widgets for the Budhilaw Blog theme.

## Structure

- `widget-loader.php` - Central file for registering and loading all widgets
- Individual widget files (one per widget)

## CSS Structure

Each widget has its own dedicated CSS file stored in `/assets/css/` directory. The CSS is only loaded when the widget is active in a sidebar.

## Adding a New Widget

1. Create a new PHP file for your widget class in this directory
2. Create a dedicated CSS file in `/assets/css/` directory
3. Register the widget in `widget-loader.php`
4. Ensure your widget class has an `enqueue_styles()` method that loads its CSS only when active

## Existing Widgets

- **Popular Articles Widget** (`popular-articles-widget.php`) - Displays popular or recent posts with options for customization

## Theme Integration

These widgets are loaded automatically by the theme through `functions.php`. 