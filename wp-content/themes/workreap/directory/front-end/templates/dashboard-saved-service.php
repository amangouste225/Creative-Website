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
$save_projects_ids	= get_post_meta( $post_id, '_saved_services', true);
$post_array_ids		= !empty($save_projects_ids) ? $save_projects_ids : array(0);


$args = array(
	'posts_per_page' 	=> $show_posts,
    'post_type' 		=> 'micro-services',
	'post_status' 		=> array('publish','pending','completed','cancelled','hired'),
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'paged' 			=> $paged,
	'post__in' 			=> $post_array_ids,
    'suppress_filters' 	=> false
);

$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;
?>
<div class="wt-haslayout wt-saved-jobs">
	<div class="wt-personalskillshold tab-pane active fade show">
		<div class="wt-yourdetails">
			<div class="wt-tabscontenttitle wt-addnew">
				<h2><?php esc_html_e('Saved services listing','workreap');?></h2>
				<?php if( $query->have_posts() ) { ?>
					<a href="#" onclick="event_preventDefault(event);" data-post-id="<?php echo intval($post_id);?>" data-itme-type="_saved_services" class="wt-clicksave wt-clickremoveall">
						<i class="lnr lnr-cross"></i>
						<?php esc_html_e('Remove All Services','workreap');?>
					</a>
				<?php } ?>
			</div>
			<div class="wt-dashboradsaveitem">
				<div class="wt-dashboardboxcontent wt-categoriescontentholder">
					<?php if( $query->have_posts() ){ ?>
						<table class="wt-tablecategories wt-tableservice">
							<tbody>
								<?php 
									while ($query->have_posts()) : $query->the_post();
										global $post;
										$employer_id		= get_post_field ('post_author', $post->ID);
									?>
										<tr>
											<td>
												<?php do_action('workreap_service_listing_basic', $post->ID ); ?>
											</td>
											<td>
												<div class="wt-actionbtn">
													<a href="#" onclick="event_preventDefault(event);" data-post-id="<?php echo intval($post_id);?>" data-item-id="<?php echo intval($post->ID);?>" data-itme-type="_saved_services"  class="wt-deleteinfo wt-clickremove"><i class="lnr lnr-trash"></i></a>
													
												</div>
											</td>
										</tr>
								<?php
								endwhile;
								wp_reset_postdata();
								?>	
							</tbody>
						</table>
					<?php
						} else{
							do_action('workreap_empty_records_html','wt-empty-saved',esc_html__( 'You have not saved any services to your favorite list.', 'workreap' ));
						}
						if (!empty($count_post) && $count_post > $show_posts) {
							workreap_prepare_pagination($count_post, $show_posts);
						}
					?>
					
				</div>
			</div>
		</div>
	</div>
</div>