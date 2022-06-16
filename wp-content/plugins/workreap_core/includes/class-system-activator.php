<?php

/**
 * Fired during plugin activation
 *
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Elevator
 * @subpackage Workreap/includes
 * @author     Amentotech <theamentotech@gmail.com>
 */
class Workreap_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
       self::create_pages();
	   self::workreap_save_settings();
	}
	
	/**
	 * @init            save default settings
	 * @package         Amentotech
	 * @subpackage      workreap_core/admin/partials
	 * @since           1.0
	 * @desc            create page when plugin get activate
	 */
    public static function workreap_save_settings() {
		if (!get_option('workreap_theme_settings')) {
			$settings			= array();
			update_option('workreap_theme_settings' , $settings);
		}
    }
	
	/**
	 * @init            create pages
	 * @package         Amentotech
	 * @subpackage      workreap_core/admin/partials
	 * @since           1.0
	 * @desc            create page when plugin get activate
	 */
    public static function create_pages() {
		$pages =	array(
						'authentication' => array(
							'name'    => esc_html__( 'Authentication','workreap_core' ),
							'title'   => esc_html__( 'Authentication','workreap_core' ),
							'content' => '[' . 'workreap_authentication'. ' login_title="Login Now" register_title="Register As"]'
						),
					) ;

        foreach ( $pages as $key => $page ) {
            //self::workreap_create_page( esc_sql( $page['name'] ), $page['title'], $page['content'] );
        }

    }

	/**
	 * @init            create pages
	 * @package         Amentotech
	 * @subpackage      workreap_core/admin/partials
	 * @since           1.0
	 * @desc            create page when plugin get activate
	 */
	public static function workreap_create_page( $slug='', $page_title = '', $page_content = '') {
		global $wpdb;
		
		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode)
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			// Search for an existing page with the specified page slug
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
		}
		
		if ( $valid_page_found ) {
			return $valid_page_found;
		}
		
		// Search for a matching valid trashed page
		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode)
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			// Search for an existing page with the specified page slug
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
		}
	
		if ( $trashed_page_found ) {
			$page_id   = $trashed_page_found;
			$page_data = array(
				'ID'             => $page_id,
				'post_status'    => 'publish',
			);
			wp_update_post( $page_data );
		} else {
			$page_data = array(
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'post_author'    => 1,
				'post_name'      => $slug,
				'post_title'     => $page_title,
				'post_content'   => $page_content,
				'comment_status' => 'closed'
			);
			$page_id = wp_insert_post( $page_data );
		}
	
		return $page_id;
	}

}
