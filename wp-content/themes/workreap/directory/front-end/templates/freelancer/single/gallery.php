<?php
/**
 *
 * The template used for displaying freelancer Gallery
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$images	= array();
if( function_exists('fw_get_db_post_option') ){
	$images	=  fw_get_db_post_option($post_id, 'images_gallery',$default_value = null);
}

$title	= get_the_title($post_id);
?>
<?php if( !empty( $images ) && is_array( $images ) ){?>
	<div class="wt-craftedprojects wt-profile-gallery">
		<div class="wt-usertitle">
			<h2><?php esc_html_e('Gallery','workreap');?></h2>
		</div>
		<div class="wt-projects wt-haslayout">
			<?php 
				foreach( $images as $key => $gallery_image ){ 
					$gallery_thumnail_image_url 	= !empty( $gallery_image['attachment_id'] ) ? wp_get_attachment_image_src( $gallery_image['attachment_id'], 'workreap_freelancer', true ) : '';
					$gallery_image_url 				= !empty( $gallery_image['url'] ) ? $gallery_image['url'] : '';
					
			?>
			<div class="wt-project wt-crprojects">
				<?php if( !empty($gallery_thumnail_image_url[0]) ){?>
					<a class="wt-venobox" data-gall="gall" href="<?php echo esc_url($gallery_image_url); ?>">
						<figure><img src="<?php echo esc_url( $gallery_thumnail_image_url[0] );?>" alt="<?php echo esc_attr($title);?>"></figure>
					</a>
				<?php }?>
			</div>
			<?php } ?>
		</div>
	</div>
<?php
}

$script	= "jQuery('.wt-venobox').venobox();";
wp_add_inline_script( 'venobox', $script, 'after' );
