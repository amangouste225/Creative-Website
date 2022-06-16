<?php
/**
 * Elementor Page builder base
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap
 *
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die('No kiddies please!');
}

/**
 * @prepare Custom taxonomies array
 * @return array
 */
function elementor_get_taxonomies($post_type = 'post', $taxonomy = 'category', $hide_empty = 0, $dataType = 'input') {
	$args = array(
		'type' 			=> $post_type,
		'child_of'  	=> 0,
		'parent' 		=> '',
		'hide_empty' 	=> $hide_empty,
		'hierarchical' 	=> 1,
		'exclude' 		=> '',
		'include' 		=> '',
		'number' 		=> '',
		'taxonomy' 		=> $taxonomy,
		'pad_counts' 	=> false
	);

	$categories = get_categories($args);

	if ($dataType == 'array') {
		return $categories;
	}

	$custom_Cats = array();

	if (isset($categories) && !empty($categories)) {
		foreach ($categories as $key => $value) {
			$custom_Cats[$value->term_id] = $value->name;
		}
	}

	return $custom_Cats;
} 

/**
 * @prepare Custom taxonomies array
 * @return array
 */
function elementor_get_posts($post_type = 'post', $dataType = 'input') {

	$args = array(
        'numberposts'      => -1,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => $post_type,
        'suppress_filters' => true,
    );

	$posts = get_posts($args);

	if ($dataType == 'array') {
		return $posts;
	}

	$custom_posts = array();

	if (isset($posts) && !empty($posts)) {
		foreach ($posts as $post) {
			setup_postdata( $post );
			global $post;
			$custom_posts[$post->ID] = $post->post_title;
		}
	}

	return $custom_posts;
} 

/**
 * @prepare Social links array
 * @return array
 */
function workreap_social_profile () {
	$social_profile = array ( 
			'facebook_link'	=> array (
								'class'	=> 'wt-facebook',
								'icon'	=> 'fa fa-facebook-f',
								'lable' => esc_html__('Facebook','workreap_core'),
							),
			'twitter_link'	=> array (
								'class'	=> 'wt-twitter',
								'icon'	=> 'fa fa-twitter',
								'lable' => esc_html__('Twitter','workreap_core'),
							),
			'linkedin_link'	=> array (
								'class'	=> 'wt-linkedin',
								'icon'	=> 'fa fa-linkedin',
								'lable' => esc_html__('LinkedIn','workreap_core'),
							),
			'googleplus_link'=> array (
								'class'	=> 'wt-googleplus',
								'icon'	=> 'fa fa-google',
								'lable' => esc_html__('Google Plus','workreap_core'),
							),
			'instagram_link'=> array (
								'class'	=> 'wt-instagram',
								'icon'	=> 'fa fa-instagram',
								'lable' => esc_html__('Instagram','workreap_core'),
							),
			'youtube_link'=> array (
								'class'	=> 'wt-youtube',
								'icon'	=> 'fa fa-youtube',
								'lable' => esc_html__('Google Plus','workreap_core'),
							),

			);
	return $social_profile;
}