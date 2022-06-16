<?php
/**
 *
 * The template used for displaying freelancer post banner
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;

$freelancer_banner = apply_filters(
		'workreap_freelancer_banner_fallback', workreap_get_freelancer_banner( array( 'width' => 1920, 'height' => 400 ), $post_id ), array( 'width' => 1920, 'height' => 400 )
	);
?>
<div class="wt-haslayout wt-innerbannerholder frinnerbannerholder wt-innerbannerholdervtwo" style="background-image:url('<?php echo esc_url( $freelancer_banner );?>');">
</div>