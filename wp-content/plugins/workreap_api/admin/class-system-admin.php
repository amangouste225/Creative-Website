<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    AndroidApp
 * @subpackage AndroidApp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AndroidApp
 * @subpackage AndroidApp/admin
 * @author     Amento Tech <theamentotech@gmail.com>
 */
class AndroidApp_Admin {

    public function __construct() {
		//do something
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
         * defined in AndroidApp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The AndroidApp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        //wp_enqueue_style('system-styles', $this->plugin_url . 'admin/css/system-admin.css', array(), $this->version, 'all');
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
         * defined in AndroidApp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The AndroidApp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        //wp_enqueue_script('core_functions', $this->plugin_url . 'admin/js/functions.js', array('jquery','android_admin_functions'), $this->version, false);
    }

}
