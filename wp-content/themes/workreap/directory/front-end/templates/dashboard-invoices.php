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
global $current_user, $wp_roles,$userdata,$post,$paged,$woocommerce;
global $wpdb;

////testing end
$identity 		= !empty($_GET['identity']) ? $_GET['identity'] : "";
$ref 			= !empty($_GET['ref']) ? $_GET['ref'] :"";

$show_posts		= get_option('posts_per_page');
$date_format	= get_option('date_format');
$time_format	= get_option('time_format');
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);
$current_page 	= $paged;
$price_symbol	= workreap_get_current_currency();
$user_type					= workreap_get_user_type( $identity );
$section_wrapper = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right';
if ( is_active_sidebar( 'sidebar-dashboard' ) ) {
	$section_wrapper = 'col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-9 float-right';
}
?>
<div class="<?php echo esc_attr($section_wrapper);?>">
	<div class="wt-dashboardbox wt-dashboardinvocies">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e( 'Invoices', 'workreap' ); ?></h2>
		</div>
		<div class="wt-dashboardboxcontent wt-categoriescontentholder wt-categoriesholder">
			<table class="wt-tablecategories">
			<?php 
			if (class_exists('WooCommerce')) {
					if(!empty($user_type) && $user_type === 'freelancer'){
						$customer_orders = wc_get_orders( apply_filters( 'woocommerce_my_account_my_orders_query', 
															array(
																  'page' 			  => $current_page, 
																  'paginate' 		  => true,
																  'limit' 			  => $show_posts,
																  'freelancer_id'     => $current_user->ID, 
																 // 'customer' 		  => $current_user->ID, //remove this
																 ) 
														   ) 
														);
					}else{
						$customer_orders = wc_get_orders( apply_filters( 'woocommerce_my_account_my_orders_query', 
															array( 'customer' 	=> $current_user->ID, 
																  'page' 		=> $current_page, 
																  'paginate' 	=> true,
																  'limit' 		=> $show_posts,
																 ) 
														   ) 
														);
					}
				?>
					<thead>
						<tr>
							<th><?php esc_html_e( 'Order ID', 'workreap' ); ?></th>
							<th><?php esc_html_e( 'Created date', 'workreap' ); ?></th>
							<th><?php esc_html_e( 'Amount', 'workreap' ); ?></th>
							<th><?php esc_html_e( 'Action', 'workreap' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if ( !empty(  $customer_orders->orders ) ) {
							$count_post 	= count($customer_orders->orders); 
							foreach ( $customer_orders->orders as $customer_order ){
								$order      	= wc_get_order( $customer_order );
								$date_time		= wc_format_datetime( $order->get_date_created(), get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );
								?>
								<tr>
									<td><?php echo intval($order->get_id());?></td>
									<td><?php echo esc_html( $date_time ); ?></td>
									<td>
										<?php 
										if(!empty($user_type) && $user_type === 'freelancer'){
											if(!empty($order->get_items())){
												foreach ( $order->get_items() as $item_id => $item ) { 
													if(function_exists('wmc_revert_price')){
												        workreap_price_format(wmc_revert_price($item->get_total(),$item->get_currency()));
												    }else{
												        workreap_price_format($item->get_total());        
												    }
												}
											}
										} else {
											if(function_exists('wmc_revert_price')){
												workreap_price_format(wmc_revert_price($order->get_total(),$order->get_currency()));
											}else{
												workreap_price_format($order->get_total());        
											}
										}?>
									</td>
									<td>
										<div class="wt-actionbtn">
											<a target="_blank" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $identity,'','invoice',intval($order->get_id())); ?>" class="wt-addinfo wt-skillsaddinfoview"><?php esc_html_e( 'View invoice', 'workreap' ); ?></a>
										</div>
									</td>
								</tr>
							<?php }?>
					<?php } ?>
						</tbody>
					</table>
					<?php	} else{?>
						<?php do_action('workreap_empty_records_html','',esc_html__( 'WooCoomerce should be installed for payments. Please contact to administrator.', 'workreap' ),true); ?>		
					<?php }?>
				<?php 
					if ( empty(  $customer_orders->orders ) ) { 
						do_action('workreap_empty_records_html','',esc_html__( 'No order has been made yet.', 'workreap' ),true);
					} 
				?>
											
			<?php if ( 1 < $customer_orders->max_num_pages ) {?>
				<nav class="wt-pagination woo-pagination">
					<?php 
						$big = 999999999;
						echo paginate_links( array(
								'base' => str_replace( $big, '%#%', esc_url_raw( get_pagenum_link( $big ) ) ),
								'format' => '?paged=%#%',
								'current' => max( 1, get_query_var('paged') ),
								'total' => $customer_orders->max_num_pages,
								'prev_text' => '<i class="lnr lnr-chevron-left"></i>',
								'next_text' => '<i class="lnr lnr-chevron-right"></i>'
							) );
					?>
				</nav>
			<?php }?>
		</div>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-3 float-left">
	<?php get_template_part('directory/front-end/templates/dashboard', 'sidebar-ads'); ?>	
</div>
<?php 
	$script = "
	     jQuery('.wt-tablecategories').basictable({
		    breakpoint: 767
		});
	";
	wp_add_inline_script( 'basictable', $script, 'after' );
