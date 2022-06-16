<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Addons_Services')) {

    class Workreap_Addons_Services {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_services_posts_columns', array(&$this, 'services_columns_add'));
			add_action('manage_services_posts_custom_column', array(&$this, 'services_columns'),10, 2);	
			add_action('add_meta_boxes', array(&$this, 'addons_service_meta_box'), 10, 2);
        }
		
		/**
		 * @Linked Chat metabox
		 * @return {post}
		 */
		public function addons_service_meta_box($post_type,$post) {
			
			if ($post_type === 'micro-services') {
				
				if (function_exists('fw_get_db_post_option') ) {
					$remove_service_addon		= fw_get_db_settings_option('remove_service_addon');
				}

				$remove_service_addon	= !empty($remove_service_addon) ? $remove_service_addon : 'no';

				if(!empty($remove_service_addon) && $remove_service_addon === 'no'){
			
					add_meta_box(
							'workreap_addons_services', esc_html__('Addons Services', 'workreap_core'), array(&$this, 'addons_services_meta_box_print'), $post_type, 'advanced', 'high'
					);
				}
				
				if (function_exists('fw_get_db_post_option')) {
					$db_downloadable   	= fw_get_db_post_option($post->ID,'downloadable');
				}
				
				$db_downloadable	= !empty( $db_downloadable ) && $db_downloadable !== 'no' ? $db_downloadable : '';
				
				if( !empty( $db_downloadable ) && $db_downloadable === 'yes' ){ 
					add_meta_box(
							'workreap_downloadable_services', esc_html__('Downloadable Services', 'workreap_core'), array(&$this, 'downloadable_services_meta_box_print'), $post_type, 'advanced', 'high'
					);
				}
				
            }
		}
		
		/**
		 * @Linked chat metabox
		 * @return {post}
		 */
		public function downloadable_services_meta_box_print($post) { ?>
			<div class="wt-addonsservices wt-tabsinfo">
				<div class="wt-addonservices-content">
					<?php
						$downloadable_files		= get_post_meta( $post->ID, '_downloadable_files', true);
						$downloadable_files		= !empty( $downloadable_files ) ? $downloadable_files : array();
						if( !empty( $downloadable_files ) ){ ?>
							<div class="wt-rightarea">
								<a class="wt-btn wt-download-files-doenload" data-id="<?php echo intval($post->ID);?>" href="#"><?php esc_html_e('Download','workreap_core');?></a>
							</div>
						<?php } else{ ?>
							<div class="no-record-item"><span><?php esc_html_e('No files to download','workreap_core');?></span></div>
						<?php }?>
				</div>
			</div>
			<?php 
		}
		
		/**
		 * @Linked chat metabox
		 * @return {post}
		 */
		public function addons_services_meta_box_print($post) {

			$db_addons			= get_post_meta($post->ID,'_addons',true);
			$db_addons			= !empty( $db_addons ) ? $db_addons : array();
			
			$post_author 		= get_post_field('post_author', $post->ID);
			$args_addons = array(
					'author'        =>  $post_author,
					'post_type'		=> 	'addons-services',
					'post_status'	=>  'publish',
					'orderby'       =>  'post_date',
					'order'         =>  'ASC',
					'posts_per_page' => -1
				);
			$addons		= get_posts( $args_addons );
			?>
			<div class="wt-addonsservices wt-tabsinfo">
				<div class="wt-addonservices-content">
					<ul>
						<?php 
							if( !empty( $addons ) ){ 
								foreach( $addons as $addon ) { 
									$db_price			= 0;
									if (function_exists('fw_get_db_post_option')) {
										$db_price   = fw_get_db_post_option($addon->ID,'price');
									}

									$checked = '';
									if( in_array($addon->ID,$db_addons) ){
										$checked = 'checked';
									}
							?>
							<li>
								<div class="wt-checkbox">
									<input id="rate<?php echo intval($addon->ID);?>" type="checkbox" name="service[addons][]" value="<?php echo intval($addon->ID);?>" <?php echo esc_attr( $checked );?> >
									<label for="rate<?php echo intval($addon->ID);?>">
										<?php if( !empty( $addon->post_title ) ){?>
											<h3><?php echo esc_html( $addon->post_title );?></h3>
										<?php } ?>
										<?php if( !empty( $addon->post_excerpt ) ){?>
											<p><?php echo esc_html( $addon->post_excerpt);?></p>
										<?php } ?>
										<?php if( !empty( $db_price ) ){?>
											<strong><?php workreap_price_format($db_price);?></strong>
										<?php } ?>
									</label>
								</div>
							</li>
							<?php }}else{?>
								<li class="no-record-item"><span><?php esc_html_e('No addon services added yet','workreap_core');?></span></li>
							<?php }?>
						</ul>
					</div>
				</div>
			<?php
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
			if (function_exists('fw_get_db_post_option') ) {
				$remove_service_addon		= fw_get_db_settings_option('remove_service_addon');
			}

			$remove_service_addon	= !empty($remove_service_addon) ? $remove_service_addon : 'no';

			if(!empty($remove_service_addon) && $remove_service_addon === 'no'){

				if( apply_filters('workreap_system_access','service_base') === true ){
					$labels = array(
						'name' 			=> esc_html__('Addons Services', 'workreap_core'),
						'all_items' 	=> esc_html__('Addons Services', 'workreap_core'),
						'singular_name' => esc_html__('Addons Services', 'workreap_core'),
						'add_new' 		=> esc_html__('Add Addons Service', 'workreap_core'),
						'add_new_item' 	=> esc_html__('Add New Addons Service', 'workreap_core'),
						'edit' 			=> esc_html__('Edit', 'workreap_core'),
						'edit_item' 	=> esc_html__('Edit Addons Service', 'workreap_core'),
						'new_item' 		=> esc_html__('New Addons Service', 'workreap_core'),
						'view' 			=> esc_html__('View Addons Service', 'workreap_core'),
						'view_item' 	=> esc_html__('View Addons Service', 'workreap_core'),
						'search_items' 	=> esc_html__('Search Addons Service', 'workreap_core'),
						'not_found' 	=> esc_html__('No Addons Service found', 'workreap_core'),
						'not_found_in_trash' 	=> esc_html__('No Addons Service found in trash', 'workreap_core'),
						'parent' 				=> esc_html__('Parent Addons Service', 'workreap_core'),
					);
					$args = array(
						'labels' 		=> $labels,
						'description' 	=> esc_html__('This is where you can add new Micro Service', 'workreap_core'),
						'public' 		=> false,
						'supports' 		=> array('title','author','excerpt'),
						'show_ui' 		=> true,
						'capability_type' 		=> 'post',
						'map_meta_cap' 			=> true,
						'menu_position' 		=> 10,
						'menu_icon'				=> 'dashicons-plus-alt',
						'show_in_menu' 			=> 'edit.php?post_type=micro-services',
						'rewrite' 				=> array('slug' => 'service', 'with_front' => true),
					);

					register_post_type('addons-services', $args);
				}
			}
        }
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function services_columns_add($columns) {
			unset($columns['date']);
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function services_columns($case) {
			global $post;
			
		}

    }
	
	new Workreap_Addons_Services();
}


