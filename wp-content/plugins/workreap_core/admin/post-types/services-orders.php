<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Services_Orders')) {

    class Workreap_Services_Orders {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_services_posts_columns', array(&$this, 'services_columns_add'));
			add_action('add_meta_boxes', array(&$this, 'service_order_add_meta_box'), 10, 1);
			add_action('manage_services_posts_custom_column', array(&$this, 'services_columns'),10, 2);	
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
					'name' 			=> esc_html__('Service Order', 'workreap_core'),
					'all_items' 	=> esc_html__('Service Order', 'workreap_core'),
					'singular_name' => esc_html__('Service order', 'workreap_core'),
					'add_new' 		=> esc_html__('Add Service order', 'workreap_core'),
					'add_new_item' 	=> esc_html__('Add New Service order', 'workreap_core'),
					'edit' 			=> esc_html__('Edit', 'workreap_core'),
					'edit_item' 	=> esc_html__('Edit Service order', 'workreap_core'),
					'new_item' 		=> esc_html__('New Service order', 'workreap_core'),
					'view' 			=> esc_html__('View Service order', 'workreap_core'),
					'view_item' 	=> esc_html__('View Service order', 'workreap_core'),
					'search_items' 	=> esc_html__('Search Service order', 'workreap_core'),
					'not_found' 	=> esc_html__('No Service order found', 'workreap_core'),
					'not_found_in_trash' 	=> esc_html__('No Service order found in trash', 'workreap_core'),
					'parent' 				=> esc_html__('Parent Service order', 'workreap_core'),
				);
				$args = array(
					'labels' 		=> $labels,
					'description' 	=> esc_html__('This is where you can add new Service order', 'workreap_core'),
					'public' 		=> false,
					'supports' 		=> array('title','author','comments'),
					'show_ui' 		=> true,
					'capability_type' 		=> 'post',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'menu_position' 		=> 10,
					'menu_icon'				=> 'dashicons-welcome-write-blog',
					'rewrite' 				=> array('slug' => 'services-orders', 'with_front' => true),
					'query_var' 			=> false,
					'has_archive' 			=> false,
					'capabilities' 			=> array(
												'create_posts' => false
											)
				);

				register_post_type('services-orders', $args);
			}
        }
		
		/**
         * @Init Meta Boxes
         * @return {post}
         */
        public function service_order_add_meta_box( $post_type ) {
            if ($post_type == 'services-orders') {
                add_meta_box(
                        'service_order_info', esc_html__('Review Info', 'workreap_core'), array(&$this, 'service_order_meta_box_reviewinfo'), 'services-orders', 'side', 'high'
                );
            }
        }
		
		/**
         * @Init Review info
         * @return {post}
         */
        public function service_order_meta_box_reviewinfo() {
            global $post;
			
			$service_title 	='';
			
            $service_id 		= get_post_meta( $post->ID, '_service_id', true );
			$service_title 		= get_the_title( $service_id ); 
			$dynamic_rating_data = array();
            ?>
            <ul class="review-info">
                <?php if (!empty( $service_id )) { ?>
                    <li>
                        <span class="push-left">
                        	<strong><?php esc_html_e('Service', 'workreap_core'); ?>:</strong>
                        </span>
                        <span class="push-right">
                        	<a href="<?php echo esc_url( get_the_permalink($service_id)); ?>" target="_blank" title="<?php esc_html__('Click for user details', 'workreap_core'); ?>">
                        		<?php echo esc_html($service_title); ?>
                        	</a>
                        </span>
                    </li>
                <?php }?>
                
                <?php if (!empty($_GET['post'])) {?>
                	<li>
                		<strong><?php esc_html_e('Rating', 'workreap_core'); ?></strong>
                		<div class="edit-type-wrap rating-data">
						<?php 
							$review_id 		= intval($_GET['post']);
							$rating_titles 	= workreap_project_ratings('services_ratings');

							if (!empty($rating_titles)) {
								foreach ($rating_titles as $slug => $label) {
									$rating_titles	=  get_post_meta($review_id,$slug,true);
									$rating 		= !empty($rating_titles) ? intval( $rating_titles ) *20 : 0; 
									?>
									<div class="cus-options-data">
										<label><span><?php echo esc_html($label); ?></span></label>
										<div class="step-value-wrap">
											<div class="step-value" style="width:<?php echo esc_attr($rating);?>%"></div>
											<span><?php echo esc_html($rating_titles); ?></span>
										</div>
									</div>
									<?php
								}
							}
						?>
						</div>
					</li>
					<?php 
					}
				?>
            </ul>
            <?php
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
	new Workreap_Services_Orders();
}


