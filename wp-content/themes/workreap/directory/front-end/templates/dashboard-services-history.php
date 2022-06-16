<?php
/**
 *
 * The template part for displaying service history
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$url_identity 	 	= $user_identity;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$edit_id			= !empty($_GET['id']) ? intval($_GET['id']) : '';
$employeer_id		= get_post_field('post_author', $edit_id);
$freelancer_id		= get_post_meta( $edit_id, '_service_author', true);

$post_status		= get_post_status($edit_id);
$service_id			= get_post_meta( $edit_id, '_service_id', true);
$service_addons		= get_post_meta( $edit_id, '_addons', true);
$order_id			= get_post_meta($edit_id,'_order_id',true);

$hire_linked_profile		= workreap_get_linked_profile_id($freelancer_id); 
$hired_freelancer_title 	= get_the_title( $hire_linked_profile );

$employer_linked_profile	= workreap_get_linked_profile_id($employeer_id); 
$employer_title 			= get_the_title( $employer_linked_profile );
$job_statuses				= worktic_job_statuses();

$rating_headings			= workreap_project_ratings('services_ratings');
$hired_freelancer_avatar 	= apply_filters(
	'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 225, 'height' => 225 )
);
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-9">
	<div class="wt-dashboardbox">
		<div class="wt-dashboardboxtitle">
			<h2><?php esc_html_e('Service Details','workreap');?></h2>
		</div>
		<div class="wt-dashboardboxcontent wt-jobdetailsholder history-addons">
			<?php do_action('workreap_service_listing_basic', $service_id ,'wt-jobservice-details','show_details',$order_id); ?>
			
			<?php if( !empty( $service_addons ) ){ ?>
				<div class="wt-addonsservices wt-tabsinfo">
					<div class="wt-tabscontenttitle">
						<h2><?php esc_html_e( 'Addons Services','workreap');?></h2>
					</div>
					<div class="wt-addonservices-content">
						<ul>
						<?php 
							foreach( $service_addons as $key => $addon ) {
								$db_price			= 0;
								if(!empty($addon['id']) && is_array($addon)){
									$db_price	= $addon['price'];
									$addon		= $addon['id'];
								}else{
									if (function_exists('fw_get_db_post_option')) {
										$db_price   = fw_get_db_post_option($addon,'price');
									}
								}
							?>
							<li>
								<div class="wt-checkbox">
									<label>
										<?php if( !empty( get_the_title($addon) ) ){?>
											<h3><?php echo esc_html( get_the_title($addon) );?></h3>
										<?php } ?>
										<?php if( !empty( get_the_excerpt($addon) ) ){?>
											<p><?php echo esc_html( get_the_excerpt($addon) );?></p>
										<?php } ?>
										<?php if( !empty( $db_price ) ){?>
											<strong><?php workreap_price_format($db_price);?></strong>
										<?php } ?>
									</label>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			<?php } ?>
			<?php if( 'employer' == apply_filters('workreap_get_user_type', $user_identity ) ){ ?>
				<div class="wt-rcvproposalholder wt-hiredfreelancer wt-tabsinfo">
					<div class="wt-tabscontenttitle">
						<h2><?php esc_html_e('Hired freelancer','workreap');?></h2>
					</div>
					<div class="wt-jobdetailscontent">
						<div class="wt-userlistinghold wt-featured wt-proposalitem">
							<?php do_action('workreap_featured_freelancer_tag', $freelancer_id); ?>
							<figure class="wt-userlistingimg">
								<img src="<?php echo esc_url( $hired_freelancer_avatar );?>" alt="<?php esc_attr_e('freelancer','workreap');?>" class="template-content">
							</figure>
							<div class="wt-proposaldetails">
								<div class="wt-contenthead">
									<div class="wt-title">
										<?php do_action( 'workreap_get_verification_check', $hire_linked_profile, $hired_freelancer_title ); ?>
									</div>
								</div>
								<?php do_action('workreap_freelancer_get_reviews',$hire_linked_profile,'v1');?>	
												
							</div>
							<?php if( $post_status === 'hired' ) {?>
								<div class="wt-rightarea wt-titlewithsearch wt-titlewithsearchvtwo">
									<form class="wt-formtheme wt-formsearch">
										<fieldset>
											<div class="form-group">
												<span class="wt-select status-change-select">
													<select id="wt-change-project-status">
														<?php 
															if( !empty( $job_statuses ) ) {
																foreach( $job_statuses as $key=> $status_v ){
																	?>
																	<option data-proposal-id="<?php echo intval($edit_id);?>" value="<?php echo esc_attr($key);?>"><?php echo esc_html($status_v);?></option>
																	<?php 
																}
															}
														?>
													</select>
												</span>
												<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn" data-toggle="modal" data-target="#wt-projectmodalbox" id="btn-change-status"><?php esc_html_e('Update','workreap');?></a>
											</div>
										</fieldset>
									</form>												
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php }?>
			<?php get_template_part('directory/front-end/templates/dashboard', 'project-history-messages'); ?>
		</div>
	</div>
</div>

<?php if( $post_status === 'hired' ) {?>
<!-- Modal Box Start -->
	<div class="wt-uploadimages modal fade" id="wt-projectmodalbox-cancelled" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="wt-modaldialog modal-dialog" role="document">
			<div class="wt-modalcontent modal-content">
				<div class="wt-boxtitle">
					<h2><?php esc_html_e('Cancel reason','workreap');?><i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i></h2>
				</div>
				<div class="wt-modalbody modal-body">
					<form class="wt-formtheme wt-formfeedback-cancelled wt-formfeedback">
						<fieldset>
							<div class="form-group">
								<textarea class="form-control cancelled-feedback" name="cancelled_reason" placeholder="<?php esc_attr_e('Add cancel reason','workreap');?>"></textarea>
							</div>
							<div class="form-group wt-btnarea">
								<a class="wt-btn cancelled-service-btn" data-service-id="<?php echo intval($edit_id);?>" href="#" onclick="event_preventDefault(event);">
									<?php esc_html_e('Cancel service','workreap');?>
								</a>
							</div>									
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="wt-uploadimages modal fade" id="wt-projectmodalbox-complete" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="wt-modaldialog modal-dialog" role="document">
			<div class="wt-modalcontent modal-content">
				<div class="wt-boxtitle">
					<h2>
						<?php esc_html_e('Complete Service','workreap');?>
						<i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i>
					</h2>
				</div>
				<div class="wt-modalbody modal-body">
					<form class="wt-formtheme wt-formfeedback-complete wt-formfeedback">
						<fieldset>
							<div class="form-group">
								<textarea class="form-control" name="feedback_description" placeholder="<?php esc_attr_e('Add Your Feedback','workreap');?>*"></textarea>
							</div>
							<?php 
							if( !empty( $rating_headings ) ) {
								foreach ( $rating_headings  as $key => $rating ) {
									$flag = rand(1, 9999);
									$field_name = $key;
									?>
									<div class="form-group wt-ratingholder" data-ratingtitle="<?php echo esc_attr($key); ?>">
										<div class="wt-ratepoints wt-ratingbox-<?php echo esc_attr($flag); ?>">
											<div class="counter wt-pointscounter"><?php esc_html_e('1.0','workreap');?></div>
											<div id="jRate-<?php echo esc_attr($flag); ?>" class="wt-jrate"></div>
											<input type="hidden" name="feedback[<?php echo esc_attr($field_name); ?>]" class="rating-<?php echo esc_attr($flag); ?>" value="1" />
										</div>
										<span class="wt-ratingdescription"><?php echo esc_html($rating);?></span>
										<?php
											$script = "jQuery(function () {
												var that = this;
												var toolitup = jQuery('#jRate-" . esc_js($flag) . "').jRate({
													rating: 1,
													min: 0,
													max: 5,
													precision: 1,
													shapeGap: '6px',
													startColor: '#fdd003',
													endColor: '#fdd003',
													width: 20,
													height: 20,
													touch: true,
													backgroundColor: '#DFDFE0',
													onChange: function (rating) {
														jQuery('.rating-" . $flag . "').val(rating);
														jQuery('.wt-ratingbox-" . esc_js($flag) . " .wt-pointscounter').html(rating+'.0');
													},
													onSet: function (rating) {
														jQuery('.rating-" . esc_js($flag) . "').val(rating);
														jQuery('.wt-ratingbox-" . esc_js($flag) . " .wt-pointscounter').html(rating+'.0');
													}
												});
											});";
											wp_add_inline_script('workreap-user-dashboard', $script, 'after');
										?>
									</div>
								<?php } ?>
							<?php } ?>
							<div class="form-group wt-btnarea">
								<a class="wt-btn compelete-service-btn" data-service-id="<?php echo intval($edit_id);?>" href="#" onclick="event_preventDefault(event);">
									<?php esc_html_e('Send Feedback','workreap');?>
								</a>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php }?>
