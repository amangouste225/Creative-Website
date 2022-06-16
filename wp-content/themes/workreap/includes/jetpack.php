<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package Charity
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
if (!function_exists('workreap_jetpack_setup')) {
	function workreap_jetpack_setup() {
		add_theme_support( 'infinite-scroll', array(
			'container' => 'main',
			'render'    => 'workreap_infinite_scroll_render',
			'footer'    => 'page',
		) );
	} // end function workreap_jetpack_setup
	add_action( 'after_setup_theme', 'workreap_jetpack_setup' );
}

if (!function_exists('workreap_infinite_scroll_render')) {
	function workreap_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', get_post_format() );
		}
	} // end function workreap_infinite_scroll_render
}