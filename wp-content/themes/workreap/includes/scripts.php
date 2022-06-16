<?php

/**
 *
 * General Theme Functions
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
if (!function_exists('workreap_scripts')) {

    function workreap_scripts() {
        $theme_version = wp_get_theme('workreap');
        $google_key = '';
		$gosocial_connect	= '';
		$facebook_connect	= '';

        if (function_exists('fw_get_db_settings_option')) {
            $google_key = fw_get_db_settings_option('google_key');
			$dir_chat 	= fw_get_db_settings_option('chat');
			$gosocial_connect	= fw_get_db_settings_option('enable_google_connect');
			$facebook_connect 	= fw_get_db_settings_option('enable_facebook_connect');
			$enable_pusher		= fw_get_db_settings_option('enable_pusher');
			$instance_id		= fw_get_db_settings_option('pusher_instance_id');
			$secret_key			= fw_get_db_settings_option('pusher_secret_key');
			$skills_typehead	= fw_get_db_settings_option('skills_typehead');
        }
		
        $protocol = is_ssl() ? 'https' : 'http';
        wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), $theme_version->get('Version'));

		wp_register_style('workreap-min', get_template_directory_uri() . '/css/main.css', array(), $theme_version->get('Version'));
		wp_register_style('workreap-style', get_template_directory_uri() . '/style.css', array(), $theme_version->get('Version'));
        wp_register_style('datetimepicker', get_template_directory_uri() . '/css/datetimepicker.css', array(), $theme_version->get('Version'));
		wp_register_style('basictable', get_template_directory_uri() . '/css/basictable.css', array(), $theme_version->get('Version'));
		wp_register_style('workreap-dashboard', get_template_directory_uri() . '/css/dashboard.css', array(), $theme_version->get('Version'));
        wp_register_style('workreap-transitions', get_template_directory_uri() . '/css/transitions.css', array(), $theme_version->get('Version'));
        wp_register_style('workreap-typo', get_template_directory_uri() . '/css/typo.css', array(), $theme_version->get('Version'));
        wp_register_style('workreap-responsive', get_template_directory_uri() . '/css/responsive.css', array(), $theme_version->get('Version')); 
        wp_register_style('workreap-dbresponsive', get_template_directory_uri() . '/css/dbresponsive.css', array(), $theme_version->get('Version'));
		wp_register_style('emojionearea', get_template_directory_uri() . '/css/emoji/emojionearea.min.css', array(), $theme_version->get('Version'));
		wp_register_style('venobox', get_template_directory_uri() . '/css/venobox.min.css', array(), $theme_version->get('Version'));      
		wp_register_style('splide', get_template_directory_uri() . '/css/splide.min.css', array(), $theme_version->get('Version'));      
        
		// typeahead js files
		wp_register_script('bloodhound.min', get_template_directory_uri() . '/js/bloodhound.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('typeahead.bundle.min', get_template_directory_uri() . '/js/typeahead.bundle.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('typeahead.jquery.min', get_template_directory_uri() . '/js/jq-typeahead.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('typeahead_js', get_template_directory_uri() . '/js/typeahead_js.js', array('jquery'), $theme_version->get('Version'), true);
		
		$custom_css = workreap_add_dynamic_styles();

        wp_enqueue_style('bootstrap');
		if (is_page_template('directory/dashboard.php')) {  
			wp_enqueue_style('workreap-select2', get_template_directory_uri() . '/css/select2.min.css', array(), $theme_version->get('Version'));
		}
		
        wp_enqueue_style('workreap-min');
		wp_enqueue_style('splide');
        wp_enqueue_style('jquery-ui-css');
        wp_enqueue_style('workreap-transitions');
        wp_enqueue_style('workreap-style');       
        wp_enqueue_style('workreap-typo');
        wp_add_inline_style('workreap-typo', $custom_css);
		
		
		if(is_rtl()){
            wp_register_style('workreap-rtl', get_template_directory_uri() . '/css/rtl.css', array(), $theme_version->get('Version'));             
            wp_enqueue_style('workreap-responsive');
			wp_enqueue_style('workreap-rtl');
        } else {
            wp_enqueue_style('workreap-responsive');
        }
		
        //script
        wp_register_script('modernizr', get_template_directory_uri() . '/js/vendor/modernizr.min.js', array(), $theme_version->get('Version'), false);
        wp_register_script('bootstrap', get_template_directory_uri() . '/js/vendor/bootstrap.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('socket.io', get_template_directory_uri() . '/node_modules/socket.io-client/dist/socket.io.js', array(), $theme_version->get('Version'), true);
		wp_register_script('socket.iofu', get_template_directory_uri() . '/node_modules/socketio-file-upload/client.js', array(), $theme_version->get('Version'), true);

		wp_register_script('workreap-all', get_template_directory_uri() . '/js/main.js', array(), $theme_version->get('Version'), true);
		wp_register_script('jRate', get_template_directory_uri() . '/js/jRate.js', array(), $theme_version->get('Version'), true);
		wp_register_script('workreap-callbacks', get_template_directory_uri() . '/js/workreap_callbacks.js', array('jquery'), $theme_version->get('Version'), true);
        wp_register_script('workreap-user-dashboard', get_template_directory_uri() . '/js/user-dashboard.js', array(), $theme_version->get('Version'), true);
		wp_register_script('basictable', get_template_directory_uri() . '/js/jquery.basictable.min.js', array(), $theme_version->get('Version'), true);
        wp_register_script('tipso', get_template_directory_uri() . '/js/tipso.js', '', '', true);
		wp_register_script('moment', get_template_directory_uri() . '/js/moment.js', '', '', true);
		wp_register_script('datetimepicker', get_template_directory_uri() . '/js/datetimepicker.js', array(), $theme_version->get('Version'), true);
		wp_register_script('markerclusterer', get_template_directory_uri() . '/js/maps/markerclusterer.min.js', array(), $theme_version->get('Version'), true);
        wp_register_script('workreap-gmaps', get_template_directory_uri() . '/js/maps/gmaps.js', array(), $theme_version->get('Version'), true);		
        wp_register_script('oms', get_template_directory_uri() . '/js/maps/oms.min.js', array(), $theme_version->get('Version'), true);
        wp_register_script('workreap-infobox', get_template_directory_uri() . '/js/maps/infobox.js', array(), $theme_version->get('Version'), true);
        wp_register_script('workreap-maps', get_template_directory_uri() . '/js/workreap_maps.js', array(), $theme_version->get('Version'), true);
		wp_register_script('auto-complete', get_template_directory_uri() . '/js/jquery.auto-complete.js', array(), $theme_version->get('Version'), true);
        wp_register_script('gmap3', get_template_directory_uri() . '/js/gmap3.js', array('jquery-ui-autocomplete'), '', true);
		wp_register_script('linkify', get_template_directory_uri() . '/js/linkify/linkify.min.js', array(), '', true);
		wp_register_script('linkify-string', get_template_directory_uri() . '/js/linkify/linkify-string.min.js', array(), '', true);
		wp_register_script('sortable', get_template_directory_uri() . '/js/sortable.min.js', array(), '', true);
		wp_register_script('particles', get_template_directory_uri() . '/js/particles.min.js', array(), '', true);
		wp_register_script('emojionearea', get_template_directory_uri() . '/js/emoji/emojionearea.min.js', array(), '', true);
		wp_register_script('hoverdir', get_template_directory_uri() . '/js/jquery.hoverdir.js', array(), '', true);
		wp_register_script('venobox', get_template_directory_uri() . '/js/venobox.min.js', array(), '', true);
		wp_register_script('splide', get_template_directory_uri() . '/js/splide.min.js', array(), '', true);

		wp_register_script('pusher', 'https://js.pusher.com/beams/1.0/push-notifications-cdn.js', array('jquery'), '', false);
		wp_register_script('pusher-notify', get_template_directory_uri() . '/js/pusher-notify.js', array( 'jquery' ), '', true);
		
		//Captcha settings
		if(function_exists('fw_get_db_settings_option')){
			$language_code    = fw_get_db_settings_option('language_code');
			$captcha_settings = fw_get_db_settings_option('captcha_settings');
			$language_code	  = empty( $language_code ) ? 'en' : $language_code;
		} else {
			$language_code    = 'en';
			$captcha_settings = '';
		}
		
		if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {
			wp_register_script('recaptcha', 'https://www.google.com/recaptcha/api.js?onload=workreapCaptchaCallback&render=explicit&hl='.$language_code, array(), $theme_version->get( 'Version' ), true);
		}
		
        if (!empty($google_key)) {
            wp_register_script('workreap-googleapis', $protocol . '://maps.googleapis.com/maps/api/js?key=' . trim($google_key) . '&libraries=places', '', '', true);
        } else {
            wp_register_script('workreap-googleapis', $protocol . '://maps.googleapis.com/maps/api/js?sensor=false&libraries=places', '', '', true);
        }
		
		if( !empty( $gosocial_connect ) && $gosocial_connect === 'enable' ){
			wp_register_script('workreap-gconnects', 'https://apis.google.com/js/api:client.js', '', '', false);	
			wp_register_script('workreap-sc-gconnect', get_template_directory_uri() . '/js/gconnect.js', '', '', false);
		}
		
		if( !empty( $facebook_connect ) && $facebook_connect === 'enable' ){	
			wp_register_script('workreap-fbconnect', get_template_directory_uri() . '/js/fbconnect.js', '', '', false);
		}
		
        wp_enqueue_script('modernizr');
		wp_enqueue_script('bootstrap');
		
		if ( is_singular('freelancers') || is_singular('micro-services') ) {
			wp_enqueue_script('linkify');
			wp_enqueue_script('linkify-string');
			wp_enqueue_style('emojionearea');
			wp_enqueue_script('emojionearea');
		} 
		
		wp_enqueue_script('jquery-ui');
	    wp_enqueue_script('workreap-all');
		wp_enqueue_script('datetimepicker');
        wp_enqueue_script('workreap-callbacks');
        wp_enqueue_script('wp-util');  
		wp_enqueue_script('hoverdir');
		wp_enqueue_script('tipso');
		wp_enqueue_script('splide');		
		
		if( !empty( $gosocial_connect ) && $gosocial_connect === 'enable' ){
			if (!is_user_logged_in()) {
				wp_enqueue_script('workreap-gconnects');
				wp_enqueue_script('workreap-sc-gconnect');
				wp_add_inline_script('workreap-callbacks',"workreap_gconnect_app();",'after');
			}
		}
		
		if( !empty( $facebook_connect ) && $facebook_connect === 'enable' ){
			if (!is_user_logged_in()) {
				wp_enqueue_script('workreap-fbconnect');
			}
		}
		
		if (is_page_template('directory/services-search.php') || is_tax('delivery') || is_tax('response_time') || is_tax('service_categories') ) { 
			wp_enqueue_script('auto-complete');
			wp_enqueue_script('jquery-ui-slider');
        }
		
        //Add proposal JS
        if (is_page_template('directory/project-proposal.php')) {  
            wp_enqueue_script('plupload');                            
        }    
		
		if( is_singular( 'freelancers' ) ) {
			wp_enqueue_script('readmore');
			wp_enqueue_script('tipso');
			wp_enqueue_style('venobox');
			wp_enqueue_script('venobox');
		}
		
		//tipso init
		if( is_tax('skills') 
			|| is_tax('languages') 
			|| is_tax('locations') 
			|| is_tax('badge_cat') 
			|| is_page_template('directory/freelancer-search.php') 
			|| is_page_template('directory/project-proposal.php')  
			|| is_page_template('directory/project-search.php')
			|| is_page_template('directory/project-search.php')
			|| is_singular( 'employers' )
		) {
			wp_enqueue_script('tipso');
		}
		
		if (is_singular()) {
            $_post = get_post();
            if ($_post != null) {
                if ($_post && (preg_match('/wt_top_freelancer/', $_post->post_content) )) {
                   wp_enqueue_script('tipso');
                }
            }
        }
		
		wp_enqueue_script('plupload'); 
		
        //Dashboard JS/CSS
        if (is_page_template('directory/dashboard.php')) { 
			wp_enqueue_script('plupload'); 
			wp_enqueue_style('workreap-dashboard');
            wp_enqueue_script('workreap-googleapis');         
            wp_enqueue_script('gmap3');  
            wp_enqueue_script('workreap-maps');
			wp_enqueue_style('basictable');
			wp_enqueue_script('basictable'); 
			wp_enqueue_script('tipso');
			wp_enqueue_script('moment');
			wp_enqueue_style('workreap-dbresponsive');
            wp_enqueue_script('workreap-user-dashboard'); 
			wp_enqueue_script('sortable');

			wp_enqueue_script('workreap-select2', get_template_directory_uri() . '/js/select2.min.js', array(), $theme_version->get('Version'), true);
        }
		
		
		//show map on detail page
		if (is_singular('projects') || is_singular('micro-services')){
			wp_enqueue_script('workreap-googleapis');         
			wp_enqueue_script('gmap3');
			wp_enqueue_script('workreap-gmaps');
		}

		if ( is_singular('micro-services') || is_singular('wt_portfolio') ){
			wp_enqueue_style('venobox');
			wp_enqueue_script('venobox');
		}
		
		//Dashboard chat 
        if ( is_page_template('directory/dashboard.php') && isset($_GET['ref']) && ( $_GET['ref'] === 'chat' || $_GET['ref'] === 'notification' ) ) {
			wp_enqueue_style('emojionearea');
			wp_enqueue_script('emojionearea');
        }
		
		if (is_user_logged_in()) {
			wp_enqueue_script('linkify');
			wp_enqueue_script('linkify-string');
        }

		//search jobs with maps
		if ( is_page_template('directory/project-search.php') || is_tax('project_cat') ) {
			wp_enqueue_script('auto-complete');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('workreap-googleapis');
			wp_enqueue_script('gmap3');  
			wp_enqueue_script('workreap-maps');
			wp_enqueue_script('markerclusterer');
            wp_enqueue_script('workreap-infobox');
			wp_enqueue_script('oms');
            wp_enqueue_script('workreap-gmaps');
		}

		//Typeahead js load
		if( !empty( $skills_typehead ) && $skills_typehead === 'yes' 
		   && ( is_page_template('directory/project-search.php') 
			   || is_page_template('directory/freelancer-search.php') 
			   || is_tax('project_cat')
			  )
			  
		) {
			wp_enqueue_script('bloodhound.min');
			wp_enqueue_script('typeahead.bundle.min');
			wp_enqueue_script('typeahead.jquery.min');
			wp_enqueue_script('typeahead_js');
		}

		if (is_page_template('directory/dashboard.php')) {
            wp_enqueue_script('jRate');
        }

        wp_localize_script('workreap-callbacks', 'scripts_vars', array(
			'is_admin'			=> 'no',
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        if (function_exists('fw_get_framework_directory_uri')):
            if (!is_admin()) {
                wp_enqueue_script('fw-form-helpers', fw_get_framework_directory_uri('/static/js/fw-form-helpers.js'));
            }
        endif;

		if (is_user_logged_in()) {
			if( !empty($instance_id) && !empty($secret_key) && $enable_pusher === 'yes'){
				wp_enqueue_script('pusher');
				wp_enqueue_script('pusher-notify');
			}
		}
    }

    add_action('wp_enqueue_scripts', 'workreap_scripts', 88);
}


/**
 * @Enqueue before render elementor
 * @return{}
 */
if (!function_exists('workreap_before_render_elementor_enqueue')) {
	
add_action( 'elementor/widget/render_content','workreap_before_render_elementor_enqueue',10, 2 ); 
   function workreap_before_render_elementor_enqueue( $content, $widget ) {
	   if( $widget->get_name() === 'wt_element_slider_v5' ){
		   wp_enqueue_script('particles');
		   wp_enqueue_script('jquery-ui-slider');
	   }

	   if( $widget->get_name() === 'wt_element_awards' 
		  || $widget->get_name() === 'wt_element_slider_v4'
		  || $widget->get_name() === 'wt_element_slider_v1'
		  || $widget->get_name() === 'wt_element_search_v2'
		  || $widget->get_name() === 'wt_element_search'
		  || $widget->get_name() === 'wt_element_how_works_v4'
		  || $widget->get_name() === 'wt_element_welcome'
		){
			wp_enqueue_style('venobox');
			wp_enqueue_script('venobox');
		}
	   
	   if( $widget->get_name() === 'wt_element_search_v2' ){
		   wp_enqueue_script('particles');
	   }

	   return $content;
   }
}


/**
 * @Enqueue admin scripts and styles.
 * @return{}
 */
if (!function_exists('workreap_admin_enqueue')) {

    function workreap_admin_enqueue($hook) {
        global $post;
        $protolcol 		= is_ssl() ? "https" : "http";
		$nonce	  		= wp_create_nonce('ajax_nonce');
        $theme_version  = wp_get_theme('workreap');
		wp_enqueue_media();

		//Styles
		$colorpicker_l10n = array(
			'clear' 		=> esc_html__( 'Clear', 'workreap' ),
			'defaultString' => esc_html__( 'Default', 'workreap' ),
			'pick' 			=> esc_html__( 'Select Color', 'workreap' ),
			'current' 		=> esc_html__( 'Current Color', 'workreap' ),
		);
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
		
        //Styles
		if (isset($hook) && $hook == 'post.php' || $hook === 'freelancers_page_workreap_settings') {
			if( ( isset($post->post_type)  && ( $post->post_type === 'employers' || $post->post_type === 'freelancers' ) ) || (!empty($_GET['page']) && !empty($_GET['tab']) && $_GET['tab'] === 'chat')  ) {
				
				wp_enqueue_style('scrollbar', get_template_directory_uri() . '/css/scrollbar.css', array(), $theme_version->get('Version')); 
				wp_enqueue_style('workreap-dashboard', get_template_directory_uri() . '/css/chat.css', array(), $theme_version->get('Version'));
				wp_enqueue_style('emojionearea', get_template_directory_uri() . '/css/emoji/emojionearea.min.css', array(), $theme_version->get('Version')); 
				wp_enqueue_script('scrollbar', get_template_directory_uri() . '/js/scrollbar.min.js', array(), $theme_version->get('Version'), true);
				wp_enqueue_script('linkify', get_template_directory_uri() . '/js/linkify/linkify.min.js', array('jquery'), '', true);
				wp_enqueue_script('linkify-string', get_template_directory_uri() . '/js/linkify/linkify-string.min.js', array('jquery'), '', true);
				wp_enqueue_script('emojionearea', get_template_directory_uri() . '/js/emoji/emojionearea.min.js', array(), '', true);
				wp_enqueue_script('workreap_chat_module');
			}
		}
	
		wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), $theme_version->get('Version'));
        wp_enqueue_style('workreap-admin-style', get_template_directory_uri() . '/admin/css/admin-style.css', array(), $theme_version->get('Version'));       
		wp_enqueue_script('workreap-admin-functions', get_template_directory_uri() . '/admin/js/admin_functions.js', array('jquery'), $theme_version->get('Version'), false);
		
		wp_enqueue_script('jquery-confirm.min', get_template_directory_uri() . '/js/jquery-confirm.min.js', array('jquery'), $theme_version->get('Version'), false);
		wp_enqueue_style('jquery-confirm.min', get_template_directory_uri() . '/css/jquery-confirm.min.css', array(), $theme_version->get('Version'));
		
		$backend_css = '';
		if (function_exists('fw_get_db_settings_option')) { 
			$backend_css = fw_get_db_settings_option('backend_css');
			wp_add_inline_style('workreap-admin-style', $backend_css);
		}

        $is_author_edit = '';
        if (isset($hook) && $hook == 'user-edit.php') {
            $is_author_edit = 'yes';
        }
		
		$dir_spinner 	= get_template_directory_uri() . '/images/spinner.gif';
		$chatloader 	= get_template_directory_uri() . '/images/chatloader.gif';
		$nonce	  		= wp_create_nonce('ajax_nonce');
		
        wp_localize_script('workreap-admin-functions', 'localize_vars', array(
            'yes' 				=> esc_html__('Yes', 'workreap'),
			'close' 				=> esc_html__('Close', 'workreap'),
            'no' 				=> esc_html__('No', 'workreap'),
			'accept' 			=> esc_html__('Accept', 'workreap'),
			'reject' 			=> esc_html__('Reject', 'workreap'),
			'import' 			=> esc_html__('Import Users', 'workreap'),
			'reason' 			=> esc_html__('Please add reason why you want to reject user uploaded documents?', 'workreap'),
			'reject_reason_text'=> esc_html__('Please add reason why you want to reject?', 'workreap'),
			'download' 			=> esc_html__('Identity verification', 'workreap'),
			'spinner'   		=> '<img class="sp-spin" src="'.esc_url($dir_spinner).'">',
            'import_message' 	=> esc_html__('Are you sure, you want to import users?', 'workreap'),
            'is_author_edit'	=> $is_author_edit,
			'is_admin'			=> 'yes',
			'ajaxurl' 			=> admin_url('admin-ajax.php'),
			'approve_account'	=> esc_html__('Approve Account', 'workreap'),
			'approve_account_message'	=> esc_html__('Do you want to approve this account? An email will be sent to this user.', 'workreap'),
			'reject_account'	=> esc_html__('Reject Account', 'workreap'),
			'reject_account_message'	=> esc_html__('Do you want to reject this account? After reject, this account will no longer visible in the search listing', 'workreap'),
			
			'approve_project'			=> esc_html__('Approve project', 'workreap'),
			'approve_project_message'	=> esc_html__('Do you want to approve this project? An email will be sent to this user.', 'workreap'),
			
			'approve_service'			=> esc_html__('Approve service', 'workreap'),
			'approve_service_message'	=> esc_html__('Do you want to approve this service? An email will be sent to this user.', 'workreap'),
			'add_message'				=> esc_html__('Please add message', 'workreap'),
			
			'approve_identity'			=> esc_html__('Identity Verify', 'workreap'),
			'approve_identity_message'	=> esc_html__('Are you sure, you want to verify identity of this user?', 'workreap'),
			
			'reject_identity'			=> esc_html__('Identity Reject', 'workreap'),
			'reject_identity_message'	=> esc_html__('Are you sure, you want to reject identity of this user?', 'workreap'),
			'reject_job'				=> esc_html__('Job rejection', 'workreap'),
			'reject_service_message'	=> esc_html__('Are you sure, you want to reject this service?', 'workreap'),
			'reject_service'			=> esc_html__('Service rejection', 'workreap'),
			'reject_job_message'		=> esc_html__('Are you sure, you want to reject this job?', 'workreap'),
			'withdraw_status'			=> esc_html__('Update status', 'workreap'),
			'withdraw_status_message'	=> esc_html__('Are you sure, you want to update the withdraw status', 'workreap'),
			
			'update_freelaners'			=> esc_html__('Update freelancers', 'workreap'),
			'update_freelaners_message'	=> esc_html__('Are you sure, you want to freelancers profile health?', 'workreap'),
			'ajax_nonce'		=> $nonce,
        ));
		
		 wp_localize_script('workreap_chat_module', 'scripts_vars', array(
			'spinner'   		=> '<img class="sp-spin" src="'.esc_url($dir_spinner).'">',
			'is_admin'			=> 'yes',
			'chat_settings'		=> 'no',
			'chat_page'			=> 'no',
			'chat_host'			=> 'no',
			'chat_port'			=> 'no',
			'chat_port'			=> 'no',
			'ajax_nonce'		=> $nonce,
			'chatloader'   		=> '<img class="sp-chatspin" src="'.esc_url($chatloader).'">',
			'ajaxurl' 			=> admin_url('admin-ajax.php')
        ));
    }

    add_action('admin_enqueue_scripts', 'workreap_admin_enqueue', 10, 1);
}

/**
 * @Theme Editor/guttenberg Style
 * 
 */
if (!function_exists('workreap_add_editor_styles')) {

    function workreap_add_editor_styles() {
		$protocol = is_ssl() ? 'https' : 'http';
        $theme_version = wp_get_theme('workreap');
		$editor_css  = '';
		
		if (function_exists('fw_get_db_settings_option')) {
            $color_base = fw_get_db_settings_option('color_settings');
        }
		
		if (isset($color_base['gadget']) && $color_base['gadget'] === 'custom') {
            if (!empty($color_base['custom']['primary_color'])) {
                $theme_color = $color_base['custom']['primary_color'];
                $theme_color = apply_filters('workreap_get_page_color', $theme_color);
				
				$editor_css  .= 'body.block-editor-page .editor-styles-wrapper a,
				body.block-editor-page .editor-styles-wrapper p a,
				body.block-editor-page .editor-styles-wrapper p a:hover,
				body.block-editor-page .editor-styles-wrapper a:hover,
				body.block-editor-page .editor-styles-wrapper a:focus,
				body.block-editor-page .editor-styles-wrapper a:active{color: '.$theme_color.';}';
				
				$editor_css  .= 'body.block-editor-page .editor-styles-wrapper blockquote:not(.blockquote-link),
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote.is-style-large,
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote:not(.is-large):not(.is-style-large),
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote.is-style-large,
								 body.block-editor-page .editor-styles-wrapper .wp-block-pullquote, 
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote, 
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote:not(.is-large):not(.is-style-large),
								 body.block-editor-page .wp-block-pullquote, 
								 body.block-editor-page .wp-block-quote, 
								 body.block-editor-page .wp-block-verse, 
								 body.block-editor-page .wp-block-quote:not(.is-large):not(.is-style-large){border-color:'.$theme_color.';}';
			}
		}
		
		$font_families	= array();
		$font_families[] = 'Montserrat:300,400,600,700';
		$font_families[] = 'Poppins:400,500,600,700';
		$font_families[] = 'Work+Sans:300,400';
		$font_families[] = 'Open+Sans:400,600,700';
		
		 $query_args = array (
			 'family' => implode('%7C' , $font_families) ,
			 'subset' => 'latin,latin-ext' ,
        );

        $theme_fonts = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');
		
		add_editor_style(esc_url_raw($theme_fonts));

		$editor_css .= "body.block-editor-page .editor-styles-wrapper{font:400 16px/26px 'Open Sans', Arial, Helvetica, sans-serif;}";
		
		$editor_css .= "body.block-editor-page .editor-styles-wrapper{color: #767676;}";
		$editor_css .= "body.block-editor-page editor-post-title__input, 
				body.block-editor-page .editor-post-title__block .editor-post-title__input,
				body.block-editor-page .editor-styles-wrapper h1, 
				body.block-editor-page .editor-styles-wrapper h2, 
				body.block-editor-page .editor-styles-wrapper h3, 
				body.block-editor-page .editor-styles-wrapper h4, 
				body.block-editor-page .editor-styles-wrapper h5, 
				body.block-editor-page .editor-styles-wrapper h6 {font-family: 'Poppins', Arial, Helvetica, sans-serif;}";
							   
		wp_enqueue_style( 'workreap-editor-style', get_template_directory_uri() . '/admin/css/workreap-editor-style.css', array(), $theme_version->get('Version'));
		wp_add_inline_style( 'workreap-editor-style', $editor_css );
		
    }

    add_action('enqueue_block_editor_assets', 'workreap_add_editor_styles');
}