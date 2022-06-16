<?php

/**
 *
 * @package   AndroidApp Core
 * @author    amentotech
 * @link      https://codecanyon.net/user/amentotech/portfolio
 * @since 1.0
 */

function android_get_video_data($video_url){
	if( !empty( $video_url ) ) {
		$height = 300;
		$width  = 450;
		$post_video = $video_url;
		$url = parse_url( $post_video );
		$videodata	= '';
		if (isset($url['host']) && ( $url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com')) {
			$content_exp = explode("/", $post_video);
			$content_vimo = array_pop($content_exp);
			$videodata .= '<iframe width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
></iframe>';
		} elseif (isset($url['host']) && $url['host'] == 'soundcloud.com') {
			$video = wp_oembed_get($post_video, array('height' => $height));
			$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
			$video = str_replace($search, '', $video);
			$videodata .= str_replace('&', '&amp;', $video);
		} else {
			$content = str_replace(array('watch?v=', 'http://www.dailymotion.com/'), array('embed/', '//www.dailymotion.com/embed/'), $post_video);
			$videodata .= '<iframe width="' . $width . '" height="' . $height . '" src="' . $content . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}
		
		return $videodata;
	}
}