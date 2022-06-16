<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Notifications')) {

    class Workreap_Notifications {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
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
                'name' 				=> esc_html__('Notifications', 'workreap_core'),
                'all_items' 		=> esc_html__('Notifications', 'workreap_core'),
                'singular_name' 	=> esc_html__('Notifications', 'workreap_core'),
                'add_new' 			=> esc_html__('Add notification', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New notification', 'workreap_core'),
                'edit' 				=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit notification', 'workreap_core'),
                'new_item' 			=> esc_html__('New notification', 'workreap_core'),
                'view' 				=> esc_html__('View notification', 'workreap_core'),
                'view_item' 		=> esc_html__('View notification', 'workreap_core'),
                'search_items' 		=> esc_html__('Search notification', 'workreap_core'),
                'not_found' 		=> esc_html__('No notification found', 'workreap_core'),
                'not_found_in_trash' 	=> esc_html__('No notification found in trash', 'workreap_core'),
                'parent' 				=> esc_html__('Parent Notifications', 'workreap_core'),
            );
			
            $args = array(
                'labels' 			=> $labels,
                'description' 		=> esc_html__('This is where you can add new Notifications ', 'workreap_core'),
                'public' 			=> false,
                'supports' 			=> array('title', 'editor'),
                'show_ui' 			=> true,
                'capability_type' 	=> 'post',
                'map_meta_cap' 		=> true,
                'publicly_queryable' 	=> false,
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'notification', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'exclude_from_search'   => true,
				'menu_icon'				=> 'dashicons-bell',
				'capabilities' 			=> array('create_posts' => false)
            );
            register_post_type('push_notifications', $args);
        }

    }

    new Workreap_Notifications();
}


