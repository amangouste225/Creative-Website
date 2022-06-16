<?php

/**
 * @Woocommerce Customization
 * return {}
 */
if (!class_exists('workreap_woocommerace')) {

    class workreap_woocommerace {

        function __construct() {
            add_action('woocommerce_product_options_general_product_data', array(&$this, 'workreap_package_meta'));
            add_action('woocommerce_process_product_meta', array(&$this, 'workreap_save_package_meta'));
			add_action( 'workreap_woocommerce_add_to_cart_button', array(&$this,'workreap_woocommerce_add_to_cart_button'), 10 );
			add_action( 'woocommerce_checkout_fields', array( &$this, 'workreap_custom_checkout_update_customer' ), 10);
			//add_action( 'woocommerce_product_query', array( &$this, 'workreap_pre_get_product_query') );  
			add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false');
			//add_filter( 'loop_shop_per_page', 'workreap_loop_shop_per_page', 20 );
			add_filter( 'woocommerce_return_to_shop_redirect', array( &$this, 'workreap_return_to_shop_redirect'));
			add_filter( 'gettext', array( &$this,'workreap_change_woocommerce_return_to_shop_text'), 20, 3 );
			
        }
		
		/**
		 * @Return to shop text
		 * @return {}
		 */
		public function workreap_change_woocommerce_return_to_shop_text( $translated_text, $text, $domain ) {
			switch ( $translated_text ) {
				case 'Return to shop' :
					$translated_text = esc_html__( 'Return to home', 'workreap' );
					break;
			}
			
			return $translated_text;
		}
		
		/**
		 * @Shop number of posts
		 * @return {}
		 */
		public function workreap_return_to_shop_redirect() {
			return home_url('/');
		}
		
		/**
		 * @Shop number of posts
		 * @return {}
		 */
		public function workreap_loop_shop_per_page($cols) {
			$cols = 12;
			return $cols; // 3 products per row
		}

		/**
		 * @remove packages from shop
		 * @return {}
		 */
		function workreap_pre_get_product_query( $q ) {
			$meta_query = $q->get( 'meta_query' );
			
			$meta_query['relation'] = 'AND';
			$meta_query[] = array(
				   'key' 			=> 'package_type',
				   'value' 			=> array('employer','freelancer','trail_employer','trail_freelancer'),
				   'compare' 		=> 'NOT IN'
			);
			
			$meta_query[] = array(
				'key' 			=> '_workreap_hiring',
				'compare' 		=> 'NOT EXISTS',
			);
			
			$q->set( 'meta_query', $meta_query );
		}
		
		/**
		 * @Checkout First and last name 
		 * @return {}
		 */
		public function workreap_custom_checkout_update_customer( $fields ){
			$user = wp_get_current_user();
			$first_name = $user ? $user->user_firstname : '';
			$last_name = $user ? $user->user_lastname : '';
			$fields['billing']['billing_first_name']['default'] = $first_name;
			$fields['billing']['billing_last_name']['default']  = $last_name;
			return $fields;
		}

		/**
		 * @Add to cart button
		 * @return {}
		 */
		public function workreap_woocommerce_add_to_cart_button(){
			global $product;
			echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				sprintf( '<a href="%s" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s ajax_add_to_cart  wt-btnaddtocart"><i class="lnr lnr-cart"></i><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></a>',
					esc_url( $product->add_to_cart_url() ),
					esc_html( $product->get_id() ),
					esc_html( $product->get_sku() ),
					esc_html( isset( $quantity ) ? $quantity : 1 ),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					esc_html( $product->get_type() ),
					esc_html( $product->add_to_cart_text() )
				),
			$product );
		}

        /**
         * @Package Meta save
         * return {}
         */
        public function workreap_save_package_meta($post_id) {
			if(current_user_can('administrator')) {
				update_post_meta($post_id, 'package_type', sanitize_text_field($_POST['package_type']));
				$pakeges_features = workreap_get_pakages_features();
				if ( !empty ( $pakeges_features )) {
					foreach( $pakeges_features as $key => $vals ) {
						update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
					}
				}
			}
        }
		
        /**
         * @Package Meta
         * return {}
         */
        public function workreap_package_meta($args) {
            global $woocommerce, $post;
			if(current_user_can('administrator')) {
				$system_access = 'paid';
				if (function_exists('fw_get_db_settings_option')) {
					$system_access = fw_get_db_settings_option('system_access', $default_value = null);
				}

				if( !empty( $system_access ) && $system_access !== 'both' ){
					woocommerce_wp_select(
							array(
								'id' 			=> 'package_type',
								'class' 		=> 'wt_package_type',
								'label' 		=> esc_html__('Package Type?', 'workreap'),
								'desc_tip' 		=> 'false',
								'disabled'		=> True,
								'description' 	=> esc_html__('You can select type of the user. If you will select "For Freelancer" then this package will be show in the freelancers dashboard. Same for employers. You can also create trial packages. Only once trial package can be created for each type.', 'workreap'),
								'options' 		=> workreap_packages_types( $post )
							)
					);

					$pakeges_features 	= workreap_get_pakages_features();
					foreach( $pakeges_features as $key => $vals ) {
						if ( $vals['type'] === 'number') {
							woocommerce_wp_text_input(
								array(
										'id' 			=> $key,
										'class' 		=> $vals['classes'],
										'label' 		=> $vals['title'],
										'desc_tip' 		=> 'true',
										'type' 			=> $vals['type'],
										'custom_attributes' => workreap_get_numaric_values( 1,0 )
									)
							);
						} elseif ( $vals['type'] === 'select') {
							woocommerce_wp_select(
										array(
											'id' 			=> $key,
											'class' 		=> $vals['classes'],
											'label' 		=> $vals['title'],
											'type' 			=> $vals['type'],
											'options' 		=> $vals['options']
										)
								);
						} elseif ( $vals['type'] === 'input') {
							woocommerce_wp_text_input(
								array(
										'id' 			=> $key,
										'class' 		=> $vals['classes'],
										'label' 		=> $vals['title'],
										'desc_tip' 		=> 'true',
										'type' 			=> $vals['type']
									)
							);
						}
					}
				}
			}
        }
    }

    new workreap_woocommerace();
}