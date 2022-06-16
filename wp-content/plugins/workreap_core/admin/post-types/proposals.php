<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Proposals')) {

    class Workreap_Proposals {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_proposals_posts_columns', array(&$this, 'proposals_columns_add'));
			add_action('manage_proposals_posts_custom_column', array(&$this, 'proposals_columns'),10, 2);	
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
                'name' 			=> esc_html__('Proposals', 'workreap_core'),
                'all_items' 	=> esc_html__('Proposals', 'workreap_core'),
                'singular_name' => esc_html__('Proposal', 'workreap_core'),
                'add_new' 		=> esc_html__('Add Proposal', 'workreap_core'),
                'add_new_item' 	=> esc_html__('Add New Proposal', 'workreap_core'),
                'edit' 			=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 	=> esc_html__('Edit Proposal', 'workreap_core'),
                'new_item' 		=> esc_html__('New Proposal', 'workreap_core'),
                'view' 			=> esc_html__('View Proposal', 'workreap_core'),
                'view_item' 	=> esc_html__('View Proposal', 'workreap_core'),
                'search_items' 	=> esc_html__('Search Proposal', 'workreap_core'),
                'not_found' 	=> esc_html__('No Proposal found', 'workreap_core'),
                'not_found_in_trash' 	=> esc_html__('No Proposal found in trash', 'workreap_core'),
                'parent' 				=> esc_html__('Parent Proposals', 'workreap_core'),
            );
            $args = array(
                'labels' 		=> $labels,
                'description' 	=> esc_html__('This is where you can add new proposal', 'workreap_core'),
                'public' 		=> false,
                'supports' 		=> array('title','editor','comments','author'),
                'show_ui' 		=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> false,
                'exclude_from_search' 	=> true,
                'hierarchical' 			=> false,
				'show_in_menu' 			=> 'edit.php?post_type=projects',
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'proposal', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array(
											'create_posts' => false
										)
            );
			
            register_post_type('proposals', $args);
        }
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function proposals_columns_add($columns) {
			unset($columns['author']);
			unset($columns['date']);
			$columns['send_by'] 	= esc_html__('Send By','workreap_core');
			$columns['amount'] 		= esc_html__('Amount','workreap_core');
			$columns['status'] 		= esc_html__('Status','workreap_core');
			$columns['project'] 	= esc_html__('Project','workreap_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function proposals_columns($case) {
			global $post;
			$send_by	= '';
			$amount		= '';
			$status		= '';
			$proposal_ID	= $post->ID;
			$_project_id 	= get_post_meta($proposal_ID, '_project_id', true);
			$_proposal_id 	= get_post_meta($_project_id, '_proposal_id', true);

			if( !empty($_proposal_id) && ( intval($_proposal_id) === $post->ID ) ) {
				$status		= get_post_field('post_status',$_project_id);
			} 
			
			$status	= !empty( $status ) ? $status : esc_html__('Pending','workreap_core');
			if(!empty($_project_id)) {
				$project_title	= get_the_title($_project_id);
				$project_title	= '<a href="'.get_edit_post_link($_project_id).'">'.$project_title.'</a>';
			} else {
				$project_title	= '';
			}
			
			if (function_exists('fw_get_db_settings_option')) {
				$send_by 	= get_post_meta($proposal_ID, '_send_by', true);
				$amount 	= get_post_meta($proposal_ID, '_amount', true);
			}
			
            $link   = '<a href="'.get_edit_post_link($send_by).'">'.get_the_title($send_by).'</a>';
            
			switch ($case) {
				case 'send_by':
					echo force_balance_tags( $link );
				break;
				
				case 'amount':
						workreap_price_format( $amount );
				break;
				
				case 'status':
					echo esc_attr( ucfirst($status) );
				break;
					
				case 'project':
					echo force_balance_tags( $project_title );
				break;
				
			}
		}

    }

    new Workreap_Proposals();
}


