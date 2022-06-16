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
$show_posts = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);
$order 	 = 'DESC';
$sorting = 'ID';

$proposal_page 			= array();
$allow_proposal_edit 	= '';
if (function_exists('fw_get_db_post_option')) {
	$proposal_page 			= fw_get_db_settings_option('dir_proposal_page');
	$allow_proposal_edit    = fw_get_db_settings_option('allow_proposal_edit');
}

$proposal_page_id = !empty( $proposal_page[0] ) ? $proposal_page[0] : '';
$submit_proposal  = !empty( $proposal_page_id ) ? get_the_permalink( $proposal_page_id ) : '';
$search_keyword  = !empty($_GET['keyword']) ? $_GET['keyword'] : "";

?>
<div class="wt-haslayout wt-job-proposals">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle wt-titlewithsearch">
				<h2><?php esc_html_e('Latest Proposals', 'workreap'); ?></h2>
				<?php do_action('workreap_dashboard_search_keyword','proposals','projects');?>
			</div>
			<div class="wt-dashboardboxcontent wt-rcvproposala emp-manage-details">
				<div class="wt-freelancerholder wt-rcvproposalholder">
					<?php 
						$query_args = array(
							'posts_per_page' 	=> $show_posts,
							'post_type' 		=> 'proposals',
							'orderby' 			=> $sorting,
							'order' 			=> $order,
							'post_status' 		=> array('publish'),
							'author' 			=> $user_identity,
							'paged' 			=> $paged,
							'suppress_filters'  => false,
							's'                 => $search_keyword,
						);

						$pquery = new WP_Query($query_args);
						$count_post = $pquery->found_posts;

						if( $pquery->have_posts() ){?>
							<div class="emp-proposal-list">
								<div class="wt-tabscontenttitle hmargin-top">
									<h2><?php esc_html_e('Submitted proposals', 'workreap'); ?></h2>
								</div>
								<div class="wt-managejobcontent">
								<?php
								while ($pquery->have_posts()) : $pquery->the_post();
									global $post;
									$author_id 			= get_the_author_meta( 'ID' );  
									$project_id			= get_post_meta($post->ID,'_project_id', true);
									$_proposal_id 		= get_post_meta($project_id, '_proposal_id', true);
									$job_status			= '';

									$proposal_hiring_status	= get_post_meta($post->ID,'_proposal_status',true);
									$proposal_hiring_status	= !empty($proposal_hiring_status) ? $proposal_hiring_status : '';
									$project_status			= get_post_status($project_id);
													
									if( !empty($_proposal_id) && ( intval($_proposal_id) === $post->ID ) ) {
										$job_status		= get_post_field('post_status',$project_id);
									}else if(!empty($_proposal_id)){
										$job_status		= 'cancelled';
									}else{
										$job_status		= 'pending';
									}
									
									$linked_profile 	= workreap_get_linked_profile_id($author_id);
									
									if (function_exists('fw_get_db_post_option')) {
										$proposal_docs 	= fw_get_db_post_option($post->ID, 'proposal_docs', true);
									} else {
										$proposal_docs	= '';
									}

									$proposal_status		= get_post_meta($project_id,'_milestone',true);
									$proposal_status		= !empty($proposal_status) ? $proposal_status : '';

									$proposal_docs = !empty( $proposal_docs ) && is_array( $proposal_docs ) ?  count( $proposal_docs ) : 0;
													
									$freelancer_avatar = apply_filters(
											'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $linked_profile ), array( 'width' => 225, 'height' => 225 )
										);
									
									$pargs	 = array( 'project_id' => $project_id, 'proposal_id' => $post->ID );
									$submit_proposal  = !empty( $submit_proposal ) ? add_query_arg( $pargs, $submit_proposal ) : '';

									?>
									<div class="wt-userlistinghold wt-featured wt-proposalitem wt-userlistingcontentvtwo" data-id="<?php echo esc_attr($post->ID);?>">
										<div class="wt-proposaldetails">
											<div class="wt-contenthead">
												<div class="wt-title">
													<a><?php the_title();?></a>
													<h2><a target="_blank" href="<?php echo get_the_permalink($project_id);?>"><?php echo get_the_title($project_id);?></a></h2>
												</div>
											</div>										
										</div>
										<div class="wt-rightarea">
											<div class="wt-btnarea wt-status-<?php echo esc_attr( $job_status );?>">
												<?php if( $job_status === 'hired' ) { ?>
													<span class="wt-btn"><?php esc_html_e('Hired','workreap');?></span>
												<?php }elseif( $job_status === 'completed' ) {?>
													<span class="wt-btn"><?php esc_html_e('Completed','workreap');?></span>
												<?php } else if( $job_status !== 'hired' ) { ?>
													
													<?php  if( !empty($allow_proposal_edit) && $allow_proposal_edit == 'yes' ){?>
														<a target="_blank" href="<?php echo esc_attr($submit_proposal);?>" class="wt-btn"><?php echo esc_html_e('Edit Proposal','workreap');?></a>
													<?php }?>
													
													<?php if( !empty($proposal_hiring_status)  && $proposal_hiring_status === 'pending' && $project_status === 'publish' ) { ?>
														<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('milestone', $user_identity,'','listing',$post->ID); ?>" class="wt-btn"><?php echo esc_html_e('Accept Milestones and Start Project','workreap');?></a>
													<?php } 
														if(!empty($job_status) && $job_status === 'cancelled'){
															$status_type	= esc_html__('Cancelled','workreap');
														}else{
															$status_type	= esc_html__('Pending','workreap');
														}
													?>
													<span class="wt-btn" ><?php echo esc_html($status_type);?></span>
												<?php } ?>
											</div>											
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
							do_action('workreap_empty_records_html','',esc_html__( 'There are no proposals, have been submitted yet', 'workreap' ),true);
						}
					?>
				</div>
				<?php 	
					if ( !empty($count_post) && $count_post > $show_posts) {
						workreap_prepare_pagination($count_post, $show_posts);
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'cover-letter');?>