<?php
/**
 *
 * Ajax request hooks
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfoliot
 * @since 1.0
 */


/** update freelancer metadata
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 *
 */
if (!function_exists('workreap_update_freelancers_metadata')) {

    function workreap_update_freelancers_metadata() {
        global $current_user;
        $json = array();     

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ( $do_check == false ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }
		
        $query_args = array(
			'posts_per_page' 	  => -1,
			'post_type' 	 	  => array( 'freelancers'),
			'post_status' 	 	  => array( 'publish' ),
			'ignore_sticky_posts' => 1
		);
		
		$users = get_posts($query_args);
		foreach( $users as $key => $user ){
			if (has_post_thumbnail($user->ID)) {
				update_post_meta($user->ID, '_have_avatar', 1); 
			}else{
				update_post_meta($user->ID, '_have_avatar', 0); 
			}
		}
		
		$json['type'] 	  = 'success';
		$json['message'] = esc_html__('All the freelancers has been updated', 'workreap');;
		wp_send_json($json);
    }

    add_action('wp_ajax_workreap_update_freelancers_metadata', 'workreap_update_freelancers_metadata');
}

/** update freelancer metadata
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 *
 */
if (!function_exists('workreap_list_skills_list')) {

    function workreap_list_skills_list() {
        global $current_user;
        $json = array();     
		if( taxonomy_exists('skills') ) {
			$skill = !empty( $_GET['skills']) ? $_GET['skills'] : array();
			$count = !empty($skill) && is_array($skill) ? count($skill) : 0;

			$active_class		= !empty($count) ? 'wt-displayfilter' : '';
			ob_start(); 
				wp_list_categories( array(
						'taxonomy' 			=> 'skills',
						'hide_empty' 		=> false,
						'current_category'	=> $skill,
						'style' 			=> '',
						'walker' 			=> new Workreap_Walker_Skills,
					)
				);
			echo ob_get_clean();   
		}
    }

    add_action('wp_ajax_workreap_list_skills_list', 'workreap_list_skills_list');
	add_action('wp_ajax_nopriv_workreap_list_skills_list', 'workreap_list_skills_list');
}

/* Download FAP attachmenst
 *
 * @throws error
 * @return 
 */
if (!function_exists('workreap_fap_download_attachment')) {
    function workreap_fap_download_attachment() {
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ( $do_check == false ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }
		
        $response = Workreap_file_permission::downloadFile($_POST['attachment_id']);
        wp_send_json($response);
    }

    add_action('wp_ajax_workreap_fap_download_attachment', 'workreap_fap_download_attachment');
    add_action('wp_ajax_nopriv_workreap_fap_download_attachment', 'workreap_fap_download_attachment');
}

/** update freelancer metadata
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_update_profile_health')) {

    function workreap_update_profile_health() {
        global $current_user;
        $json = array();     
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ( $do_check == false ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }
		
        $query_args = array(
			'posts_per_page' 	  => -1,
			'post_type' 	 	  => array( 'freelancers'),
			'post_status' 	 	  => array( 'publish' ),
			'ignore_sticky_posts' => 1,
		);
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$hide_profiles		= fw_get_db_settings_option('hide_profiles', $default_value = 'no');
		}

		$percentage	= !empty($hide_profiles['yes']['define_percentage']) ? $hide_profiles['yes']['define_percentage'] : 0;
		
		$users = get_posts($query_args);
		foreach( $users as $key => $user ){
			if (function_exists('fw_get_db_post_option')) {
				$tag_line		= fw_get_db_post_option($user->ID, 'tag_line', true);
				$experience 	= fw_get_db_post_option($user->ID, 'skills', true);
				$skills 		= fw_get_db_post_option($user->ID, 'skills', true);
			}
			
			if (has_post_thumbnail($user->ID)) {
				do_action('workreap_update_profile_strength','avatar',true,$user->ID);
			}else{
				do_action('workreap_update_profile_strength','avatar',false,$user->ID);
			}
			
			//Check experience
			if( !empty( $experience ) ){
				do_action('workreap_update_profile_strength','experience',true,$user->ID);
			}else{
				do_action('workreap_update_profile_strength','experience',false,$user->ID);
			}
			
			//Update tagline Profile health
			if(!empty($tag_line)){
				do_action('workreap_update_profile_strength','tagline',true,$user->ID);
			}else{
				do_action('workreap_update_profile_strength','tagline',false,$user->ID);
			}

			//Update tagline Profile health
			$content	= get_the_content('','',$user->ID);
			if(!empty($content)){
				do_action('workreap_update_profile_strength','description',true,$user->ID);
			}else{
				do_action('workreap_update_profile_strength','description',false,$user->ID);
			}
			
			//Update skills Profile health
			if(!empty($skills)){
				do_action('workreap_update_profile_strength','skills',true,$user->ID);
			}else{
				do_action('workreap_update_profile_strength','skills',false,$user->ID);
			}

			//Update identity verification Profile health
			$identity_verified	= get_post_meta($user->ID, 'identity_verified', true);
			if( !empty( $identity_verified ) ){
				do_action('workreap_update_profile_strength','identity_verification',true,$user->ID);
			}else{
				do_action('workreap_update_profile_strength','identity_verification',false,$user->ID);
			}
			
			//update profile health
			$get_profile_data	= get_post_meta($user->ID, 'profile_strength',true);
			$total_percentage	= !empty( $get_profile_data['data'] ) ? array_sum( $get_profile_data['data'] ) : 0;
			$total_percentage	= !empty( $total_percentage ) ? intval($total_percentage) : 0;
			update_post_meta($user->ID, '_profile_health_filter', $total_percentage); 
			
		}
		
		$json['type'] 	  = 'success';
		$json['message'] = esc_html__('All the freelancers has been updated', 'workreap');;
		wp_send_json($json);
    }

    add_action('wp_ajax_workreap_update_profile_health', 'workreap_update_profile_health');
	add_action('wp_ajax_nopriv_workreap_update_profile_health', 'workreap_update_profile_health');
}

/**
 * check dispute feeback
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_dispute_feedback')) {

    function workreap_get_dispute_feedback() {
        global $current_user;
        $json = array();  
		$user_input = !empty($_POST['dispute_id']) ? intval( $_POST['dispute_id'] ) : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($user_input);
		}

		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ( $do_check == false ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }

        if ( empty( $user_input ) ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('No tricks please!', 'workreap');
            wp_send_json($json);
        }
		
		if (function_exists('fw_get_db_post_option')) {
			$feedback 	= fw_get_db_post_option($user_input, 'feedback');
		}
		
		$user_input = !empty($feedback) ? $feedback : esc_html__('No feedback provided yet.', 'workreap');
		
		$json['type'] 	  = 'success';
		$json['feedback'] = $user_input;
		wp_send_json($json);
    }

    add_action('wp_ajax_workreap_get_dispute_feedback', 'workreap_get_dispute_feedback');
}

/**
 * Update verification request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_send_verification_request' ) ) {

	function workreap_send_verification_request() {
		global $current_user;
		$json 	 	= array();
		$post_id	= workreap_get_linked_profile_id($current_user->ID);
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($post_id);
		} //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ( $do_check == false ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }
		
		$files          		= !empty( $_POST['identity'] ) ? $_POST['identity'] : array();

		$required = array(
			'name'   			=> esc_html__('Name is required', 'workreap'),
			'contact_number'  	=> esc_html__('Contact number is required', 'workreap'),
			'verification_number'   => esc_html__('Verification number is required', 'workreap'),
			'address'   			=> esc_html__('Address is required', 'workreap'),
		);

		foreach ($required as $key => $value) {
			if( empty( $_POST['basics'][$key] ) ){
				$json['type'] = 'error';
				$json['message'] = $value;        
				wp_send_json($json);
			}
		}
		
		extract($_POST['basics']);
		$identity_array			= array();
		
		$identity_array['info']['name'] 				= !empty($name) ? esc_html($name) : '';
        $identity_array['info']['contact_number']  		= !empty($contact_number ) ? esc_html($contact_number) : '';
		$identity_array['info']['verification_number']  = !empty($verification_number ) ? esc_html($verification_number) : '';
		$identity_array['info']['address'] 				= !empty($address ) ? esc_html($address) : '';
		
		if ( empty( $files ) ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Please upload a document', 'workreap');
            wp_send_json($json);
        }

		if( !empty( $files ) ) {
			foreach ( $files as $key => $value ) {
				$identity_array[] = workreap_temp_upload_to_media($value, $post_id,true);	
			}                
		}

		update_post_meta($post_id,'verification_attachments',$identity_array);
		update_post_meta($post_id,'identity_verified',0);
		
		//Send an email to admin
		if (class_exists('Workreap_Email_helper')) {
            if (class_exists('WorkreapIdentityVerification')) {
                $email_helper = new WorkreapIdentityVerification();
				$username   	= workreap_get_username( $current_user->ID );
				
                $emailData = array();
                $emailData['user_name']  	= $username;
				$emailData['user_link']  	= admin_url('users.php').'?s='.$current_user->user_email;
				$emailData['user_email']  	= $current_user->user_email;
				
                $email_helper->send_verification_to_admin($emailData);
            }
        }  

		$json['type'] 		= 'success';
		$json['message'] 	= esc_html__('Successfully! submitted your request for verification', 'workreap');	
		
		wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_send_verification_request', 'workreap_send_verification_request' );
	add_action( 'wp_ajax_nopriv_workreap_send_verification_request', 'workreap_send_verification_request' );
}

/**
 * Update verification request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_cancel_verification_request' ) ) {

	function workreap_cancel_verification_request() {
		global $current_user;
		$json 	 	= array();
		$post_id	= workreap_get_linked_profile_id($current_user->ID);
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($post_id);
		} //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ( $do_check == false ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }
		
		update_post_meta($post_id,'verification_attachments','');
		update_post_meta($post_id,'identity_verified',0);

		$json['type'] 		= 'success';
		$json['message'] 	= esc_html__('Successfully! deleted your verification request', 'workreap');	
		
		wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_cancel_verification_request', 'workreap_cancel_verification_request' );
	add_action( 'wp_ajax_nopriv_workreap_cancel_verification_request', 'workreap_cancel_verification_request' );
}

/**
 * Create dispute
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_submit_dispute')) {

    function workreap_submit_dispute() {
        global $wpdb,$current_user,$post;
        $json = array();     
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$get_user_type	= apply_filters('workreap_get_user_type', $current_user->ID );
		$fields	= array(
			'project' 		=> esc_html__('No project/service is selected','workreap'),
			'reason' 		=> esc_html__('Please select the reason','workreap'),
			'description' 	=> esc_html__('Please add dispute description','workreap'),
		);

		foreach( $fields as $key => $item ){
			if( empty( $_POST['dispute'][$key] ) ){
				$json['type'] 	 = "error";
				$json['message'] = $item;
				wp_send_json( $json );
			}
		}
		
		//Create dispute
		$dispute_options= !empty($get_user_type) && $get_user_type == 'freelancer' ? 'dispute_options_freelancer' : 'dispute_options';
        $username   	= workreap_get_username( $current_user->ID );
		$linked_profile = workreap_get_linked_profile_id($current_user->ID);
        $project      	= sanitize_text_field( $_POST['dispute']['project'] );
		$title      	= sanitize_text_field( $_POST['dispute']['reason'] );
		$description    = !empty( $_POST['dispute']['description'] ) ? ( $_POST['dispute']['description'] ) : '';
		$list			= workreap_project_ratings($dispute_options);
		$dispute_title  = !empty( $list[$title] ) ? $list[$title] : rand(1,9999);
		$get_post_type	= get_post_type($project);
		
		
		$dispute_args = array('posts_per_page' => -1,
			'post_type' 		=> array( 'disputes'),
			'orderby' 			=> 'ID',
			'order' 			=> 'DESC',
			'post_status' 		=> array('pending','publish'),
			'author' 			=> $current_user->ID,
			'suppress_filters'  => false,
			'meta_query'		=> array(
				'relation' 		=> 'AND',
				 array( 'key' 			=> '_dispute_project',
					   'value' 			=> $project,
					   'compare' 		=> '='
					 )
			)
		);
		
		$dispute_is = get_posts($dispute_args); 
		if( !empty( $dispute_is ) ){
			$json['type'] = "error";
			$json['message'] = esc_html__("You have already submitted the dispute against this project.", 'workreap');
			wp_send_json( $json );
		}
		
		$project_id		= get_post_meta($project, '_project_id', true);
		$dispute_against	= '';
		
		if(!empty($get_user_type) && $get_user_type === 'freelancer'){
			if(!empty($get_post_type) && ( $get_post_type === 'services-orders' ) ){
				$postdata 			= get_post( $project );
     			$project_author		= $postdata->post_author;
				$dispute_against   	= workreap_get_username( $project_author );
				
				$author_data            = get_userdata( $project_author );                    
				$dispute_email_to       = $author_data->data->user_email;
				
				$service_id				= get_post_meta($postdata->ID, '_service_id', true);
				$dispute_project_title	= get_the_title($service_id);
				$dispute_project_link	= get_the_permalink($service_id);
				
			}else if(!empty($get_post_type) && ( $get_post_type === 'proposals' ) ){
				$project_id			= get_post_meta($project, '_project_id', true);
				$postdata 			= get_post( $project_id );
     			$project_author		= $postdata->post_author;
				$dispute_against   	= workreap_get_username( $project_author );
				
				$author_data            = get_userdata( $project_author );                    
				$dispute_email_to       = $author_data->data->user_email;
				$dispute_project_title	= $postdata->post_title;
				$dispute_project_link	= get_the_permalink($postdata->ID);
				
			}
		}else if(!empty($get_user_type) && $get_user_type === 'employer'){
			if(!empty($get_post_type) && ( $get_post_type === 'services-orders' ) ){
				$service_author		= get_post_meta($project, '_service_author', true);
				$dispute_against   	= workreap_get_username( $service_author );
				$author_data            = get_userdata( $service_author );                    
				$dispute_email_to  = $author_data->data->user_email;
				
				$service_id				= get_post_meta($project, '_service_id', true);
				$dispute_project_title	= get_the_title($service_id);
				$dispute_project_link	= get_the_permalink($service_id);
				
			}else if(!empty($get_post_type) && ( $get_post_type === 'proposals' ) ){
				$project_id			= get_post_meta($project, '_project_id', true);
				$postdata 			= get_post( $project );
     			$project_author		= $postdata->post_author;
				$dispute_against   	= workreap_get_username( $project_author );
				
				$author_data            = get_userdata( $project_author );                    
				$dispute_email_to       = $author_data->data->user_email;
				$dispute_project_title	= $postdata->post_title;
				$dispute_project_link	= get_the_permalink($postdata->ID);
			}
		}

        $dispute_post  = array(
            'post_title'    => wp_strip_all_tags( $dispute_title ), //proposal title
            'post_status'   => 'pending',
            'post_content'  => $description,
            'post_author'   => $current_user->ID,
            'post_type'     => 'disputes',
        );

        $dispute_id    		= wp_insert_post( $dispute_post );
		update_post_meta( $dispute_id, '_send_by', $current_user->ID);
		update_post_meta( $dispute_id, '_dispute_key', $title);
		update_post_meta( $dispute_id, '_dispute_project', $project); //propsal ID
		update_post_meta( $dispute_id, '_project_id', $project_id);
		update_post_meta( $project, 'dispute', 'yes');

		$post_type_object = get_post_type_object( 'proposals' );
		$link = !empty( $post_type_object->_edit_link ) ? admin_url( sprintf( $post_type_object->_edit_link . '&action=edit', $project ) ) : '';

        //Send email to user
        if (class_exists('Workreap_Email_helper')) {
            if (class_exists('WorkreapSendDispute')) {
                $email_helper = new WorkreapSendDispute();
                $emailData = array();
                $emailData['project_link']  	= $link;
				$emailData['project_title']  	= get_the_title($project);
				$emailData['user_name']  		= $username;
                $emailData['user_link']     	= get_the_permalink($linked_profile);
                $emailData['message']      		= $description;
				$emailData['dispute_subject']   = $dispute_title;
				$emailData['dispute_author']    = $username;
				$emailData['dispute_against']   = $dispute_against;
				$emailData['dispute_email_to']  = $dispute_email_to;

                $email_helper->send($emailData);
				
				$emailData['project_link']  	= !empty($dispute_project_link) ?  $dispute_project_link : get_the_permalink($project);
				$emailData['project_title']  	= !empty($dispute_project_title) ?  $dispute_project_title : get_the_title($project);
				$email_helper->dispute_notify($emailData);
            }
        }     
		
		//Push notification
		$push	= array();
		
		$push['sender_id']			= $current_user->ID;
		$push['dispute_against']	= !empty($author_data->data->ID) ? $author_data->data->ID : 0;
		$push['project_id']			= !empty($project_id) ? $project_id : 0;
		$push['message']			= $description;
		
		$push['%dispute_author%']	= workreap_get_username( $current_user->ID );
		$push['%dispute_against%']	= !empty($author_data->data->ID) ? workreap_get_username( $author_data->data->ID ) : 0;
		$push['%message%']			= $description;
		$push['%replace_message%']	= $description;
		$push['%project_link%']		= $dispute_project_link;
		$push['%project_title%']	= $dispute_project_title;
		$push['type']				= 'dispute';

		do_action('workreap_user_push_notify',array($push['dispute_against']),'','pusher_dispute_user_content',$push);
		

        $json['type'] = "success";
        $json['message'] = esc_html__("We have received your dispute, soon we will get back to you.", 'workreap');
        wp_send_json( $json );
    }

    add_action('wp_ajax_workreap_submit_dispute', 'workreap_submit_dispute');
}

/**
 * Get Lost Password
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_ajax_lp')) {

    function workreap_ajax_lp() {
        global $wpdb;
        $json = array();     
        $user_input = !empty($_POST['email']) ? sanitize_email( $_POST['email'] ) : '';
		
        $captcha_settings = '';
		if (function_exists('fw_get_db_settings_option')) { 
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
		}
		
		//recaptcha check
        if (isset($captcha_settings) && $captcha_settings === 'enable') {
            if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                $docReResult = workreap_get_recaptcha_response($_POST['g-recaptcha-response']);

                if ($docReResult == 1) {
                    $workdone = 1;
                } else if ($docReResult == 2) {
					$json['type'] = 'error';
                    $json['message'] = esc_html__('An error occurred, please try again later.', 'workreap');
                    wp_send_json($json);
                } else {
					$json['type'] = 'error';
                    $json['message'] = esc_html__('Wrong reCaptcha. Please verify first.', 'workreap');
                    wp_send_json($json);
                }
            } else {
                wp_send_json(array('type' => 'error', 'message' => esc_html__('Please enter reCaptcha!', 'workreap')));
            }
        }

        if (empty($user_input)) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Please add email address.', 'workreap');
            wp_send_json( $json );
        } else if (!is_email($user_input)) {
            $json['type'] = "error";
            $json['message'] = esc_html__("Please add a valid email address.", 'workreap');
            wp_send_json( $json );
        }      

        $user_data = get_user_by('email', $user_input);
        if (empty($user_data) ) {
            $json['type'] = "error";
            $json['message'] = esc_html__("The email address does not exist", 'workreap');
            wp_send_json( $json );
        }

        $user_id    = $user_data->ID;
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $username   = workreap_get_username( $user_id );

        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

        if (empty($key)) {
            //generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }else{
			//generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
		}

        $protocol = is_ssl() ? 'https' : 'http';
        $reset_link = esc_url(add_query_arg(array('action' => 'reset_pwd', 'key' => $key, 'login' => $user_login), home_url('/', $protocol)));

        //Send email to user
        if (class_exists('Workreap_Email_helper')) {
            if (class_exists('WorkreapGetPassword')) {
                $email_helper = new WorkreapGetPassword();
                $emailData = array();
                $emailData['username']  = $username;
                $emailData['email']     = $user_email;
                $emailData['link']      = $reset_link;
                $email_helper->send($emailData);
            }
        }
		
		//Push notification
		$push	= array();
		$push['receiver_id']		= $user_id;
		$push['%name%']				= $username;
		$push['%link%']				= $reset_link;
		$push['%account_email%']	= $user_email;
		
		$push['%replace_link%']				= $reset_link;
		$push['%replace_account_email%']	= $user_email;
		
		$push['type']				= 'reset_password';

		do_action('workreap_user_push_notify',array($push['receiver_id']),'','pusher_lp_content',$push);

        $json['type'] = "success";
        $json['message'] = esc_html__("A link has been sent, please check your email.", 'workreap');
        wp_send_json( $json );
    }

    add_action('wp_ajax_workreap_ajax_lp', 'workreap_ajax_lp');
    add_action('wp_ajax_nopriv_workreap_ajax_lp', 'workreap_ajax_lp');
}

/**
 * Reset Password
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_ajax_reset_password')) {

    function workreap_ajax_reset_password() {
        global $wpdb;
        $json = array();   
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
        //Security check
        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$password		= sanitize_text_field ( $_POST['password'] );
		$new_password	= sanitize_text_field ( $_POST['verify_password'] );
		
		
		if ( empty($password) || empty($new_password) ) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Password should not be empty', 'workreap');
			wp_send_json( $json );
		}

		if ( $password != $new_password) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Password does not match.', 'workreap');
			wp_send_json( $json );
		}
		
		if (!empty($new_password)) {
			do_action('workreap_strong_password_validation',$new_password);
		}

        if (!empty($_POST['key']) &&
			( isset($_POST['reset_action']) && $_POST['reset_action'] == "reset_pwd" ) &&
			(!empty($_POST['login']) )
        ) {

            $reset_key  = sanitize_text_field($_POST['key']);
            $user_login = sanitize_text_field($_POST['login']);

            $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;

            if (!empty($reset_key) && !empty($user_data)) {
                wp_set_password($new_password, $user_data->ID);

                $json['redirect_url'] = home_url('/');
                $json['type'] = "success";
                $json['message'] = esc_html__("Congratulation! your password has been changed.", 'workreap');
                wp_send_json( $json );
            } else {
                $json['type'] = "error";
                $json['message'] = esc_html__("Oops! Invalid request", 'workreap');
                wp_send_json( $json );
            }
        } else {
        	$json['type'] = 'error';
        	$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
        	wp_send_json( $json );
        }
    }

    add_action('wp_ajax_workreap_ajax_reset_password', 'workreap_ajax_reset_password');
    add_action('wp_ajax_nopriv_workreap_ajax_reset_password', 'workreap_ajax_reset_password');
}


/**
 * Process proposal
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_process_project_proposal' ) ){
    function workreap_process_project_proposal(){
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
        $user_role 			= apply_filters('workreap_get_user_role', $current_user->ID);
        $linked_profile  	= workreap_get_linked_profile_id($current_user->ID);

		$project_id         = !empty($_POST['post_id']) ?  intval( $_POST['post_id'] ) :'';
		$proposed_amount    = !empty($_POST['proposed_amount']) ?   workreap_wmc_compatibility( $_POST['proposed_amount'] )  : '';
		$proposal_edit_id	= !empty($_POST['proposal_id']) ?  intval( $_POST['proposal_id'] ) : '';

		if (function_exists('fw_get_db_settings_option')) {
			$restrict_proposals   = fw_get_db_settings_option('restrict_proposals', false);
		}
		
		if(!empty($restrict_proposals) && $restrict_proposals === 'yes' && empty($proposal_edit_id)){
			$expiry_date   = '';
			if (function_exists('fw_get_db_post_option')) {                               
				$expiry_date   = fw_get_db_post_option($project_id, 'expiry_date', true);
			}
			
			$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';
			//if job has expired
			if( !empty($expiry_date) && $expiry_date !== 1 && current_time( 'timestamp' ) > strtotime($expiry_date) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('We are sorry, but this job has been expired.','workreap');
				wp_send_json( $json );
			}
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( apply_filters('workreap_feature_connects', $current_user->ID) === false && empty($proposal_edit_id) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You’ve consumed all your credits to apply on a job. Please subscribe to a package to apply on this job','workreap');
			wp_send_json( $json );
		}
		
		do_action('workreap_check_post_author_status', $linked_profile); //check if user is not blocked or deactive
		do_action('workreap_check_post_author_identity_status', $linked_profile); //check if user identity is verified
		do_action('workreap_check_switch_user_status', $project_id);
		
		if( get_post_status( $project_id ) === 'hired' ){
			$json['type'] = 'error';
            $json['message'] = esc_html__('This project has been assigned to one of the freelancer. You can\'t send proposals.', 'workreap');
            wp_send_json( $json );
		} else if( get_post_status( $project_id ) === 'completed' ){
			$json['type'] = 'error';
            $json['message'] = esc_html__('This project has been completed, so you can\'t send proposals', 'workreap');
            wp_send_json( $json );
		}else if( get_post_status( $project_id ) === 'completed' ){
			$json['type'] = 'error';
            $json['message'] = esc_html__('This project has been cancelled, when employer will re-open this project then you would be able to send proposal.', 'workreap');
            wp_send_json( $json );
		}else if( get_post_status( $project_id ) === 'pending' ){
			$json['type'] = 'error';
            $json['message'] = esc_html__('This project is under review. You can\'t send proposals.', 'workreap');
            wp_send_json( $json );
		}
		
		//Check user role
        if( $user_role !== 'freelancers' ){
            $json['type'] = 'error';
            $json['message'] = esc_html__('You are not allowed to send  the proposals', 'workreap');
            wp_send_json( $json );
        }

        if( empty( $_POST['post_id'] ) ){
            $json['type'] = 'error';
            $json['message'] = esc_html__('Some thing went wrong, try again', 'workreap');
            wp_send_json( $json );
        }
				
        //Check if user already submitted proposal
        $proposals_sent = intval(0);
        $args = array(
            'post_type' => 'proposals',
            'author'    =>  $current_user->ID,
            'meta_query' => array(
                array(
                    'key'     => '_project_id',
                    'value'   => intval( $_POST['post_id'] ),
                    'compare' => '=',
                ),
            ),
        );

        $query = new WP_Query( $args );
        if( !empty( $query ) ){
           $proposals_sent =  $query->found_posts;
        }

        if( $proposals_sent > 0 && empty($proposal_edit_id) ){
            $json['type'] = 'error';
            $json['message'] = esc_html__('You have already sent the proposal', 'workreap');
            wp_send_json( $json );
        }

        //Check if project is open
        $project_status = get_post_status( $project_id );
        if( $project_status === 'closed' ){
            $json['type'] = 'error';
            $json['message'] = esc_html__('You can not send proposal for a closed project', 'workreap');
            wp_send_json( $json );
        }        

        //Validation
        $validations = array(            
            'proposed_amount'       => esc_html__('Amount field is required', 'workreap'),
            'proposed_content'      => esc_html__('Cover latter field is required', 'workreap'),            
        );
		
		$validations	= apply_filters('workreap_sort_proposal_validations',$validations);
		
        foreach ( $validations as $key => $value ) {
			$field_value	= !empty($_POST[$key]) ?  $_POST[$key] : '';
			$field_value	= $key == 'proposed_amount' ? floatval($field_value) : $field_value;
			
            if ( empty( $field_value ) ) {
                $json['type'] = 'error';
                $json['message'] = $value;
                wp_send_json( $json );
            }                    
        }           

		if (function_exists('fw_get_db_post_option')) {
			$db_project_type     = fw_get_db_post_option($project_id,'project_type');
			if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){
				if( empty( $_POST['estimeted_time'])) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Estimated Hours are required','workreap');
					wp_send_json( $json );
				} else {
					$estimeted_time     = sanitize_text_field( $_POST['estimeted_time'] );
					$per_hour_amount	= $proposed_amount;
					$proposed_amount	= $proposed_amount * $estimeted_time;
				}
			} else if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
				if( empty( $_POST['proposed_time'])) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Proposal time is required','workreap');
					wp_send_json( $json );
				} else {
					$proposed_time      = sanitize_text_field( $_POST['proposed_time'] );
				}
			}
		}
		
        //Get Form data
        $fw_options 		= array();
        $user_id            = $current_user->ID;
        
        $proposed_content   = sanitize_textarea_field( $_POST['proposed_content'] );
        $files              = !empty( $_POST['temp_items'] ) ? $_POST['temp_items'] : array();     
        $proposal_files     = array();
		
		
		//Calculate Service and Freelance Share
        $service_fee		= workreap_commission_fee($proposed_amount,'projects',$project_id);

		if( !empty( $service_fee ) ){
			$admin_amount       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
        	$freelancer_amount  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $proposed_amount;
		} else{
			$admin_amount       = 0;
        	$freelancer_amount  = $proposed_amount;
		}
		
		$admin_amount 		= number_format($admin_amount,2,'.', '');
		$freelancer_amount 	= number_format($freelancer_amount,2,'.', '');
                
		//Upload files from temp folder to uploads
	
        if( !empty( $files ) ) {
            foreach ( $files as $key => $value ) { 
				if( !empty($value['attachment_id']) ) {
					$proposal_files[$key] = $value;
				} else {
					$proposal_files[] = workreap_temp_upload_to_media($value, $project_id,true);
				}
            }                
        }
	
        //Create Proposal
        $username   = workreap_get_username( $current_user->ID );
        $title      = esc_html( get_the_title( $project_id ));
        $title      = $title .' ' . '(' . $username . ')';
		
		$proposal_post = array(
			'post_title'    => wp_strip_all_tags( $title ), //proposal title
			'post_status'   => 'publish',
			'post_content'  => $proposed_content,
			'post_author'   => $current_user->ID,
			'post_type'     => 'proposals',
		);

		if( empty($proposal_edit_id) ){
			$proposal_id    = wp_insert_post( $proposal_post );

			//Prepare Params
			$params_array['user_identity'] = $current_user->ID;
			$params_array['project_id'] = (int) $project_id;
			$params_array['user_role'] = apply_filters('workreap_get_user_type', $current_user->ID );
			$params_array['type'] = 'proposal_made';

			//child theme : update extra settings
			do_action('wt_process_proposal_made', $params_array);
		} else {
			$proposal_post['ID']	= $proposal_edit_id;
			$proposal_id    		= $proposal_edit_id;
			wp_update_post( $proposal_post );
		}
		
        if( !is_wp_error( $proposal_id ) ) {
			
			
			if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
            	update_post_meta( $proposal_id, '_proposed_duration', $proposed_time );
				$fw_options['proposal_duration'] 	= $proposed_time;
			} else if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){
				update_post_meta( $proposal_id, '_estimeted_time', $estimeted_time );
				update_post_meta( $proposal_id, '_per_hour_amount', $per_hour_amount );
				$fw_options['estimeted_time'] 	= $estimeted_time;
				$fw_options['per_hour_amount'] 	= $per_hour_amount;
			}
			
            //Update post meta
            update_post_meta( $proposal_id, '_send_by', $linked_profile);
            update_post_meta( $proposal_id, '_project_id', $project_id );
            update_post_meta( $proposal_id, '_amount', $proposed_amount);
            update_post_meta( $proposal_id, '_status', 'pending');
            update_post_meta( $proposal_id, '_admin_amount', $admin_amount);
            update_post_meta( $proposal_id, '_freelancer_amount', $freelancer_amount);
			
			//update connects
			if ( function_exists( 'fw_get_db_settings_option' ) ) {
				$proposal_connects 	= fw_get_db_settings_option( 'proposal_connects', $default_value = null );
				$proposal_connects	= !empty( $proposal_connects ) ? intval( $proposal_connects ) : 2;
			} 
			
			$remaning_connects		= workreap_get_subscription_metadata( 'wt_connects',intval($current_user->ID) );
			$remaning_connects  	= !empty( $remaning_connects ) ? intval($remaning_connects) : '';
			
			if( !empty( $remaning_connects) && $remaning_connects >= $proposal_connects && empty($proposal_edit_id) ) {
				$update_connects	= $remaning_connects - $proposal_connects ;
				$update_connects	= intval($update_connects);
				
				$wt_subscription 	= get_user_meta(intval($current_user->ID), 'wt_subscription', true);
				$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();
				$wt_subscription['wt_connects'] = $update_connects;
				update_user_meta( intval($current_user->ID), 'wt_subscription', $wt_subscription);
			}
			
			
            if( !empty( $proposal_files ) ){
                update_post_meta( $proposal_id, '_proposal_docs', $proposal_files);
                $fw_options['proposal_docs'] = $proposal_files;
            }
            
			//update meta
			$fw_options['project']				= array($project_id);
			$fw_options['proposed_amount'] 		= $proposed_amount;
			
			
			//Update User Profile
			fw_set_db_post_option($proposal_id, null, $fw_options);

			//update more data hook 
			do_action('workreap_update_proposals_extra_data',$_POST,$proposal_id);
			
            //Send email to employer
            if (class_exists('Workreap_Email_helper')) {
                if (class_exists('WorkreapProposalSubmit')) {
					
					if(empty($proposal_edit_id)){
						$freelancer_link        = esc_url( get_the_permalink( $linked_profile ));
						$project_link           = esc_url( get_the_permalink( $project_id ));
						$project_title          = esc_html( get_the_title( $project_id ));
						$duration_list          = worktic_job_duration_list();
						$project_duration_value = !empty( $duration_list[$proposed_time] ) ? $duration_list[$proposed_time] : '';

						$post_author_id         = get_post_field( 'post_author', $project_id );
						$author_data            = get_userdata( $post_author_id );                    
						$email_to               = $author_data->user_email; 
						$employer_name          = workreap_get_username( $post_author_id );                 

						$email_helper           = new WorkreapProposalSubmit();

						$emailData = array();
						$emailData['employer_name']              = $employer_name;
						$emailData['freelancer_link']            = $freelancer_link;
						$emailData['freelancer_name']            = $username;
						$emailData['project_link']               = $project_link;
						$emailData['project_title']              = $project_title;
						$emailData['proposal_amount']            = workreap_price_format($proposed_amount,'return');;
						$emailData['proposal_duration']          = $project_duration_value;
						$emailData['proposal_message']           = $proposed_content;
						$emailData['employer_email']             = $email_to;
						$emailData['freelancer_email']           = $current_user->user_email;
						
						if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
							$emailData['project_type']             = 'fixed';
						}else{
							$emailData['project_type']             = 'hourly';
						}
						
						$email_helper->send_employer_proposal_submit($emailData);
						$email_helper->send_freelancer_proposal_submit($emailData);
						
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $current_user->ID;
						$push['employer_id']		= $post_author_id;
						$push['project_id']			= $project_id;

						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%project_title%']	= $emailData['project_title'];
						$push['%project_link%']		= $emailData['project_link'];
						
						$push['%proposal_amount%']	= $emailData['proposal_amount'];
						$push['%proposal_duration%']= $emailData['proposal_duration'];
						$push['%message%']			= $emailData['proposal_message'];
						
						$push['%replace_proposal_amount%']		= $emailData['proposal_amount'];
						$push['%replace_proposal_duration%']	= $emailData['proposal_duration'];
						$push['%replace_message%']				= $emailData['proposal_message'];
						$push['type']							= 'proposal_received';
						
						//employer notification
						do_action('workreap_user_push_notify',array($post_author_id),'','pusher_emp_proposal_content',$push);
						
						//freelancer notification
						$push['type']					= 'submit_proposal';
						do_action('workreap_user_push_notify',array($current_user->ID),'','pusher_frl_proposal_content',$push);
						
						
					}
                }
            }

			$json['return']  = esc_url( get_the_permalink( $project_id ));
            $json['type']    = 'success';
            $json['message'] = esc_html__('Your proposal has sent Successfully', 'workreap');
            wp_send_json( $json );
			
        } else {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Some thing went wrong, try again', 'workreap');
            wp_send_json( $json );
        }

    }
    add_action('wp_ajax_workreap_process_project_proposal', 'workreap_process_project_proposal');
}

/**
 * File uploader
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_temp_file_uploader')) {

    function workreap_temp_file_uploader() {       
        global $current_user, $wp_roles, $userdata, $post;
        $user_identity 		= $current_user->ID;
        $ajax_response  	=  array();
        $upload 			= wp_upload_dir();
		
		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$ajax_response['type'] = 'error';
			$ajax_response['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $ajax_response );
		}

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		if ( apply_filters('workreap_get_user_type', $user_identity) === 'employer' 
			|| apply_filters('workreap_get_user_type', $user_identity) === 'freelancer'  ){

			$upload_dir = $upload['basedir'];
			$upload_dir = $upload_dir . '/workreap-temp/';

			//create directory if not exists
			if (! is_dir($upload_dir)) {
			   wp_mkdir_p( $upload_dir );
			}

			$submitted_file = $_FILES['file_name'];
			$name = preg_replace("/[^A-Z0-9._-]/i", "_", $submitted_file["name"]);

			//file type check
			$filetype 		= wp_check_filetype($submitted_file['name']);

			//check if file type is allowed
			$file_info 		= wp_check_filetype_and_ext($submitted_file['tmp_name'], $submitted_file['name'], false);
			$ext_verify 	= empty($file_info['ext']) ? '' : $file_info['ext'];
         	$type_verify 	= empty($file_info['type']) ? '' : $file_info['type'];

			 if (!$ext_verify || !$type_verify) {
				$ajax_response['message'] = esc_html__('These file types are not allowed', 'workreap');
				$ajax_response['type']    = 'error';
				wp_send_json($ajax_response);
			}

			$allowed_types	= array('php','javascript','js','exe','text/javascript','text/php');
			$file_ext		= !empty($filetype['ext']) ? $filetype['ext'] : ''; 

			if(!empty($file_ext)){
				if(in_array($file_ext,$allowed_types)){
					$ajax_response['message'] = esc_html__('These file types are not allowed', 'workreap');
					$ajax_response['type']    = 'error';
					wp_send_json($ajax_response);
				}	
			}elseif(empty($file_ext)){
				if(in_array($submitted_file['type'],$allowed_types)){
					$ajax_response['message'] = esc_html__('These file types are not allowed', 'workreap');
					$ajax_response['type']    = 'error';
					wp_send_json($ajax_response);
				}
			}

			$i = 0;
			$parts = pathinfo($name);
			while (file_exists($upload_dir . $name)) {
				$i++;
				$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			}

			//move files
			$is_moved = move_uploaded_file($submitted_file["tmp_name"], $upload_dir . '/'.$name);                
			if( $is_moved ){
				$size       = $submitted_file['size'];
				$file_size  = size_format($size, 2);           
				$ajax_response['type']    = 'success';
				$ajax_response['message'] = esc_html__('File uploaded!', 'workreap');
				$url = $upload['baseurl'].'/workreap-temp/'.$name;
				$ajax_response['thumbnail'] = $upload['baseurl'].'/workreap-temp/'.$name;
				$ajax_response['name']    = $name;
				$ajax_response['size']    = $file_size;
			} else{
				$ajax_response['message'] = esc_html__('Some error occur, please try again later', 'workreap');
				$ajax_response['type']    = 'error';
			}
		}else{
			$ajax_response['message'] = esc_html__('You are not authorized to upload any files', 'workreap');
			$ajax_response['type']    = 'error';
		}
		
        wp_send_json($ajax_response);
    }

    add_action('wp_ajax_workreap_temp_file_uploader', 'workreap_temp_file_uploader');
    add_action('wp_ajax_nopriv_workreap_temp_file_uploader', 'workreap_temp_file_uploader');
}

/**
 * Generate QR code
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_generate_qr_code' ) ) {
    function workreap_generate_qr_code(){
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
        $user_id = !empty( $_POST['key'] ) ? $_POST['key'] : '';  
        $type    = !empty( $_POST['type'] ) ? $_POST['type'] : '';        
        if( file_exists( WP_PLUGIN_DIR. '/workreap_core/libraries/phpqrcode/phpqrcode.php' ) ){
            if( !empty( $user_id ) && !empty( $type ) ) {  
                require_once(WP_PLUGIN_DIR. '/workreap_core/libraries/phpqrcode/phpqrcode.php' );
                $user_link      = get_permalink( $user_id );
                $data_type 		= $type.'-';
				
                $tempDir        = wp_upload_dir();                  
                $codeContents   = esc_url($user_link);      
                $tempUrl    = trailingslashit($tempDir['baseurl']);
                $tempUrl    = $tempUrl.'/qr-code/'.$data_type.$user_id.'/';            
                $upload_dir = trailingslashit($tempDir['basedir']);
                $upload_dir = $upload_dir .'qr-code/';
				
                if (! is_dir($upload_dir)) {
                    wp_mkdir_p( $upload_dir);
                    //qr-code directory created
                    $upload_folder = $upload_dir.$data_type.$user_id.'/';                
                    if (! is_dir($upload_folder)) {
                        wp_mkdir_p( $upload_folder);
                        //Create image
                        $fileName = $user_id.'.png';      
                        $qrAbsoluteFilePath = $upload_folder.$fileName; 
                        $qrRelativeFilePath = $tempUrl.$fileName;     
                    } 
                } else {
                    //create user directory
                    $upload_folder = $upload_dir.$data_type.$user_id.'/';              
                    if (! is_dir($upload_folder)) {
                        wp_mkdir_p( $upload_folder );
                        //Create image
                        $fileName = $user_id.'.png';      
                        $qrAbsoluteFilePath = $upload_folder.$fileName; 
                        $qrRelativeFilePath = $tempUrl.$fileName;     
                    } else {
                        $fileName = $user_id.'.png';      
                        $qrAbsoluteFilePath = $upload_folder.$fileName; 
                        $qrRelativeFilePath = $tempUrl.$fileName;     
                    }
                }                
                //Delete if exists
                if (file_exists($qrAbsoluteFilePath)) { 
                    wp_delete_file( $qrAbsoluteFilePath );
                    QRcode::png($codeContents, $qrAbsoluteFilePath, QR_ECLEVEL_L, 3);                        
                } else {
                    QRcode::png($codeContents, $qrAbsoluteFilePath, QR_ECLEVEL_L, 3);            
                }           
                
                if( !empty( $qrRelativeFilePath ) ) {
                        $json['type'] = 'success';
                        $json['message'] = esc_html__('QR code genrated successfully', 'workreap');
                        $json['key'] = $qrRelativeFilePath;
                        wp_send_json( $json );
                }     
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some thing went wrong.', 'workreap');
                wp_send_json( $json );  
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Something went wrong.', 'workreap');
                wp_send_json( $json ); 
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Please update/install required plugins', 'workreap');
            wp_send_json( $json );
        }
    }
    add_action('wp_ajax_workreap_generate_qr_code', 'workreap_generate_qr_code');
    add_action('wp_ajax_nopriv_workreap_generate_qr_code', 'workreap_generate_qr_code');
}

/**
 * add project to favourite
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_add_project_to_wishlist')) {

    function workreap_add_project_to_wishlist() {
        global $current_user;
        $json           = array();
		$saves_jobs     = array();
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
        $post_id        = workreap_get_linked_profile_id($current_user->ID);
        $saves_jobs     = get_post_meta($post_id, '_saved_projects', true);
        $saves_jobs     = !empty( $saves_jobs ) && is_array( $saves_jobs ) ? $saves_jobs : array();
        $project_id     = sanitize_text_field( $_POST['project_id'] );

        if (!empty($project_id)) {            
            $saves_jobs[] = $project_id;
            $saves_jobs   = array_unique( $saves_jobs );
            update_post_meta( $post_id, '_saved_projects', $saves_jobs );
           
            $json['type'] 		= 'success';
			$json['text'] 		= esc_html__('Saved', 'workreap');
            $json['message'] 	= esc_html__('Successfully! added to your saved jobs', 'workreap');
            wp_send_json( $json );
        }
        
        $json['type'] 		= 'error';
        $json['message'] 	= esc_html__('Oops! something is going wrong.', 'workreap');
        wp_send_json( $json );
    }

    add_action('wp_ajax_workreap_add_project_to_wishlist', 'workreap_add_project_to_wishlist');
    add_action('wp_ajax_nopriv_workreap_add_project_to_wishlist', 'workreap_add_project_to_wishlist');
}

/**
 * follow employer action
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_follow_employer' ) ) {

	function workreap_follow_employer() {
		global $current_user;
		$type 		= !empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$post_id 	= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		$json 		= array();

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		$linked_profile   		= workreap_get_linked_profile_id($current_user->ID);
		
		//employer followers
		$emp_followers 			= get_post_meta($post_id, '_followers', true);
		$emp_followers   		= !empty( $emp_followers ) && is_array( $emp_followers ) ? $emp_followers : array();
		$emp_followers[] 		= $linked_profile;
		$emp_followers   		= array_unique( $emp_followers );
		update_post_meta($post_id, '_followers', $emp_followers);
		
		//update user followings
		$user_followings 		= get_post_meta($linked_profile, '_following_employers', true);
		$user_followings   		= !empty( $user_followings ) && is_array( $user_followings ) ? $user_followings : array();
		$user_followings[] 		= $post_id;
		$user_followings   		= array_unique( $user_followings );
		
		update_user_meta( $current_user->ID, '_following_employers', $user_followings );
		
		if( !empty( $linked_profile ) ){
			update_post_meta($linked_profile, '_following_employers', $user_followings);
		}
		
		$json['type'] = 'success';
		$json['message'] = esc_html__( 'Successfully added your following list', 'workreap' );
		wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_follow_employer', 'workreap_follow_employer' );
	add_action( 'wp_ajax_nopriv_workreap_follow_employer', 'workreap_follow_employer' );
}

/**
 * Report employer, project or freelancer 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_report_user' ) ) {
	function workreap_report_user(){
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json 			= array();
		$type 			= !empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$reported_id 	= !empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$description 	= !empty( $_POST['description'] ) ? sanitize_textarea_field( $_POST['description'] ) : '';
		$reason 		= !empty( $_POST['reason'] ) ? sanitize_textarea_field( $_POST['reason'] ) : '';
		$json['loggin'] = 'true';

		if( empty( $reason ) || empty( $description ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__( 'All the fields are required', 'workreap' );
			wp_send_json( $json );
		}

		if ( empty( $current_user->ID ) ) {
			$json['type'] = 'error';
			$json['loggin'] = 'false';
			$json['message'] = esc_html__( 'You must login before report', 'workreap' );
			wp_send_json( $json );
		}
		
		$reasons	= workreap_get_report_reasons();
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$remove_settings 	= fw_get_db_settings_option( 'report_'.$type, $default_value = null );
			$remove_report		= !empty( $remove_settings['gadget'] ) ? $remove_settings['gadget'] : 'no';
			$reasons			= !empty( $remove_settings['no']['report_options'] ) ? $remove_settings['no']['report_options'] : 'no';
			
			if( !empty( $reasons ) and is_array($reasons) ){
				$reasons = array_filter($reasons);
				$reasons = array_combine(array_map('sanitize_title', $reasons), $reasons);
			} else{
				$reasons	= workreap_get_report_reasons();
			}
		} 
		
		$linked_profile   	= workreap_get_linked_profile_id($current_user->ID);
		$title				= !empty( $reasons[$reason] ) ? $reasons[$reason] : rand(1,999999);
		
		//Create Post
		$user_post = array(
			'post_title'    => wp_strip_all_tags( $title ),
			'post_status'   => 'publish',
			'post_content'  => $description,
			'post_author'   => $current_user->ID,
			'post_type'     => 'reports',
		);

		$post_id    = wp_insert_post( $user_post );
		
		
		if( !is_wp_error( $post_id ) ) {
			update_post_meta($post_id, '_report_type', $type);
			update_post_meta($post_id, '_reported_id', $reported_id);
			update_post_meta($post_id, '_user_by', $linked_profile);
			
			//Send email to users
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapReportUser')) {
					$email_helper = new WorkreapReportUser();
					$emailData = array();
					$emailData['name'] 				= esc_html( get_the_title($reported_id));
					$emailData['user_link'] 		= get_edit_post_link($linked_profile);
					$emailData['message'] 			= $description;
					$emailData['reported_by'] 		= workreap_get_username($current_user->ID);
					$emailData['reported_title'] 	= $title;
					
					if( !empty( $type ) && $type === 'employer' ){
						$emailData['employer_link'] 	= get_edit_post_link($reported_id);
						$email_helper->send_employer_report($emailData);
					} else if( !empty( $type ) && $type === 'project' ){
						$emailData['project_link'] 	= get_edit_post_link($reported_id);
						$email_helper->send_project_report($emailData);
					} else if( !empty( $type ) && $type === 'freelancer' ){
						$emailData['freelancer_link'] 	= get_edit_post_link($reported_id);
						$email_helper->send_freelancer_report($emailData);
					}else if( !empty( $type ) && $type === 'service' ){
						$emailData['service_link'] 	= get_edit_post_link($reported_id);
						$email_helper->send_service_report($emailData);
					}
				}
			}
			
			$json['type'] = 'success';
			$json['message'] = esc_html__('Your report has submitted', 'workreap');                
			wp_send_json($json);
		} else {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occurs, please try again later', 'workreap');                
			wp_send_json($json);
		}			
	}
	add_action( 'wp_ajax_workreap_report_user', 'workreap_report_user' );
	add_action( 'wp_ajax_nopriv_workreap_report_user', 'workreap_report_user' );
}

/**
 * follow freelqancer action
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_follow_freelancer' ) ) {

	function workreap_follow_freelancer() {
		global $current_user;
		$post_id = !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		$json = array();

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$linked_profile   	= workreap_get_linked_profile_id($current_user->ID);
		$saved_freelancers 	= get_post_meta($linked_profile, '_saved_freelancers', true);
		
		$json       = array();
        $wishlist   = array();
        $wishlist   = !empty( $saved_freelancers ) && is_array( $saved_freelancers ) ? $saved_freelancers : array();

        if (!empty($post_id)) {
            if( in_array($post_id, $wishlist ) ){                
                $json['type'] = 'error';
                $json['message'] = esc_html__('This freelancer is already to your wishlist', 'workreap');
                wp_send_json( $json );
            }

            $wishlist[] = $post_id;
            $wishlist   = array_unique( $wishlist );
            update_post_meta( $linked_profile, '_saved_freelancers', $wishlist );
           
            $json['type'] = 'success';
            $json['message'] = esc_html__('Successfully! added to your wishlist', 'workreap');
            wp_send_json( $json );
        }
        
        $json['type'] = 'error';
        $json['message'] = esc_html__('Oops! something is going wrong.', 'workreap');
        wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_follow_freelancer', 'workreap_follow_freelancer' );
	add_action( 'wp_ajax_nopriv_workreap_follow_freelancer', 'workreap_follow_freelancer' );
}

/**
 * Update freelancer Profile
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_update_freelancer_profile' ) ){
    function workreap_update_freelancer_profile(){       
        global $current_user, $post;               
        $json = array();
		$user_id		 = $current_user->ID;
		$post_id  		 = workreap_get_linked_profile_id($user_id);
		$hide_map 		= 'show';

		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($post_id);
		} //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		if (function_exists('fw_get_db_settings_option') ) {
			$hide_map			= fw_get_db_settings_option('hide_map');
			$profile_mandatory	= fw_get_db_settings_option('freelancer_profile_required');
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$phone_setting 		= '';
		$phone_mandatory	= '';
		if (function_exists('fw_get_db_settings_option')) {
			$phone_option		= fw_get_db_settings_option('phone_option');
			$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
			$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
		}
		
		if( !empty($phone_setting) && $phone_setting == 'enable' && !empty($phone_mandatory) && $phone_mandatory == 'enable' ){
			if( empty( $_POST['basics']['user_phone_number'] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Phone number is required', 'workreap');        
				wp_send_json($json);
			}
		}

		//Skills
        $skills 		= !empty( $_POST['settings']['skills'] ) ? $_POST['settings']['skills'] : array();
        $skill_keys 	= array();
        $skills_new 	= array();
		$skills_term 	= array();
		$skills_names 	= array();
		$counter 		= 0;
		
		// Featured skills
		$feature_skills		= workreap_is_feature_value( 'wt_no_skills', $user_id);

		if(!empty($profile_mandatory)) {
			$freelancer_required = workreap_freelancer_required_fields();
			foreach ($profile_mandatory as $key) {
				if($key === 'freelancer_type' ){
					if( empty( $_POST['settings'][$key] ) ){
					 $json['type'] 		= 'error';
					 $json['message'] 	= $freelancer_required[$key];        
					 wp_send_json($json);
					}
				}else if($key === 'skills'){
					if( empty( $_POST['settings'][$key] ) && empty( $_POST['settings']['custom_skills'] ) ){
					 $json['type'] 		= 'error';
					 $json['message'] 	= $freelancer_required[$key];        
					 wp_send_json($json);
					}
				}else{
					if( empty( $_POST['basics'][$key] ) ){
					 $json['type'] 		= 'error';
					 $json['message'] 	= $freelancer_required[$key];        
					 wp_send_json($json);
					}
				}
				
			 }
		}
		
        if( !empty( $skills ) ){
            foreach ($skills as $key => $value) {
                if( !in_array($value['skill'], $skill_keys ) ){
                    $skill_keys[] = $value['skill'];
                    $skills_new[$counter]['skill'][0] = $value['skill'];
                    $skills_new[$counter]['value'] = $value['value'];
					$skills_term[] = $value['skill'];
                    $counter++;
					
					if(!empty($value['skill'])){
						$skills_names[] = get_term( $value['skill'] )->name;
					}
					
                }
			} 
			
			//Prepare Params
			$params_array['post_obj'] = $_POST;
			$params_array['user_identity'] = $current_user->ID;
			$params_array['user_role'] = apply_filters('workreap_get_user_type', $current_user->ID );
			$params_array['type'] = 'skills';

			//child theme : update extra settings
			do_action('wt_process_profile_child', $params_array);

			if( !empty($skills_term) ){
				wp_set_post_terms( $post_id, $skills_term, 'skills' );
				do_action('workreap_update_profile_strength','skills',true);
			}else{
				do_action('workreap_update_profile_strength','skills',false);
			}
        }

		//update languages
		
		$lang		= array();
		$lang_slugs	= array();
		if( !empty( $_POST['settings']['languages'] ) ){
			foreach( $_POST['settings']['languages'] as $key => $item ){
				$lang[] = $item;
			}
		}

		wp_set_post_terms($post_id, $lang, 'languages');

		//update english level
		$english_level	= sanitize_text_field( $_POST['settings']['english_level']);
		update_post_meta($post_id, '_english_level', $english_level);

		//update freelancer type
		$freelancer_type = '';
		$freelancer_type	=  !empty( $_POST['settings']['freelancer_type'] ) ? $_POST['settings']['freelancer_type'] : '';
		update_post_meta($post_id, '_freelancer_type', $freelancer_type);
		$freelancer_type_array	= !empty($freelancer_type) && is_array($freelancer_type) ? $freelancer_type : array($freelancer_type);
		
		if( !empty( $freelancer_type_array ) ){
			do_action('workreap_update_term_taxonomy_meta', $_POST);
			wp_set_object_terms($post_id, $freelancer_type_array, 'freelancer_type');
		}
		
        //Form data
        $first_name = !empty($_POST['basics']['first_name']) ? sanitize_text_field($_POST['basics']['first_name']) : '';
        $last_name  = !empty($_POST['basics']['last_name'] ) ? sanitize_text_field($_POST['basics']['last_name']) : '';
        $gender     = !empty($_POST['basics']['gender'] ) ? $_POST['basics']['gender'] : '';
		$tag_line   = !empty($_POST['basics']['tag_line'] ) ? sanitize_text_field( $_POST['basics']['tag_line'] ) : '';
		
        $content    = !empty($_POST['basics']['content'] ) ? wp_kses_post( $_POST['basics']['content'] ) : '';
        $per_hour   = !empty($_POST['basics']['per_hour_rate']) ? intval($_POST['basics']['per_hour_rate']) : 0;        
        $address    = !empty( $_POST['basics']['address'] ) ? $_POST['basics']['address'] : '';
        $country    = !empty( $_POST['basics']['country'] ) ? $_POST['basics']['country'] : '';
        $latitude   = !empty( $_POST['basics']['latitude'] ) ? $_POST['basics']['latitude'] : '';
        $longitude  = !empty( $_POST['basics']['longitude'] ) ? $_POST['basics']['longitude'] : '';
		$display_name  = !empty( $_POST['basics']['display_name'] ) ? $_POST['basics']['display_name'] : '';

		
        
        //Update user meta
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
		
		if( !empty( $display_name ) ) {
			$post_title	= $display_name;
			$user_info	= array( 'ID' => $user_id, 'display_name' => $display_name );
			wp_update_user( $user_info );
		} else {
			$post_title	= esc_html( get_the_title( $post_id ));
		}
		
        //Update Freelancer Post        
        $freelancer_user = array(
            'ID'           => $post_id,
            'post_title'   => $post_title,
            'post_content' => $content,
        );

        // Update the post into the database
        wp_update_post( $freelancer_user );
		
		//Update tagline Profile health
		if(!empty($tag_line)){
			do_action('workreap_update_profile_strength','tagline',true);
		}else{
			do_action('workreap_update_profile_strength','tagline',false);
		}
		
		//Update description Profile health
		if(!empty($content)){
			do_action('workreap_update_profile_strength','description',true);
		}else{
			do_action('workreap_update_profile_strength','description',false);
		}
		
		//Update identity verification Profile health
		$identity_verified	= get_post_meta($post_id, 'identity_verified', true);
		if( !empty( $identity_verified ) ){
			do_action('workreap_update_profile_strength','identity_verification',true);
		}else{
			do_action('workreap_update_profile_strength','identity_verification',false);
		}
		
        update_post_meta($post_id, '_gender', $gender);
        update_post_meta($post_id, '_tag_line', $tag_line);
        update_post_meta($post_id, '_perhour_rate', $per_hour);
        update_post_meta($post_id, '_address', $address);
        update_post_meta($post_id, '_country', $country);
        update_post_meta($post_id, '_latitude', $latitude);
        update_post_meta($post_id, '_longitude', $longitude);
		update_post_meta($post_id, '_skills_names', $skills_names);

        //Profile avatar
        $profile_avatar = array();
        if( !empty( $_POST['basics']['avatar']['attachment_id'] ) ){
            $profile_avatar = $_POST['basics']['avatar'];
        } else {                                
            if( !empty( $_POST['basics']['avatar'] ) ){
                $profile_avatar = workreap_temp_upload_to_media($_POST['basics']['avatar'], $post_id);
            }
        }
		
		//Set country for unyson
        $locations = get_term_by( 'slug', $country, 'locations' );
        $location = array();
        if( !empty( $locations ) ){
            $location[0] = $locations->term_id;
			wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
        }
		
		//delete prevoius attachment ID
		$pre_attachment_id = get_post_thumbnail_id($post_id);
		if ( !empty($pre_attachment_id) && !empty( $profile_avatar['attachment_id'] ) && intval($pre_attachment_id) != intval($profile_avatar['attachment_id'])) {
			wp_delete_attachment($pre_attachment_id, true);
		}
		
		//update thumbnail
		if (!empty($profile_avatar['attachment_id'])) {
			delete_post_thumbnail($post_id);
			set_post_thumbnail($post_id, $profile_avatar['attachment_id']);
			update_post_meta($post_id, '_have_avatar', 1);
			do_action('workreap_update_profile_strength','avatar',true);
		} else {
			wp_delete_attachment( $pre_attachment_id, true );
			update_post_meta($post_id, '_have_avatar', 0);
			do_action('workreap_update_profile_strength','avatar',false);
		}   

        //Profile avatar
        $profile_banner = array();
        if( !empty( $_POST['basics']['banner']['attachment_id'] ) ){
            $profile_banner = $_POST['basics']['banner'];
        } else {                                
            if( !empty( $_POST['basics']['banner'] ) ){
                $profile_banner = workreap_temp_upload_to_media($_POST['basics']['banner'], $post_id);
            }else{
				$banner_image       = fw_get_db_post_option($post_id, 'banner_image', true);
				if(!empty($banner_image['attachment_id'])){
					wp_delete_attachment( $banner_image['attachment_id'], true );
				}
			}
		}
		
		//Resume
        $profile_resume = array();
        if( !empty( $_POST['basics']['resume']['attachment_id'] ) ){
            $profile_resume = $_POST['basics']['resume'];
        } else {                                
            if( !empty( $_POST['basics']['resume'] ) ){
                $profile_resume = workreap_temp_upload_to_media($_POST['basics']['resume'], $post_id,true);
            }else{
				$resume_attachment       = fw_get_db_post_option($post_id, 'resume', true);
				if(!empty($resume_attachment['attachment_id'])){
					wp_delete_attachment( $resume_attachment['attachment_id'], true );
				}
			}
        }   

        //Set country for unyson
        $locations = get_term_by( 'slug', $country, 'locations' );
        $location = array();
        if( !empty( $locations ) ){
            $location[0] = $locations->term_id;
        }
        
        
        update_post_meta($post_id, '_skills', $skills_new);
        
        //Experience
        $experiences = array();      
        $experience  = !empty( $_POST['settings']['experience'] ) ? $_POST['settings']['experience'] : array();        
        if( !empty( $experience ) ){
            $counter = 0;
			do_action('workreap_update_profile_strength','experience',true);
            foreach ($experience as $key => $value) {
                if( !empty( $value['title'] ) ){
                    $experiences[$counter]['title']       = sanitize_text_field($value['title']);
                    $experiences[$counter]['company']     = sanitize_text_field($value['job']);
                    $experiences[$counter]['startdate']   = apply_filters('workreap_picker_date_format',$value['startdate'] ); 
                    $experiences[$counter]['enddate']     = apply_filters('workreap_picker_date_format',$value['enddate'] ); 
                    $experiences[$counter]['description'] = $value['details'];
                    $counter++;
                }

            }
        }else{
			do_action('workreap_update_profile_strength','experience',false);
		}
		
        update_post_meta($post_id, '_experience', $experiences);

        //Education        
        $educations = array();      
        $education  = !empty( $_POST['settings']['education'] ) ? $_POST['settings']['education'] : array();  
        if( !empty( $education ) ){
            $counter = 0;
            foreach ($education as $key => $value) {
                if( !empty( $value['degree'] ) ){
                    $educations[$counter]['title']          = sanitize_text_field($value['degree']);
                    $educations[$counter]['institute']      = sanitize_text_field($value['university']);
                    $educations[$counter]['startdate']      = apply_filters('workreap_picker_date_format',$value['startdate'] ); 
                    $educations[$counter]['enddate']        = apply_filters('workreap_picker_date_format',$value['enddate'] ); 
                    $educations[$counter]['description']    = sanitize_textarea_field($value['details']);
                    $counter++;
                }

            }
        }
		
        update_post_meta($post_id, '_educations', $educations);

        //Awards
        $awards = array();
        $award = !empty( $_POST['settings']['awards'] ) ? $_POST['settings']['awards'] : array();
		
        if( !empty( $award ) ){
            $counter = 0;
            foreach ($award as $key => $value) {
                if( !empty( $value['title'] ) ){
                    $awards[$counter]['title']     = sanitize_text_field($value['title']);
                    $awards[$counter]['date']      = $value['date'];

                    if( !empty( $value['image']['attachment_id'] ) ){
                        $awards[$counter]['image'] = $value['image'];
                    } else {                                
                        if( !empty( $value['image'] ) ){
                            $awards[$counter]['image'] = workreap_temp_upload_to_media($value['image'], $post_id);
                        }
                    }
					
                    $counter++;
                }

            }
        }
		
        update_post_meta($post_id, '_awards', $awards);

        //Projects
        $projects = array();
        $project  = !empty( $_POST['settings']['project'] ) ? $_POST['settings']['project'] : array();
        if( !empty( $project ) ){
            $counter = 0;
            foreach ($project as $key => $value) {
                if( !empty( $value['title'] ) ){
                    $projects[$counter]['title']     = sanitize_text_field($value['title']);
                    $projects[$counter]['link']      = $value['link'];
                    if( !empty( $value['image']['attachment_id'] ) ){
                        $projects[$counter]['image'] = $value['image'];
                    } else {                                
                        if( !empty( $value['image'] ) ){
                            $projects[$counter]['image'] = workreap_temp_upload_to_media($value['image'], $post_id);
                        }
                    }
					
                    $counter++;
                }

            }
        }        
        update_post_meta($post_id, '_projects', $projects);
		
		$videos = !empty( $_POST['settings']['videos'] ) ? $_POST['settings']['videos'] : array();
		
        //Fw Options
		$fw_options = array();
		if( !empty($phone_setting) && $phone_setting == 'enable') {
			$user_phone_number  				= !empty( $_POST['basics']['user_phone_number'] ) ? $_POST['basics']['user_phone_number'] : '';
			$fw_options['user_phone_number']    = $user_phone_number;
		}

		$max_price   = !empty($_POST['basics']['max_price'] ) ? sanitize_text_field( $_POST['basics']['max_price'] ) : '';
		if (function_exists('fw_get_db_settings_option')) {
			$freelancer_price_option = fw_get_db_settings_option('freelancer_price_option', $default_value = null);
		}

		if(!empty($freelancer_price_option) && $freelancer_price_option === 'enable' ){
			$fw_options['max_price']     = $max_price;
			update_post_meta($post_id, '_max_price', $max_price);
		}
		
		//Profile avatar
		$gallery_old_attachment       = fw_get_db_post_option($post_id, 'images_gallery', true);
		if(!empty($gallery_old_attachment)){
			$gallery_old_attachment 	= wp_list_pluck($gallery_old_attachment,'attachment_id');
		}


        $profile_gallery = array();
        if( !empty( $_POST['basics']['images_gallery'] ) ){
            $fw_options['images_gallery'] 	= $_POST['basics']['images_gallery'];
		} 
		
		if( !empty( $_POST['basics']['images_gallery_new'] ) ){
			$new_index	= !empty($fw_options['images_gallery']) ?  max(array_keys($fw_options['images_gallery'])) : 0;
			foreach( $_POST['basics']['images_gallery_new'] as $new_gallery ){
				$new_index ++;
				$profile_gallery 							= workreap_temp_upload_to_media($new_gallery, $post_id);
				$fw_options['images_gallery'][$new_index]	= $profile_gallery;
			}
		}

		$delete_list = array();
		if(empty($fw_options['images_gallery']) && !empty($gallery_old_attachment)){
			foreach($gallery_old_attachment as $key => $delete_media){
				if(!empty($delete_media)){
					wp_delete_attachment( $delete_media, true );
				}
			}
		}else if(!empty($fw_options['images_gallery']) && !empty($gallery_old_attachment)){
			$gallery_new_attachment 	= wp_list_pluck($fw_options['images_gallery'],'attachment_id');
			
			if(!empty($gallery_old_attachment)){
				foreach($gallery_old_attachment as $key => $delete_media){
					if(!empty($delete_media) && !empty($gallery_new_attachment) && !in_array($delete_media,$gallery_new_attachment)){
						$delete_list[] = $delete_media;
						wp_delete_attachment( $delete_media, true );
					}
				}
			}
		}

		//specializations
        $specialization = !empty( $_POST['settings']['specialization'] ) ? $_POST['settings']['specialization'] : array();
        $spec_keys 	= array();
        $specialization_new 	= array();
		$specialization_term 	= array();

		$counter = 0;
        if( !empty( $specialization ) ){
            foreach ($specialization as $key => $value) {
                if( !in_array($value['spec'], $spec_keys ) ){
                    $spec_keys[] = $value['spec'];
                    $specialization_new[$counter]['spec'][0] = $value['spec'];
                    $specialization_new[$counter]['value'] = $value['value'];
					$specialization_term[] = $value['spec'];
                    $counter++;
                }
			}
		}

		wp_set_post_terms( $post_id, $specialization_term, 'wt-specialization' );
		$fw_options['specialization']             = $specialization_new;

		//specializations
        $industrial_experiences = !empty( $_POST['settings']['industrial_experiences'] ) ? $_POST['settings']['industrial_experiences'] : array();
        $exp_keys 	= array();
        $industrial_experiences_new 	= array();
		$industrial_experiences_term 	= array();
		
		$counter = 0;
        if( !empty( $industrial_experiences ) ){
            foreach ($industrial_experiences as $key => $value) {
                if( !in_array($value['exp'], $exp_keys ) ){
                    $exp_keys[] = $value['exp'];
                    $industrial_experiences_new[$counter]['exp'][0] = $value['exp'];
                    $industrial_experiences_new[$counter]['value'] = $value['value'];
					$industrial_experiences_term[] = $value['exp'];
                    $counter++;
                }
			} 

			if( !empty($industrial_experiences_term) ){
				wp_set_post_terms( $post_id, $industrial_experiences_term, 'wt-industrial-experience' );
			}
			$fw_options['industrial_experiences']             = $industrial_experiences_new;
		}
		
		$socialmediaurls	= array();
		if( function_exists('fw_get_db_settings_option')  ){
			$socialmediaurls	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
		}

		$socialmediaurl 		= !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';
		if(!empty($socialmediaurl) && $socialmediaurl  ==='enable'){
			$social_settings    	= function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('yes') : array();
			if(!empty($social_settings)) {
				foreach($social_settings as $key => $val ) {
					$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
					if( !empty($enable_value) && $enable_value === 'enable' ){
						$social_val	= !empty($_POST['basics'][$key]) ? esc_attr($_POST['basics'][$key]) : '';
						$fw_options[$key]           = $social_val;
					}
				}
			}
		}

		$freelancer_faq_option	= array();
		if( function_exists('fw_get_db_settings_option')  ){
			$freelancer_faq_option	= fw_get_db_settings_option('freelancer_faq_option', $default_value = null);
		}
		if(!empty($freelancer_faq_option) && $freelancer_faq_option == 'yes' ) {
			$faq 					= !empty( $_POST['settings']['faq'] ) ? $_POST['settings']['faq'] : array();
			$fw_options['faq']      = $faq;
		}

        $fw_options['gender']             = $gender;
		$fw_options['first_name']         = $first_name;
		$fw_options['last_name']          = $last_name;
        $fw_options['tag_line']           = $tag_line;
        $fw_options['_perhour_rate']      = $per_hour;
        $fw_options['address']            = $address;
        $fw_options['longitude']          = $longitude;
        $fw_options['latitude']           = $latitude;
        $fw_options['country']            = $location;
        $fw_options['skills']             = $skills_new;
        $fw_options['projects']           = $projects;
        $fw_options['awards']             = $awards;
        $fw_options['experience']         = $experiences;
        $fw_options['education']          = $educations;
        $fw_options['banner_image']       = $profile_banner;
		$fw_options['resume']       	  = $profile_resume;
		$fw_options['videos']       	  = $videos;
		$fw_options['freelancer_type']    = $freelancer_type;
		$fw_options['english_level']   	  = $english_level;


        //Update User Profile
        fw_set_db_post_option($post_id, null, $fw_options);
		
		
		$custom_skills = !empty( $_POST['settings']['custom_skills'] ) ? $_POST['settings']['custom_skills'] : array();
        $skills = !empty( $_POST['settings']['skills'] ) ? $_POST['settings']['skills'] : array();
        if( !empty( $custom_skills ) ){
            $fw_options             = fw_get_db_post_option($post_id);
            $skills_custom_term 	= array();
            $skills_custom_keys 	= array();
            $skills_custom_new 	    = array();
            $custom_term_email      = array();
            $custom_skill_counter   = 0;
    
            foreach($custom_skills as $key => $val) {
                $slugify_skill = sanitize_title($val['skill']);
                $term_exists = term_exists( $slugify_skill, 'skills' );
    
                if ( $term_exists !== null ) {
                    $insert_term = $term_exists;
                } else {
                    $insert_term = wp_insert_term(
                        esc_html($val['skill']),
                        'skills',
                        array(
                            'slug'        => $slugify_skill,
                            'parent'      => intval(0),
                        )
                    );
    
                    update_term_meta($insert_term['term_id'], 'skill_term_status', 'draft');
    
                    $custom_term_email[] = $slugify_skill;
                }
                
                if( !in_array($val['value'], $skills_custom_keys ) ){
                    $skills_custom_keys[] = $insert_term['term_id'];
                    $skills_custom_new[$custom_skill_counter]['skill'][0] = $insert_term['term_id'];
                    $skills_custom_new[$custom_skill_counter]['value'] = $val['value'];
                    $skills_custom_term[] = $insert_term['term_id'];
                    $custom_skill_counter++;
                }
            }
    
            $skill_keys 	= array();
            $skills_new 	= array();
            $skills_term 	= array();
            $skill_counter = 0;
            if (!empty($skills)) {
                foreach ($skills as $key => $value) {
                    if (!in_array($value['skill'], $skill_keys)) {
                        $skill_keys[] = $value['skill'];
                        $skills_new[$skill_counter]['skill'][0] = $value['skill'];
                        $skills_new[$skill_counter]['value'] = $value['value'];
                        $skills_term[] = (int) $value['skill'];
                        $skill_counter++;
                    }
                }
            }
    
            $final_term_array = array_merge($skills_term, $skills_custom_term);
            $final_skills_array = array_merge($skills_new, $skills_custom_new);

            if( !empty($skills_custom_term)) {
                wp_set_post_terms( $post_id, $final_term_array, 'skills' );
    
                update_post_meta($post_id, '_skills', $final_skills_array);
                $fw_options['skills']             = $final_skills_array;
                fw_set_db_post_option($post_id, null, $fw_options);
            }
        }

		//update profile health
		$get_profile_data	= get_post_meta($post_id, 'profile_strength',true);
		$total_percentage	= !empty( $get_profile_data['data'] ) ? array_sum( $get_profile_data['data'] ) : 0;
		$total_percentage	= !empty( $total_percentage ) ? intval($total_percentage) : 0;
		update_post_meta($post_id, '_profile_health_filter', $total_percentage); 

		
		//change status on update
		do_action('workreap_update_post_status_action',$post_id,'freelancer'); //Admin will get an email to publish it
		//child theme : update extra settings
		do_action('workreap_update_freelancer_profile_settings', $_POST);
		
        $json['type']    = 'success';
        $json['message'] = esc_html__('Settings Updated.', 'workreap');        
        wp_send_json($json);
    }
            
    add_action('wp_ajax_workreap_update_freelancer_profile', 'workreap_update_freelancer_profile');
}

/**
 * Update employer Profile
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_update_employer_profile' ) ){
    function workreap_update_employer_profile(){       
        global $current_user, $post;               
        $json = array();
		$user_id		 = $current_user->ID;
		$post_id  		 = workreap_get_linked_profile_id($user_id);

		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($post_id);
		}; //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		$hide_map 		= 'show';
		if (function_exists('fw_get_db_settings_option')) {
			$hide_map	= fw_get_db_settings_option('hide_map');
		}

		$phone_setting 		= '';
		$phone_mandatory	= '';
		if (function_exists('fw_get_db_settings_option')) {
			$phone_option		= fw_get_db_settings_option('phone_option');
			$profile_mandatory	= fw_get_db_settings_option('employer_profile_required');
			$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
			$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
		}
		
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		

		if(!empty($profile_mandatory)) {
			$employer_required = workreap_employer_required_fields();
			foreach ($profile_mandatory as $key) {
				if( empty( $_POST['basics'][$key] ) ){
					 $json['type'] 		= 'error';
					 $json['message'] 	= $employer_required[$key];        
					 wp_send_json($json);
				}
			 }
		}
		
		
		$company_name	= '';
		if( function_exists('fw_get_db_settings_option')  ){
			$company_name	= fw_get_db_settings_option('company_name', $default_value = null);
		}

		$company_job_title	= '';
		if( function_exists('fw_get_db_settings_option')  ){
			$company_job_title	= fw_get_db_settings_option('company_job_title', $default_value = null);
		}

        //Form data
        $first_name = !empty($_POST['basics']['first_name']) ? sanitize_text_field($_POST['basics']['first_name']) : '';
        $last_name  = !empty($_POST['basics']['last_name'] ) ? sanitize_text_field($_POST['basics']['last_name']) : '';
        $tag_line   = !empty($_POST['basics']['tag_line'] ) ? sanitize_text_field( $_POST['basics']['tag_line'] ) : '';
		$content    = !empty($_POST['basics']['content'] ) ? wp_kses_post( $_POST['basics']['content'] ) : '';   
		    
        $address    = !empty( $_POST['basics']['address'] ) ? $_POST['basics']['address'] : '';
        $country    = !empty( $_POST['basics']['country'] ) ? $_POST['basics']['country'] : '';
        $latitude   = !empty( $_POST['basics']['latitude'] ) ? $_POST['basics']['latitude'] : '';
        $longitude  = !empty( $_POST['basics']['longitude'] ) ? $_POST['basics']['longitude'] : '';
		
		$employees  = !empty( $_POST['employees'] ) ? $_POST['employees'] : '';
		$department  = !empty( $_POST['department'] ) ? $_POST['department'] : '';
		
		$display_name  = !empty( $_POST['basics']['display_name'] ) ? $_POST['basics']['display_name'] : '';
		$brochures     = !empty( $_POST['basics']['brochures'] ) ? $_POST['basics']['brochures'] : array();
		
		//Update user meta
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
		
		if( !empty( $display_name ) ) {
			$post_title	= $display_name;
			$user_info	= array( 'ID' => $user_id, 'display_name' => $display_name );
			wp_update_user( $user_info );
		} else {
			$post_title	= esc_html( get_the_title( $post_id ));
		}

        //Update Freelancer Post        
        $freelancer_user = array(
            'ID'           => $post_id,
            'post_title'   => $post_title,
            'post_content' => $content,
        );

        // Update the post into the database
        wp_update_post( $freelancer_user );
		
        update_post_meta($post_id, '_tag_line', $tag_line);
        update_post_meta($post_id, '_address', $address);
        update_post_meta($post_id, '_country', $country);
        update_post_meta($post_id, '_latitude', $latitude);
        update_post_meta($post_id, '_longitude', $longitude);
		update_post_meta($post_id, '_employees', $employees);
		
		if( !empty( $department ) ){
			$department_term = get_term_by( 'term_id', $department, 'department' );
			if( !empty( $department_term ) ){
				wp_set_post_terms( $post_id, $department, 'department' );
				update_post_meta($post_id, '_department', $department_term->slug);
			}
		}
		
        //Profile avatar
        $profile_avatar = array();
        if( !empty( $_POST['basics']['avatar']['attachment_id'] ) ){
            $profile_avatar = $_POST['basics']['avatar'];
        } else {                                
            if( !empty( $_POST['basics']['avatar'] ) ){
                $profile_avatar = workreap_temp_upload_to_media($_POST['basics']['avatar'], $post_id);
            }
        }
		
		//delete prevoius attachment ID
		$pre_attachment_id = get_post_thumbnail_id($post_id);
		if ( !empty($pre_attachment_id) && !empty( $profile_avatar['attachment_id'] ) && intval($pre_attachment_id) != intval($profile_avatar['attachment_id'])) {
			wp_delete_attachment($pre_attachment_id, true);
		}
		
		//update thumbnail
		if (!empty($profile_avatar['attachment_id'])) {
			delete_post_thumbnail($post_id);
			set_post_thumbnail($post_id, $profile_avatar['attachment_id']);
		} else {
			wp_delete_attachment( $pre_attachment_id, true );
		}   

        //Profile avatar
        $profile_banner = array();
        if( !empty( $_POST['basics']['banner']['attachment_id'] ) ){
            $profile_banner = $_POST['basics']['banner'];
        } else {                                
            if( !empty( $_POST['basics']['banner'] ) ){
                $profile_banner = workreap_temp_upload_to_media($_POST['basics']['banner'], $post_id);
            }else{
				$banner_image       = fw_get_db_post_option($post_id, 'banner_image', true);
				if(!empty($banner_image['attachment_id'])){
					wp_delete_attachment( $banner_image['attachment_id'], true );
				}
			}
        }        

        //Set country for unyson
        $locations = get_term_by( 'slug', $country, 'locations' );
        $location = array();
        if( !empty( $locations ) ){
            $location[0] = $locations->term_id;
			wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
        }
        //Fw Options
		$fw_options = array();
		if( !empty($phone_setting) && $phone_setting == 'enable') {
			$user_phone_number  = !empty( $_POST['basics']['user_phone_number'] ) ? $_POST['basics']['user_phone_number'] : '';
			$fw_options['user_phone_number']           = $user_phone_number;
		}
		if(!empty($company_name) && $company_name === 'enable') { 
			$company_name  = !empty( $_POST['basics']['company_name'] ) ? $_POST['basics']['company_name'] : '';
			$fw_options['company_name']           = $company_name;
		}
		if(!empty($company_job_title) && $company_job_title === 'enable') { 
			$job_title  = !empty( $_POST['basics']['company_name_title'] ) ? $_POST['basics']['company_name_title'] : '';
			$fw_options['company_job_title']           = $job_title;
		}
		
		$socialmediaurls	= array();
		if( function_exists('fw_get_db_settings_option')  ){
			$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
		}
		
		$brochure_attachemnts	= array();
		if( !empty( $brochures ) ) {
			foreach ( $brochures as $key => $value ) {
				if( !empty( $value['attachment_id'] ) ){
					$brochure_attachemnts[] = $value;
				} else{
					$brochure_attachemnts[] = workreap_temp_upload_to_media($value, $post_id,true);
				} 	
			}                
		}

		$socialmediaurl 		= !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';
		if(!empty($socialmediaurl) && $socialmediaurl  ==='enable'){
			$social_settings    	= function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('yes') : array();
			if(!empty($social_settings)) {
				foreach($social_settings as $key => $val ) {
					$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
					if( !empty($enable_value) && $enable_value === 'enable' ){
						$social_val	= !empty($_POST['basics'][$key]) ? esc_attr($_POST['basics'][$key]) : '';
						$fw_options[$key]           = $social_val;
					}
				}
			}
		}
		
		$fw_options['first_name']         = $first_name;
		$fw_options['last_name']          = $last_name;
        $fw_options['tag_line']           = $tag_line;
        $fw_options['address']            = $address;
        $fw_options['longitude']          = $longitude;
        $fw_options['latitude']           = $latitude;
        $fw_options['country']            = $location;
		$fw_options['department']         = array( $department );
		$fw_options['no_of_employees']    = $employees;
        $fw_options['banner_image']       = $profile_banner;
        $fw_options['brochures']       	  = $brochure_attachemnts;
		
        //Update User Profile
        fw_set_db_post_option($post_id, null, $fw_options);
		
		//child theme : update extra settings
		do_action('workreap_update_employer_profile_settings',$_POST);

		
        $json['type']    = 'success';
        $json['message'] = esc_html__('Settings Updated.', 'workreap');        
        wp_send_json($json);
    }
            
    add_action('wp_ajax_workreap_update_employer_profile', 'workreap_update_employer_profile');
}

/**
 * delete account
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_delete_account' ) ) {

	function workreap_delete_account() {
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$post_id	= workreap_get_linked_profile_id($current_user->ID);
		$user 		= wp_get_current_user(); //trace($user);
		$json 		= array();

		$required = array(
            'password'   	=> esc_html__('Password is required', 'workreap'),
            'retype'  		=> esc_html__('Retype your password', 'workreap'),
            'reason' 		=> esc_html__('Select reason to delete your account', 'workreap'),
        );

        foreach ($required as $key => $value) {
           if( empty( $_POST['delete'][$key] ) ){
            $json['type'] = 'error';
            $json['message'] = $value;        
            wp_send_json($json);
           }
        }
		
		if (empty($_POST['delete']['password']) || empty($_POST['delete']['retype'])) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Please add your password and retype password.', 'workreap');
            wp_send_json( $json );
        }
		
		$user_name 	 = workreap_get_username($user->data->ID);
		$user_email	 = $user->user_email;
		$is_password = wp_check_password($_POST['delete']['password'], $user->user_pass, $user->data->ID);
		
		$account_types_permissions	= '';
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
		}
	
		if( $is_password ){
			if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
				$switch_user_id	= get_user_meta($current_user->ID, 'switch_user_id', true); 
				$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';
				
				if( !empty($switch_user_id) ){
					if ( ! delete_user_meta($switch_user_id,'switch_user_id') ) {
						$json['type'] = 'error';
						$json['message'] = esc_html__('Ooops! Error while deleting this information!', 'workreap');
						wp_send_json( $json );
					}
				}
			}
			wp_delete_user($user->data->ID);
			wp_delete_post($post_id,true);
			extract($_POST['delete']);
			$reason		 = workreap_get_account_delete_reasons($reason);
			
			//Send email to users
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapDeleteAccount')) {
					$email_helper 					= new WorkreapDeleteAccount();
					$emailData 						= array();
					$emailData['username'] 			= esc_html( $user_name );
					$emailData['reason'] 			= sanitize_textarea_field( $reason );
					$emailData['email'] 			= esc_html( $user_email );
					$emailData['description'] 		= sanitize_textarea_field( $description );
					$email_helper->send($emailData);
				}
			}

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('You account has been deleted.', 'workreap');
			$json['redirect'] 	= esc_url(home_url('/'));
			wp_send_json( $json );
		} else{
			$json['type'] = 'error';
			$json['message'] = esc_html__('Password doesn\'t match', 'workreap');
			wp_send_json( $json );
		}
	}

	add_action( 'wp_ajax_workreap_delete_account', 'workreap_delete_account' );
}

 
/**
 * Update User Email
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_change_user_email')) {

    function workreap_change_user_email() {
        global $current_user,$wpdb;
        $user_identity = $current_user->ID;
        $json = array();
		$useremail = !empty($_POST['useremail']) ? $_POST['useremail'] : '';
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
        //security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		if (!is_email( $useremail )) {
            $json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Email field is invalid', 'workreap');
            wp_send_json( $json );
		}
		
		$account_types_permissions	= '';
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
		}
		$switch_user_id	= get_user_meta($user_identity, 'switch_user_id', true); 
		$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';

		if( !empty($switch_user_id) && !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
			$user_details	= get_userdata($switch_user_id);
			if(!empty($user_details->user_email) && $user_details->user_email == $useremail){
				$query	= "UPDATE ".$wpdb->prefix."users SET `user_email` = '".$useremail."'  
							WHERE ID='".$user_identity."'";

					$user_update = $wpdb->query(
						$wpdb->prepare($query)
					);
					$json['type'] = 'success';
					$json['message'] = esc_html__('Your email has been updated', 'workreap');
					wp_send_json($json);
			} else {
				$user_data = wp_update_user(array('ID' => $current_user->ID, 'user_email' => $useremail));
				if (!is_wp_error($user_data)) {
					$query	= "UPDATE ".$wpdb->prefix."users SET `user_email` = '".$useremail."'  
							WHERE ID='".$switch_user_id."'";

					$user_update = $wpdb->query(
						$wpdb->prepare($query)
					);
					$json['type'] = 'success';
					$json['message'] = esc_html__('Your email has been updated', 'workreap');
					wp_send_json($json);
				} else {
					$error_string = $user_data->get_error_message();
					$json['type'] = 'error';
					if(!empty($error_string)){
						$json['message'] = $error_string;
					}else{
						$json['message'] = esc_html__('Error occurred', 'workreap');
					}
					wp_send_json($json);
				}
			}
		} else {
		
			$user_data = wp_update_user(array('ID' => $current_user->ID, 'user_email' => $useremail));

			if (is_wp_error($user_data)) {
				$error_string = $user_data->get_error_message();
				$json['type'] = 'error';
				if(!empty($error_string)){
					$json['message'] = $error_string;
				}else{
					$json['message'] = esc_html__('Error occurred', 'workreap');
				}
				wp_send_json($json);
			}else {
				$json['type'] = 'success';
				$json['message'] = esc_html__('Your email has been updated', 'workreap');
			}

			wp_send_json( $json );
		}
    }

    add_action('wp_ajax_workreap_change_user_email', 'workreap_change_user_email');
}

/**
 * Update User Password
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_change_user_password')) {

    function workreap_change_user_password() {
        global $current_user;
        $user_identity = $current_user->ID;
        $json = array();
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
        //security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$password		= sanitize_text_field ( $_POST['password'] );
		$new_password	= sanitize_text_field ( $_POST['retype'] );
		
		if ( empty($password) || empty($new_password) ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Old password or new password should not be empty', 'workreap');
			wp_send_json( $json );
		}
		
		if ( !empty($password) && !empty($new_password) && $password == $new_password ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('New password should be different from previous password.', 'workreap');
			wp_send_json( $json );
		}
		
		if (!empty($new_password)) {
			do_action('workreap_strong_password_validation',$new_password);
		}
		
        $user 			= wp_get_current_user(); //trace($user);
        $is_password 	= wp_check_password($password, $user->user_pass, $user->data->ID);

		$account_types_permissions	= '';
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
		}

        if ($is_password) {

			$switch_user_id	= get_user_meta($user_identity, 'switch_user_id', true); 
			$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';

			if( !empty($switch_user_id) && !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
				wp_update_user(array('ID' => $switch_user_id, 'user_pass' => sanitize_text_field($new_password)));
			}
            wp_update_user(array('ID' => $user_identity, 'user_pass' => sanitize_text_field($new_password)));
			$json['type'] = 'success';
			$json['message'] = esc_html__('Password Updated.', 'workreap');
        } else {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Old Password doesn\'t matched with the existing password', 'workreap');
        }

       wp_send_json( $json );
    }

    add_action('wp_ajax_workreap_change_user_password', 'workreap_change_user_password');
}

/**
 * Save account settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_save_account_settings')) {

    function workreap_save_account_settings() {
        global $current_user;
        $user_identity   = $current_user->ID;
		$link_id		 = workreap_get_linked_profile_id( $user_identity );
		$user_type	 	 = apply_filters('workreap_get_user_type', $user_identity );
        $json = array();
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($link_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		//update settings
		$settings		 = workreap_get_account_settings($user_type);
		if( !empty( $settings ) ){
			foreach( $settings as $key => $value ){
				$save_val 	= !empty( $_POST['settings'][$key] ) ? $_POST['settings'][$key] : '';
				$db_val 	= !empty( $save_val ) ?  $save_val : 'off';
				update_post_meta($link_id, $key, $db_val);
			}
		}

        $json['type'] = 'success';
		$json['message'] = esc_html__('Settings Updated.', 'workreap');

        wp_send_json( $json );
    }

    add_action('wp_ajax_workreap_save_account_settings', 'workreap_save_account_settings');
}
/**
 * Freelancer request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_milstone_request' ) ) {

	function workreap_milstone_request() {
		global $current_user;
		$proposal_id		= !empty($_POST['id']) ? intval($_POST['id']) : '';
		$project_id			= get_post_meta($proposal_id, '_project_id', true);
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($project_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$proposed_amount  	= get_post_meta($proposal_id, '_amount', true);

		update_post_meta( $proposal_id, '_proposal_status', 'pending' );
		update_post_meta( $proposal_id, '_proposal_type', 'milestone' );
		
		$freelancer_id				= get_post_field('post_author', $proposal_id);
		$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
		$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile);
		$employer_id				= get_post_field('post_author', $project_id);
		$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
		$employer_name 				= workreap_get_username('', $employer_linked_profile);
		$employer_link 				= esc_url(get_the_permalink($employer_linked_profile));
		
		$project_title				= get_the_title($project_id);
		$project_link				= get_the_permalink($project_id);

		$proposed_duration  		= get_post_meta($proposal_id, '_proposed_duration', true);
		$duration_list				= worktic_job_duration_list();
		$duration					= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';

		$profile_id		= workreap_get_linked_profile_id($freelancer_linked_profile, 'post');
		$user_email 	= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';

		//Send email to freelancer
		if (class_exists('Workreap_Email_helper')) {
			if (class_exists('WorkreapMilestoneRequest')) {
				$email_helper = new WorkreapMilestoneRequest();
				$emailData = array();
				
				$emailData['freelancer_link'] 	= get_the_permalink( $freelancer_linked_profile );
				$emailData['freelancer_name'] 	= esc_html( $hired_freelancer_title);
				$emailData['employer_name'] 	= esc_html( $employer_name);
				$emailData['employer_link'] 	= esc_html( $employer_link);
				$emailData['project_title'] 	= esc_html( $project_title);
				$emailData['project_link'] 		= esc_html( $project_link);
				$emailData['proposal_amount'] 	= workreap_price_format($proposed_amount, 'return');
				$emailData['proposal_duration'] = esc_html( $duration);
				$emailData['email_to'] 			= esc_html( $user_email);

				$email_helper->send_milestone_request_email($emailData);
				
				
				//Push notification
				$push	= array();
				$push['freelancer_id']		= $profile_id;
				$push['employer_id']		= $employer_id;
				$push['project_id']			= $project_id;
				$push['type']				= 'milestone_send_request';

				$push['%freelancer_link%']	= $emailData['freelancer_link'];
				$push['%freelancer_name%']	= $emailData['freelancer_name'];
				$push['%employer_name%']	= $emailData['employer_name'] ;
				$push['%employer_link%']	= $emailData['employer_link'];
				$push['%project_title%']	= $emailData['project_title'];
				$push['%project_link%']		= $emailData['project_link'];

				do_action('workreap_user_push_notify',array($profile_id),'','pusher_ml_rec_content',$push);
				
			}
		}

        $json['type'] = 'success';
		$json['message'] = esc_html__('Request sent successfully to the freelancer.', 'workreap');

        wp_send_json( $json );

	}
	add_action('wp_ajax_workreap_milstone_request', 'workreap_milstone_request');
}

/**
 * Freelancer request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_cancelled_milestone' ) ) {

	function workreap_cancelled_milestone() {
		global $current_user;
		$proposal_id			= !empty($_POST['proposal_id']) ? intval($_POST['proposal_id']) : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($proposal_id);
		}; //if user is not logged in then prevent

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		

		
		$project_id				= get_post_meta($proposal_id, '_project_id', true);
		$cancelled_reason		= !empty($_POST['cancelled_reason']) ? ($_POST['cancelled_reason']) : '';
		$json					= array();
		$update_post			= array();

		if(empty($proposal_id)){
			$json['type'] = 'error';
            $json['message'] = esc_html__('Proposal ID is required', 'workreap');
            wp_send_json( $json );
		}

		if(empty($cancelled_reason)){
			$json['type'] = 'error';
            $json['message'] = esc_html__('Cancel reason is required', 'workreap');
            wp_send_json( $json );
		}

		if(!empty($proposal_id) && !empty($cancelled_reason)) {
			update_post_meta( $proposal_id, '_cancelled_reason', $cancelled_reason );
			update_post_meta( $proposal_id, '_proposal_status', 'cancelled' );
			update_post_meta( $proposal_id, '_cancelled_user_id', $current_user->ID );
			$update_post	= array(
								'ID'    		=>  $proposal_id,
								'post_status'   =>  'cancelled'
							);	
			wp_update_post($update_post);

			$freelancer_id				= get_post_field('post_author', $proposal_id);
			$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
			$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile );
			$freelancer_link 		    = esc_url(get_the_permalink($freelancer_linked_profile));


			$employer_id				= get_post_field('post_author', $project_id);
			$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
			$employer_name 				= workreap_get_username('', $employer_linked_profile );
			$employer_link				= get_the_permalink($employer_linked_profile);
			
			$project_title				= get_the_title($project_id);
			$project_link				= get_the_permalink($project_id);

			$profile_id					= workreap_get_linked_profile_id($employer_linked_profile, 'post');
			$user_email 				= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';

			//Send email to employer
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapMilestoneRequest')) {
					$email_helper = new WorkreapMilestoneRequest();
					$emailData = array();
					
					$emailData['freelancer_name'] 	= esc_html( $hired_freelancer_title);
					$emailData['freelancer_link'] 	= esc_html( $freelancer_link);
					$emailData['employer_name'] 	= esc_html( $employer_name);
					$emailData['employer_link'] 	= esc_html( $employer_link);
					$emailData['project_title'] 	= esc_html( $project_title);
					$emailData['project_link'] 		= esc_html( $project_link);
					$emailData['reason'] 			= esc_html( $cancelled_reason);

					$emailData['email_to'] 			= esc_html( $user_email);

					$email_helper->send_milestone_request_rejected_email($emailData);
					
					//Push notification
					$push	= array();
					$push['freelancer_id']		= $freelancer_id;
					$push['employer_id']		= $employer_id;
					$push['project_id']			= $project_id;
					$push['type']				= 'milestone_cancelled';

					$push['%freelancer_link%']	= $emailData['freelancer_link'];
					$push['%freelancer_name%']	= $emailData['freelancer_name'];
					$push['%employer_name%']	= $emailData['employer_name'] ;
					$push['%employer_link%']	= $emailData['employer_link'];
					$push['%project_title%']	= $emailData['project_title'];
					$push['%project_link%']		= $emailData['project_link'];
					$push['%replace_reason%']	= wp_strip_all_tags($emailData['reason']);
					
					do_action('workreap_user_push_notify',array($employer_id),'','pusher_ml_req_rej_content',$push);
					
				}
			}
			
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Settings Updated.', 'workreap');
			wp_send_json( $json );
		}
	}
	add_action('wp_ajax_workreap_cancelled_milestone', 'workreap_cancelled_milestone');
}

/**
 * Freelancer request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_milestone_completed' ) ) {

	function workreap_milestone_completed() {
		$json 			= array();
		$current_date 	= current_time('mysql');
		$milestone_id	= !empty($_POST['id']) ? intval($_POST['id']) : '';
		$completed_date	= date('Y-m-d H:i:s', strtotime($current_date));
		$milestone_title 	= get_the_title($milestone_id);
		$project_id 		= get_post_meta($milestone_id, '_project_id', true);
		$freelancer_id 		= get_post_meta($project_id, '_freelancer_id', true);
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($project_id);
		}; //if user is not logged in then prevent

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$freelancer_name 	= workreap_get_username('', $freelancer_id);
		$profile_id			= workreap_get_linked_profile_id($freelancer_id, 'post');	
		$user_email 		= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';
		
		$update		= array( 'status' 		=> 'completed' );
		$where		= array( 'milestone_id' => $milestone_id );
		workreap_update_earning( $where, $update, 'wt_earnings');

		// complete service
		$order_id			= get_post_meta($milestone_id,'_order_id',true);
		if ( class_exists('WooCommerce') && !empty( $order_id )) {
			$order = wc_get_order( intval($order_id ) );
			if( !empty( $order ) ) {
				$order->update_status( 'completed' );
			}
		}

		update_post_meta( $milestone_id, '_status', 'completed' );
		update_post_meta( $milestone_id, '_completed_date', $completed_date );
		
		$project_title		= get_the_title($project_id);
		$project_link		= get_the_permalink($project_id);
		
		

		//Send email to freelancer
		if (class_exists('Workreap_Email_helper')) {
			if (class_exists('WorkreapMilestoneRequest')) {
				$email_helper = new WorkreapMilestoneRequest();
				$emailData = array();
				
				$emailData['freelancer_name'] 	= esc_html( $freelancer_name);
				$emailData['milestone_title'] 	= esc_html( $milestone_title);
				$emailData['project_title'] 	= esc_html( $project_title);
				$emailData['project_link'] 		= esc_html( $project_link);
				$emailData['email_to'] 			= esc_html( $user_email);

				$email_helper->send_completed_milestone_to_freelancer_email($emailData);
				
				//Push notification
				$push	= array();
				$push['freelancer_id']		= $profile_id;
				$push['project_id']			= $project_id;
				$push['type']				= 'milestone_completed';

				$push['%freelancer_name%']	= $emailData['freelancer_name'];
				$push['%milestone_title%']	= $emailData['milestone_title'];
				$push['%project_title%']	= $emailData['project_title'];
				$push['%project_link%']		= $emailData['project_link'];

				$push['%replace_milestone_title%']	= $emailData['milestone_title'];

				do_action('workreap_user_push_notify',array($profile_id),'','pusher_ml_completed_content',$push);

			}
		}

		$json['type'] 		= 'success';
		$json['message'] 	= esc_html__('Milestone is completed successfully', 'workreap');
		wp_send_json($json);

	}
	add_action('wp_ajax_workreap_milestone_completed', 'workreap_milestone_completed');
}

/**
 * Freelancer request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_milstone_checkout' ) ) {

	function workreap_milstone_checkout() {
		global $woocommerce,$current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$milestone_id	= !empty($_POST['id']) ? intval($_POST['id']) : '';
		$price_symbol	= workreap_get_current_currency();

		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$bk_settings 		= fw_get_db_settings_option('hiring_payment_settings');
		}

		$product_id	= workreap_get_hired_product_id();
		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {

				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $current_user->ID;
				$job_id				= get_post_meta($milestone_id ,'_project_id',true);
				$price				= get_post_meta($milestone_id ,'_price',true);
				$admin_shares 		= 0.0;
				$freelancer_shares 	= 0.0;

				if( !empty( $price ) ){
					$service_fee		= workreap_commission_fee($price,'milestone',$job_id);

					if( !empty( $service_fee ) ){
						$admin_shares       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
						$freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
					} else{
						$admin_shares       = 0.0;
						$freelancer_shares  = $price;
					}

					$admin_shares 		= number_format($admin_shares,2,'.', '');
					$freelancer_shares 	= number_format($freelancer_shares,2,'.', '');
				}

				$cart_meta['project_id']		= $job_id;
				$cart_meta['price']				= $price;
				$cart_meta['milestone_id']		= $milestone_id;

				//hired freelancers
				$proposal_id				= get_post_meta( $job_id, '_proposal_id', true);
				$hired_freelance_id			= get_post_field('post_author',$proposal_id);
				$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'price'				=> $price_symbol['symbol'].$price,
					'payment_type'     	=> 'milestone',
					'admin_shares'     	=> $admin_shares,
					'freelancer_shares' => $freelancer_shares,
					'employer_id' 		=> $current_user->ID,
					'freelancer_id' 	=> $hired_freelance_id,
					'current_project' 	=> $job_id,
				);

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
				
				if( !empty( $bk_settings['gadget'] ) && $bk_settings['gadget'] === 'enable' ) {
					$json['type'] 			= 'checkout';
					$json['message'] 		= esc_html__('Please wait you are redirecting to the checkout page.', 'workreap');
					$json['checkout_url']	= wc_get_checkout_url();
					wp_send_json($json);
				}else{
					workreap_create_woocommerce_order($milestone_id,$proposal_id);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please install WooCommerce plugin to process this order', 'workreap');
				wp_send_json($json);
			}
		} else{
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Hiring settings is missing, please contact to administrator.', 'workreap');
			wp_send_json($json);
		}
		

	}
	add_action('wp_ajax_workreap_milstone_checkout', 'workreap_milstone_checkout');
}



/**
 * Freelancer request approved
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_milstone_request_approved' ) ) {

	function workreap_milstone_request_approved() {
		$proposal_id		= !empty($_POST['id']) ? intval($_POST['id']) : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($proposal_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$json = array();
		$meta_query_args	= array();
		$status				= !empty($_POST['status']) ? $_POST['status'] : '';
		
		if(!empty($status) && $status === 'approved' ){
			$args 			= array(
								'posts_per_page' 	=> -1,
								'post_type' 		=> 'wt-milestone',
								'suppress_filters' 	=> false
							);
			$meta_query_args[] = array(
								'key' 		=> '_propsal_id',
								'value' 	=> $proposal_id,
								'compare' 	=> '='
							);
			$query_relation 	= array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($args);
			
			while ($query->have_posts()) : $query->the_post();
				global $post;
				update_post_meta( $post->ID, '_status', 'pay_now' );
			endwhile;
			
			wp_reset_postdata();

			$project_id	= get_post_meta( $proposal_id, '_project_id', true );
			if(!empty($proposal_id) && !empty($project_id)){
				workreap_hired_freelancer_after_payment($project_id, $proposal_id);
			}

			$freelancer_id				= get_post_field('post_author', $proposal_id);
			$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
			$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile);
			$freelancer_link 		    = esc_url(get_the_permalink($freelancer_linked_profile));


			$employer_id				= get_post_field('post_author', $project_id);
			$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
			$employer_name 				= workreap_get_username('', $employer_linked_profile );
			$employer_link				= get_the_permalink($employer_linked_profile);
			
			$project_title				= get_the_title($project_id);
			$project_link				= get_the_permalink($project_id);

			$profile_id					= workreap_get_linked_profile_id($employer_linked_profile, 'post');
			$user_email 				= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';
		}
		
		update_post_meta( $proposal_id, '_proposal_status', $status );

		//Send email to freelancer
		if (class_exists('Workreap_Email_helper')) {
			if (class_exists('WorkreapMilestoneRequest')) {
				$email_helper = new WorkreapMilestoneRequest();
				$emailData = array();
				
				$emailData['freelancer_name'] 	= esc_html($hired_freelancer_title);
				$emailData['freelancer_link'] 	= esc_html($freelancer_link);
				$emailData['employer_name'] 	= esc_html($employer_name);
				$emailData['employer_link'] 	= esc_html($employer_link);
				$emailData['project_title'] 	= esc_html($project_title);
				$emailData['project_link'] 		= esc_html($project_link);

				$emailData['email_to'] 			= esc_html( $user_email);

				$email_helper->send_milestone_request_approved_email($emailData);
				
				//Push notification
				$push	= array();
				$push['freelancer_id']		= $freelancer_id;
				$push['employer_id']		= $employer_id;
				$push['project_id']			= $project_id;
				$push['type']				= 'milestone_request_approved';
				

				$push['%freelancer_link%']	= $emailData['freelancer_link'];
				$push['%freelancer_name%']	= $emailData['freelancer_name'];
				$push['%employer_name%']	= $emailData['employer_name'] ;
				$push['%employer_link%']	= $emailData['employer_link'];
				$push['%project_title%']	= $emailData['project_title'];
				$push['%project_link%']		= $emailData['project_link'];
				
				do_action('workreap_user_push_notify',array($employer_id),'','pusher_ml_req_appr_content',$push);
			}
		}
		
		$json['type'] 		= 'success';
		$json['message'] 	= esc_html__('You have successfully update proposal request.', 'workreap');
		wp_send_json($json);

	}
	add_action('wp_ajax_workreap_milstone_request_approved', 'workreap_milstone_request_approved');
}
/**
 * Saev milestone
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_save_milstone' ) ) {

	function workreap_save_milstone() {
		global $current_user;
		$proposal_id	= !empty($_POST['id']) ? intval($_POST['id']) : '';
		$milstone_id	= !empty($_POST['milestone_id']) ? intval($_POST['milestone_id']) : '';
		$project_id		= !empty($proposal_id) ? get_post_meta($proposal_id,'_project_id',true) : '';
		$price			= !empty($_POST['price']) ? $_POST['price'] : '';
		$due_date		= !empty($_POST['due_date']) ? $_POST['due_date'] : '';
		$title			= !empty($_POST['title']) ? $_POST['title'] : '';
		$description	= !empty($_POST['description']) ? $_POST['description'] : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($project_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		
		$json	= array();
		$required = array(
			'id'   				=> esc_html__('Proposal ID is required', 'workreap'),
			'title'   			=> esc_html__('Milestone title is required', 'workreap'),
			'due_date'  		=> esc_html__('Due date is required', 'workreap'),
			'price'  			=> esc_html__('Price is required', 'workreap')
		);
		
		foreach ($required as $key => $value) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
		}

		$proposal_price					= get_post_meta( $proposal_id, '_amount', true );
		$proposal_price					= !empty($proposal_price) ? $proposal_price : 0;
		$total_milestone_price			= workreap_get_milestone_statistics($proposal_id,array('pending','publish'));
		$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
		$remaning_price	= ($proposal_price) > ($total_milestone_price) ? $proposal_price - $total_milestone_price : 0;
		
		$remaning_price	= (string) $remaning_price;
		
		if( ( $price > $remaning_price) && empty($milstone_id) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Price is greater then remaining price','workreap');       
			wp_send_json($json);
		} else if(!empty($milstone_id)){
			$old_price	= get_post_meta($milstone_id,'_price',true);
			$old_price	= !empty($old_price) ? $old_price : 0;
			$new_price	= $old_price+ $remaning_price;
			
			if( empty($remaning_price) && $price > $old_price ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Price is greater then remaining price','workreap');        
				wp_send_json($json);

			} else if($price > $new_price ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Price is greater then remaining price','workreap');        
				wp_send_json($json);
			}
		}

		if(empty($milstone_id)) {
			$milestone_post = array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_status'   => 'pending',
				'post_content'  => $description,
				'post_author'   => $current_user->ID,
				'post_type'     => 'wt-milestone',
			);

			$milstone_id    		= wp_insert_post( $milestone_post );
			update_post_meta( $milstone_id, '_status', 'pending' );
		} else if( !empty($milstone_id) ) {
			$milestone_post = array(
				'ID'			=> $milstone_id,
				'post_title'    => wp_strip_all_tags( $title ),
				'post_content'  => $description,
				'post_type'     => 'wt-milestone',
			);
			
			wp_update_post( $milestone_post );
		}
		
		if(!empty($milstone_id )){
			$freelancer_id			= get_post_field('post_author', $proposal_id);
			
			$fw_options	= array();
			$fw_options['projects']	= $project_id;
			$fw_options['price']	= $price;
			$fw_options['due_date']	= $due_date;
			fw_set_db_post_option($milstone_id, null, $fw_options);

			update_post_meta($milstone_id,'_freelancer_id',$freelancer_id);
			update_post_meta($milstone_id,'_propsal_id',$proposal_id);
			update_post_meta($milstone_id,'_project_id',$project_id);
			update_post_meta($milstone_id,'_price',$price);
			update_post_meta($milstone_id,'_due_date',$due_date);

		}

		if(!empty($milstone_id)){
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('You are successfully update/added Milestone.','workreap');
			wp_send_json( $json );
		} else {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('There are some errors, please try again later', 'workreap');
            wp_send_json( $json );
		}

	}
	add_action('wp_ajax_workreap_save_milstone', 'workreap_save_milstone');

}

/**
 * Post a job
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_post_job' ) ) {

	function workreap_post_job() {
		global $current_user;
		$hide_map 		= 'show';
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		$linked_profile  	= workreap_get_linked_profile_id($current_user->ID);
		
		do_action('workreap_check_post_author_identity_status', $linked_profile); //check if user identity is verified
		
		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		if (function_exists('fw_get_db_settings_option')) {
			$hide_map	= fw_get_db_settings_option('hide_map');
			$job_status	= fw_get_db_settings_option('job_status');
			$remove_freelancer_type   	= fw_get_db_settings_option('remove_freelancer_type');
			$remove_english_level   	= fw_get_db_settings_option('remove_english_level');
			$remove_project_level   	= fw_get_db_settings_option('remove_project_level');
			$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');
			$remove_location_job   		= fw_get_db_settings_option('remove_location_job');
			$project_mandatory			= fw_get_db_settings_option('project_required');
		}
		
		$remove_location_job			= !empty($remove_location_job) ? $remove_location_job : 'no';
		$remove_project_level 			= !empty($remove_project_level) ? $remove_project_level : 'no';
		$remove_project_duration 		= !empty($remove_project_duration) ? $remove_project_duration : 'no';
		$job_status	=  !empty( $job_status ) ? $job_status : 'publish';

		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id	= workreap_get_linked_profile_id($current_user->ID);
		do_action('workreap_check_post_author_status', $user_id); //check if user is not blocked or deactive
		
		$json = array();
		$current = !empty($_POST['id']) ? intval($_POST['id']) : '';

		if( apply_filters('workreap_is_job_posting_allowed','wt_jobs', $current_user->ID) === false && empty($current) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You’ve consumed all you points to add new job.','workreap');
			wp_send_json( $json );
		}
		
		if( isset( $hide_map ) && $hide_map === 'show' ){
			$required = array(
				'title'   			=> esc_html__('Job title is required', 'workreap'),
				'project_level'  	=> esc_html__('Project level is required', 'workreap'),
				'project_duration'  => esc_html__('Project duration is required', 'workreap'),
				'english_level'   	=> esc_html__('English level is required', 'workreap'),
				'project_type' 		=> esc_html__('Please select job type.', 'workreap'),
				'categories' 		=> esc_html__('Please select at-least one category', 'workreap'),
				'address'   		=> esc_html__('Address is required', 'workreap'),
				'country'   		=> esc_html__('Country is required', 'workreap'),
			);
		} else{
			$required = array(
				'title'   			=> esc_html__('Job title is required', 'workreap'),
				'project_level'  	=> esc_html__('Project level is required', 'workreap'),
				'project_duration'  => esc_html__('Project duration is required', 'workreap'),
				'english_level'   	=> esc_html__('English level is required', 'workreap'),
				'project_type' 		=> esc_html__('Please select job type.', 'workreap'),
				'categories' 		=> esc_html__('Please select at-least one category', 'workreap'),
				'country'           => esc_html__('Country is required', 'workreap'),
			);
		}

		//remove location
		if(!empty($remove_location_job) && $remove_location_job === 'yes' ){
			unset( $required['address']);
			unset( $required['latitude']);
			unset( $required['longitude']);
			unset( $required['country']);
		}

		$required	= apply_filters('workreap_filter_post_job_fields',$required);
		if(!empty($project_mandatory)) {
			$job_required  = workreap_jobs_required_fields();
			foreach ($project_mandatory as $key) {
				if( empty( $_POST['job'][$key] ) ){
				 $json['type'] 		= 'error';
				 $json['message'] 	= $job_required[$key];        
				 wp_send_json($json);
				}
			 }
		}

		//remove english level
		if(!empty($remove_english_level) && $remove_english_level === 'yes' ){
			unset( $required['english_level']);
		}
		
		//remove project level
		if(!empty($remove_project_level) && $remove_project_level === 'yes' ){
			unset( $required['project_level']);
		}
		
		//remove project duration
		if(!empty($remove_project_duration) && $remove_project_duration === 'yes' ){
			unset( $required['project_duration']);
		}
		
		if (function_exists('fw_get_db_settings_option')) {
			$job_option_setting         = fw_get_db_settings_option('job_option', $default_value = null);
			$multiselect_freelancertype = fw_get_db_settings_option('multiselect_freelancertype', $default_value = null);
			$job_experience_single  	= fw_get_db_settings_option('job_experience_option', $default_value = null);
			$job_price_option           = fw_get_db_settings_option('job_price_option', $default_value = null);
			$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
		}
		
		$multiselect_freelancertype = !empty($multiselect_freelancertype) ?  $multiselect_freelancertype: '';
		$job_price_option 			= !empty($job_price_option) ? $job_price_option : '';
		$job_option_setting 		= !empty($job_option_setting) ? $job_option_setting : '';
		$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

		if(!empty($job_option_setting) && $job_option_setting === 'enable' ){
			$required['job_option']	= esc_html__('Project location type is required', 'workreap');
		}
		
        foreach ($required as $key => $value) {
			if( empty( $_POST['job'][$key] ) ){
				$json['type'] = 'error';
				$json['message'] = $value;        
				wp_send_json($json);
			}
			
			if( $key === 'project_type' 
			   && $_POST['job']['project_type'] === 'hourly' 
			   && empty( floatval( $_POST['job']['hourly_rate'] ) ) 
			){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Per hour rate is required', 'workreap');        
				wp_send_json($json);
			} else if( $key === 'project_type' && $_POST['job']['project_type'] === 'hourly' && empty( $_POST['job']['estimated_hours'] )  ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Estimated hours is required', 'workreap');        
				wp_send_json($json);
			} else if( $key == 'project_type' 
					  && $_POST['job']['project_type'] === 'hourly' 
					  && !empty( floatval ($_POST['job']['max_price']) ) 
					  && floatval ($_POST['job']['max_price'] ) < floatval( $_POST['job']['hourly_rate'] ) 
			){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Maximum project cost should not be less than minimum project cost', 'workreap');        
				wp_send_json($json);
			} else if( $key == 'project_type' 
					  && $_POST['job']['project_type'] === 'fixed' 
					  && !empty( floatval( $_POST['job']['max_price'] ) ) 
					  && floatval( $_POST['job']['max_price'] ) < floatval( $_POST['job']['project_cost'] )
			){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Maximum project cost should not be less than minimum project cost', 'workreap');        
				wp_send_json($json);
			} else if( $key == 'project_type' && $_POST['job']['project_type'] === 'fixed' && empty( floatval( $_POST['job']['project_cost'] ) )  ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Project cost is required', 'workreap');        
				wp_send_json($json);
			}

		}

		//extract the job variables
		extract($_POST['job']);
		$title				= !empty( $title ) ? $title : rand(1,999999);		
		
		if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
			$current = !empty($_POST['id']) ? intval($_POST['id']) : '';
			
			$post_author = get_post_field('post_author', $current);
            $post_id 	 = $current;
            $status 	 = get_post_status($post_id);
			
			if( intval( $post_author ) === intval( $current_user->ID ) ){
				$article_post = array(
					'ID' => $current,
					'post_title' => $title,
					'post_content' => $description,
					'post_status' => $status,
				);

				wp_update_post($article_post);
			} else{
				$json['type'] = 'error';
				$json['message'] = esc_html__('You are not authorized to update this service', 'workreap');
				wp_send_json( $json );
			}
			
			//change status on update
			do_action('workreap_update_post_status_action',$post_id,'project'); //Admin will get an email to publish it
			
			$gallery_old_attachment       = fw_get_db_post_option($current, 'project_documents', true);
			if(!empty($gallery_old_attachment)){
				$gallery_old_attachment 	= wp_list_pluck($gallery_old_attachment,'attachment_id');
			}

		} else{
			//Create Post
			$user_post = array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_status'   => $job_status,
				'post_content'  => $description,
				'post_author'   => $current_user->ID,
				'post_type'     => 'projects',
			);

			$post_id    		= wp_insert_post( $user_post );
			update_post_meta( $post_id, '_featured_job_string',0 );

			//update jobs
			$remaning_jobs		= workreap_get_subscription_metadata( 'wt_jobs',intval($current_user->ID) );
			$remaning_jobs  	= !empty( $remaning_jobs ) ? intval($remaning_jobs) : 0;

			if( !empty( $remaning_jobs ) && $remaning_jobs >= 1 ) {
				$update_jobs	= intval( $remaning_jobs ) - 1 ;
				$update_jobs	= intval($update_jobs);

				$wt_subscription 	= get_user_meta(intval($current_user->ID), 'wt_subscription', true);
				$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();
				$wt_subscription['wt_jobs'] = $update_jobs;
				update_user_meta( intval($current_user->ID), 'wt_subscription', $wt_subscription);
			}
		}

		$expiry_string		= workreap_get_subscription_metadata( 'subscription_featured_string',$current_user->ID );
		if( !empty($expiry_string) ) {
			update_post_meta($post_id, '_expiry_string', $expiry_string);
		}
		
		if( $post_id ){
			//Upload files from temp folder to uploads
			$files              = !empty( $_POST['job']['project_documents'] ) ? $_POST['job']['project_documents'] : array();
			$job_files			= array();
			if( !empty( $files ) ) {
				foreach ( $files as $key => $value ) {
					if( !empty( $value['attachment_id'] ) ){
						$job_files[] = $value;
					} else{
						$job_files[] = workreap_temp_upload_to_media($value, $post_id,true);
					} 	
				} 
	
				$gallery_new_attachment 	= wp_list_pluck($job_files,'attachment_id');
				if(!empty($gallery_old_attachment)){
					foreach($gallery_old_attachment as $key => $delete_media){
						if(!empty($delete_media) && !empty($gallery_new_attachment) && !in_array($delete_media,$gallery_new_attachment)){
							$delete_list[] = $delete_media;
							wp_delete_attachment( $delete_media, true );
						}
					}
				}

			}else{
				if(!empty($gallery_old_attachment) ){
					foreach($gallery_old_attachment as $key => $delete_media){
						if(!empty($delete_media)){
							wp_delete_attachment( $delete_media, true );
						}
					}
				}
			}

			
			$languages               = !empty( $_POST['job']['languages'] ) ? $_POST['job']['languages'] : array();
			$categories              = !empty( $_POST['job']['categories'] ) ? $_POST['job']['categories'] : array();
			$skills              	 = !empty( $_POST['job']['skills'] ) ? $_POST['job']['skills'] : array();
			$expiry_date             = !empty( $_POST['job']['expiry_date'] ) ? $_POST['job']['expiry_date'] : 0;
			$deadline             	 = !empty( $_POST['job']['deadline'] ) ? $_POST['job']['deadline'] : 0;
			
			$make_featured			= false;
			$make_featured_date		= '';
			$is_featured              = !empty( $_POST['job']['is_featured'] ) ? $_POST['job']['is_featured'] : '';
			if( !empty($is_featured) ){
				if( $is_featured === 'on'){
					$is_featured_job	= get_post_meta($post_id,'_featured_job_string',true); 
					if(empty($is_featured_job)){
						$featured_jobs	= workreap_featured_job( $current_user->ID );
						if( $featured_jobs ) {
							$make_featured		= true;
							$make_featured_date	= !empty($expiry_string) ? date('Y-m-d',$expiry_string) : '';

							update_post_meta($post_id, '_featured_job_string', 1);
							$remaning_featured_jobs		= workreap_get_subscription_metadata( 'wt_featured_jobs',intval($current_user->ID) );
							$remaning_featured_jobs  	= !empty( $remaning_featured_jobs ) ? intval($remaning_featured_jobs) : 0;

							if( !empty( $remaning_featured_jobs) && $remaning_featured_jobs >= 1 ) {
								$update_featured_jobs	= intval( $remaning_featured_jobs ) - 1 ;
								$update_featured_jobs	= intval( $update_featured_jobs );
								$wt_subscription 	= get_user_meta(intval($current_user->ID), 'wt_subscription', true);
								$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();
								$wt_subscription['wt_featured_jobs'] = $update_featured_jobs;

								update_user_meta( intval($current_user->ID), 'wt_subscription', $wt_subscription);
							}
						} else{
							update_post_meta( $post_id, '_featured_job_string',0 );
						}
					}
				} else {
					update_post_meta( $post_id, '_featured_job_string',0 );
				}
			} else{
				update_post_meta( $post_id, '_featured_job_string',0 );
			}

			//update langs
			wp_set_post_terms( $post_id, $languages, 'languages' );
			
			//update cats
			wp_set_post_terms( $post_id, $categories, 'project_cat' );
			
			//update skills
			wp_set_post_terms( $post_id, $skills, 'skills' );
			
			//update for keyword search
			$skills_names = wp_get_post_terms( $post_id, 'skills', array( 'fields' => 'names' ) );
			update_post_meta($post_id, '_skills_names', $skills_names); 
			
			//update for keyword search
			$project_cat_list = wp_get_post_terms( $post_id, 'project_cat', array( 'fields' => 'names' ) );
			update_post_meta($post_id, '_categories_names', $project_cat_list); 

			// price range
			if(!empty($job_price_option) && $job_price_option === 'enable' ){
				update_post_meta($post_id, '_max_price', workreap_wmc_compatibility( $max_price));
			}

			// update projec expriences
			if(!empty($job_experience_single['gadget']) && $job_experience_single['gadget'] === 'enable' ){
				$experiences		= !empty( $_POST['job']['experiences'] ) ? $_POST['job']['experiences'] : array();
				wp_set_post_terms( $post_id, $experiences, 'project_experience' );
			}
			
			//update
			update_post_meta($post_id, '_expiry_date', $expiry_date);
			update_post_meta($post_id, '_project_expiry_string', strtotime($expiry_date));
			update_post_meta($post_id, 'deadline', $deadline);
			update_post_meta($post_id, '_project_type', $project_type);
			update_post_meta($post_id, '_project_duration', $project_duration);
			update_post_meta($post_id, '_english_level', $english_level);
			update_post_meta($post_id, 'post_rejected', '');

			update_post_meta($post_id, '_estimated_hours', $estimated_hours);
			update_post_meta($post_id, '_hourly_rate', workreap_wmc_compatibility( $hourly_rate));
			update_post_meta($post_id, '_project_cost', workreap_wmc_compatibility( $project_cost));

			$project_data	= array(); 
			$project_data['gadget']	= !empty( $_POST['job']['project_type'] ) ? $_POST['job']['project_type'] : 'fixed';
			$project_data['hourly']['hourly_rate']		= !empty( $_POST['job']['hourly_rate'] ) ? workreap_wmc_compatibility( $_POST['job']['hourly_rate']) : '';
			$project_data['hourly']['estimated_hours']	= !empty( $_POST['job']['estimated_hours'] ) ? $_POST['job']['estimated_hours'] : '';
			$project_data['fixed']['project_cost']		= !empty( $_POST['job']['project_cost'] ) ? workreap_wmc_compatibility( $_POST['job']['project_cost']) : '';
			$project_data['hourly']['max_price']		= !empty( $_POST['job']['max_price'] ) ? workreap_wmc_compatibility( $_POST['job']['max_price']) : '';
			$project_data['fixed']['max_price']			= !empty( $_POST['job']['max_price'] ) ? workreap_wmc_compatibility( $_POST['job']['max_price']) : '';

			//update location
			$address    = !empty( $_POST['job']['address'] ) ? $_POST['job']['address'] : '';
			$country    = !empty( $_POST['job']['country'] ) ? $_POST['job']['country'] : '';
			$latitude   = !empty( $_POST['job']['latitude'] ) ? $_POST['job']['latitude'] : '';
			$longitude  = !empty( $_POST['job']['longitude'] ) ? $_POST['job']['longitude'] : '';
			
			update_post_meta($post_id, '_address', $address);
			update_post_meta($post_id, '_country', $country);
			update_post_meta($post_id, '_latitude', $latitude);
			update_post_meta($post_id, '_longitude', $longitude);
			

			//Set country for unyson
			$locations = get_term_by( 'slug', $country, 'locations' );
			$location = array();
			if( !empty( $locations ) ){
				$location[0] = $locations->term_id;

				if( !empty( $location ) ){
					wp_set_post_terms( $post_id, $location, 'locations' );
				}

			}

			//update unyson meta
			$fw_options = array();
			
			if(!empty($job_price_option) && $job_price_option === 'enable' ){
				$fw_options['max_price']         	 = workreap_wmc_compatibility( $max_price );
			}

			$freelancer_level	= !empty( $_POST['job']['freelancer_level'] ) ? $_POST['job']['freelancer_level']  : array();
			if(!empty($multiselect_freelancertype) && $multiselect_freelancertype === 'enable' ){
				$fw_options['freelancer_level']      = $freelancer_level;
			} else {
				$freelancer_level					= !empty($freelancer_level[0]) ? $freelancer_level[0] : '';
				$fw_options['freelancer_level'][0]  = $freelancer_level;
			}

			if( !empty($milestone) && $milestone ==='enable' && !empty($project_data['gadget']) && $project_data['gadget'] === 'fixed' ){
				$is_milestone    			= !empty( $_POST['job']['is_milestone'] ) ? $_POST['job']['is_milestone'] : 'off';
				$project_data['fixed']['milestone']  	= $is_milestone;
				update_post_meta($post_id, '_milestone', $is_milestone);
			}

			// update post option
			if( !empty($job_option_setting) && $job_option_setting === 'enable' ){
				$job_option_text						= !empty( $_POST['job']['job_option'] ) ? $_POST['job']['job_option'] : '';
				$fw_options['job_option']    			= $job_option_text;
				update_post_meta($post_id, '_job_option', $job_option_text);
			}

			update_post_meta($post_id, '_freelancer_level', $freelancer_level);
			
			$job_faq_option	= array();
			if( function_exists('fw_get_db_settings_option')  ){
				$job_faq_option	= fw_get_db_settings_option('job_faq_option', $default_value = null);
			}

			if(!empty($job_faq_option) && $job_faq_option == 'yes' ) {
				$faq 					= !empty( $_POST['settings']['faq'] ) ? $_POST['settings']['faq'] : array();
				$fw_options['faq']      = $faq;
			}
			$fw_options['expiry_date']         	 = $expiry_date;
			$fw_options['deadline']         	 = $deadline;
			if(empty($remove_project_level) || $remove_project_level != 'yes' ){
				$fw_options['project_level']         = $project_level;
			}

			$fw_options['featured_post']         = $make_featured;
			$fw_options['featured_expiry']       = $make_featured_date;
			$fw_options['project_type']          = $project_data;
			$fw_options['project_duration']      = $project_duration;
			$fw_options['english_level']         = $english_level;
			$fw_options['show_attachments']      = $show_attachments;
			$fw_options['project_documents']     = $job_files;
			$fw_options['address']            	 = $address;
			$fw_options['longitude']          	 = $longitude;
			$fw_options['latitude']           	 = $latitude;
			$fw_options['country']            	 = $location;

			//Update User Profile
			fw_set_db_post_option($post_id, null, $fw_options);
			
			if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
				$json['type'] 		= 'success';
				$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $current_user->ID, true,'posted');
				$json['message'] 	= esc_html__('Your job has been updated', 'workreap');
			} else{
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapJobPost')) {
						$email_helper = new WorkreapJobPost();
						$emailData 	  = array();

						$employer_name 		= workreap_get_username($current_user->ID);
						$employer_email 	= get_userdata( $current_user->ID )->user_email;
						$employer_profile 	= get_permalink($user_id);
						$job_title 			= esc_html( get_the_title($post_id) );
						$job_link 			= get_permalink($post_id);
						

						$emailData['employer_name'] 	= esc_html( $employer_name );
						$emailData['employer_email'] 	= sanitize_email( $employer_email );
						$emailData['employer_link'] 	= esc_url( $employer_profile );
						$emailData['status'] 			= esc_html( $job_status );
						$emailData['job_link'] 			= esc_url( $job_link );
						$emailData['job_title'] 		= esc_html( $job_title );

						$email_helper->send_admin_job_post($emailData);
						$email_helper->send_employer_job_post($emailData);
					}
				}
	
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your job has been posted.', 'workreap');
			}
			
			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $current_user->ID, true,'posted');
			
			//add custom data
			do_action('workreap_post_job_extra_data',$_POST,$post_id);

			//Prepare Params
			$params_array['user_identity'] = $current_user->ID;
			$params_array['user_role'] = apply_filters('workreap_get_user_type', $current_user->ID );
			$params_array['type'] = 'project_create';
			
			do_action('wt_process_job_child', $params_array);
			
			wp_send_json( $json );
		} else {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
			wp_send_json( $json );
		}

	}

	add_action( 'wp_ajax_workreap_post_job', 'workreap_post_job' );
}

/**
 * submit project comment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_submit_project_chat' ) ){
    function workreap_submit_project_chat(){
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
    	$user_id 		= $current_user->ID; 
    	$user_email 	= $current_user->user_email;  
    	$author 		= workreap_get_username($user_id);
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if ( apply_filters('workreap_get_user_type', $user_id) === 'employer' ){
			$employer_post_id   		= get_user_meta($user_id, '_linked_profile', true);
    		$avatar = apply_filters(
		        'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_post_id), array('width' => 100, 'height' => 100) 
			);
    	} else {
			$freelancer_post_id   		= get_user_meta($user_id, '_linked_profile', true);
    		$avatar = apply_filters(
				'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_post_id), array('width' => 100, 'height' => 100) 
			);
    	}    	
    	
        $json = array();

        //Form Validation
       	if( empty( $_POST['id'] ) || empty( $_POST['chat_desc'] ) ){
       		$json['type'] = 'error';
       		$json['message'] = esc_html__('Message is required.', 'workreap');
       		wp_send_json($json);
       	}

       	$post_id 	= !empty( $_POST['id'] ) ? $_POST['id'] : '';     	
    	$temp_items = !empty( $_POST['temp_files']) ? ($_POST['temp_files']) : array();
    	$content 	= !empty( $_POST['chat_desc'] ) ? $_POST['chat_desc'] : ''; 
		
		$post_type	= get_post_type($post_id);

		//Upload files from temp folder to uploads
		$project_files = array();
        if( !empty( $temp_items ) ) {
            foreach ( $temp_items as $key => $value ) {
                $project_files[] = workreap_temp_upload_to_media($value, $post_id,true);
            }                
		}
		
		if( isset( $post_type ) && $post_type === 'services-orders' ){
			$project_id 				= get_post_meta( $post_id, '_service_id', true);
			$hired_freelance_id 		= get_post_field('post_author', $project_id);
			$freelancer_id				= workreap_get_linked_profile_id($hired_freelance_id);
			$employer_id				= get_post_field('post_author', $post_id);
		} else{
			$project_id 				= get_post_meta( $post_id, '_project_id', true);
			$freelancer_id 				= get_post_meta( $project_id, '_freelancer_id', true);
			$hired_freelance_id			= get_post_field('post_author', $post_id);
			$employer_id				= get_post_field('post_author', $project_id);
		}

		$time = current_time('mysql');
						
		$data = array(
		    'comment_post_ID' 		=> $post_id,
		    'comment_author' 		=> $author,
		    'comment_author_email' 	=> $user_email,
		    'comment_author_url' 	=> 'http://',
		    'comment_content' 		=> $content,
		    'comment_type' 			=> '',
		    'comment_parent' 		=> 0,
		    'user_id' 				=> $user_id,
		    'comment_date' 			=> $time,
		    'comment_approved' 		=> 1,
		);

		$comment_id = wp_insert_comment($data);
		
		if( !empty( $comment_id ) ) {	
			$is_files	= 'no';
			if( !empty( $project_files )) {
				$is_files	= 'yes';
				add_comment_meta($comment_id, 'message_files', $project_files);		
			}
			
			if( isset( $post_type ) && $post_type === 'services-orders' ){
				if( $user_type === 'employer' ){
					$receiver_id = $hired_freelance_id;
				} else{
					$receiver_id = $employer_id;
				}

				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapServiceMessage')) {
						$email_helper = new WorkreapServiceMessage();
						$emailData = array();

						$employer_name 		= workreap_get_username($employer_id);
						$employer_profile 	= get_permalink(workreap_get_linked_profile_id($employer_id));

						$job_title 			= esc_html( get_the_title($project_id) );
						$job_link 			= get_permalink($project_id);

						$freelancer_link 	= get_permalink($freelancer_id);
						$freelancer_title 	= workreap_get_username('',$freelancer_id);

						$freelancer_email 	= get_userdata( $hired_freelance_id )->user_email;
						$employer_email 	= get_userdata( $employer_id )->user_email;


						$emailData['employer_name'] 		= esc_html( $employer_name );
						$emailData['employer_link'] 		= esc_url( $employer_profile );
						$emailData['employer_email'] 		= sanitize_email( $employer_email );

						$emailData['freelancer_link']       = esc_url( $freelancer_link );
						$emailData['freelancer_name']       = esc_html( $freelancer_title );
						$emailData['freelancer_email']      = sanitize_email( $freelancer_email );

						$emailData['service_title'] 		= esc_html( $job_title );
						$emailData['service_link'] 			= esc_url( $job_link );
						$emailData['service_msg']			= esc_textarea( $content );

						//Push notification
						$push	= array();
						$push['service_id']			= $project_id;
						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%service_title%']	= $emailData['service_title'];
						$push['%service_link%']		= $emailData['service_link'];
						$push['%message%']			= wp_strip_all_tags($emailData['service_msg']);

						$push['%replace_message%']	= wp_strip_all_tags($emailData['service_msg']);

						if ( apply_filters('workreap_get_user_type', $user_id) === 'employer' ){
							$email_helper->send_service_message_freelancer($emailData);
							$push['employer_id']		= $current_user->ID;
							$push['freelancer_id']		= $hired_freelance_id;
							$push['service_id']			= $project_id;
							$push['type']				= 'service_message_freelancer';
							do_action('workreap_user_push_notify',array($hired_freelance_id),'','pusher_frl_service_msg_content',$push);
						} else{
							$email_helper->send_service_message_employer($emailData);
							$push['freelancer_id']		= $current_user->ID;
							$push['employer_id']		= $employer_id;
							$push['service_id']			= $project_id;
							$push['type']				= 'service_message_employer';
							do_action('workreap_user_push_notify',array($employer_id),'','pusher_emp_service_msg_content',$push);
						}

					}
				}
				
			} else{
				if($user_type === 'employer'){
					$receiver_id = $hired_freelance_id;
				} else{
					$receiver_id = $employer_id;
				}
				
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapProposalMessage')) {
						$email_helper = new WorkreapProposalMessage();
						$emailData = array();

						$employer_name 		= workreap_get_username($employer_id);
						$employer_profile 	= get_permalink(workreap_get_linked_profile_id($employer_id));

						$job_title 			= esc_html( get_the_title($project_id) );
						$job_link 			= get_permalink($project_id);

						$freelancer_link 	= get_permalink($freelancer_id);
						$freelancer_title 	= workreap_get_username('',$freelancer_id);

						$freelancer_email 	= get_userdata( $hired_freelance_id )->user_email;
						$employer_email 	= get_userdata( $employer_id )->user_email;


						$emailData['employer_name'] 		= esc_html( $employer_name );
						$emailData['employer_link'] 		= esc_url( $employer_profile );
						$emailData['employer_email'] 		= sanitize_email( $employer_email );

						$emailData['freelancer_link']       = esc_url( $freelancer_link );
						$emailData['freelancer_name']       = esc_html( $freelancer_title );
						$emailData['freelancer_email']      = sanitize_email( $freelancer_email );

						$emailData['job_title'] 			= esc_html( $job_title );
						$emailData['job_link'] 				= esc_url( $job_link );
						$emailData['proposal_msg']			= $content;
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $freelancer_id;
						$push['employer_id']		= $employer_id;
						$push['project_id']			= $project_id;
						
						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%project_title%']	= $emailData['job_title'];
						$push['%project_link%']		= $emailData['job_link'];
						$push['%message%']			= wp_strip_all_tags($emailData['proposal_msg']);

						$push['%replace_message%']	= wp_strip_all_tags($emailData['proposal_msg']);
						
						if ( apply_filters('workreap_get_user_type', $user_id) == 'employer' ){
							$email_helper->send_proposal_message_freelancer($emailData);
							
							$push['employer_id']		= $current_user->ID;
							$push['freelancer_id']		= $hired_freelance_id;
							$push['type']				= 'project_message_employer';
							do_action('workreap_user_push_notify',array($hired_freelance_id),'','pusher_frl_proposal_msg_content',$push);
							
						} else{
							$email_helper->send_proposal_message_employer($emailData);
							
							$push['freelancer_id']		= $current_user->ID;
							$push['employer_id']		= $employer_id;
							$push['type']				= 'project_message_freelancer';
							do_action('workreap_user_push_notify',array($employer_id),'','pusher_emp_proposal_msg_content',$push);
						}

					}
				}
			}
			
			$json['comment_id']			= $comment_id;
			$json['user_id']			= intval( $user_id );
			$json['receiver_id']		= intval( $receiver_id );
			$json['type'] 				= 'success';
			$json['message'] 			= esc_html__('Your message has sent.', 'workreap');
			$json['content_message'] 	= esc_html( wp_strip_all_tags( $content ) );
			$json['user_name'] 			= $author;
			$json['is_files'] 			= $is_files;
			$json['date'] 				= date_i18n(get_option('date_format'), strtotime($time));
			$json['img'] 				= $avatar;
			wp_send_json($json);
		}
    	
    	$json['type'] = 'error';
		$json['message'] = esc_html__('Something went wrong please try again', 'workreap');
		wp_send_json($json);
      
    }
    add_action('wp_ajax_workreap_submit_project_chat', 'workreap_submit_project_chat');
}

/**
 * Download attachment chat
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists( 'workreap_download_chat_attachments' ) ){
	function workreap_download_chat_attachments(){
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$json = array();
		$attachment_id	=  !empty( $_POST['comments_id'] ) ? intval($_POST['comments_id']) : '';
		if( empty( $attachment_id ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('Attachment ID is missing', 'workreap');
			wp_send_json($json);
		} else {
			$project_files = get_comment_meta( $attachment_id, 'message_files', true);
			if( !empty( $project_files ) ){
				if( class_exists('ZipArchive') ){
					$zip = new ZipArchive();
					$uploadspath	= wp_upload_dir();
					$folderRalativePath = $uploadspath['baseurl']."/downloads";
					$folderAbsolutePath = $uploadspath['basedir']."/downloads";
					wp_mkdir_p($folderAbsolutePath);
					$rand	= workreap_unique_increment(5);
					$filename	= $rand.round(microtime(true)).'.zip';
					$zip_name = $folderAbsolutePath.'/'.$filename; 
					$zip->open($zip_name,  ZipArchive::CREATE);
					$download_url	= $folderRalativePath.'/'.$filename;

					foreach($project_files as $key => $value) {	
						$file_url	= $value['url'];
						$response	= wp_remote_get( $file_url );
						$filedata   = wp_remote_retrieve_body( $response );
						$zip->addFromString(basename( $file_url ), $filedata);
					}
					$zip->close();
				}else{
					$json['type'] = 'error';
					$json['message'] = esc_html__('Zip library is not installed on the server, please contact to hosting provider', 'workreap');
					wp_send_json($json);
				}
			}
			
			$json['type'] = 'success';
			$json['attachment'] = workreap_add_http( $download_url );
			$json['message'] = esc_html__('Downloads successfully.', 'workreap');
			wp_send_json($json);
		}
	}
	add_action('wp_ajax_workreap_download_chat_attachments', 'workreap_download_chat_attachments');
}

/**
 * Download Downloadable files
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists( 'workreap_download_downloadable_files' ) ){
	function workreap_download_downloadable_files(){
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$json 		= array();
		$service_id	=  !empty( $_POST['id'] ) ? intval($_POST['id']) : '';
		
		if( empty( $service_id ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('Service ID is missing', 'workreap');
			wp_send_json($json);
		} else {
			$downloadable_files		= get_post_meta( $service_id, '_downloadable_files', true);
			$downloadable_files		= !empty( $downloadable_files ) ? $downloadable_files : array();
			
			if( !empty( $downloadable_files ) ){
				$zip = new ZipArchive();
				$uploadspath	= wp_upload_dir();
				$folderRalativePath = $uploadspath['baseurl']."/downloads";
				$folderAbsolutePath = $uploadspath['basedir']."/downloads";
				wp_mkdir_p($folderAbsolutePath);
				$rand	= workreap_unique_increment(5);
				$filename	= $rand.round(microtime(true)).'.zip';
				$zip_name = $folderAbsolutePath.'/'.$filename; 
				$zip->open($zip_name,  ZipArchive::CREATE);
				$download_url	= $folderRalativePath.'/'.$filename;

				foreach($downloadable_files as $key => $value) {	
					$file_url	= $value['url'];
					$response	= wp_remote_get( $file_url );
					$filedata   = wp_remote_retrieve_body( $response );
					$zip->addFromString(basename( $file_url ), $filedata);
				}
				
				$zip->close();
			}
			
			$json['type'] 		= 'success';
			$json['attachment'] = $download_url;
			$json['message'] 	= esc_html__('Downloads successfully.', 'workreap');
			wp_send_json($json);
		}
	}
	add_action('wp_ajax_workreap_download_downloadable_files', 'workreap_download_downloadable_files');
}

/**
 * Cancel Project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists( 'workreap_cancel_project' ) ){
	function workreap_cancel_project(){
		global $current_user, $wpdb, $woocommerce;
		$json 				= array();
		$project_id			=  !empty( $_POST['project_id'] ) ? intval($_POST['project_id']) : '';
		$cancelled_reason	=  !empty( $_POST['cancelled_reason'] ) ? $_POST['cancelled_reason'] : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($project_id);
		}; //if user is not logged in then prevent

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		if( empty( $project_id ) || empty( $cancelled_reason ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('Project reason is missing', 'workreap');
			wp_send_json($json);
		} else {
			$proposal_id 			= get_post_meta( $project_id, '_proposal_id', true);
			$freelancer_id 			= get_post_meta( $project_id, '_freelancer_id', true);
			$hired_freelance_id		= get_post_field('post_author', $proposal_id);
			
			delete_post_meta( $project_id, '_proposal_id', $proposal_id );
			delete_post_meta( $project_id, '_freelancer_id', $freelancer_id );
			add_post_meta( $proposal_id, '_cancelled_reason', $cancelled_reason );
			add_post_meta( $project_id, '_cancelled_proposal_id', $proposal_id );
			add_post_meta( $proposal_id, '_employer_user_id', $current_user->ID );
			
			$project_post_data 	= array(
				'ID'            => $project_id,
				'post_status'   => 'cancelled',
			);
  			wp_update_post( $project_post_data );
			$proposal_post_data 	= array(
				'ID'            => $proposal_id,
				'post_status'   => 'cancelled',
			);

			wp_update_post( $proposal_post_data );
			
			//update earnings
			
			$table_name = $wpdb->prefix . 'wt_earnings';
			$e_query		= $wpdb->prepare("SELECT * FROM `$table_name` where user_id = %d and project_id = %d",$hired_freelance_id,$project_id);
			$earnings		= $wpdb->get_results($e_query,OBJECT ); 
			
			if( !empty( $earnings ) ) {
				foreach($earnings as $earning ){
					$update		= array( 'status' => 'cancelled' );
					$where		= array( 'id' 	=> $earning->id );
					workreap_update_earning( $where, $update, 'wt_earnings');
					
					if ( class_exists('WooCommerce') ) {
						$order = wc_get_order( intval( $earning->order_id ) );
						if( !empty( $order ) ) {
							$order->update_status( 'cancelled' );
						}
					}
				}
					
			}
			
			//Send email to users
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapCancelJob')) {
					$email_helper = new WorkreapCancelJob();
					$emailData = array();

					$employer_name 		= workreap_get_username($current_user->ID);
					$employer_profile 	= get_permalink(workreap_get_linked_profile_id($current_user->ID));
					$job_title 			= esc_html( get_the_title($project_id));
					$job_link 			= get_permalink($project_id);
					$freelancer_link 	= get_permalink(workreap_get_linked_profile_id($hired_freelance_id));
					$freelancer_title 	= esc_html( get_the_title(workreap_get_linked_profile_id($hired_freelance_id)) );
					$freelancer_email 	= get_userdata( $hired_freelance_id )->user_email;
					
					$emailData['employer_name'] 		= esc_html( $employer_name );
					$emailData['employer_link'] 		= esc_url( $employer_profile );
					$emailData['freelancer_link']       = esc_url( $freelancer_link );
					$emailData['freelancer_name']       = esc_html( $freelancer_title );
					$emailData['email_to']      		= sanitize_email( $freelancer_email );
					$emailData['job_title'] 			= esc_html( $job_title );
					$emailData['job_link'] 				= esc_url( $job_link );
					$emailData['cancel_msg'] 			= esc_textarea($cancelled_reason);

					$email_helper->send_job_cancel_email($emailData);
					
					//Push notification
					$push	= array();
					$push['freelancer_id']		= $freelancer_id;
					$push['employer_id']		= $current_user->ID;
					$push['project_id']			= $project_id;

					$push['%freelancer_link%']	= $emailData['freelancer_link'];
					$push['%freelancer_name%']	= $emailData['freelancer_name'];
					$push['%employer_name%']	= $emailData['employer_name'] ;
					$push['%employer_link%']	= $emailData['employer_link'];
					$push['%project_title%']	= $emailData['job_title'];
					$push['%project_link%']		= $emailData['job_link'];
					$push['%message%']			= wp_strip_all_tags( $emailData['cancel_msg'] );
					$push['type']				= 'project_cancelled';

					$push['%replace_message%']	= wp_strip_all_tags( $emailData['cancel_msg'] );

					do_action('workreap_user_push_notify',array($freelancer_id),'','pusher_frl_cancel_job_content',$push);
					
				}
			}
			
			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $current_user->ID, true,'cancelled');
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Project cancelled successfully.', 'workreap');
			wp_send_json($json);
		}
	}
	add_action('wp_ajax_workreap_cancel_project', 'workreap_cancel_project');
}


/**
 * Cancel Project from posted projects
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists( 'workreap_cancel_job' ) ){
	function workreap_cancel_job(){
		global $current_user, $wpdb, $woocommerce;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$json 				= array();
		$project_id			= !empty( $_POST['project_id'] ) ? intval($_POST['project_id']) : '';
		
		if( empty( $project_id ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('Project ID is missing', 'workreap');
			wp_send_json($json);
		} else {
			
			$project_post_data 	= array(
				'ID'            => $project_id,
				'post_status'   => 'cancelled',
			);
			
  			wp_update_post( $project_post_data );
			
			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $current_user->ID, true,'cancelled');
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Project has been cancelled.', 'workreap');
			wp_send_json($json);
		}
	}
	add_action('wp_ajax_workreap_cancel_job', 'workreap_cancel_job');
}

/**
 * Complete Project with reviews
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_complete_project' ) ){
	function workreap_complete_project(){
		global $current_user,$wpdb;
		$json 					= array();
		$where					= array();
		$update					= array();
		
		$rating_headings		= workreap_project_ratings();
		$project_id				= !empty( $_POST['project_id'] ) ? intval($_POST['project_id']) : '';
		$contents 				= !empty( $_POST['feedback_description'] ) ? sanitize_textarea_field($_POST['feedback_description']) : '';
		$reviews 				= !empty( $_POST['feedback'] ) ? $_POST['feedback'] : array();
		
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($project_id);
		} //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		if( empty( $project_id ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');	
			
			wp_send_json($json);
			
		} else {
			$employer_id		= get_post_field('post_author',$project_id);
			$proposal_id		= get_post_meta( $project_id, '_proposal_id', true);
			$freelance_id		= get_post_field('post_author',$proposal_id);
			$review_title		= esc_html( get_the_title($proposal_id) );

			$user_reviews = array(
				'posts_per_page' 	=> 1,
				'post_type' 		=> 'reviews',
				'post_status' 		=> 'any',
				'author' 			=> $freelance_id,
				'meta_key' 			=> '_project_id',
				'meta_value' 		=> $project_id,
				'meta_compare' 		=> "=",
				'orderby' 			=> 'meta_value',
				'order' 			=> 'ASC',
			);

			$reviews_query = new WP_Query($user_reviews);
			$reviews_count = $reviews_query->post_count;
			
			if (isset($reviews_count) && $reviews_count > 0) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('You have already submit a review.', 'workreap');
				wp_send_json($json);
			}

			$review_post = array(
                'post_title' 		=> $review_title,
                'post_status' 		=> 'publish',
                'post_content' 		=> $contents,
                'post_author' 		=> $freelance_id,
                'post_type' 		=> 'reviews',
                'post_date' 		=> current_time('Y-m-d H:i:s')
            );

            $post_id = wp_insert_post($review_post);
			
			/* Get the rating headings */
            $rating_evaluation 			= workreap_project_ratings();
            $rating_evaluation_count 	= !empty($rating_evaluation) ? workreap_count_items($rating_evaluation) : 0;
			
			$review_extra_meta = array();
			$rating 		= 0;
			$user_rating 	= 0;
			
            if (!empty($rating_evaluation)) {
                foreach ($rating_evaluation as $slug => $label) {
                    if (isset($reviews[$slug])) {
                        $review_extra_meta[$slug] = esc_attr($reviews[$slug]);
                        update_post_meta($post_id, $slug, esc_attr($reviews[$slug]));
                        $rating += (int) $reviews[$slug];
                    }
                }
            }
			
			update_post_meta($post_id, '_project_id', $project_id);
			update_post_meta($post_id, '_proposal_id', $proposal_id);
			if( !empty( $rating ) ){
				$user_rating = $rating / $rating_evaluation_count;
			}
			
			$employer_profile_id 	= workreap_get_linked_profile_id( $employer_id );
			$freelance_profile_id 	= workreap_get_linked_profile_id( $freelance_id );
			
            $user_rating 			= number_format((float) $user_rating, 2, '.', '');
			$review_meta 			= array(
                'user_rating' 		=> $user_rating,
                'user_from' 		=> $employer_profile_id,
                'user_to' 			=> $freelance_profile_id,
                'review_date' 		=> current_time('Y-m-d H:i:s'),
            );
			
			$review_meta = array_merge($review_meta, $review_extra_meta);

            //Update post meta
            foreach ($review_meta as $key => $value) {
                update_post_meta($post_id, $key, $value);
            }
			
			$review_meta['user_from'] 	= array($employer_profile_id);
            $review_meta['user_to'] 	= array($freelance_profile_id);

            $new_values = $review_meta;
            if (isset($post_id) && !empty($post_id)) {
                fw_set_db_post_option($post_id, null, $new_values);
            }

            /* Update avarage rating in user table */

			$table_review = $wpdb->prefix . "posts";
			$table_meta   = $wpdb->prefix . "postmeta";

			$db_rating_query = $wpdb->get_row( "
				SELECT  p.ID,
				SUM( pm2.meta_value ) AS db_rating,
				count( p.ID ) AS db_total
				FROM   ".$table_review." p 
				LEFT JOIN ".$table_meta." pm1 ON (pm1.post_id = p.ID  AND pm1.meta_key = 'user_to') 
				LEFT JOIN ".$table_meta." pm2 ON (pm2.post_id = p.ID  AND pm2.meta_key = 'user_rating')
				WHERE post_status = 'publish'
				AND pm1.meta_value    = ".$freelance_profile_id."
				AND p.post_type = 'reviews'
			",ARRAY_A);
			
			$user_rating 	= '0';
			
			if( empty( $db_rating_query ) ){
				$user_db_reviews['wt_average_rating'] 			= 0;
				$user_db_reviews['wt_total_rating'] 			= 0;
				$user_db_reviews['wt_total_percentage'] 		= 0;
				$user_db_reviews['wt_rating_count'] 			= 0;
			} else{
				
				$rating			= !empty( $db_rating_query['db_rating'] ) ? $db_rating_query['db_rating']/$db_rating_query['db_total'] : 0;
				$user_rating 	= number_format((float) $rating, 2, '.', '');
				
				$user_db_reviews['wt_average_rating'] 			= $user_rating;
				$user_db_reviews['wt_total_rating'] 			= !empty( $db_rating_query['db_total'] ) ? $db_rating_query['db_total'] : '';
				$user_db_reviews['wt_total_percentage'] 		= $user_rating * 20;
				$user_db_reviews['wt_rating_count'] 			= !empty( $db_rating_query['db_rating'] ) ? $db_rating_query['db_rating'] : '';
			}

			update_post_meta($freelance_profile_id, 'review_data', $user_db_reviews);
			update_post_meta($freelance_profile_id, 'rating_filter', $user_rating);
			
			//Update order to completed
			$order_id			= get_post_meta($proposal_id,'_order_id',true);
			if ( class_exists('WooCommerce') && !empty( $order_id )) {
				$order = wc_get_order( intval($order_id ) );
				if( !empty( $order ) ) {
					$order->update_status( 'completed' );
				}
			}
			
			//Update project to completed
			$project_post_data 	= array(
				'ID'            => $project_id,
				'post_status'   => 'completed',
			);
			
			wp_update_post( $project_post_data );

			$proposal_id 	= get_post_meta( $project_id, '_proposal_id', true);
			update_post_meta($proposal_id, '_employer_user_id', $current_user->ID);
			
			//update earning
			$where		= array('project_id' => $project_id, 'user_id' => $freelance_id);
			$update		= array('status' => 'completed');
			
			workreap_update_earning( $where, $update, 'wt_earnings');
			
			//Send email to users
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapJobCompleted')) {
					$email_helper = new WorkreapJobCompleted();
					$emailData 	  = array();

					$job_title 			= esc_html( get_the_title($project_id) );
					$job_link 			= get_permalink($project_id);
					$employer_name 		= workreap_get_username($current_user->ID);
					$employer_profile 	= get_permalink(workreap_get_linked_profile_id($current_user->ID));
					$freelancer_link 	= get_permalink($freelance_profile_id );
					$freelancer_title 	= esc_html( get_the_title($freelance_profile_id ) );
					$freelancer_email 	= get_userdata( $freelance_id )->user_email;

						
					$emailData['employer_name'] 		= esc_html( $employer_name );
					$emailData['employer_link'] 		= esc_url( $employer_profile );
					$emailData['freelancer_name']       = esc_html( $freelancer_title );
					$emailData['freelancer_link']       = esc_url( $freelancer_link );
					$emailData['freelancer_email']      = sanitize_email( $freelancer_email );
					$emailData['project_title'] 		= esc_html( $job_title );
					$emailData['ratings'] 				= esc_html( $user_rating );
					$emailData['project_link'] 			= esc_url( $job_link );
					$emailData['message'] 				= sanitize_textarea_field( $contents );

					$email_helper->send_job_completed_email_admin($emailData);
					$email_helper->send_job_completed_email_freelancer($emailData);
					
					//Push notification
					$push	= array();
					$push['freelancer_id']		= $freelance_id;
					$push['employer_id']		= $current_user->ID;
					$push['project_id']			= $project_id;

					$push['%freelancer_link%']	= $emailData['freelancer_link'];
					$push['%freelancer_name%']	= $emailData['freelancer_name'];
					$push['%employer_name%']	= $emailData['employer_name'] ;
					$push['%employer_link%']	= $emailData['employer_link'];
					$push['%project_title%']	= $emailData['project_title'];
					$push['%project_link%']		= $emailData['project_link'];
					$push['%ratings%']			= $emailData['ratings'];
					$push['%message%']			= $emailData['message'];
					$push['type']				= 'project_completed';

					$push['%replace_ratings%']	= $emailData['ratings'];
					$push['%replace_message%']	= $emailData['message'];

					do_action('workreap_user_push_notify',array($freelance_id),'','pusher_frl_job_complete_content',$push);
				}
			}

			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $current_user->ID, true,'completed');
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Project completed successfully.', 'workreap');
			wp_send_json($json);
			
		}
	}
	add_action('wp_ajax_workreap_complete_project', 'workreap_complete_project');
}

/**
 * hire freelancer for job reopen
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_job_reopen' ) ) {

	function workreap_job_reopen() {
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json				= array();
		$project_id			= !empty( $_POST['project_id'] ) ? intval( $_POST['project_id'] ) : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( !empty($project_id) ){
			$project_post_data = array(
				'ID'            => $project_id,
				'post_status'   => 'publish'
			);
  			wp_update_post( $project_post_data );
			$json['type'] = 'success';
            $json['message'] = esc_html__('Job reopened successfully.', 'workreap');
            wp_send_json($json);
		} else{
			$json['type'] = 'error';
            $json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_workreap_job_reopen', 'workreap_job_reopen' );
}

/**
 * Update project shares
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_update_project_shares' ) ) {

	function workreap_update_project_shares() {
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json				= array();
		$project_id			= !empty( $_POST['project_id'] ) ? intval( $_POST['project_id'] ) : '';
		$proposed_amount	= !empty( $_POST['proposed_amount'] ) ? floatval( $_POST['proposed_amount'] ) : 0;

		$settings	= array();
		if( empty($project_id) || empty($proposed_amount) ){
			$settings['admin_shares'] 		= 0.0;
			$settings['freelancer_shares'] 	= 0.0;
		}else{
			$settings	= workreap_commission_fee($proposed_amount,'projects',$project_id);
		}

		wp_send_json($settings);	
	}

	add_action( 'wp_ajax_workreap_update_project_shares', 'workreap_update_project_shares' );
	add_action( 'wp_ajax_nopriv_workreap_update_project_shares', 'workreap_update_project_shares' );
}

/**
 * hire freelancer for job post
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_hire_freelancer' ) ) {

	function workreap_hire_freelancer() {
		global $current_user, $woocommerce;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			= array();
		$job_id			= !empty( $_POST['job_post_id'] ) ? intval( $_POST['job_post_id'] ) : '';
		$proposal_id	= !empty( $_POST['proposal_id'] ) ? intval( $_POST['proposal_id'] ) : '';

		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$bk_settings 		= fw_get_db_settings_option('hiring_payment_settings');
		}

		$product_id	= workreap_get_hired_product_id();
		if( !empty( $product_id )) {

			if ( class_exists('WooCommerce') ) {

				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $current_user->ID;
				$price				= get_post_meta($proposal_id ,'_amount',true);
				$price_symbol		= workreap_get_current_currency();
				$admin_shares 		= 0.0;
				$freelancer_shares 	= 0.0;

				if( !empty( $price ) ){
					$service_fee		= workreap_commission_fee($price,'projects',$job_id);
					if( !empty( $service_fee ) ){
						$admin_shares       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
						$freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
					} else{
						$admin_shares       = 0.0;
						$freelancer_shares  = $price;
					}

					$admin_shares 		= number_format($admin_shares,2,'.', '');
					$freelancer_shares 	= number_format($freelancer_shares,2,'.', '');
				}
				
				$employer_service_fee		= workreap_employer_hiring_payment_setting('projects',$price);
				
				$cart_meta['project_id']		= $job_id;
				$cart_meta['price']				= $price;
				$cart_meta['proposal_id']		= $proposal_id;
				$cart_meta['processing_fee']	= !empty( $employer_service_fee['commission_amount'] ) ? $employer_service_fee['commission_amount'] : 0.0;

				$hired_freelance_id			= get_post_field('post_author',$proposal_id);
				$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'price'				=> workreap_price_format($price,'return'),
					'payment_type'     	=> 'hiring',
					'admin_shares'     	=> $admin_shares,
					'freelancer_shares' => $freelancer_shares,
					'employer_id' 		=> $current_user->ID,
					'freelancer_id' 	=> $hired_freelance_id,
					'current_project' 	=> $job_id,
				);

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
				
				if( !empty( $bk_settings['gadget'] ) && $bk_settings['gadget'] === 'enable' ) {
					$json['type'] 			= 'checkout';
					$json['message'] 		= esc_html__('Please wait you are redirecting to the checkout page.', 'workreap');
					$json['checkout_url']	= wc_get_checkout_url();
					wp_send_json($json);
				}else{
					workreap_create_woocommerce_order($job_id);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please install WooCommerce plugin to process this order', 'workreap');
				wp_send_json($json);
			}
		} else{
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Hiring settings is missing, please contact to administrator.', 'workreap');
			wp_send_json($json);
		}
	
	}

	add_action( 'wp_ajax_workreap_hire_freelancer', 'workreap_hire_freelancer' );
}
/**
 * hire freelancer for job post
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_download_attachments' ) ) {

	function workreap_download_attachments() {
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			=  array();
		$job_id			= !empty( $_POST['job_post_id'] ) ? intval( $_POST['job_post_id'] ) : '';
		$project_type			= !empty( $_POST['type'] ) ? $_POST['type'] : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( !empty($job_id) ){
			if (function_exists('fw_get_db_post_option')) {
				if(!empty($project_type) && $project_type === 'project'){
					$proposal_docs 			= fw_get_db_post_option($job_id, 'project_documents');
				}else{
					$proposal_docs 			= fw_get_db_post_option($job_id, 'proposal_docs');
				}
				
				if( !empty($proposal_docs) ) {
					$zip = new ZipArchive();
					$uploadspath			= wp_upload_dir();
					$folderRalativePath 	= $uploadspath['baseurl']."/downloads";
					$folderAbsolutePath 	= $uploadspath['basedir']."/downloads";
					wp_mkdir_p($folderAbsolutePath);
					$rand					= workreap_unique_increment(5);
					$filename				= $rand.round(microtime(true)).'.zip';
					$zip_name 				= $folderAbsolutePath.'/'.$filename; 
					$zip->open($zip_name,  ZipArchive::CREATE);
					$download_url			= $folderRalativePath.'/'.$filename; 

					foreach($proposal_docs as $file) {
						$response			= wp_remote_get($file['url']);
						$filedata   		= wp_remote_retrieve_body( $response );
						$zip->addFromString(basename($file['url']), $filedata);
					}
					
					$zip->close();
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('No file attached.', 'workreap');
					wp_send_json($json);
				}
			}

			$json['type'] 		= 'success';
			$json['attachment'] = $download_url;
            $json['message'] 	= esc_html__('Downloaded successfully.', 'workreap');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_workreap_download_attachments', 'workreap_download_attachments' );
}

/**
 * hire Remove single Save item
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_remove_save_item' ) ) {

	function workreap_remove_save_item() {
		$post_id		= !empty( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($post_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			= array();
		
		$item_id		= !empty( $_POST['item_id'] ) ? array(intval( $_POST['item_id'] )) : array();
		$item_type		= !empty( $_POST['item_type'] ) ? sanitize_text_field( $_POST['item_type'] ) : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( !empty($post_id) && !empty($item_type) && !empty(item_id) ){
			$save_projects_ids	= get_post_meta( $post_id, $item_type, true);
			$updated_values 	= array_diff(  $save_projects_ids , $item_id);
			update_post_meta( $post_id, $item_type, $updated_values );
			
			$json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Remove save item successfully.', 'workreap');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_workreap_remove_save_item', 'workreap_remove_save_item' );
}

/**
 * hire Remove Multiple Save item
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_remove_save_multipuleitems' ) ) {

	function workreap_remove_save_multipuleitems() {
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			=  array();
		$post_id		= !empty( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
		$item_type		= !empty( $_POST['item_type'] ) ? sanitize_text_field( $_POST['item_type'] ) : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( !empty($post_id) && !empty($item_type) && !empty(item_id) ){
			$save_projects_ids	= get_post_meta( $post_id, $item_type, true);
			update_post_meta( $post_id, $item_type, '' );
			
			$json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Remove save items successfully.', 'workreap');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_workreap_remove_save_multipuleitems', 'workreap_remove_save_multipuleitems' );
}

/**
 * get cover letter
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_get_coverletter' ) ) {

	function workreap_get_coverletter() {
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json				=  array();
		$proposal_id		= !empty( $_POST['proposal_id'] ) ? intval( $_POST['proposal_id'] ) : '';
		if( empty( $proposal_id )) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}
		
		if( !empty($proposal_id) ){
			$contents			= nl2br( stripslashes( get_the_content('',true,$proposal_id) ) );
			
			$json['contents'] 	= $contents;
			$json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Proposal coverletter', 'workreap');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_workreap_get_coverletter', 'workreap_get_coverletter' );
}


/**
 * Add to Cart
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_update_cart' ) ) {

	function workreap_update_cart() {
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json				=  array();
		$product_id		= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {
			
				global $current_user, $woocommerce;
				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $current_user->ID;
				$is_cart_matched	= workreap_matched_cart_items($product_id);
				if ( isset( $is_cart_matched ) && $is_cart_matched > 0) {
					$json = array();
					$json['type'] 			= 'success';
					$json['message'] 		= esc_html__('You have already in cart, We are redirecting to checkout', 'workreap');
					$json['checkout_url'] 	= wc_get_checkout_url();
					wp_send_json($json);
				}
				
				$cart_meta					= array();
				$user_type					= workreap_get_user_type( $user_id );
				$pakeges_features			= workreap_get_pakages_features();

				if ( !empty ( $pakeges_features )) {
					foreach( $pakeges_features as $key => $vals ) {
						if( $vals['user_type'] === $user_type || $vals['user_type'] === 'common' ) {
							$item			= get_post_meta($product_id,$key,true);
							$text			=  !empty( $vals['text'] ) ? ' '.esc_html($vals['text']) : '';
							if( $key === 'wt_duration_type' ) {
								$feature 	= workreap_get_duration_types($item,'value');
							} else if( $key === 'wt_badget' ) {
								$feature 	= !empty( $item ) ? $item : 0;
							} else {
								$feature 	= $item;
							}
							
							$cart_meta[$key]	= $feature.$text;
						}
					}
				}
				
				$user_type					= workreap_get_user_type( $user_id );
				
				if(!empty($user_type) && $user_type === 'freelancer'){
					$cart_data = array(
						'product_id' 		=> $product_id,
						'cart_data'     	=> $cart_meta,
						'payment_type'     	=> 'subscription',
						'freelancer_id' 	=> $hired_freelance_id,
					);
					
				}else{
					$cart_data = array(
						'product_id' 		=> $product_id,
						'cart_data'     	=> $cart_meta,
						'payment_type'     	=> 'subscription',
					);
				}

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

				$json = array();
				$json['type'] 			= 'success';
				$json['message'] 		= esc_html__('Please wait you are redirecting to checkout page.', 'workreap');
				$json['checkout_url']	= wc_get_checkout_url();
				wp_send_json($json);
			} else {
				$json = array();
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please install WooCommerce plugin to process this order', 'workreap');
			}
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_workreap_update_cart', 'workreap_update_cart' );
}

/**
 * FAQ support
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_support_faq' ) ) {

	function workreap_support_faq() {
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			=  array();
		$query_type		= !empty( $_POST['query_type'] ) ? $_POST['query_type'] : '';
		$details		= !empty( $_POST['details'] ) ? $_POST['details'] : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( empty(details) ) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Message is required.', 'workreap');
            wp_send_json($json);
		} else if( empty($query_type) ) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Query type is required.', 'workreap');
            wp_send_json($json);
		}else if( !empty(details) && !empty($query_type) ){
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapHelp')) {
					$email_helper = new WorkreapHelp();
					$emailData 	  = array();
					$user_name 			= workreap_get_username($current_user->ID);
					$profile 			= workreap_get_linked_profile_id($current_user->ID);
					$user_profile 		= get_the_permalink($profile);

					$emailData['user_name'] 		= esc_attr( $user_name );
					$emailData['user_email'] 		= esc_attr( $user_email );
					$emailData['user_link'] 		= esc_url ( $user_profile );
					$emailData['query_type'] 		= esc_attr( $query_type );
					$emailData['message'] 			= esc_html( $details );

					$email_helper->send_admin_help($emailData);
				}
			}
			
			$json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Message has sent', 'workreap');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_workreap_support_faq', 'workreap_support_faq' );
}

/**
 * load more reviews
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_get_more_reviews' ) ) {

	function workreap_get_more_reviews() {
		$json			= array();
		$page			= !empty( $_POST['page'] ) ? intval( $_POST['page'] ) : '';
		$author_id		= !empty( $_POST['author_id'] ) ? intval( $_POST['author_id'] ) : '';
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$show_posts		= 3;
		$order 			= 'DESC';
		$sorting 		= 'ID';
		
		if(!empty($author_id) && !empty($page)) {
			$args2 		= array(
							'posts_per_page' 	=> $show_posts,
							'post_type' 		=> 'reviews',
							'orderby' 			=> $sorting,
							'order' 			=> $order,
							'author' 			=> $author_id,
							'paged' 			=> $page,
							'suppress_filters' 	=> false
						);
			$query2 			= new WP_Query($args2);

			if( $query2->have_posts() ){
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Reviews found', 'workreap');
				
				ob_start();
				$counter	= 0;
				
				while ($query2->have_posts()) : $query2->the_post();
					global $post;
				
					$counter ++;
					$project_id			= get_post_meta($post->ID, '_project_id', true);
					$project_rating		= get_post_meta($post->ID, 'user_rating', true);
					$employer_id		= get_post_field('post_author',$project_id);
					$company_profile 	= workreap_get_linked_profile_id($employer_id);
					$employer_title 	= esc_html( get_the_title( $company_profile ));
					$project_title		= esc_html( get_the_title($project_id));

					$company_avatar 	= apply_filters(
													'workreap_employer_avatar_fallback', workreap_get_employer_avatar( array( 'width' => 100, 'height' => 100 ), $company_profile ), array( 'width' => 225, 'height' => 225 )
												);
					$bg_class			= !empty($counter) && intval($counter)%2 === 0 ? '' : 'wt-bgcolor';
					?>
					<div class="wt-userlistinghold wt-userlistingsingle <?php echo esc_attr($bg_class);?> class-<?php echo esc_attr($post->ID);?>">	
						<figure class="wt-userlistingimg">
							<img src="<?php echo esc_url( $company_avatar );?>" alt="<?php esc_attr_e('Company','workreap');?>" >
						</figure>
						<div class="wt-userlistingcontent">
							<div class="wt-contenthead">
								<div class="wt-title">
									<?php do_action( 'workreap_get_verification_check', $company_profile, $employer_title ); ?>
									<h3><?php echo esc_html($project_title);?></h3>
								</div>
								<ul class="wt-userlisting-breadcrumb">
									<?php do_action('workreap_project_print_project_level', $project_id); ?>
									<?php do_action('workreap_print_location', $project_id); ?>
									<?php do_action('workreap_post_date', $post->ID); ?>
									<?php do_action('workreap_freelancer_get_project_rating', $project_rating,$post->ID); ?>
								</ul>
							</div>
						</div>
						<div class="wt-description">
							<p><?php echo get_the_content();?></p>
						</div>
					</div>
					<?php
					
				endwhile;
				wp_reset_postdata();
				
				$review				= ob_get_clean();
				$json['reviews'] 	= $review;
			} else{
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No more review', 'workreap');
				$json['reviews'] 	= 'null';
			}
		}
		
		wp_send_json($json);			
	}

	add_action( 'wp_ajax_workreap_get_more_reviews', 'workreap_get_more_reviews' );
	add_action( 'wp_ajax_nopriv_workreap_get_more_reviews', 'workreap_get_more_reviews' );
}

/**
 * Update Payrols
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_payrols_settings')) {

    function workreap_payrols_settings() {
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
        $user_identity 	= $current_user->ID;
        $json 			= array();
		$data 			= array();
		$payrols		= workreap_get_payouts_lists();
		
		$fields		= !empty( $payrols[$_POST['payout_settings']['type']]['fields'] ) ? $payrols[$_POST['payout_settings']['type']]['fields'] : array();
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}

		if( !empty($fields) ) {
			foreach( $fields as $key => $field ){
				if( $field['required'] === true && empty( $_POST['payout_settings'][$key] ) ){
					$json['type'] = 'error';
					$json['message'] = $field['message'];
					wp_send_json( $json );
				}
				
			}
		}
		
		update_user_meta($user_identity,'payrols',$_POST['payout_settings']);
		$json['type'] 	 = 'success';
		$json['message'] = esc_html__('Payout settings have been updated.', 'workreap');

       wp_send_json( $json );
    }

    add_action('wp_ajax_workreap_payrols_settings', 'workreap_payrols_settings');
}

/**
 * hire freelancer for service post
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_hire_service' ) ) {

	function workreap_hire_service() {
		global $current_user, $woocommerce;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		$linked_profile  	= workreap_get_linked_profile_id($current_user->ID);
		
		do_action('workreap_check_post_author_identity_status', $linked_profile); //check if user identity is verified
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			= array();
		$service_id		= !empty( $_POST['service_id'] ) ? intval( $_POST['service_id'] ) : '';
		$addons			= !empty( $_POST['addons'] ) ? explode( ',',$_POST['addons'] ) : array();
		$cart_meta		= array();
		$addon_data		= array();
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if ( is_user_logged_in() ) {
			$user_type	= apply_filters('workreap_get_user_type', $current_user->ID);
			if( $user_type !== 'employer' ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You do not have permission to buy this service. Only employers can buy the services', 'workreap');
				wp_send_json($json);
			}
			
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You do not have permission to buy this service. Please log in to continue', 'workreap');
			wp_send_json($json);
		}
		
		do_action('workreap_check_switch_user_status', $service_id);
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$bk_settings 		= fw_get_db_settings_option('hiring_payment_settings');
		}

		$product_id	= workreap_get_hired_product_id();

		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {

				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $current_user->ID;
				$price				= get_post_meta($service_id ,'_price',true);
				$single_service_price	= $price;


				if( !empty( $addons ) ){
					foreach( $addons as $addon_id ){
						$addons_price		= get_post_meta($addon_id ,'_price',true);
						$addons_price		= !empty( $addons_price ) ? $addons_price : 0 ;
						$price				= $price + $addons_price;
						$addon_data[$addon_id]['id']	= $addon_id;
						$addon_data[$addon_id]['price']	= $addons_price;
					}
				}

				$delivery_time		= wp_get_post_terms($service_id, 'delivery');
				$delivery_time 		= !empty( $delivery_time[0]->term_id ) ? $delivery_time[0]->term_id : '';
				$admin_shares 		= 0.0;
				$freelancer_shares 	= 0.0;

				if( !empty( $price ) ){
					$service_fee		= workreap_commission_fee($price,'services',$service_id);
					if( !empty( $service_fee ) ){
						$admin_shares       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
						$freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
					} else{
						$admin_shares       = 0.0;
						$freelancer_shares  = $price;
					}

					$admin_shares 		= number_format($admin_shares,2,'.', '');
					$freelancer_shares 	= number_format($freelancer_shares,2,'.', '');
				}
				
				$employer_service_fee		= workreap_employer_hiring_payment_setting('services',$price);
				
				$cart_meta['service_id']		= $service_id;
				$cart_meta['delivery_time']		= $delivery_time;
				$cart_meta['price']				= $price;
				$cart_meta['processing_fee']	= !empty( $employer_service_fee['commission_amount'] ) ? $employer_service_fee['commission_amount'] : 0.0;
				$cart_meta['service_price']		= $single_service_price;
				$cart_meta['addons']			= $addon_data;

				$hired_freelance_id			= get_post_field('post_author',$service_id);
				$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'price'				=> workreap_price_format($price,'return'),
					'payment_type'     	=> 'hiring_service',
					'admin_shares'     	=> $admin_shares,
					'freelancer_shares' => $freelancer_shares,
					'employer_id' 		=> $current_user->ID,
					'freelancer_id' 	=> $hired_freelance_id,
					'current_project' 	=> $service_id,
				);
				
				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
				
				if( !empty( $bk_settings['gadget'] ) && $bk_settings['gadget'] === 'enable' ) {
					

					$json['type'] 			= 'checkout';
					$json['message'] 		= esc_html__('Please wait you are redirecting to the checkout page.', 'workreap');
					$json['checkout_url']	= wc_get_checkout_url();
					wp_send_json($json);
				}else{
					workreap_create_woocommerce_order($service_id);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please install WooCommerce plugin to process this order', 'workreap');
				wp_send_json($json);
			}
		} else{
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Hiring settings is missing, please contact to administrator.', 'workreap');
			wp_send_json($json);
		}
					
	}

	add_action( 'wp_ajax_workreap_hire_service', 'workreap_hire_service' );
}

/**
 * hire freelancer for service quote
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_hire_quote' ) ) {

	function workreap_hire_quote() {
		global $current_user, $woocommerce;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		$linked_profile  	= workreap_get_linked_profile_id($current_user->ID);
		
		do_action('workreap_check_post_author_identity_status', $linked_profile); //check if user identity is verified
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			= array();
		$quote_id		= !empty( $_POST['quote'] ) ? intval( $_POST['quote'] ) : '';
		$service_id		= !empty( $quote_id ) ? get_post_meta($quote_id,'service',true) : '';
		$cart_meta		= array();
		$addon_data		= array();
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if ( is_user_logged_in() ) {
			$user_type	= apply_filters('workreap_get_user_type', $current_user->ID);
			if( $user_type !== 'employer' ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You do not have permission to buy this service. Only employers can buy the services', 'workreap');
				wp_send_json($json);
			}
			
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You do not have permission to buy this service. Please log in to continue', 'workreap');
			wp_send_json($json);
		}
		
		do_action('workreap_check_switch_user_status', $service_id);

		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$bk_settings 		= fw_get_db_settings_option('hiring_payment_settings');
		}

		$product_id	= workreap_get_hired_product_id();

		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {

				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $current_user->ID;
				$price				= get_post_meta($quote_id ,'price',true);
				$single_service_price	= $price;

				$delivery_time		= get_post_meta($quote_id ,'date',true);;
				
				$admin_shares 		= 0.0;
				$freelancer_shares 	= 0.0;

				if( !empty( $price ) ){
					$service_fee		= workreap_commission_fee($price,'services',$service_id);
					if( !empty( $service_fee ) ){
						$admin_shares       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
						$freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
					} else{
						$admin_shares       = 0.0;
						$freelancer_shares  = $price;
					}

					$admin_shares 		= number_format($admin_shares,2,'.', '');
					$freelancer_shares 	= number_format($freelancer_shares,2,'.', '');
				}
				
				$employer_service_fee		= workreap_employer_hiring_payment_setting('services',$price);
				
				$cart_meta['service_id']		= $service_id;
				$cart_meta['delivery_date']		= $delivery_time;
				$cart_meta['price']				= $price;
				$cart_meta['processing_fee']	= !empty( $employer_service_fee['commission_amount'] ) ? $employer_service_fee['commission_amount'] : 0.0;
				$cart_meta['service_price']		= $single_service_price;
				$cart_meta['addons']			= $addon_data;
				$cart_meta['quote']			    = $quote_id;

				$hired_freelance_id			= get_post_field('post_author',$service_id);
				$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';

				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'price'				=> workreap_price_format($price,'return'),
					'payment_type'     	=> 'hiring_service',
					'admin_shares'     	=> $admin_shares,
					'freelancer_shares' => $freelancer_shares,
					'employer_id' 		=> $current_user->ID,
					'freelancer_id' 	=> $hired_freelance_id,
					'current_project' 	=> $service_id,
				);
				
				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
				
				if( !empty( $bk_settings['gadget'] ) && $bk_settings['gadget'] === 'enable' ) {
				
					$json['type'] 			= 'checkout';
					$json['message'] 		= esc_html__('Please wait you are redirecting to the checkout page.', 'workreap');
					$json['checkout_url']	= wc_get_checkout_url();
					wp_send_json($json);
				}else{
					workreap_create_woocommerce_order($service_id);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please install WooCommerce plugin to process this order', 'workreap');
				wp_send_json($json);
			}
		} else{
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Hiring settings is missing, please contact to administrator.', 'workreap');
			wp_send_json($json);
		}
					
	}

	add_action( 'wp_ajax_workreap_hire_quote', 'workreap_hire_quote' );
}

/**
 * load more services
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_get_more_services' ) ) {

	function workreap_get_more_services() {
		$json			= array();
		$page			= !empty( $_POST['page'] ) ? intval( $_POST['page'] ) : '';
		$author_id		= !empty( $_POST['author_id'] ) ? intval( $_POST['author_id'] ) : '';
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$show_posts		= 3;
		$order 			= 'DESC';
		$sorting 		= 'ID';
		
		$post_id			= workreap_get_linked_profile_id( $author_id );
		$freelancer_title 	= get_the_title( $post_id );
		
		if(!empty($author_id) && !empty($page)) {
			$service_args = array(
							'posts_per_page' 	=> $show_posts,
							'post_type' 		=> 'micro-services',
							'orderby' 			=> $sorting,
							'order' 			=> $order,
							'author' 			=> $author_id,
							'paged' 			=> $page,
							'suppress_filters' 	=> false
						);
			
			$services_query		= new WP_Query($service_args);
			if( $services_query->have_posts() ){
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Services found', 'workreap');
				ob_start();
				$counter	= 0;
				while ($services_query->have_posts()) : $services_query->the_post();
					global $post;
				
					$counter ++;
					$service_url		= get_the_permalink();
					
					$db_docs			= array();
					$db_price			= '';
					$delivery_time		= '';
					$order_details		= '';

					if (function_exists('fw_get_db_post_option')) {
						$db_docs   			= fw_get_db_post_option($post->ID,'docs');
						$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
						$order_details   	= fw_get_db_post_option($post->ID,'order_details');
						$db_price   		= fw_get_db_post_option($post->ID,'price');
					}
					$freelancer_avatar = apply_filters(
						'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $post_id), array('width' => 100, 'height' => 100) 
					);

				?>
					<div class="col-12 col-sm-12 col-md-6 col-lg-4 float-left">
						<div class="wt-freelancers-info">
							<?php if( !empty( $db_docs ) ) {?>
								<div class="wt-freelancers wt-freelancers-services owl-carousel">
									<?php
										foreach( $db_docs as $key => $doc ){
											$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
											$img_url		= wp_get_attachment_image_url($attachment_id,'medium');
											?>
											<figure class="item">
												<a href="<?php echo esc_url( $service_url );?>">
													<img src="<?php echo esc_url($img_url);?>" alt="<?php esc_attr_e('Service ','workreap');?>" class="item">
												</a>
											</figure>
									<?php } ?>
								</div>
							<?php } ?>
							<?php do_action('workreap_service_print_featured', $post->ID); ?>
							<div class="wt-freelancers-details">
								<?php if( !empty( $freelancer_avatar ) ){?>
									<figure class="wt-freelancers-img">
										<img src="<?php echo esc_url($freelancer_avatar); ?>" alt="<?php esc_attr_e('Service ','workreap');?>">
									</figure>
								<?php }?>
								<div class="wt-freelancers-content">
									<div class="dc-title">
										<?php do_action( 'workreap_get_verification_check', $post_id, $freelancer_title ); ?>
										<h3><?php echo esc_html( $post->post_title);?></h3>
										<?php if( !empty( $db_price ) ){?>
											<span><?php esc_html_e('Starting From:','workreap');?>&nbsp;<strong><?php echo workreap_price_format($db_price);?></strong>
											</span>
										<?php }?>
									</div>
								</div>
								<div class="wt-freelancers-rating">
									<ul>
										<?php do_action('workreap_service_get_reviews',$post_id,'v1'); ?>
									</ul>
								</div>
							</div>
						</div>
					</div>	
				<?php
					
				endwhile;
				wp_reset_postdata();
				
				$review				= ob_get_clean();
				$json['services'] 	= $review;
			} else{
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No more service', 'workreap');
				$json['services'] 	= 'null';
			}
		}
		wp_send_json($json);			
	}

	add_action( 'wp_ajax_workreap_get_more_services', 'workreap_get_more_services' );
	add_action( 'wp_ajax_nopriv_workreap_get_more_services', 'workreap_get_more_services' );
}

/**
 * Post a Addons Service
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_post_service' ) ) {

	function workreap_post_service() {
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		$hide_map 			= 'show';
		$system_access		= '';
		$service_faq_option	= '';
		$job_status			= '';
		$files              = !empty( $_POST['service']['service_documents'] ) ? $_POST['service']['service_documents'] : array();

		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		if (function_exists('fw_get_db_post_option') ) {
			$hide_map		= fw_get_db_settings_option('hide_map');
			$job_status		= fw_get_db_settings_option('service_status');
			$system_access	= fw_get_db_settings_option('system_access');
			$total_limit	= fw_get_db_settings_option('default_service_images');
			$required_limit	= fw_get_db_settings_option('service_images_required');

			$minimum_service_price			= fw_get_db_settings_option('minimum_service_price');
			$service_faq_option				= fw_get_db_settings_option('service_faq_option', $default_value = null);
		}
		
		$total_limit			= !empty($total_limit) ? intval($total_limit) : 100;
		$minimum_service_price	= !empty($minimum_service_price) ? intval($minimum_service_price) : 1;
		$job_status				= !empty( $job_status ) ? $job_status : 'pending';

		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$user_id	= workreap_get_linked_profile_id($current_user->ID);
		do_action('workreap_check_post_author_status',$user_id); //check if user is not blocked or deactive
		do_action('workreap_check_post_author_identity_status', $user_id); //check if user identity is verified
		
		$json 		= array();
		$current 	= !empty($_POST['id']) ? esc_attr($_POST['id']) : '';
		
		if( apply_filters('workreap_is_service_posting_allowed','wt_services', $current_user->ID) === false && empty($current) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You’ve consumed all you points or your package has get expired. Please upgrade your package','workreap');
			wp_send_json( $json );
		}
		
		$is_featured              = !empty( $_POST['service']['is_featured'] ) ? $_POST['service']['is_featured'] : '';
		
		$required = array(
            'title'   			=> esc_html__('Service title is required', 'workreap'),
			'price'  			=> esc_html__('Service price is required', 'workreap'),
			'categories'   		=> esc_html__('Category is required', 'workreap')
        );
		
		$required	= apply_filters('workreap_filter_service_required_fields', $required);
		
        foreach ($required as $key => $value) {
			if( empty( $_POST['service'][$key] ) ){
				$json['type'] = 'error';
				$json['message'] = $value;        
				wp_send_json($json);
			}
			
			if( $key === 'price' && empty( floatval( $_POST['service'][$key] ) ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			} else if( $key === 'price' &&  $_POST['service'][$key] < $minimum_service_price ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Minimum service price should be ', 'workreap').$minimum_service_price;        
				wp_send_json($json);
			}
			
        }
		
		//Addon check
		if( !empty( $_POST['addons_service'] ) ){
			$required = array(
				'title'   			=> esc_html__('Addons Service title is required', 'workreap'),
				'price'  			=> esc_html__('Addons Service price is required', 'workreap'),
			);
			
			foreach( $_POST['addons_service'] as $key => $item ) {
				foreach( $required as $inner_key => $item_check ) {
					if( empty( $_POST['addons_service'][$key][$inner_key] ) ){
						$json['type'] = 'error';
						$json['message'] =  $item_check;      
						wp_send_json($json);
					}
				}
			}	
		}
		
		//required images
		if(!empty($required_limit) && $required_limit === 'yes' ){
			if(empty($files)){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('At-least one image is required for this service','workreap');
				wp_send_json( $json );
			}
		}
		

		//extract the job variables
		extract($_POST['service']);
		$title				= !empty( $title ) ? $title : rand(1,999999);
		$description		= !empty( $description ) ?  $description : '';
		
		if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
			$current = !empty($_POST['id']) ? esc_attr($_POST['id']) : '';
			
			$post_author = get_post_field('post_author', $current);
            $post_id 	 = $current;
            $status 	 = get_post_status($post_id);
			
			if( intval( $post_author ) === intval( $current_user->ID ) ){
				$article_post = array(
					'ID' 			=> $current,
					'post_title' 	=> $title,
					'post_content' 	=> $description,
					'post_status' 	=> $status,
				);

				wp_update_post($article_post);
			} else{
				$json['type'] = 'error';
				$json['message'] = esc_html__('You are not authorized to update this service', 'workreap');
				wp_send_json( $json );
			}
			
			//change status on update
			do_action('workreap_update_post_status_action',$post_id,'service'); //Admin will get an email to publish it
			
			$gallery_old_attachment       = fw_get_db_post_option($current, 'docs', true);
			if(!empty($gallery_old_attachment)){
				$gallery_old_attachment 	= wp_list_pluck($gallery_old_attachment,'attachment_id');
			}

		} else{
			//Create Post
			$user_post = array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_status'   => $job_status,
				'post_content'  => $description,
				'post_author'   => $current_user->ID,
				'post_type'     => 'micro-services',
			);

			$post_id    		= wp_insert_post( $user_post );
			
			//featured string
			update_post_meta( $post_id, '_featured_service_string', 0 );
			
			$remaning_services		= workreap_get_subscription_metadata( 'wt_services',intval($current_user->ID) );
			$remaning_services  	= !empty( $remaning_services ) ? intval($remaning_services) : 0;

			if( !empty( $remaning_services) && $remaning_services >= 1 ) {
				$update_services	= intval( $remaning_services ) - 1 ;
				$update_services	= intval($update_services);

				$wt_subscription 	= get_user_meta(intval($current_user->ID), 'wt_subscription', true);
				$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();

				$wt_subscription['wt_services'] = $update_services;
				update_user_meta( intval($current_user->ID), 'wt_subscription', $wt_subscription);
			}
			
			$expiry_string		= workreap_get_subscription_metadata( 'subscription_featured_string',$current_user->ID );
			if( !empty($expiry_string) ) {
				update_post_meta($post_id, '_expiry_string', $expiry_string);
			}
		}
				
		if( $post_id ){
			//Upload files from temp folder to uploads
			$service_files		= array();

			if( !empty( $files ) ) {
				$files 			= array_slice($files, 0, $total_limit);
				foreach ( $files as $key => $value ) {
					if( !empty( $value['attachment_id'] ) ){
						$service_files[] = $value;
					} else{
						$service_files[] = workreap_temp_upload_to_media($value, $post_id);
					} 	
				}
				
				$gallery_new_attachment 	= wp_list_pluck($service_files,'attachment_id');
				if(!empty($gallery_old_attachment) ){
					foreach($gallery_old_attachment as $key => $delete_media){
						if(!empty($delete_media) && !empty($gallery_new_attachment) && !in_array($delete_media,$gallery_new_attachment)){
							$delete_list[] = $delete_media;
							wp_delete_attachment( $delete_media, true );
						}
					}
				}
				
			}else{
				if(!empty($gallery_old_attachment) ){
					foreach($gallery_old_attachment as $key => $delete_media){
						if(!empty($delete_media)){
							wp_delete_attachment( $delete_media, true );
						}
					}
				}
			}
			
			if( !empty( $service_files [0]['attachment_id'] ) ){
				set_post_thumbnail( $post_id, $service_files [0]['attachment_id']);
			}
			
			$downloadable_files		= !empty( $_POST['service']['downloadable_files'] ) ? $_POST['service']['downloadable_files'] : array();
			$downloadables			= array();
			
			if( !empty( $downloadable_files ) ) {
				foreach ( $downloadable_files as $key => $value ) {
					if( !empty( $value['attachment_id'] ) ){
						$downloadables[] = $value;
					} else{
						$downloadables[] = workreap_temp_upload_to_media($value['url'], $post_id,true);
					} 	
				}                
			}
			
			$is_downloable	= !empty( $_POST['service']['downloadable'] ) ? $_POST['service']['downloadable'] : '';
			
			if( !empty( $is_downloable ) && $is_downloable === 'yes' && !empty( $downloadables ) ){
				update_post_meta( $post_id, '_downloadable_files', $downloadables );
			}
			
			update_post_meta( $post_id, '_downloadable', $is_downloable );
			
			//Set terms ( cat , language)
			$categories		= !empty( $_POST['service']['categories'] ) ? $_POST['service']['categories'] : array();
			$languages		= !empty( $_POST['service']['languages'] ) ? $_POST['service']['languages'] : array();
			
			$price	        = !empty( $_POST['service']['price'] ) ? workreap_wmc_compatibility( $_POST['service']['price'] ) : '';
			$delivery_time  = !empty( $_POST['service']['delivery_time'] ) ? array($_POST['service']['delivery_time']) : array();
			$response_time  = !empty( $_POST['service']['response_time'] ) ? array($_POST['service']['response_time']) : array();
			$english_level	= !empty( $_POST['service']['english_level'] ) ? $_POST['service']['english_level'] : '';
			
			$addons	        = !empty( $_POST['service']['addons'] ) ? $_POST['service']['addons'] : array();
			
			if( !empty( $_POST['addons_service'] ) ){
				foreach( $_POST['addons_service'] as $key => $item ) {

					$user_post = array(
						'post_title'    => wp_strip_all_tags( $item['title'] ),
						'post_excerpt'  => $item['description'],
						'post_author'   => $current_user->ID,
						'post_type'     => 'addons-services',
						'post_status'	=> 'publish'
					);

					$addon_post_id    		= wp_insert_post( $user_post );
					$addons[]				= $addon_post_id;
					$addon_price	        = !empty( $item['price'] ) ? $item['price'] : '';

					//update
					update_post_meta($addon_post_id, '_price', workreap_wmc_compatibility( $addon_price));

					//update unyson meta
					$fw_options = array();
					$fw_options['price']         	= workreap_wmc_compatibility( $addon_price );
					
					//Update User Profile
					fw_set_db_post_option($addon_post_id, null, $fw_options);
				}	
			}

			update_post_meta( $post_id, '_addons', $addons );
			
			if( !empty($is_featured) && $is_featured === 'on' ){
				$is_featured_service	= get_post_meta($post_id,'_featured_service_string',true);
				
				if(empty( $is_featured_service )){
					$featured_services	= workreap_featured_service( $current_user->ID );
					if( $featured_services || $system_access == 'both' ) {
						$featured_string	= workreap_is_feature_value( 'subscription_featured_string', $current_user->ID );
						update_post_meta($post_id, '_featured_service_string', 1);
					}

					$remaning_featured_services		= workreap_get_subscription_metadata( 'wt_featured_services',intval($current_user->ID) );
					$remaning_featured_services  	= !empty( $remaning_featured_services ) ? intval($remaning_featured_services) : 0;

					if( !empty( $remaning_featured_services) && $remaning_featured_services >= 1 ) {
						$update_featured_services	= intval( $remaning_featured_services ) - 1 ;
						$update_featured_services	= intval( $update_featured_services );

						$wt_subscription 	= get_user_meta(intval($current_user->ID), 'wt_subscription', true);
						$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();

						$wt_subscription['wt_featured_services'] = $update_featured_services;
						update_user_meta( intval($current_user->ID), 'wt_subscription', $wt_subscription);
					}
				}
			} else {
				update_post_meta( $post_id, '_featured_service_string', 0 );
			}

			if( !empty( $categories ) ){
				if (function_exists('fw_get_db_post_option') ) {
					$services_categories	= fw_get_db_settings_option('services_categories');
				}
				
				$services_categories	= !empty($services_categories) ? $services_categories : 'no';
				if( !empty($services_categories) && $services_categories === 'no' ) {
					$taxonomy_type	= 'project_cat';
				}else{
					$taxonomy_type	= 'service_categories';
				}
				
				wp_set_post_terms( $post_id, $categories, $taxonomy_type );
				$service_cat_list = wp_get_post_terms( $post_id, $taxonomy_type, array( 'fields' => 'names' ) );
				
			}else{
				$service_cat_list = array();
			}

			//update for keyword search
			update_post_meta($post_id, '_categories_names', $service_cat_list);
			update_post_meta($post_id, 'post_rejected', '');
			
			wp_set_post_terms( $post_id, $languages, 'languages' );
			wp_set_post_terms( $post_id, $delivery_time, 'delivery' );
			wp_set_post_terms( $post_id, $response_time, 'response_time' );

					
			//update location
			$address    = !empty( $_POST['service']['address'] ) ? esc_attr( $_POST['service']['address'] ) : '';
			$country    = !empty( $_POST['service']['country'] ) ? $_POST['service']['country'] : '';
			$latitude   = !empty( $_POST['service']['latitude'] ) ? esc_attr( $_POST['service']['latitude'] ): '';
			$longitude  = !empty( $_POST['service']['longitude'] ) ? esc_attr( $_POST['service']['longitude'] ): '';
			$service_map  	= !empty( $_POST['service']['service_map'] ) ? esc_attr( $_POST['service']['service_map'] ): 'on';
			$videos 		= !empty( $_POST['service']['videos'] ) ? $_POST['service']['videos'] : array();

			update_post_meta($post_id, '_country', $country);
			
			//Set country for unyson
			$locations = get_term_by( 'slug', $country, 'locations' );
			
			$location = array();
			if( !empty( $locations ) ){
				$location[0] = $locations->term_id;

				if( !empty( $location ) ){
					wp_set_post_terms( $post_id, $location, 'locations' );
				}
			}
			
			//update
			update_post_meta($post_id, '_price', $price);
			update_post_meta($post_id, '_english_level', $english_level);

			//update unyson meta
			$fw_options = array();
			if(!empty($service_faq_option) && $service_faq_option == 'yes' ) {
				$faq 					= !empty( $_POST['settings']['faq'] ) ? $_POST['settings']['faq'] : array();
				$fw_options['faq']      = $faq;
			}
			$fw_options['price']         	= $price;
			$fw_options['downloadable']     = $is_downloable;
			$fw_options['english_level']    = $english_level;
			$fw_options['docs']    			= $service_files;
			
			$fw_options['address']            	 = $address;
			$fw_options['longitude']          	 = $longitude;
			$fw_options['latitude']           	 = $latitude;
			$fw_options['country']            	 = $location;
			$fw_options['service_map']           = $service_map;
			$fw_options['videos']            	 = $videos;

			//Update User Profile
			fw_set_db_post_option($post_id, null, $fw_options);
			
			if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your service has been updated', 'workreap');
			} else{
				//Send email to users
				if(!empty($job_status) && $job_status === 'publish') {
					$json['message'] 	= esc_html__('Your service has been posted.', 'workreap');
				}else{
					$json['message'] 	= esc_html__('Your service has been sent to administrator for the review.', 'workreap');
				}
				
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapServicePost')) {
						$emailData 	  = array();
						$email_helper = new WorkreapServicePost();
						$freelancer_name 		= workreap_get_username($current_user->ID);
						$freelancer_email 		= get_userdata( $current_user->ID )->user_email;

						$freelancer_profile 	= get_permalink($user_id);
						$service_title 			= get_the_title($post_id);
						$service_link 			= get_permalink($post_id);

						$emailData['freelancer_name'] 	= esc_html( $freelancer_name );
						$emailData['freelancer_email'] 	= esc_html( $freelancer_email );
						$emailData['freelancer_link'] 	= esc_url( $freelancer_profile );
						$emailData['status'] 			= esc_html( $job_status );
						$emailData['service_title'] 	= esc_html( $service_title );
						$emailData['service_link'] 		= esc_url( $service_link );

						$email_helper->send_admin_service_post($emailData);
						$email_helper->send_freelancer_service_post($emailData);

						//Push notification
						$push	= array();
						$push['freelancer_id']		= $current_user->ID;
						$push['service_id']			= $post_id;
						$push['%freelancer_name%']	= $freelancer_name;
						$push['%freelancer_link%']	= $freelancer_profile;
						$push['%service_title%']	= $service_title;
						$push['%service_link%']		= $service_link;
						$push['type']				= 'post_service';

						do_action('workreap_user_push_notify',array($current_user->ID),'','pusher_freelancer_service_post_content',$push);
					}
				}
				
				$json['type'] 		= 'success';	
			}
			
			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('services', $current_user->ID, true,'posted');
			wp_send_json( $json );
		} else{
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
			wp_send_json( $json );
		}

	}

	add_action( 'wp_ajax_workreap_post_service', 'workreap_post_service' );
}

/**
 * Post a quote
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_post_quote' ) ) {

	function workreap_post_quote() {
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}


		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$user_id	= workreap_get_linked_profile_id($current_user->ID);
		do_action('workreap_check_post_author_status',$user_id); //check if user is not blocked or deactive
		do_action('workreap_check_post_author_identity_status', $user_id); //check if user identity is verified
		
		$json 		= array();

		$required = array(
            'employer'   	=> esc_html__('Please select the employer', 'workreap'),
			'service'  		=> esc_html__('Select service which quote you want to send', 'workreap'),
			'price'   		=> esc_html__('Add quote price', 'workreap'),
			'date'   		=> esc_html__('Add dilivery date', 'workreap')
        );
		
		$required	= apply_filters('workreap_filter_quote_required_fields', $required);
		
        foreach ($required as $key => $value) {
			if( empty( $_POST['quote'][$key] ) ){
				$json['type'] = 'error';
				$json['message'] = $value;        
				wp_send_json($json);
			}
			
			if( $key === 'price' && empty( floatval( $_POST['quote'][$key] ) ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
			
        }

		//extract the job variables
		extract($_POST['quote']);

		$title				= !empty( $service ) ? get_the_title($service) : rand(1,999999);
		$description		= !empty( $description ) ?  $description : '';
		
		if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
			$current = !empty($_POST['id']) ? esc_html($_POST['id']) : '';
			
			$post_author = get_post_field('post_author', $current);
            $post_id 	 = $current;

			if( intval( $post_author ) === intval( $current_user->ID ) ){
				$article_post = array(
					'ID' 			=> $current,
					'post_title' 	=> $title,
					'post_content' 	=> $description,
					'post_status' 	=> 'publish',
				);

				wp_update_post($article_post);
			} else{
				$json['type'] = 'error';
				$json['message'] = esc_html__('You are not authorized to update this quote', 'workreap');
				wp_send_json( $json );
			}

		} else{
			//Create Post
			$user_post = array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_status'   => 'publish',
				'post_content'  => $description,
				'post_author'   => $current_user->ID,
				'post_type'     => 'send-quote',
			);

			$post_id    		= wp_insert_post( $user_post );

		}
				
		if( $post_id ){
			update_post_meta( $post_id, 'employer', $employer );
			update_post_meta( $post_id, 'service', $service );
			update_post_meta( $post_id, 'price', $price );
			update_post_meta( $post_id, 'date', $date );
			update_post_meta( $post_id, 'hiring_status', 'pending' );
			update_post_meta( $post_id, 'declined', 'no' );
			
			//Email variables
			$service_id				= $service;
			$employer_id			= $employer;

			//Freelancer
			$freelancer_name 		= workreap_get_username($current_user->ID);
			$linked_profile  		= workreap_get_linked_profile_id($current_user->ID);
			$freelancer_link 			= get_the_permalink($linked_profile);

			//employer
			$employer_name 			= workreap_get_username($employer_id);
			$employer_linked  		= workreap_get_linked_profile_id($employer_id);
			$employer_link 			= get_the_permalink($employer_linked);
			$email_to 				= get_userdata( $employer_id )->user_email;

			//service
			$service_name 			= get_the_title($service_id);
			$service_link 			= get_the_permalink($service_id);


			$emailData 	  = array();
			$emailData['freelancer_name'] 		= esc_html( $freelancer_name );
			$emailData['freelancer_link'] 		= esc_url( $freelancer_link );
			$emailData['service_name'] 			= esc_html( $service_name );
			$emailData['service_link'] 			= esc_url( $service_link );
			$emailData['employer_name'] 		= esc_html( $employer_name );
			$emailData['employer_link'] 		= esc_url( $employer_link );
			$emailData['email_to'] 				= esc_html( $email_to );

			$push	= array();
			$push['freelancer_id']		= $current_user->ID;
			$push['employer_id']		= $employer_id;
			$push['service_id']			= $service_id;

			$push['%freelancer_link%']	= $emailData['freelancer_link'];
			$push['%freelancer_name%']	= $emailData['freelancer_name'];
			$push['%employer_name%']	= $emailData['employer_name'] ;
			$push['%employer_link%']	= $emailData['employer_link'];
			$push['%service_name%']		= $emailData['service_name'];
			$push['%service_link%']		= $emailData['service_link'];

			if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapServiceOffer')) {
						$email_helper = new WorkreapServiceOffer();
						$email_helper->update_offer($emailData);

						//Push notification
						$push['type']				= 'quote_updated';
						do_action('workreap_user_push_notify',array($employer_id),'','pusher_emp_noty_update_offer',$push);
					}
				}

				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your quote has been updated', 'workreap');
			} else{
				//Send email to users
				$json['message'] 	= esc_html__('Your quote has been sent to employer.', 'workreap');
				
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapServiceOffer')) {
						
						$email_helper = new WorkreapServiceOffer();
						$email_helper->send_offer($emailData);

						//Push notification
						$push['type']				= 'quote_sent';
						do_action('workreap_user_push_notify',array($employer_id),'','pusher_emp_noty_send_offer',$push);

					}
				}
				
				$json['type'] 		= 'success';	
			}
			
			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('services', $current_user->ID, true,'quote_listing');
			wp_send_json( $json );
		} else{
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
			wp_send_json( $json );
		}

	}

	add_action( 'wp_ajax_workreap_post_quote', 'workreap_post_quote' );
}

/**
 * Post a quote
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_decline_quote' ) ) {

	function workreap_decline_quote() {
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		//Get quote decline ID from cookie set with js
		$quote_id	= !empty($_POST['quote_id']) ?  $_POST['quote_id'] : '';

		if( empty( $quote_id ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
			wp_send_json($json);
		}

		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$user_id	= workreap_get_linked_profile_id($current_user->ID);
		do_action('workreap_check_post_author_status',$user_id); //check if user is not blocked or deactive
		do_action('workreap_check_post_author_identity_status', $user_id); //check if user identity is verified
		
		$json 		= array();

		$required = array(
            'reason'   	=> esc_html__('Please add decline reason', 'workreap'),
        );
		
		$required	= apply_filters('workreap_filter_quote_required_fields', $required);
		
        foreach ($required as $key => $value) {
			if( empty( $_POST['quote'][$key] ) ){
				$json['type'] = 'error';
				$json['message'] = $value;        
				wp_send_json($json);
			}
        }

		//extract the job variables
		extract($_POST['quote']);

		$reason				= !empty( $reason ) ? $reason : '';
		$quote_post = array(
			'ID' 			=> $quote_id,
			'post_status' 	=> 'pending',
		);

		wp_update_post($quote_post);

		update_post_meta( $quote_id, 'reason', $reason );
		update_post_meta( $quote_id, 'declined', 'yes' );
			
			
		$json['message'] 	= esc_html__('Quote has been declined. Freelancer will be informed by notification', 'workreap');
			
		if (class_exists('Workreap_Email_helper')) {
			if (class_exists('WorkreapServiceOffer')) {
				$emailData 	  = array();
				$email_helper = new WorkreapServiceOffer();

				//Email variables
				$service_id				= get_post_meta($quote_id,'service',true);
				$employer_id			= get_post_meta($quote_id,'employer',true);

				//employer
				$employer_name 			= workreap_get_username($current_user->ID);
				$employer_linked  		= workreap_get_linked_profile_id($current_user->ID);
				$employer_link 			= get_the_permalink($employer_linked);

				//Freelancer
				$quote_post 		= get_post( $quote_id );
				$freelancer_id		= $quote_post->post_author;

				$freelancer_name 		= workreap_get_username($freelancer_id);
				$freelancer_linked  	= workreap_get_linked_profile_id($freelancer_id);
				$freelancer_link 		= get_the_permalink($freelancer_linked);
				$email_to 				= get_userdata( $freelancer_id )->user_email;

				//service
				$service_name 			= get_the_title($service_id);
				$service_link 			= get_the_permalink($service_id);


				$emailData 	  = array();
				$emailData['freelancer_name'] 		= esc_html( $freelancer_name );
				$emailData['freelancer_link'] 		= esc_url( $freelancer_link );
				$emailData['service_name'] 			= esc_html( $service_name );
				$emailData['service_link'] 			= esc_url( $service_link );
				$emailData['employer_name'] 		= esc_html( $employer_name );
				$emailData['employer_link'] 		= esc_url( $employer_link );
				$emailData['email_to'] 				= esc_html( $email_to );

				$email_helper->decline_offer($emailData);

				//Push notification
				$push	= array();
				$push['freelancer_id']		= $freelancer_id;
				$push['employer_id']		= $employer_id;
				$push['service_id']			= $service_id;

				$push['%freelancer_link%']	= $emailData['freelancer_link'];
				$push['%freelancer_name%']	= $emailData['freelancer_name'];
				$push['%employer_name%']	= $emailData['employer_name'] ;
				$push['%employer_link%']	= $emailData['employer_link'];
				$push['%service_name%']		= $emailData['service_name'];
				$push['%service_link%']		= $emailData['service_link'];

				$push['type']				= 'quote_declined';
				do_action('workreap_user_push_notify',array($freelancer_id),'','pusher_quote_rejected_content',$push);
			}
		}
		
		$json['type'] 		= 'success';
		wp_send_json( $json );	

	}

	add_action( 'wp_ajax_workreap_decline_quote', 'workreap_decline_quote' );
}



/**
 * Post a portfolio
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_add_portfolio' ) ) {

	function workreap_add_portfolio() {
		global $current_user;
		$json 		= array();
		$current 	= !empty($_POST['id']) ? esc_attr($_POST['id']) : '';
		
		$linked_profile  	= workreap_get_linked_profile_id($current_user->ID);

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		} //if user is not logged in then prevent

		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		} //if demo site then prevent

		$do_check = check_ajax_referer('ajax_nonce', 'nonce', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		do_action('workreap_check_post_author_status', $linked_profile); //check if user is not blocked or deactive
		do_action('workreap_check_post_author_identity_status', $linked_profile); //check if user identity is verified
		
		$ppt_option			= '';
		$total_limit		= '';
		if( function_exists('fw_get_db_settings_option') ){
			$ppt_option		= fw_get_db_settings_option('ppt_template');
			$total_limit	= fw_get_db_settings_option('default_portfolio_images');
		}
		
		$total_limit			= !empty($total_limit) ? intval($total_limit) : 100;

		$required = array(
            'title'   			=> esc_html__('Portfolio title is required', 'workreap'),
			'gallery_imgs'   	=> esc_html__('At-least one portfolio image is required', 'workreap'),
        );
		
		$required	= apply_filters('workreap_filter_portfolio_required_fields', $required);
		
        foreach ($required as $key => $value) {
			if( empty( $_POST['portfolio'][$key] ) ){
				$json['type'] = 'error';
				$json['message'] = $value;        
				wp_send_json($json);
			}
        }

		if( function_exists('workreap_check_video_url') ){
			if( !empty($_POST['portfolio']['videos']) ){
				foreach( $_POST['portfolio']['videos'] as $video_url ){
					if( empty($video_url)  ){
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Please add valid video URL','workreap');        
						wp_send_json($json);
					}
					
				}
			}
		}
		//extract the portfolio variables
		extract($_POST['portfolio']);
		
		$title				= !empty( $title ) ? $title : '';
		$description		= !empty( $description ) ?  $description : '';
		
		if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
			$current = !empty($_POST['id']) ? esc_attr($_POST['id']) : '';
			
			$post_author = get_post_field('post_author', $current);
            $post_id 	 = $current;
            $status 	 = get_post_status($post_id);
			
			if( intval( $post_author ) === intval( $current_user->ID ) ){
				$portfolio_post = array(
					'ID' 			=> $current,
					'post_title' 	=> $title,
					'post_content' 	=> $description,
					'post_status' 	=> $status,
				);

				wp_update_post($portfolio_post);

				if( !empty( $categories ) ){
					wp_set_post_terms( $post_id, $categories, 'portfolio_categories' );
				}
	
				if( !empty( $_POST['tags'] ) ) {
					wp_set_post_terms( $post_id, $_POST['tags'], 'portfolio_tags' );
				}
			} else{
				$json['type'] = 'error';
				$json['message'] = esc_html__('You are not authorized to perform this action', 'workreap');
				wp_send_json( $json );
			}
			
		} else{
			//Create Post
			$user_post = array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_status'   => 'publish',
				'post_content'  => $description,
				'post_author'   => $current_user->ID,
				'post_type'     => 'wt_portfolio',
			);

			$post_id    		= wp_insert_post( $user_post );

			//Prepare Params
			$params_array['user_identity'] = $current_user->ID;
			$params_array['user_role'] = apply_filters('workreap_get_user_type', $current_user->ID );
			$params_array['type'] = 'portfolio_upload';

			//child theme : update extra settings
			do_action('wt_process_portfolio_upload', $params_array);
		}
				
		if( $post_id ){
			//Upload files from temp folder to uploads
			$files              = !empty( $_POST['portfolio']['gallery_imgs'] ) ? $_POST['portfolio']['gallery_imgs'] : array();
			$documents          = !empty( $_POST['portfolio']['documents'] ) ? $_POST['portfolio']['documents'] : array();
			$zip_files          = !empty( $_POST['portfolio']['zip_attachments'] ) ? $_POST['portfolio']['zip_attachments'] : array();
			$videos				= !empty( $_POST['portfolio']['videos'] ) ? $_POST['portfolio']['videos'] : array();

			

			if( !empty($ppt_option) && $ppt_option === 'enable' ){
				$ppt_template		= !empty($_POST['ppt_template']) ? $_POST['ppt_template'] : '';
				update_post_meta( $post_id, 'ppt_template', $ppt_template );
			}

			$gallery_imgs		= array();
			$doc_attachemnts	= array();
			$zip_attachements	= array();

			if( !empty( $files ) ) {
				foreach ( $files as $key => $value ) {
					$files 			= array_slice($files, 0, $total_limit);
					if( !empty( $value['attachment_id'] ) ){
						$gallery_imgs[] = $value;
					} else{
						$gallery_imgs[] = workreap_temp_upload_to_media($value, $post_id);
					} 	
				}                
			}

			if( !empty( $documents ) ) {
				foreach ( $documents as $key => $value ) {
					if( !empty( $value['attachment_id'] ) ){
						$doc_attachemnts[] = $value;
					} else{
						$doc_attachemnts[] = workreap_temp_upload_to_media($value, $post_id);
					} 	
				}                
			}

			if( !empty( $zip_files ) ) {
				foreach ( $zip_files as $key => $value ) {
					if( !empty( $value['attachment_id'] ) ){
						$zip_attachements[] = $value;
					} else{
						$zip_attachements[] = workreap_temp_upload_to_media($value, $post_id);
					} 	
				}                
			}
			
			if( !empty( $gallery_imgs[0]['attachment_id'] ) ){
				set_post_thumbnail( $post_id, $gallery_imgs[0]['attachment_id']);
			}
			
			$custom_link	= !empty( $_POST['portfolio']['custom_link'] ) ? $_POST['portfolio']['custom_link'] : '';

			if( !empty( $categories ) ){
				wp_set_post_terms( $post_id, $categories, 'portfolio_categories' );
			}

			if( !empty( $_POST['tags'] ) ) {
				wp_set_post_terms( $post_id, $_POST['tags'], 'portfolio_tags' );
			}

			//update unyson meta
			$fw_options = array();
			$fw_options['custom_link']  	= $custom_link;
			$fw_options['gallery_imgs']    	= $gallery_imgs;
			$fw_options['documents']    	= $doc_attachemnts;
			$fw_options['zip_attachments']  = $zip_attachments;
			$fw_options['videos']    		= $videos;

			fw_set_db_post_option($post_id, null, $fw_options);
			

			if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your portfolio has been updated', 'workreap');
			} else{
				$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('portfolios', $current_user->ID, true, 'posted');
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your portfolio has been added.', 'workreap');
			}

			wp_send_json( $json );
		} else{
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
			wp_send_json( $json );
		}

	}

	add_action( 'wp_ajax_workreap_add_portfolio', 'workreap_add_portfolio' );
}

/**
 * Post a Service
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_post_addons_service' ) ) {

	function workreap_post_addons_service() {
		global $current_user;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id	= workreap_get_linked_profile_id($current_user->ID);
		$json 		= array();
		$current 	= !empty($_POST['id']) ? esc_attr($_POST['id']) : '';
		
		$required = array(
            'title'   			=> esc_html__('Addons Service title is required', 'workreap'),
			'price'  			=> esc_html__('Addons Service price is required', 'workreap'),
        );
		
        foreach ($required as $key => $value) {
			if( empty( $_POST['addons_service'][$key] ) ){
				$json['type'] = 'error';
				$json['message'] = $value;        
				wp_send_json($json);
			}
        }
		
		//extract the job variables
		extract($_POST['addons_service']);
		$title				= !empty( $title ) ? $title : rand(1,999999);
		$description		= !empty( $description ) ?  $description : '';
		
		if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
			$current = !empty($_POST['id']) ? esc_attr($_POST['id']) : '';
			
			$post_author = get_post_field('post_author', $current);
            $post_id 	 = $current;
			
			if( intval( $post_author ) === intval( $current_user->ID ) ){
				$article_post = array(
					'ID' 			=> $current,
					'post_title' 	=> $title,
					'post_excerpt' 	=> $description,
				);

				wp_update_post($article_post);
			} else{
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
				wp_send_json( $json );
			}
			
		} else{
			//Create Post
			$user_post = array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_excerpt'  => $description,
				'post_author'   => $current_user->ID,
				'post_type'     => 'addons-services',
				'post_status'	=> 'publish'
			);

			$post_id    		= wp_insert_post( $user_post );
			
		}
				
		if( $post_id ){
			//Upload files from temp folder to uploads
			$price	        = !empty( $_POST['addons_service']['price'] ) ? $_POST['addons_service']['price'] : '';
					
			//update
			update_post_meta($post_id, '_price', $price);

			//update unyson meta
			$fw_options = array();
			$fw_options['price']         	= $price;
			//Update User Profile
			fw_set_db_post_option($post_id, null, $fw_options);
			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('addons_service', $current_user->ID, true,'listing',$post_id);
			if( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'update' ){
				$json['type'] 		= 'success';
				
				$json['message'] 	= esc_html__('Your addons service has been updated', 'workreap');
			} else{
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your addons service has been added', 'workreap');
				
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your service has been posted.', 'workreap');
			}

			wp_send_json( $json );
		} else{
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occur, please try again later', 'workreap');
			wp_send_json( $json );
		}

	}

	add_action( 'wp_ajax_workreap_post_addons_service', 'workreap_post_addons_service' );
}

/**
 * Update service price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_service_price_update' ) ) {

	function workreap_service_price_update() {
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		} //if user is not logged in then prevent
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$service_id = !empty( $_POST['service_id'] ) ? intval( $_POST['service_id'] ) : '';
		$addons_ids = !empty( $_POST['addons_ids'] ) ?  $_POST['addons_ids']  : array();
		$json		= array();
		$service_price	= 0 ;
		
		if( !empty( $service_id ) ){
			$service_price	= get_post_meta($service_id,'_price',true);
		}
		
		$addons_price	= 0 ;
		if( !empty( $addons_ids ) ){
			foreach( $addons_ids as $post_id ) {
				$addon_price	= get_post_meta($post_id,'_price',true);
				$addon_price	= !empty( $addon_price ) ? $addon_price : 0 ;
				$addons_price	= $addons_price + $addon_price ;
			}
		}
		
		$total_service_price	= $addons_price + $service_price;
		$json['price'] 			= workreap_price_format($total_service_price,'return');
		$json['type'] 		= 'success';
        $json['message'] 	= esc_html__('Service price updated.', 'workreap');
        wp_send_json( $json );
	}
	
	add_action( 'wp_ajax_workreap_service_price_update', 'workreap_service_price_update' );
	add_action( 'wp_ajax_nopriv_workreap_service_price_update', 'workreap_service_price_update' );
}
/**
 * follow service action
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_follow_service' ) ) {

	function workreap_follow_service() {
		global $current_user;
		$post_id = !empty( $_POST['id'] ) ? esc_attr( $_POST['id'] ) : '';
		$json = array();
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$linked_profile   	= workreap_get_linked_profile_id($current_user->ID);
		$saved_services 	= get_post_meta($linked_profile, '_saved_services', true);
		
		$json       = array();
        $wishlist   = array();
        $wishlist   = !empty( $saved_services ) && is_array( $saved_services ) ? $saved_services : array();

        if (!empty($post_id)) {
            if( in_array($post_id, $wishlist ) ){                
                $json['type'] = 'error';
                $json['message'] = esc_html__('This service is already to your wishlist', 'workreap');
                wp_send_json( $json );
            }

            $wishlist[] = $post_id;
            $wishlist   = array_unique( $wishlist );
            update_post_meta( $linked_profile, '_saved_services', $wishlist );
           
            $json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Successfully! added to your wishlist', 'workreap');
            wp_send_json( $json );
        }
        
        $json['type'] = 'error';
        $json['message'] = esc_html__('Oops! something is going wrong.', 'workreap');
        wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_follow_service', 'workreap_follow_service' );
	add_action( 'wp_ajax_nopriv_workreap_follow_service', 'workreap_follow_service' );
}

/**
 * change service status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_service_status' ) ) {

	function workreap_service_status() {
		global $current_user;
		$service_id 			= !empty( $_POST['id'] ) ? esc_attr( $_POST['id'] ) : '';
		$status					= !empty( $_POST['status'] ) ? esc_attr( $_POST['status'] ) : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($service_id);
		} //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json = array();
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$required = array(
            'id'   			=> esc_html__('Post ID is required', 'workreap'),
            'status'  		=> esc_html__('Post status is required', 'workreap')
        );
		
        foreach ($required as $key => $value) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
        }

		$update_post			= array();
		$update					= workreap_save_service_status($service_id,$status);
		
		if( $update ) {
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Successfully! updated the post status', 'workreap');
			wp_send_json( $json );
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Service status is not updated.', 'workreap' );
			wp_send_json( $json );
		}
		
	}

	add_action( 'wp_ajax_workreap_service_status', 'workreap_service_status' );
}

/**
 * Remove service
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_service_remove' ) ) {

	function workreap_service_remove() {
		global $current_user;
		$service_id 		= !empty( $_POST['id'] ) ? esc_attr( $_POST['id'] ) : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($service_id);
		} //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json = array();

		$required = array(
            'id'   			=> esc_html__('Service ID is required', 'workreap')
        );
		
        foreach ($required as $key => $value) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
        }
		
		
		$queu_services		= workreap_get_services_count('services-orders',array('hired'), $service_id);
		if( $queu_services === 0 ){
			do_action('workreap_delete_attachments','service',$service_id); //delete attachments
			$update		= workreap_save_service_status($service_id, 'deleted');
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Successfully!  removed this service.', 'workreap');	
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You can\'t your service because you have orders in queue.', 'workreap');
		}
		
		wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_service_remove', 'workreap_service_remove' );
}

/**
 * Remove portfolio
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_portfolio_remove' ) ) {

	function workreap_portfolio_remove() {
		global $current_user;
		$json = array();
		
		$portfolio_id = !empty($_POST['id']) ? $_POST['id'] : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($portfolio_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if ( empty( $current_user->ID ) ) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'You must login before removing this portfolio.', 'workreap' );
			wp_send_json( $json );
		} else {
			wp_delete_post($portfolio_id);

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Portfolio removed successfully.', 'workreap');
			wp_send_json( $json );	
		}	
	}

	add_action( 'wp_ajax_workreap_portfolio_remove', 'workreap_portfolio_remove' );
}

/**
 * Remove service
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_addons_service_remove' ) ) {

	function workreap_addons_service_remove() {
		global $current_user;
		$json = array();
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$required = array(
            'id'   			=> esc_html__('Addons Service ID is required', 'workreap')
        );
		
        foreach ($required as $key => $value) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
        }
		
		$service_id 		= !empty( $_POST['id'] ) ? esc_attr( $_POST['id'] ) : '';
		if( !empty( $service_id ) ){
			wp_delete_post($service_id);
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Successfully!  removed this addons service.', 'workreap');	
			wp_send_json( $json );
		} 
	}

	add_action( 'wp_ajax_workreap_addons_service_remove', 'workreap_addons_service_remove' );
}


/**
 * Complete Service with reviews
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_complete_service_project' ) ){
	function workreap_complete_service_project(){
		global $current_user;
		$json 					= array();
		$where					= array();
		$update					= array();
		$service_order_id		= !empty( $_POST['service_order_id'] ) ? intval($_POST['service_order_id']) : '';
		$contents 				= !empty( $_POST['feedback_description'] ) ? esc_attr($_POST['feedback_description']) : '';
		$reviews 				= !empty( $_POST['feedback'] ) ? ($_POST['feedback']) : array();
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($service_order_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		if( empty( $service_order_id ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');	 
			wp_send_json($json);
			
		} else {
			workreap_save_service_rating($service_order_id,$reviews,'add');
			$freelancer_id	= get_post_meta( $service_order_id, '_service_author', true);
			$service_id		= get_post_meta( $service_order_id, '_service_id', true);
			
			if( function_exists( 'fw_set_db_post_option' ) ) {
				fw_set_db_post_option($service_order_id, 'feedback', $contents);
			}
			
			workreap_save_service_status( $service_order_id,'completed' );
			
			//update earning
			$where		= array('project_id' => $service_order_id, 'user_id' => $freelancer_id);
			$update		= array('status' 	=> 'completed');
			
			workreap_update_earning( $where, $update, 'wt_earnings');

			// complete service
			$order_id			= get_post_meta($service_order_id,'_order_id',true);
			if ( class_exists('WooCommerce') && !empty( $order_id )) {
				$order = wc_get_order( intval($order_id ) );
				if( !empty( $order ) ) {
					$order->update_status( 'completed' );
				}
			}
			
			$user_ratings	= get_post_meta( $service_order_id ,'_hired_service_rating', true );
			$user_ratings	= !empty( $user_ratings ) ? $user_ratings : 0;
			
			if( function_exists( 'fw_get_db_post_option' ) ) {
				$contents	= fw_get_db_post_option($service_order_id, 'feedback');
			}
			
			$contents		= !empty( $contents ) ? $contents : '';
			
			//Send email to users
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapServiceCompleted')) {
					$email_helper = new WorkreapServiceCompleted();
					$emailData 	  = array();
					
					$freelance_profile_id	= workreap_get_linked_profile_id( $freelancer_id );
					$service_title 			= get_the_title($service_id);
					$service_link 			= get_permalink($service_id);
					
					$employer_name 		= workreap_get_username($current_user->ID);
					$employer_profile 	= get_permalink(workreap_get_linked_profile_id($current_user->ID));
					$freelancer_link 	= get_permalink($freelance_profile_id );
					$freelancer_title 	= get_the_title($freelance_profile_id );
					$freelancer_email 	= get_userdata( $freelancer_id )->user_email;	

						
					$emailData['employer_name'] 		= esc_attr( $employer_name );
					$emailData['employer_link'] 		= esc_url( $employer_profile );
					$emailData['freelancer_name']       = esc_attr( $freelancer_title );
					$emailData['freelancer_link']       = esc_url( $freelancer_link );
					$emailData['freelancer_email']      = esc_attr( $freelancer_email );
					$emailData['service_title'] 		= esc_attr( $service_title );
					$emailData['ratings'] 				= esc_attr( $user_ratings );
					$emailData['service_link'] 			= esc_url( $service_link );
					$emailData['message'] 				= esc_textarea( $contents );

					$email_helper->send_service_completed_email_admin($emailData);
					$email_helper->send_service_completed_email_freelancer($emailData);
					
					//Push notification
					$push	= array();
					$push['freelancer_id']		= $freelancer_id;
					$push['employer_id']		= $current_user->ID;
					$push['service_id']			= $service_id;
					
					
					$push['%freelancer_link%']	= $emailData['freelancer_link'];
					$push['%freelancer_name%']	= $emailData['freelancer_name'];
					$push['%employer_name%']	= $emailData['employer_name'] ;
					$push['%employer_link%']	= $emailData['employer_link'];
					$push['%service_title%']	= $emailData['service_title'];
					$push['%service_link%']		= $emailData['service_link'];
					$push['%ratings%']			= $emailData['ratings'];
					$push['%message%']			= $emailData['message'];
					$push['type']				= 'service_completed';
					
					$push['%replace_ratings%']	= $emailData['ratings'];
					$push['%replace_message%']	= $emailData['message'];
					
					do_action('workreap_user_push_notify',array($push['freelancer_id']),'','pusher_frl_service_complete_content',$push);
					
				}
			}
			
			$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('services', $current_user->ID, true,'completed');
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Service completed successfully.', 'workreap');
			wp_send_json($json);
			
		}
	}
	add_action('wp_ajax_workreap_complete_service_project', 'workreap_complete_service_project');
}

/**
 * Cancel service
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists( 'workreap_service_cancelled' ) ){
	function workreap_service_cancelled(){
		global $current_user, $wpdb, $woocommerce;
		$json 				= array();
		$service_order_id	=  !empty( $_POST['service_id'] ) ? intval($_POST['service_id']) : '';
		$cancelled_reason	=  !empty( $_POST['cancelled_reason'] ) ? $_POST['cancelled_reason'] : '';
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($service_order_id);
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		if( empty( $service_order_id ) || empty( $cancelled_reason ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Cancel reason or service order ID is missing', 'workreap');
			wp_send_json($json);
		} else {
			$freelancer_id		= get_post_meta( $service_order_id, '_service_author', true);
			$service_id			= get_post_meta( $service_order_id, '_service_id', true);
			
			$service_cancelled	= workreap_save_service_status($service_order_id, 'cancelled');
			
			if( $service_cancelled ) {
				// update earnings
				if( function_exists( 'fw_set_db_post_option' ) ) {
					fw_set_db_post_option($service_order_id, 'feedback', $cancelled_reason);
				}
				
				$table_name 	= $wpdb->prefix . 'wt_earnings';
				$e_query		= $wpdb->prepare("SELECT * FROM $table_name where project_id = %d", $service_order_id);
				$earning		= $wpdb->get_row($e_query, OBJECT ); 
				if( !empty( $earning ) ) {
					$update		= array( 'status' 	=> 'cancelled' );
					$where		= array( 'id' 		=> $earning->id );
					workreap_update_earning( $where, $update, 'wt_earnings');

					if ( class_exists('WooCommerce') ) {
						$order = wc_get_order( intval( $earning->order_id ) );
						if( !empty( $order ) ) {
							$order->update_status( 'cancelled' );
						}
					}	
				}
				
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapCancelService')) {
						$email_helper = new WorkreapCancelService();
						$emailData 	  = array();

						$service_title 			= get_the_title($service_id);
						$service_link 			= get_permalink($service_id);
						$freelance_profile_id	= workreap_get_linked_profile_id( $freelancer_id );

						$employer_name 		= workreap_get_username($current_user->ID);
						$employer_profile 	= get_permalink(workreap_get_linked_profile_id($current_user->ID));
						$freelancer_link 	= get_permalink($freelance_profile_id );
						$freelancer_title 	= get_the_title($freelance_profile_id );
						$freelancer_email 	= get_userdata( $freelancer_id )->user_email;


						$emailData['employer_name'] 		= esc_attr( $employer_name );
						$emailData['employer_link'] 		= esc_url( $employer_profile );
						$emailData['freelancer_name']       = esc_attr( $freelancer_title );
						$emailData['freelancer_link']       = esc_url( $freelancer_link );
						$emailData['freelancer_email']      = esc_attr( $freelancer_email );
						$emailData['service_title'] 		= esc_attr( $service_title );
						$emailData['service_link'] 			= esc_url( $service_link );
						$emailData['message'] 				= esc_html( $cancelled_reason );

						$email_helper->send_service_cancel_email($emailData);
						
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $freelancer_id;
						$push['employer_id']		= $current_user->ID;
						$push['service_id']			= $service_id;


						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%service_title%']	= $emailData['service_title'];
						$push['%service_link%']		= $emailData['service_link'];
						$push['%message%']			= $emailData['message'];
						$push['type']				= 'cancel_service';

						$push['%replace_message%']	= $emailData['message'];

						do_action('workreap_user_push_notify',array($push['freelancer_id']),'','pusher_frl_cancel_service_content',$push);
						
					}
				}
				
				$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('services', $current_user->ID, true,'cancelled');
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your order have been cancelled.', 'workreap');
				wp_send_json($json);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap');
				wp_send_json($json);
			}
			
		}
	}
	add_action('wp_ajax_workreap_service_cancelled', 'workreap_service_cancelled');
}

/**
 * Service Cancel reason
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_service_reason' ) ) {

	function workreap_service_reason() {
		global $current_user;
		$json = array();

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent

		$required = array(
            'service_id'   			=> esc_html__('Service ID is required', 'workreap')
        );
        foreach ($required as $key => $value) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
        }
		
		$service_id 			= !empty( $_POST['service_id'] ) ? esc_attr( $_POST['service_id'] ) : '';
		$feedback	 			= fw_get_db_post_option($service_id, 'feedback');
		if( $feedback ) {
			$json['type'] 		= 'success';
			$json['feedback'] 	= $feedback;
			wp_send_json( $json );
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Service status is not updated.', 'workreap' );
			wp_send_json( $json );
		}
		
	}

	add_action( 'wp_ajax_workreap_service_reason', 'workreap_service_reason' );
}

/**
 * Service Complete Rating
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_service_complete_rating' ) ) {

	function workreap_service_complete_rating() {
		global $current_user;
		$json = array();
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$required = array(
            'service_id'   			=> esc_html__('Service ID is required', 'workreap')
        );
		
        foreach ($required as $key => $value) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
        }
		ob_start(); 
		$service_id 	= !empty( $_POST['service_id'] ) ? esc_attr( $_POST['service_id'] ) : '';
		$feedback	 	= fw_get_db_post_option($service_id, 'feedback');
		$rating_titles 	= workreap_project_ratings('services_ratings');
		?>
		
		<div class="wt-description">
			<p><?php echo esc_html( $feedback );?></p>
		</div>
		<form class="wt-formtheme wt-formfeedback">
			<fieldset>
				<?php 
					if( !empty( $rating_titles ) ) {
						foreach( $rating_titles as $slug => $label ) {
							$q_rating	 	= get_post_meta($service_id, $slug, true);
							if( !empty( $q_rating ) ){ ?>
								<div class="form-group wt-ratingholder">
									<div class="wt-ratepoints">
										<div class="counter wt-pointscounter"><?php echo esc_html( $q_rating );?></div>
										<div class="user-stars-v2">
											<?php do_action('workreap_freelancer_single_service_rating', $q_rating ); ?>
										</div>
									</div>
									<span class="wt-ratingdescription"><?php echo esc_html( $label );?></span>
								</div>
							<?php }?>
					<?php }?>
				<?php }?>
				<div class="form-group wt-btnarea">
					<a class="wt-btn" href="#" onclick="event_preventDefault(event);" data-dismiss="modal" aria-label="Close"><?php esc_html_e('Okay','workreap');?></a>
				</div>
			</fieldset>
		</form>
		<?php
		if( $feedback ) {
			$json['type'] 		= 'success';
			$json['ratings'] 	= ob_get_clean();
			wp_send_json( $json );
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Service status is not updated.', 'workreap' );
			wp_send_json( $json );
		}
		
	}

	add_action( 'wp_ajax_workreap_service_complete_rating', 'workreap_service_complete_rating' );
}

/*
**
 * load more service reviews
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_more_rating_service' ) ) {

	function workreap_more_rating_service() {
		$json			= array();
		$paged			= !empty( $_POST['page'] ) ? intval( $_POST['page'] ) : '';
		$service_id		= !empty( $_POST['service_id'] ) ? intval( $_POST['service_id'] ) : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$show_posts		= 1;
		$order 			= 'DESC';
		$sorting 		= 'ID';
		
		if(!empty($service_id) && !empty($paged)) {
			$args2 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'services-orders',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('completed'),
					'paged' 			=> $paged,
					'suppress_filters' 	=> false
				);

			$meta_query_args2[] = array(
									'key' 		=> '_service_id',
									'value' 	=> $service_id,
									'compare' 	=> '='
								);
			$query_relation2 		= array('relation' => 'AND',);
			$args2['meta_query'] 	= array_merge($query_relation2, $meta_query_args2);
			
			$query2 			= new WP_Query($args2);
			$count_post 		= $query2->found_posts;

			if( $query2->have_posts() ){
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Review found', 'workreap');
				ob_start();
				$counter	= 0;
				while ($query2->have_posts()) : $query2->the_post();
					global $post;
					$author_id 		= get_the_author_meta( 'ID' );  
					$linked_profile = workreap_get_linked_profile_id($author_id);
					$tagline		= workreap_get_tagline($linked_profile);
					$employer_title = get_the_title( $linked_profile );
					$employer_avatar = apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
									);
					$service_ratings	= get_post_meta($post->ID,'_hired_service_rating',true);
					if( function_exists('fw_get_db_post_option') ) {
						$feedback	 		= fw_get_db_post_option($post->ID, 'feedback');
					}
					?>
					<div class="wt-userlistinghold wt-userlistingsingle">	
						<?php if( !empty( $employer_avatar ) ){?>
							<figure class="wt-userlistingimg">
								<img src="<?php echo esc_url( $employer_avatar );?>" alt="<?php echo esc_attr($employer_title);?>">
							</figure>
						<?php } ?>
						<div class="wt-userlistingcontent">
							<div class="wt-contenthead">
								<div class="wt-title">
									<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
									<?php if( !empty( $tagline ) ) {?>
										<h3><?php echo esc_html( $tagline );?></h3>
									<?php } ?>
								</div>
								<ul class="wt-userlisting-breadcrumb">
									<?php do_action('workreap_print_location', $linked_profile); ?>
									<li><?php do_action('workreap_freelancer_single_service_rating', $service_ratings ); ?></li>
								</ul>
							</div>
						</div>
						<?php if( !empty( $feedback ) ){?>
							<div class="wt-description">
								<p>“<?php echo esc_html( $feedback );?>”</p>
							</div>
						<?php  }?>
					</div>
					<?php
					
				endwhile;
				wp_reset_postdata();
				
				$review				= ob_get_clean();
				$json['reviews'] 	= $review;
			} else{
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No more service reviews', 'workreap');
				$json['reviews'] 	= 'null';
			}
		}
		wp_send_json($json);			
	}

	add_action( 'wp_ajax_workreap_more_rating_service', 'workreap_more_rating_service' );
	add_action( 'wp_ajax_nopriv_workreap_more_rating_service', 'workreap_more_rating_service' );
}

/*
**
 * load more service
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_more_service' ) ) {

	function workreap_more_service() {
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json			= array();
		$paged			= !empty( $_POST['page'] ) ? intval( $_POST['page'] ) : '';
		$user_id		= !empty( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : '';
		
		$post_id		= workreap_get_linked_profile_id( $user_id );
		$flag 			= rand(9999, 999999);
		
		$order 			= 'DESC';
		$sorting 		= 'ID';
		
		if(!empty($user_id) && !empty($paged)) {
			$show_posts		= 3;
			$order 			= 'DESC';
			$sorting 		= 'ID';
			$width			= 352;
			$height			= 200;
			$args_res 		= array(
									'posts_per_page' 	=> $show_posts,
									'post_type' 		=> 'micro-services',
									'post_status' 		=> 'publish',
									'orderby' 			=> $sorting,
									'order' 			=> $order,
									'author' 			=> $user_id,
									'paged' 			=> $paged,
									'suppress_filters' 	=> false
								);
			$query_res 		= new WP_Query($args_res);
			$count_post 	= $query_res->found_posts;

			if( $query_res->have_posts() ){
				$json['type'] 		= 'success';
				if( intval( $query_res->max_num_pages ) >= $paged ) {
					$json['show_btn']	= 'show';
				} else {
					$json['show_btn']	= 'hide';
				}
				
				$json['message'] 	= esc_html__('services found', 'workreap');
				ob_start();
				
				while ($query_res->have_posts()) : $query_res->the_post();
					global $post;
					$project_rating			= get_post_meta($post->ID, 'user_rating', true);
					$freelancer_title 		= get_the_title( $post_id );	
					$service_url			= get_the_permalink();

					$db_docs			= array();
					$db_price			= '';
					$delivery_time		= '';
					$order_details		= '';

					if (function_exists('fw_get_db_post_option')) {
						$db_docs   			= fw_get_db_post_option($post->ID,'docs');
						$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
						$order_details   	= fw_get_db_post_option($post->ID,'order_details');
						$db_price   		= fw_get_db_post_option($post->ID,'price');
					}
					
					if( count( $db_docs )>1 ) {
						$class	= 'wt-freelancers-services-'.intval( $flag ).' owl-carousel';
					} else {
						$class	= '';
					}
					
					if( empty($db_docs) ) {
						$empty_image_class	= 'wt-empty-service-image';
						$is_featured		= workreap_service_print_featured( $post->ID, 'yes');
						$is_featured    	= !empty( $is_featured ) ? 'wt-featured-service' : '';
					} else {
						$empty_image_class	= '';
						$is_featured		= '';
					}

				?>
				<div class="col-12 col-sm-12 col-md-6 col-lg-4 float-left">
					<div class="wt-freelancers-info <?php echo esc_attr( $empty_image_class );?> <?php echo esc_attr( $is_featured );?>">
						<?php if( !empty( $db_docs ) ) {?>
							<div class="wt-freelancers <?php echo esc_attr( $class );?>">
								<?php
									foreach( $db_docs as $key => $doc ){
										$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
										$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
										if ( strpos( $thumbnail,'media/default.png' ) === false ) { ?>
										<figure class="item">
											<a href="<?php echo esc_url( $service_url );?>">
												<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Service ','workreap');?>" class="item">
											</a>
										</figure>
								<?php } } ?>
							</div>
						<?php } ?>
						<?php do_action('workreap_service_print_featured', $post->ID); ?>
						<?php do_action('workreap_service_shortdescription', $post->ID,$post_id); ?>
					</div>
				</div>
				<?php
					endwhile;
					wp_reset_postdata();
				
					$service			= ob_get_clean();
					$json['flag']		= $flag;
					$json['services'] 	= $service;
					wp_send_json($json);
			} else{
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No more services available.', 'workreap');
				$json['services'] 	= 'null';
				wp_send_json($json);
			}
		}			
	}
	
	add_action( 'wp_ajax_workreap_more_service', 'workreap_more_service' );
	add_action( 'wp_ajax_nopriv_workreap_more_service', 'workreap_more_service' );
}

/**
 * Submit withdraw request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_submit_withdraw')) {

    function workreap_submit_withdraw() {
        global $wpdb,$current_user,$post;
        $json = array();     
		$insert_payouts = '';
		
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$payment_method	= !empty( $_POST['withdraw']['gateway'] ) ? esc_html( $_POST['withdraw']['gateway'] ) : '';
		$amount			= !empty( $_POST['withdraw']['amount'] ) ? floatval( $_POST['withdraw']['amount'] ) : 0;
		$user_id		= !empty( $current_user->ID ) ? intval( $current_user->ID ) : '';
		$payment_setting		= worrketic_hiring_payment_setting();

		$total_pending	= workreap_sum_freelancer_withdraw(array('publish','pending'));
        $total_pending	= !empty($total_pending) ? floatval($total_pending) : 0;

        $totalamount    = workreap_sum_user_earning('completed', 'freelancer_amount', $current_user->ID);
        $total_amount   = !empty($totalamount->total_amount) ? floatval($totalamount->total_amount ) - floatval($total_pending ) : 0;
		
		if ( !empty($payment_setting['minamount']) && $amount < $payment_setting['minamount']) {
            $json['type']       = 'error';
            $json['message'] 	= esc_html__("You are not allowed to withdraw amount below the", 'workreap').' '. workreap_price_format($payment_setting['minamount'],'return');
            wp_send_json($json);
        }
		
        if ( $amount > $total_amount) {
            $json['type']       = 'error';
            $json['message'] 	= esc_html__("We are Sorry! you do not have sufficient amount for the withdrawal", 'workreap');
            wp_send_json($json);
        }
		
		$contents	= get_user_meta($user_id,'payrols',true);

		if( $payment_method === 'paypal' ){
			if( !empty( $contents['payrol'] ) && $contents['payrol'] === 'paypal' ){
				//only for migration from release 1.0.7
				$email		= !empty($contents['email']) ? $contents['email'] : "";
			} else{
				$email		= !empty($contents['paypal_email']) ? $contents['paypal_email'] : "";
			}
			
			$insert_payouts		= serialize( array('paypal_email' => $email) );
			
			//check if email is valid
			if( empty( $email ) || !is_email( $email ) ){
				$json['type'] 	 = "error";
				$json['message'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap');
				wp_send_json( $json );
			}

		} else if( $payment_method === 'bacs' ){
			$bank_details	= array();
			$bank_details['bank_account_name']		= !empty($contents['bank_account_name']) ? $contents['bank_account_name'] : "";
			$bank_details['bank_account_number']	= !empty($contents['bank_account_number']) ? $contents['bank_account_number'] : "";
			$bank_details['bank_name']				= !empty($contents['bank_name']) ? $contents['bank_name'] : "";
			$bank_details['bank_routing_number']	= !empty($contents['bank_routing_number']) ? $contents['bank_routing_number'] : "";
			$bank_details['bank_iban']				= !empty($contents['bank_iban']) ? $contents['bank_iban'] : "";
			$bank_details['bank_bic_swift']			= !empty($contents['bank_bic_swift']) ? $contents['bank_bic_swift'] : "";
			
			$bank_details        = apply_filters('payout_bank_transfer_filter_details',$bank_details,$contents);
			$insert_payouts		= serialize( $bank_details );
			
			if( empty( $contents['bank_iban'] ) || empty( $contents['bank_account_number'] ) ){
				$json['type'] 	 = "error";
				$json['message'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap');
				wp_send_json( $json );
			}
		} else{
			$payout_details	= array();
			$fields	= workreap_get_payouts_lists($payment_method);

			if( !empty($fields[$payment_method]['fields'])) {
				foreach( $fields[$payment_method]['fields'] as $key => $field ){
					if(!empty($field['show_this']) && $field['show_this'] == true){
						if(!empty($contents[$key])){
							$payout_details[$key]		= $contents[$key];
						}
						
					}
				}
			}
			
			$insert_payouts		= serialize( $payout_details );

			if(empty($payout_details)){
				$json['type'] 	 = "error";
				$json['message'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap');
				wp_send_json( $json );
			}
		}
		
		
		//Process withdraw
        $account_details    = !empty($insert_payouts) ? $insert_payouts : '';
        
        $user_name = !empty($current_user->ID) ? workreap_get_username($current_user->ID) . '-' . $amount : '';
        $withdraw_post = array(
            'post_title'    => wp_strip_all_tags($user_name),
            'post_status'   => 'pending',
            'post_author'   => $current_user->ID,
            'post_type'     => 'withdraw',
        );
		
        $withdraw_id    = wp_insert_post($withdraw_post);
        $current_date   = current_time('mysql');

        update_post_meta($withdraw_id, '_withdraw_amount', $amount);
        update_post_meta($withdraw_id, '_payment_method', $payment_method);
        update_post_meta($withdraw_id, '_timestamp', strtotime($current_date));
        update_post_meta($withdraw_id, '_year', date('Y',strtotime($current_date)));
        update_post_meta($withdraw_id, '_month', date('m',strtotime($current_date)));
        update_post_meta($withdraw_id, '_account_details', $account_details);
		
        if (class_exists('Workreap_Email_helper')) {
            if (class_exists('WorkreapSendPayoutsNotification')) {
                $emailData                          = array();
                $user_name                          = workreap_get_username($current_user->ID);
				$post_id							= workreap_get_linked_profile_id($current_user->ID);
                $emailData['user_name']             = !empty($user_name) ? $user_name : '';
				$emailData['user_link']             = admin_url( 'post.php?post='.$post_id.'&action=edit'); 
                $emailData['amount']                = !empty($amount) ? workreap_price_format($amount,'return') : '';
                $emailData['detail']                = admin_url( 'edit.php?post_type=withdraw&author='.$current_user->ID); 
                $email_helper = new WorkreapSendPayoutsNotification();
                $email_helper->send_withdraw_request_to_admin($emailData);
            }
        }

        $json['type'] 	 		= "success";
        $json['message']        = esc_html__('Your withdrawal request has been submitted. We will process your withdrawal request', 'workreap');
        wp_send_json( $json );
    }

    add_action('wp_ajax_workreap_submit_withdraw', 'workreap_submit_withdraw');
}

/**
 * Update status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_update_withdraw_status' ) ) {

	function workreap_update_withdraw_status() {
		global $current_user;
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json = array();
		$status			= !empty( $_POST['status'] ) ? $_POST['status'] : 0;
		$post_id		= !empty( $_POST['id'] ) ? $_POST['id'] : 0;
		
		$status	= !empty( $status ) && $status == 'pending' ? 'publish' : 'pending';
		
		$update_post			= array();
		$update_post['ID']		= $post_id;
		$update_post['post_status']	= $status;
		wp_update_post( $update_post );
		
		if(!empty($status) && $status === 'publish'){
			$amount	= get_post_meta($post_id, '_withdraw_amount', true);
			$post 	= get_post( $post_id );
			$post_author	= !empty($post->post_author) ? $post->post_author : 0;

			if(class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapSendPayoutsNotification')) {
					$linked_profile 	= workreap_get_linked_profile_id($post_author);
					$email_helper 		= new WorkreapSendPayoutsNotification();
					$emailData 			= array();
					$emailData['total_amount']  	= workreap_price_format($amount, 'return');
					$emailData['freelancer_name']  	= get_the_title($linked_profile);
					$emailData['freelancer_email']  = get_userdata($post_author)->user_email;
					$email_helper->send_notification_to_freelancer($emailData);

				}
			}
			
			$json['message']        = esc_html__('Withdrawal status has been updated, and an email has sent to freelancer', 'workreap');
		}else{
			$json['message']        = esc_html__('Withdrawal status has been updated', 'workreap');
		}
		
		$json['type'] 	 		= "success";
        wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_update_withdraw_status', 'workreap_update_withdraw_status' );
}

/**
 * Update status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_switch_user_account' ) ) {

	function workreap_switch_user_account() {
		global $current_user,$wpdb;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json 	= array();
		$wp_user_object = !empty($current_user) ? $current_user : '';
		if(empty($wp_user_object)){return;}
		
		$current_user_id		= $wp_user_object->ID;

		$current_profile_id		= workreap_get_linked_profile_id($current_user_id);
		$username   			= workreap_get_username( $wp_user_object->ID );
		
		if (!empty($wp_user_object->roles[0]) && $wp_user_object->roles[0] === 'freelancers') {
			$new_role	= 'employers';
			$user_type	= 'employer';
		} else if (!empty($wp_user_object->roles[0]) && $wp_user_object->roles[0] === 'employers') {
			$new_role	= 'freelancers';
			$user_type	= 'freelancer';
		}
		
		$get_switch_id	= get_user_meta($current_user_id,'switch_user_id',true);
		
		$get_switch_id	=  !empty($get_switch_id) ? intval($get_switch_id) : '';
		
		$count_user	= 0;
		if(!empty($get_switch_id)){
			$count_user = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $get_switch_id));
		}

		if(empty($get_switch_id) || empty($count_user)){
			$user_login			= sanitize_title( $wp_user_object->user_login.'_'.$wp_user_object->ID );
			$defaul_user_name 	= $user_login;
			
			$i = 1;
			while ( username_exists( $user_login ) ) {
				$user_login = $defaul_user_name . $i;
				$i++;
			}
			
			$query	= "INSERT INTO ".$wpdb->prefix."users (`user_login`,  
					`user_pass`,
					`user_nicename`, 
					`user_email`, 
					`display_name`
			) VALUES ('".$user_login."', 
					'".$wp_user_object->user_pass."',
					 '".$wp_user_object->display_name."', 
					 '".$wp_user_object->user_email."', 
					 '".$wp_user_object->display_name."'
			)";

			$user_update = $wpdb->query(
			   $wpdb->prepare($query)
			);
			
			$new_profile_id 	= $wpdb->insert_id;
			
			wp_update_user( array('ID' => esc_sql( $new_profile_id ), 'role' => $new_role, 'user_status' => 1 ) );
			$first_name			= get_user_meta( $current_user_id, 'first_name',true );
			$last_name			= get_user_meta( $current_user_id, 'last_name',true );
			$full_name			= get_user_meta( $current_user_id, 'full_name',true );
			$termsconditions	= get_user_meta( $current_user_id, 'termsconditions',true );

			update_user_meta( $new_profile_id, 'first_name', $first_name );
            update_user_meta( $new_profile_id, 'last_name', $last_name );             

			update_user_meta($new_profile_id, 'show_admin_bar_front', false);
            update_user_meta($new_profile_id, 'full_name', esc_html($full_name));
			update_user_meta($new_profile_id, 'termsconditions', esc_html($termsconditions));

			//Create Post
			$user_post = array(
                'post_title'    => wp_strip_all_tags( $username ),
                'post_status'   => 'publish',
                'post_author'   => $new_profile_id,
                'post_type'     => $new_role,
            );

            $post_id    = wp_insert_post( $user_post );
			
			update_user_meta($current_user_id, 'switch_user_id', $new_profile_id); 
			update_user_meta($new_profile_id, 'switch_user_id', $current_user_id); 
			// update keys
			update_user_meta($new_profile_id, '_switch_user_type', 'new');
			update_user_meta($new_profile_id, '_linked_profile', $post_id); 
			update_post_meta($post_id, '_linked_profile', $new_profile_id); 

			$is_verified		= get_post_meta( $current_profile_id, '_is_verified',true );
			$is_verified_user	= get_user_meta($current_user_id,'_is_verified',true);
			$confirmation_key	= get_user_meta( intval( $current_user_id ), 'confirmation_key', true);

			$is_verified		= !empty($is_verified) ? $is_verified : '';
			$confirmation_key	= !empty($confirmation_key) ? $confirmation_key : '';
			$is_verified_user	= !empty($is_verified_user) ? $is_verified_user : '';

			update_user_meta( $new_profile_id, 'confirmation_key', $confirmation_key );
			update_user_meta( $new_profile_id, '_is_verified', $is_verified_user );
			update_post_meta( $post_id, '_is_verified', $is_verified );
			$fw_options		= array();
			if( $new_role == 'employers' ){
				$employer_package_id			= workreap_get_package_type( 'package_type','trail_employer');
				workreap_update_pakage_data( $employer_package_id ,$new_profile_id,'','employer' );
				update_post_meta($post_id, '_user_type', 'employer');
				update_post_meta($post_id, '_employees', 'employer');            		
				update_post_meta($post_id, '_followers', '');

			} elseif( $new_role == 'freelancers' ){
				$freelancer_package_id			= workreap_get_package_type( 'package_type','trail_freelancer');
				workreap_update_pakage_data( $freelancer_package_id ,$new_profile_id,'','freelancer' );
				update_post_meta($post_id, '_user_type', 'freelancer');
				update_post_meta($post_id, '_perhour_rate', '');
				update_post_meta($post_id, 'rating_filter', 0);
				update_post_meta($post_id, '_freelancer_type', 'rising_talent');         		           		
				update_post_meta($post_id, '_featured_timestamp', 0); 
				update_post_meta($post_id, '_english_level', 'basic');
				update_post_meta($post_id, '_have_avatar', 0); 
				update_post_meta($post_id, '_profile_health_filter', 0); 
				//extra data in freelancer
				
				$fw_options['_perhour_rate']    = '';
			}
							
			//add extra fields as a null
			$tagline	= '';
			update_post_meta($post_id, '_tag_line', $tagline);
			update_post_meta($post_id, '_address', '');
			update_post_meta($post_id, '_latitude', '');
			update_post_meta($post_id, '_longitude', '');
			
			$fw_options['address']    	= '';
			$fw_options['longitude']    = '';
			$fw_options['latitude']    	= '';
			$fw_options['tag_line']     = $tagline;
			//end extra data
			
			fw_set_db_post_option($post_id, null, $fw_options);
			
			//update privacy settings
			$settings		 = workreap_get_account_settings($user_type);
			if( !empty( $settings ) ){
				foreach( $settings as $key => $value ){
					$val = $key === '_profile_blocked' || $key === '_hourly_rate_settings'? 'off' : 'on';
					update_post_meta($post_id, $key, $val);
				}
			}
			
			$user = get_user_by('ID', $new_profile_id );
			wp_clear_auth_cookie();
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID );
			
		}else{
			$user = get_user_by('ID', $get_switch_id );
			wp_clear_auth_cookie();
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID );
		}

		$json['type']   	   = 'success';
		$json['switch_url']    = workreap_login_redirect($user->ID);
        $json['message'] = esc_html__('You have successfully switched the user.', 'workreap');        
        wp_send_json($json);
	}

	add_action( 'wp_ajax_workreap_switch_user_account', 'workreap_switch_user_account' );
}

/**
 * Notification detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_read_notification' ) ) {
	add_action( 'wp_ajax_workreap_read_notification', 'workreap_read_notification' );
	function workreap_read_notification() {
		$json			= array();
		$notify_id		= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		$content		= apply_filters('workreap_push_notification_excerpt',$notify_id,false,'',true);

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$notify_post = array(
		  'ID'           => $notify_id,
		  'post_status'  => 'publish',
		);
		
		if ( apply_filters('workreap_get_domain',false) === true ) {
			//for demo only
		}else{
			wp_update_post( $notify_post );
		}
		
		$json['type'] 	 = "success";
		$json['content'] = $content;
		wp_send_json( $json );
	}
}

//Articluate plugin compatibility
add_action('wp_ajax_workreap_articulate_upload_form_data', 'articulate_upload_form_data');

/**
 * Notification detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_update_billing' ) ) {
	add_action( 'wp_ajax_workreap_update_billing', 'workreap_update_billing' );
	function workreap_update_billing() {
		global $current_user;
		$json			= array();
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$data = apply_filters( 'workreap_billing_fields', '' );
				
		foreach ($data as $meta_key => $meta_value ) {
			update_user_meta( $current_user->ID, $meta_key, sanitize_text_field( $_POST['billing'][$meta_key] ) );
		}
		
		$json['type'] 	 		= "success";
		$json['message']        = esc_html__('Details has been updated', 'workreap');
		wp_send_json( $json );
	}
}

/**
 * Update mailchimp array
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_mailchimp_array' ) ) {
	function workreap_mailchimp_array(){
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json		= array();
		$transName 	= 'latest-mailchimp-list';
		$mailChip = get_transient( $transName );
		if( empty($mailChip) ){
			$list_array	= array();
			if( function_exists('workreap_mailchimp_list') ) {
				$list_array	= workreap_mailchimp_list();
				set_transient( $transName, $list_array, 60 * 60 * 24 );
			}
		}
		
		$json['type']	= 'success';	
		$json['message']	= esc_html__('MailChimp is updated','workreap' );
		wp_send_json($json);
	}
	add_action('workreap_mailchimp_array', 'workreap_mailchimp_array');
	add_action('wp_ajax_workreap_mailchimp_array', 'workreap_mailchimp_array');	
}

/**
 * View verification details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_view_identity_detail' ) ) {
	function workreap_view_identity_detail(){
		$json = array();  
		$post_id = !empty($_POST['post_id']) ? intval( $_POST['post_id'] ) : '';
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$verification  = get_post_meta($post_id, 'verification_attachments', true);
		
		if(empty($verification)){
			$json['type']	= 'error';
			$json['message']	= esc_html__('No verification user details found','workreap' );
			wp_send_json($json);
		}
		
		$user_info	= !empty($verification['info']) ? $verification['info'] : array();
		$required = array(
			'name'   				=> esc_html__('Name', 'workreap'),
			'contact_number'  		=> esc_html__('Contact number', 'workreap'),
			'verification_number'   => esc_html__('Verification number', 'workreap'),
			'address'   			=> esc_html__('Address', 'workreap'),
		);

		if( !empty($verification['info'] ) ) {
			unset( $verification['info'] );
		}

		ob_start();
		?>
		<div class="cus-modal-bodywrap">
			<div class="cus-form cus-form-change-settings">
				<div class="edit-type-wrap">
					<?php if(!empty($user_info)){
						foreach($user_info as $key => $item){
							if(!empty($required[$key])){
						?>
						<div class="cus-options-data">
							<label><span><strong><?php echo esc_html( $required[$key] );?></strong></span></label>
							<div class="step-value">
								<span><?php echo esc_html( $item );?></span>
							</div>
						</div>
					<?php }}}?>
					
					<?php if(!empty($verification)){
						foreach($verification as $key => $item){
						?>
						<div class="cus-options-data cus-options-files">
							<div class="step-value">
								<span><a target="_blank" href="<?php echo esc_attr( $item['url'] );?>"><?php echo esc_html( $item['name'] );?></a></span>
							</div>
						</div>
					<?php }}?>
				</div>
			</div>
		</div>
		<?php
		
		$data	= ob_get_clean();
		$json['type']	= 'success';
		$json['html']	= $data;
		$json['message']	= esc_html__('Verification user details','workreap' );
		wp_send_json($json);
	}
	add_action('wp_ajax_workreap_view_identity_detail', 'workreap_view_identity_detail');	
}

/**
 * Update verification request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_login_user' ) ) {

	function workreap_login_user() {
		global $current_user;
		$json 	 	= array();
		$user_id	= !empty($_POST['id']) ? intval($_POST['id']) : 0;
		if( !empty($user_id) ){
			$user = get_user_by( 'id', $user_id ); 
			$redirect_url	= workreap_login_redirect($user_id);
			if( $user ) {
				wp_clear_auth_cookie();
				wp_set_current_user( $user_id, $user->user_login );
				wp_set_auth_cookie( $user_id );
				update_user_caches($user);
				$json['type'] 			= 'success';
				$json['redirect_url'] 	= $redirect_url;
				wp_send_json( $json );
			}
			
		}
	}
	add_action( 'wp_ajax_nopriv_workreap_login_user', 'workreap_login_user' );
}

/**
 * Delete project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_delete_project' ) ) {

	function workreap_delete_project() {
		global $current_user;
		$json 	 	= array();
		$project_id	= !empty($_POST['id']) ? intval($_POST['id']) : 0;
		$post_id	= workreap_get_linked_profile_id($current_user->ID);

		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($project_id);
		} //if user is not logged in then prevent
		
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ( $do_check == false ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }

		wp_delete_post($project_id,true);
		
		//Send email to users
		if (class_exists('Workreap_Email_helper')) {
			if (class_exists('WorkreapJobPost')) {
				$email_helper 		= new WorkreapJobPost();
				$emailData 			= array();
				$meta_query_args 	= array();
				
				$query_args = array('posts_per_page' => -1,
					'post_type' 		=> 'proposals',
					'suppress_filters' 	=> false,
				);
				
				$meta_query_args[] = array(
					'key' 			=> '_project_id',
					'value' 		=> $project_id,
					'compare' 		=> '='
				);
				$query_relation = array('relation' => 'AND',);
				$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);    
				
				$proposals = get_posts($query_args);
				foreach( $proposals as $key => $proposal ){
					$freelance_id			= get_post_field('post_author',$proposal->ID);

					if(!empty($freelance_id)){
						$author_data    = get_userdata( $freelance_id );                    
						$email_to       = $author_data->data->user_email;
						$freelancer_post_id	= workreap_get_linked_profile_id($current_user->ID);
						
						$emailData['email_to'] 			= esc_html( $email_to );
						$emailData['project_title'] 	= esc_html( get_the_title($project_id) );
						$emailData['employer_name'] 	= workreap_get_username( $current_user->ID );
						$emailData['employer_link'] 	= esc_html( get_the_permalink($post_id) );
						$emailData['freelancer_name'] 	= workreap_get_username( $freelance_id );
						$emailData['freelancer_link'] 	= esc_html( get_the_permalink($freelancer_post_id) );

						$email_helper->send_delete_job_email($emailData);
						wp_delete_post($proposal->ID,true);
					}
				}

			}
		}
		
		$json['type'] = 'success';
		$json['message'] = esc_html__('Project has been deleted', 'workreap');
		wp_send_json( $json );
		
	}
	add_action( 'wp_ajax_workreap_delete_project', 'workreap_delete_project' );
}


/**
 * Remove service
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_quote_remove' ) ) {

	function workreap_quote_remove() {
		global $current_user;
		$quote_id 		= !empty( $_POST['id'] ) ? esc_attr( $_POST['id'] ) : '';
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('workreap_validate_privileges') ) { 
			workreap_validate_privileges($quote_id);
		} //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}
		
		$json = array();
		$required = array(
            'id'   			=> esc_html__('quote ID is required', 'workreap')
        );
		
        foreach ($required as $key => $value) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
			}
        }

		wp_delete_post($quote_id,false);
		
		$json['type'] = 'error';
		$json['message'] = esc_html__('Quote has been deleted', 'workreap');
		wp_send_json( $json );
	}

	add_action( 'wp_ajax_workreap_quote_remove', 'workreap_quote_remove' );
}