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
$user_identity 	 = $current_user->ID;
$url_identity 	 = $user_identity;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

$date_format			= get_option('date_format');
$proposal_id			= !empty($_GET['id']) ? intval($_GET['id']) : '';

$project_id				= get_post_meta( $proposal_id, '_project_id', true );
$project_status			= get_post_status($project_id);

$post_author			= get_post_field('post_author', $project_id);
$hired_freelancer_id	= get_post_field('post_author', $proposal_id);

$post_status			= get_post_status($proposal_id);
$hired_freelance_id		= !empty( $hired_freelancer_id ) ? intval( $hired_freelancer_id ) : '';
$hire_linked_profile	= workreap_get_linked_profile_id($hired_freelance_id); 
$hired_freelancer_title	= get_the_title( $hire_linked_profile );
$job_statuses			= worktic_job_statuses();
$proposal_price			= get_post_meta( $proposal_id, '_amount', true );
$proposal_price			= !empty($proposal_price) ? $proposal_price : 0;

$total_milestone_price			= workreap_get_milestone_statistics($proposal_id,array('pending','publish'));
$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
$meta_array	= array(
				array(
					'key'		=> '_propsal_id',
					'value'   	=> $proposal_id,
					'compare' 	=> '=',
					'type' 		=> 'NUMERIC'
				),
				array(
					'key'		=> '_status',
					'value'   	=> 'completed',
					'compare' 	=> '=',
				)
			);
$completed	= workreap_get_post_count_by_meta('wt-milestone','publish',$meta_array);
$completed	= !empty($completed) ? intval($completed) : 0;

$remaning_price	= intval($proposal_price) > intval($total_milestone_price) ? $proposal_price - $total_milestone_price : 0;

$hired_freelancer_avatar 	= apply_filters(
	'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 225, 'height' => 225 )
);

$proposal_status				= get_post_meta($proposal_id,'_proposal_status',true);
$proposal_status				= !empty($proposal_status) ? $proposal_status : '';
$show_posts 	= -1;//get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);

$order 			= 'ASC';
$sorting 		= 'ID';

$meta_query_args	= array();
$args 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'wt-milestone',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('pending','publish'),
					'paged' 			=> $paged,
					'suppress_filters' 	=> false
				);
				$meta_query_args[] = array(
					'key' 		=> '_propsal_id',
					'value' 	=> $proposal_id,
					'compare' 	=> '='
				);
$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);
$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;
$datepicker_class	= 'milestone-datepicker';
?>

<div class="col-12">
	<div class="wt-dashboardbox">
		<div class="wt-dashboardboxtitle">
			<h2><?php esc_html_e('Manage Milstone', 'workreap'); ?></h2>
		</div>
		<div class="wt-dashboardboxcontent wt-jobdetailsholder wt-milestonesingle">
		<?php 
			if ( intval( $url_identity ) === intval( $post_author )  ) {
				$employer_title = esc_html(get_the_title( $linked_profile ));
			?>
			<div class="wt-userlistinghold wt-featured wt-userlistingvtwo">
				<?php do_action('workreap_project_print_featured', $project_id); ?>
				<div class="wt-userlistingcontent">
					<div class="wt-contenthead">
						<div class="wt-title">
							<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
							<h2><?php echo esc_html(get_the_title($project_id)); ?></h2>
						</div>
						<ul class="wt-saveitem-breadcrumb wt-userlisting-breadcrumb">
							<?php do_action('workreap_project_print_project_level', $project_id); ?>
							<?php do_action('workreap_print_location', $project_id); ?>
							<?php do_action('workreap_print_project_type', $project_id); ?>
						</ul>
					</div>
					<?php if( $project_status === 'hired' || $project_status === 'completed' || $project_status === 'cancelled') {?>
						<div class="wt-rightarea">
							<?php do_action('workreap_milstone_freelancer_html', $proposal_id); ?>
						</div>
					<?php } ?>
				</div>	
			</div>
			<?php }?>
					
			<div class="wt-rcvproposalholder wt-hiredfreelancer">
				<?php if( ( $project_status === 'publish' || $project_status === 'hired' || $project_status === 'completed' || $project_status === 'cancelled')  && !empty( $hired_freelance_id ) ) { ?>
					<div class="wt-rcvproposalholder wt-hiredfreelancer wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Freelancer', 'workreap'); ?></h2>
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
									<?php if( $project_status === 'hired' ) { ?>
										<form class="wt-formtheme wt-formsearch">
											<fieldset>
												<div class="form-group">
													<span class="wt-select status-change-select">
														<select id="wt-change-project-status">
															<?php 
																if( !empty( $job_statuses ) ) {
																	foreach( $job_statuses as $key=> $status_v ){
																		if( (!empty($key) && $key !=='completed') || (!empty($completed) && $completed == $count_post &&  $project_status ==='hired' ) ){
																		?>
																			<option data-proposal-id="<?php echo intval($proposal_id);?>" value="<?php echo esc_attr($key);?>"><?php echo esc_html($status_v);?></option>
																		<?php }
																	}
																}
															?>
														</select>
													</span>
													<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn" id="btn-change-status"><?php esc_html_e('Update','workreap');?></a>
												</div>
											</fieldset>
										</form>
									<?php } elseif( $project_status === 'completed' ) { ?>
										<div class="wt-btnarea">
											<span class="wt-btn wt-bg-green"><?php esc_html_e('Project completed', 'workreap'); ?></span>
										</div>
									<?php } else{ ?>
										<div class="wt-milestonebtn wt-flexbox">
											<?php if(!empty($post_status) && $post_status === 'cancelled' ){ ?>
												<a href="#" onclick="event_preventDefault(event);" class="wt-btn" data-toggle="modal" data-target="#wt-projectmodalbox-cancelled"><?php esc_html_e('Cancelled','workreap');?></a>
											<?php } else {
												if(!empty($proposal_status) && $proposal_status === 'pending' ){ ?>
													<a href="#" onclick="event_preventDefault(event);" class="wt-btn btn-proposal-underreview"><?php esc_html_e('Under freelancer review','workreap');?></a>
												<?php } ?>
												<?php if( empty($proposal_status)  && $count_post > 0 && intval($proposal_price) == intval($total_milestone_price)) { ?>
													<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-milstone-request" data-id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Send Milestone to Freelancer','workreap');?></a>
												<?php } ?>
												<?php if( $proposal_price > $total_milestone_price ){?>
													<a href="#" onclick="event_preventDefault(event);" class="wt-btn" data-toggle="modal" data-target="#wt-addmilstone"><?php esc_html_e('Add new milestone','workreap');?></a>
												<?php } ?>
											<?php } ?>
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
			<?php do_action('workreap_milstone_amount_statistics', $proposal_id,'employer');?>
			<?php if( $query->have_posts() ){ ?>
				<div id="milestoneaccordion" class="accordion">
					<div class="wt-rcvproposalholder wt-hiredfreelancer">
						<div class="wt-tabscontenttitle wt-flexbox">
							<h2><?php esc_html_e('Milestones','workreap');?></h2>
						</div>
						<?php 
							$counter	= 0;
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$counter++;
								$milstone_title		= get_the_title($post->ID);
								$milstone_content	= get_post_field('post_content',$post->ID);
								$milstone_price_single		= get_post_meta( $post->ID, '_price', true );
								$milstone_date		= get_post_meta( $post->ID, '_due_date', true );
								$milstone_date		= str_replace('/','-',$milstone_date); 
								$milstone_due_date	= !empty($milstone_date) ? date_i18n($date_format, strtotime($milstone_date)) : '';
								$milstone_price		= !empty($milstone_price_single) ? workreap_price_format($milstone_price_single,'return') : '';

								$milstone_status	= get_post_status($post->ID);
								$edit_price			= $remaning_price+$milstone_price_single;
								$updated_status	= get_post_meta($post->ID,'_status',true);
								$updated_status	= !empty($updated_status) ? $updated_status : '';
								$status_class	= '';
								$status_text	= '';
								$status_option	= '';

								$order_id	= get_post_meta( $post->ID, '_order_id', true );
								$order_id	= !empty($order_id) ? intval($order_id) : 0;
								$order_url	= '';
								if( !empty( $order_id ) ){
									if( class_exists('WooCommerce') ) {
										$order		= wc_get_order($order_id);
										$order_url	= Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $user_identity,true,'invoice',intval($order_id));;
									}
								}
											 
								if(!empty($updated_status)){
									if( ($updated_status === 'pay_now' || $updated_status === 'pending') && ( !empty($proposal_status) && $proposal_status === 'approved' && empty($order_id) )  ) {
										$status_text	= esc_html__( 'Pay Now', 'workreap' );
										$status_class	= 'wt-pay-milestone wt-bg-green';
									} else if($updated_status === 'pending') {
										$status_text	= esc_html__( 'Pending', 'workreap' );
										$status_class	= 'wt-bg-pending';
									} else if($updated_status === 'hired') {
										$status_class	= 'wtbtn-hired';
										$status_text	= esc_html__( 'Hired', 'workreap' );
									} else if($updated_status === 'completed') {
										$status_class	= 'wt-bg-green wtbtn-completed';
										$status_text	= esc_html__( 'Completed', 'workreap' );
									}
								}
								
								?>
									<div class="wt-milestonesingle__active wt-milestonesingle__upcoming">
										<div class="wt-milestonestabs">
											<div class="wt-milestonesingle__active--uppersection">
												<div class="wt-milestonesingle__active--title">
													<span class="wt-countdata"><?php echo esc_html($counter);?></span>
													<div class="wt-milestone-title">
														<?php if(!empty($milstone_title)){?>
															<h4><?php echo esc_html($milstone_title);?></h4>
														<?php } ?>
														<?php if(!empty($milstone_price)){?>
															<h6><?php esc_html_e('Budget','workreap');?>&nbsp;<?php echo esc_html($milstone_price);?></h6>
														<?php } ?>
													</div>
												</div>
												<?php if(!empty($milstone_due_date)){ ?>
													<div class="wt-milestonesingle__active--date">
														<h6><?php echo esc_html_e('Due Date','workreap');?></h6>
														<span><?php echo esc_html($milstone_due_date);?></span>
													</div>
												<?php } ?>
												<div class="wt-milestonesingle__active--btn">
													<?php if(!empty($updated_status) && $updated_status  === 'pending' ) { ?>
														<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#wt-addmilstone-<?php echo intval($post->ID);?>" class="wt-milestonesingle__active--edit"><?php esc_html_e('Edit','workreap');?></a>
													<?php } ?>
													<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval($post->ID);?>" class="wt-btn <?php echo esc_attr($status_class);?>"><?php echo esc_html(ucfirst($status_text));?></a>
													<?php if($updated_status === 'hired') { ?>
														<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval($post->ID);?>" class="wt-btn wt-milestone-completed wt-bg-green"><?php esc_html_e('Complete Now','workreap');?></a>
													<?php } ?>
													<?php if( !empty($order_id) ){ ?>
														<a href="<?php echo esc_url($order_url);?>" class="wt-btn wt-status-order" target="_blank"><?php esc_html_e('Check Invoice','workreap');?></a>
													<?php } ?>
												</div>
											</div>
										</div>
										<?php if(!empty($milstone_content)) {?>
											<div class="collapse">
												<div class="wt-milestonesingle__active--lowersection">
													<div class="wt-milestonesingle__active--description">
														<?php echo do_shortcode($milstone_content);?>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
									<div class="modal fade wt-offerpopup wt-milestonepopup" tabindex="-1" role="dialog" id="wt-addmilstone-<?php echo intval($post->ID);?>">
										<div class="modal-dialog" role="document">
											<div class="wt-modalcontent modal-content">
												<div class="wt-popuptitle">
													<h2><?php esc_html_e('Update  milestone','workreap');?></h2>
													<a href="#" class="wt-closebtn close"><i class="fa fa-close" data-dismiss="modal" aria-label="Close"></i></a>
												</div>
												<div class="modal-body">
													<form class="wt-formtheme wt-milestone-form">
														<fieldset>
															<div class="form-group">
																<input type="text" name="title" class="form-control" placeholder="<?php esc_attr_e('Milestone Title','workreap');?>" value="<?php echo esc_attr($milstone_title);?>">
															</div>
															<div class="form-group form-group-half wt-inputwithicon" data-provide="datepicker">
																<i class="lnr lnr-calendar-full"></i>
																<input id="wt-startdate" type="text" name="due_date" class="form-control <?php echo esc_attr($datepicker_class);?>" autocomplete="off" placeholder="<?php esc_attr_e('Due Date','workreap');?>" value="<?php echo esc_attr($milstone_date);?>">
															</div>
															<div class="form-group form-group-half">
																<input type="number" name="price" autocomplete="off" class="form-control" placeholder="<?php esc_attr_e('Milestone Price','workreap');?>" value="<?php echo esc_attr($milstone_price_single);?>" max="<?php echo esc_attr($edit_price);?>">
															</div>
															<div class="form-group">
																<textarea class="form-control" name="description" placeholder="<?php  echo _x('Description', 'Description for milestone', 'workreap' );?>"><?php echo do_shortcode($milstone_content);?></textarea>
															</div>
															<div class="form-group wt-btnarea">
																<a href="#" onclick="event_preventDefault(event);" class="wt-btn pull-right wt-save-milstone" data-id="<?php echo intval($proposal_id);?>" data-milestone_id="<?php echo intval($post->ID);?>"><?php esc_html_e('Update','workreap');?></a>
															</div>
														</fieldset>
													</form>
												</div>
											</div>
										</div>
									</div>
								<?php
								endwhile;
								wp_reset_postdata();
							?>
					</div>
				</div>
			<?php } ?>
			<?php
				if( $project_status === 'hired' || $project_status === 'completed' || $project_status === 'cancelled') {
					 get_template_part('directory/front-end/templates/dashboard', 'project-history-messages'); 
				}
			?>
		</div>
		
	</div>
</div>
<?php
	if( $post_status === 'cancelled' ) { 
		$cancelled_reason	= get_post_meta( $proposal_id, '_cancelled_reason', true );
		if(!empty($cancelled_reason)) {	?>
		<div class="wt-uploadimages modal fade" id="wt-projectmodalbox-cancelled" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="wt-modaldialog modal-dialog" role="document">
				<div class="wt-modalcontent modal-content">
					<div class="wt-boxtitle">
						<h2><?php esc_html_e('Cancel reason','workreap');?><i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i></h2>
					</div>
					<div class="wt-modalbody modal-body">
						<?php echo do_shortcode($cancelled_reason);?>	
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
<?php  if( $proposal_price > $total_milestone_price ){ ?>
<!-- Popup Start-->
<!-- Milestone Popup Start -->
<div class="modal fade wt-offerpopup wt-milestonepopup" tabindex="-1" role="dialog" id="wt-addmilstone">
		<div class="modal-dialog" role="document">
			<div class="wt-modalcontent modal-content">
				<div class="wt-popuptitle">
					<h2><?php esc_html_e('Add new milestone','workreap');?></h2>
					<a href="#" class="wt-closebtn close"><i class="fa fa-close" data-dismiss="modal" aria-label="Close"></i></a>
				</div>
				<div class="modal-body">
					<form class="wt-formtheme wt-milestone-form">
						<fieldset>
							<div class="form-group">
								<input type="text" name="title" class="form-control" placeholder="<?php esc_attr_e('Milestone Title','workreap');?>">
							</div>
							<div class="form-group form-group-half wt-inputwithicon" data-provide="datepicker">
								<i class="lnr lnr-calendar-full"></i>
								<input id="wt-startdate" autocomplete="off" type="text" name="due_date" class="form-control <?php echo esc_attr($datepicker_class);?>" placeholder="<?php esc_attr_e('Due Date','workreap');?>">
							</div>
							<div class="form-group form-group-half">
								<input type="number" name="price" class="form-control" placeholder="<?php esc_attr_e('Milestone Price','workreap');?>" value="<?php echo esc_attr($remaning_price);?>" max="<?php echo esc_attr($remaning_price);?>">
							</div>
							<div class="form-group">
								<textarea class="form-control" name="description" placeholder="<?php  echo _x('Description', 'Description for milestone', 'workreap' );?>"></textarea>
							</div>
							<div class="form-group wt-btnarea">
								<a href="#" onclick="event_preventDefault(event);" class="wt-btn pull-right wt-save-milstone" data-_milestone_id="" data-id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Save','workreap');?></a>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Milestone Popup End -->
	<!-- Popup End-->
<?php } ?>
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
							<textarea class="form-control cancelled-feedback" name="cancelled_reason" placeholder="<?php esc_attr_e('Cancel reason','workreap');?>"></textarea>
						</div>
						<div class="form-group wt-btnarea">
							<a class="wt-btn cancelled-btn" data-project-id="<?php echo intval($project_id);?>" href="#" onclick="event_preventDefault(event);">
								<?php esc_html_e('Cancel','workreap');?>
							</a>
						</div>									
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
 if(!empty($completed) && $completed == $count_post &&  $project_status ==='hired'){  
	$rating_headings			= workreap_project_ratings();?>
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
							<a class="wt-btn compelete-btn" data-project-id="<?php echo intval($project_id);?>" href="#" onclick="event_preventDefault(event);">
								<?php esc_html_e('Send Feedback','workreap');?>
							</a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_datepicker_jobs("' . esc_js( $datepicker_class ). '"); });';
	wp_add_inline_script( 'workreap-user-dashboard', $inline_script, 'after' );
?>