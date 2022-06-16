<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Employers')) {

    class Workreap_Employers {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_employers_posts_columns', array(&$this, 'employers_columns_add'));
			add_action('manage_employers_posts_custom_column', array(&$this, 'employers_columns'),10, 2);
			add_action('add_meta_boxes', array(&$this, 'linked_profile_add_meta_box'), 10, 2);
			add_action('add_meta_boxes', array(&$this, 'messages_add_meta_box'), 10, 2);
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
                'name' 				=> esc_html__('Employers', 'workreap_core'),
                'all_items' 		=> esc_html__('Employers', 'workreap_core'),
                'singular_name' 	=> esc_html__('Employer', 'workreap_core'),
                'add_new' 			=> esc_html__('Add Employer', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New Employer', 'workreap_core'),
                'edit' 				=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit Employer', 'workreap_core'),
                'new_item' 			=> esc_html__('New Employer', 'workreap_core'),
                'view' 				=> esc_html__('View Employer', 'workreap_core'),
                'view_item' 		=> esc_html__('View Employer', 'workreap_core'),
                'search_items' 		=> esc_html__('Search Employer', 'workreap_core'),
                'not_found' 		=> esc_html__('No Employer found', 'workreap_core'),
                'not_found_in_trash' => esc_html__('No Employer found in trash', 'workreap_core'),
                'parent' 			=> esc_html__('Parent Employers', 'workreap_core'),
            );
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new company', 'workreap_core'),
                'public' 				=> true,
                'supports' 				=> array('title','editor','thumbnail'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> true,
                'exclude_from_search' 	=> false,
                'hierarchical' 			=> false,
                'menu_position' 		=> 14,
				'menu_icon'				=> 'dashicons-businessman',
                'rewrite' 				=> array('slug' => 'employer', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array(
											'create_posts' => false
										)
            );
			
            register_post_type('employers', $args);
			
			//Regirster department Taxonomy
            $dep_labels = array(
                'name' 				=> esc_html__('Department', 'workreap_core'),
                'singular_name' 	=> esc_html__('Department','workreap_core'),
                'search_items' 		=> esc_html__('Search Department', 'workreap_core'),
                'all_items' 		=> esc_html__('All Department', 'workreap_core'),
                'parent_item' 		=> esc_html__('Parent Department', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Department:', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit Department', 'workreap_core'),
                'update_item' 		=> esc_html__('Update Department', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New Department', 'workreap_core'),
                'new_item_name' 	=> esc_html__('New Department Name', 'workreap_core'),
                'menu_name' 		=> esc_html__('Department', 'workreap_core'),
            );
			
            $dep_args = array(
                'hierarchical' 		=> true,
                'labels' 			=> $dep_labels,
                'show_admin_column' => false,
				'show_ui' 			=> true,
				'show_in_quick_edit'=> false,
				'show_in_nav_menus' 	=> false,
				'meta_box_cb'       => false,
                'query_var' 		=> true,
                'rewrite' 			=> array('slug' => 'department'),
            );
            register_taxonomy('department', array('employers'), $dep_args);
			
        }
		
		/**
		 * @Linked Chat metabox
		 * @return {post}
		 */
		public function messages_add_meta_box($post_type,$post) {
			$linked_profile	= workreap_get_linked_profile_id($post->ID,'post');
			if(empty( $linked_profile )){return;}
			
			if ($post_type === 'employers' || $post_type === 'freelancers') {
                add_meta_box(
                        'workreap_chat', esc_html__('Chat History', 'workreap_core'), array(&$this, 'messages_meta_box_print'), $post_type, 'advanced', 'high'
                );
            }
		}
		
		/**
		 * @Linked chat metabox
		 * @return {post}
		 */
		public function messages_meta_box_print($post) {
			
			$user_identity	= workreap_get_linked_profile_id($post->ID,'post');
			if(empty( $user_identity )){return;}
			?>
			<section class="wt-haslayout am-chat-module">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-10">
					<div class="wt-dashboardbox wt-messages-holder">
						<div class="wt-dashboardboxtitle">
						   <h2><?php esc_html_e('Messages', 'workreap_core'); ?></h2>
						</div>
						<div class="wt-dashboardboxtitle wt-titlemessages chat-current-user"></div>
						<div class="wt-dashboardboxcontent wt-dashboardholder wt-offersmessages">
							<?php do_action('fetch_users_threads', $user_identity); ?>
						</div>
					</div>
				</div>
			</section>
			<script type="text/template" id="tmpl-load-chat-replybox">
				<div class="wt-messages wt-verticalscrollbar wt-dashboardscrollbar chat-history-wrap"></div>
			</script>
			<script type="text/template" id="tmpl-load-chat-messagebox">
				<# if( !_.isEmpty(data.chat_nodes) ) { #>
				<# 
				_.each( data.chat_nodes , function( element, index ) { 
					var chat_class = 'wt-offerermessage wt-msg-thread';
					if(element.chat_is_sender === 'yes'){
						chat_class = 'wt-memessage wt-readmessage wt-msg-thread';
					}

					load_message	= element.chat_message;
				#>
				<div class="{{chat_class}}" data-id="{{element.chat_id}}">
					<figure><img src="{{element.chat_avatar}}" alt="{{element.chat_username}}"></figure>
					<div class="wt-description">
						<p>{{load_message}}</p>
						<div class="clearfix"></div>
						<time datetime="2017-08-08">{{element.chat_date}}</time>
						<div class="clearfix"></div>
					</div>
				</div>
				<# }); #>
				<# } #>
				</script>
				<script type="text/template" id="tmpl-load-chat-recentmsg-data">
					{{data.desc}}
				</script>
				<script type="text/template" id="tmpl-load-user-details">
				<a href="#" onclick="event_preventDefault(event);" class="wt-back back-chat"><i class="ti-arrow-left"></i></a>
				<div class="wt-userlogedin">
					<figure class="wt-userimg">
						<img src="{{data.chat_img}}" alt="{{data.chat_name}}">
					</figure>
					<div class="wt-username">
						<h3>{{data.chat_name}}</h3>
						<a target="_blank" href="{{data.chat_url}}" class="wt-viewprofile"><?php esc_html_e('View Profile', 'workreap_core'); ?></a>
					</div>
				</div>
				<a href="{{data.chat_url}}" class="wt-viewprofile wt-viewprofile-icon"><i class="lnr lnr-unlink"></i></a>
				</script>
			<?php
		}
		
		/**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function linked_profile_add_meta_box($post_type, $post) {
			$linked_profile	= workreap_get_linked_profile_id($post->ID,'post');
			if(empty( $linked_profile )){return;}
			
			if ($post_type === 'employers') {
                add_meta_box(
					'linked_profile', esc_html__('Linked Profile', 'workreap_core'), array(&$this, 'linked_profile_meta_box_print'), 'employers', 'side', 'high'
				);
				add_meta_box(
					'balance_history', esc_html__('Balance History', 'workreap_core'), array(&$this, 'balance_history_meta_box_print'), 'employers', 'side', 'high'
				);
				add_meta_box(
					'payouts', esc_html__('Payouts Settings', 'workreap_core'), array(&$this, 'payouts_meta_box_print'), 'employers', 'side', 'high'
				);
            }
		}
		
		/**
		 * @Payout Settings
		 * @return {post}
		 */
		public function payouts_meta_box_print($post) {
			$linked_profile		= workreap_get_linked_profile_id($post->ID,'post');
			$payrols			= workreap_get_payouts_lists();
			
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
		 * @Balance history metabox
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
			<ul class="review-info">
                <li>
                   <span class="push-right">
                    	<?php esc_html_e('Pending balance', 'workreap_core'); ?>&nbsp:
                    </span>
                    <span class="push-left">
                    	<?php echo workreap_price_format($current_balance); ?>
                    </span>
                    
                </li>
                <li>
                   <span class="push-right">
                    	<?php esc_html_e('Available balance', 'workreap_core'); ?>&nbsp:
                    </span>
                    <span class="push-left">
                    	<?php echo workreap_price_format($available_balance); ?>
                    </span>
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
                <?php if( ( empty( $is_verified ) || $is_verified === 'no' ) && ( isset( $verify_user ) && $verify_user === 'none' )  ){?>
					<li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_verify_user" data-type="approve" data-id="<?php echo esc_attr( $linked_profile );?>"  data-user_id="<?php echo intval( $linked_profile );?>"><?php esc_html_e('Approve Account', 'workreap_core'); ?></a>
						</span>
					</li>
                <?php }else{?>
                	<li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_verify_user" data-type="reject"  data-id="<?php echo esc_attr( $post->ID );?>" data-user_id="<?php echo intval( $linked_profile );?>"><?php esc_html_e('Disable Account', 'workreap_core'); ?></a>
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
		public function employers_columns_add($columns) {
			unset($columns['author']);
			unset($columns['date']);
			$columns['job_posted'] 		= esc_html__('Jobs Posted','workreap_core');
			$columns['followers'] 		= esc_html__('Followers','workreap_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function employers_columns($case) {
			global $post;
			
			$user_id			= workreap_get_linked_profile_id(get_the_ID(),'post');
			$job_posted			= workreap_count_posts_by_meta('projects',$user_id,'','',array('publish','pending'));
			$total_freelancers	= get_post_meta( get_the_ID(), '_saved_freelancers', true);
			$total_compnies		= get_post_meta( get_the_ID(), '_following_employers', true);
			$total_freelancers 	= !empty( $total_freelancers ) ? count( $total_freelancers ) : 0;
			$total_compnies 	= !empty( $total_compnies ) ? count( $total_compnies ) : 0;
			$followers			= intval($total_compnies) + intval($total_freelancers);
			
			switch ($case) {
				case 'job_posted':
					echo intval( $job_posted );
				break;
				
				case 'followers':
					echo intval( $followers );
				break;
				
			}
		}

    }

    new Workreap_Employers();
}

