<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Portfolio')) {

    class Workreap_Portfolio {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_action('manage_portfolio_posts_custom_column', array(&$this, 'portfolio_columns'),10, 2);	
        }
		
        /**
         * @Init Post Type
         * @return {post}
         */
        public function init_post_type() {
            $this->prepare_post_type();
        }

        /**
         * @Prepare Post Type & Category
         * @return post type
         */
        public function prepare_post_type() {
			$labels = array(
				'name' 			=> esc_html__('Portfolios', 'workreap_core'),
				'all_items' 	=> esc_html__('Portfolios', 'workreap_core'),
				'singular_name' => esc_html__('Portfolios', 'workreap_core'),
				'add_new' 		=> esc_html__('Add Portfolio', 'workreap_core'),
				'add_new_item' 	=> esc_html__('Add New Portfolio', 'workreap_core'),
				'edit' 			=> esc_html__('Edit', 'workreap_core'),
				'edit_item' 	=> esc_html__('Edit Portfolio', 'workreap_core'),
				'new_item' 		=> esc_html__('New Portfolio', 'workreap_core'),
				'view' 			=> esc_html__('View Portfolio', 'workreap_core'),
				'view_item' 	=> esc_html__('View Portfolio', 'workreap_core'),
				'search_items' 	=> esc_html__('Search Portfolio', 'workreap_core'),
				'not_found' 	=> esc_html__('No Portfolio found', 'workreap_core'),
				'not_found_in_trash' 	=> esc_html__('No Portfolio found in trash', 'workreap_core'),
				'parent' 				=> esc_html__('Parent Portfolio', 'workreap_core'),
			);
			$args = array(
				'labels' 		=> $labels,
				'description' 	=> esc_html__('This is where you can add new Portfolio', 'workreap_core'),
				'public' 		=> true,
				'supports' 		=> array('title','editor','author','excerpt','comments','thumbnail'),
				'show_ui' 		=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap' 			=> true,
				'menu_icon'				=> 'dashicons-portfolio',
				'menu_position' 		=> 10,
				'rewrite' 				=> array('slug' => 'portfolio', 'with_front' => true),
			);

			register_post_type('wt_portfolio', $args);

			//Regirster Category Taxonomy
            $cat_labels = array(
                'name' 					=> _x('Categories', 'Categories for portfolio', 'workreap_core' ),
                'singular_name' 		=> _x('Category', 'Categories for portfolio','workreap_core'),
                'search_items'			=> _x('Search category', 'Categories for portfolio', 'workreap_core'),
                'all_items' 			=> _x('All category', 'Categories for portfolio', 'workreap_core'),
                'parent_item' 			=> _x('Parent category', 'Categories for portfolio', 'workreap_core'),
                'parent_item_colon' 	=> _x('Parent category:', 'Categories for portfolio', 'workreap_core'),
                'edit_item' 			=> _x('Edit category', 'Categories for portfolio', 'workreap_core'),
                'update_item' 			=> _x('Update category', 'Categories for portfolio', 'workreap_core'),
                'add_new_item' 			=> _x('Add New category', 'Categories for portfolio', 'workreap_core'),
                'new_item_name' 		=> _x('New category Name', 'Categories for portfolio', 'workreap_core'),
                'menu_name' 			=> _x( 'Categories', 'Categories for portfolio', 'workreap_core' ),
            );
			
            $cat_args = array(
                'hierarchical' 		=> true,
                'labels' 			=> $cat_labels,
                'show_ui' 			=> true,
                'show_admin_column' => false,
                'query_var' 		=> true,
                'rewrite' 			=> array('slug' => 'portfolio_category'),
            );
			
			register_taxonomy('portfolio_categories', array('wt_portfolio'), $cat_args);
			
			//Regirster Tags Taxonomy
            $tag_labels = array(
                'name' 					=> esc_html__('Tags', 'workreap_core'),
                'singular_name' 		=> esc_html__('Tag','workreap_core'),
                'search_items'			=> esc_html__('Search Tag', 'workreap_core'),
                'all_items' 			=> esc_html__('All Tag', 'workreap_core'),
                'parent_item' 			=> esc_html__('Parent Tag', 'workreap_core'),
                'parent_item_colon' 	=> esc_html__('Parent Tag:', 'workreap_core'),
                'edit_item' 			=> esc_html__('Edit Tag', 'workreap_core'),
                'update_item' 			=> esc_html__('Update Tag', 'workreap_core'),
                'add_new_item' 			=> esc_html__('Add New Tag', 'workreap_core'),
                'new_item_name' 		=> esc_html__('New Tag Name', 'workreap_core'),
                'menu_name' 			=> esc_html__('Tags', 'workreap_core'),
            );
			
            $tag_args = array(
                'hierarchical' 		=> false,
                'labels' 			=> $tag_labels,
                'show_ui' 			=> true,
                'show_admin_column' => false,
                'query_var' 		=> true,
                'rewrite' 			=> array('slug' => 'portfolio_tag'),
            );
			
            register_taxonomy('portfolio_tags', array('wt_portfolio'), $tag_args);

        }

    }
	
	new Workreap_Portfolio();
}


