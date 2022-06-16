<?php 
require WorkreapGlobalSettings::get_plugin_path() . 'libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @Import Users
 * @return{}
 */     

if ( !class_exists('SP_Import_User') ) {    
    class SP_Import_User {        
        function __construct(){
            // Constructor Code here..
   		}
		
		/*
		 * @import users
		 */
		public function workreap_import_user(){
		
			global $wpdb, $wpdb_data_table;
	
			// User data fields list used to differentiate with user meta
			$userdata_fields       = array(
				'ID', 
				'username', 
				'user_pass',
				'user_email', 
				'user_url', 
				'user_nicename',
				'display_name', 
				'user_registered', 
				'first_name',
				'last_name', 
				'nickname', 
				'description',
				'rich_editing', 
				'comment_shortcuts', 
				'admin_color',
				'use_ssl', 
				'show_admin_bar_front', 
				'show_admin_bar_admin',
				'role'
			);

			$wp_user_table		= $wpdb->prefix.'users';
			$wp_usermeta_table	= $wpdb->prefix.'usermeta';

			if ( isset( $_FILES['users_csv']['tmp_name'] ) ) {
				$file = $_FILES['users_csv']['tmp_name'];
				$name = !empty( $_FILES['users_csv']['name'] ) ? $_FILES['users_csv']['name'] : '';
				
				$filetype	= '';
				if( !empty( $name ) ){
					$filetype = pathinfo($name, PATHINFO_EXTENSION);
				}
				
				$import_type	= 'upload';
				
			} else{
				$file 			= WorkreapGlobalSettings::get_plugin_path().'/import-users/users.xlsx';
				$filetype		= 'xlsx';
				$import_type	= 'dummy';
			}
			
			try {
				//Load the excel(.xls/.xlsx) file
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
			}

			$worksheet = $spreadsheet->getActiveSheet();
			// Get the highest row and column numbers referenced in the worksheet
			$total_rows = $worksheet->getHighestRow(); // e.g. 10
			$highest_column = $worksheet->getHighestColumn(); // e.g 'F'
	
			$first = true;
			$rkey = 0;
			for($row =1; $row <= $total_rows; $row++) {
	
				// If the first line is empty, abort
				// If another line is empty, just skip it
				if ( empty( $row ) ) {
					if ( $first )
						break;
					else
						continue;
				}
	
				// If we are on the first line, the columns are the headers
				if ( $first ) {
					$line = $spreadsheet->getActiveSheet()
									->rangeToArray(
										'A' . $row . ':' . $highest_column . $row,     // The worksheet range that we want to retrieve
										NULL,        // Value that should be returned for empty cells
										TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
										FALSE,       // Should values be formatted (the equivalent of getFormattedValue() for each cell)
										FALSE        // Should the array be indexed by cell row and cell column
									);
					//$line = $sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, NULL, TRUE, FALSE);
					$headers 	= !empty( $line[0] ) ? $line[0] : array();
					$first 		= false;
					continue;
				} else{
					//$data = array_map("utf8_encode", $line); //Encoding other than english language
					$data = $spreadsheet->getActiveSheet()
									->rangeToArray(
										'A' . $row . ':' . $highest_column . $row,     // The worksheet range that we want to retrieve
										NULL,        // Value that should be returned for empty cells
										TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
										FALSE,       // Should values be formatted (the equivalent of getFormattedValue() for each cell)
										FALSE        // Should the array be indexed by cell row and cell column
									);
				}

				// Separate user data from meta
				$userdata = $usermeta = array();
				foreach ( $data[0] as $ckey => $column ) {
					$column_name = trim( $headers[$ckey] );
					$column = trim( $column );

					if ( in_array( $column_name, $userdata_fields ) ) {
						$userdata[$column_name] = $column;
					} else {
						$usermeta[$column_name] = $column;
					}
				}

				// If no user data, bailout!
				if ( empty( $userdata ) )
					continue;

	
				$user = $user_id = false;

				if ( ! $user ) {
					if ( isset( $userdata['username'] ) )
						$user = get_user_by( 'login', $userdata['username'] );
	
					if ( ! $user && isset( $userdata['user_email'] ) )
						$user = get_user_by( 'email', $userdata['user_email'] );
				}
				
				$update = false;
				if ( $user ) {
					$userdata['ID'] = $user->ID;
					$update = true;
				}
	
				// If creating a new user and no password was set, let auto-generate one!
				if ( ! $update && $update == false  && empty( $userdata['user_pass'] ) ) {
					$userdata['user_pass'] = wp_generate_password( 12, false );
				}
				
				if (isset($update)&& $update == true) {
					//$userdata['ID']	= $usermeta['user_id'];
					$user_id = wp_update_user( $userdata );
					
					$new_user 	= new WP_User( $user_id );
					
					if( $userdata['role'] === 'freelancers' ){
						$new_user->set_role( 'freelancers' );
						$role	= 'freelancers';
					} else{
						$new_user->set_role( 'employers' );	
						$role	= 'employers';
					}
					
					$display_name	= $userdata['first_name'].' '.$userdata['last_name'];
					
				} else {
					$display_name	= $userdata['first_name'].' '.$userdata['last_name'];
					
					
					$db_user_id = !empty( $usermeta['user_id'] ) ? $usermeta['user_id'] : '';
					$db_username = !empty( $userdata['username'] ) ? $userdata['username'] : '';
					$db_user_pass = !empty( $userdata['user_pass'] ) ? $userdata['user_pass'] : '123456';
					$db_user_email = !empty( $userdata['user_email'] ) ? $userdata['user_email'] : '';
					$db_user_url = !empty( $userdata['user_url'] ) ? $userdata['user_url'] : '';
					$db_nicename = !empty( $userdata['user_nicename'] ) ? sanitize_title( $userdata['user_nicename'] ) : $db_username;
					$display_name = !empty( $userdata['display_name'] ) ? $userdata['display_name'] : $display_name;
					
					if(empty($db_user_email)){
						continue;
					}
					
					$sql = "INSERT INTO $wp_user_table (user_login, 
														user_pass, 
														user_email, 
														user_registered,
														user_status, 
														display_name, 
														user_nicename, 
														user_url
														) VALUES ('".$db_username."',
														'".md5($db_user_pass)."',
														'".$db_user_email."',
														'".date('Y-m-d H:i:s')."',
														0,
														'".$display_name."',
														'".$db_nicename."',
														'".$db_user_url."'
													)";
					
					
					$wpdb->query($sql);
					$lastid 	= $wpdb->insert_id;
					$new_user 	= new WP_User( $lastid );
					
					if( $userdata['role'] === 'freelancers' ){
						$new_user->set_role( 'freelancers' );
						$role	= 'freelancers';
					} else{
						$new_user->set_role( 'employers' );	
						$role	= 'employers';
					}

					$user_id =	$lastid;
					
					// Include again meta fields
					$usermeta['first_name']	    = !empty( $userdata['first_name'] ) ? $userdata['first_name'] : '';
					$usermeta['last_name']	    = !empty( $userdata['last_name'] ) ? $userdata['last_name'] : '';

					update_user_meta( $user_id, 'usertype', $userdata['role'] ); //update user type
					update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
					update_user_meta( $user_id, 'full_name', $display_name );
					update_user_meta( $user_id, 'rich_editing', 'true' );
					update_user_meta( $user_id, 'nickname', $display_name );
					update_user_meta( $user_id, '_is_verified', 'yes' );
					
					
					$user_type						= workreap_get_user_type( $user_id );
					$freelancer_package_id			= workreap_get_package_type( 'package_type','trail_freelancer');
					$employer_package_id			= workreap_get_package_type( 'package_type','trail_employer');

					if( $user_type === 'employer' && !empty($employer_package_id) ) {
						workreap_update_pakage_data( $employer_package_id ,$user_id,'' );
					} else if( $user_type === 'freelancer' && !empty($freelancer_package_id) ) {
						workreap_update_pakage_data( $freelancer_package_id ,$user_id,'' );
					}
	
				}

				// Is there an error o_O?
				if ( is_wp_error( $user_id ) ) {
					$errors[$rkey] = $user_id;
				} else {
					// If no error, let's update the user meta too!
					$db_schedules	= array();
					if ( $usermeta ) {
						if( $import_type === 'upload' ){
							$fw_options	= array();
							$content	= '';

							$freelancers = array('gender','address','longitude','latitude','freelancer_type','english_level','tag_line','_perhour_rate','english_level');
							$employers   = array('address','longitude','latitude','employees','tag_line');

							foreach ( $usermeta as $metakey => $metavalue ) {
								$metavalue = maybe_unserialize( $metavalue );
								
								if( $role === 'freelancers' ){
									if( $metakey === 'country' ){
										$locations = get_term_by( 'slug', $metavalue, 'locations' );
										$location = array();
										if( !empty( $locations ) ){
											$location[0] = $locations->term_id;
											$fw_options[$metakey] = $location;
										}
									} else if( $metakey === 'content' ){
										$content = $metavalue;
									}  else if( $metakey === 'banner' ){
										if( !empty( $metavalue ) ){
											$attachment_url = wp_get_attachment_url( $metavalue );
											if( !empty( $attachment_url ) ){
												$profile_banner['attachment_id'] = $metavalue;
												$profile_banner['url'] = $attachment_url;
												$fw_options['banner_image']       = $profile_banner;
											}
										}

									} else if( in_array( $metakey, $freelancers ) ){
										$fw_options[$metakey] = $metavalue;
									} else{
										update_user_meta( $user_id, $metakey, trim( $metavalue ) );  
									}

								} else{
									if( $metakey === 'country' ){
										$locations = get_term_by( 'slug', $metavalue, 'locations' );
										$location = array();
										if( !empty( $locations ) ){
											$location[0] = $locations->term_id;
											$fw_options[$metakey] = $location;
										}
									} else if( $metakey === 'content' ){
										$content = $metavalue;
									} else if( $metakey === 'banner' ){
										if( !empty( $metavalue ) ){
											$attachment_url = wp_get_attachment_url( $metavalue );
											if( !empty( $attachment_url ) ){
												$profile_banner['attachment_id'] = $metavalue;
												$profile_banner['url'] = $attachment_url;
												$fw_options['banner_image']       = $profile_banner;
											}
										}

									} else if( in_array( $metakey, $employers ) ){
										$fw_options[$metakey] = $metavalue;
									} else{
										update_user_meta( $user_id, $metakey, trim( $metavalue ) );  
									}
								}
							}

							$full_name    = $display_name;

							//Create Post
							$user_post = array(
								'post_title'    => wp_strip_all_tags( $full_name ),
								'post_status'   => 'publish',
								'post_content'  => $content,
								'post_author'   => $user_id,
								'post_type'     => $role,
							);

							$post_id    = wp_insert_post( $user_post );
							if( !is_wp_error( $post_id ) ) {

								update_user_meta( $user_id, '_linked_profile', $post_id );

								// update location 
								if( !empty($location[0]) ){
									wp_set_post_terms( $post_id, array($location[0]), 'locations' );
								}
								
								if( $role == 'employers' ){
									$user_type = 'employer';
									update_post_meta($post_id, '_user_type', 'employer');
									update_post_meta($post_id, '_employees', $fw_options['employees']);            		
									update_post_meta($post_id, '_followers', '');
									
									//update department
									if( !empty( $usermeta['department'] ) ){
										$department_term = get_term_by( 'slug', $usermeta['department'], 'department' );
										if( !empty( $department_term ) ){
											wp_set_post_terms( $post_id, $usermeta['department'], 'department' );
											update_post_meta($post_id, '_department', $department_term->slug);
											
											//Fw Options
											$fw_options['department']         = array( $department_term->term_id );
										}
									}



								} elseif( $role == 'freelancers' ){

									//update languages
									if( !empty( $usermeta['languages'] ) ){
										$lang_array	= explode(',',$usermeta['languages']);
										$lang		= array();
										foreach( $lang_array as $key => $item ){
											$langs = get_term_by( 'slug', $item, 'languages' );
											if( !empty( $langs ) ){
												$lang[] = $langs->term_id;
											}
										}

										if( !empty( $lang ) ){
											wp_set_post_terms($post_id, $lang, 'languages');
										}
									}

									$user_type = 'freelancer';
									update_post_meta($post_id, '_user_type', 'freelancer');
									update_post_meta($post_id, '_perhour_rate', $fw_options['_perhour_rate']);
									update_post_meta($post_id, 'rating_filter', 0);
									update_post_meta($post_id, '_freelancer_type', $fw_options['freelancer_type']);         		           		
									update_post_meta($post_id, '_featured_timestamp', 0); 
									update_post_meta($post_id, '_english_level', $fw_options['english_level']);

								}
								
								update_post_meta($post_id, '_is_verified', 'yes'); 
								update_post_meta($post_id, '_profile_blocked', 'off'); 

								//update profile picture
								if( !empty( $usermeta['image'] ) ){
									set_post_thumbnail($post_id, $usermeta['image']);
								}

								//update privacy settings
								$settings		 = workreap_get_account_settings($user_type);
								if( !empty( $settings ) ){
									foreach( $settings as $key => $value ){
										$val = $key === '_profile_blocked' ? 'off' : 'on';
										update_post_meta($post_id, $key, $val);
									}
								}

								//Update User Profile
								fw_set_db_post_option($post_id, null, $fw_options);

								update_post_meta($post_id, '_linked_profile', $user_id);
								update_post_meta($metavalue, 'identity_verified', 1);
							}
						} else{

							foreach ( $usermeta as $metakey => $metavalue ) {
								$metavalue = maybe_unserialize( $metavalue );
								if( $metakey === '_linked_profile' ){
									update_user_meta( $user_id, $metakey, trim( $metavalue ) );
									update_post_meta($metavalue, '_linked_profile', $user_id);
									update_post_meta($metavalue, '_featured_timestamp', 0); 
									update_post_meta($metavalue, 'rating_filter', 0);       		           		
									update_post_meta($metavalue, '_is_verified', 'yes'); 
									update_post_meta($metavalue, '_profile_blocked', 'off');
									update_post_meta($metavalue, '_profile_health_filter', 0);
									update_post_meta($metavalue, '_have_avatar', 1);
									update_post_meta($metavalue, 'identity_verified', 1); 
									
								} else{
									update_user_meta( $user_id, $metakey, trim( $metavalue ) ); 
								}
							}
						} 
					}
					// If we created a new user, maybe set password nag and send new user notification?
					if ( ! $update ) {
					}
				}
	
				$rkey++;
			}
		}
	}
}