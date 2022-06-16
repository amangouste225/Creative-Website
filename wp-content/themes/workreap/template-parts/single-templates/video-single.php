<?php
/**
 *
 * The template used for displaying audio post formate
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$post_video;

$url 	= parse_url( $post_video );
$height = intval(400);
$width  = intval(1140);

if ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com') {
	echo '<figure class="wt-classimg wt-media-single">';
	$content_exp  = explode("/" , $post_video);
	$content_vimo = array_pop($content_exp);
	echo '<iframe width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
></iframe>';
	echo '</figure>';
} elseif ($url['host'] == 'soundcloud.com') {
	$video  = wp_oembed_get($post_video , array (
		'height' => $height ));
	$search = array (
		'webkitallowfullscreen' ,
		'mozallowfullscreen' ,
		'frameborder="0"' );
	echo '<figure class="wt-classimg wt-media-single">';
	echo str_replace($search , '' , $video);
	echo '</figure>';
} else {
	echo '<figure class="wt-classimg wt-media-single">';
	$content = str_replace(array (
		'watch?v=' ,
		'http://www.dailymotion.com/' ) , array (
		'embed/' ,
		'//www.dailymotion.com/embed/' ) , $post_video);
	echo '<iframe width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" src="' . esc_url( $content ) . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
	echo '</figure>';
}