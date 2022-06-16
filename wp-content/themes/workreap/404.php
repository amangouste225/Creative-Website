<?php
/**
 *
 * 404 Page
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
get_header();
$default_title   = esc_html__('The page you are looking for, does not exist', 'workreap');
if ( ! function_exists('fw_get_db_settings_option') ) {
	$img_404 = '';
	$desc    = '';
} else {
	$img_404 = fw_get_db_settings_option('404_banner');
	$title 	 = fw_get_db_settings_option('404_title');
	$desc 	 = fw_get_db_settings_option('404_description');
}

$title = !empty( $title ) ?  $title : $default_title;
?>
<div class="container">
	<div class="row justify-content-md-center">
		<div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
			<div class="wt-404errorpage">
				<?php if( !empty( $img_404['url'] ) ) { ?>
					<figure class="wt-404errorimg">
						<img src="<?php echo esc_url( $img_404['url']  );?>" alt="<?php esc_attr_e('404 Page','workreap');?>">
					</figure>
				<?php } ?>
				<div class="wt-404errorcontent">
					<div class="wt-title">
						<h3><?php echo esc_html( $title);?></h3>
					</div>
					<?php if( !empty( $desc ) ) { ?>
						<div class="wt-description 404page-desc">
							<p><?php echo esc_html( $desc );?>&nbsp;<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Homepage','workreap');?></a></p>
						</div>
					<?php } ?>
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
