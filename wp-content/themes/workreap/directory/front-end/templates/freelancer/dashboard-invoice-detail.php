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

$identity 		= !empty($_GET['identity']) ? $_GET['identity'] : "";
$ref 			= !empty($_GET['ref']) ? $_GET['ref'] :"";
$order_id 		= !empty($_GET['id']) ? $_GET['id'] : "";

if(empty($order_id)){return;}

$order      	= wc_get_order( $order_id );
$data_created	= wc_format_datetime( $order->get_date_created(), get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );
$get_date_paid	= $order->get_date_paid();
$get_date_paid	= $order->get_date_paid();
$get_total		= $order->get_total();
$get_taxes		= $order->get_taxes();
$get_subtotal	= $order->get_subtotal();
$billing_address	= $order->get_formatted_billing_address();

$date_format	= get_option('date_format');
$time_format	= get_option('time_format');

if ( function_exists('fw_get_db_settings_option') ) {
	$invoice_address 	= fw_get_db_settings_option('invoice_address');
	$invoice_text 		= fw_get_db_settings_option('invoice_text');
	$billing_address	= !empty($invoice_address) ? $invoice_address : $billing_address;
}


//Get sub totals
if( function_exists('wmc_revert_price')) {
	$get_total	= workreap_price_format(wmc_revert_price($get_total,$order->get_currency()),'return');
}else{
	$get_total	= workreap_price_format($get_total,'return');
}

$html			= '';
$project_title	= '';
$counter		= 0;
$payment_type_title	= esc_html__('Project title:', 'workreap');
if(!empty($order->get_items())){
	foreach ( $order->get_items() as $item_id => $item ) {
		$counter++;
		$total 				= $item->get_total();
		$tax 				= $item->get_subtotal_tax();
		$admin_shares		= $item->get_meta('admin_shares',true);
		$freelancer_shares	= $item->get_meta('freelancer_shares',true);
		$woo_product_data	= $item->get_meta('cus_woo_product_data',true);
		$payment_type		= $item->get_meta('payment_type',true);

		$project_title		= $item->get_name();
		$employer_id		= $item->get_meta('employer_id',true);
		$freelancer_id		= $item->get_meta('freelancer_id',true);
		$current_project	= $item->get_meta('current_project',true);
		$addons				= '';
		
		if(!empty($current_project)){
			$project_title	= get_the_title($current_project);
			if(!empty($woo_product_data['addons'])){
				foreach($woo_product_data['addons'] as $key => $service_item){
					$addons	.= '<p>'. get_the_title($key).'</p>';
				}
			}
			
			if(!empty($woo_product_data['milestone_id'])){
				$addons	.= '<p>'. get_the_title($woo_product_data['milestone_id']).'</p>';
			}
		} elseif(!empty($woo_product_data['project_id'])){
			$project_title	= get_the_title($woo_product_data['project_id']);
			
			if(!empty($woo_product_data['milestone_id'])){
				$addons	.= '<p>'. get_the_title($woo_product_data['milestone_id']).'</p>';
			}
		} elseif(!empty($woo_product_data['service_id'])){
			$project_title	= get_the_title($woo_product_data['service_id']);
			if(!empty($woo_product_data['addons'])){
				foreach($woo_product_data['addons'] as $key => $service_item){
					$addons	.= '<p>'. get_the_title($key).'</p>';
				}
			}
		}
		
		if(!empty($payment_type) && $payment_type === 'subscription'){
			$payment_type_title	= esc_html__('Package:', 'workreap');
		} else if(!empty($payment_type) && $payment_type === 'hiring_service'){
			$payment_type_title	= esc_html__('Service title:', 'workreap');
		} else if(!empty($payment_type) && $payment_type === 'milestone'){
			$payment_type_title	= esc_html__('Milestone title:', 'workreap');
		}  else if(!empty($payment_type) && $payment_type === 'hiring'){
			$payment_type_title	= esc_html__('Project title:', 'workreap');
		}

		//total with wmc
		if( function_exists('wmc_revert_price') ){
			$total	= workreap_price_format(wmc_revert_price($total,$order->get_currency()),'return');
		}else{
			$total	= workreap_price_format($total,'return');
		}

		$html	.= '<tr>
						<td data-label="'.esc_html__('#', 'workreap').'"><span>'.intval($counter).'</span></td>
						<td data-label="'. _x('Description', 'Description for invoice detail', 'workreap' ).'"><span>'.$project_title.'</span>'.$addons.'</td>
						<td data-label="'. esc_html__('Cost', 'workreap').'"><span>'. $total.'</span></td>
						<td data-label="'. esc_html__('Transaction fee', 'workreap').'"><span>'. workreap_price_format($admin_shares,'return').'</span></td>
						<td data-label="'. esc_html__('Amount', 'workreap').'"><span>'. $total.'</span></td>
					</tr>';

	}
}

$from_billing_address		= !empty($freelancer_id) ? workreap_user_billing_address($freelancer_id) : '';

if (function_exists('fw_get_db_settings_option')) {
	$main_logo = fw_get_db_settings_option('main_logo');
}

$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
if (!empty($main_logo['url'])) {
	$logo = $main_logo['url'];
} else {
	$logo = get_template_directory_uri() . '/images/logo.png';
}
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg- col-xl-8 float-right">
	<div class="wt-dashboardbox wt-dashboardinvocies">
		<div class="wt-printable">
			<div class="wt-invoicebill">
				<?php if (!empty($logo)) {?>
					<figure><img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($blogname); ?>"></figure>
				<?php }?>
				<div class="wt-billno">
					<h3><?php esc_html_e('Invoice', 'workreap'); ?></h3>
					<span>#<?php echo intval($order_id);?></span>
				</div>
			</div>
			<div class="wt-tasksinfos">
				<?php if(!empty($project_title)){?>
					<div class="wt-invoicetasks">
						<h6><?php echo esc_html( $payment_type_title ); ?></h6>
						<h3><?php echo do_shortcode($project_title);?></h3>
					</div>
				<?php } ?>
				<div class="wt-tasksdates">
					<span><em><?php esc_html_e('Issue date:', 'workreap'); ?></em>&nbsp;<?php echo esc_html($data_created);?></span>
				</div>
			</div>
			<div class="wt-invoicefromto">
			<?php if(!empty($billing_address)){?>
					<div class="wt-fromreceiver">
						<span><strong><?php esc_html_e('To:', 'workreap'); ?></strong></span>
						<div class="billing-area"><?php echo do_shortcode(nl2br($billing_address));?></div>
					</div>
				<?php }?>
				<?php if(!empty($from_billing_address)){?>
					<div class="wt-fromreceiver">
						<span><strong><?php esc_html_e('From:', 'workreap'); ?></strong></span>
						<div class="billing-area"><?php echo do_shortcode(nl2br( $from_billing_address));?></div>
					</div>
				<?php }?>
				
			</div>
			<table class="wt-table wt-invoice-table">
				<thead>
					<tr>
						<th><?php esc_html_e('Item', 'workreap'); ?>#</th>
						<th><?php  echo _x('Description', 'Description for invoice detail', 'workreap' );?></th>
						<th><?php esc_html_e('Cost', 'workreap'); ?></th>
						<th><?php esc_html_e('Transaction fee', 'workreap'); ?></th>
						<th><?php esc_html_e('Amount', 'workreap'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php echo do_shortcode( $html );?>
				</tbody>
			</table>
			<div class="wt-subtotal">
				<ul class="wt-subtotalbill">
					<?php if(!empty($total)){?><li><?php esc_html_e('Subtotal :', 'workreap'); ?><h6><?php echo esc_attr($total);?></h6></li><?php }?>
					
					<?php if( !empty($admin_shares) ){?>
						<li><?php esc_html_e('Transaction fee','workreap');?>&nbsp;<h6>-<?php workreap_price_format($admin_shares);?></h6> </li>
					<?php } ?>
				</ul>
				<?php if( !empty($freelancer_shares) ){?>
					<div class="wt-sumtotal"><?php esc_html_e('Total :', 'workreap'); ?>
						<h6><?php echo workreap_price_format($freelancer_shares);?></h6>
					</div>
				<?php }else{?>
					<div class="wt-sumtotal"><?php esc_html_e('Total :', 'workreap'); ?>
						<h6><?php echo esc_attr($get_total);?></h6>
					</div>
				<?php } ?>
			</div>
			<?php if(!empty($invoice_text)){?><div class="wt-disclaimer"><p><?php echo esc_html($invoice_text);?></p></div><?php }?>
		</div>
	</div>
</div>