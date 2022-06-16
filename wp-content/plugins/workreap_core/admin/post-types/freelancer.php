<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Freelancer')) {

    class Workreap_Freelancer {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_freelancers_posts_columns', array(&$this, 'freelancers_columns_add'));
			add_action('manage_freelancers_posts_custom_column', array(&$this, 'freelancers_columns'),10, 2);
			add_action('add_meta_boxes', array(&$this, 'linked_profile_add_meta_box'), 10, 2);
			add_action('init', array(&$this, 'init_sidebars_taxonomy'));
        }

        /**
         * @Init Post Type
         * @return {post}
         */
        public function init_post_type() {
            $this->prepare_post_type();
        }

        /**
         * @Prepare Post Type Category
         * @return post type
         */
        public function prepare_post_type() {
            $labels = array(
                'name' 					=> esc_html__('Freelancers', 'workreap_core'),
                'all_items' 			=> esc_html__('Freelancers', 'workreap_core'),
                'singular_name' 		=> esc_html__('Freelancer', 'workreap_core'),
                'add_new' 				=> esc_html__('Add Freelancer', 'workreap_core'),
                'add_new_item' 			=> esc_html__('Add New Freelancer', 'workreap_core'),
                'edit' 					=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 			=> esc_html__('Edit Freelancer', 'workreap_core'),
                'new_item' 				=> esc_html__('New Freelancer', 'workreap_core'),
                'view' 					=> esc_html__('View Freelancer', 'workreap_core'),
                'view_item' 			=> esc_html__('View Freelancer', 'workreap_core'),
                'search_items' 			=> esc_html__('Search Freelancer', 'workreap_core'),
                'not_found' 			=> esc_html__('No Freelancer found', 'workreap_core'),
                'not_found_in_trash' 	=> esc_html__('No Freelancer found in trash', 'workreap_core'),
                'parent' 				=> esc_html__('Parent Freelancers', 'workreap_core'),
            );
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new freelancer', 'workreap_core'),
                'public' 				=> true,
                'supports' 				=> array('title','editor','author','excerpt','thumbnail'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> true,
                'exclude_from_search' 	=> false,
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'freelancer', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'menu_icon'				=> WorkreapGlobalSettings::get_plugin_url().'/images/featured.png',
				'capabilities' 			=> array(
											'create_posts' => false
										)	
            );
			
            register_post_type('freelancers', $args);
			
			//for Badges
			$badge_labels = array(
                'name' 				=> esc_html__('Packages badges', 'workreap_core'),
                'singular_name' 	=> esc_html__('Packages badges','workreap_core'),
                'search_items' 		=> esc_html__('Search packages badge', 'workreap_core'),
                'all_items' 		=> esc_html__('All packages badge', 'workreap_core'),
                'parent_item' 		=> esc_html__('Parent packages badge', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent packages badge:', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit packages badge', 'workreap_core'),
                'update_item' 		=> esc_html__('Update packages badge', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New packages badge', 'workreap_core'),
                'new_item_name' 	=> esc_html__('New packages badge Name', 'workreap_core'),
                'menu_name' 		=> esc_html__('Packages badge', 'workreap_core'),
            );
			
			$badge_args = array(
                'hierarchical'		=> true,
                'labels' 			=> $badge_labels,
                'show_ui' 			=> true,
                'show_admin_column' => false,
				'show_in_nav_menus' => false,
				'publicly_queryable'=> false,
                'query_var' 		=> true,
                'rewrite' 			=> array('slug' => 'badge_cat'),
            );
			
			register_taxonomy('badge_cat', array('freelancers'), $badge_args);

			$specialization	= '';
			if( function_exists('fw_get_db_settings_option')  ){
				$specialization	= fw_get_db_settings_option('freelancer_specialization', $default_value = null);
			}

			$experience	= '';
			if( function_exists('fw_get_db_settings_option')  ){
				$experience	= fw_get_db_settings_option('freelancer_industrial_experience', $default_value = null);
			}
			
			if(!empty($specialization) && $specialization === 'enable' ){
				$specialization_labels = array(
					'name' 				=> esc_html__('Specialization', 'workreap_core'),
					'singular_name' 	=> esc_html__('Specialization','workreap_core'),
					'search_items' 		=> esc_html__('Search Specialization', 'workreap_core'),
					'all_items' 		=> esc_html__('All Specialization', 'workreap_core'),
					'parent_item' 		=> esc_html__('Parent Specialization', 'workreap_core'),
					'parent_item_colon' => esc_html__('Parent Specialization:', 'workreap_core'),
					'edit_item' 		=> esc_html__('Edit Specialization', 'workreap_core'),
					'update_item' 		=> esc_html__('Update Specialization', 'workreap_core'),
					'add_new_item' 		=> esc_html__('Add New Specialization', 'workreap_core'),
					'new_item_name' 	=> esc_html__('New Specialization Name', 'workreap_core'),
					'menu_name' 		=> esc_html__('Specialization', 'workreap_core'),
				);
				
				$specialization_args = array(
					'hierarchical'		=> true,
					'labels' 			=> $specialization_labels,
					'show_ui' 			=> true,
					'show_admin_column' => false,
					'show_in_nav_menus' => false,
					'publicly_queryable'=> false,
					'query_var' 		=> true,
					'rewrite' 			=> array('slug' => 'wt-specialization'),
				);
				
				register_taxonomy('wt-specialization', array('freelancers'), $specialization_args);
			}

			if(!empty($experience) && $experience === 'enable' ){
				//for Badges
				$experience_labels = array(
					'name' 				=> esc_html__('Industrial experience', 'workreap_core'),
					'singular_name' 	=> esc_html__('Industrial experience','workreap_core'),
					'search_items' 		=> esc_html__('Search Industrial experience', 'workreap_core'),
					'all_items' 		=> esc_html__('All Industrial experience', 'workreap_core'),
					'parent_item' 		=> esc_html__('Parent Industrial experience', 'workreap_core'),
					'parent_item_colon' => esc_html__('Parent Industrial experience:', 'workreap_core'),
					'edit_item' 		=> esc_html__('Edit Industrial experience', 'workreap_core'),
					'update_item' 		=> esc_html__('Update Industrial experience', 'workreap_core'),
					'add_new_item' 		=> esc_html__('Add New Industrial experience', 'workreap_core'),
					'new_item_name' 	=> esc_html__('New Industrial experience Name', 'workreap_core'),
					'menu_name' 		=> esc_html__('Industrial experience', 'workreap_core'),
				);
				
				$experience_args = array(
					'hierarchical'		=> true,
					'labels' 			=> $experience_labels,
					'show_ui' 			=> true,
					'show_admin_column' => false,
					'show_in_nav_menus' => false,
					'publicly_queryable'=> false,
					'query_var' 		=> true,
					'rewrite' 			=> array('slug' => 'wt-industrial-experience'),
				);
				
				register_taxonomy('wt-industrial-experience', array('freelancers'), $experience_args);

			}
			
			//Register hourly filter Taxonomy
			$rate_labels = array(
				'name' 				=> esc_html__('Hourly rate filter', 'workreap_core'),
				'singular_name' 	=> esc_html__('Hourly rate filter','workreap_core'),
				'search_items' 		=> esc_html__('Search Hourly rate filter', 'workreap_core'),
				'all_items' 		=> esc_html__('All Hourly rate filter', 'workreap_core'),
				'parent_item' 		=> esc_html__('Parent Hourly rate filter', 'workreap_core'),
				'parent_item_colon' => esc_html__('Parent Hourly rate filter', 'workreap_core'),
				'edit_item' 		=> esc_html__('Edit Hourly rate filter', 'workreap_core'),
				'update_item' 		=> esc_html__('Update Hourly rate filter', 'workreap_core'),
				'add_new_item' 		=> esc_html__('Add New Hourly rate filter', 'workreap_core'),
				'new_item_name' 	=> esc_html__('New Hourly rate filter', 'workreap_core'),
				'menu_name' 		=> esc_html__('Hourly rate filter', 'workreap_core'),
			);

			$rate_args = array(
				'hierarchical' 			=> true,
				'labels' 				=> $rate_labels,
				'show_in_quick_edit' 	=> true,
				'show_admin_column' 	=> false,
				'show_in_nav_menus' 	=> false,
				'query_var' 			=> true,
				'show_ui'               => true,
				'rewrite' 				=> array('slug' => 'hourly_rate'),
			);
			register_taxonomy('hourly_rate', array('freelancers'), $rate_args);
        }
		
		/* @Init sidebars 
         * @return {post}
         */
        public function init_sidebars_taxonomy() {
			$sidebars_labels = array(
				'name' 				=> esc_html__('Sidebars', 'workreap_core'),
				'singular_name' 	=> esc_html__('Sidebar','workreap_core'),
				'search_items' 		=> esc_html__('Search sidebar', 'workreap_core'),
				'all_items' 		=> esc_html__('All sidebars', 'workreap_core'),
				'parent_item' 		=> esc_html__('Parent sidebar', 'workreap_core'),
				'parent_item_colon' => esc_html__('Parent sidebar:', 'workreap_core'),
				'edit_item' 		=> esc_html__('Edit sidebar', 'workreap_core'),
				'update_item' 		=> esc_html__('Update sidebar', 'workreap_core'),
				'add_new_item' 		=> esc_html__('Add new sidebar', 'workreap_core'),
				'new_item_name' 	=> esc_html__('New sidebar name', 'workreap_core'),
				'menu_name' 		=> esc_html__('Sidebars', 'workreap_core'),
			);

			$sidebars_args = array(
				'hierarchical' 			=> false,
				'labels' 				=> $sidebars_labels,
				'public' 				=> false,
				'show_in_nav_menus' 	=> false,
				'show_ui' 				=> true,
				'query_var' 			=> false,
				'rewrite' 				=> false,
			);
			
			register_taxonomy('wt_sidebars', 'freelancers', $sidebars_args);
			
			$sidebars = get_terms( array(
				'taxonomy' 		=> 'wt_sidebars',
				'hide_empty' 	=> false,
			) );

			foreach ( $sidebars as $sidebar ) {
				register_sidebar(
					array(
						'id'            => 'wt-' . sanitize_title( $sidebar->name ),
						'name'          => $sidebar->name,
						'description'   => $sidebar->description,
						'before_widget' => '<div id="%1$s" class="wt-widget %2$s">',
						'after_widget' 	=> '</div>',
						'before_title' 	=> '<div class="wt-widgettitle"><h2>',
						'after_title' 	=> '</h2></div>',
					)
				);

			}
        }
		
		/**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function linked_profile_add_meta_box($post_type,$post) {
			$linked_profile	= workreap_get_linked_profile_id($post->ID,'post');
			if(empty( $linked_profile )){return;}
			
			if ($post_type === 'freelancers') {
                add_meta_box(
                        'linked_profile', esc_html__('Linked Profile', 'workreap_core'), array(&$this, 'linked_profile_meta_box_print'), 'freelancers', 'side', 'high'
                );
				add_meta_box(
                        'balance_history', esc_html__('Balance History', 'workreap_core'), array(&$this, 'balance_history_meta_box_print'), 'freelancers', 'side', 'high'
                );
				add_meta_box(
                        'payouts', esc_html__('Payouts Settings', 'workreap_core'), array(&$this, 'payouts_meta_box_print'), 'freelancers', 'side', 'high'
                );
            }
		}
		
		/**
		 * @Payout Settings
		 * @return {post}
		 */
		public function payouts_meta_box_print($post) {
			$linked_profile		= workreap_get_linked_profile_id($post->ID,'post');
			$payrols	= workreap_get_payouts_lists();
			
			if(empty( $linked_profile )){return;}
			?>
			<div class="wt-tabsinfo wt-email-settings">
				<div class="wt-tabscontenttitle"><?php esc_html_e('Payouts Settings', 'workreap_core'); ?></div>
				<div class="wt-settingscontent">
					<div class="wt-description">
						<p><?php esc_html_e('All the earning will be sent to below selected payout method','workreap_core');?></p>
					</div>
					<div class="wt-formtheme wt-userform payout-holder">
						<div class="wt-payout-settings">
							<?php 
								if( !empty($payrols) ) {
									foreach ($payrols as $pay_key	=> $payrol) {
										if( !empty($payrol['status']) && $payrol['status'] === 'enable' ) {
											$contents	= get_user_meta($linked_profile,'payrols',true);
											$db_option	= !empty( $contents['type'] ) ? $contents['type'] : '';
											$db_option_display	= !empty( $contents['type'] ) && $pay_key === $contents['type'] ? 'display:block' : 'display:none';
											$db_option_display	= !empty($contents['payrol']) && $contents['payrol'] === 'paypal' ? 'display:block' : $db_option_display; //only for migration
										?>
										<fieldset>
											<div class="wt-checkboxholder"> 
												<span class="wt-radio">
													<input id="payrols-<?php echo esc_attr( $payrol['id'] ); ?>" <?php checked( $pay_key, $db_option); ?> type="radio" name="payout_settings[type]" value="<?php echo esc_attr( $payrol['id'] ); ?>">
													<label for="payrols-<?php echo esc_attr( $payrol['id'] ); ?>">
														<figure class="wt-userlistingimg">
															<img width="100" src="<?php echo esc_url( $payrol['img_url'] ); ?>" alt="<?php echo esc_attr( $payrol['title'] ); ?>">
														</figure>
													</label>
												</span>
											</div>
											<div class="fields-wrapper wt-haslayout" style="<?php echo esc_attr( $db_option_display );?>">
												<?php if( !empty($payrol['desc'])) {?>
													<div class="wt-description"><p><?php echo do_shortcode($payrol['desc']);?></p></div>
												<?php }?>
												<?php 
												if( !empty($payrol['fields'])) {
													foreach( $payrol['fields'] as $key => $field ){
														$db_value		= !empty($contents[$key]) ? $contents[$key] : "";
														//only for migration
														if( !empty($contents['email']) 
														   && !empty($contents['payrol']) 
														   && $contents['payrol'] === 'paypal' 
														   && $pay_key === 'paypal'
														){
															$db_value		= $contents['email'];
														}
													?>
													<div class="form-group form-group-half toolip-wrapo">
														<input type="<?php echo esc_attr($field['type']);?>" name="payout_settings[<?php echo esc_attr($key);?>]" id="<?php echo esc_attr($key);?>-payrols" class="form-control" placeholder="<?php echo esc_attr($field['placeholder']);?>" value="<?php echo esc_attr( $db_value ); ?>">
													</div>
												<?php }}?>
											</div>
										</fieldset>
										<?php

										}
									}
								}
							?>
						</div>	
					</div>
				</div>
			</div>
			<?php
		}
		/**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function balance_history_meta_box_print($post) {
			$linked_profile		= workreap_get_linked_profile_id($post->ID,'post');

			$current_balance	= workreap_get_sum_earning_freelancer($linked_profile,'hired','freelancer_amount');
			$current_balance	= !empty($current_balance) ? $current_balance : 0 ;

			$total_pending		= workreap_sum_freelancer_withdraw(array('publish','pending'),intval($linked_profile));
			$total_pending		= !empty($total_pending) ? floatval($total_pending) : 0;

			$totalamount    	= workreap_sum_user_earning('completed', 'freelancer_amount', intval($linked_profile));

			$available_balance	= 0;
			if(!empty($totalamount->total_amount)){
				$balance_remaining	= floatval($totalamount->total_amount ) - floatval( $total_pending );
				$available_balance    = !empty( $balance_remaining ) && $balance_remaining > 0  ? floatval( $totalamount->total_amount ) - floatval( $total_pending ) : 0;
			}
			
			if(empty( $linked_profile )){return;}
			?>
			<ul class="review-info freelancer-earning-insight">
                <li class="freelancer-earning-current">
                   <span class="push-right"><?php esc_html_e('Pending balance', 'workreap_core'); ?>&nbsp:</span>
                   <span class="push-left"><?php echo workreap_price_format($current_balance); ?></span>
                </li>
                <li class="freelancer-earning-available">
                   <span class="push-right"><?php esc_html_e('Available balance', 'workreap_core'); ?>&nbsp:</span>
                   <span class="push-left"><?php echo workreap_price_format($available_balance); ?></span>
                </li>
			</ul>
			<?php
		}
		
		/**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function linked_profile_meta_box_print($post) {
			$linked_profile	= workreap_get_linked_profile_id($post->ID,'post');
			$is_verified	= get_post_meta($post->ID,'_is_verified', true);
			
			if (function_exists('fw_get_db_settings_option')) {        
				$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
			}
			
			if(empty( $linked_profile )){return;}
			
			?>
			<ul class="review-info">
                <li>
                    <span class="push-right">
                    	<a target="_blank" href="<?php echo get_edit_user_link( $linked_profile );?>"><?php esc_html_e('View User Profile', 'workreap_core'); ?></a>
                    </span>
                </li>
                <?php if( ( empty( $is_verified ) || $is_verified == 'no' ) ){?>
					<li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_verify_user" data-type="approve" data-id="<?php echo esc_attr( $post->ID );?>" data-user_id="<?php echo intval( $linked_profile );?>"><?php esc_html_e('Approve account', 'workreap_core'); ?></a>
						</span>
					</li>
                <?php }else{?>
                	<li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_verify_user" data-type="reject"  data-id="<?php echo esc_attr( $post->ID );?>"  data-user_id="<?php echo intval( $linked_profile );?>"><?php esc_html_e('Disable account', 'workreap_core'); ?></a>
						</span>
					</li>
                <?php }?>
			</ul>
			<?php
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function freelancers_columns_add($columns) {
			unset($columns['author']);
			unset($columns['date']);
			$columns['per_hour'] 		= esc_html__('Charge Per hour','workreap_core');
			$columns['onegoing_prj'] 	= esc_html__('Ongoing Projects/Services','workreap_core');
			$columns['completed_prj'] 	= esc_html__('Completed Projects/Services','workreap_core');
			$columns['cancelled_prj'] 	= esc_html__('Cancelled Projects/Services','workreap_core');
			$columns['rating'] 			= esc_html__('Rating','workreap_core');
			$columns['earnings'] 		= esc_html__('Total Earning','workreap_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function freelancers_columns($case) {
			global $post;
			
			$linked_profile				= get_the_ID();
			$user_id					= workreap_get_linked_profile_id($linked_profile,'post');
			$per_hour 					= get_post_meta($linked_profile, '_perhour_rate', true);
			$rating 					= get_post_meta($linked_profile, 'rating_filter', true);
			$completed_jobs				= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $linked_profile, 'completed');
			$ongoing_jobs				= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $linked_profile, 'hired');
			$cancelled_jobs				= workreap_count_posts_by_meta( 'proposals' ,$user_id, '', '', 'cancelled');
			$earnings					= workreap_get_sum_payments_freelancer($user_id,'paid','amount');
			$completed_services			= workreap_count_posts_by_meta( 'services-orders' ,'', '_service_author', $user_id, 'completed');
			$ongoing_services			= workreap_count_posts_by_meta( 'services-orders' ,'', '_service_author', $user_id, 'hired');
			$cancelled_services			= workreap_count_posts_by_meta( 'services-orders' ,'', '_service_author', $user_id, 'cancelled');
			
			$per_hour					= !empty($per_hour) ? $per_hour : '';
			$rating						= !empty($rating) ? $rating : 0;
			$total_completed_jobs		= !empty($completed_jobs) ? $completed_jobs : 0;
			$total_ongoing_jobs			= !empty($ongoing_jobs) ? $ongoing_jobs : 0;
			$total_cancelled_jobs		= !empty($cancelled_jobs) ? $cancelled_jobs : 0;
			$earnings					= !empty($earnings) ? $earnings : 0;
			$total_completed_services	= !empty($completed_services) && intval($completed_services) > 0 ? sprintf('%02d', intval($completed_services)) : 0;
			$total_ongoing_services		= !empty($ongoing_services) && intval($ongoing_services) > 0? sprintf('%02d', intval($ongoing_services)) : 0;
			$total_cancelled_services	= !empty($cancelled_services) && intval($cancelled_services) > 0? sprintf('%02d', intval($cancelled_services)) : 0;
			
			$t_ongoing		= $total_ongoing_jobs + $total_ongoing_services;
			$t_completed	= $total_completed_jobs + $total_completed_services;
			$t_cancelled	= $total_cancelled_jobs + $total_cancelled_services;
			
			switch ($case) {
				case 'per_hour':
					echo intval( $per_hour );
				break;
				
				case 'onegoing_prj':
					echo intval( $t_ongoing );
				break;
				
				case 'completed_prj':
					echo intval( $t_completed );
				break;
					
				case 'cancelled_prj':
					echo intval( $t_cancelled );
				break;
				
				case 'rating':
					echo number_format( $rating,2 );
				break;
					
				case 'earnings':
					echo workreap_price_format( $earnings );
				break;
				
			}
		}

    }

    new Workreap_Freelancer();
}


