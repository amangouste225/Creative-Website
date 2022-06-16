<?php
/**
 *
 * The template part for payments
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
$payments			= workreap_get_payments_freelancer($user_identity);

$table_name 		= $wpdb->prefix . "wt_payouts_history";
$earning_sql		= "SELECT * FROM $table_name where ( user_id =".$user_identity." AND ( status= 'completed' || status= 'inprogress' ))";
$total_query 		= "SELECT COUNT(1) FROM (${earning_sql}) AS combined_table";

$total 				= $wpdb->get_var( $total_query );
$items_per_page 	= get_option('posts_per_page');
$page 				= isset( $_GET['epage'] ) ? abs( (int) $_GET['epage'] ) : 1;
$offset 			= ( $page * $items_per_page ) - $items_per_page;

$payments 			= $wpdb->get_results( $earning_sql . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}" );
$total_pages		= ceil($total / $items_per_page);
$date_formate		= get_option('date_format');
$payrols_list		= workreap_get_payouts_lists();
?>
<div class="wt-userexperience wt-followcompomy">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Your Payments','workreap');?></h2>
	</div>
	<div class="wt-dashboardboxcontent wt-categoriescontentholder wt-categoriesholder wt-emptydata-holder">
		<?php if( !empty($payments) ) {?>
			<table class="wt-tablecategories">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Date', 'workreap' ); ?></th>
						<th><?php esc_html_e( 'Payout Details', 'workreap' ); ?></th>
						<th><?php esc_html_e( 'Amount', 'workreap' ); ?></th>
						<th><?php esc_html_e( 'Payment Method', 'workreap' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $payments as $payment ) {
						$payment_mode	= !empty( $payment->payment_method ) ? $payment->payment_method : 'paypal';
						$payrol_title 	= !empty( $payrols_list[$payment_mode]['title'] ) ? $payrols_list[$payment_mode]['title'] : '';
						
						$status		= !empty($payment->status) && $payment->status == 'inprogress' ? esc_html__('In Progress','workreap') : esc_html__('Processed','workreap');
						if( !empty( $payment->payment_method ) && $payment->payment_method === 'bacs' ){
							$paymentdetails	 = '';
							$payrols_fields	= !empty( $payrols_list['bacs']['fields'] ) ? $payrols_list['bacs']['fields'] : array();
							$bank_detail	= !empty( $payment->payment_details ) ? maybe_unserialize($payment->payment_details) : array();

							if( !empty( $payrols_fields ) ){
								foreach( $payrols_fields as $key => $pay ){
									if( !empty( $bank_detail[$key] ) ){
										$paymentdetails	.= '<span class="wt-payout-bank"><strong>'.$pay['placeholder'].':</strong> <em>'.$bank_detail[$key].'</em></span>';
									}
								}
							}	
						} else{
							$paymentdetails	= $payment->paypal_email;
						}
						?>
						<tr>
							<td><?php echo date($date_formate,strtotime($payment->processed_date));?></td>
							<td><?php echo do_shortcode($paymentdetails);?></td>
							<td><?php echo esc_html($payment->currency_symbol.$payment->amount);?></td>
							<td><?php echo esc_html($payrol_title);?>&nbsp;(<?php echo esc_html($status);?>)</td>
						</tr>
					<?php }?>
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
				do_action('workreap_empty_records_html','',esc_html__( 'No payments found yet.', 'workreap' ));
			} ?>
	</div>									
</div>