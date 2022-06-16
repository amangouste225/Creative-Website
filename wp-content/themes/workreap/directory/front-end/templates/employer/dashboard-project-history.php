<?php
/**
 *
 * The template part for displaying job proposals
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 			= $current_user->ID;
$url_identity 	 			= $user_identity;
$linked_profile  			= workreap_get_linked_profile_id($user_identity);
$edit_id					= !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_author				= get_post_field('post_author', $edit_id);
$job_status					= get_post_status( $edit_id );
$proposal_id				= get_post_meta( $edit_id, '_proposal_id', true);
$proposal_id				= !empty( $proposal_id ) ? intval( $proposal_id ) : '';
$job_statuses				= worktic_job_statuses();
$hired_freelance_id			= get_post_field('post_author',$proposal_id);
$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';
$hire_linked_profile		= workreap_get_linked_profile_id($hired_freelance_id); 
$hired_freelancer_title 	= esc_html( get_the_title( $hire_linked_profile ));
$rating_headings			= workreap_project_ratings();
$hired_freelancer_avatar 	= apply_filters(
	'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 225, 'height' => 225 )
);
?>
<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
	<div class="wt-haslayout wt-job-proposals">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle">
				<h2><?php esc_html_e('Job Details', 'workreap'); ?></h2>
			</div>
			<div class="wt-dashboardboxcontent wt-rcvproposala">
			<?php 
				if ( intval( $url_identity ) === intval( $post_author ) ) {
					$employer_title = esc_html( get_the_title( $linked_profile ));
				?>
				<div class="project-detail-history">
					<div class="wt-tabscontenttitle">
						<h2><?php esc_html_e('Project Detail', 'workreap'); ?></h2>
					</div>
					<div class="wt-jobdetailscontent">
						<div class="wt-userlistinghold wt-featured wt-userlistingvtwo">
							<?php do_action('workreap_project_print_featured', $edit_id); ?>
							<div class="wt-userlistingcontent">
								<div class="wt-contenthead">
									<div class="wt-title">
										<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
										<h2><?php echo esc_html(get_the_title($edit_id)); ?></h2>
									</div>
									<ul class="wt-saveitem-breadcrumb wt-userlisting-breadcrumb">
										<?php do_action('workreap_project_print_project_level', $edit_id); ?>
										<?php do_action('workreap_print_location', $edit_id); ?>
										<?php do_action('workreap_print_project_type', $edit_id); ?>
									</ul>
								</div>
								<?php if( $job_status === 'hired' || $job_status === 'completed'  ) { ?>
									<?php do_action('workreap_hired_freelancer_html', $edit_id); ?>
								<?php } ?>
							</div>	
						</div>
					</div>
				</div>
				<div class="wt-rcvproposalholder wt-hiredfreelancer">
					<?php if( $job_status === 'hired' || $job_status === 'completed'  && !empty( $hired_freelance_id ) ) { ?>
						<div class="wt-rcvproposalholder wt-hiredfreelancer wt-tabsinfo">
							<div class="wt-tabscontenttitle">
								<h2><?php esc_html_e('Hired freelancer', 'workreap'); ?></h2>
							</div>
							<div class="wt-jobdetailscontent ">
								<div class="wt-userlistinghold wt-featured wt-proposalitem">
									<?php do_action('workreap_featured_freelancer_tag', $hired_freelance_id); ?>
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
									<div class="wt-rightarea wt-rightarea wt-titlewithsearch">
										<?php if( $job_status === 'hired' ) { ?>
											<form class="wt-formtheme wt-formsearch">
												<fieldset>
													<div class="form-group">
														<span class="wt-select status-change-select">
															<select id="wt-change-project-status">
																<?php 
																	if( !empty( $job_statuses ) ) {
																		foreach( $job_statuses as $key=> $status_v ){
																			?>
																			<option data-proposal-id="<?php echo intval($proposal_id);?>" value="<?php echo esc_attr($key);?>"><?php echo esc_html($status_v);?></option>
																			<?php 
																		}
																	}
																?>
															</select>
														</span>
														<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn" id="btn-change-status"><?php esc_html_e('Update','workreap');?></a>
													</div>
												</fieldset>
											</form>
										<?php } elseif( $job_status === 'completed' ) { ?>
											<div class="wt-btnarea">
												<span><?php esc_html_e('Project completed', 'workreap'); ?></span>
											</div>
										<?php } ?>
										<?php do_action('worrketic_proposal_duration_and_amount',$proposal_id);?>
										<?php do_action('worrketic_proposal_cover',$proposal_id);?>
										<?php do_action('worrketic_proposal_attachments',$proposal_id);?>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php get_template_part('directory/front-end/templates/dashboard', 'project-history-messages'); ?>
					<?php }	?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'cover-letter');?>
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
							<a class="wt-btn cancelled-btn" data-project-id="<?php echo intval($edit_id);?>" href="#" onclick="event_preventDefault(event);">
								<?php esc_html_e('Cancel reason','workreap');?>
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
					<?php esc_html_e('Complete Project','workreap');?>
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
							<a class="wt-btn compelete-btn" data-project-id="<?php echo intval($edit_id);?>" href="#" onclick="event_preventDefault(event);">
								<?php esc_html_e('Send Feedback','workreap');?>
							</a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Modal Box End -->