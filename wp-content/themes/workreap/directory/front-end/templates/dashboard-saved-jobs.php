<?php
/**
 *
 * The template part for displaying saved jobs
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$post_id 		 	= $linked_profile;
$show_posts 		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 			= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 			= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage

$paged 				= max($pg_page, $pg_paged);
$order 				= 'DESC';
$sorting 			= 'ID';
$save_projects_ids	= get_post_meta( $post_id, '_saved_projects', true);
$post_array_ids		= !empty($save_projects_ids) ? $save_projects_ids : array(0);


$args = array(
	'posts_per_page' 	=> $show_posts,
    'post_type' 		=> 'projects',
	'post_status' 		=> array('publish','pending','completed','cancelled','hired'),
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'paged' 			=> $paged,
	'post__in' 			=> $post_array_ids,
    'suppress_filters' 	=> false
);

$query 		= new WP_Query($args);
$total_job = $query->found_posts;
if( $query->have_posts() ){
	$emptyClass = '';
} else{
	$emptyClass = 'wt-emptydata-holder';
}
?>
<div class="wt-haslayout wt-saved-jobs">
	<div class="wt-personalskillshold tab-pane active fade show">
		<div class="wt-yourdetails">
			<div class="wt-tabscontenttitle wt-addnew">
				<h2><?php esc_html_e('Saved jobs listing','workreap');?></h2>
				<?php if( $query->have_posts() ) { ?>
					<a href="#" onclick="event_preventDefault(event);" data-post-id="<?php echo intval($post_id);?>" data-itme-type="_saved_projects" class="wt-clicksave wt-clickremoveall">
						<i class="lnr lnr-cross"></i>
						<?php esc_html_e('Remove All Jobs','workreap');?>
					</a>
				<?php } ?>
			</div>
			<div class="wt-dashboradsaveitem <?php echo esc_attr( $emptyClass );?>">
				<?php
					if( $query->have_posts() ){
						while ($query->have_posts()) : $query->the_post();
							global $post;
							$author_id 		= get_the_author_meta( 'ID' );  
							$linked_profile = workreap_get_linked_profile_id($author_id);
							$employer_title = esc_html( get_the_title( $linked_profile ));
							?>
							<div class="wt-userlistinghold wt-featured wt-dashboradsaveditems">
								<?php do_action('workreap_project_print_featured', $post->ID); ?>
								<div class="wt-userlistingcontent">
									<div class="wt-contenthead wt-dashboardsavehead">
										<div class="wt-title">
											<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
											<h2><a href="<?php echo esc_url(get_the_permalink()); ?>" target="_blank"><?php echo esc_html(get_the_title()); ?></a></h2>
										</div>
										<ul class="wt-saveitem-breadcrumb wt-userlisting-breadcrumb">
											<?php do_action('workreap_project_print_project_level', $post->ID); ?>
											<?php do_action('workreap_print_location', $post->ID); ?>
											<?php do_action('workreap_print_project_type', $post->ID); ?>
											<?php do_action('workreap_trash_icon_project_html' , $post_id , $post->ID ,'_saved_projects'); ?>		
										</ul>
									</div>
								</div>	
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
					} else{
						do_action('workreap_empty_records_html','wt-empty-saved',esc_html__( 'You have not any jobs in your favorite list.', 'workreap' ));
					}
				?>								
			</div>
			<?php
				if (!empty($total_job) && $total_job > $show_posts) {
					workreap_prepare_pagination($total_job, $show_posts);
				}
			?>
		</div>
	</div>
</div>