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
global $current_user,$paged;
$user_identity 	 = $current_user->ID;
$url_identity 	 = $user_identity;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$meta_query_args = array();
$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);

$show_posts 			= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$edit_id				= !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_author			= get_post_field('post_author', $edit_id);
$hired_freelancer_id	= get_post_meta($edit_id,'_freelancer_id',true);

$job_status				= get_post_status( $edit_id );
$milestone				= array();
if (function_exists('fw_get_db_settings_option')) {
	$milestone         	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
}
$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
?>
<div class="wt-haslayout wt-job-proposals">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle">
				<h2><?php esc_html_e('Manage proposals', 'workreap'); ?></h2>
			</div>
			<div class="wt-dashboardboxcontent wt-rcvproposala emp-manage-details">
			<?php 
				if ( intval( $url_identity ) === intval( $post_author )  ) {
					$employer_title = esc_html(get_the_title( $linked_profile ));
				?>
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
						<div class="wt-rightarea">
							<div class="wt-btnarea">
								<a target="_blank" href="<?php echo esc_url(get_the_permalink($edit_id)); ?>" class="wt-btn"><?php esc_html_e('Preview Project','workreap');?></a>
							</div>
							<?php do_action('workreap_proposals_received_html', $edit_id); ?>
						</div>
					</div>	
				</div>
				<div class="wt-freelancerholder wt-rcvproposalholder">
				<?php 
					$query_args = array('posts_per_page' => $show_posts,
						'post_type' 		=> 'proposals',
						'paged' 		 	=> $paged,
						'suppress_filters' 	=> false,
					);

					$meta_query_args[] = array(
						'key' 			=> '_project_id',
						'value' 		=> $edit_id,
						'compare' 		=> '='
					);
					$query_relation = array('relation' => 'AND',);
					$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);    


					$pquery = new WP_Query($query_args);
					$count_post = $pquery->found_posts;
					
					if( $pquery->have_posts() ){?>
						<?php if( $job_status === 'hired' ) { ?>
							<?php get_template_part('directory/front-end/templates/employer/dashboard-hired', 'freelancer');?>	
						<?php } ?>
						<div class="emp-proposal-list">
							<div class="wt-tabscontenttitle hmargin-top">
								<h2><?php esc_html_e('Received proposals', 'workreap'); ?></h2>
							</div>
							<div class="wt-managejobcontent">
							<?php
							while ($pquery->have_posts()) : $pquery->the_post();
								global $post;
								$author_id 			= get_the_author_meta( 'ID' );  
								$linked_profile 	= workreap_get_linked_profile_id($author_id);
								$freelancer_title 	= esc_html(get_the_title( $linked_profile ));

								if (function_exists('fw_get_db_post_option')) {
									$proposal_docs 	= fw_get_db_post_option($post->ID, 'proposal_docs', true);
								} else {
									$proposal_docs	= '';
								}

								$proposal_docs = !empty( $proposal_docs ) && is_array( $proposal_docs ) ?  count( $proposal_docs ) : 0;
								$freelancer_avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $linked_profile ), array( 'width' => 225, 'height' => 225 )
									);
												
								$order_id	= get_post_meta( $post->ID, '_order_id', true );
								$order_id	= !empty($order_id) ? intval($order_id) : 0;
								$order_url	= '';
												
								if( !empty( $order_id ) ){
									if( class_exists('WooCommerce') ) {
										$order		= wc_get_order($order_id);
										$order_url	= Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $user_identity,true,'invoice',intval($order_id));
									}
								}

								?>
								<div class="wt-userlistinghold wt-featured wt-proposalitem wt-userlistingcontentvtwo" data-id="<?php echo esc_attr($post->ID);?>">
									<?php do_action('workreap_featured_freelancer_tag', $author_id); ?>
									<figure class="wt-userlistingimg">
										<img src="<?php echo esc_url( $freelancer_avatar );?>" alt="<?php esc_attr_e('freelancer','workreap');?>" class="template-content">
									</figure>
									<div class="wt-proposaldetails">
										<div class="wt-contenthead">
											<div class="wt-title">
												<?php do_action( 'workreap_get_verification_check', $linked_profile, $freelancer_title ); ?>
											</div>
										</div>
										<?php do_action('workreap_freelancer_get_reviews',$linked_profile,'v1');?>												
									</div>
									<div class="wt-rightarea">
									<?php if( $job_status == 'hired' && $hired_freelancer_id == $author_id ) {?>
										<div class="wt-btnarea">
											<span class="wt-btn"><?php esc_html_e('Hired','workreap');?></span>
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','history',$edit_id); ?>" class="wt-btn"><?php esc_html_e('View History','workreap');?></a>
										</div>		
									<?php } 
												
										if( $job_status !== 'hired' ) { 
											$chat_option	= array();
											if( function_exists('fw_get_db_settings_option')  ){
												$chat_option	= fw_get_db_settings_option('proposal_message_option', $default_value = null);
											}
											
											if(!empty($chat_option) && $chat_option === 'enable' ){ ?>
											<div class="wt-btnarea">
												<a href="#" onclick="event_preventDefault(event);" class="wt-btn chat-proposal-now" data-id="<?php echo intval($linked_profile);?>"><?php esc_html_e('Chat Now','workreap');?></a>
											</div>
											<div class="modal fade wt-offerpopup-proposal-chat" tabindex="-1" role="dialog" id="proposalchatmodal-<?php echo intval($linked_profile);?>">
												<div class="modal-dialog modal-dialog-centered" role="document">
													<div class="wt-modalcontent modal-content">
														<div class="wt-popuptitle">
															<h2><?php esc_html_e('Send message','workreap');?></h2>
															<a href="#" onclick="event_preventDefault(event);" class="wt-closebtn close"><i class="fa fa-close" data-dismiss="modal"></i></a>
														</div>
														<div class="modal-body">
															<form class=" chat-form">
																<div class="wt-formtheme wt-formpopup">
																	<fieldset>
																		<div class="form-group">
																			<textarea class="form-control reply_msg" name="reply" placeholder="<?php esc_attr_e('Type message here', 'workreap'); ?>"></textarea>
																		</div>
																		<div class="form-group wt-btnarea">
																			<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-proposal-chat" data-url="<?php  Workreap_Profile_Menu::workreap_profile_menu_link('chat', $user_identity);?>&user_id=<?php echo intval($author_id);?>" data-status="unread" data-msgtype="proposal" data-receiver_id="<?php echo intval($author_id);?>"><?php esc_html_e('Send Message','workreap');?></a>
																		</div>
																	</fieldset>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
											<?php } 
											if(!empty($milestone) && $milestone === 'enable') {
												$_milestone   	= get_post_meta($edit_id,'_milestone',true);
												$is_milestone	= !empty( $_milestone ) ? $_milestone : 'off';
												if(!empty($is_milestone) && $is_milestone ==='on' ){?>
													<div class="wt-btnarea">
														<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('milestone', $user_identity,'','listing',$post->ID); ?>" class="wt-btn" ><?php esc_html_e('Hire and Set Milestones','workreap');?></a>
													</div>
												<?php } else if( empty($order_id) ){ ?>
													<div class="wt-btnarea"><a href="#" onclick="event_preventDefault(event);" class="wt-btn hire-now" data-id="<?php echo intval($post->ID);?>" data-post-id="<?php echo esc_attr($edit_id);?>"><?php esc_html_e('Hire Now','workreap');?></a></div>
												<?php }
											} else if( empty($order_id) ){?>
												<div class="wt-btnarea"><a href="#" onclick="event_preventDefault(event);" class="wt-btn hire-now" data-id="<?php echo intval($post->ID);?>" data-post-id="<?php echo esc_attr($edit_id);?>"><?php esc_html_e('Hire Now','workreap');?></a></div>
											<?php } ?>
											
										<?php } ?>
										<?php if( !empty($order_id) ){ ?>
											<div class="wt-btnarea"><a href="<?php echo esc_url($order_url);?>" class="wt-btn" target="_blank"><?php esc_html_e('Check Invoice','workreap');?></a></div>
										<?php } ?>						
										<?php do_action('worrketic_proposal_duration_and_amount',$post->ID);?>
										<?php do_action('worrketic_proposal_cover',$post->ID);?>
										<?php do_action('worrketic_proposal_attachments',$post->ID);?>													
									</div>
								</div>		
								<?php 
								endwhile;
								wp_reset_postdata();
							?>
							</div>
						</div>
					<?php } else{
						do_action('workreap_empty_records_html','',esc_html__( 'There is no proposal related to this project.', 'workreap' ),true);
					}?>
				</div>
				<?php }?>
			</div>
			
			<?php 	
				if ( !empty($count_post) && $count_post > $show_posts) {
					echo '<div class="col-12">';
					workreap_prepare_pagination($count_post, $show_posts);
					echo '</div>';
				}
			?>
		</div>
	</div>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'underscore');?>
<?php get_template_part('directory/front-end/templates/dashboard', 'cover-letter');?>