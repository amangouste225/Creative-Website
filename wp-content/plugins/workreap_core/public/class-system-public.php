<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Workreap
 * @subpackage Workreap/public
 * @author     Amentotech <theamentotech@gmail.com>
 */
class Workreap_Public {

    public function __construct() {

        $this->plugin_name = WorkreapGlobalSettings::get_plugin_name();
        $this->version = WorkreapGlobalSettings::get_plugin_verion();
        $this->plugin_path = WorkreapGlobalSettings::get_plugin_path();
        $this->plugin_url = WorkreapGlobalSettings::get_plugin_url();
    }

	/**
     * Print POPUP Wrapper
     *
     * @since    1.0.0
     */
    public function print_popup_wrapper() {
		ob_start();
		if ( 'freelancers' == get_post_type() || is_page_template('directory/portfolio-search.php') ) {
			?>
			<div class="modal fade wt-portfoliopopup" tabindex="-1" role="dialog" id="popupwrapper" data-backdrop="static">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="wt-modalcontentvtwo modal-content">
						<a href="#" onclick="event_preventDefault(event);" class="wt-closebtn close"><i class="lnr lnr-cross" data-dismiss="modal"></i></a>
						<div class="wt-portfolio-content-model"></div>
					</div>
				</div>
			</div>
			<?php
		}
		echo ob_get_clean();
    }
	
	/**
     * Print POPUP Wrapper
     *
     * @since    1.0.0
     */
    public function print_popup_login_register() {
		ob_start();
		if(!is_user_logged_in()){
			if (function_exists('fw_get_db_settings_option')) {
				$enable_login_register = fw_get_db_settings_option('enable_login_register');
			} else {
				$enable_login_register = '';
			}

			if(class_exists('Workreap_Prepare_Headers')){
				$header	= new Workreap_Prepare_Headers();
				if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){
					$header->workreap_login_model();
					$header->workreap_registration_model();
					wp_enqueue_script('recaptcha');
				} else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){
					$header->workreap_single_step_login_model();
					$header->workreap_single_step_registration_model();
					wp_enqueue_script('recaptcha');
				} else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'pages' ){
					wp_enqueue_script('recaptcha');
				}
			}
		}
		
		echo ob_get_clean();
    }
	
    /**
     * Register the stylesheets for the public-facing side of the site.
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
        //wp_enqueue_style('system-public', plugin_dir_url(__FILE__) . 'css/system-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
        wp_register_script('workreap_core', plugin_dir_url(__FILE__) . 'js/system-public.js', array('jquery'), $this->version, false);
		
		if ( function_exists('fw_get_db_post_option' )) {
			$dir_chat = fw_get_db_settings_option('chat');
		}
		
		$dependencies	= array('jquery');
		if( ( !empty( $dir_chat['gadget'] ) && $dir_chat['gadget'] === 'chat' ) || (is_page_template('directory/dashboard.php') && isset($_GET['mode']) && $_GET['mode'] === 'history' ) ){
			$dependencies	= array('jquery','socket.io','socket.iofu');
		}

		if ( is_page_template('directory/dashboard.php') && ( ( isset($_GET['ref']) && $_GET['ref'] === 'chat' ) || isset($_GET['mode']) && $_GET['mode'] === 'history' ) ) {
			wp_register_script('workreap_chat_module', plugin_dir_url(__FILE__) . 'js/workreap_chat_module.js', $dependencies, $this->version, false);
            wp_enqueue_script('workreap_chat_module');
        }
		
		if ( ( !empty( $dir_chat['gadget'] ) && $dir_chat['gadget'] === 'chat' ) && ( is_singular('freelancers') || is_singular('micro-services') ) ) {
			wp_register_script('workreap_chat_module', plugin_dir_url(__FILE__) . 'js/workreap_chat_module.js', $dependencies, $this->version, false);
			wp_enqueue_script('workreap_chat_module');
        }

		wp_enqueue_script('workreap_core');	 
		
    }
}
