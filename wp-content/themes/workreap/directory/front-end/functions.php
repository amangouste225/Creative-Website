<?php

/**
 * Return Number Users
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_get_search_list' ) ) {
	function worktic_get_search_list($is_single='no'){
		
		$list = array(
		    
	        'freelancer' => array( 
	    		'title' 		=> esc_html__('Freelancers', 'workreap'),
	    	),
			'job' => array( 
		    	'title' => esc_html__('Jobs', 'workreap'),
		    ),
	        'employer' => array( 
	    		'title' 		=> esc_html__('Employers', 'workreap'),
	    	),
			'services' => array( 
	    		'title' 		=> esc_html__('Services', 'workreap'),
	    	),
		);

		$list = apply_filters('worktic_filter_search_list', $list);		
		
		if( $is_single === 'yes' ){
			$list = workreap_array_column_extract($list, 'title',-1);
		}
		
		return $list;
	}
}

/**
 * Remove search options from list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'get_final_search_list' ) ) {
	function get_final_search_list($list=array()){
		global $current_user,$post;
		if( is_user_logged_in() ) {
			$role	= apply_filters('workreap_get_user_type', $current_user->ID );
			if ( function_exists('fw_get_db_post_option' )) {
				$restict_user_view_search    	= fw_get_db_settings_option('restict_user_view_search');
				
				if(!empty($restict_user_view_search) && $restict_user_view_search == 'yes'){
					if(!empty($role) && $role === 'employer'){
						unset($list['employer']);
						unset($list['job']);
						$remove	= array('employer','job');
						$list	= array_diff($list, $remove);
					}else if(!empty($role) && $role === 'freelancer'){
						unset($list['freelancer']);
						unset($list['services']);
						$remove	= array('services','freelancer');
						$list	= array_diff($list, $remove);
					}
				}
			}
		}
		
		return $list;
	}
}
/**
 * Get registered sidebars
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreapGetRegisterSidebars' ) ) {
	function workreapGetRegisterSidebars(){
		global $wp_registered_sidebars;
		$am_sidebarsArray		= array();
		$am_sidebarsArray[''] 	= esc_html__('No Sidebar','workreap');
		$sidebars = $wp_registered_sidebars;
		if (is_array($sidebars) && !empty($sidebars)) {
			foreach ($sidebars as $key => $sidebar) {
				$am_sidebarsArray[$key] = $sidebar['name'];
			}
		}
		
		return $am_sidebarsArray;
	}
}

/**
 * Get sidebar position
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_sidebars_get_current_position' ) ) {
	function workreap_sidebars_get_current_position($post_id=0){
		if(empty($post_id)){return 'full';}
		$sidebars	= array();
		
		if(function_exists('fw_get_db_post_option')){
			$sidebars['sd_layout'] = fw_get_db_post_option( $post_id, 'sd_layout', true );
			$sidebars['sd_sidebar'] = fw_get_db_post_option( $post_id, 'sd_sidebar', true );
		}
		
		return $sidebars;
	}
}


/**
 * Add index file into folder
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreapGetRecursiveFolderList' ) ) {
	function workreapGetRecursiveFolderList($curDir,$folders=false){                   
		$dirs = glob($curDir . '/*', GLOB_ONLYDIR);    
		$cur = 0;
		foreach($dirs as $dir)  {
			$folders[$cur]['path'] = $dir;
			$folders[$cur] = workreapGetRecursiveFolderList($dir,$folders[$cur]);
			++$cur;
		  }

		return $folders;
	}
}

/**
 * WMC plugin compatibility
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_wmc_compatibility' ) ) {
	function workreap_wmc_compatibility($price) {
		if(function_exists('wmc_revert_price')){
			$price	= wmc_revert_price( $price );
		}
		
		return $price;
	}
}

/**
 * @Show types for admin support
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_support_type')) {

    function workreap_support_type(  ) {
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$help 		= fw_get_db_settings_option('help_support');		
			$contact_subject		= !empty( $help['enable']['contact_subject'] ) ? $help['enable']['contact_subject'] : '';
			
			if( !empty( $contact_subject ) and is_array($contact_subject) ){
				$contact_subject = array_filter($contact_subject);
				$contact_subject = array_combine(array_map('sanitize_title', $contact_subject), $contact_subject);
				return $contact_subject;
			} else{
				$support	= array ( 
					'query'			=> esc_html__("Query",'workreap'),
					'query_type'	=> esc_html__("Query Type",'workreap')
					);
			}
			
		} else {
			$support	= array ( 
					'query'			=> esc_html__("Query",'workreap'),
					'query_type'	=> esc_html__("Query Type",'workreap')
					);
		}

		return $support;
	}
}

/**
 * @Validte if user is logged in
 * @return {}
 */
if (!function_exists('workreap_validate_user')) {
	function workreap_validate_user(){
		$json = array();

		if (!is_user_logged_in()) {
			$json['type']	    =  "error";
			$json['message']	=  esc_html__("You must logged in to perform this action",'workreap' );;
			wp_send_json($json);
		}
	}
}

/**
 * @Validte if user is logged  and right privileges
 * @return {}
 */
if (!function_exists('workreap_validate_privileges')) {
	function workreap_validate_privileges($post_id,$message=''){
		global $current_user;
		$json = array();
		
		$message	=  !empty($message) ? $message : esc_html__('You are not authorized to perform this action', 'workreap');
		
		if (!is_user_logged_in()) {
			$json['type'] 	 = 'error';
            $json['message'] = $message;
            wp_send_json( $json );
		}
		
		$post_data 		= get_post( $post_id );
     	$post_author	= !empty( $post_data->post_author ) ? intval($post_data->post_author) : 0;
		
		if(isset($post_author) && $post_author  !== $current_user->ID ){
			$json['type'] 	 = 'error';
            $json['message'] = $message;
            wp_send_json( $json );
		}
	}
}

/**
 * Authorize pusher
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_pusher_authorizer')) {
    function workreap_pusher_authorizer() {
		if (function_exists('fw_get_db_settings_option')) {
			$instance_id	= fw_get_db_settings_option('pusher_instance_id');
			$secret_key		= fw_get_db_settings_option('pusher_secret_key');
			$enable_pusher		= fw_get_db_settings_option('enable_pusher');
		}
		
		if( empty($instance_id) || empty($secret_key) || $enable_pusher === 'no'){return;}
		
		$beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
		   "instanceId" 	=> $instance_id,
			"secretKey" 	=> $secret_key,
		));

		$user_id	= !empty($_POST['user_id']) ? 'private-user-'. intval($_POST['user_id']) : '';
		$beamsToken = $beamsClient->generateToken($user_id);
		wp_send_json($beamsToken);
	}
}

/**
 * Required fields for freelancers
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_freelancer_required_fields' ) ) {
	function workreap_freelancer_required_fields(){
		$list	= array(
						'first_name' 		=> esc_html__('First name is required', 'workreap'),
						'last_name' 		=> esc_html__('Last name is required', 'workreap'),
						'display_name' 		=> esc_html__('Display name is required', 'workreap'),
						'per_hour_rate' 	=> esc_html__('Per hour rate is required', 'workreap'),
						'tag_line' 			=> esc_html__('Tagline is required', 'workreap'),
						'content' 			=> esc_html__('Detail is required', 'workreap'),
						'freelancer_type' 	=> esc_html__('Freelancer type is required', 'workreap'),
						'avatar' 			=> esc_html__('Profile photo is required', 'workreap'),
						'country' 			=> esc_html__('Country is required', 'workreap'),
						'address' 			=> esc_html__('Address is required', 'workreap'),
						'latitude' 			=> esc_html__('Latitude is required', 'workreap'),
						'longitude' 		=> esc_html__('Longitude is required', 'workreap'),
						'skills' 			=> esc_html__('Skills are required', 'workreap'),
					);
		return $list;
	}
}

/**
 * Required fields for employers
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_employer_required_fields' ) ) {
	function workreap_employer_required_fields(){
		$list	= array(
						'first_name' 		=> esc_html__('First name is required', 'workreap'),
						'last_name' 		=> esc_html__('Last name is required', 'workreap'),
						'display_name' 		=> esc_html__('Display name is required', 'workreap'),
						'company_name' 		=> esc_html__('Company name is required', 'workreap'),
						'user_phone_number' => esc_html__('Phne number is required', 'workreap'),
						'tag_line' 			=> esc_html__('Tagline is required', 'workreap'),
						'content' 			=> esc_html__('Detail is required', 'workreap'),
						'company_name_title'=> esc_html__('Designation in company is required', 'workreap'),
						'country' 			=> esc_html__('Country is required', 'workreap'),
						'address' 			=> esc_html__('Address is required', 'workreap'),
						'latitude' 			=> esc_html__('Latitude is required', 'workreap'),
						'longitude' 		=> esc_html__('Longitude is required', 'workreap'),
					);
		return $list;
	}
}

/**
 * Required fields for projects
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_jobs_required_fields' ) ) {
	function workreap_jobs_required_fields(){
		$list	= array(
						'title'   			=> esc_html__('Job title is required', 'workreap'),
						'deadline'   		=> esc_html__('Deadline is required', 'workreap'),
						'description'   	=> esc_html__('Description is required', 'workreap'),
						'project_level'  	=> esc_html__('Project level is required', 'workreap'),
						'project_duration'  => esc_html__('Project duration is required', 'workreap'),
						'english_level'   	=> esc_html__('English level is required', 'workreap'),
						'project_type' 		=> esc_html__('Please select job type', 'workreap'),
						'country'           => esc_html__('Country is required', 'workreap'),
						'categories' 		=> esc_html__('Please select at-least one category', 'workreap'),
						'experiences'   	=> esc_html__('Experience level is required', 'workreap'),
						'skills' 			=> esc_html__('Skills are required', 'workreap'),
						'expiry_date' 		=> esc_html__('Project expiry date are required', 'workreap'),
                        'latitude' 			=> esc_html__('Latitude is required', 'workreap'),
                        'longitude' 		=> esc_html__('Longitude are required', 'workreap'),
					);
		return $list;
	}
}

/**rangesNumber projects
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_amount_ranges' ) ) {
	function workreap_amount_ranges(){
		$list	= array(
						'1-500' 			=> esc_html__('$0 - $500', 'workreap'),
						'501-1000' 			=> esc_html__('$501 - $1000', 'workreap'),
						'1001-2000' 		=> esc_html__('$1001 - $2000', 'workreap'),
						'2001-3000' 		=> esc_html__('$2001 - $3000', 'workreap'),
						'3001-4000' 		=> esc_html__('$3001 - $4000', 'workreap'),
						'4001-5000' 		=> esc_html__('$4001 - $5000', 'workreap'),
						'5001-10000' 		=> esc_html__('$5001 - $10,000', 'workreap'),
						'10001-20000' 		=> esc_html__('$10,001 - $20,000', 'workreap'),
						'20001-30000' 		=> esc_html__('$20,001 - $30,000', 'workreap'),
						'30001-40000' 		=> esc_html__('$30,001 - $40,000', 'workreap'),
						'40001-50000' 		=> esc_html__('$40,001 - $50,000', 'workreap'),
						'50001-60000' 		=> esc_html__('$50,001 - $60,000', 'workreap'),
						'60001-70000' 		=> esc_html__('$60,001 - $70,000', 'workreap'),
						'70001-80000' 		=> esc_html__('$70,001 - $80,000', 'workreap'),
						'80001-90000' 		=> esc_html__('$80,001 - $90,000', 'workreap'),
						'90001-100000' 		=> esc_html__('$90,001 - $100,000', 'workreap'),
						'100001' 			=> esc_html__('Greater or equal to $100,000', 'workreap'),
		);
		
		$list = apply_filters('workreap_filter_amount_ranges', $list);		
		return $list;
	}
}

/**rangesNumber services
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_amount_ranges' ) ) {
	function workreap_service_amount_ranges(){
		$list	= array(
						'1-10' 			=> esc_html__('$0 - $10', 'workreap'),
						'10-20' 		=> esc_html__('$10 - $20', 'workreap'),
						'20-30' 		=> esc_html__('$20 - $30', 'workreap'),
						'30-40' 		=> esc_html__('$30 - $40', 'workreap'),
						'40-50' 		=> esc_html__('$40 - $50', 'workreap'),
						'50-60' 		=> esc_html__('$50 - $60', 'workreap'),
						'60-70' 		=> esc_html__('$60 - $70', 'workreap'),
						'70-80' 		=> esc_html__('$70 - $80', 'workreap'),
						'80-90' 		=> esc_html__('$80 - $90', 'workreap'),
						'90-100' 		=> esc_html__('$90 - $100', 'workreap'),
						'100-200' 		=> esc_html__('$100 - $200', 'workreap'),
						'200-300' 		=> esc_html__('$200 - $300', 'workreap'),
						'300-400' 		=> esc_html__('$300 - $400', 'workreap'),
						'400-500' 		=> esc_html__('$400 - $500', 'workreap'),
						'500-600' 		=> esc_html__('$500 - $600', 'workreap'),
						'600-700' 		=> esc_html__('$600 - $700', 'workreap'),
						'700-800' 		=> esc_html__('$700 - $800', 'workreap'),
						'800-900' 		=> esc_html__('$800 - $900', 'workreap'),
						'900-1000' 		=> esc_html__('$900 - $1000', 'workreap'),
						'1000-2000' 	=> esc_html__('$1000 - $2000', 'workreap'),
						'2000-3000' 	=> esc_html__('$2000 - $3000', 'workreap'),
						'3000-4000' 	=> esc_html__('$3000 - $4000', 'workreap'),
						'4000-5000' 	=> esc_html__('$4000 - $5000', 'workreap'),
						'5000-6000' 	=> esc_html__('$5000 - $6000', 'workreap'),
						'6000-7000' 	=> esc_html__('$6000 - $7000', 'workreap'),
						'7000-8000' 	=> esc_html__('$7000 - $8000', 'workreap'),
						'8000-9000' 	=> esc_html__('$8000 - $9000', 'workreap'),
						'9000-10000' 	=> esc_html__('$9000 - $10000', 'workreap'),
						'10001' 			=> esc_html__('Greater or equal to $10,000', 'workreap'),
		);
		
		$list = apply_filters('workreap_filter_services_amount_ranges', $list);		
		return $list;
	}
}

/**
 * Return Number Users
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_get_icon_name' ) ) {
	function workreap_get_icon_name($ext='file'){
		
		$list = array(
	        'docx' 		=> 'fa fa-file-word-o fa-file-word',
			'doc' 		=> 'fa fa-file-word-o fa-file-word',
			'pdf' 		=> 'fa fa-file-pdf fa-file-pdf-o',
			'zip' 		=> 'fa fa-file-zip fa-file-zip-o',
			'php' 		=> 'fa fa-code fa-code-o',
			'html' 		=> 'fa fa-code fa-code-o',
			'css' 		=> 'fa fa-code fa-code-o',
			'text' 		=> 'fa fa-file fa-file-o',
			'rar' 		=> 'fa-file-zip fa-file-zip-o',
			'xls' 	=> 'fa fa-file-excel-o fa-file-excel',
			'xlsx' 	=> 'fa fa-file-excel-o fa-file-excel',
			'ppt' 	=> 'fa fa-file-powerpoint-o fa-file-powerpoint',
			'pptx' 	=> 'fa fa-file-powerpoint-o fa-file-powerpoint',
			'csv' 	=> 'fa fa-file-excel-o fa-file-excel',
			'mp4' 	=> 'fa fa-video-camera',
			'mp3' 	=> 'fa fa-file-audio-o fa-file-audio',
			'3gp' 	=> 'fa fa-video-camera',
			'flv' 	=> 'fa fa-video-camera',
			'ogg' 	=> 'fa fa-video-camera',
			'wmv' 	=> 'fa fa-video-camera',
			'avi' 	=> 'fa fa-video-camera',
		);

		$list = apply_filters('workreap_filter_icon_name', $list);		
		
		return !empty( $list[$ext] ) ? $list[$ext] : 'fa fa-file';
	}
}

/**
**
 *  Count posts by meta keys and autor
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_count_featured_by_meta')) {

    function workreap_count_featured_by_meta( $post_type, $author_id, $key, $value, $status ='' ) {
		$meta_query_args	= array();
		$args 			= array(
							'posts_per_page' => -1,
							'post_type' => $post_type
						);
		//status
		if( !empty( $author_id ) ){
			$args['author'] = $author_id;	
		}
		//status
		if( !empty( $status ) ){
			$args['post_status'] = $status;	
		}
		
		//meta filterss
		if( !empty( $key ) && !empty( $value ) ){
			$meta_query_args[] = array(
								'key' 		=> $key,
								'value' 	=> $value,
								'compare' 	=> '='
							);
		
			$query_relation 	= array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);	
		}
		
		$query 				= new WP_Query($args);
		$count_post 		= $query->found_posts;
		return $count_post;
    }
}

/**
 * Extract array column
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_array_column_extract' ) ) {
	function workreap_array_column_extract($array, $columnkey, $indexkey = null) {
		$result = array();
		foreach ($array as $subarray => $value) {
			if (array_key_exists($columnkey,$value)) { $val = $array[$subarray][$columnkey]; }
			else if ($columnkey === null) { $val = $value; }
			else { continue; }

			if ($indexkey === null) { $result[] = $val; }
			elseif ($indexkey == -1 || array_key_exists($indexkey,$value)) {
				$result[($indexkey == -1)? $subarray:$array[$subarray][$indexkey]] = $val;
			}
		}
		return $result;
	}
}

/**
 * Year experience
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_experience_years' ) ) {
    function workreap_experience_years(){
        $list = array(
            '1'              => esc_html__('1 Year', 'workreap'),
			'2'              => esc_html__('2 Years', 'workreap'),
			'3'              => esc_html__('3 Years', 'workreap'), 
			'4'              => esc_html__('4 Years', 'workreap'), 
			'5'              => esc_html__('5 Years', 'workreap'), 
			'6'              => esc_html__('6 Years', 'workreap'), 
			'7'              => esc_html__('7 Years', 'workreap'), 
			'8'              => esc_html__('8 Years', 'workreap'), 
			'9'              => esc_html__('9 Years', 'workreap'), 
			'10'              => esc_html__('10+ Years', 'workreap'), 
			
        );
		$list = apply_filters('worktic_set_experience_years', $list);
		return $list;
    }
}

/**
 * Return Number Users
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_get_employees_list' ) ) {
	function worktic_get_employees_list(){
		$list = array(
		    '1' => array( 
		    		'title' 		=> esc_html__('Its Just Me', 'workreap'),
		    		'search_title' 	=> esc_html__('Less Than Two', 'workreap'),
		    		'value' 		=> 1,
		    	),
	        '2' => array( 
	    		'title' 		=> esc_html__('2 - 9 Employees', 'workreap'),
	    		'search_title' 	=> esc_html__('Less Than 10', 'workreap'),
	    		'value' 		=> 10,
	    	),
	        '3' => array( 
	    		'title' 		=> esc_html__('10 - 99 Employees', 'workreap'),
	    		'search_title' 	=> esc_html__('Less Than 100', 'workreap'),
	    		'value' 		=> 100,
	    	),
	        '4' => array( 
	    		'title' 		=> esc_html__('100 - 499 Employees', 'workreap'),
	    		'search_title' 	=> esc_html__('Less Than 500', 'workreap'),
	    		'value' 		=> 500,
	    	),
	        '5' => array( 
	    		'title' 		=> esc_html__('500 - 1000 Employees', 'workreap'),
	    		'search_title' 	=> esc_html__('Less Than 1000', 'workreap'),
	    		'value' 		=> 1000,
	    	),
	    	'6' => array( 
	    		'title' 		=> esc_html__('More Than 1000 Employees', 'workreap'),
	    		'search_title' 	=> esc_html__('More Than 1000', 'workreap'),
	    		'value' 		=> 5000,
	    	),
		);

		$list = apply_filters('worktic_set_employees_list', $list);			
		return $list;
	}
}

/**
 * Upload temp files to WordPress media
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_temp_upload_to_media')) {
    function workreap_temp_upload_to_media($file_url, $post_id,$encrypt=false) {
		global $wp_filesystem;
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent
		
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}
		
        $json   =  array();
        $upload_dir = wp_upload_dir();
		$folderRalativePath = $upload_dir['baseurl']."/workreap-temp";
		$folderAbsolutePath = $upload_dir['basedir']."/workreap-temp";

		$args = array(
			'timeout'     => 15,
			'headers' => array('Accept-Encoding' => ''),
			'sslverify' => false
		);
		
		$response   	= wp_remote_get( $file_url, $args );
		$file_data		= wp_remote_retrieve_body($response);
		
		if(empty($file_data)){
			return $json;
		}
		
		$filename 		= basename($file_url);
		
		//file type check
		$filetype 		= wp_check_filetype($filename);
		$allowed_types	= array('php','javascript','js','exe','text/javascript','text/php');
	    $file_ext		= !empty($filetype['ext']) ? $filetype['ext'] : ''; 
		
		if(!empty($file_ext)){
			if(in_array($file_ext,$allowed_types)){
				return $json;
			}	
		}elseif(empty($file_ext)){
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if(!empty($ext) && in_array($ext,$allowed_types)){
				return $json;
			}
		}
		
        if (wp_mkdir_p($upload_dir['path'])){
			$file = $upload_dir['path'] . '/' . $filename;
		}  else {
            $file = $upload_dir['basedir'] . '/' . $filename;
		}

		$temp_file_name	= $filename;
		
		//Rename file before update
		$file_detail  		= Workreap_file_permission::getEncryptFile($file, $post_id, false,$encrypt);
		$new_filename		= $file_detail['name'];
		$new_path 			= $upload_dir['path'] . '/' . $new_filename; 
		$file				= $new_path;
		$filename 			= basename($file);
		$actual_filename 	= pathinfo($file, PATHINFO_FILENAME);
		
		//put content to the file
		file_put_contents($file, $file_data);
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' 	=> $wp_filetype['type'],
            'post_title' 		=> sanitize_file_name($actual_filename),
            'post_content' 		=> '',
            'post_status' 		=> 'inherit'
        );
        
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
		$post_type = get_post_type($post_id);
		if( $encrypt ) {
			update_post_meta($attach_id,'is_encrypted','1');
		}
		
        $json['attachment_id']  = $attach_id;
        $json['url']            = $upload_dir['url'] . '/' . basename( $filename );
		$json['name']			= $filename;
		
		$target_path = $folderAbsolutePath . "/" . $temp_file_name;

		if(file_exists($target_path)){
        	unlink($target_path); //delete file after upload
		}
	
        return $json;
    }
}




/**
 * get total proposals
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_totoal_proposals' ) ) {
	function workreap_get_totoal_proposals($post_id='',$return='count',$count='-1') {
		global $current_user;
		ob_start();
		$args = array(
			'post_type' 		=> 'proposals',
			'posts_per_page'   	=> $count,
			'meta_query' 		=> array(
									array(
										'key'     => '_project_id',
										'value'   => $post_id,
										'compare' => '=',
									),
								),
		);
		
		$proposals 	= get_posts($args);
		
		if($return === 'count'){
			$proposals	= !empty( $proposals ) ? count($proposals) : 0;
		}

		return $proposals;
	}
}

/**
 * Prepare social sharing links for job
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_get_term_name') ){
    function workreap_get_term_name($term_id = '', $taxonomy = ''){
        if( !empty( $term_id ) && !empty( $taxonomy ) ){
            $term = get_term_by( 'id', $term_id, $taxonomy);  
            if( !empty( $term ) ){
                return $term->name;
            }
        }
        return '';
    }
}

/**
 * Get user review meta data
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_review_data')) {

    function workreap_get_review_data($user_id, $review_key = '', $type = '') {
        $review_meta = get_user_meta($user_id, 'review_data', true);
        if ($type === 'value') {
            return !empty($review_meta[$review_key]) ? $review_meta[$review_key] : '';
        }
        return !empty($review_meta) ? $review_meta : array();
    }

}

/**
 * Get Average Ratings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_everage_rating')) {

    function workreap_get_everage_rating($user_id = '') {
		$data = array();
        $meta_query_args = array('relation' => 'AND');
        $meta_query_args[] = array(
            'key' 		=> 'user_to',
            'value' 	=> $user_id,
            'compare' 	=> '=',
            'type' 		=> 'NUMERIC'
        );

        $args = array('posts_per_page' => -1,
            'post_type' 		=> 'reviews',
            'post_status' 		=> 'publish',
            'order' 			=> 'ASC',
        );

        $args['meta_query'] = $meta_query_args;

        $average_rating = 0;
        $total_rating   = 0;
		
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
                global $post;
                $user_rating = get_post_meta($post->ID, 'user_rating', true);
			
                $average_rating = $average_rating + $user_rating;
                $total_rating++;

            endwhile;
            wp_reset_postdata();
        }

        $data['wt_average_rating'] 			= 0;
        $data['wt_total_rating'] 			= 0;
        $data['wt_total_percentage'] 		= 0;
		
        if (isset($average_rating) && $average_rating > 0) {
            $data['wt_average_rating'] 			= $average_rating / $total_rating;
            $data['wt_total_rating'] 			= $total_rating;
            $data['wt_total_percentage'] 		= ( $average_rating / $total_rating) * 5;
        }

        return $data;
    }

}

/**
 * Get milestone state by status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_milestone_statistics')) {

    function workreap_get_milestone_statistics($propsal_id = '',$status='pending') {
		
        $meta_query_args = array('relation' => 'AND');
        $meta_query_args[] = array(
            'key' 		=> '_propsal_id',
            'value' 	=> $propsal_id,
            'compare' 	=> '=',
            'type' 		=> 'NUMERIC'
		);

        $args = array('posts_per_page' => -1,
			'post_type' 		=> 'wt-milestone',
			'post_status'		=> $status,
            'order' 			=> 'ASC',
        );

        $args['meta_query'] = $meta_query_args;

        $total_price   = 0;
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
				global $post;
                $user_price = get_post_meta($post->ID, '_price', true);

                $total_price = $total_price + $user_price;
			
            endwhile;
            wp_reset_postdata();
        }
		
        return $total_price;
    }

}

/**
 * Count items in array
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_count_items')) {
    function workreap_count_items($items) {
        if( is_array($items) ){
			return count($items);
		} else{
			return 0;
		}
    }
}

/**
 * Get Project Ratings Headings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_project_ratings' ) ) {
	function workreap_project_ratings( $key = 'project_ratings' ){
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$ratings_headings	= fw_get_db_settings_option( $key, true);
			
			if( !empty( $ratings_headings ) and is_array($ratings_headings) ){
				$ratings_headings = array_filter($ratings_headings);
				$ratings_headings = array_combine(array_map('sanitize_title', $ratings_headings), $ratings_headings);
				return $ratings_headings;
			} else{
				return array();
			}
			
		} else {
			return array();
		}
	}
	
}

/**
 * Get earning for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_earning_freelancer' ) ) {
    function workreap_get_earning_freelancer( $user_identity,$limit=6  ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_earnings";
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_identity) ) {
				$e_query	= $wpdb->prepare("SELECT * FROM $table_name where user_id =%d and ( status = 'completed' || status = 'processed' ) ORDER BY id DESC LIMIT %d",$user_identity,$limit);
				$earning = $wpdb->get_results( $e_query );
			} else {
				$earning	= 0;
			}
		} else{
			$earning	= 0;
		}
		
		return $earning;
		
	}
}

/**
 * Get sum earning for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_sum_earning_freelancer' ) ) {
    function workreap_get_sum_earning_freelancer( $user_id='',$status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_earnings";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_id) && !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT sum(".$colum_name.") FROM ".$table_name." WHERE user_id = %d and status = %s",$user_id,$status);
				$total_earning	= $wpdb->get_var( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Get sum earning for milestone
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_sum_earning_milestone' ) ) {
    function workreap_get_sum_earning_milestone( $project_id='',$status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_earnings";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($project_id) && !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT sum(".$colum_name.") FROM ".$table_name." WHERE project_id = %d and status = %s",$project_id,$status);
				$total_earning	= $wpdb->get_var( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Get total earning for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_total_earning_freelancer' ) ) {
    function workreap_get_total_earning_freelancer( $user_id='',$status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_earnings";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_id) && !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT sum(".$colum_name.") FROM ".$table_name." WHERE user_id = %d and ( status = %s || status = %s )",$user_id,$status[0],$status[1]);
				$total_earning	= $wpdb->get_var( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Get earning for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_payments_freelancer' ) ) {
    function workreap_get_payments_freelancer( $user_identity,$limit=6  ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_payouts_history";
		$month		= date('m');
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_identity) ) {
				$e_query	= $wpdb->prepare("SELECT * FROM $table_name where ( user_id =%d and status= 'completed' And month=%d) ORDER BY id DESC LIMIT %d",$user_identity,$month,$limit);
				$payments = $wpdb->get_results( $e_query );
			} else {
				$payments	= 0;
			}
		} else{
			$payments	= 0;
		}
		
		return $payments;
		
	}
}


/**
 * Get earning for workreap
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_payments_workreap' ) ) {
    function workreap_get_payments_workreap( $user_identity,$limit=6  ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_payouts_history";
		$month		= date('m');
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_identity) ) {
				$e_query	= $wpdb->prepare("SELECT * FROM $table_name where ( user_id =%d and status= 'completed' AND month=%d) ORDER BY id DESC LIMIT %d",$user_identity,$month,$limit);
				$payments = $wpdb->get_results( $e_query );
			} else {
				$payments	= 0;
			}
		} else{
			$payments	= 0;
		}
		
		return $payments;
		
	}
}

/**
 * Get sum payments for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_sum_payments_freelancer' ) ) {
    function workreap_get_sum_payments_freelancer( $user_id='',$status='',$colum_name='') {
		global $wpdb;

		return $current_balance	= workreap_get_total_earning_freelancer($user_id,array('completed','processed'),'freelancer_amount');
	}
}


/**
 * Get package type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_package_type')) {

	 function workreap_get_package_type($key, $value) {
		global $wpdb;
		$meta_query_args = array();
		$args = array(
			'post_type' 			=> 'product',
			'posts_per_page' 		=> 1,
			'order' 				=> 'DESC',
			'orderby' 				=> 'ID',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts' 	=> 1
		);
		 
		$meta_query_args[] = array(
			'key' 			=> $key,
			'value' 		=> $value,
			'compare' 		=> '=',
		);	
		 
		$query_relation 		= array('relation' => 'AND',);
		$meta_query_args 		= array_merge($query_relation, $meta_query_args);
		$args['meta_query'] 	= $meta_query_args;
		
		$trial_product = get_posts($args);
		
		if (!empty($trial_product)) {
            return (int) $trial_product[0]->ID;
        } else{
			 return 0;
		}
	}
}

/**
 * Get subscription metadata
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_subscription_metadata')) {

    function workreap_get_subscription_metadata($key = '', $user_id) {
        $wt_subscription 	= get_user_meta($user_id, 'wt_subscription', true);
		$current_date 		= current_time('mysql');
		
		if (function_exists('fw_get_db_settings_option')) {
			$remove_chat = fw_get_db_settings_option('remove_chat', $default_value = null);
		}
		
		//check if listing is free
		if(apply_filters('workreap_is_listing_free',false,$user_id) === true ){
			return 'yes';
		}
		
		//Free chat
		if( !empty( $remove_chat ) && $remove_chat === 'yes' && $key === 'wt_pr_chat' ){
			return 'yes';
		}

        if ( is_array( $wt_subscription ) && !empty( $wt_subscription[$key] ) ) {
			if (!empty($wt_subscription['subscription_featured_string']) && $wt_subscription['subscription_featured_string'] > strtotime($current_date)) {
				return $wt_subscription[$key];
			}
        }
    }

}

/**
 * Get Packages Type 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_packages_types' ) ) {

	function workreap_packages_types( $post = '') {
		if ( !empty( $post ) ) {
			$package_type	= get_post_meta( $post->ID , 'package_type', true);
		}
		
		$system_access = 'paid';
		if (function_exists('fw_get_db_settings_option')) {
			$system_access = fw_get_db_settings_option('system_access', $default_value = null);
		}
		
		$packages						= array();
		$packages[0]					= esc_html__('Package Type', 'workreap');
		if( $system_access != 'both'  ){
			$trail_employer_package_id		= workreap_get_package_type( 'package_type','trail_employer');
			$trail_freelancer_package_id	= workreap_get_package_type( 'package_type','trail_freelancer');
			
			if( $system_access != 'employer_free' ){
				$packages['employer']			= esc_html__('For Employer', 'workreap');
				if( empty($trail_employer_package_id )) {
					$packages['trail_employer']		= esc_html__('For Trial Employer', 'workreap');
				} else if ( !empty( $post ) && $package_type === 'trail_employer') {
					$packages['trail_employer']		= esc_html__('For Trial Employer', 'workreap');
				}
			}
			
			if( $system_access != 'freelancer_free' ){
				$packages['freelancer']			= esc_html__('For Freelancer', 'workreap');

				if( empty( $trail_freelancer_package_id ) ) {
					$packages['trail_freelancer']	= esc_html__('For Trial Freelancer', 'workreap');
				} else if ( !empty( $post ) && $package_type === 'trail_freelancer') {
					$packages['trail_freelancer']	= esc_html__('For Trial Freelancer', 'workreap');
				}
			}
			
		}
		
		
		return $packages;
	}
}

/**
 * Get Pakages Featured attribute
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_pakages_features_attributes')) {

    function workreap_get_pakages_features_attributes( $key ='' , $attr = 'title' ) {
		$features		= workreap_get_pakages_features();
		if( !empty ( $key ) && !empty ( $attr )) {
			$attribute	= $features[$key][$attr];
		} else {
			$attribute ='';
		}
		return $attribute;
	}
}

/**
 * Get All Badges
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_pakages_badges')) {

    function workreap_get_pakages_badges() {
		$values	= array();
		
		if( taxonomy_exists('badge_cat') ) {
			$terms = get_terms( array(
				'taxonomy' 		=> 'badge_cat',
				'hide_empty' 	=> false,
			) );

			$values	= array();
			$values['']	= esc_html__('Select a Badge','workreap');

			if( !empty($terms) ) {
				foreach( $terms as $term ) {
					$values[$term->term_id]	= $term->name;
				}
			} 
		}
		
		return $values;
	}
}

/**
 * Hire freelancer after payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_hired_freelancer_after_payment' ) ) {

	function workreap_hired_freelancer_after_payment( $job_id, $proposal_id ) {
		global $current_user;
		
		update_post_meta( $job_id, '_proposal_id', $proposal_id );
		$job_post_data = array(
							'ID'            => $job_id,
							'post_status'   => 'hired',
						);

		wp_update_post( $job_post_data );

		$hired_freelance_id			= get_post_field('post_author',$proposal_id);
		$hired_freelance_profile_id = workreap_get_linked_profile_id( $hired_freelance_id );
		update_post_meta( $job_id, '_freelancer_id', $hired_freelance_profile_id );
	}
}

/**
 * Hire milestone freelancer after payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_hired_milestone_after_payment' ) ) {

	function workreap_hired_milestone_after_payment( $milestone_id ) {

		update_post_meta( $milestone_id, '_status', 'hired' );
		$current_date 	= current_time('mysql');
		$hired_date		= date('Y-m-d H:i:s', strtotime($current_date));
		update_post_meta( $milestone_id, '_hired_date', $hired_date );
		$job_post_data = array(
							'ID'            => $milestone_id,
							'post_status'   => 'publish',
						);

		wp_update_post( $job_post_data );
	}
}
/**
 * Hiring payment setting
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'worrketic_hiring_payment_setting' ) ) {
	function worrketic_hiring_payment_setting($type='',$id='') {
		$settings	 = array();

		if (function_exists('fw_get_db_settings_option')) {
            $hiring 		= fw_get_db_settings_option('hiring_payment_settings');
			$min_amount 	= fw_get_db_settings_option('min_amount');
			
			if( !empty($id) ){
				$service_fee = fw_get_db_post_option( $id, 'service_fee', true );
				
				if( !empty($type) && $type === 'services' ){
					$service_fee = fw_get_db_post_option( $id, 'service_commision', true );
				}
				
				if( empty( $service_fee['gadget'] ) || ( isset( $service_fee['gadget'] ) && $service_fee['gadget'] === 'none' ) ){
					$service_fee 	= fw_get_db_settings_option('service_fee');

					if( !empty($type) && $type === 'services' ){
						$service_fee 	= fw_get_db_settings_option('service_commision');
					}
				}

			}else{
				$service_fee 	= fw_get_db_settings_option('service_fee');

				if( !empty($type) && $type === 'services' ){
					$service_fee 	= fw_get_db_settings_option('service_commision');
				}
			}	
        }

		$settings['type']			= !empty( $service_fee['gadget'] )  ? $service_fee['gadget'] : 'none';
		$settings['fixed_amount']	= !empty( $service_fee['fixed']['amount'] )  ? $service_fee['fixed']['amount'] : 0;
		$settings['percentage']		= !empty( $service_fee['percentage']['percentage'] )  ? $service_fee['percentage']['percentage'] : 0;
		$settings['comissions_tiers']		= !empty( $service_fee['comissions_tiers']['add_tiers'] )  ? $service_fee['comissions_tiers']['add_tiers'] : array();

		$settings['minamount']		= !empty( $min_amount )  ? $min_amount : 0;

		return $settings;
	}
}


/**
 * Hiring payment setting
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_employer_hiring_payment_setting' ) ) {
	function workreap_employer_hiring_payment_setting($type='',$price=0) {
		$settings	 = array();

		if (function_exists('fw_get_db_settings_option')) {
			$service_fee 	= fw_get_db_settings_option('employer_service_fee');

			if( !empty($type) && $type === 'services' ){
				$service_fee 	= fw_get_db_settings_option('employer_service_commision');
			}	
        }

		$settings['type']			= !empty( $service_fee['gadget'] )  ? $service_fee['gadget'] : 'none';
		$settings['fixed_amount']	= !empty( $service_fee['fixed']['amount'] )  ? $service_fee['fixed']['amount'] : 0;
		$settings['percentage']		= !empty( $service_fee['percentage']['percentage'] )  ? $service_fee['percentage']['percentage'] : 0;
		$settings['comissions_tiers']		= !empty( $service_fee['comissions_tiers']['add_tiers'] )  ? $service_fee['comissions_tiers']['add_tiers'] : array();
		
		if(!empty($settings['type']) && $settings['type'] === 'fixed' ){
			$commission	= !empty($settings['fixed_amount']) ? $settings['fixed_amount'] : 0;
			$price 		= $price + $commission;
			
		}else if(!empty($settings['type']) && $settings['type'] === 'percentage' ){
			$percentage		= !empty($settings['percentage']) ? $settings['percentage'] : 0;
			$commission 	= $price/100 * $percentage;
			$price 			= $price + $commission;
		}else if(!empty($settings['type']) && $settings['type'] === 'comissions_tiers' ){
			$comissions_tiers	= !empty( $settings['comissions_tiers'] ) ? $settings['comissions_tiers'] : array();
			
			if(!empty($comissions_tiers)){
				foreach($comissions_tiers as $key => $item){
					$range		= !empty( $item['range'] ) ? explode('-',$item['range']) : 0;
					$commission	= !empty( $item['amount']) ? $item['amount'] : 0;
					
					$start	=  !empty($range[0]) ? $range[0] : 0;
					$end	=  !empty($range[1]) ? $range[1] : 0;
					
					if( !empty($start) && !empty($end) ){
						if( $price >= $start && $price <= $end ){

							if(!empty($item['type']) && $item['type'] === 'fixed' ){
								$price 			= $price + $commission;

							}else if(!empty($item['type']) && $item['type'] === 'percentage' ){
								$commission 	= $price/100 * $commission;
								$price 			= $price + $commission;
							}
							
							break;
						}
					}else if( !empty($start) && empty($end) ){
						if( $price >= $start ){
							if(!empty($item['type']) && $item['type'] === 'fixed' ){
								$price 			= $price + $commission;

							}else if(!empty($item['type']) && $item['type'] === 'percentage' ){
								$commission 	= $price/100 * $commission;
								$price 			= $price + $commission;
							}
							
							break;
						}
					}
				}
			}
		}else{
			$price 	= $price;
		}
		
		$settings['commission_amount']	= !empty( $commission )  ? $commission : 0.0;
		$settings['total_amount']		= $price;

		return $settings;
	}
}


/**
 * Update freelancer earning
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_commission_fee' ) ) {

	function workreap_commission_fee( $proposed_price='',$type='',$id='' ) {
		global $wpdb;
		$settings	= worrketic_hiring_payment_setting($type,$id);
		
		if (function_exists('fw_get_db_post_option')) {
			$db_project_type    	= fw_get_db_post_option($id,'project_type');
		}

		// if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
		// 	$proposed_price 	= !empty( $db_project_type['fixed']['project_cost'] ) ? $db_project_type['fixed']['project_cost'] : 0;
		// } else if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){
		// 	$estimated_hours 	= !empty( $db_project_type['hourly']['estimated_hours'] ) ? $db_project_type['hourly']['estimated_hours'] : 0;
		// 	$proposed_price 	= !empty( $db_project_type['hourly']['hourly_rate'] ) ? $estimated_hours * ($db_project_type['hourly']['hourly_rate'] ) : 0;
		// }
		
		if(!empty($settings['type']) && $settings['type'] === 'fixed' ){
			$fixed_amount	= !empty($settings['fixed_amount']) ? $settings['fixed_amount'] : 0;
			
			$admin_shares 		= $fixed_amount;
			$freelancer_shares 	= $proposed_price - $admin_shares;
			
		}else if(!empty($settings['type']) && $settings['type'] === 'percentage' ){
			$percentage	= !empty($settings['percentage']) ? $settings['percentage'] : 0;
			
			$admin_shares 		= $proposed_price/100 * $percentage;
			$freelancer_shares 	= $proposed_price - $admin_shares;
		}else if(!empty($settings['type']) && $settings['type'] === 'comissions_tiers' ){
			$comissions_tiers	= !empty( $settings['comissions_tiers'] ) ? $settings['comissions_tiers'] : array();
			
			$admin_shares 		= 0;
			$freelancer_shares 	= $proposed_price;
			
			if(!empty($comissions_tiers)){
				foreach($comissions_tiers as $key => $item){
					$range	= !empty( $item['range'] ) ? explode('-',$item['range']) : 0;
					$amount	= !empty( $item['amount']) ? $item['amount'] : 0;
					
					$start	=  !empty($range[0]) ? $range[0] : 0;
					$end	=  !empty($range[1]) ? $range[1] : 0;

					if( !empty($start) && !empty($end) ){
						if( $proposed_price >= $start && $proposed_price <= $end ){
							if(!empty($item['type']) && $item['type'] === 'fixed' ){
								$admin_shares 		= $amount;
								$freelancer_shares 	= $proposed_price - $admin_shares;

							}else if(!empty($item['type']) && $item['type'] === 'percentage' ){
								$admin_shares 		= $proposed_price/100 * $amount;
								$freelancer_shares 	= $proposed_price - $admin_shares;
							}
							
							break;
						}
					}else if( !empty($start) && empty($end) ){
						if( $proposed_price >= $start ){
							if(!empty($item['type']) && $item['type'] === 'fixed' ){
								$admin_shares 		= $amount;
								$freelancer_shares 	= $proposed_price - $admin_shares;

							}else if(!empty($item['type']) && $item['type'] === 'percentage' ){
								$admin_shares 		= $proposed_price/100 * $amount;
								$freelancer_shares 	= $proposed_price - $admin_shares;
							}
							
							break;
						}
					}
				}
			}
		}else{
			$admin_shares 		= 0.0;
			$freelancer_shares 	= $proposed_price;
		}

		$settings['admin_shares'] 		= !empty($admin_shares) && $admin_shares > 0 ? number_format($admin_shares,2,'.', '') : 0.0;
		$settings['freelancer_shares'] 	= !empty($freelancer_shares) && $freelancer_shares > 0 ? number_format($freelancer_shares,2,'.', '') : 0.0;
		
		return $settings;
	}
}

/**
 * Update freelancer earning
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_update_earning' ) ) {

	function workreap_update_earning( $where, $update, $table_name ) {
		global $wpdb;
		
		if( !empty($where) && !empty($update) && !empty($table_name) ) {
			$wpdb->update($wpdb->prefix.$table_name, $update, $where);
		} else {
			return false;
		}
	}
}

/**
 * Get account settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_account_settings' ) ) {
	function workreap_get_account_settings($key='') {
		global $current_user;
		$settings = array(
			'freelancer' => array(
				'_profile_blocked' 			=> esc_html__('Disable my account temporarily','workreap'),
				'_hourly_rate_settings' 	=> esc_html__('Disable hourly rate on frontend','workreap'),
				'_project_notification' 	=> esc_html__('New project notifications','workreap'),
			),
			'employer' => array(
				'_profile_blocked' 		=> esc_html__('Disable my account temporarily','workreap'),
			),
		);
		
		if( function_exists('fw_get_db_settings_option')  ){
			$hide_perhour	= fw_get_db_settings_option('hide_freelancer_perhour', $default_value = null);
		}
		
		
		if( isset($hide_perhour) && $hide_perhour === 'yes' ){
			unset( $settings['freelancer']['_hourly_rate_settings']);
		}

		$settings	= apply_filters('workreap_filters_account_settings',$settings);
		
		return !empty( $settings[$key] ) ? $settings[$key] : array();
	}
}

/**
 * Get leave reasons list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_account_delete_reasons' ) ) {
	function workreap_get_account_delete_reasons($key='') {
		global $current_user;
		$list = array(
			'not_satisfied' => esc_html__('Not satisfied','workreap'),
			'support' 		=> esc_html__('Support is not good','workreap'),
			'other' 		=> esc_html__('Others','workreap'),
		);

		$reasons	= apply_filters('workreap_filters_account_delete_reasons',$list);
		
		if( !empty( $key ) ){
			return !empty( $list[$key] ) ? $list[$key] : '';
		}
		
		return $reasons;
	}
}

/**
 * Get linked profile ID
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_linked_profile_id')) {

    function workreap_get_linked_profile_id($user_identity, $type='users') {
		
		if( $type == 'post') {
			$linked_profile   	= get_post_meta($user_identity, '_linked_profile', true);
		} else {
			$linked_profile   	= get_user_meta($user_identity, '_linked_profile', true);
		}

        $linked_profile	= !empty( $linked_profile ) ? $linked_profile : '';
		
        return intval( $linked_profile );
    }
}

/**
 * Get skills
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_all_skills' ) ) {
    function workreap_get_all_skills(){
		$skills	= array();
		if( taxonomy_exists('skills') ) {
			$args = array(
				'hide_empty' => false,
			);

			$terms = get_terms('skills', $args);


			if (!empty($terms)) {
				foreach ($terms as $key => $term) {
					$skills[$term->term_id]['name'] = $term->name;
					$skills[$term->term_id]['id'] 	= $term->term_id;
					$skills[$term->term_id]['slug'] = $term->slug;
				}
			}
		}
		
		return $skills;
    }
}

/**
 * Get texanomy list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_texanomy_list' ) ) {
    function workreap_get_texanomy_list($texnomy_name){
		$texnomy_list	= array();
		if( taxonomy_exists($texnomy_name) ) {
			$args = array(
				'hide_empty' => false,
			);

			$terms = get_terms($texnomy_name, $args);


			if (!empty($terms)) {
				foreach ($terms as $key => $term) {
					$texnomy_list[$term->term_id]['name'] = $term->name;
					$texnomy_list[$term->term_id]['id'] 	= $term->term_id;
					$texnomy_list[$term->term_id]['slug'] = $term->slug;
				}
			}
		}
		
		return $texnomy_list;
    }
}

/**
 * Get project level
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_project_level' ) ) {
    function workreap_get_project_level($key=''){
		$term_data = get_terms( 
			array(
				'taxonomy' 		=> 'project_levels',
				'hide_empty' 	=> false,
			) 
		);

		if( !empty( $term_data ) && empty( $key ) ){
			return wp_list_pluck( $term_data, 'name', 'slug' );
		} else if( !empty( $term_data ) && !empty( $key ) ){
			$data	= workreap_get_term_by_type('slug', $key, 'project_levels', 'name');
			if( !empty( $data ) ){
				return $data;
			}
		}
		
        $list = array(
			'basic' 		=> esc_html__('Basic Level','workreap'),
			'medium' 		=> esc_html__('Medium Level','workreap'),
			'expensive' 	=> esc_html__('Expensive','workreap'),
		);

		$levels	= apply_filters('workreap_filters_project_level',$list);
		
		if( !empty( $key ) ){
			return !empty( $levels[$key] ) ? $levels[$key] : '';
		}
		
		return $levels;
    }
}

/**
 * Get job duration
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_job_type' ) ) {
    function workreap_get_job_type($key=''){
		$project_type_show	= '';
		if( function_exists('fw_get_db_settings_option')  ){
			$project_type_show	= fw_get_db_settings_option('project_type_show', $default_value = null);
		}
		
        $list = array(
			'fixed' => esc_html__('Fixed project','workreap'),
			'hourly' => esc_html__('Hourly Based Project','workreap')
		);
		
		$data	= apply_filters('workreap_filters_job_type',$list);
		
		//data
		if(!empty($project_type_show) && $project_type_show !== 'both' ){
			unset($data[$project_type_show]);
		}
		
		if( !empty( $key ) ){
			return !empty( $data[$key] ) ? $data[$key] : '';
		}
		
		return $data;
    }
}

/**
 * Get job option 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_job_option ' ) ) {
    function workreap_get_job_option ($key=''){
        $list = array(
			'onsite' 			=> esc_html__('Onsite','workreap'),
			'partial_onsite' 	=> esc_html__('Partial Onsite','workreap'),
			'remote' 			=> esc_html__('Remote','workreap'),
		);

		$data	= apply_filters('workreap_filters_job_option',$list);
		
		if( !empty( $key ) ){
			return !empty( $data[$key] ) ? $data[$key] : '';
		}
		
		return $data;
    }
}	


/**
 * Get Project price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_project_price ' ) ) {
    function workreap_project_price ($job_id=''){

		$job_price_option	= '';
		if( function_exists('fw_get_db_settings_option')  ){
			$job_price_option	= fw_get_db_settings_option('job_price_option', $default_value = null);
		}

		$db_project_type	= array();
		if( function_exists('fw_get_db_post_option')  ){
			$db_project_type	= fw_get_db_post_option($job_id,'project_type');
		}

		$data				= array();	
		$project_cost		= 0;
		$price_text			= '';
		$estimated_hours 	= 0;
		
		if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
			$job_type_text = '';
			$price_text		= esc_html__('Cost','workreap');
			$amount_text	= esc_html__('Enter Your Proposal Amount','workreap');
			$project_cost 	= !empty( $db_project_type['fixed']['project_cost'] ) ? workreap_price_format($db_project_type['fixed']['project_cost'],'return') : '';
			$max_val		= !empty($db_project_type['fixed']['project_cost']) ? $db_project_type['fixed']['project_cost'] : 0;
			
			if(!empty($job_price_option) && $job_price_option === 'enable') {
				$db_max_price	= !empty( $db_project_type['fixed']['max_price'] ) ? $db_project_type['fixed']['max_price'] : '';
				$project_cost	= !empty($db_max_price) ? ($project_cost.' - '.workreap_price_format($db_max_price,'return')) : $project_cost;
				$max_val			= $db_max_price;
			} 
		} else if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){
			$price_text			= esc_html__('Per hour rate for estimated','workreap');
			$amount_text		= esc_html__('Enter Your Per Hour rate','workreap');
			$estimated_hours 	= !empty( $db_project_type['hourly']['estimated_hours'] ) ? $db_project_type['hourly']['estimated_hours'] : 0;
			$job_type_text		= ' '.$estimated_hours.esc_attr__(" hours","workreap");
			$project_cost 		= !empty( $db_project_type['hourly']['hourly_rate'] ) ? ($db_project_type['hourly']['hourly_rate'] ) : 0;
			$max_val			= $project_cost;
				
			if(!empty($job_price_option) && $job_price_option === 'enable') {
				$db_max_price	= !empty( $db_project_type['hourly']['max_price'] ) ? ($db_project_type['hourly']['max_price'] ) : 0;
				$max_val		= $db_max_price;
				$project_cost	= !empty($db_max_price) ? (workreap_price_format($project_cost,'return').' - '.workreap_price_format($db_max_price,'return')) : workreap_price_format($project_cost,'return');
			} else {
				$total_amount		= $estimated_hours * $project_cost;
				$project_cost		= workreap_price_format($project_cost,'return');
				$total_amount		= apply_filters('workreap_price_format',$total_amount,'return');
				$job_type_text		.= '<br>'.esc_attr__("Total Amount","workreap").' = '.$total_amount;
			} 
			
			$job_type_text		.= '';
		}
		
		$data['cost']		= !empty($project_cost) ? $project_cost : 0;
		$data['max_val']	= !empty($max_val) ? $max_val : 0;
		$data['price_text']	= !empty($price_text) ? $price_text : '';
		$data['amount_text']= !empty($amount_text) ? $amount_text : '';
		$data['text']		= !empty($job_type_text) ? $job_type_text : '';
		$data['estimated_hours'] = !empty( $estimated_hours ) ? $estimated_hours : '';
		$data['type']		= !empty($db_project_type['gadget']) ? $db_project_type['gadget'] : '';

		return $data;
    }
}

/**
 * Filter dashboard menu
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_dashboard_menu' ) ) {
	function workreap_get_dashboard_menu() {
		global $current_user;
		
		$menu_settings = get_option( 'wt_dashboard_menu_settings' );
		
		$list	= array(
			'switch-account'	=> array(
				'title' 	=> esc_html__('switch','workreap'),
				'type'		=> 'none',
				'hide_left'	=> 'no'
			),
			'insights'	=> array(
				'title' => esc_html__('Dashboard','workreap'),
				'type'	=> 'none'
			),
			'create-post'	=> array(
				'title' => esc_html__('Create','workreap'),
				'type'	=> 'none'
			),
			'chat'	=> array(
				'title' => esc_html__('Inbox','workreap'),
				'type'	=> 'none'
			),
			'preview'	=> array(
				'title' => esc_html__('View my profile','workreap'),
				'type'	=> 'none'
			),
			'identity-verification'	=> array(
				'title' => esc_html__('Identity Verification','workreap'),
				'type'	=> 'none'
			),
			'manage-settings'	=> array(
				'title' => esc_html__('Settings','workreap'),
				'type'	=> 'none'
			),
			'manage-portfolios'	=> array(
				'title' => esc_html__('Manage Portfolios','workreap'),
				'type'	=> 'freelancer'
			),
			'manage-projects'	=> array(
				'title' => esc_html__('Manage Projects','workreap'),
				'type'	=> 'freelancer'
			),
			'manage-jobs'	=> array(
				'title' => esc_html__('Manage Projects','workreap'),
				'type'	=> 'employer'
			),
			'manage-services'	=> array(
				'title' => esc_html__('Manage Services','workreap'),
				'type'	=> 'freelancer'
			),
			
			'manage-service'	=> array(
				'title' => esc_html__('Manage Services','workreap'),
				'type'	=> 'employer'
			),
			'saved'	=> array(
				'title' => esc_html__('Saved Items','workreap'),
				'type'	=> 'none'
			),
			'invoices'	=> array(
				'title' => esc_html__('Invoices','workreap'),
				'type'	=> 'none'
			),
			
			'disputes'	=> array(
				'title' => esc_html__('Disputes','workreap'),
				'type'	=> 'none'
			),
			'help'	=> array(
				'title' => esc_html__('Help & Support','workreap'),
				'type'	=> 'none'
			),

			'packages'	=> array(
				'title' => esc_html__('Packages','workreap'),
				'type'	=> 'none'
			),
			'logout'	=> array(
				'title' => esc_html__('Logout','workreap'),
				'type'	=> 'none'
			)
		);
		
		$final_list	= !empty( $menu_settings ) ? $menu_settings : $list;
		$menu_list 	= apply_filters('workreap_filter_dashboard_menu',$final_list);
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$remove_dispute = fw_get_db_settings_option( 'remove_dispute');
			$remove_saved = fw_get_db_settings_option( 'remove_saved');
		}
		
		if(!empty($remove_saved) && $remove_saved === 'yes'){
			unset($menu_list['saved']);
		}
		
		if(!empty($remove_dispute) && $remove_dispute === 'yes'){
			unset($menu_list['disputes']);
		}
		
		return $menu_list;
	}
}

/**
 * Get freelancer banner
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_get_freelancer_banner' ) ) {
	function workreap_get_freelancer_banner( $sizes = array(), $user_identity = '' ) {
		extract( shortcode_atts( array(
			"width" => '1920',
			"height" => '400',
		), $sizes ) );
		
		$height = intval($height);
		$width  = intval($width);
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$default_banner = fw_get_db_settings_option( 'default_freelancer_banner', $default_value = null );
			$thumb_id = fw_get_db_post_option( $user_identity, 'banner_image', true );
		}

		if ( !empty( $thumb_id['attachment_id'] ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id['attachment_id'], array( $width, $height ), true );

			if ( $thumb_url[1] === $width && $thumb_url[2] === $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id['attachment_id'], 'full', true );
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}
		} else {
			if ( !empty( $default_banner['attachment_id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_banner['attachment_id'], array( $width, $height ), true );

				if ( $thumb_url[1] === $width && $thumb_url[2] === $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_banner['attachment_id'], "full", true );
					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{
						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}
				}
			} else {
				return false;
			}
		}
	}
}

/**
 * Get employer banner
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_get_employer_banner' ) ) {
	function workreap_get_employer_banner( $sizes = array(), $user_identity = '' ) {
		extract( shortcode_atts( array(
			"width" => '1140',
			"height" => '400',
		), $sizes ) );
		
		$height = intval($height);
		$width  = intval($width);
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$default_banner = fw_get_db_settings_option( 'default_employer_banner', $default_value = null );
			$thumb_id = fw_get_db_post_option( $user_identity, 'banner_image', true );
		}

		if ( !empty( $thumb_id['attachment_id'] ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id['attachment_id'], array( $width, $height ), true );
			if ( $thumb_url[1] === $width and $thumb_url[2] === $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id['attachment_id'], 'full', true );
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}
		} else {
			if ( !empty( $default_banner['attachment_id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_banner['attachment_id'], array( $width, $height ), true );

				if ( $thumb_url[1] === $width and $thumb_url[2] === $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_banner['attachment_id'], "full", true );
					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{
						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}
				}
			} else {
				return false;
			}
		}
	}
}

/**
 * Get freelancer avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_get_freelancer_avatar' ) ) {
	function workreap_get_freelancer_avatar( $sizes = array(), $user_identity = '' ) {
		extract( shortcode_atts( array(
			"width" => '100',
			"height" => '100',
		), $sizes ) );
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$default_avatar = fw_get_db_settings_option( 'default_freelancer_avatar', $default_value = null );
		}

		$thumb_id = get_post_thumbnail_id( $user_identity );
		
		if ( !empty( $thumb_id ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id, array( $width, $height ), true );
			if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}
		} else {
			if ( !empty( $default_avatar['attachment_id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_avatar['attachment_id'], array( $width, $height ), true );

				if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_avatar['attachment_id'], "full", true );
					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{
						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}
				}
			} else {
				return false;
			}
		}
	}
}


/**
 * User verification check
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_username' ) ) {
	function workreap_get_username( $user_id = '' , $linked_profile = '' ){
		$shortname_option	= '';
		if( function_exists('fw_get_db_settings_option')  ){
			$shortname_option	= fw_get_db_settings_option('shortname_option', $default_value = null);
		}
		
		if( !empty( $linked_profile ) ){
			$title	= get_the_title($linked_profile);
			if(!empty($shortname_option) && $shortname_option === 'enable' ){
				$full_name		= explode(' ',$title);
				$first_name		= !empty($full_name[0]) ? ucfirst($full_name[0]) : '';
				$second_name	= !empty($full_name[1]) ? ' '.strtoupper($full_name[1][0]) : '';
				return esc_html( $first_name.$second_name );
			} else {
				return esc_html( $title );
			}
		} 
		
		if ( empty($user_id) ) {
            return esc_html__('unnamed', 'workreap');
        }
		
        $userdata = get_userdata($user_id);
        $user_role = '';
        if (!empty($userdata->roles[0])) {
            $user_role = $userdata->roles[0];
        }

        if (!empty($user_role) && $user_role === 'freelancers' || $user_role === 'employers' ) {
			$linked_profile   	= workreap_get_linked_profile_id($user_id);
			if( !empty( $linked_profile ) ){
				$title	= get_the_title($linked_profile);
				if(!empty($shortname_option) && $shortname_option === 'enable' ){
					$full_name		= explode(' ',$title);
					$first_name		= !empty($full_name[0]) ? ucfirst($full_name[0]) : '';
					$second_name	= !empty($full_name[1]) ? ' '.strtoupper($full_name[1][0]) : '';
					
					return esc_html( $first_name.$second_name );
				} else {
					return esc_html( $title );
				}
			} else{
				if (!empty($userdata->first_name) && !empty($userdata->last_name)) {
					
					if(!empty($shortname_option) && $shortname_option === 'enable' ){
						$last_name		= substr($userdata->last_name,0,1);
						$second_name	= !empty($last_name) ? ' '.ucfirst($last_name) : '';
						return esc_html( $userdata->first_name.$second_name );
					} else {
						return $userdata->first_name . ' ' . $userdata->last_name;
					}
					
				} else if (!empty($userdata->first_name) && empty($userdata->last_name)) {
					return $userdata->first_name;
				} else if (empty($userdata->first_name) && !empty($userdata->last_name)) {
					return $userdata->last_name;
				} else {
					return esc_html__('No Name', 'workreap');
				}
			}
			
		} else{
			if (!empty($userdata->first_name) && !empty($userdata->last_name)) {
				if(!empty($shortname_option) && $shortname_option === 'enable' ){
					$last_name		= substr($userdata->last_name,0,1);
					$second_name	= !empty($last_name) ? ' '.ucfirst($last_name) : '';
					return esc_html( $userdata->first_name.$second_name );
				} else {
					return $userdata->first_name . ' ' . $userdata->last_name;
				}
            } else if (!empty($userdata->first_name) && empty($userdata->last_name)) {
                return $userdata->first_name;
            } else if (empty($userdata->first_name) && !empty($userdata->last_name)) {
                return $userdata->last_name;
            } else {
                return esc_html__('No Name', 'workreap');
            }
		}
	}
}

/**
 * Report reasons
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_report_reasons' ) ) {
	function workreap_get_report_reasons(){
		$list	= array(
			'fake' 		=> esc_html__('This is the fake', 'workreap'),
			'bahavior' 	=> esc_html__('Their behavior is inappropriate or abusive', 'workreap'),
			'Other' 	=> esc_html__('Other', 'workreap'),
		);
		
		$list	= apply_filters('workreap_filter_reasons',$list);
		return $list;
	}
}

/**
 * Get employer avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_get_employer_avatar' ) ) {
	function workreap_get_employer_avatar( $sizes = array(), $user_identity = '' ) {
		extract( shortcode_atts( array(
			"width" => '100',
			"height" => '100',
		), $sizes ) );

		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$default_avatar = fw_get_db_settings_option( 'default_employer_avatar', $default_value = null );
		}

		$thumb_id = get_post_thumbnail_id( $user_identity );
		
		if ( !empty( $thumb_id ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id, array( $width, $height ), true );
			if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}
		} else {
			if ( !empty( $default_avatar['attachment_id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_avatar['attachment_id'], array( $width, $height ), true );

				if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_avatar['attachment_id'], "full", true );
					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{
						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}
				}
			} else {
				return false;
			}
		}
	}
}

/**
 * Add http from URL
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_add_http')) {

    function workreap_add_http($url) {
        $protolcol = is_ssl() ? "https" : "http";
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = $protolcol . ':' . $url;
        }
        return $url;
    }

}

/**
 * Get page id by slug
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_page_by_slug')) {

    function workreap_get_page_by_slug($slug = '', $post_type = 'post', $return = 'id') {
        $args = array(
            'name' 				=> $slug,
            'post_type' 		=> $post_type,
            'post_status' 		=> 'publish',
            'posts_per_page' 	=> 1
        );

        $post_data = get_posts($args);

        if (!empty($post_data)) {
            return (int) $post_data[0]->ID;
        }

        return false;
    }

}

/**
 * Add http from URL
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_matched_cart_items')) {

    function workreap_matched_cart_items($product_id) {
        // Initialise the count
        $count = 0;

        if (!WC()->cart->is_empty()) {
            foreach (WC()->cart->get_cart() as $cart_item):
                $items_id = $cart_item['product_id'];

                // for a unique product ID (integer or string value)
                if ($product_id == $items_id) {
                    $count++; // incrementing the counted items
                }
            endforeach;
            // returning counted items 
            return $count;
        }

        return $count;
    }

}

/**
 * Get the terms
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_taxonomy_options')) {

    function workreap_get_taxonomy_options($current = '', $taxonomyName = '', $parent = '') {
		
		if( taxonomy_exists($taxonomyName) ){
			//This gets top layer terms only.  This is done by setting parent to 0.  
			$parent_terms = get_terms($taxonomyName, array('parent' => 0, 'orderby' => 'slug', 'hide_empty' => false));


			$options = '';
			if (!empty($parent_terms)) {
				foreach ($parent_terms as $pterm) {
					$selected = '';
					if (!empty($current) && is_array($current) && in_array($pterm->slug, $current)) {
						$selected = 'selected';
					} else if (!empty($current) && !is_array($current) && $pterm->slug == $current) {
						$selected = 'selected';
					}

					$options .= '<option ' . $selected . ' value="' . $pterm->slug . '">' . $pterm->name . '</option>';
				}
			}

			echo do_shortcode($options);
		}else{
			echo '';
		}
    }

}

/**
 * Get taxonomy array
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_taxonomy_array')) {

    function workreap_get_taxonomy_array($taxonomyName = '',$parent='') {
		
		if( taxonomy_exists($taxonomyName) ){
			if(!empty( $parent )){
				return get_terms($taxonomyName, array('parent' => $parent, 'orderby' => 'slug', 'hide_empty' => false));
			} else{
				return get_terms($taxonomyName, array('orderby' => 'slug', 'hide_empty' => false));
			}
			
		} else{
			return array();
		}
        
    }

}

/**
 * Get the categories
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_categories')) {

    function workreap_get_categories($current = '', $type = '') {
        //This gets top layer terms only.  This is done by setting parent to 0.  

        $args = array('posts_per_page' => '-1',
            'post_type' 			=> $type,
            'post_status' 			=> 'publish',
            'suppress_filters' 		=> false
        );

        $options = '';
        $cust_query = get_posts($args);

        if (!empty($cust_query)) {
            $counter = 0;
            foreach ($cust_query as $key => $dir) {
                $selected = '';
                if (intval($dir->ID) === intval($current)) {
                    $selected = 'selected';
                }

                $options .= '<option ' . $selected . ' value="' . $dir->ID . '">' . esc_html( get_the_title($dir->ID) ) . '</option>';
            }
        }

        echo do_shortcode($options);
    }

}

/**
 * Prepare Business Hours Settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_prepare_business_hours_settings')) {

    function workreap_prepare_business_hours_settings() {
        return array(
            'monday' 	=> esc_html__('Monday', 'workreap'),
            'tuesday' 	=> esc_html__('Tuesday', 'workreap'),
            'wednesday' => esc_html__('Wednesday', 'workreap'),
            'thursday' 	=> esc_html__('Thursday', 'workreap'),
            'friday' 	=> esc_html__('Friday', 'workreap'),
            'saturday' 	=> esc_html__('Saturday', 'workreap'),
            'sunday' 	=> esc_html__('Sunday', 'workreap')
        );
    }

}

/**
 * Get Week Array
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_week_array')) {

    function workreap_get_week_array() {
        return array(
            'mon' => esc_html__('Monday', 'workreap'),
            'tue' => esc_html__('Tuesday', 'workreap'),
            'wed' => esc_html__('Wednesday', 'workreap'),
            'thu' => esc_html__('Thursday', 'workreap'),
            'fri' => esc_html__('Friday', 'workreap'),
            'sat' => esc_html__('Saturday', 'workreap'),
            'sun' => esc_html__('Sunday', 'workreap'),
        );
    }

}

/**
 * Time formate
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_date_24midnight')) {

    function workreap_date_24midnight($format, $ts) {
        if (date("Hi", $ts) == "0000") {
            $replace = array(
                "H" => "24",
                "G" => "24",
                "i" => "00",
            );

            return date(
                    str_replace(
                            array_keys($replace), $replace, $format
                    ), $ts - 60 // take a full minute off, not just 1 second
            );
        } else {
            return date($format, $ts);
        }
    }

}

/**
 * Get distance between two points
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_GetDistanceBetweenPoints')) {
	function workreap_GetDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Km') {
		$unit	= workreap_get_distance_scale();
		
		$theta = $longitude1 - $longitude2;
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515; switch($unit) {
		  case 'Mi': break;
		  case 'Km' : $distance = $distance * 1.60934;
		}
		return (round($distance,2)).'&nbsp;'. strtolower( $unit );
	}
}

/**
 * Get distance between two points
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_distance_scale')) {
	function workreap_get_distance_scale() {
		if (function_exists('fw_get_db_settings_option')) {
			$dir_distance_type = fw_get_db_settings_option('dir_distance_type');
		} else {
			$dir_distance_type = 'Km';
		}
		
		$unit = !empty( $dir_distance_type ) && $dir_distance_type === 'mi' ? 'Mi' : 'Km';
		
		return $unit;
	}
}

/**
 * Get woocommmerce currency settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_current_currency' ) ) {
	function workreap_get_current_currency() {
		$currency	= array();
		if (class_exists('WooCommerce')) {
			$currency['code']	= get_woocommerce_currency();
			$currency['symbol']	= get_woocommerce_currency_symbol();
		} else{
			$currency['code']	= 'USD';
			$currency['symbol']	= '$';
		}
		
		return $currency;
	}
}

/**
 * Get calendar date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_calendar_format' ) ) {
	function workreap_get_calendar_format() {
		if (function_exists('fw_get_db_settings_option')) {
			$calendar_locale    = fw_get_db_settings_option('calendar_locale');
			$calendar_format	= !empty( $calendar_format ) ?  $calendar_format : 'Y-m-d';
		}else{
			$calendar_format	= 'Y-m-d';
		}
		
		return $calendar_format;
	}
}

/**
 * Get social setting value
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_social_settings_value' ) ) {
    function workreap_get_social_settings_value($parent,$key,$sub_key,$user_identity) {
        $api_settings   =  get_user_meta($user_identity, 'sp_social_api', true);
        return !empty( $api_settings[$parent][$key][$sub_key] ) ? $api_settings[$parent][$key][$sub_key] : '';
    }
}

/**
 * Get login page uri
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_login_registration_page_uri' ) ) {
    function workreap_get_login_registration_page_uri() {
		$login_register = '';
		$login_reg_link = '#';
		if (function_exists('fw_get_db_settings_option')) {
			$login_register = fw_get_db_settings_option('enable_login_register');
		}

		if (!empty($login_register['enable']['login_reg_page'])) {
			$login_reg_link = $login_register['enable']['login_reg_page'];
		}
		
		if( !empty( $login_reg_link[0] ) ){
			return get_permalink((int) $login_reg_link[0]);
		} else{
			return '#';
		}	
	}
}

/**
 * Get profile status list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_status_list' ) ) {
	function workreap_get_status_list(){
		$list	= array(
			'offline' => array(
				'classes' => 'offline',
				'title'   => esc_html__('Offline','workreap')
			),
			'online' => array(
				'classes' => 'online',
				'title'   => esc_html__('Online','workreap')
			),
			'busy' => array(
				'classes' => 'busy',
				'title'   => esc_html__('Busy','workreap')
			),
			'away' => array(
				'classes' => 'away',
				'title'   => esc_html__('Away','workreap')
			),
			'sphide' => array(
				'classes' => 'sphide',
				'title'   => esc_html__('Hide status','workreap')
			)
			
		);
		
		return $list;
	}
}

/**
 * Get profile status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_profile_status' ) ) {
	function workreap_get_profile_status($view='one',$type='echo',$user_id){
		if( empty($user_id)){return;}
		
		ob_start();
		
		$profile_status	= get_user_meta($user_id,'profile_status',true);
		$statuses		= workreap_get_status_list();

		if( isset( $view ) && $view === 'one'){
			//print code
		} else{
			if( !empty( $profile_status ) && $profile_status != 'wthide' && !is_array( $profile_status )){
			?>
				<div class="displaystatus-wrap sp-<?php echo esc_attr($statuses[$profile_status]['classes']);?>">
					<span><?php esc_html_e('Status','workreap');?>:</span>
					<span><i class="fa fa-circle"></i>&nbsp;<?php echo esc_attr($statuses[$profile_status]['title']);?></span>
				</div>
			<?php
			} 
		}
		
		if( isset( $type ) && $type === 'return'){
			return ob_get_clean();
		} else{
			echo ob_get_clean();
		}
	}
}

/**
 * Get term by slug
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_term_by_type')) {

    function workreap_get_term_by_type($from = 'slug', $value = "", $taxonomy = 'sub_category', $return = 'id') {

        $term = get_term_by($from, $value, $taxonomy);

        if (!empty($term)) {
            if ($from === 'slug' && $return === 'id') {
                return $term->term_id;
            } elseif ($from === 'id' && $return === 'slug') {
                return $term->slug;
            } elseif ($from === 'name' && $return === 'id') {
                return $term->term_id;
            } elseif ($from === 'id' && $return === 'name') {
                return $term->name;
            } elseif ($from === 'name' && $return === 'slug') {
                return $term->slug;
            } elseif ($from === 'slug' && $return === 'name') {
                return $term->name;
            } else {
                return $term->term_id;
            }
        }

        return false;
    }
}

/**
 * Get total post by user id
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_total_posts_by_user')) {

    function workreap_get_total_posts_by_user($user_id = '',$type='sp_ads') {
        if (empty($user_id)) {
            return 0;
        }

        $args = array(
			'posts_per_page'	=> '-1',
            'post_type' 		=> $type,
            'post_status' 		=> 'publish',
            'author' 			=> $user_id,
            'suppress_filters' 	=> false
        );
        $query = new WP_Query($args);
        return $query->post_count;
    }
}

/**
 * Get search page uri
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_search_page_uri' ) ) {
    function workreap_get_search_page_uri( $type = '' ) {

        $tpl_freelancer = array();
        $tpl_employer   = array();
        $tpl_project    = array();
		$tpl_services   = array();
        $tpl_dashboard  = array();

        if (function_exists('fw_get_db_settings_option')) {
            $tpl_freelancer = fw_get_db_settings_option('search_freelancer_tpl');
            $tpl_employer   = fw_get_db_settings_option('search_employer_tpl');
            $tpl_project    = fw_get_db_settings_option('search_job_tpl');
            $tpl_dashboard  = fw_get_db_settings_option('dashboard_tpl');
			$tpl_services   = fw_get_db_settings_option('search_services_tpl');
        }
        
        $search_page = '';
        if ( !empty( $type ) && $type === 'freelancer' ) {
            $search_page = !empty($tpl_freelancer) ? get_permalink((int) $tpl_freelancer[0]) : '';
        } elseif ( !empty( $type ) && $type === 'employer' ) {
            $search_page = !empty( $tpl_employer ) ? get_permalink((int) $tpl_employer[0]) : '';
        } elseif ( !empty( $type ) && $type === 'dashboard' ) {
            $search_page = !empty( $tpl_dashboard[0] ) ? get_permalink((int) $tpl_dashboard[0]) : '';           
        } elseif ( !empty( $type ) && $type === 'services' ) {
            $search_page = !empty( $tpl_services[0] ) ? get_permalink((int) $tpl_services[0]) : '';           
        } elseif ( !empty( $type ) && $type === 'jobs' ) {
            $search_page = !empty( $tpl_project[0] ) ? get_permalink((int) $tpl_project[0]) : '';           
        } elseif ( !empty( $type ) && $type === 'job' ) {
            $search_page = !empty( $tpl_project[0] ) ? get_permalink((int) $tpl_project[0]) : '';           
        } else {
            $search_page = !empty( $tpl_freelancer ) ? get_permalink((int) $tpl_freelancer[0]) : '';
        }
        
        return $search_page;
    }
}

/**
 * Payouts
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_payouts_lists' ) ) {
	function workreap_get_payouts_lists(){
		if (function_exists('fw_get_db_settings_option')) {
            $payout_bank 	  = fw_get_db_settings_option('payout_bank');
            $payout_paypal    = fw_get_db_settings_option('payout_paypal');
			$payout_payoneer    = fw_get_db_settings_option('payout_payoneer');
			$payout_choices   = fw_get_db_settings_option('payouts_methods');
			$bank_transfer_fields   = fw_get_db_settings_option('bank_transfer_fields');
        }
		
		$payout_bank	= !empty( $payout_bank['url'] ) ? $payout_bank['url'] : get_template_directory_uri().'/images/payouts/bank.png';
		$payout_paypal	= !empty( $payout_paypal['url'] ) ? $payout_paypal['url'] : get_template_directory_uri().'/images/payouts/paypal.png';
		$payout_payoneer	= !empty( $payout_payoneer['url'] ) ? $payout_payoneer['url'] : get_template_directory_uri().'/images/payouts/payoneer.jpg';
			
		$list	= array(
					'paypal' => array(
									'id'		=> 'paypal',
									'title'		=> esc_html__('Paypal', 'workreap'),
									'img_url'	=> esc_url($payout_paypal),
									'status'	=> 'enable',
									'desc'		=> wp_kses( __( 'You need to add your PayPal ID below in the text field. For more about&nbsp;<a target="_blank" href="https://www.paypal.com/"> PayPal </a>&nbsp;|&nbsp;<a target="_blank" href="https://www.paypal.com/signup/">Create an account</a>', 'workreap' ),array(
																'a' => array(
																	'href' => array(),
																	'target' => array(),
																	'title' => array()
																),
																'br' => array(),
																'em' => array(),
																'strong' => array(),
															)),
									'fields'	=> array(
										'paypal_email' => array(
											'type'			=> 'text',
											'classes'		=> '',
											'required'		=> true,
											'show_this'		=> true,
											'title'			=> esc_html__('PayPal email address','workreap'),
											'placeholder'	=> esc_html__('Add PayPal Email Address','workreap'),
											'message'	=> esc_html__('PayPal Email Address is required','workreap'),
										)
									)
								),
					'payoneer' => array(
									'id'		=> 'payoneer',
									'title'		=> esc_html__('Payoneer', 'workreap'),
									'img_url'	=> esc_url($payout_payoneer),
									'status'	=> 'enable',
									'desc'		=> wp_kses( __( 'You need to add your payoneer email ID below in the text field.&nbsp;<a target="_blank" href="https://www.payoneer.com/"> Payoneer</a>&nbsp;|&nbsp;<a target="_blank" href="https://www.payoneer.com/accounts/">Create an account</a>', 'workreap' ),array(
																'a' => array(
																	'href' => array(),
																	'target' => array(),
																	'title' => array()
																),
																'br' => array(),
																'em' => array(),
																'strong' => array(),
															)),
									'fields'	=> array(
										'payoneer_email' => array(
											'type'			=> 'text',
											'show_this'		=> true,
											'classes'		=> '',
											'required'		=> true,
											'title'			=> esc_html__('Payoneer email address','workreap'),
											'placeholder'	=> esc_html__('Add Payoneer email address','workreap'),
											'message'	=> esc_html__('Payoneer email address is required','workreap'),
										)
									)
								),
					'bacs' => array(
									'id'		=> 'bacs',
									'title'		=> esc_html__('Direct Bank Transfer (BACS)', 'workreap'),
									'img_url'	=> esc_url($payout_bank),
									'status'	=> 'enable',
									'desc'		=> wp_kses( __( 'Please add all required settings for the bank transfer.', 'workreap' ),array(
																'a' => array(
																	'href' => array(),
																	'target' => array(),
																	'title' => array()
																),
																'br' => array(),
																'em' => array(),
																'strong' => array(),
															)),
									'fields'	=> array(
										'bank_account_name' => array(
											'type'			=> 'text',
											'classes'		=> '',
											'required'		=> true,
											'show_this'		=> true,
											'title'	=> esc_html__('Bank Account Name','workreap'),
											'placeholder'	=> esc_html__('Bank Account Name','workreap'),
											'message'		=> esc_html__('Bank Account Name is required','workreap'),
										),
										'bank_account_number' => array(
											'type'			=> 'text',
											'classes'		=> '',
											'required'		=> true,
											'show_this'		=> true,
											'title'	=> esc_html__('Bank Account Number','workreap'),
											'placeholder'	=> esc_html__('Bank Account Number','workreap'),
											'message'		=> esc_html__('Bank Account Number is required','workreap'),
										),
										'bank_name' => array(
											'type'			=> 'text',
											'classes'		=> '',
											'required'		=> true,
											'show_this'		=> true,
											'title'	=> esc_html__('Bank Name','workreap'),
											'placeholder'	=> esc_html__('Bank Name','workreap'),
											'message'		=> esc_html__('Bank Name is required','workreap'),
										),
										'bank_routing_number' => array(
											'type'			=> 'text',
											'classes'		=> '',
											'required'		=> false,
											'show_this'		=> true,
											'title'	=> esc_html__('Bank Routing Number','workreap'),
											'placeholder'	=> esc_html__('Bank Routing Number','workreap'),
											'message'		=> esc_html__('Bank Routing Number is required','workreap'),
										),
										'bank_iban' => array(
											'type'			=> 'text',
											'classes'		=> '',
											'required'		=> false,
											'show_this'		=> true,
											'title'	=> esc_html__('Bank IBAN','workreap'),
											'placeholder'	=> esc_html__('Bank IBAN','workreap'),
											'message'		=> esc_html__('Bank IBAN is required','workreap'),
										),
										'bank_bic_swift' => array(
											'type'			=> 'text',
											'classes'		=> '',
											'required'		=> false,
											'show_this'		=> true,
											'title'	=> esc_html__('Bank BIC/SWIFT','workreap'),
											'placeholder'	=> esc_html__('Bank BIC/SWIFT','workreap'),
											'message'		=> esc_html__('Bank BIC/SWIFT is required','workreap'),
										)
									)
								),
			);
		
		$payout_choices	= !empty($payout_choices) ?  $payout_choices : array();
		if( !empty( $payout_choices )){
			$list_array	=  array();
			foreach($payout_choices as $key => $payout){
				$list_array[$payout]	= $list[$payout];
			}
			
			$list	= $list_array;
		}
		
		//remove some fields
		if(!empty($list['bacs']) && !empty($bank_transfer_fields)){
			foreach($bank_transfer_fields as $key => $field){
				unset($list['bacs']['fields'][$field]);
			}
		}
		
		
		$list	= apply_filters('workreap_filter_payouts_lists',$list);
		return $list;
	}
}

/**
 * Get Tag Line
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if(!function_exists('workreap_get_tagline') ) {
	function workreap_get_tagline($post_id ='') {
		if (function_exists('fw_get_db_post_option')) {
			$tag_line	= fw_get_db_post_option($post_id, 'tag_line');
			$tag_line	= !empty( $tag_line ) ? esc_html( stripslashes($tag_line) ) : '';
		} else {
			$tag_line	= "";
		}
		
		return $tag_line;
	} 
}

/**
 * Get Location
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if(!function_exists('workreap_get_location') ) {
	function workreap_get_location($post_id ='') {
		$args	= array();
		$terms 				= wp_get_post_terms( $post_id, 'locations', $args );
		$countries			= !empty( $terms[0]->term_id ) ? intval( $terms[0]->term_id ) : 0;
		$locations_name		= !empty( $terms[0]->name ) ?  $terms[0]->name  : '';
		if(!empty($locations_name) ) {
			$item['_country']	= $locations_name;
		} else {
			$item['_country']	= '';
		}
		
		$icon          	= fw_get_db_term_option($countries,'locations', 'image');
		$item['flag'] 	= !empty($icon['url']) ? workreap_add_http($icon['url']) : '';
		return $item;
	} 
}


/**
 * Get signup uri
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_get_signup_page_url' ) ) {    
    function workreap_get_signup_page_url($key = 'step', $slug = '1') {
        //Get authentication page settings
        $login_register     = '';   
        $login_reg_link     = '';
        $signup_page_slug   = '';

        if (function_exists('fw_get_db_settings_option')) {
            $login_register = fw_get_db_settings_option('enable_login_register');            
        } 

        if (!empty($login_register['enable']['login_reg_page'])) {
            $login_reg_link = $login_register['enable']['login_reg_page'];
        }

        if(!empty( $login_reg_link[0] )){
            $signup_page_slug = esc_url(get_permalink((int) $login_reg_link[0]));            
        }

        if( !empty( $signup_page_slug ) ){
            $signup_page_slug = add_query_arg( $key, $slug, $signup_page_slug );    
            return $signup_page_slug;
        }

        return '';
    }
}

/**
 * Get sum earning for payouts
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_sum_earning_freelancer_payouts' ) ) {
    function workreap_sum_earning_freelancer_payouts( $status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_earnings";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT user_id, sum(".$colum_name.") as total_amount FROM ".$table_name." WHERE status = %s GROUP BY user_id",$status);
				$total_earning	= $wpdb->get_results( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * List Months
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_list_month' ) ) {
    function workreap_list_month( ) {
		$month_names = array(
						'01'	=> esc_html__("January",'workreap'),
						'02'	=> esc_html__("February",'workreap'),
						'03' 	=> esc_html__("March",'workreap'),
						'04'	=> esc_html__("April",'workreap'),
						'05'	=> esc_html__("May",'workreap'),
						'06'	=> esc_html__("June",'workreap'),
						'07'	=> esc_html__("July",'workreap'),
						'08'	=> esc_html__("August",'workreap'),
						'09'	=> esc_html__("September",'workreap'),
						'10'	=> esc_html__("October",'workreap'),
						'11'	=> esc_html__("November",'workreap'),
						'12'	=> esc_html__("December",'workreap')
					);
		
		return $month_names;
		
	}
}

/**
 * Get Earnigs Status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_earning_status_list' ) ) {
	function workreap_get_earning_status_list(){
		$list	= array(
			'hired' 	=> esc_html__('Hired','workreap'),
			'completed' => esc_html__('Completed','workreap'),
			'cancelled' => esc_html__('Cancelled','workreap'),
			'processed' => esc_html__('Processed','workreap')
			
		);
		
		return $list;
	}
}

/**
 * get total service companies
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_services_count' ) ) {
	function workreap_get_services_count($post_type, $status = array(),$post_id='',$return='count',$count='-1') {
		global $current_user;
		$args = array(
			'post_type' 		=> $post_type,
			'posts_per_page'   	=> $count,
			'post_status'   	=> $status,
			'meta_query' 		=> array(
									array(
										'key'     => '_service_id',
										'value'   => $post_id,
										'compare' => '=',
									),
								),
		);

		$services 	= get_posts($args);
		
		if($return === 'count'){
			$services	= !empty( $services ) ? count($services) : 0;
		}

		return $services;
	}
}

/**
 * get total service companies
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_post_count_by_meta' ) ) {
	function workreap_get_post_count_by_meta($post_type, $status = array(),$meta_array=array(),$return='count',$count='-1') {
		$args = array(
			'post_type' 		=> $post_type,
			'posts_per_page'   	=> $count,
			'post_status'   	=> $status
		);
		if(!empty($meta_array)){
			foreach($meta_array as $meta){
				$args['meta_query'][]	= $meta;
			}
			
		}

		$post_data 	= get_posts($args);
		
		if($return === 'count'){
			$post_data	= !empty( $post_data ) ? count($post_data) : 0;
		}

		return $post_data;
	}
}
/**
 * Return Service Cart attributes 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_service_cart_attributes' ) ) {
	function worktic_service_cart_attributes(){
		$list = array(
		    'service_id' 		=> esc_html__('Service title', 'workreap'),
			'delivery_time' 	=> esc_html__('Delivery time', 'workreap'),
			'delivery_date' 	=> esc_html__('Delivery date', 'workreap')
		);

		$list = apply_filters('worktic_set_service_cart_attributes', $list);			
		return $list;
	}
}

/**
 * Display form field with list of authors.
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_post_author_meta_box_services')) {
	function workreap_post_author_meta_box_services( $post ) {
		global $user_ID;
		?>
		<label class="screen-reader-text" for="post_author_override"><?php esc_html_e( 'Author', 'workreap' ); ?></label>
		<?php
		$roles	= array('freelancers');
		wp_dropdown_users( array(
			'role__in' 	=> $roles,
			'name' 		=> 'post_author_override',
			'selected' 	=> empty( $post->ID ) ? $user_ID : $post->post_author,
			'show' 		=> 'display_name_with_login',
			'include_selected' => true,
			
		) );
	}
}

/**
 * Display form field with list of authors in order service.
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_post_author_meta_box_order_services')) {
	function workreap_post_author_meta_box_order_services( $post ) {
		global $user_ID;
		?>
		<label class="screen-reader-text" for="post_author_override"><?php esc_html_e( 'Author', 'workreap' ); ?></label>
		<?php
		$roles	= array('employers');
		wp_dropdown_users( array(
			'role__in' 	=> $roles,
			'name' 		=> 'post_author_override',
			'selected' 	=> empty( $post->ID ) ? $user_ID : $post->post_author,
			'show' 		=> 'display_name_with_login',
			'include_selected' => true,
			
		) );
	}
}

/**
 * Save service ratings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if(!function_exists('workreap_save_service_rating')){
    function workreap_save_service_rating($post_id = '', $rating_options = '' ,$action = ''){
		global $wpdb;
		$current_date 				= current_time('mysql');
		$rating_titles 				= workreap_project_ratings('services_ratings');
		$rating_evaluation_count 	= !empty($rating_titles) ? count($rating_titles) : 0;
		$review_extra_meta 			= array();
		$rating 					= 0;
		
		$service_id				= get_post_meta( $post_id , '_service_id',true );
		$service_id				= !empty( $service_id ) ? intval( $service_id ) : '';

		if( !empty( $post_id ) && !empty( $rating_options ) && !empty( $rating_titles ) && !empty( $service_id ) ){
			$serviceTotalRating	= get_post_meta( $service_id , '_service_total_rating',true );
			$serviceFeedbacks	= get_post_meta( $service_id , '_service_feedbacks',true );
			$hiredRating		= get_post_meta( $post_id , '_hired_service_rating',true );
			
			$serviceTotalRating	= !empty( $serviceTotalRating ) ? $serviceTotalRating : 0;
			$serviceFeedbacks	= !empty( $serviceFeedbacks ) ? intval( $serviceFeedbacks ) : 0;
			$hiredRating		= !empty( $hiredRating ) ?  $hiredRating  : 0;

			foreach ( $rating_titles as $slug => $label ) {
				if (isset($rating_options[$slug])) {
					fw_set_db_post_option($post_id, $slug, $rating_options[$slug]);
					update_post_meta($post_id, $slug, $rating_options[$slug]);
					$rating += (float) $rating_options[$slug];
				}
			}
			
			$hired_service_rating = $rating / $rating_evaluation_count;
			$hired_service_rating = number_format((float) $hired_service_rating, 2, '.', '');
			
			if( !empty( $serviceTotalRating ) && !empty( $serviceFeedbacks ) ) {
				if( !empty( $action ) && $action === 'add' ) {
					$newServiceTotal	= $serviceTotalRating + $hired_service_rating;
					$serviceFeedbacks	= $serviceFeedbacks +1;
				} else {
					$newServiceTotal	= $serviceTotalRating * $serviceFeedbacks;
					$newServiceTotal	= $newServiceTotal - $hiredRating;
					$newServiceTotal	= ( $newServiceTotal + $hired_service_rating ) / $serviceFeedbacks ;
				}
								
			} else {
				$newServiceTotal	= $hired_service_rating;
				$serviceFeedbacks	= 1;
			}
			
			
			//user rating
			$freelancer_id			= get_post_meta( $post_id, '_service_author', true);
			$freelance_profile_id	= workreap_get_linked_profile_id( $freelancer_id );
			$user_db_reviews	= get_post_meta($freelance_profile_id, 'review_data', true);
			$user_db_reviews	= !empty( $user_db_reviews ) ? $user_db_reviews : array();
			
			$user_rating			= 0;
			
			if( !empty( $user_db_reviews['wt_rating_count'] ) ){
				$rating			= !empty( $user_db_reviews['wt_rating_count'] ) ? ( $user_db_reviews['wt_rating_count'] + $hired_service_rating ) / ( $user_db_reviews['wt_total_rating'] + 1 ) : $user_rating;
				$user_rating 	= number_format((float) $rating, 2, '.', '');

				$user_db_reviews['wt_average_rating'] 			= $user_rating;
				$user_db_reviews['wt_total_rating'] 			= !empty( $user_db_reviews['wt_total_rating'] ) ? $user_db_reviews['wt_total_rating'] + 1 : 1;
				$user_db_reviews['wt_total_percentage'] 		= $user_rating * 20;
				$user_db_reviews['wt_rating_count'] 			= !empty( $user_db_reviews['wt_rating_count'] ) ? $user_db_reviews['wt_rating_count'] + $hired_service_rating : $hired_service_rating;

			} else{ //Migration from release 1.1.9 to 1.2.0
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

				$rating			= ( $db_rating_query['db_rating'] + $hired_service_rating ) / ( $db_rating_query['db_total'] + 1 );
				$user_rating 	= number_format((float) $rating, 2, '.', '');

				$user_db_reviews['wt_average_rating'] 			= $user_rating;
				$user_db_reviews['wt_total_rating'] 			= !empty( $db_rating_query['db_total'] ) ? $db_rating_query['db_total'] + 1 : 1;
				$user_db_reviews['wt_total_percentage'] 		= $user_rating * 20;
				$user_db_reviews['wt_rating_count'] 			= $db_rating_query['db_rating'] + $hired_service_rating;
			}

            update_post_meta($freelance_profile_id, 'review_data', $user_db_reviews);
			update_post_meta($freelance_profile_id, 'rating_filter', $user_rating);

			//end user rating
			update_post_meta($service_id, '_service_total_rating', $newServiceTotal);
			update_post_meta($service_id, '_service_feedbacks', $serviceFeedbacks);
						
			$review_meta = array(
				'_hired_service_rating' 	=> $hired_service_rating,
				'_review_date' 				=> date('Y-m-d H:i:s', strtotime($current_date)),
			);
			
			//Update post meta
			foreach ($review_meta as $key => $value) {
				update_post_meta($post_id, $key, $value);
			}
		} 
    }
}

/**
 * Update Post staus by ID
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if(!function_exists('workreap_save_service_status')){
    function workreap_save_service_status($post_id = '', $status = '' ){
		if( !empty( $post_id ) && !empty( $status ) ) {
			$update_post['ID']			= $post_id;
			$update_post['post_status']	= $status;

			$post_id	= wp_update_post( $update_post );
			if ( is_wp_error( $post_id ) ) {
				 return false;
			}
			else {
				 return true;
			}
		} else {
			return false;
		}
	}
}

/**
 * Display form field with list of authors.
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_post_author_meta_box_services')) {
	function workreap_post_author_meta_box_services( $post ) {
		global $user_ID;
		?>
		<label class="screen-reader-text" for="post_author_override"><?php esc_html_e( 'Author', 'workreap' ); ?></label>
		<?php
		$roles	= array('freelancers');
		wp_dropdown_users( array(
			'role__in' 	=> $roles,
			'name' 		=> 'post_author_override',
			'selected' 	=> empty( $post->ID ) ? $user_ID : $post->post_author,
			'show' 		=> 'display_name_with_login',
			'include_selected' => true,
			
		) );
	}
}

/**
 * Display form field with list of authors in order service.
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_post_author_meta_box_order_services')) {
	function workreap_post_author_meta_box_order_services( $post ) {
		global $user_ID;
		?>
		<label class="screen-reader-text" for="post_author_override"><?php esc_html_e( 'Author', 'workreap' ); ?></label>
		<?php
		$roles	= array('employers');
		wp_dropdown_users( array(
			'role__in' 	=> $roles,
			'name' 		=> 'post_author_override',
			'selected' 	=> empty( $post->ID ) ? $user_ID : $post->post_author,
			'show' 		=> 'display_name_with_login',
			'include_selected' => true,
			
		) );
	}
}

/**
 * @return Application access
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_return_system_access')) {

    function workreap_return_system_access( ) {
		if (function_exists('fw_get_db_settings_option')) {
        	$application_access = fw_get_db_settings_option('application_access');
		}
		
		$application_access	= !empty( $application_access ) ? $application_access : '';
		
		if( !empty( $application_access ) ) {
			if($application_access === 'service_base') {
				return 'service';
			} else if( $application_access === 'job_base' ) {
				return 'job';
			} else {
				return $application_access;
			}
		} else {
			return false;
		}
    }
}

/**
 * get latitude and longitude for search
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'workreap_get_location_lat_long' ) ) {
	function workreap_get_location_lat_long() {
		if (function_exists('fw_get_db_settings_option')) {
			$location_search = fw_get_db_settings_option('dir_location');
			$dir_longitude = fw_get_db_settings_option('dir_longitude');
			$dir_latitude  = fw_get_db_settings_option('dir_latitude');
			$google_key = fw_get_db_settings_option('google_key');
		} else{
			 $dir_longitude = '-0.1262362';
			 $dir_latitude 	= '51.5001524';
			 $google_key = '';
			 $location_search = 'disable';
		}

		$current_latitude	= $dir_latitude;
		$current_longitude	= $dir_longitude;

		if( !empty( $_GET['lat'] ) && !empty( $_GET['long'] ) ){
			$current_latitude	= esc_attr( $_GET['lat'] );
			$current_longitude	= esc_attr( $_GET['long'] );
		} else{
			if(!empty($location_search) && $location_search == 'enable' && !empty($address)){
				$args = array(
					'timeout'     => 15,
					'headers' => array('Accept-Encoding' => ''),
					'sslverify' => false
				);
				
				$address	 = !empty($_GET['geo']) ?  $_GET['geo'] : '';
				$prepAddr	= str_replace(' ','+',$address);
				
				$url	    = 'https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key='.$google_key;
				$response   = wp_remote_get( $url, $args );
				$geocode	= wp_remote_retrieve_body($response);

				$output	  = json_decode($geocode);

				if( isset( $output->results ) && !empty( $output->results ) ) {
					$Latitude	 = $output->results[0]->geometry->location->lat;
					$Longitude   = $output->results[0]->geometry->location->lng;
				}
				
				if( !empty( $Latitude ) && !empty( $Longitude ) ){
					$current_latitude	= $Latitude;
					$current_longitude	= $Longitude;
				} else{
					$current_latitude	= $dir_latitude;
					$current_longitude	= $dir_longitude;
				}
			}
		}
		
		$location	= array();
		
		$location['lat']	= $current_latitude;
		$location['long']	= $current_longitude;
		
		return $location;
	}
}

/**
 * get Folder size
 *
 * @param json
 * @return string
 */
if ( ! function_exists( 'workreap_foldersize' ) ) {
	function workreap_foldersize( $folder_path ) {
		$total_size = 0;
		$files 		= scandir( $folder_path );
		$cleanPath 	= rtrim( $folder_path, '/' ) . '/';

		foreach( $files as $file ) {
			if ( '.' != $file && '..' != $file ) {
				$currentFile = $cleanPath . $file;
				if ( is_dir( $currentFile ) ) {
					$size = workreap_foldersize( $currentFile );
					$total_size += $size;
				} else {
					$size = filesize( $currentFile );
					$total_size += $size;
				}
			}   
		}

		return $total_size;
	}
}

/**
 * check video URL
 *
 * @param json
 * @return string
 */
if(!function_exists('workreap_check_video_url')) {
    function workreap_check_video_url($url){
		$return 	= false;
		$video_platform 	= array('youtube','vimeo','dailymotion','yahoo','bliptv','veoh','viddler');
		$video_platform		= apply_filters('workreap_filter_video_url',$video_platform);
		if (filter_var($url, FILTER_VALIDATE_URL)) {
			foreach ($video_platform as $val) {
				if (strpos($url, $val) !== FALSE) { 
					$return = true;
				}
			}
		}
		return $return;
	}
}


/**
 * Get post status title
 *
 * @param json
 * @return string
 */
if( !function_exists(  'workreap_get_status_title' ) ) {
	function workreap_get_status_title($status){
		$list	= array(
			'hired' 	=> esc_html__('Hired','workreap'),
			'completed' => esc_html__('Completed','workreap'),
			'cancelled' => esc_html__('Cancelled','workreap'),
			'processed' => esc_html__('Processed','workreap'),
			'publish' => esc_html__('Published','workreap'),
			'draft' => esc_html__('Drafted','workreap'),
			'pending' => esc_html__('Pending','workreap'),
		);
		
		return !empty($list[$status]) ? $list[$status] : $status;
	}
}

/**
 * Get withdraw by freelancer
 *
 * @throws error
 * @return 
 */
if ( ! function_exists( 'workreap_sum_freelancer_withdraw' ) ) {
    function workreap_sum_freelancer_withdraw( $status='publish',$user_id='' ) {
		global $current_user;
		$user_id	= !empty($user_id) ? $user_id : $current_user->ID;
		$args = array(
				'fields' 			=> 'ids',
				'post_status'		=> $status,
				'post_type'			=> 'withdraw',
				'author'			=>  $user_id,
				'posts_per_page' 	=> -1 
			);
		
		$posts = get_posts($args);
		$total_amount	= 0;
		
		if( !empty($posts) ){
			foreach($posts as $val){
				$price			= !empty($val) ? get_post_meta( $val, '_withdraw_amount', true ) : 0;
				$price			= !empty($price) ? $price : 0;
				$total_amount	= $price + $total_amount;
			}
		}
		
		return $total_amount;
	}
}

/**
 * Get sum earning by user id
 *
 * @throws error
 * @return 
 */
if ( ! function_exists( 'workreap_sum_user_earning' ) ) {
    function workreap_sum_user_earning( $status='',$colum_name='',$user_id='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_earnings";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT sum(".$colum_name.") as total_amount FROM ".$table_name." WHERE status = %s and user_id = %s GROUP BY user_id",$status,$user_id);
				$total_earning	= $wpdb->get_row( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Return Number Users
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_switch_user' ) ) {
	function workreap_switch_user($current_user_id){
		global $wpdb;
		$wp_user_object			= get_user_by('id', $current_user_id);
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
		if(empty($get_switch_id)){
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
		$user_id	= !empty($user->ID) ? $user->ID : '';
		return $user_id;
	}
}

/**
 * Billing fields
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_billing_fields' ) ) {
	function workreap_billing_fields($key='') {
		$list = array(
			'billing_first_name'    => esc_html__('First name is required', 'workreap'),
			'billing_last_name'    	=> esc_html__('Last name is required', 'workreap'),
			'billing_company'    	=> esc_html__('Company is required', 'workreap'),
			'billing_address_1'    	=> esc_html__('Address is required', 'workreap'),
			'billing_country'   	=> esc_html__('country is required', 'workreap'),
			'billing_city'    		=> esc_html__('City is required', 'workreap'),
			'billing_postcode'    	=> esc_html__('Postal code is required', 'workreap'),
			'billing_phone'    		=> esc_html__('Phone is required', 'workreap'),
			'billing_email'    		=> esc_html__('email is required', 'workreap'),
		);

		$reasons	= apply_filters('workreap_filters_billing_fields',$list);
		
		if( !empty( $key ) ){
			return !empty( $list[$key] ) ? $list[$key] : '';
		}
		
		return $reasons;
	}
	add_filter( 'workreap_billing_fields','workreap_billing_fields',10,1);
}


/**
 * Add terms in bulk
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_insert_skills' ) ) {
	function workreap_insert_skills() {
		$skills = array(
			array("val0"=>"Name here","val1"=> "slug here")
		);
		foreach ( $skills as $term_key => $term) {
			wp_insert_term(
				$term['val0'],
				'skills', 
				array(
					'description' => '',
					'slug'        => $term['val1'],
				)
			);
		}	
	}
}