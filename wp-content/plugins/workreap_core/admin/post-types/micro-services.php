<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Services')) {

    class Workreap_Services {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_micro-services_posts_columns', array(&$this, 'services_columns_add'));
			add_action('manage_micro-services_posts_custom_column', array(&$this, 'services_columns'),10, 2);	
			add_action('add_meta_boxes', array(&$this, 'add_custom_meta_box'), 10, 2);
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
			if( apply_filters('workreap_system_access','service_base') === true ){
				$labels = array(
					'name' 			=> esc_html__('Micro Services', 'workreap_core'),
					'all_items' 	=> esc_html__('Micro Services', 'workreap_core'),
					'singular_name' => esc_html__('Micro Services', 'workreap_core'),
					'add_new' 		=> esc_html__('Add Micro Service', 'workreap_core'),
					'add_new_item' 	=> esc_html__('Add New Micro Service', 'workreap_core'),
					'edit' 			=> esc_html__('Edit', 'workreap_core'),
					'edit_item' 	=> esc_html__('Edit Micro Service', 'workreap_core'),
					'new_item' 		=> esc_html__('New Micro Service', 'workreap_core'),
					'view' 			=> esc_html__('View Micro Service', 'workreap_core'),
					'view_item' 	=> esc_html__('View Micro Service', 'workreap_core'),
					'search_items' 	=> esc_html__('Search Micro Service', 'workreap_core'),
					'not_found' 	=> esc_html__('No Micro Service found', 'workreap_core'),
					'not_found_in_trash' 	=> esc_html__('No Micro Service found in trash', 'workreap_core'),
					'parent' 				=> esc_html__('Parent Micro Service', 'workreap_core'),
				);
				$args = array(
					'labels' 		=> $labels,
					'description' 	=> esc_html__('This is where you can add new Micro Service', 'workreap_core'),
					'public' 		=> true,
					'supports' 		=> array('title','editor','author','excerpt','thumbnail'),
					'show_ui' 		=> true,
					'capability_type' 		=> 'post',
					'map_meta_cap' 			=> true,
					'menu_position' 		=> 10,
					'menu_icon'				=> 'dashicons-clipboard',
					'rewrite' 				=> array('slug' => 'service', 'with_front' => true),
					'capabilities' 			=> array('create_posts' => false)
				);

				register_post_type('micro-services', $args);

				//Regirster Delivery Taxonomy
				$delivery_labels = array(
					'name' 				=> esc_html__('Delivery Time', 'workreap_core'),
					'singular_name' 	=> esc_html__('Delivery Time','workreap_core'),
					'search_items' 		=> esc_html__('Search Delivery Time', 'workreap_core'),
					'all_items' 		=> esc_html__('All Delivery Time', 'workreap_core'),
					'parent_item' 		=> esc_html__('Parent Delivery Time', 'workreap_core'),
					'parent_item_colon' => esc_html__('Parent Delivery Time:', 'workreap_core'),
					'edit_item' 		=> esc_html__('Edit Delivery Time', 'workreap_core'),
					'update_item' 		=> esc_html__('Update Delivery Time', 'workreap_core'),
					'add_new_item' 		=> esc_html__('Add New Delivery Time', 'workreap_core'),
					'new_item_name' 	=> esc_html__('New Delivery Time Name', 'workreap_core'),
					'menu_name' 		=> esc_html__('Delivery Time', 'workreap_core'),
				);

				$delivery_args = array(
					'hierarchical' 			=> true,
					'labels' 				=> $delivery_labels,
					'show_in_quick_edit' 	=> true,
					'show_admin_column' 	=> false,
					'show_in_nav_menus' 	=> false,
					'query_var' 			=> true,
					'show_ui'               => true,
					'rewrite' 				=> array('slug' => 'delivery'),
				);
				register_taxonomy('delivery', array('micro-services'), $delivery_args);

				//Regirster Response Taxonomy
				$response_labels = array(
					'name' 				=> esc_html__('Response Time', 'workreap_core'),
					'singular_name' 	=> esc_html__('Response Time','workreap_core'),
					'search_items' 		=> esc_html__('Search Response Time', 'workreap_core'),
					'all_items' 		=> esc_html__('All Response Time', 'workreap_core'),
					'parent_item' 		=> esc_html__('Parent Response Time', 'workreap_core'),
					'parent_item_colon' => esc_html__('Parent Response Time:', 'workreap_core'),
					'edit_item' 		=> esc_html__('Edit Response Time', 'workreap_core'),
					'update_item' 		=> esc_html__('Update Response Time', 'workreap_core'),
					'add_new_item' 		=> esc_html__('Add New Response Time', 'workreap_core'),
					'new_item_name' 	=> esc_html__('New Response Time Name', 'workreap_core'),
					'menu_name' 		=> esc_html__('Response Time', 'workreap_core'),
				);

				$response_args = array(
					'hierarchical' 			=> true,
					'labels' 				=> $response_labels,
					'show_in_quick_edit' 	=> true,
					'show_admin_column' 	=> false,
					'show_in_nav_menus' 	=> false,
					'query_var' 			=> true,
					'show_ui'               => true,
					'rewrite' 				=> array('slug' => 'response-time'),
				);
				register_taxonomy('response_time', array('micro-services'), $response_args);

			}
        }
		
		/**
		 * @metabox
		 * @return {post}
		 */
		public function add_custom_meta_box($post_type,$post) {
			if ($post_type === 'micro-services') {
				if (function_exists('fw_get_db_settings_option')) {        
					$job_status = fw_get_db_settings_option('service_status', $default_value = null);
				}
				
				if(!empty($post->ID)){
                    $post_status	= get_post_meta($post->ID,'post_rejected',true);	
                }
    
                if(!empty($post_status) && $post_status === 'yes'){return;}
				
				if( ( $post->post_status === 'pending' ) ){
					add_meta_box( 'publish_post', esc_html__('Service Options', 'workreap_core'), array(&$this, 'approve_service_meta_box_print'), 'micro-services', 'side', 'high');
				}
            }
		}
		
		/**
		 * @Approve metabox
		 * @return {post}
		 */
		public function approve_service_meta_box_print($post) {
			$linked_profile	= $post->post_author;
			if(empty( $linked_profile )){return;}
			
			?>
			<ul class="review-info">
                <?php if( ( $post->post_status === 'pending' ) ){?>
					<li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_approve_post" data-type="service" data-post="<?php echo esc_attr( $post->ID );?>" data-id="<?php echo esc_attr( $linked_profile );?>"><?php esc_html_e('Approve Service', 'workreap_core'); ?></a>
						</span>
					</li>
					<li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_reject_post" data-type="service" data-post="<?php echo esc_attr( $post->ID );?>" data-id="<?php echo esc_attr( $linked_profile );?>"><?php esc_html_e('Reject Service', 'workreap_core'); ?></a>
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
		public function services_columns_add($columns) {
			unset($columns['date']);
			
		 	$columns['downloadable'] 		= esc_html__('Downloadable','workreap_core');
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function services_columns($name) {
			global $post;
			
			if (function_exists('fw_get_db_post_option')) {
				$db_downloadable   	= fw_get_db_post_option($post->ID,'downloadable');
			}
				
			$db_downloadable	= !empty( $db_downloadable ) ? $db_downloadable : 'no';
			
			switch ($name) {
                case 'downloadable':
					if (!empty( $db_downloadable) ) {
						echo ucfirst( $db_downloadable );
					}
				break;
            }
			
		}

    }
	
	new Workreap_Services();
}


