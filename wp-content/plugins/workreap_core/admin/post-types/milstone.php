<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Milestone')) {

    class Workreap_Milestone {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
			add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_wt-milestone_posts_columns', array(&$this, 'milestone_columns_add'));
			add_action('manage_wt-milestone_posts_custom_column', array(&$this, 'milestone_columns'),10, 2);
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
			
			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone		= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			$milestone			= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

			$labels = array(
				'name' 			=> esc_html__('Milestone', 'workreap_core'),
				'all_items' 	=> esc_html__('Milestone', 'workreap_core'),
				'singular_name' => esc_html__('Milestone', 'workreap_core'),
				'add_new' 		=> esc_html__('Add Milestone', 'workreap_core'),
				'add_new_item' 	=> esc_html__('Add New Milestone', 'workreap_core'),
				'edit' 			=> esc_html__('Edit', 'workreap_core'),
				'edit_item' 	=> esc_html__('Edit Milestone', 'workreap_core'),
				'new_item' 		=> esc_html__('New Milestone', 'workreap_core'),
				'view' 			=> esc_html__('View Milestone', 'workreap_core'),
				'view_item' 	=> esc_html__('View Milestone', 'workreap_core'),
				'search_items' 	=> esc_html__('Search Milestone', 'workreap_core'),
				'not_found' 	=> esc_html__('No Milestone found', 'workreap_core'),
				'not_found_in_trash' 	=> esc_html__('No Milestone found in trash', 'workreap_core'),
				'parent' 				=> esc_html__('Parent Milestone', 'workreap_core'),
			);
			$args = array(
				'labels' 		=> $labels,
				'description' 	=> esc_html__('This is where you can add new Milestone', 'workreap_core'),
				'public' 		=> false,
				'exclude_from_search' 	=> true,
				'supports' 		=> array('title','author','editor'),
				'show_ui' 		=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap' 			=> true,
				'menu_position' 		=> 12,
				'menu_icon'				=> 'dashicons-schedule',
				'rewrite' 				=> array('slug' => 'wt-milestone', 'with_front' => true),
			);
			if( !empty($milestone) && $milestone === 'enable'){		
				register_post_type('wt-milestone', $args);
			}
        }
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function milestone_columns_add($columns) {
			unset($columns['author']);
			unset($columns['date']);
			$columns['project_title'] 		= esc_html__('Project Title', 'workreap_core');
			$columns['freelancer'] 			= esc_html__('Freelancer', 'workreap_core');
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function milestone_columns($case) {
			global $post;

			$project_id		= get_post_meta( $post->ID, '_project_id', true );
			$project_id		= !empty($project_id) ? intval($project_id) : '';
			$project_title	= !empty($project_id) ? get_the_title($project_id) : '';

			$freelancer_id		= get_post_meta( $post->ID, '_freelancer_id', true );
			$freelancer_id		= !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id) : '';

			$profile_name		= !empty($freelancer_id) ? workreap_get_username('',$freelancer_id) : '';
			$link   		= '<a href="'.get_edit_post_link($freelancer_id).'">'.$profile_name.'</a>';
			$project_link   = '<a href="'.get_edit_post_link($project_id).'">'.$project_title.'</a>';
			
			switch ($case) {
				case 'project_title':
					echo force_balance_tags( $project_link );
				break;
				
				case 'freelancer':
					echo force_balance_tags( $link );
				break;
				
				
			}
			
		}

    }
	
	new Workreap_Milestone();
}


