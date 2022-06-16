<?php
/**
 * Elementor Page builder config
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die('No kiddies please!');
}

if( !class_exists( 'Workreap_Elementor' ) ) {

	final class Workreap_Elementor{
		private static $_instance = null;
		
		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      string    workreap core
		 */
        public function __construct() {
            add_action( 'elementor/elements/categories_registered', array( &$this, 'workreap_init_elementor_widgets' ) );
            add_action( 'init', array( &$this, 'elementor_shortcodes' ),  20 );
        }
		
	
		/**
		 * class init
         * @since 1.1.0
         * @static
         * @var      string    workreap core
         */
        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
		
		/**
		 * Add category
		 * @since    1.0.0
		 * @access   static
		 * @var      string    workreap core
		 */
        public function workreap_init_elementor_widgets( $elements_manager ) {
            $elements_manager->add_category(
                'workreap-elements',
                [
                    'title' => esc_html__( 'Workreap Elements', 'workreap_core' ),
                    'icon' => 'fa fa-plug',
                ]
            );
        }

        /**
		 * Add widgets
		 * @since    1.0.0
		 * @access   static
		 * @var      string    workreap core
		 */
        public function elementor_shortcodes() {
			$dir = WorkreapGlobalSettings::get_plugin_path();
			$scan_shortcodes = glob("$dir/elementor/shortcodes/*");
			foreach ($scan_shortcodes as $filename) {
				$file = pathinfo($filename);
				if( !empty( $file['filename'] ) ){
					@include workreap_template_exsits( '/elementor/shortcodes/'.$file['filename'] );
				} 
			}

			$theme_dir				= get_stylesheet_directory();
			$theme_dir_shortcodes 	= glob("$theme_dir/extend/elementor/shortcodes/new/*"); 
			foreach ($theme_dir_shortcodes as $filename) { 
				if( !empty( $filename ) ){
					@include $filename;
				} 
			}
        }
		 
	}
}

//Init class
if ( did_action( 'elementor/loaded' ) ) {
    Workreap_Elementor::instance();
}