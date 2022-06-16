<?php
/**
 *
 * The template part for displaying the freeelancer earning
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$wpdb;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$user_type			= apply_filters('workreap_get_user_type', $user_identity );
$date_formate		= get_option('date_format');

$table_name 		= $wpdb->prefix . "wt_earnings";
$earning_sql		= "SELECT * FROM $table_name where user_id =".$user_identity." And ( status= 'completed' ||  status= 'processed' )";
$total_query 		= "SELECT COUNT(1) FROM (${earning_sql}) AS combined_table";

$total 				= $wpdb->get_var( $total_query );
$items_per_page 	= get_option('posts_per_page');
$page 				= isset( $_GET['epage'] ) ? abs( (int) $_GET['epage'] ) : 1;
$offset 			= ( $page * $items_per_page ) - $items_per_page;

$earnings 			= $wpdb->get_results( $earning_sql . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}" );
$total_pages		= ceil($total / $items_per_page);
$currency			= workreap_get_current_currency();
?>
<div class="col-12 col-sm-12 col-md-12 col-lg-12">
	<div class="wt-dashboardbox wt-earningsholder wt-sectionspacenone">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e('Past Earnings','workreap');?></h2>
		</div>
		<div class="wt-dashboardboxcontent all-earnings">
			<?php if(!empty( $earnings )) {?>
					<table class="wt-tablecategories">
						<thead>
							<tr>
								<th><?php esc_html_e('Project Title','workreap');?></th>
								<th><?php esc_html_e('Date','workreap');?></th>
								<th><?php esc_html_e('Earnings','workreap');?></th>
							</tr>
						</thead>
						<tbody>
						<?php 
							foreach( $earnings as $earning ) { 
								$project_title	= !empty($earning->project_id) ? esc_html( get_the_title($earning->project_id)) :"";
								$symbol 		= !empty($currency['symbol'] ) ? $currency['symbol'] : '$';
								$amount			= !empty($earning->freelancer_amount) ? $earning->freelancer_amount :0;
								$timestamp		= !empty($earning->timestamp) ? $earning->timestamp :000000;
							?>
							<tr class="wt-earning-contents">
								<td class="wt-earnig-single"><?php echo esc_html($project_title);?></td>
								<td><?php echo date_i18n($date_formate ,intval($timestamp));?></td>
								<td><?php workreap_price_format($amount);?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<?php if( !empty( $total_pages ) && $total_pages > 0 ) { ?>
					<nav class="wt-pagination woo-pagination">
						<?php 
							echo paginate_links( array(
								'base' 		=> add_query_arg( 'epage', '%#%' ),
								'format' 	=> '',
								'prev_text' => '<i class="lnr lnr-chevron-left"></i>',
								'next_text' => '<i class="lnr lnr-chevron-right"></i>',
								'total' 	=> $total_pages,
								'current' 	=> $page
							));
						?>
					</nav>
					<?php } ?>
				<?php 
					
				} else {
					do_action('workreap_empty_records_html','wt-empty-person',esc_html__( 'No Earning has been made yet.', 'workreap' ),true);
				}
			?>
		</div>
	</div>
</div>