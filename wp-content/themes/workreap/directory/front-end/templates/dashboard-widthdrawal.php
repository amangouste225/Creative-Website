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
global $current_user,$paged;
$user_identity 	 	= $current_user->ID;
$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);
$show_posts = get_option('posts_per_page');
$order 		= 'DESC';
$sorting 	= 'ID';

$args = array(
	'posts_per_page' 	=> $show_posts,
	'paged' 		 	=> $paged,
    'post_type' 		=> 'withdraw',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'post_status' 		=> array('publish','pending'),
	'author' 			=> $user_identity,
    'suppress_filters'  => false
);

$query 				= new WP_Query($args);
$total_withdraw 	= $query->found_posts;
$payrols_list		= workreap_get_payouts_lists();
?>
<div class="wt-userexperience wt-followcompomy">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Your Payments','workreap');?></h2>
	</div>
	<div class="wt-dashboardboxcontent wt-categoriescontentholder wt-categoriesholder wt-emptydata-holder">
		<?php if ($query->have_posts()) {?>
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
					<?php 
						$count_item		= 0;
						while ($query->have_posts()) : $query->the_post();
							global $post;
							$price	= get_post_meta( $post->ID, '_withdraw_amount', true );
							$price	= !empty($price) ? $price : '';
							$publish_date		= get_the_date('',$post->ID);
							$account_type_key	= get_post_meta( $post->ID, '_payment_method', true );
							$account_type		= !empty($payrols_list[$account_type_key]['title']) ? $payrols_list[$account_type_key]['title'] : $account_type_key;
							$status				= get_post_status( $post->ID );
							$status_data		= !empty($status) && $status === 'pending' ? esc_html__('Pending','workreap') : esc_html__('Processed','workreap');
							$account_details	= get_post_meta($post->ID, '_account_details',true);

						?>
						<tr>
							<td><?php echo esc_html($publish_date);?></td>
							<td>
								<?php 
								$db_saved	= maybe_unserialize( $account_details );
								foreach ($payrols_list[$account_type_key]['fields'] as $key => $field) {
									if(!empty($field['show_this']) && $field['show_this'] == true){
										$current_val	= !empty($db_saved[$key]) ? $db_saved[$key] : 0;
									?>
									<span class="wt-payout-bank">
										<strong><?php echo esc_html($field['title']);?></strong>
										<em><?php echo esc_html($current_val);?><em>
									</span>
								<?php }}?>	
							</td>
							<td><?php workreap_price_format($price);?></td>
							<td><?php echo esc_html($account_type);?>&nbsp;(<?php echo esc_html($status_data);?>)</td>
						</tr>
					<?php $count_item ++;
					endwhile;
						wp_reset_postdata();
					?>
				</tbody>
			</table>
			<?php 
				if ( !empty($total_withdraw) && $total_withdraw > $show_posts ) {
					 workreap_prepare_pagination($total_withdraw, $show_posts);
				}
			?>
		<?php 
			} else {
				do_action('workreap_empty_records_html','',esc_html__( 'No payments found yet.', 'workreap' ));
			} ?>
	</div>									
</div>