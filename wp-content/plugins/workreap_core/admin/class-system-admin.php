<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 * @author     Amentotech <theamentotech@gmail.com>
 */
class Workreap_Admin {

    public function __construct() {
        $this->plugin_name = WorkreapGlobalSettings::get_plugin_name();
        $this->version = WorkreapGlobalSettings::get_plugin_verion();
        $this->plugin_path = WorkreapGlobalSettings::get_plugin_path();
        $this->plugin_url = WorkreapGlobalSettings::get_plugin_url();
        $this->prepare_post_types();

        /**
		 * The class responsible for defining activate license functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-user-purchase-verify.php';
    }

    /**
     * Register the spost types for the admin area.
     *
     * @since    1.0.0
     */
    public function prepare_post_types() {
        $dir = $this->plugin_path;
        $scan_PostTypes = glob("$dir/admin/post-types/*");
        foreach ($scan_PostTypes as $filename) {
			$file = pathinfo($filename);
			if( !empty( $file['filename'] ) ){
				@include workreap_template_exsits( 'admin/post-types/'.$file['filename'] );
			} 
        }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Workreap_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Workreap_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('workreap_core_admin', $this->plugin_url . 'admin/css/system-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Workreap_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Workreap_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */        
        wp_enqueue_script('core_functions', $this->plugin_url . 'admin/js/functions.js', array('jquery'), $this->version, false);
		wp_register_script('workreap_chat_module', $this->plugin_url. '/public/js/workreap_chat_module.js', '', $this->version, false);
    }

}
