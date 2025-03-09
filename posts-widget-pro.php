<?php
/**
 * Plugin Name: Posts Widget Pro
 * Plugin URI: https://example.com/posts-widget-pro
 * Description: A powerful widget to display recent posts with advanced sorting, filtering, and display options.
 * Version: 1.0.0
 * Author: Sazid Kabir
 * Author URI: https://github.com/sazid1007
 * Text Domain: posts-widget-pro
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('POSTS_WIDGET_PRO_VERSION', '1.0.0');
define('POSTS_WIDGET_PRO_PATH', plugin_dir_path(__FILE__));
define('POSTS_WIDGET_PRO_URL', plugin_dir_url(__FILE__));

/**
 * Load plugin text domain for translations
 */
function posts_widget_pro_load_textdomain() {
    load_plugin_textdomain('posts-widget-pro', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'posts_widget_pro_load_textdomain');

/**
 * Enqueue plugin styles
 */
function posts_widget_pro_enqueue_styles() {
    wp_enqueue_style(
        'posts-widget-pro-styles',
        POSTS_WIDGET_PRO_URL . 'posts-widget-pro.css',
        array(),
        POSTS_WIDGET_PRO_VERSION
    );
}
add_action('wp_enqueue_scripts', 'posts_widget_pro_enqueue_styles');

/**
 * Include the widget class
 */
require_once POSTS_WIDGET_PRO_PATH . 'class-posts-widget-pro.php';

/**
 * Register the widget
 */
function posts_widget_pro_register_widget() {
    register_widget('Posts_Widget_Pro');
}
add_action('widgets_init', 'posts_widget_pro_register_widget');

