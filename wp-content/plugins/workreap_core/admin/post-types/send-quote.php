<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Service_Quote')) {

    class Workreap_Service_Quote {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_send-quote_posts_columns', array(&$this, 'services_columns_add'));
			add_action('manage_send-quote_posts_custom_column', array(&$this, 'services_columns'),10, 2);	
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
					'name' 			=> esc_html__('Service quotes', 'workreap_core'),
					'all_items' 	=> esc_html__('Service quotes', 'workreap_core'),
					'singular_name' => esc_html__('Service quotes', 'workreap_core'),
					'add_new' 		=> esc_html__('Add service quote', 'workreap_core'),
					'add_new_item' 	=> esc_html__('Add New service quote', 'workreap_core'),
					'edit' 			=> esc_html__('Edit', 'workreap_core'),
					'edit_item' 	=> esc_html__('Edit service quote', 'workreap_core'),
					'new_item' 		=> esc_html__('New service quote', 'workreap_core'),
					'view' 			=> esc_html__('View service quote', 'workreap_core'),
					'view_item' 	=> esc_html__('View service quote', 'workreap_core'),
					'search_items' 	=> esc_html__('Search service quote', 'workreap_core'),
					'not_found' 	=> esc_html__('No service quote found', 'workreap_core'),
					'not_found_in_trash' 	=> esc_html__('No service quote found in trash', 'workreap_core'),
					'parent' 				=> esc_html__('Parent service quote', 'workreap_core'),
				);

				$args = array(
					'labels' 		=> $labels,
					'description' 	=> esc_html__('This is where you can add new quote', 'workreap_core'),
					'public' 		=> false,
					'supports' 		=> array('title','editor','author'),
					'show_ui' 		=> true,
					'capability_type' 		=> 'post',
					'map_meta_cap' 			=> true,
					'menu_position' 		=> 10,
					'show_in_menu' 			=> 'edit.php?post_type=micro-services',
					'menu_icon'				=> 'dashicons-clipboard',
					'rewrite' 				=> array('slug' => 'quote', 'with_front' => true),
					'query_var' 			=> false,
					'has_archive' 			=> false,
					'capabilities' 			=> array('create_posts' => false)
				);

				register_post_type('send-quote', $args);
			}
        }
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function services_columns_add($columns) {
			unset($columns['date']);
			unset($columns['author']);
			
		 	$columns['quote_price'] 	= esc_html__('Quote price','workreap_core');
			$columns['send_by'] 		= esc_html__('Send by','workreap_core');
			$columns['send_to'] 		= esc_html__('Send to','workreap_core');
			$columns['status'] 			= esc_html__('Status','workreap_core');
			$columns['service'] 		= esc_html__('service','workreap_core');
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function services_columns($name) {
			global $post;
			switch ($name) {
				case 'quote_price':
					$price = get_post_meta($post->ID,'price',true);
					echo workreap_price_format($price);
					break;
				case 'send_by':
					$send_by	= !empty( $post->post_author ) ? intval($post->post_author) : 0;
					$linked_profile   	= workreap_get_linked_profile_id($send_by);
					echo '<a href="'.get_the_permalink($linked_profile).'">'.get_the_title($linked_profile).'</a>';
					break;
				case 'send_to':
					$send_to = get_post_meta($post->ID,'employer',true);
					$linked_profile   	= workreap_get_linked_profile_id($send_to);
					echo '<a href="'.get_the_permalink($linked_profile).'">'.get_the_title($linked_profile).'</a>';
					break;
				case 'service':
					$service = get_post_meta($post->ID,'service',true);
					echo '<a href="'.get_the_permalink($service).'">'.get_the_title($service).'</a>';
					break;
				case 'status':
					$service = get_post_meta($post->ID,'hiring_status',true);
					if(!empty($service) && $service === 'hired'){
						echo '<span class="wt-status-completed">'.esc_html__('Hired','workreap_core').'</span>';
					}else{
						echo '<span class="wt-status-hourly">'.esc_html__('Pending','workreap_core').'</span>';
					}

					break;
				default:
					return;
            }
			
		}

    }
	
	new Workreap_Service_Quote();
}


