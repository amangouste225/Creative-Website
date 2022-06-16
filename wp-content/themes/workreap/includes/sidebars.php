<?php

/**
 *
 * Sidebar Resgister
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */

/**
 * @Register widget area.
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if (!function_exists('workreap_widgets_init')) {

    function workreap_widgets_init() {
        register_sidebar(array(
            'name' 			=> esc_html__('Sidebar', 'workreap'),
            'id' 			=> 'sidebar-1',
            'description' 	=> esc_html__('Default sidebar for the home and archive pages.', 'workreap'),
            'before_widget' => '<div id="%1$s" class="wt-widget %2$s">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<div class="wt-widgettitle"><h2>',
            'after_title' 	=> '</h2></div>',
        ));
		
		register_sidebar(array(
            'name' 			=> esc_html__('Dashboard Sidebar', 'workreap'),
            'id' 			=> 'sidebar-dashboard',
            'description' 	=> esc_html__('Default sidebar for the dashboard pages.', 'workreap'),
            'before_widget' => '<div id="%1$s" class="wt-widget %2$s">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<div class="wt-widgettitle"><h2>',
            'after_title' 	=> '</h2></div>',
        ));

        register_sidebar(array(
            'name' 			=> esc_html__('Footer Sidebar 1', 'workreap'),
            'id' 			=> 'sidebar-footer-1',
            'description' 	=> esc_html__('Sidebar for footer.', 'workreap'),
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<div class="wt-fwidgettitle"><h3>',
            'after_title' 	=> '</h3></div>',
        ));

        register_sidebar(array(
            'name' 			=> esc_html__('Footer Sidebar 2', 'workreap'),
            'id' 			=> 'sidebar-footer-2',
            'description' 	=> esc_html__('Sidebar for footer.', 'workreap'),
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<div class="wt-fwidgettitle"><h3>',
            'after_title' 	=> '</h3></div>',
        ));
    }

    add_action('widgets_init', 'workreap_widgets_init');
}