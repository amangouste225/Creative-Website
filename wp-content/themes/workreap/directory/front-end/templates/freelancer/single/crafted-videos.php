<?php
/**
 *
 * The template used for displaying freelancer Crafted Projects
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 		= $post->ID;
$cr_video_limit	= 4;
$videos		= array();
if (function_exists('fw_get_db_post_option')) {
	$videos 		= fw_get_db_post_option($post_id, 'videos', true);
}

if( !empty( $videos ) && is_array( $videos ) ){?>
	<div class="wt-videos wt-craftedprojects">
		<div class="wt-usertitle">
			<h2><?php esc_html_e('Videos','workreap');?></h2>
		</div>
		<div class="wt-videos-wrap">
			<?php 
				$total_videos	= !empty($videos) ? count(array_filter($videos)) : 0;
				$count_item		= 0;
				foreach( $videos as $key => $media ){
					if( !empty( $media ) ){
						$count_item ++;
						$item_show	= !empty($count_item) && intval($count_item) > $cr_video_limit ? 'd-none' : "";
					?>
					<div class="wt-video-list <?php echo do_shortcode( $item_show );?>">
						<?php
							$media_url  = parse_url($media);
							$height 	= 210;
							$width 		= 370;

							$url = parse_url($media);
							if ( isset( $url['host'] ) && ( $url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com' ) ) {
								echo '<div class="sp-videos-frame">';
								$content_exp = explode("/", $media);
								$content_vimo = array_pop($content_exp);
								echo '<iframe width="' . intval($width) . '" height="' . intval($height) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
	></iframe>';
								echo '</div>';
							} elseif ( isset( $url['host'] ) && $url['host'] == 'soundcloud.com') {
								$video = wp_oembed_get($media, array('height' => intval($height)));
								$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
								echo '<div class="audio">';
								$video = str_replace($search, '', $video);
								echo str_replace('&', '&amp;', $video);
								echo '</div>';
							} else {
								echo '<div class="sp-videos-frame">';
								echo do_shortcode('[video width="' . intval($width) . '" height="' . intval($height) . '" src="' . esc_url($media) . '"][/video]');
								echo '</div>';
							}
						?>
					</div>
			<?php }} 
			if( intval($total_videos) > $cr_video_limit ){?>
				<div class="wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-loadmore-videos"><?php esc_html_e('Load More','workreap');?></a>
				</div>
			<?php }?>
		</div>
	</div>
<?php
}