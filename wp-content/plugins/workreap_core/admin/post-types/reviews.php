<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Reviews')) {

    class Workreap_Reviews {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_directory_type'));
			add_filter('manage_reviews_posts_columns', array(&$this, 'reviews_columns_add'));
			add_action('manage_reviews_posts_custom_column', array(&$this, 'reviews_columns'),10, 2);	
			add_action('add_meta_boxes', array(&$this, 'reviews_add_meta_box'), 10, 1);
			add_action('save_post', array(&$this, 'reviews_save_meta_box'), 10);
        }

        /**
         * @Init Post Type
         * @return {post}
         */
        public function init_directory_type() {
            $this->prepare_post_type();
        }

        /**
         * @Prepare Post Type Category
         * @return post type
         */
        public function prepare_post_type() {
            $labels = array(
                'name' 				=> esc_html__('Reviews', 'workreap_core'),
                'all_items' 		=> esc_html__('Reviews', 'workreap_core'),
                'singular_name' 	=> esc_html__('Reviews', 'workreap_core'),
                'add_new' 			=> esc_html__('Add Review', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New Review', 'workreap_core'),
                'edit' 				=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit Review', 'workreap_core'),
                'new_item' 			=> esc_html__('New Review', 'workreap_core'),
                'view' 				=> esc_html__('View Review', 'workreap_core'),
                'view_item' 		=> esc_html__('View Review', 'workreap_core'),
                'search_items' 		=> esc_html__('Search Review', 'workreap_core'),
                'not_found' 		=> esc_html__('No Review found', 'workreap_core'),
                'not_found_in_trash' 	=> esc_html__('No Review found in trash', 'workreap_core'),
                'parent' 				=> esc_html__('Parent Reviews', 'workreap_core'),
            );
            $args = array(
                'labels' 			=> $labels,
                'description' 		=> esc_html__('This is where you can add new Reviews ', 'workreap_core'),
                'public' 			=> true,
                'supports' 			=> array('title', 'editor'),
                'show_ui' 			=> true,
                'capability_type' 	=> 'post',
                'map_meta_cap' 		=> true,
                'publicly_queryable' 	=> true,
                'exclude_from_search' 	=> true,
                'hierarchical' 			=> false,
				'show_in_menu' 			=> 'edit.php?post_type=freelancers',
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'reviews', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array(
											'create_posts' => false
										)
            );
            register_post_type('reviews', $args);
        }
		
		/**
         * @Init Meta Boxes
         * @return {post}
         */
        public function reviews_add_meta_box($post_type) {
            if ($post_type == 'reviews') {
                add_meta_box(
                        'reviews_info', esc_html__('Review Info', 'workreap_core'), array(&$this, 'reviews_meta_box_reviewinfo'), 'reviews', 'side', 'high'
                );
            }
        }
		
		/**
         * @Init Review info
         * @return {post}
         */
        public function reviews_meta_box_reviewinfo() {
            global $post;

            if ( function_exists('fw_get_db_settings_option') ) {
                $user_from_id 		= get_post_meta( $post->ID, 'user_from', true );
                $user_to_id 		= get_post_meta( $post->ID, 'user_to', true );
                $rating 			= get_post_meta( $post->ID, 'user_rating', true );
				$project_id 		= get_post_meta( $post->ID, '_project_id', true );
				$project_title 		= get_post_field('post_title',$project_id);
                $user_from 			= workreap_get_username( '' , $user_from_id );
                $user_to 			= workreap_get_username( '' , $user_to_id );

            } else {

                $user_from 		= '';
                $user_to 		= '';
                $rating 		= 0;
				$project_title 	='';
            }
            ?>
            <ul class="review-info">
                <li>
                    <span class="push-left">
                    	<strong>
                    		<?php esc_html_e('Rating:', 'workreap_core'); ?>
                    	</strong>
                    </span>
                    <span class="push-right">
                    	<?php echo esc_attr($rating); ?>/<?php echo intval(5); ?>
                    </span>
                </li>
                <?php if (!empty( $user_from )) { ?>
                    <li>
                        <span class="push-left">
                        	<strong><?php esc_html_e('Review By', 'workreap_core'); ?>:</strong>
                        </span>
                        <span class="push-right">
                        	<a href="<?php echo esc_url( get_the_permalink($user_from_id)); ?>" target="_blank" title="<?php esc_html__('Click for user details', 'workreap_core'); ?>">
                        		<?php echo esc_attr($user_from); ?>
                        	</a>
                        </span>
                    </li>
                <?php } ?>
                <?php if (!empty( $user_to )) { ?>
                    <li>
                        <span class="push-left">
                        	<strong>
                        		<?php esc_html_e('Review To', 'workreap_core'); ?>
                        	</strong>
                        </span>
                        <span class="push-right">
                        	<a href="<?php echo esc_url( get_the_permalink( $user_to_id )); ?>" target="_blank" title="<?php esc_html__('Click for user details', 'workreap_core'); ?>">
                        		<?php echo esc_attr( $user_to ); ?>
                        	</a>
                        </span>
                    </li>
                <?php } ?>
            </ul>
            <?php
        }
		
		/**
         * @Init Save Meta Boxes
         * @return {post}
         */
        public function reviews_save_meta_box() {
            global $post;
           
        }
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function reviews_columns_add($columns) {
			unset($columns['author']);
			$columns['user_rating'] 		= esc_html__('Review','workreap_core');
			$columns['project'] 			= esc_html__('Project','workreap_core');
			$columns['user_from'] 			= esc_html__('Review by','workreap_core');
			$columns['user_to'] 			= esc_html__('Review to','workreap_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function reviews_columns($name) {
            global $post;

            $link_from = '';
            $rating 	= '';

            if (function_exists('fw_get_db_settings_option')) {
				
                $user_from 		= get_post_meta( $post->ID, 'user_from', true );
                $user_to 		= get_post_meta( $post->ID, 'user_to', true );
				$project_id 	= get_post_meta( $post->ID, '_project_id', true );
                $rating 		= get_post_meta( $post->ID, 'user_rating', true );
				
				$project_title 			= get_post_field( 'post_title',$project_id );
				$user_from_title 		= workreap_get_username( '' , $user_from );
                $user_to_title 			= workreap_get_username( '' , $user_to );
				
				$link_from   			= '<a href="'.get_edit_post_link($user_from).'">'.$user_from_title.'</a>';
				$link_to   				= '<a href="'.get_edit_post_link($user_to).'">'.$user_to_title.'</a>';
				$link_project			= '<a href="'.get_edit_post_link($project_id).'">'.$project_title.'</a>';
            }

            switch ($name) {
                case 'user_from':
                   if (!empty( $link_from) ) {
                        echo force_balance_tags( $link_from );
                    }
                    break;
                case 'user_to':
                    if (!empty( $link_to ) ) {
                        echo force_balance_tags( $link_to );
                    }
                    break;
				case 'project':
                    if (!empty( $link_project ) ) {
                       echo force_balance_tags( $link_project );
                    }
                    break;
                case 'user_rating':
                    printf('%s', $rating);
                    break;
            }
        }

    }

    new Workreap_Reviews();
}


