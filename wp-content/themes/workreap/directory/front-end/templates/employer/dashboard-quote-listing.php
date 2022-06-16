<?php
/**
 *
 * The template part for displaying  Ongoing services
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
					'post_type' 		=> 'send-quote',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> 'publish',
					'paged' 			=> $paged,
					'suppress_filters' 	=> false,
					's'                 => $search_keyword
				);

$meta_query_args = array();
$meta_query_args[]  = array(
	'key' 			=> 'hiring_status',
	'value' 		=> 'pending',
);

$meta_query_args[]  = array(
	'key' 			=> 'employer',
	'value' 		=> $user_identity,
);

if (!empty($meta_query_args)) {
	$query_relation 		= array('relation' => 'AND',);
	$meta_query_args 		= array_merge($query_relation, $meta_query_args);
	$args['meta_query'] = $meta_query_args;
}

$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
	<div class="wt-dashboardbox wt-dashboardservcies wt-quote-wrapper">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e('Quote listing','workreap');?></h2>
			<?php do_action('workreap_dashboard_search_keyword','services','quote_listing');?>
		</div>
		<div class="wt-dashboardboxcontent wt-categoriescontentholder">
			<?php if( $query->have_posts() ){ ?>
				<table class="wt-tablecategories wt-tableservice">
					<thead>
						<tr>
							<th><?php esc_html_e('Service name','workreap');?></th>
							<th><?php esc_html_e('Send by','workreap');?></th>
							<th><?php esc_html_e('Action','workreap');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$service_id			= get_post_meta($post->ID,'service',true);
								$service_author		= !empty( $post->post_author ) ? $post->post_author : '';
								?>
								<tr>
									<td><?php do_action('workreap_quote_listing_basic', $service_id,$post->ID); ?></td>
									<td><?php do_action('workreap_service_employer_html', $service_author ); ?></td>
									<td>
										<div class="wt-actionbtn">
											<a href="#" data-id="<?php echo esc_attr($post->ID);?>" class="wt-accept-quote wt-btn"><?php esc_html_e('Accept and pay','workreap');?></a>
											<a href="#" data-toggle="modal" data-target="#wt-decline-quote" data-id="<?php echo esc_attr($post->ID);?>" class="wt-decline-quote wt-btn"><?php esc_html_e('Decline','workreap');?></a>
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
					<?php do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No quote found', 'workreap' )); ?>
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
<div class="wt-uploadimages modal fade" id="wt-decline-quote" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="wt-modaldialog modal-dialog" role="document">
		<div class="wt-modalcontent modal-content">
			<div class="wt-boxtitle">
				<h2><?php esc_html_e('Decline quote','workreap');?><i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i></h2>
			</div>
			<div class="wt-modalbody modal-body">
				<form class="decline-quote-form">
					<textarea id="empty-reason" placeholder="<?php esc_attr_e('Add reason','workreap');?>" class="form-control" name="quote[reason]"></textarea>
					<button type="submit" class="wt-btn decline-quote"><?php esc_html_e( 'Decline quote', 'workreap' );?></button>
				</form>
			</div>
		</div>
	</div>
</div>
