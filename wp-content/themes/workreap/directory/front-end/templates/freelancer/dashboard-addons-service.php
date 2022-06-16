<?php
/**
 *
 * The template part for displaying post a job
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$action			= 'add';
$title			= '';
$price			= '';
$description	= '';

$post_id 	= !empty($_GET['id']) ? intval($_GET['id']) : '';
$mode		= !empty($_GET['mode']) ? esc_attr($_GET['mode']) : '';

if( !empty( $mode ) && !empty( $post_id ) && $mode === 'edit' ) {
	$title			= get_the_title( $post_id );
	$description	= get_the_excerpt( $post_id );
	$price			= get_post_meta( $post_id,'_price',true);
	$action			= 'update';
}

if (function_exists('fw_get_db_post_option') ) {
	$remove_service_addon		= fw_get_db_settings_option('remove_service_addon');
}

$remove_service_addon	= !empty($remove_service_addon) ? $remove_service_addon : 'no';

if(!empty($remove_service_addon) && $remove_service_addon === 'no'){?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-left">
	<div class="wt-haslayout wt-post-job-wrap">
		<form class="post-service-form wt-haslayout">
			<div class="wt-dashboardbox">
				<div class="wt-dashboardboxtitle">
					<h2><?php esc_html_e('Post a Addons Service','workreap');?></h2>
				</div>
				<div class="wt-dashboardboxcontent">
					<div class="wt-jobdescription wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Service description','workreap');?></h2>
						</div>
						<div class="wt-formtheme wt-userform wt-userformvtwo">
							<fieldset>
								<div class="form-group">
									<input type="text" name="addons_service[title]" class="form-control" placeholder="<?php esc_attr_e('Addons service title','workreap');?>" value="<?php echo esc_attr( $title );?>">
								</div>
								<div class="form-group">
									<input type="text" class="wt-numeric" name="addons_service[price]" value="<?php echo esc_attr( $price );?>" placeholder="<?php esc_attr_e('Service price','workreap');?>">
								</div>
								<div class="form-group">
									<textarea class="form-control" name="addons_service[description]" placeholder="<?php esc_attr_e('Addons Service description','workreap');?>"><?php echo do_shortcode( $description );?></textarea>
								</div>
							</fieldset>
						</div>
					</div>
					
				</div>
			</div>
			<div class="wt-updatall">
				<?php wp_nonce_field('wt_post_addons_service_nonce', 'post_addons_service'); ?>
				<i class="ti-announcement"></i>
				<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'workreap'); ?></span>
				<a class="wt-btn wt-post-addons-service" data-id="<?php echo intval( $post_id );?>" data-type="<?php echo esc_attr( $action );?>" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
			</div>
		</form>	
	</div>
</div>
<?php }