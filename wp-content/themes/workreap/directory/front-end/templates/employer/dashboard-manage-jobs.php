<?php
/**
 *
 * The template part for displaying jobs
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post,$paged;
$user_identity 	 = $current_user->ID;
$post_id 		 = workreap_get_linked_profile_id($user_identity);

$post_job		 = Workreap_Profile_Menu::workreap_profile_menu_link('post_job', $user_identity,true); 
$show_posts 	 = get_option('posts_per_page') ? get_option('posts_per_page') : 10;

$pg_page 		 = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		 = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var

//paged works on single pages, page - works on homepage
$paged 		= max($pg_page, $pg_paged);
$order 		= 'DESC';
$sorting 	= 'ID';
$search_keyword  = !empty($_GET['keyword']) ? $_GET['keyword'] : "";

$args = array('posts_per_page' => $show_posts,
    'post_type' 		=> 'projects',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'post_status' 		=> array('publish','pending'),
    'author' 			=> $user_identity,
    'paged' 			=> $paged,
    'suppress_filters'  => false,
	's'                 => $search_keyword
);

$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;
if( $query->have_posts() ){
	$emptyClass = '';
} else{
	$emptyClass = 'wt-emptydata-holder';
}

if (function_exists('fw_get_db_settings_option')) {
    $allow_delete_project        = fw_get_db_settings_option('allow_delete_project', $default_value = null);
}
?>
<div class="wt-haslayout wt-manage-jobs">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle wt-titlewithsearch">
				<h2><?php esc_html_e('Manage Jobs','workreap');?></h2>
				<?php do_action('workreap_dashboard_search_keyword','jobs','posted');?>
			</div>
			<div class="wt-dashboardboxcontent wt-jobdetailsholder">
				<div class="wt-freelancerholder">
					<div class="wt-tabscontenttitle">
						<h2><?php esc_html_e('Posted Jobs','workreap');?></h2>
					</div>
					<div class="wt-managejobcontent <?php echo esc_attr($emptyClass);?>">
					<?php
						if( $query->have_posts() ){
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$author_id 		= get_the_author_meta( 'ID' );  
								$linked_profile = workreap_get_linked_profile_id($author_id);
								$employer_title = esc_html( get_the_title( $linked_profile ) );
								$job_status				= get_post_status( $post->ID );
							?>
							<div class="wt-userlistinghold wt-featured wt-userlistingvtwo">
								<?php do_action('workreap_project_print_featured', $post->ID); ?>
								<div class="wt-userlistingcontent">
									<div class="wt-contenthead">
										<div class="wt-title">
											<?php do_action('workreap_get_verification_check', $linked_profile, $employer_title ); ?>
											<h2><?php the_title(); ?></h2>
										</div>
										<ul class="wt-saveitem-breadcrumb wt-userlisting-breadcrumb">
											<?php do_action('workreap_project_print_project_level', $post->ID); ?>
											<?php do_action('workreap_print_location', $post->ID); ?>
											<?php do_action('workreap_print_project_type', $post->ID); ?>
										</ul>
									</div>
									<div class="wt-rightarea">
										<div class="wt-btnarea">
											<?php if( get_post_status( $post->ID ) !== 'pending' && get_post_status( $post->ID ) !== 'draft' ){?>
												<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity, '','proposals',$post->ID); ?>" class="wt-btn"><?php esc_html_e('View proposals','workreap');?></a>
											<?php }?>
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity, '','edit',$post->ID); ?>" class="wt-btn"><?php esc_html_e('Edit job','workreap');?></a>
											<?php if(!empty($allow_delete_project) && $allow_delete_project === 'yes' && $job_status === 'publish'){?>
												<a href="javascript:void(0);" class="wt-btn delete-emp-project" data-id="<?php echo esc_attr($post->ID);?>"><?php esc_html_e('Delete job','workreap');?></a>
											<?php }?>
										</div>
										<?php do_action('workreap_proposals_received_html', $post->ID); ?>
									</div>
								</div>	
							</div>
							<?php
							endwhile;
							wp_reset_postdata();
						} else{
							do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'You have no job yet.', 'workreap' ));
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