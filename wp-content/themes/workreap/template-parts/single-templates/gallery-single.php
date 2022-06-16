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

global $blog_post_gallery;
?>
<div id="wt-blog-slider" class="wt-blog-slider wt-haslayout owl-carousel">
	<?php
		foreach ( $blog_post_gallery as $blog_gallery ) {
			$width      = intval(1140);
			$height     = intval(400);
			$thumbnail  = workreap_prepare_image_source($blog_gallery['attachment_id'] , $width , $height);		
			if ( !empty( $thumbnail ) ) {?>
				<div class="item">
					<figure class="wt-singleimg-one">
						<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php esc_attr_e('banner','workreap');?>" />
					</figure>
				</div>
				<?php
			}
		}
	?>
</div>
<?php
	$script = "
		jQuery(document).ready(function () {
			jQuery('#wt-blog-slider').owlCarousel({
				items: 1,
				loop: false,
				nav: false,
				rtl: ".workreap_owl_rtl_check().",
				autoplay: true
			});
		});
	"; 
	wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
