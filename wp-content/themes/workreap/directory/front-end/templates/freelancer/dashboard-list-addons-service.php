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

$show_posts 	 = get_option('posts_per_page') ? get_option('posts_per_page') : 10;

$pg_page 		 = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		 = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var

//paged works on single pages, page - works on homepage
$paged 		= max($pg_page, $pg_paged);
$order 		= 'DESC';
$sorting 	= 'ID';

$search_keyword  = !empty($_GET['keyword']) ? $_GET['keyword'] : "";

$args = array('posts_per_page' => $show_posts,
    'post_type' 		=> 'addons-services',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'post_status' 		=> array('publish'),
    'author' 			=> $user_identity,
    'paged' 			=> $paged,
    'suppress_filters'  => false,
	's'                 => $search_keyword
);

$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;

if (function_exists('fw_get_db_post_option') ) {
	$remove_service_addon		= fw_get_db_settings_option('remove_service_addon');
}

$remove_service_addon	= !empty($remove_service_addon) ? $remove_service_addon : 'no';

if(!empty($remove_service_addon) && $remove_service_addon === 'no'){?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
	<div class="wt-dashboardbox wt-dashboardservcies addon-list">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e('Addons Services Listing','workreap');?></h2>
			<?php do_action('workreap_dashboard_search_keyword','addons_service','listing');?>
			<div class="wt-rightarea">
				<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('addons_service', $user_identity); ?>" class="wt-btn"><?php esc_html_e('Add New','workreap');?></a>
			</div>
		</div>
		<div class="wt-dashboardboxcontent wt-categoriescontentholder">
			<?php if( $query->have_posts() ){ ?>
				<table class="wt-tablecategories wt-tableservice">
					<thead>
						<tr>
							<th><?php esc_html_e('Service name','workreap');?></th>
							<th><?php esc_html_e('Service Status','workreap');?></th>
							<th><?php esc_html_e('Price','workreap');?></th>
							<th><?php esc_html_e('Action','workreap');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$service_title		= get_the_title( $post->ID );
								$db_price			= 0;
								if (function_exists('fw_get_db_post_option')) {
									$db_price   = fw_get_db_post_option($post->ID,'price');
								}
											 
								$perma_link		= get_the_permalink($post->ID);
								$post_status	= get_post_status($post->ID);
								$post_status	= workreap_get_status_title($post_status);
								?>
								<tr>
									<td>
										<div class="wt-service-tabel">
											<div class="wt-freelancers-content">
												<div class="dc-title">
													<h3><?php echo esc_html( $service_title );?></h3>
												</div>
											</div>
										</div>
									</td>
									<td><span><?php echo esc_html( $post_status );?></span></td>
									<td><span><?php workreap_price_format($db_price);?></span></td>
									<td>
										<div class="wt-actionbtn">
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('addons_service', $user_identity, '','edit',$post->ID); ?>" class="wt-addinfo">
												<i class="lnr lnr-pencil"></i>
											</a>
											<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval( $post->ID );?>" class="wt-deleteinfo wt-delete-addon-service"><i class="lnr lnr-trash"></i></a>
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
					<?php do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No posted addons service yet.', 'workreap' )); ?>
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
<?php }
