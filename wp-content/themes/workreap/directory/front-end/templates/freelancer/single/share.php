<?php
/**
 *
 * The template used for displaying freelancer Share
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;

$freelancer_avatar = apply_filters(
		'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $post_id ), array( 'width' => 225, 'height' => 225 )
	);
if (function_exists('workreap_prepare_project_social_sharing')) {
	workreap_prepare_project_social_sharing(false, esc_html__('Share this freelancer', 'workreap'), 'true', '', $freelancer_avatar);
}