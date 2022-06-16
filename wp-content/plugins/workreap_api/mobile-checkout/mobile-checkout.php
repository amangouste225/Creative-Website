<?php
/**
 * Template Name: Mobile Checkout Page
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap APP
 *
 */
 if(isset($_GET['order_id'])){ 
	global $wpdb,$woocommerce; 
	$order_id 		= $_GET['order_id'];
	$platform		= $_GET['platform'];
	$get_data   	= "SELECT * FROM `".MOBILE_APP_TEMP_CHECKOUT."`  WHERE `id`=".$order_id;
	$temp_date   	= $wpdb->get_results($get_data);
	$temp_date   	= $temp_date[0]->temp_data;
	$order_data 	= maybe_unserialize($temp_date);
	$order_data 	= json_decode($order_data);
	$checkout_url   = wc_get_checkout_url();
	$price_symbol	= workreap_get_current_currency();
	//separate arrays
	if ( $order_data ){
		foreach($order_data as $key => $value){
			$$key = $value;
		}	
	}
	
	$user_id 		= $customer_id; //wp_validate_auth_cookie($order_data['token'], 'logged_in');
	$user 			= get_userdata($user_id);
	
	$order_type		= !empty( $order_type ) ?  $order_type : 'service'; 
	$service_id		= !empty( $service_id ) ?  $service_id : ''; 
	$addons			= !empty( $addons ) ?  explode( ',',$addons ) : array();
	$milestone_id	= !empty( $milestone_id ) ?  $milestone_id : '';
	$job_id			= !empty( $job_id ) ?  $job_id : ''; 
	$proposal_id	= !empty( $proposal_id ) ?  $proposal_id : ''; 

    if ($user) {
        if (!is_user_logged_in()) {
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id);
            $url = $_SERVER['REQUEST_URI'];
			wp_redirect( $url );
        }
    } else{
		esc_html_e('You must be login to view checkout page.','workreap_api'); 
		return;
	}

	//Selected Payment Method
	if(isset($payment_method) && $payment_method != ""){
		$current_method   = $payment_method;
	}
	
	$bk_settings = '';
	if ( function_exists( 'fw_get_db_settings_option' ) ) {
		$bk_settings 		= fw_get_db_settings_option('hiring_payment_settings');
	}

	if( $order_type === 'service' ){
		$product_id	= workreap_get_hired_product_id();
		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {
				$woocommerce->session->set('refresh_totals', true);
				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $user_id;
				$price				= get_post_meta($service_id ,'_price',true);
				$single_service_price	= $price;
				$addon_data				= array();

				if( !empty( $addons ) ){
					foreach( $addons as $addon_id ){
						$addons_price		= get_post_meta($addon_id ,'_price',true);
						$addons_price		= !empty( $addons_price ) ? $addons_price : 0 ;
						$price				= $price + $addons_price;
						$addon_data[$addon_id]['id']	= $addon_id;
						$addon_data[$addon_id]['price']	= $addons_price;
					}
				}

				$delivery_time		= wp_get_post_terms($service_id, 'delivery');
				$delivery_time 		= !empty( $delivery_time[0]->term_id ) ? $delivery_time[0]->term_id : '';
				$admin_shares 		= 0.0;
				$freelancer_shares 	= 0.0;

				if( !empty( $price ) ){
					$service_fee		= workreap_commission_fee($price,'services',$service_id);

					if( !empty( $service_fee ) ){
						$admin_shares       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
						$freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
					} else{
						$admin_shares       = 0.0;
						$freelancer_shares  = $price;
					}

					$admin_shares 		= number_format($admin_shares,2,'.', '');
					$freelancer_shares 	= number_format($freelancer_shares,2,'.', '');
				}

				$cart_meta['service_id']		= $service_id;
				$cart_meta['delivery_time']		= $delivery_time;
				$cart_meta['price']				= $price;
				$cart_meta['service_price']		= $single_service_price;
				$cart_meta['addons']			= $addon_data;

				$hired_freelance_id			= get_post_field('post_author',$service_id);
				$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'price'				=> workreap_price_format($price,'return'),
					'payment_type'     	=> 'hiring_service',
					'admin_shares'     	=> $admin_shares,
					'freelancer_shares' => $freelancer_shares,
					'employer_id' 		=> $user_id,
					'freelancer_id' 	=> $hired_freelance_id,
					'current_project' 	=> $service_id,
				);

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

				if( !empty( $bk_settings['gadget'] ) && $bk_settings['gadget'] === 'enable' ) {
					//redirect here
				}else{
					$redirect_url	= workreap_create_woocommerce_order($service_id,'',true);
					wp_redirect( $redirect_url );
				}
			} else {
				esc_html_e('Please install WooCommerce plugin to process this order','workreap_api'); 
				return;
			}
		} else{
			esc_html_e('Hiring settings is missing, please contact to administrator.','workreap_api'); 
			return;
		}
	} else if( $order_type === 'package'){

		$product_id		= !empty( $product_id ) ?  $product_id : ''; 
		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {
				global $current_user, $woocommerce;
				$woocommerce->session->set('refresh_totals', true);
				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $user_id;

				$cart_meta			= array();
				$user_type			= workreap_get_user_type( $user_id );
				$pakeges_features	= workreap_get_pakages_features();

				if ( !empty ( $pakeges_features )) {
					foreach( $pakeges_features as $key => $vals ) {
						if( $vals['user_type'] === $user_type || $vals['user_type'] === 'common' ) {
							$item			= get_post_meta($product_id,$key,true);
							$text			=  !empty( $vals['text'] ) ? ' '.esc_html($vals['text']) : '';
							if( $key === 'wt_duration_type' ) {
								$feature 	= workreap_get_duration_types($item,'value');
							} else if( $key === 'wt_badget' ) {
								$feature 	= !empty( $item ) ? $item : 0;
							} else {
								$feature 	= $item;
							}

							$cart_meta[$key]	= $feature.$text;
						}
					}
				}

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'payment_type'     	=> 'subscription',
				);

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
			} else {
				esc_html_e('Please install WooCommerce plugin to process this order', 'workreap_api');
				return;
			}
		} else{
			esc_html_e('Some error occur, please try again later', 'workreap_api');
		}
	}elseif( $order_type === 'milestone'){
		$product_id	= workreap_get_hired_product_id();
		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {
				global $current_user, $woocommerce;
				
				$woocommerce->session->set('refresh_totals', true);
				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $user_id;
				$job_id				= get_post_meta($milestone_id ,'_project_id',true);
				$price				= get_post_meta($milestone_id ,'_price',true);
				$admin_shares 		= 0.0;
				$freelancer_shares 	= 0.0;

				if( !empty( $price ) ){
					$service_fee		= workreap_commission_fee($price,'milestone',$job_id);

					if( !empty( $service_fee ) ){
						$admin_shares       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
						$freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
					} else{
						$admin_shares       = 0.0;
						$freelancer_shares  = $price;
					}

					$admin_shares 		= number_format($admin_shares,2,'.', '');
					$freelancer_shares 	= number_format($freelancer_shares,2,'.', '');
				}

				$cart_meta['project_id']		= $job_id;
				$cart_meta['price']				= $price;
				$cart_meta['milestone_id']		= $milestone_id;

				//hired freelancers
				$proposal_id				= get_post_meta( $job_id, '_proposal_id', true);
				$hired_freelance_id			= get_post_field('post_author',$proposal_id);
				$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'price'				=> $price_symbol['symbol'].$price,
					'payment_type'     	=> 'milestone',
					'admin_shares'     	=> $admin_shares,
					'freelancer_shares' => $freelancer_shares,
					'employer_id' 		=> $user_id,
					'freelancer_id' 	=> $hired_freelance_id,
					'current_project' 	=> $job_id,
				);

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
				
				if( !empty( $bk_settings['gadget'] ) && $bk_settings['gadget'] === 'enable' ) {
					//redirect here
				}else{
					$redirect_url = workreap_create_woocommerce_order($milestone_id,$proposal_id,true);
					wp_redirect( $redirect_url );
				}
				
			} else {
				esc_html_e('Please install WooCommerce plugin to process this order', 'workreap_api');
				return;
			}
		} else{
			esc_html_e('Hiring settings is missing, please contact to administrator.', 'workreap_api');	
			return;
		}
	}elseif( $order_type === 'hiring'){
		$product_id	= workreap_get_hired_product_id();
		if( !empty( $product_id )) {

			if ( class_exists('WooCommerce') ) {
				global $current_user, $woocommerce;
				$woocommerce->session->set('refresh_totals', true);
				
				$woocommerce->cart->empty_cart();
				$user_id			= $user_id;
				$price				= get_post_meta($proposal_id ,'_amount',true);
				$price_symbol		= workreap_get_current_currency();
				$admin_shares 		= 0.0;
				$freelancer_shares 	= 0.0;

				if( !empty( $price ) ){
					$service_fee		= workreap_commission_fee($price,'projects',$job_id);
					
					if( !empty( $service_fee ) ){
						$admin_shares       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
						$freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
					} else{
						$admin_shares       = 0.0;
						$freelancer_shares  = $price;
					}

					$admin_shares 		= number_format($admin_shares,2,'.', '');
					$freelancer_shares 	= number_format($freelancer_shares,2,'.', '');
				}

				$cart_meta['project_id']		= $job_id;
				$cart_meta['price']				= $price;
				$cart_meta['proposal_id']		= $proposal_id;

				$hired_freelance_id			= get_post_field('post_author',$proposal_id);
				$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'price'				=> $price_symbol['symbol'].$price,
					'payment_type'     	=> 'hiring',
					'admin_shares'     	=> $admin_shares,
					'freelancer_shares' => $freelancer_shares,
					'employer_id' 		=> $user_id,
					'freelancer_id' 	=> $hired_freelance_id,
					'current_project' 	=> $job_id,
				);

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
				
				if( !empty( $bk_settings['gadget'] ) && $bk_settings['gadget'] === 'enable' ) {
					//Reedirect
				}else{
					$redirect_url = workreap_create_woocommerce_order($job_id,'',true);
					wp_redirect( $redirect_url );
				}
			} else {
				esc_html_e('Please install WooCommerce plugin to process this order', 'workreap_api');
				return;
			}
		} else{
			esc_html_e('Hiring settings is missing, please contact to administrator.', 'workreap_api');
			return;
		}
	}
 
	if( !empty( $current_method ) ){
		$woocommerce->session->set( 'chosen_payment_method', $current_method );
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<title><?php esc_html_e('Mobile Checkout Template','workreap_api');?></title>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<div style="display:none;">
	  <form name="checkout" id="mobile_checkout" method="post" class="woocommerce-checkout" action="<?php echo esc_url( $checkout_url )."?platform=".$platform; ?>" enctype="multipart/form-data">
		  <input type="text" class="mobile-checkout-field" name="billing_first_name" id="billing_first_name" value="<?php echo esc_attr( $billing_info->first_name ); ?>"/>
		  <input type="text" class="mobile-checkout-field" name="billing_last_name" id="billing_last_name" value="<?php echo esc_attr( $billing_info->last_name ); ?>"/>
		  <input type="text" class="mobile-checkout-field" name="billing_country" id="billing_country" value="<?php echo esc_attr( $billing_info->country ); ?>"/>
		  <input type="text" class="mobile-checkout-field" name="billing_company" id="billing_company" value="<?php echo esc_attr( $billing_info->company ); ?>" />
		  <input type="text" class="mobile-checkout-field" name="billing_address_1" id="billing_address_1" placeholder="<?php esc_html_e('House number and street name','workreap_api');?>" value="<?php  echo esc_attr( $billing_info->address_1 ); ?>" />
		  <input type="text" class="mobile-checkout-field" name="billing_address_2" id="billing_address_2" placeholder="<?php esc_html_e('Apartment, suite, unit etc. (optional)','workreap_api');?>" value="<?php  echo esc_attr( $billing_info->address_2 ); ?>" />
		  <input type="text" class="mobile-checkout-field" name="billing_city" id="billing_city" value="<?php  echo esc_attr( $billing_info->city ); ?>" />
		  <input type="text" class="mobile-checkout-field" value="<?php  echo esc_attr( $billing_info->state ); ?>" name="billing_state" id="billing_state" />
		  <input type="text" class="mobile-checkout-field" name="billing_postcode" id="billing_postcode" value="<?php  echo ( $billing_info->postcode ); ?>" />
		  <input type="tel" class="mobile-checkout-field" name="billing_phone" id="billing_phone" value="<?php  echo esc_attr( $billing_info->phone ); ?>" />
		  <input type="email" class="mobile-checkout-field" name="billing_email" id="billing_email" value="<?php  echo esc_attr( $billing_info->email ); ?>" />
		  <input id="ship-to-different-address-checkbox" class="woocommerce-form__input input-checkbox"  type="checkbox" name="ship_to_different_address" value="1" <?php if(isset($sameAddress) && $sameAddress !=""){?> checked="checked" <?php } ?>>
		  <input type="text" class="mobile-checkout-field" name="shipping_first_name" id="shipping_first_name" value="<?php  echo esc_attr( $shipping_info->first_name ); ?>" />  <input type="text" class="mobile-checkout-field" name="shipping_last_name" id="shipping_last_name" value="<?php  echo esc_attr( $shipping_info->last_name ); ?>" />  
		  <input type="text" class="mobile-checkout-field" name="shipping_company" id="shipping_company" value="<?php  echo esc_attr( $shipping_info->company ); ?>" />  
		  <input type="text" class="mobile-checkout-field" name="shipping_country" id="shipping_country" value="<?php  echo esc_attr( $shipping_info->country ); ?>"/>
		  <input type="text" class="mobile-checkout-field" name="shipping_address_1" id="shipping_address_1" placeholder="<?php esc_html_e('House number and street name','workreap_api');?>" value="<?php  echo esc_attr( $shipping_info->address_1 ); ?>" />  
		  <input type="text" class="mobile-checkout-field" name="shipping_address_2" id="shipping_address_2" placeholder="<?php esc_html_e('Apartment, suite, unit etc (optional)','workreap_api');?>" value="<?php  echo esc_attr( $shipping_info->address_2 ); ?>" /> 
		  <input type="text" class="mobile-checkout-field" name="shipping_city" id="shipping_city" value="<?php  echo esc_attr( $shipping_info->city ); ?>" />
		  <input type="text" class="mobile-checkout-field" value="<?php  echo esc_attr( $shipping_info->state ); ?>" name="shipping_state" id="shipping_state" />
		  <input type="text" class="mobile-checkout-field" name="shipping_postcode" id="shipping_postcode" value="<?php  echo esc_attr( $shipping_info->postcode ); ?>" />  <textarea name="order_comments" class="mobile-checkout-field" id="order_comments" placeholder="<?php esc_html_e('Write notes about your order','workreap_api');?>" rows="2" cols="5"><?php $customer_note; ?></textarea>

		  <input type="radio" checked="checked" class="shipping_method" name="shipping_method[]" id="shipping_method_0_<?php echo esc_attr( $shipping_methods ); ?><?php echo esc_attr( $shipid ); ?>" value="<?php echo  esc_attr( $shipping_methods ); ?>:<?php echo esc_attr( $shipid ); ?>" /><?php echo esc_attr( $shipping_methods ); ?>                  
	  </form>
	 </div>               
	<script type="text/javascript"> setTimeout(function(){document.getElementById("mobile_checkout").submit();}, 500);</script>
	</body>
</html>
<?php } ?>