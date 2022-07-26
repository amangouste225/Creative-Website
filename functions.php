<?php
/**
 * Custom functions and definitions
 *
 * @package Custom
 */

function load_stylesheets()
{
	wp_register_style('stylesheet', get_template_directory_uri() . '/css/styles.css
	', array(), false, 'all');
	wp_enqueue_style('stylesheet');
}

add_action('wp_enqueue_scripts', 'load_stylesheets');



function include_jquery()

{
	wp_deregister_script('jquery');

	wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery-3.6.0.min.js', '', 1 , true);
	add_action('wp_enqueue_scripts', 'jquery');
}


add_action('wp_enqueue_scripts', 'include_jquery');

function loadjs()
{
	wp_register_script('customjs', get_template_directory_uri() . '/js/index.js', '', 1, true);
	wp_enqueue_script('customjs');
}

add_action('wp_enqueue_scripts', 'loadjs');
