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
$post_author			= get_post_field('post_author', $proposal_id);

$hired_freelancer_id	= get_post_field('post_author', $proposal_id);

$hired_freelance_id		= !empty( $hired_freelancer_id ) ? intval( $hired_freelancer_id ) : '';
$hire_linked_profile	= workreap_get_linked_profile_id($hired_freelance_id); 
$hired_freelancer_avatar 	= apply_filters(
	'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 225, 'height' => 225 )
);
$hired_freelancer_title			= get_the_title( $hired_freelance_id );
$post_status					= get_post_status($proposal_id);
$proposal_status				= get_post_meta($proposal_id,'_proposal_status',true);
$proposal_status				= !empty($proposal_status) ? $proposal_status : '';
$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);

$order 			= 'ASC';
$sorting 		= 'ID';

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
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-9">
	<div class="wt-dashboardbox">
		<div class="wt-dashboardboxtitle">
			<h2><?php esc_html_e('Manage Milestone', 'workreap'); ?></h2>
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
					<div class="wt-rightarea">
						<?php if( $project_status === 'hired' || $project_status === 'completed' || $project_status === 'cancelled') { do_action('workreap_milstone_freelancer_html', $proposal_id); }?>
					</div>
				</div>	
			</div>
			<?php }?>
			<div class="wt-rcvproposalholder wt-hiredfreelancer">
				<?php if( ( $project_status === 'publish' || $project_status === 'hired' || $project_status === 'completed' || $project_status === 'cancelled')  && !empty( $hired_freelance_id ) ) { ?>
					<div class="wt-rcvproposalholder wt-hiredfreelancer wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Freelancer', 'workreap'); ?></h2>
						</div>
						<div class="wt-jobdetailscontent">
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
									<?php if( $post_status === 'cancelled' ) { ?>
										<div class="wt-btnarea">
											<span><a href="#" class="wt-btn" data-toggle="modal" data-target="#wt-projectmodalbox-cancelled"><?php esc_html_e('Proposal Cancelled', 'workreap'); ?></a></span>
										</div>
									<?php }else if( $project_status === 'completed' ) { ?>
										<div class="wt-btnarea">
											<span class="wt-btn wt-bg-green"><?php esc_html_e('Project completed', 'workreap'); ?></span>
										</div>
									<?php } else{ ?>
										<div class="wt-milestonebtn wt-flexbox">
											<?php if( !empty($proposal_status)  && $proposal_status ==='pending' && $project_status ==='publish') { ?>
												<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-milstone-request-approved wt-bg-green" data-status="approved" data-id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Accept','workreap');?></a>
												<a href="#" onclick="event_preventDefault(event);" class="wt-btn" data-toggle="modal" data-target="#wt-cancelledfeedback"><?php esc_html_e('Decline','workreap');?></a>
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
			<?php do_action('workreap_milstone_amount_statistics', $proposal_id,'freelancers'); ?>
			<?php if( $query->have_posts() && !empty($proposal_status)  && ($proposal_status ==='pending' || $proposal_status ==='approved' || $proposal_status ==='cancelled' ) ){ ?>
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
								$milstone_price		= get_post_meta( $post->ID, '_price', true );
								$milstone_due_date	= get_post_meta( $post->ID, '_due_date', true );
								$milstone_due_date	= str_replace('/','-',$milstone_due_date); 
								$milstone_due_date	= !empty($milstone_due_date) ? date_i18n($date_format, strtotime($milstone_due_date)) : '';
								$milstone_price		= !empty($milstone_price) ? workreap_price_format($milstone_price,'return') : '';
								$milstone_status	= get_post_status($post->ID);
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
											<?php if(!empty($milstone_due_date)){?>
												<div class="wt-milestonesingle__active--date">
													<h6><?php echo esc_html_e('Due Date','workreap');?></h6>
													<span><?php echo esc_html($milstone_due_date);?></span>
												</div>
											<?php } ?>
											<?php if(!empty($milstone_status)) {?>
												<div class="wt-milestonesingle__active--btn">
													<?php
													$updated_status	= get_post_meta($post->ID,'_status',true);
									
													$updated_status	= !empty($updated_status) ? $updated_status : '';
													$status_class	= '';
													$status_text	= '';
													$status_option	= '';
									
													if(!empty($updated_status)){
														if( ($updated_status === 'pay_now' || $updated_status === 'pending') && ( !empty($proposal_status) && $proposal_status === 'approved')  ) {
															$status_text	= esc_html__( 'Pending', 'workreap' );
															$status_class	= 'wt-bg-pending';
														} else if($updated_status === 'pending') {
															$status_text	= esc_html__( 'Pending', 'workreap' );
															$status_class	= 'wt-bg-pending';
														} else if($updated_status === 'hired') {
															$status_text	= esc_html__( 'Hired', 'workreap' );
															$status_class	= 'wt-bg-green wtbtn-hired';
														} else if($updated_status === 'completed') {
															$status_class	= 'wt-bg-green wtbtn-completed';
															$status_text	= esc_html__( 'Completed', 'workreap' );
														}
													}
													?>
													<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval($post->ID);?>" class="wt-btn <?php echo esc_attr($status_class);?>"><?php echo esc_html(ucfirst($status_text));?></a>
													
												</div>
											<?php } ?>
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
							<?php
								endwhile;
								wp_reset_postdata();
							?>
					</div>
				</div>
			<?php } ?>
			<?php
				if( $project_status === 'hired' || $project_status === 'completed' || $post_status ==='cancelled') {
					 get_template_part('directory/front-end/templates/dashboard', 'project-history-messages'); 
				}
			?>
		</div>
		
	</div>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'cover-letter');?>
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
<?php if( !empty($proposal_status)  && $proposal_status ==='pending' && $project_status ==='publish') { ?>
<div class="wt-uploadimages modal fade" id="wt-cancelledfeedback" tabindex="-1" role="dialog" aria-hidden="true">
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
							<a class="wt-btn cancelled-btn-milestone" data-proposal-id="<?php echo intval($proposal_id);?>" href="#" onclick="event_preventDefault(event);">
								<?php esc_html_e('Cancel reason','workreap');?>
							</a>
						</div>									
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<?php }