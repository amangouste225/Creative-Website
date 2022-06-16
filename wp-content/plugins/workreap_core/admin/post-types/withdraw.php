<?php

/**
 * @package   Workreap Core
 * @version 1.0
 * @since 1.0
 */

if (!class_exists('Workreap_Withdraw')) {

    class Workreap_Withdraw {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
			add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_withdraw_posts_columns', array(&$this, 'withdraw_columns_add'));
			add_action('manage_withdraw_posts_custom_column', array(&$this, 'withdraw_columns'),10, 2);
			add_action('admin_notices', array(&$this, 'workreap_download_withdraw'));
			add_filter('post_row_actions',array(&$this, 'workreap_withdraw_action_row'), 10, 2);
 
		}
		
		/**
		 * @Remove row actions
		 * @return {post}
		 */
		public function workreap_withdraw_action_row($actions, $post){
			if ($post->post_type === "withdraw"){
				unset($actions['edit']);
				unset($actions['inline hide-if-no-js']);
			}
			return $actions;
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function workreap_download_withdraw() {
			global $pagenow;
			
			if ( $pagenow == 'edit.php' ) {
				$post_type	= !empty($_GET['post_type']) ? $_GET['post_type'] : '';
							
				if( !empty($post_type) && $post_type === 'withdraw'){
					if( !empty($_POST['years'])) {
						
						$month	= !empty($_POST['months']) ? sprintf("%02d", $_POST['months']) : '';
						$year	= !empty($_POST['years']) ? $_POST['years'] : '';
						$file_name	= !empty($month) ? $month.'-'.$year : $year;
        				header('Content-Type: text/csv');
						header('Content-Disposition: attachment; filename="'.$file_name.'.csv"');
						
						ob_end_clean();

						$output_handle 		= fopen('php://output', 'w');
						$payout_methods		= workreap_get_payouts_lists();
						$filename = "website_data_" . date('Ymd') . ".xls";
						$withdraw_titles	= array(
								esc_html__('User name','workreap_core'),
								esc_html__('Account title','workreap_core'),
								esc_html__('Price','workreap_core'),
								esc_html__('Status','workreap_core'),
								esc_html__('Month','workreap_core'),
								esc_html__('Year','workreap_core'),
								esc_html__('Details','workreap_core'),
						);
						
						$staus		= array('pending','publish','draft');
						$args_array = array(
							'post_status'		=> $staus,
							'post_type'			=> 'withdraw',
							'posts_per_page' 	=> -1,
						);
						

						if( !empty($year) ){
							$args_array['meta_query'][] = array('key' 		=> '_year',
																'value' 	=> intval($year),
																'compare' 	=> '=',
															);
						}

						if( !empty($month) ){
							$args_array['meta_query'][] = array(
																'key' 		=> '_month',
																'value' 	=> $month,
																'compare' 	=> '=',
															);
						}
						
						$post_data		= get_posts($args_array);
	
						$csv_fields     = array();
					
						foreach($withdraw_titles as $title){
							$csv_fields[] = $title;
						}
						
						fputcsv($output_handle, $csv_fields);
	
						if( !empty($post_data) ){
							foreach($post_data as $row){
								$post_author	= !empty($row->post_author) ? $row->post_author : 0;
								$employer_name	= !empty($post_author) ? workreap_get_username($post_author) : '';

								$account_name			= get_post_meta( $row->ID, '_payment_method' ,true);
								$account_name			= !empty($account_name) ? $account_name : '';

								$account_name_val	= !empty($account_name) && !empty($payout_methods[$account_name]['title']) ? $payout_methods[$account_name]['title'] : '';
								$account_details	= get_post_meta( $row->ID, '_account_details',true );
								$account_details	= !empty($account_details) ? maybe_unserialize( $account_details ) : array();

								$account_detail		= '';
								$payout_details	= array();

								if( !empty($payout_methods[$account_name]['fields'])) {
									foreach( $payout_methods[$account_name]['fields'] as $key => $field ){
										
										if(isset($account_details[$key])){
											$account_detail			.= $field['title'].':';
											$account_detail			.= ' ';
											$account_detail			.= !empty($account_details[$key]) ? $account_details[$key]."\r	" : '';
										}
										
									}
								}
								
								$price			= get_post_meta( $row->ID, '_withdraw_amount' ,true);
								$price			= !empty($price) ? $price : 0;

								$year			= get_post_meta( $row->ID, '_year' ,true);
								$year			= !empty($year) ? $year : 0;

								$month			= get_post_meta( $row->ID, '_month' ,true);
								$month			= !empty($month) ? $month : 0;

								$status			= get_post_status( $row->ID );
								$status			= !empty($status) ? ucfirst($status) : '';

								$row_data   = array();
								$row_data['employer_name']	= $employer_name;
								$row_data['account']		= $account_name_val;
								$row_data['price']			= html_entity_decode(workreap_price_format($price,'return'));
								$row_data['status']			= $status;
								$row_data['month']			= $month;
								$row_data['year']			= $year;
								$row_data['details']		= $account_detail;
								
								$OutputRecord = $row_data;   
								fputcsv($output_handle, $OutputRecord);                    
							}
						}
						
						fclose( $output_handle );
						exit;	
					}
					
					if( function_exists('workreap_list_month') ) {
						$months	= workreap_list_month();
					} else {
						$months	= array();
					}
					
					$years = array_combine(range(date("Y"), 1970), range(date("Y"), 1970));
					?>
					<h2 class="wp-heading-inline"><?php esc_html_e('Download withdraw requests','workreap_core');?></h2>
					<form method="post" action="<?php echo admin_url( 'edit.php?post_type=withdraw');?>">
						<div class="tablenav">
							<div class="alignleft">
								<select name="months" id="bulk-action-selector-top">
									<option value=""><?php esc_html_e('Select month','workreap_core');?></option>
									<?php if( !empty( $months ) ) {?>
										<?php foreach ( $months as $key	=> $val ) {
											$selected_m = '';
											if( !empty($month) && $month == $key ){
												$selected_m = 'selected';
											}
											?>
											<option value="<?php echo intval($key);?>" <?php echo esc_attr($selected_m);?>><?php echo esc_attr($val);?></option>
										<?php } ?>
									<?php } ?>
								</select>
								<select name="years" id="bulk-action-selector-top">
									<option value=""><?php echo esc_html__('Select year','workreap_core');?></option>
									<?php if( !empty( $years ) ) {?>
										<?php foreach ( $years as $key	=> $val ) {
											$selected_y = '';
											if( !empty($year) && $year == $key ){
												$selected_y = 'selected';
											}
											?>
											<option value="<?php echo intval($key);?>" <?php echo esc_attr($selected_y);?>><?php echo esc_attr($val);?></option>
										<?php } ?>
									<?php } ?>
								</select>
								<input type="submit" class="button" value="<?php esc_html_e('Download','workreap_core');?>">
							</div>
						</div>
					</form>
					<?php 
				}
				
			}
		}
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function withdraw_columns_add($columns) {
			$columns['price'] 				= esc_html__('Price','workreap_core');
			$columns['account_type'] 		= esc_html__('Account type','workreap_core');
			$columns['acount_details'] 		= esc_html__('Account details','workreap_core');
			$columns['status'] 				= esc_html__('Status','workreap_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function withdraw_columns($case) {
			global $post;
			$price	= get_post_meta( $post->ID, '_withdraw_amount', true );
			$price	= !empty($price) ? $price : '';

			$account_type		= get_post_meta( $post->ID, '_payment_method', true );
			$account_type		= !empty($account_type) ? $account_type : '';
			$status				= get_post_status( $post->ID );
			$status_data		= !empty($status) && $status === 'pending' ? esc_html__('Pending','workreap_core') : esc_html__('Processed','workreap_core');
			$account_details	= get_post_meta($post->ID, '_account_details',true);
			
			switch ($case) {
				case 'price':
					workreap_price_format($price);
				break;

				case 'acount_details':
						$payrols	= workreap_get_payouts_lists();		
					?>
					<div class="order-edit-wrap">
						<div class="view-order-detail">
							<a href="#" onclick="event_preventDefault(event);" data-target="#cus-order-modal-<?php echo esc_attr( $post->ID );?>" class="cus-open-modal cus-btn cus-btn-sm"><?php esc_html_e('View detail','workreap_core');?></a>
						</div>
						<div class="cus-modal" id="cus-order-modal-<?php echo esc_attr( $post->ID );?>">
							<div class="cus-modal-dialog">
								<div class="cus-modal-content">
									<div class="cus-modal-header">
										<a href="#" onclick="event_preventDefault(event);" data-target="#cus-order-modal-<?php echo esc_attr( $post->ID );?>" class="cus-close-modal">×</a>
										<h4 class="cus-modal-title"><?php esc_html_e('Account detail','workreap_core');?></h4>
									</div>
									<div class="cus-modal-body">
										<div class="sp-order-status">
											<p><?php echo ucwords( $status );?></p>
										</div>
										<div class="cus-form cus-form-change-settings">
											<div class="edit-type-wrap">
												<?php 
												$db_saved	= maybe_unserialize( $account_details );
												foreach ($payrols[$account_type]['fields'] as $key => $field) {
													if(!empty($field['show_this']) && $field['show_this'] == true){
														$current_val	= !empty($db_saved[$key]) ? $db_saved[$key] : 0;
													?>
													<div class="cus-options-data">
														<label><span><?php echo esc_html($field['title']);?></span></label>
														<div class="step-value">
															<span><?php echo esc_html($current_val);?></span>
														</div>
													</div>
												<?php }}?>		
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				break;
				
				case 'account_type':
					echo esc_attr( $account_type );
				break;
				
				case 'status':
					?>
					<div class="order-edit-wrap">
						<div class="view-order-detail">
							<?php echo esc_attr( $status_data );?> | <a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval($post->ID);?>" data-status="<?php echo esc_attr($status);?>" class="update-withdraw-status"><?php esc_html_e('Change Status','workreap_core');?></a>
						</div>
					</div>
					<?php 
				break;
				
			}
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
			
			if (function_exists('fw_get_db_settings_option')) {        
				$allow_freelancers_withdraw = fw_get_db_settings_option('allow_freelancers_withdraw', $default_value = null);
			}
			
			if(!empty($allow_freelancers_withdraw) && $allow_freelancers_withdraw === 'admin'){
				return;
			}
			
			$labels = array(
				'name' 			=> esc_html__('Withdraw', 'workreap_core'),
				'all_items' 	=> esc_html__('Withdraw', 'workreap_core'),
				'singular_name' => esc_html__('Withdraw', 'workreap_core'),
				'add_new' 		=> esc_html__('Add Withdraw', 'workreap_core'),
				'add_new_item' 	=> esc_html__('Add New Withdraw', 'workreap_core'),
				'edit' 			=> esc_html__('Edit', 'workreap_core'),
				'edit_item' 	=> esc_html__('Edit Withdraw', 'workreap_core'),
				'new_item' 		=> esc_html__('New Withdraw', 'workreap_core'),
				'view' 			=> esc_html__('View Withdraw', 'workreap_core'),
				'view_item' 	=> esc_html__('View Withdraw', 'workreap_core'),
				'search_items' 	=> esc_html__('Search Withdraw', 'workreap_core'),
				'not_found' 	=> esc_html__('No Withdraw found', 'workreap_core'),
				'not_found_in_trash' 	=> esc_html__('No Withdraw found in trash', 'workreap_core'),
				'parent' 				=> esc_html__('Parent Withdraw', 'workreap_core'),
			);
			
			$args = array(
				'labels' 		=> $labels,
				'description' 	=> esc_html__('This is where you can add new withdraw', 'workreap_core'),
				'public' 		=> false,
				'supports' 		=> array('title','author'),
				'show_ui' 		=> true,
				'capability_type' 	=> 'post',
				'map_meta_cap' 		=> true,
				'menu_position' 	=> 10,
				'publicly_queryable'=> false,
    			'query_var'			=> false,
				'menu_icon'			=> 'dashicons-money-alt',
				'rewrite' 			=> array('slug' => 'withdraw', 'with_front' => true),
				'capabilities' => array(
					'create_posts' => false,
				),
			);
			
			register_post_type('withdraw', $args);
        }
		
		

    }
	
	new Workreap_Withdraw();
}