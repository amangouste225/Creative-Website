<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Reports')) {

    class Workreap_Reports {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_directory_type'));
			add_filter('manage_reports_posts_columns', array(&$this, 'reports_columns_add'));
			add_action('manage_reports_posts_custom_column', array(&$this, 'reports_columns'),10, 2);	
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
                'name'				=> esc_html__('Reports', 'workreap_core'),
                'all_items' 		=> esc_html__('Reports', 'workreap_core'),
                'singular_name' 	=> esc_html__('Report', 'workreap_core'),
                'add_new' 			=> esc_html__('Add Report', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New Report', 'workreap_core'),
                'edit' 				=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit Report', 'workreap_core'),
                'new_item' 			=> esc_html__('New Report', 'workreap_core'),
                'view' 				=> esc_html__('View Report', 'workreap_core'),
                'view_item' 		=> esc_html__('View Report', 'workreap_core'),
                'search_items' 		=> esc_html__('Search Report', 'workreap_core'),
                'not_found' 		=> esc_html__('No Report found', 'workreap_core'),
                'not_found_in_trash'=> esc_html__('No Report found in trash', 'workreap_core'),
                'parent' 			=> esc_html__('Parent Reports', 'workreap_core'),
            );
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new reports ', 'workreap_core'),
                'public' 				=> false,
                'supports' 				=> array('title','editor'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> false,
                'exclude_from_search' 	=> true,
				'show_in_menu' 			=> 'edit.php?post_type=freelancers',
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'report', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array(
											'create_posts' => false
										)
            );
            register_post_type('reports', $args);     
        }
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function reports_columns_add($columns) {
			unset($columns['author']);
			$columns['reported'] 			= esc_html__('Reported by','workreap_core');
			$columns['type'] 				= esc_html__('Report Type','workreap_core');
			$columns['reported_profile'] 	= esc_html__('Reported Posts','workreap_core');
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function reports_columns($case) {
			global $post;
			$reported		= '';
			$type			= '';
			$reported_id	= '';
			
			$reported_profile	= '';
			if (function_exists('fw_get_db_settings_option')) {
				$reported 		= get_post_meta($post->ID, '_user_by', true);
				$reported_id 	= get_post_meta($post->ID, '_reported_id', true);
				$type 			= get_post_meta($post->ID, '_report_type', true);
			}

			if( !empty( $type ) && $type === 'employer' ){
				$report_type	= esc_html__('Employer','workreap_core');
			} else if( !empty( $type ) && $type === 'project' ){
				$report_type	= esc_html__('Project','workreap_core');
			} else if( !empty( $type ) && $type === 'freelancer' ){
				$report_type	= esc_html__('Freelancer','workreap_core');
			}else if( !empty( $type ) && $type === 'service' ){
				$report_type	= esc_html__('Service','workreap_core');
			}
			
			$link	= '<a href="'.get_edit_post_link($reported).'">'.get_the_title($reported).'</a>';
			if( !empty( $reported_id ) ) {
				$reported_profile	= '<a href="'.get_edit_post_link($reported_id).'">'.get_the_title($reported_id).'</a>';
			}
			
			switch ($case) {
				case 'reported':
					echo force_balance_tags( $link );
				break;
				case 'type':
					echo esc_attr( $report_type );
				break;	
				case 'reported_profile':
					echo force_balance_tags( $reported_profile );
				break;
			}
		}

    }

    new Workreap_Reports();
}


