<?php
/**
 *
 * The template part for displaying Cancelled services
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
$paged 			= max($pg_page, $pg_paged);

$order 			= 'DESC';
$sorting 		= 'ID';
$search_keyword  = !empty($_GET['keyword']) ? $_GET['keyword'] : "";

$args 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'services-orders',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('cancelled'),
					'author' 			=> $user_identity,
					'paged' 			=> $paged,
					'suppress_filters' 	=> false,
					's'                 => $search_keyword
				);
$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
	<div class="wt-dashboardbox wt-dashboardservcies">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e('Cancelled services','workreap');?></h2>
			<?php do_action('workreap_dashboard_search_keyword','services','cancelled');?>
		</div>
		<div class="wt-dashboardboxcontent wt-categoriescontentholder">
			<?php if( $query->have_posts() ){ ?>
			<table class="wt-tablecategories wt-tableservice">
				<thead>
					<tr>
						<th><?php esc_html_e('Service name','workreap');?></th>
						<th><?php esc_html_e('Offered By','workreap');?></th>
						<th><?php esc_html_e('Reason','workreap');?></th>
						<th><?php esc_html_e('Action','workreap');?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
						while ($query->have_posts()) : $query->the_post();
							global $post;
							$service_id			= get_post_meta($post->ID,'_service_id',true);
							$service_author		= get_post_meta($post->ID,'_service_author',true);
							$order_id			= get_post_meta($post->ID,'_order_id',true);
							?>
							<tr>
								<td><?php do_action('workreap_service_listing_basic', $service_id,'','',$order_id ); ?></td>
								<td><?php do_action('workreap_service_freelancer_html', $service_author ); ?></td>
								<td>
									<span class="bt-content">
										<div class="wt-actionbtn">
											<a href="#" onclick="event_preventDefault(event);" class="wt-viewinfo wt-btnhistory wt-reasonbtn wt-service-reason" data-id="<?php echo intval($post->ID);?>"><?php esc_html_e('Show Reason','workreap');?></a>
										</div>
									</span>
								</td>
								<td>
									<div class="wt-actionbtn">
										<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity, '','history',$post->ID); ?>" class="wt-viewinfo wt-btnhistory"><?php esc_html_e('Show History','workreap');?></a>
									</div>
								</td>
							</tr>
					<?php
						endwhile;
						wp_reset_postdata();
					?>	
				</tbody>
			</table>
			<?php } else{ ?>
				<div class="wt-emptydata-holder">
					<?php do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No cancelled service yet.', 'workreap' )); ?>
				</div>
			<?php } ?>
			<?php
				if (!empty($count_post) && $count_post > $show_posts) {
					workreap_prepare_pagination($count_post, $show_posts);
				}
			?>

		</div>
	</div>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'service-cancelled-reason');?>