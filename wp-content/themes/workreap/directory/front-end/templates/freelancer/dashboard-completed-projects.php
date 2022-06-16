<?php
/**
 *
 * The template part for displaying  Completed jobs
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$paged;
$user_identity 	 = $current_user->ID;
$post_id 		 = workreap_get_linked_profile_id($user_identity);

$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$milestone	= array();
if (function_exists('fw_get_db_settings_option')) {
	$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
}
$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

$paged 			= max($pg_page, $pg_paged);

$order 			= 'DESC';
$sorting 		= 'ID';
$search_keyword  = !empty($_GET['keyword']) ? $_GET['keyword'] : "";

$args 			= array(
					'posts_per_page' => $show_posts,
					'post_type' => 'projects',
					'orderby' => $sorting,
					'order' => $order,
					'post_status' => array('completed'),
					'paged' => $paged,
					'suppress_filters' => false,
					's'                 => $search_keyword,
				);

$meta_query_args[] = array(
						'key' 		=> '_freelancer_id',
						'value' 	=> $post_id,
						'compare' 	=> '='
					);
$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);
$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;
if( $query->have_posts() ){
	$emptyClass = '';
} else{
	$emptyClass = 'wt-emptydata-holder';
}
?>
<div class="wt-haslayout wt-manage-jobs">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle wt-titlewithsearch">
				<h2><?php esc_html_e('Manage completed project','workreap');?></h2>
				<?php do_action('workreap_dashboard_search_keyword','projects','completed');?>
			</div>
			<div class="wt-dashboardboxcontent wt-jobdetailsholder">
				<div class="wt-freelancerholder">
					<div class="wt-tabscontenttitle">
						<h2><?php esc_html_e('Completed Jobs','workreap');?></h2>
					</div>
					<div class="wt-managejobcontent <?php echo esc_attr($emptyClass);?>">
					<?php
						if( $query->have_posts() ){
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$author_id 		= get_the_author_meta( 'ID' );  
								$linked_profile = workreap_get_linked_profile_id($author_id);
								$employer_title = esc_html( get_the_title( $linked_profile ));
								$milestone_option	= 'off';
								if( !empty($milestone) && $milestone ==='enable' ){
									$milestone_option	= get_post_meta( $post->ID, '_milestone', true );
								}
								$proposal_id	= get_post_meta( $post->ID, '_proposal_id', true );
							?>
							<div class="wt-userlistinghold wt-featured wt-userlistingvtwo">
								<?php do_action('workreap_project_print_featured', $post->ID); ?>
								<div class="wt-userlistingcontent wt-userlistingcontentvtwo">
									<div class="wt-contenthead">
										<div class="wt-title">
											<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
											<h2><a href="<?php the_permalink(); ?>" target="_blank"><?php echo esc_html(get_the_title()); ?></a></h2>
										</div>
										<ul class="wt-saveitem-breadcrumb wt-userlisting-breadcrumb">
											<?php do_action('workreap_project_print_project_level', $post->ID); ?>
											<?php do_action('workreap_print_location', $post->ID); ?>
											<?php do_action('workreap_print_project_type', $post->ID); ?>
											<?php do_action('workreap_print_project_duration_html', $post->ID); ?>
										</ul>
									</div>
									<div class="wt-rightarea">
										<div class="wt-btnarea">
											<span><?php esc_html_e('Project Complete','workreap');?></span>
											<?php if(!empty($milestone_option) && $milestone_option === 'on' && !empty($proposal_id) ){ ?>
												<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('milestone', $user_identity, '','listing',$proposal_id); ?>" class="wt-btn"><?php esc_html_e('View history','workreap');?></a>
											<?php } else{ ?>
												<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','history',$post->ID); ?>" class="wt-btn"><?php esc_html_e('View history','workreap');?></a>
											<?php } ?>
										</div>
										<?php do_action('workreap_project_employer_html', $post->ID); ?>
									</div>
								</div>	
							</div>
							<?php
							endwhile;
							wp_reset_postdata();
						} else{
							do_action('workreap_empty_records_html','wt-empty-project',esc_html__( 'No completed project.', 'workreap' ));
						}?>								
					</div>
				</div>
				<?php
					if (!empty($count_post) && $count_post > $show_posts) {
						workreap_prepare_pagination($count_post, $show_posts);
					}
				?>
			</div>
		</div>
	</div>
</div>