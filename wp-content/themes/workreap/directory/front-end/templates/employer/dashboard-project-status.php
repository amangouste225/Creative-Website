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
global $current_user, $wp_roles, $userdata, $post,$paged;
$user_identity 	 = $current_user->ID;
$url_identity 	 = $user_identity;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$meta_query_args = array();
$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);

$show_posts 		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;

$edit_id			= !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_author		= get_post_field('post_author', $edit_id);
$hire_freelancer_id	= get_post_meta($edit_id,'_hired_freelancer_id',true);
$job_status			= get_post_status( $edit_id );

?>
<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
	<div class="wt-haslayout wt-job-proposals">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle">
				<h2><?php esc_html_e('Manage proposals', 'workreap'); ?></h2>
			</div>
			<div class="wt-dashboardboxcontent wt-rcvproposala">
			<?php 
				if (intval($url_identity) === intval($post_author)) {
					$employer_title = esc_html( get_the_title( $linked_profile ));
				?>
				<div class="wt-userlistinghold wt-featured wt-userlistingvtwo">
					<div class="wt-userlistingcontent">
						<div class="wt-contenthead">
							<div class="wt-title">
								<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
								<h2><a href="<?php echo esc_url( get_the_permalink($edit_id) ); ?>" target="_blank"><?php echo esc_html( get_the_title($edit_id)); ?></a></h2>
							</div>
							<ul class="wt-saveitem-breadcrumb wt-userlisting-breadcrumb">
								<?php do_action('workreap_project_print_project_level', $edit_id); ?>
								<?php do_action('workreap_print_location', $edit_id); ?>
								<?php do_action('workreap_print_project_type', $edit_id); ?>
							</ul>
						</div>
						<div class="wt-rightarea">
							<div class="wt-btnarea">
								<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity, '','proposals'); ?>" class="wt-btn"><?php esc_html_e('View Detail','workreap');?></a>
							</div>
							<?php do_action('workreap_proposals_received_html', $edit_id); ?>
						</div>
					</div>	
				</div>
				<div class="wt-freelancerholder wt-rcvproposalholder">
					<?php if( $job_status === 'hired' ) { ?>
						<?php get_template_part('directory/front-end/templates/employer/dashboard-hired', 'freelancer');?>	
					<?php } ?>	
					<div class="wt-tabscontenttitle">
						<h2><?php esc_html_e('Received proposals', 'workreap'); ?></h2>
					</div>
					<div class="wt-managejobcontent">
					<?php
					$query_args = array('posts_per_page' => $show_posts,
							'post_type' 		=> 'proposals',
							'paged' 		 	=> $paged,
							'suppress_filters'  => false,
						);

					$meta_query_args[] = array(
						'key' 		=> '_project_id',
						'value' 	=> $edit_id,
						'compare' 	=> '='
					);

					$query_relation			  = array('relation' => 'AND',);
					$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);    

					$pquery 		= new WP_Query($query_args);
					$count_post 	= $pquery->found_posts;

					while ($pquery->have_posts()) : $pquery->the_post();
						global $post;
						$author_id 				= get_the_author_meta( 'ID' );  
						$linked_profile 		= workreap_get_linked_profile_id($author_id);
						$freelancer_title 		= esc_html( get_the_title( $linked_profile ));
						$proposed_amount  		= get_post_meta($post->ID, '_amount', true);

						if (function_exists('fw_get_db_post_option')) {
							$proposal_docs = fw_get_db_post_option($post->ID, 'proposal_docs', true);
						}

						$proposal_docs = !empty( $proposal_docs ) ?  count( $proposal_docs ) : 0;
						$freelancer_avatar = apply_filters(
								'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $linked_profile ), array( 'width' => 225, 'height' => 225 )
							);
						?>

						<div class="wt-userlistinghold wt-featured wt-proposalitem">
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
								<?php if( $job_status != 'hired' ) { ?>
									<div class="wt-btnarea">
										<a href="#" onclick="event_preventDefault(event);" class="wt-btn hire-now" data-id="<?php echo esc_attr($post->ID);?>" data-post-id="<?php echo esc_attr($edit_id);?>"><?php esc_html_e('Hire Now','workreap');?></a>
									</div>	
								<?php } ?>											
								<?php do_action('worrketic_proposal_duration_and_amount',$post->ID);?>
								<?php do_action('worrketic_proposal_cover',$post->ID);?>
								<?php do_action('worrketic_proposal_attachments',$post->ID);?>													
							</div>
						</div>		
						<?php 
							endwhile;
							wp_reset_postdata();
						}
					?>
					</div>
				</div>
			</div>
			<?php 	
				if (!empty($count_post) && $count_post > $show_posts) {
					echo '<div class="col-12">';
					workreap_prepare_pagination($count_post, $show_posts);
					echo '</div>';
				}
			?>
		</div>
	</div>
</div>	