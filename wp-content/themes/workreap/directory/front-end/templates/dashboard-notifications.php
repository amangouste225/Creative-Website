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
global $current_user, $wp_roles,$post,$paged,$wpdb;
////testing end
$identity 		= !empty($_GET['identity']) ? $_GET['identity'] : "";
$ref 			= !empty($_GET['ref']) ? $_GET['ref'] :"";

$user_identity 	 = $current_user->ID;
$post_id 		 = workreap_get_linked_profile_id($user_identity);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$show_posts 	 = get_option('posts_per_page') ? get_option('posts_per_page') : 20;
$pg_page 		 = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		 = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
	
//paged works on single pages, page - works on homepage
$paged 		= max($pg_page, $pg_paged);
$order 		= 'DESC';
$sorting 	= 'ID';

$args = array('posts_per_page' => $show_posts,
    'post_type' 		=> 'push_notifications',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'post_status' 		=> array('publish','pending','draft'),
    'author' 			=> $user_identity,
    'paged' 			=> $paged,
    'suppress_filters'  => false
);

$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
	<div class="wt-dashboardbox wt-dashboardinvocies disputes-header">
		<div class="wt-dashboardboxtitle wt-addnew">
			<h2><?php esc_html_e( 'Notification', 'workreap' ); ?></h2>
		</div>
		<div class="wt-notify-listwarap wt-categoriescontentholder">
			<?php if( $query->have_posts() ){ ?>
				<table class="wt-tablecategories wt-tableservice">
					<thead>
						<tr>
							<th></th>
							<th><?php esc_html_e('Notification','workreap');?></th>
							<th><?php esc_html_e('Time','workreap');?></th>
							<th><?php esc_html_e('Action','workreap');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$post_status	= get_post_status($post->ID );
								$post_status	= !empty($post_status) && $post_status === 'publish' ? 'fa fa-envelope-open' : 'fa fa-envelope';
								$date			= get_the_date( get_option( 'date_format' ), $post->ID );
								$time			= get_post_time('U',false,$post->ID,true );
								$date			= human_time_diff( $time, current_time('timestamp') );
								?>
								<tr>
									<td><i class="<?php echo esc_html( $post_status );?>"></i></td>
									<td><?php echo do_action('workreap_push_notification_excerpt',$post->ID,true);?></td>
									<td><?php echo esc_html($date);?>&nbsp;<?php esc_html_e('ago','workreap');?></td>
									<td>
										<div class="wt-actionbtn">
											<a href="#" onclick="event_preventDefault(event);" class="wt-viewinfo viewinfo-notification"  data-id="<?php echo intval( $post->ID );?>"><i class="lnr lnr-eye"></i></a>
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
					<?php do_action('workreap_empty_records_html','wt-empty-notifications',esc_html__( 'No recent notifications found', 'workreap' )); ?>
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