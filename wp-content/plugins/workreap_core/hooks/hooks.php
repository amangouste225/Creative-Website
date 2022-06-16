<?php
/**
 *
 * @package   Workreap Core
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
/**

/**
 * @get default color schemes
 * @return 
 */
if (!function_exists('workreap_get_domain')) {
	add_filter('workreap_get_domain','workreap_get_domain',10,1);
	function workreap_get_domain(){
		if( isset( $_SERVER["SERVER_NAME"] ) && $_SERVER["SERVER_NAME"] === 'amentotech.com' ){
			return true;
		} else{
			return false;
		}
	}
}

/**
 * REcaptucha
 *
 * @param json
 * @return string
 */
if (!function_exists('workreap_get_recaptcha_response')) {

    function workreap_get_recaptcha_response($recaptcha_data = '') {
		$status_cap = 0;
        if (function_exists('fw_get_db_settings_option')) {
            $response = null;
            $secret_key = fw_get_db_settings_option('secret_key', $default_value = null);

            if (!empty($secret_key)) {
				
				$args = array(
					'timeout'     	=> 15,
					'headers' 		=> array('Accept-Encoding' => ''),
					'sslverify' 	=> false
				);
				
				$url			= 'https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$recaptcha_data.'&remoteip='.$_SERVER['REMOTE_ADDR'];
				$response   	= wp_remote_get( $url, $args );
				$removeCaptcha	= wp_remote_retrieve_body($response);

				$output	  		= !empty($removeCaptcha) ? json_decode($removeCaptcha) : '';

				if( !empty( $output->success ) ) {
					 $status_cap = 1;
				}else{
					$status_cap  = 0;
				}
            } else {
                $status_cap = 2;
            }
        }

        return $status_cap;
    }

}

/**
 * @User social fields
 * @return fields
 */
if( !function_exists('workreap_user_social_fields')){
	function workreap_user_social_fields($user_fields) {
		$user_fields['twitter'] = esc_html__('Twitter', 'workreap_core');
		$user_fields['facebook'] = esc_html__('Facebook', 'workreap_core');
		$user_fields['google'] = esc_html__('Google+', 'workreap_core');
		$user_fields['tumblr'] = esc_html__('Tumbler', 'workreap_core');
		$user_fields['instagram'] = esc_html__('Instagram', 'workreap_core');
		$user_fields['pinterest'] = esc_html__('Pinterest', 'workreap_core');
		$user_fields['skype'] = esc_html__('Skype', 'workreap_core');
		$user_fields['linkedin'] = esc_html__('Linkedin', 'workreap_core');

		return $user_fields;
	}
	add_filter('user_contactmethods', 'workreap_user_social_fields');
}

/**
 * MSet post views
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_post_views')) {

    function workreap_post_views($post_id = '',$key='set_blog_view') {

        if (!is_single())
            return;

        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;
        }
		
        if (!isset($_COOKIE[$key . $post_id])) {
            setcookie($key . $post_id, $key, time() + 3600);
            $view_key = $key;

            $count = get_post_meta($post_id, $view_key, true);

            if ($count == '') {
                $count = 0;
                delete_post_meta($post_id, $view_key);
                add_post_meta($post_id, $view_key, '0');
            } else {
                $count++;
                update_post_meta($post_id, $view_key, $count);
            }
        }
    }

    add_action('workreap_post_views', 'workreap_post_views', 5, 2);
}

/**
 * @Wp Login
 * @return 
 */
if (!function_exists('workreap_ajax_login')) {

    function workreap_ajax_login() { 
		global $wpdb;

        $user_array = array();
		$json		= array();
        $user_array['user_login'] = sanitize_text_field($_POST['username']);
        $user_array['user_password'] = sanitize_text_field($_POST['password']);
		$redirect	= !empty( $_POST['redirect'] ) ? esc_url( $_POST['redirect'] ) : '';
		
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
                    $json['message'] = esc_html__('An error occurred, please try again later.', 'workreap_core');
                    wp_send_json($json);
                } else {
					$json['type'] = 'error';
                    $json['message'] = esc_html__('Wrong reCaptcha. Please verify first.', 'workreap_core');
                    wp_send_json($json);
                }
            } else {
                wp_send_json(array('type' => 'error', 'message' => esc_html__('Please enter reCaptcha!', 'workreap_core')));
            }
        }
		
        if (isset($_POST['rememberme'])) {
            $remember = sanitize_text_field($_POST['rememberme']);
        } else {
            $remember = '';
        }

        if ($remember) {
            $user_array['remember'] = true;
        } else {
            $user_array['remember'] = false;
        }
		
        if ($user_array['user_login'] == '') {
            echo json_encode(array('type' => 'error', 'loggedin' => false, 'message' => esc_html__('Username should not be empty.', 'workreap_core')));
            exit();
        } elseif ($user_array['user_password'] == '') {
            echo json_encode(array('type' => 'error', 'loggedin' => false, 'message' => esc_html__('Password should not be empty.', 'workreap_core')));
            exit();
        } else {
			
			$user = wp_signon($user_array, false);
			
			if (is_wp_error($user)) {
				echo json_encode(array('type' => 'error', 'loggedin' => false, 'message' => esc_html__('Wrong email/username or password.', 'workreap_core')));
			} else {

				$user_meta  = get_userdata($user->ID);
				$user_roles = $user_meta->roles;
				$user_role = !empty($user_roles) ? $user_roles[0] : '';

				if (empty( $redirect )) {
					$redirect   = workreap_login_redirect($user->ID);
				}
				
				if(!empty($user_role) && $user_role === 'administrator'){
					$redirect	= home_url('/');
				}
				
				echo json_encode(array( 'job'=>'no','type' => 'success', 'role_type' => $user_role, 'redirect' => $redirect, 'url' => home_url('/'), 'loggedin' => true, 'message' => esc_html__('Successfully Logged in', 'workreap_core')));
				
			}
			
        }

        die();
    }

    add_action('wp_ajax_workreap_ajax_login', 'workreap_ajax_login');
    add_action('wp_ajax_nopriv_workreap_ajax_login', 'workreap_ajax_login');
}


/**
 * @Strong password validation
 * @return 
 */
if( !function_exists( 'workreap_strong_password_validation' ) ){
	add_action('workreap_strong_password_validation', 'workreap_strong_password_validation',10,1);
	function workreap_strong_password_validation($password){
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$password_strength 		= fw_get_db_settings_option( 'password_strength');
		}
		
		$password_strength	= !empty($password_strength) ? $password_strength : array('length');
		$choices = array(
					'length'   			=> wp_kses( __('Password must be 8 characters<br>', 'workreap_core' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
					'upper'				=> wp_kses( __('1 upper case<br>', 'workreap_core' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
					'lower'  			=> wp_kses( __('1 lower case<br>', 'workreap_core' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
					'special_character' => wp_kses( __('1 special character<br>', 'workreap_core' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
					'number'  			=> wp_kses( __('1 number<br>', 'workreap_core' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
				);
		
		if( !empty($password) ) {
			$number 			= preg_match('@[0-9]@', $password);
			$uppercase 		= preg_match('@[A-Z]@', $password);
			$lowercase 		= preg_match('@[a-z]@', $password);
			$specialChars 	= preg_match('@[^\w]@', $password);
			$errors			= '';


			foreach($password_strength as $key => $item){
				  if( $item === 'length'){
					  if( strlen($password) < 8 ) {
						$errors .= $choices[$item];  
					  }
				  }else if( $item === 'upper' && !$uppercase ){
					  $errors .= $choices[$item];  
				  }else if( $item === 'lower' && !$lowercase ){
					  $errors .= $choices[$item];  
				  }else if( $item === 'number' && !$number ){
					  $errors .= $choices[$item];  
				  }else if( $item === 'special_character' && !$specialChars ){
					  $errors .= $choices[$item];  
				  }
			}

			if(!empty($errors)){
				$json['type'] 		= 'error';
				$json['message'] 	= $errors;
				wp_send_json($json);
			}
		}
	}
}

/**
 * @Registration gender types
 * @return 
 */
if( !function_exists( 'workreap_gender_types' ) ){
	add_filter('workreap_gender_types', 'workreap_gender_types',10,1);
	function workreap_gender_types($list){
		$gender_list	= array();
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$g_settings 		= fw_get_db_settings_option( 'gender_settings', $default_value = null );
			$is_true			= !empty( $g_settings['gadget'] ) ? $g_settings['gadget'] : 'no';
			$list				= !empty( $g_settings['yes']['gender_options'] ) ? $g_settings['yes']['gender_options'] : 'no';

			if( !empty( $list ) and is_array( $list ) && $is_true === 'yes' ){
				$list = array_filter($list);
				$list = array_combine(array_map('sanitize_title', $list), $list);
				$gender_list	= apply_filters('workreap_filter_gender_types',$list);
			}
		} 

		return $gender_list;
	}
}

/**
 * @Registration Step One
 * @return 
 */
if( !function_exists( 'workreap_registration_single_step' ) ){
	function workreap_registration_single_step($type=''){
		$image_url	= '';
		$login_register	= array();
		$single_step_logo	= '';
		$enable_google_connect 	 = '';
		$enable_facebook_connect = '';
		$enable_linkedin_connect = '';
		$captcha_settings = '';

		if (function_exists('fw_get_db_settings_option')) { 
			$enable_google_connect 	 = fw_get_db_settings_option('enable_google_connect', $default_value = null);
			$enable_facebook_connect = fw_get_db_settings_option('enable_facebook_connect', $default_value = null);
			$enable_linkedin_connect = fw_get_db_settings_option('enable_linkedin_connect', $default_value = null);
			$captcha_settings 		 = fw_get_db_settings_option('captcha_settings', $default_value = null);
			$login_register 		 = fw_get_db_settings_option('enable_login_register');  
			$image_url				 = !empty($login_register['enable']['single_step_image']['url']) ? $login_register['enable']['single_step_image']['url'] : ''; 
			$single_step_logo		 = !empty($login_register['enable']['single_step_logo']['url']) ? $login_register['enable']['single_step_logo']['url'] : ''; 
		}
		
		$login_page	= '';     
		
		if (!empty($login_register['enable']['login_page'][0]) && !empty($login_register['enable']['login_signup_type']) && $login_register['enable']['login_signup_type'] == 'pages' ) {
			$login_page = get_the_permalink($login_register['enable']['login_page'][0]);
		}

		$remove_role	= !empty( $login_register['enable']['remove_role_registration'] ) ? $login_register['enable']['remove_role_registration'] : 'both';
		$default_role	= !empty( $login_register['enable']['default_role'] ) ? $login_register['enable']['default_role'] : 'freelancer';
		
		//set default role from URL
		if(!empty($_GET['type']) && ( $_GET['type'] === 'employer' || $_GET['type'] === 'freelancer' )){
			$default_role	= $_GET['type'];
		}
		
		if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {
			$terms_link 	= !empty( $login_register['enable']['registration']['enable']['terms_link'] ) ? $login_register['enable']['registration']['enable']['terms_link'] : '';
			$terms_link 	= !empty( $terms_link ) ? get_the_permalink($terms_link[0]) : '';
			$term_text 		= !empty( $login_register['enable']['registration']['enable']['term_text'] ) ? $login_register['enable']['registration']['enable']['term_text'] : esc_html__('Agree our terms and conditions', 'workreap_core');
		}
		
		$phone_option			= '';
		$phone_option_reg		= '';
		if( function_exists('fw_get_db_settings_option')  ){
			$phone_option		= fw_get_db_settings_option('phone_option', $default_value = null);
			$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
			$phone_option_reg	= !empty($phone_option['enable']['phone_option_registration']) ? $phone_option['enable']['phone_option_registration'] : '';
		}

		$hide_location 	= !empty( $login_register['enable']['registration']['enable']['hide_loaction'] ) ? $login_register['enable']['registration']['enable']['hide_loaction'] : 'no';
		
		
		ob_start();
		$left_banner	= '';
		$holderClass	= 'col-12 col-md-8 col-lg-8 col-xl-7';
		if( empty($image_url) ){
			$left_banner	= 'left-banner-empty';
			$holderClass	= 'col-12 col-sm-10 col-lg-8 col-xl-7';
		}
		?>
		<div class="row align-items-center <?php echo esc_attr($left_banner);?>">
			<?php if( !empty($image_url) && isset($type) && $type !== 'shortcode' ){?>
				<div class="col-12 col-md-4 col-xl-5">
					<figure class="wt-joinnow-img">
						<img src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Registration','workreap_core');?>">
					</figure>
				</div>
			<?php } ?>
			<div class="<?php echo esc_attr($holderClass);?>">
				<div class="wt-joinnowpopup-wrap">
					<?php if( !empty($single_step_logo) ){?>
						<strong class="wt-joinnow-logo"><a href="<?php echo esc_url(get_home_url());?>"><img src="<?php echo esc_url($single_step_logo);?>" alt="<?php esc_attr_e('Registration logo','workreap_core');?>"></a></strong>
					<?php }?>
					<form class="wt-formtheme wt-joinnow-form single-social-style" id="wt-single-joinnow-form" method="post">
						<fieldset>
							<div class="wt-popuptitletwo">
								<h4><?php esc_html_e("It's Free to Sign Up and Get Started.","workreap_core");?></h4>
								<span><?php esc_html_e("Already have account?","workreap_core");?> 
									<?php if( !empty($login_page) ) {?>
										<a href="<?php echo esc_url($login_page);?>"><?php esc_html_e("Sign In Now","workreap_core");?></a>
									<?php } else { ?>
										<a href="#" onclick="event_preventDefault(event);" id="wt-single-sigin"><?php esc_html_e("Sign In Now","workreap_core");?></a>
									<?php } ?>
								</span>
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="first_name"  class="form-control" value="" placeholder="<?php esc_attr_e('First Name', 'workreap_core'); ?>" required="">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="last_name" value="" class="form-control" placeholder="<?php esc_attr_e('Last Name', 'workreap_core'); ?>">
							</div>
							<?php do_action('workreap_username_add_remove','form-group-half');?>
							<?php if( !empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_setting) && $phone_setting == 'enable' ){?>
								<div class="form-group form-group-half">
									<input type="text" name="user_phone_number" value="" class="form-control" placeholder="<?php esc_attr_e('Phone number', 'workreap_core'); ?>">
								</div>
							<?php } ?>
							
							<div class="form-group form-group-half">
								<input type="email" name="email" class="form-control" value=""  placeholder="<?php esc_attr_e('Email', 'workreap_core'); ?>">
							</div>
							<div class="form-group wt-eyeicon form-group-half toolip-wrapo">
								<input type="password" class="form-control wt-password-field" name="password" placeholder="<?php esc_attr_e('Password', 'workreap_core'); ?>">
								<a href="#" class="wt-hidepassword"><i class="ti-eye"></i></a>
								<?php do_action('workreap_get_tooltip','element','password');?>
							</div>
							<?php if( !empty($hide_location) && $hide_location == 'no' ){?>
								<div class="form-group form-group-half">
									<?php do_action('worktic_get_locations_list','location',''); ?>	
								</div>
							<?php } ?>
							<div class="form-group wt-checkbox-wrap">
								<h4><?php esc_html_e("I want to start as","workreap_core");?>: </h4>
								<?php if(!empty($remove_role) && $remove_role !== 'freelancers'){?>
									<span class="wt-radio">
										<input id="wt-freelancer-single" type="radio"  name="user_type" value="freelancer" <?php checked( $default_role, 'freelancer' ); ?>>
										<label for="wt-freelancer-single"><?php esc_html_e('Freelancer', 'workreap_core'); ?></label>
									</span>
								<?php }?>
								<?php if(!empty($remove_role) && $remove_role !== 'employers'){?>
									<span class="wt-radio">
										<input id="wt-employer-single" type="radio" name="user_type" value="employer" <?php checked( $default_role, 'employer' ); ?>>
										<label for="wt-employer-single"><?php esc_html_e('Employer', 'workreap_core'); ?></label>
									</span>
								<?php }?>
							</div>
							<?php if( !empty($term_text) || !empty($terms_link)) {?>
							<div class="form-group wt-checkbox-wrap">
								<div class="wt-joinnowfooter">
									<p>
										<input type="hidden" name="termsconditions" value="no">
										<input type="checkbox" name="termsconditions" value="yes">
										<?php echo esc_html( $term_text ); ?>
										<?php if( !empty( $terms_link ) ) { ?>
											<a target="_blank" href="<?php echo esc_url( $terms_link ); ?>"><?php esc_html_e('Terms & Conditions', 'workreap_core'); ?></a>
										<?php } ?>
									</p>
								</div>
							</div>
							<?php } ?>
							<?php if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
								<?php wp_enqueue_script('recaptcha');?>
								<div class="domain-captcha form-group">
									<div id="recaptcha_signup"></div>
								</div>
							<?php }?>
							<div class="form-group wt-btnarea">
								<button class="wt-btn" id="wt-singe-signup"><i class="ti-lock"></i> <?php esc_html_e('Sign up now','workreap_core');?></button>
								<?php 
								if (  ( isset($enable_google_connect) && $enable_google_connect === 'enable' ) 
								   || ( isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) 
								   || ( isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) 
								) {?>
									<div class="wt-loginicon">
										
										<ul>
											<?php if (  isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) {?><li class="wt-facebook"><a href="#" onclick="event_preventDefault(event);" class="sp-fb-connect"><i class="fa fa-facebook-f"></i><?php esc_html_e('Facebook','workreap_core');?></a></li><?php } ?>
											<?php if (  isset($enable_google_connect) && $enable_google_connect === 'enable' ) {?><li class="wt-google"><a href="#" onclick="event_preventDefault(event);"  class="wt-googlebox" id="wt-gconnect-reg"><i class="fa fa-google"></i><?php esc_html_e('Google','workreap_core');?></a></li><?php } ?>
											<?php if (  isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) {?><li class="wt-linkedin"><a class="sp-linkedin-connect" href="#" onclick="event_preventDefault(event);"><i class="fa fa-linkedin"></i><?php esc_html_e('LinkedIn', 'workreap_core')?></a></li><?php } ?>
										</ul>
									</div>
								<?php } ?>
							</div>
						</fieldset>
						
					</form>
				</div>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}
	add_action('workreap_registration_single_step', 'workreap_registration_single_step',10,1);
}


/**
 * @Add or remove username
 * @return 
 */
if( !function_exists( 'workreap_username_add_remove' ) ){
	add_action('workreap_username_add_remove', 'workreap_username_add_remove',10,1);
	function workreap_username_add_remove($classes=''){
		if (function_exists('fw_get_db_settings_option')) { 
			$remove_username 		= fw_get_db_settings_option('enable_login_register');
		}

		if(!empty($remove_username['gadget'])
		   && !empty($remove_username['enable']['remove_username']) 
		   && $remove_username['gadget'] === 'enable' 
		   && $remove_username['enable']['remove_username'] === 'yes'){return;}
		?>
		<div class="form-group <?php echo esc_attr($classes);?>">
			<input type="text" name="username" class="form-control" value=""  placeholder="<?php esc_attr_e('Type username', 'workreap_core'); ?>">
		</div>
		<?php 
	}
}

/**
 * @Add or remove username
 * @return 
 */
if( !function_exists( 'demo_login_details' ) ){
	//add_action('demo_login_details', 'demo_login_details',10,1);
	function demo_login_details($classes=''){
		if( isset( $_SERVER["SERVER_NAME"] ) && 
		   ( $_SERVER["SERVER_NAME"] === 'amentotech.com' 
			 ||  $_SERVER["SERVER_NAME"] === 'wp-guppy.com' 
		   	 ||  $_SERVER["SERVER_NAME"] === 'houzillo.com'
			 //||  $_SERVER["SERVER_NAME"] === 'localhost'
		   )
		  ){
			?>
			<?php /*?><div class="demo-login-wrap">
				<ul>
					<li>
						<a href="javascript:void(0);" data-type="freelancer"><?php esc_html_e('Demo login','workreap_core');?><em><?php esc_html_e('Login as freelancer','workreap_core');?></em></a>
					</li>
					<li>
						<a href="javascript:void(0);" data-type="employer"><?php esc_html_e('Demo login','workreap_core');?><em><?php esc_html_e('Login as employer','workreap_core');?></em></a>
					</li>
				</ul>
			</div>
			<style>
				.demo-login-wrap ul{
					display: flex;
					flex-wrap: wrap;
					margin: -10px;
					list-style: none;
				}
				.demo-login-wrap li{
					width: 100%;
					max-width: 50%;
					padding: 10px;
					list-style-type: none;
				}
				.demo-login-wrap li a{
					display: block;
					line-height: 24px;
					border: 1px solid #eee;
					font-size: 16px;
					padding: 12px 20px;
					color: #484848;
					border-radius: 4px;
					font-weight: 600;
				}
				.demo-login-wrap li a:hover{
					background: #f7f7f7;
				}
				.wt-popuptitletwo span + .demo-login-wrap{
					margin-top: 10px;
				}
				.demo-login-wrap a em{
					display: block;
					font-style: normal;
					font-size: 12px;
					font-weight: 400;
				}
			</style><?php */?>
			<?php
		}
	}
}

/**
 * @Login single
 * @return 
 */
if( !function_exists( 'workreap_login_single_step' ) ){
	function workreap_login_single_step($type=''){
		$image_url	= '';
		$single_step_logo	= '';
		$enable_google_connect 	 = '';
		$enable_facebook_connect = '';
		$enable_linkedin_connect = '';
		$captcha_settings = '';
		$login_register	= array();
		if (function_exists('fw_get_db_settings_option')) { 
			$enable_google_connect 	 = fw_get_db_settings_option('enable_google_connect', $default_value = null);
			$enable_facebook_connect = fw_get_db_settings_option('enable_facebook_connect', $default_value = null);
			$enable_linkedin_connect = fw_get_db_settings_option('enable_linkedin_connect', $default_value = null);
			$login_register 		= fw_get_db_settings_option('enable_login_register');
			$image_url				= !empty($login_register['enable']['single_step_image']['url']) ? $login_register['enable']['single_step_image']['url'] : ''; 
			$single_step_logo		= !empty($login_register['enable']['single_step_logo']['url']) ? $login_register['enable']['single_step_logo']['url'] : ''; 
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
			
		}

		if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {
			$terms_link 	= !empty( $login_register['enable']['registration']['enable']['terms_link'] ) ? $login_register['enable']['registration']['enable']['terms_link'] : '';
			$terms_link 	= !empty( $terms_link ) ? get_the_permalink($terms_link[0]) : '';
			$term_text 		= !empty( $login_register['enable']['registration']['enable']['term_text'] ) ? $login_register['enable']['registration']['enable']['term_text'] : esc_html__('Agree our terms and conditions', 'workreap_core');
		}
		
		
		$reg_url	= '';     
		if (!empty($login_register['enable']['login_reg_page'][0]) && !empty($login_register['enable']['login_signup_type']) && $login_register['enable']['login_signup_type'] == 'pages' ) {
			$reg_url = get_the_permalink($login_register['enable']['login_reg_page'][0]);
		}
		
		ob_start();
		$left_banner	= '';
		if( empty($image_url) ){
			$left_banner	= 'left-banner-empty';
		}
		
		$columnClass	= 'col-12 col-md-8 col-lg-8 col-xl-7';
		if(!empty($type) && $type === 'shortcode'){
			$columnClass	= 'col-sm-7 col-lg-5 wt-loginshortcodes';
		}

		?>
		<div class="row align-items-center custom-login-wrapper <?php echo esc_attr($left_banner);?>">
			<?php if( !empty($image_url) && isset($type) && $type !== 'shortcode' ){?>
				<div class="col-12 col-md-4 col-xl-5">
					<figure class="wt-joinnow-img">
						<img src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Registration','workreap_core');?>">
					</figure>
				</div>
			<?php } ?>
			<div class="<?php echo esc_attr($columnClass);?>">
				<div class="wt-joinnowpopup-wrap">
					<?php if( !empty($single_step_logo) ){?>
						<strong class="wt-joinnow-logo"><a href="<?php echo esc_url(get_home_url());?>"><img src="<?php echo esc_url($single_step_logo);?>" alt="<?php esc_attr_e('Regidtration logo','workreap_core');?>"></a></strong>
					<?php }?>
					
					<form class="wt-formtheme wt-joinnow-form do-login-form single-social-style" id="wt-single-login-form" method="post">
						<fieldset>
							<div class="wt-popuptitletwo">
								<h4><?php esc_html_e("Sign In Now","workreap_core");?></h4>
								<?php if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {?>
									<?php if(isset($type) && $type !== 'shortcode'){?>
										<span><?php esc_html_e("Don’t have an account?","workreap_core");?>
											<a href="#" onclick="event_preventDefault(event);" id="wt-single-signup"><?php esc_html_e("Sign up","workreap_core");?></a>
										</span>
									<?php } else if( !empty($reg_url) ) {?>
										<span>
											<?php esc_html_e("Don’t have an account?","workreap_core");?>
											<a href="<?php echo esc_url($reg_url);?>"><?php esc_html_e("Sign up","workreap_core");?></a>
										</span>
									<?php } ?>
								<?php } ?>
								<?php do_action('demo_login_details');?>
							</div>
							<div class="form-group">
								<input type="text" name="username" class="form-control" value=""  placeholder="<?php esc_attr_e('Type email or username', 'workreap_core'); ?>">
							</div>
							<div class="form-group wt-eyeicon toolip-wrapo">
								<input type="password" class="form-control wt-password-field" name="password" placeholder="<?php esc_attr_e('Password', 'workreap_core'); ?>">
								<a href="#" onclick="event_preventDefault(event);" class="wt-hidepassword"><i class="ti-eye"></i></a>
								<?php do_action('workreap_get_tooltip','element','password');?>
							</div>
							<?php if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
								<?php wp_enqueue_script('recaptcha');?>
								<div class="domain-captcha form-group">
									<div id="recaptcha_signin"></div>
								</div>
							<?php }?>
							<div class="form-group wt-btnarea">
								<span class="wt-checkbox">
									<input id="wt-loginp" type="checkbox" name="rememberme">
									<label for="wt-loginp"><?php esc_html_e('Keep me logged in','workreap_core');?></label>
								</span>
								<button class="wt-btn do-login-button" ><i class="ti-lock"></i> <?php esc_html_e('Sign In','workreap_core');?></button>
								<span><a href="#" onclick="event_preventDefault(event);" class="wt-forgot-password-single" ><?php esc_html_e('Reset Password?','workreap_core');?></a></span>
								<?php 
								if (  ( isset($enable_google_connect) && $enable_google_connect === 'enable' ) 
								   || ( isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) 
								   || ( isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) 
								) {?>
									<div class="wt-loginicon">
										<span class="wt-optionsbar"><em><?php esc_html_e('or', 'workreap_core')?></em></span>
										<ul>

											<?php if (  isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) {?><li class="wt-facebook"><a href="#" onclick="event_preventDefault(event);" class="sp-fb-connect"><i class="fa fa-facebook-f"></i> <?php esc_html_e('Facebook','workreap_core');?></a></li><?php } ?> 
											<?php if (  isset($enable_google_connect) && $enable_google_connect === 'enable' ) {?><li class="wt-googleplus"><a href="#" onclick="event_preventDefault(event);"  class="wt-googlebox" id="wt-gconnect"><i class="fa fa-google"></i><?php esc_html_e('Google','workreap_core');?></a></li><?php } ?>
											<?php if (  isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) {?><li class="wt-linkedin"><a class="sp-linkedin-connect" href="#" onclick="event_preventDefault(event);"><i class="fa fa-linkedin"></i><?php esc_html_e('LinkedIn', 'workreap_core')?></a></li><?php } ?>
										</ul>
									</div>
								<?php } ?>
							</div>
						</fieldset>
					</form>
					<form class="wt-formtheme wt-joinnow-form do-login-form wt-hide-form do-forgot-password-form">
						<fieldset>
							<div class="wt-popuptitletwo">
								<h4><?php esc_html_e("Forgot password","workreap_core");?></h4>
								<span><?php esc_html_e("If you have an account?","workreap_core");?> <a href="#" onclick="event_preventDefault(event);" class="wt-single-revert"><?php esc_html_e("Sign In","workreap_core");?></a></span>
							</div>
							<div class="form-group">
								<input type="email" name="email" class="form-control get_password" placeholder="<?php esc_html_e('Email', 'workreap_core'); ?>">
							</div>
							<?php if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
								<?php wp_enqueue_script('recaptcha');?>
								<div class="domain-captcha form-group">
									<div id="recaptcha_forgot"></div>
								</div>
							<?php }?>
							<div class="form-group wt-btnarea">
								<a href="#" onclick="event_preventDefault(event);" class="wt-btn do-get-password-btn"><?php esc_html_e('Get Password','workreap_core');?></a>
							</div>                                                               
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}
	add_action('workreap_login_single_step', 'workreap_login_single_step');
}

/**
 * @PayPal return URL change
 * @return 
 */
if( !function_exists( 'workreap_paypal_get_return_url' ) ){
	//add_filter('woocommerce_get_return_url','workreap_paypal_get_return_url',10,2);
	function workreap_paypal_get_return_url($return_url,$order){
		global $current_user;
		$return_url	= Workreap_Profile_Menu::workreap_profile_menu_link('insights', $current_user->ID,true);
		$return_url	= str_replace('&utm_nooverride=1','',$return_url);
		return $return_url;
	}
}

/**
 * @Registration Step One
 * @return 
 */
if( !function_exists( 'workreap_registration_step_one' ) ){
	function workreap_registration_step_one($class=''){
		$step_one_title = '';
		$step_one_desc  = '';
		$verify_user  	= 'verified';
		$selected 		= 'selected';
		$phone_option			= '';
		$phone_option_reg		= '';
		$json			= array();
		
		if( !empty( $class ) ){
			$post_class		= 'wt-model-reg1';
		} else{
			$post_class		= !empty( $_POST['key'] ) ? 'wt-model-reg1' : 'rg-step-one';
		}
		
		$gender_list	= apply_filters('workreap_gender_types',array());
		
		$enable_login_register	= array();
		
		if (function_exists('fw_get_db_settings_option')) {           
            $step_one_title = fw_get_db_settings_option('step_one_title');
            $step_one_desc = fw_get_db_settings_option('step_one_desc');        
			$enable_google_connect 	 = fw_get_db_settings_option('enable_google_connect', $default_value = null);
			$enable_facebook_connect = fw_get_db_settings_option('enable_facebook_connect', $default_value = null);
			$enable_linkedin_connect = fw_get_db_settings_option('enable_linkedin_connect', $default_value = null);
			$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
			$enable_login_register = fw_get_db_settings_option('enable_login_register');
			$gender_settings = fw_get_db_settings_option('gender_settings');
			$phone_option		= fw_get_db_settings_option('phone_option', $default_value = null);
			$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
			$phone_option_reg	= !empty($phone_option['enable']['phone_option_registration']) ? $phone_option['enable']['phone_option_registration'] : '';
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
        }
		
		$login_page	= '';     
		if (!empty($enable_login_register['enable']['login_page'][0]) 
			&& !empty($enable_login_register['enable']['login_signup_type']) 
			&& $enable_login_register['enable']['login_signup_type'] == 'pages' ) {
			$login_page = get_the_permalink($enable_login_register['enable']['login_page'][0]);
		}
		
        if( empty( $step_one_title ) ){
        	$step_one_title = esc_html__('Join For a Good Start', 'workreap_core');
        }   
		
		$usernameClass = '';
		if(!empty($enable_login_register['gadget'])
		   && !empty($enable_login_register['enable']['remove_username']) 
		   && $enable_login_register['gadget'] === 'enable' 
		   && $enable_login_register['enable']['remove_username'] === 'yes'){
			$usernameClass = 'form-group-half';
		}
		
		//Enqueu recaptcha
		if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {wp_enqueue_script('recaptcha');}
							
		ob_start(); ?>		
		<div class="wt-registerformmain">
			<div class="wt-registerhead">
				<div class="wt-title">
					<h3><?php echo esc_attr( $step_one_title ); ?></h3>
				</div>
				<?php if( !empty( $step_one_desc ) ) { ?>
					<div class="description">
						<?php echo do_shortcode( $step_one_desc ); ?>
					</div>
				<?php } ?>
			</div>
			<div class="wt-joinforms">
				<ul class="wt-joinsteps">
					<li class="wt-active"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('01', 'workreap_core'); ?></a></li>
					<li><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('02', 'workreap_core'); ?></a></li>
					<li><a href="#" onclick="event_preventDefault(event);">	<?php esc_html_e('03', 'workreap_core'); ?></a></li>
				</ul>
				<form class="wt-formtheme wt-formregister">
					<fieldset class="wt-registerformgroup">
						<div class="form-group wt-form-group-dropdown form-group-half">
							<?php if( !empty( $gender_list ) ){?>
							<span class="wt-select">
								<select name="gender">
									<?php foreach( $gender_list as $key	=> $val ){?>
										<option value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $val );?></option>
									<?php }?>
								</select>
							</span>
							<?php }?>
							<input type="text" name="first_name" class="form-control" value="" placeholder="<?php esc_html_e('First Name', 'workreap_core'); ?>">
						</div>
						<div class="form-group form-group-half">
							<input type="text" name="last_name" value="" class="form-control" placeholder="<?php esc_html_e('Last Name', 'workreap_core'); ?>">
						</div>
						<?php do_action('workreap_username_add_remove','form-group-half');?>
						<div class="form-group form-group-half">
							<input type="email" name="email" class="form-control" value="" placeholder="<?php esc_html_e('Email', 'workreap_core'); ?>">
						</div>
						<?php if( !empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_setting) && $phone_setting == 'enable' ){?>
							<div class="form-group <?php echo esc_attr($usernameClass);?>">
								<input type="text" name="user_phone_number" value="" class="form-control" placeholder="<?php esc_attr_e('Phone number', 'workreap_core'); ?>">
							</div>
						<?php } ?>
						<div class="form-group">
							<a href="#" onclick="event_preventDefault(event);" class="wt-btn <?php echo esc_attr( $post_class );?>">
								<?php esc_html_e('Start Now', 'workreap_core'); ?>
							</a>
						</div>
					</fieldset>
				</form>
				<div class="wt-joinnowholder">
					<?php 
					if (  ( isset($enable_google_connect) && $enable_google_connect === 'enable' ) 
					   || ( isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) 
					   || ( isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) 
					) {?>
						<div class="wt-title">
							<h4><?php esc_html_e('Join Now With', 'workreap_core'); ?></h4>
						</div>
						<div class="wt-description">
							<p><?php esc_html_e('Use a social account for faster login or easy registration to directly get in to your account', 'workreap_core'); ?></p>
						</div>
						<ul class="wt-socialicons wt-iconwithtext">
							<?php if (  isset($enable_google_connect) && $enable_google_connect === 'enable' ) {?>
								<li class="wt-googleplus"><a id="wt-gconnect-reg" class="wt-googlebox" href="#" onclick="event_preventDefault(event);"><i class="fa fa-google"></i><em><?php esc_html_e('Google', 'workreap_core'); ?></em></a></li>
							<?php }?>
							<?php if (  isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) {?>
								<li class="wt-facebook"><a class="sp-fb-connect" href="#" onclick="event_preventDefault(event);"><i class="fa fa-facebook-f"></i><em><?php esc_html_e('Facebook', 'workreap_core'); ?></em></a></li>
							<?php }?>
							<?php if (  isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) {do_action('workreap_linkedin_login_button');}?>
						</ul>
					<?php }?>
					<?php if( !is_user_logged_in() ){ ?>
					<div class="wt-loginfooterinfo-signup wt-haslayout">
						<?php if( !empty($login_page) ) {?>
							<span><?php esc_html_e('Already have an Account?', 'workreap_core' ); ?>&nbsp;<a href="<?php echo esc_url($login_page);?>"><?php esc_html_e("Sign In","workreap_core");?></a></a></span>

						<?php } else { ?>
							<span><?php esc_html_e("Already have an account?","workreap_core");?>&nbsp;<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#loginpopup" class="wt-loginbtn-signup"><?php esc_html_e("Sign In","workreap_core");?></a></span>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		if( !empty( $post_class ) && $post_class === 'wt-model-reg1' && empty( $class )){
			$json['type'] 		= 'success';
			$json['html']		= ob_get_clean();
			wp_send_json($json);
		} else {
			echo ob_get_clean();
		}
		
	}
	add_action('workreap_registration_step_one', 'workreap_registration_step_one', 10,1);
	
	add_action('wp_ajax_workreap_registration_step_one', 'workreap_registration_step_one');
    add_action('wp_ajax_nopriv_workreap_registration_step_one', 'workreap_registration_step_one');
}

/**
 * @Registration Step Two
 * @return 
 */
if( !function_exists( 'workreap_registration_step_two' ) ){
	function workreap_registration_step_two(){		
		$login_register = '';
		$step_two_title = '';
		$step_two_desc  = '';
		$terms_link 	= '';
		$terms_text 	= '';
		$verify_user  	= 'verified';
		$hide_departments  	= '';
		$captcha_settings  	= '';
		
		$json			= array();
		$post_class		= !empty( $_POST['key'] ) ? 'wt-model-reg2' : 'wt-step-two';
		$hide_user_type	= '';
		$employer		= '';
		$freelancer		= 'checked';
		
		$signup_page_slug = workreap_get_signup_page_url('step', '1');	           

		if (function_exists('fw_get_db_settings_option')) {
            $login_register = fw_get_db_settings_option('enable_login_register');
            $step_two_title = fw_get_db_settings_option('step_two_title');
            $step_two_desc = fw_get_db_settings_option('step_two_desc');   
			$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
			$hide_departments = fw_get_db_settings_option('hide_departments', $default_value = null);
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
        }
		
		$remove_role	= !empty( $login_register['enable']['remove_role_registration'] ) ? $login_register['enable']['remove_role_registration'] : 'both';
		$default_role	= !empty( $login_register['enable']['default_role'] ) ? $login_register['enable']['default_role'] : 'freelancer';
		
		if( empty( $step_two_title ) ){
        	$step_two_title = esc_html__('Join For a Good Start', 'workreap_core');
        }              
		$hide_location	= 'no';
        if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {
            $terms_link 	= !empty( $login_register['enable']['registration']['enable']['terms_link'] ) ? $login_register['enable']['registration']['enable']['terms_link'] : '';
            $terms_link 	= !empty( $terms_link ) ? get_the_permalink($terms_link[0]) : '';
			$term_text 		= !empty( $login_register['enable']['registration']['enable']['term_text'] ) ? $login_register['enable']['registration']['enable']['term_text'] : esc_html__('Agree our terms and conditions', 'workreap_core');
			$hide_location 	= !empty( $login_register['enable']['registration']['enable']['hide_loaction'] ) ? $login_register['enable']['registration']['enable']['hide_loaction'] : 'no';
		}
		
		if( !empty( $post_class ) && $post_class === 'wt-model-reg2'){
			$signup_page_slug	= '#';
		} else{
			$signup_page_slug	= $signup_page_slug;
		}
		
		ob_start(); ?>		
		<div class="wt-registerformmain">
			<div class="wt-registerhead">
				<div class="wt-title">
					<h3><?php echo esc_attr( $step_two_title ); ?></h3>
				</div>
				<?php if( !empty( $step_two_desc ) ) { ?>
					<div class="description">
						<?php echo do_shortcode( $step_two_desc ); ?>
					</div>
				<?php } ?>
			</div>
			<div class="wt-joinforms">
				<ul class="wt-joinsteps">
					<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
					<li class="wt-active"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('02', 'workreap_core'); ?></a></li>
					<li><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('03', 'workreap_core'); ?></a></li>
				</ul>
				<p><?php esc_html_e('Please try to add strong password, minimum 6 characters', 'workreap_core' ); ?></p>
				<form class="wt-formtheme wt-formregister wt-formregister-step-two">
					<fieldset class="wt-registerformgroup">
						<?php if( !empty($hide_location) && $hide_location == 'no' ){?>
							<div class="form-group">
								<?php do_action('worktic_get_locations_list','location',''); ?>	
							</div>
						<?php } ?>
						<div class="form-group form-group-half toolip-wrapo">
							<input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password*', 'workreap_core' ); ?>">
							<?php do_action('workreap_get_tooltip','element','password');?>
						</div>
						<div class="form-group form-group-half">
							<input type="password" name="verify_password" class="form-control" placeholder="<?php esc_html_e('Retype Password*', 'workreap_core' ); ?>">
						</div>
					</fieldset>
					<fieldset class="wt-formregisterstart" style="<?php echo esc_attr( $hide_user_type );?>">
						<div class="wt-title wt-formtitle"><h4><?php esc_html_e('Start as :', 'workreap_core' ); ?></h4></div>
						<ul class="wt-accordionhold wt-formaccordionhold accordion">
							<?php if(!empty($remove_role) && $remove_role !== 'freelancers'){?>
								<li>
									<div class="wt-accordiontitle wt-ragister-option">
										<span class="wt-radio">
											<input id="wt-freelancer" class="register-radio" type="radio" name="user_type" value="freelancer" <?php checked( $default_role, 'freelancer' ); ?>>
											<label for="wt-freelancer"><?php esc_html_e('Freelancer', 'workreap_core'); ?><span><?php esc_html_e(' (Signup as freelancer &amp; get hired)', 'workreap_core'); ?></span></label>
										</span>
									</div>
								</li>
							<?php }?>
							<?php if(!empty($remove_role) && $remove_role !== 'employers'){?>
								<li>
									<div class="wt-accordiontitle wt-ragister-option">
										<span class="wt-radio">
											<input id="wt-company" class="register-radio" type="radio" name="user_type" value="employer" <?php checked( $default_role, 'employer' ); ?>>
											<label for="wt-company"><?php esc_html_e('Employer ', 'workreap_core'); ?><span> <?php esc_html_e('(Signup as company/service seeker &amp; post jobs)', 'workreap_core' ); ?></span></label>
										</span>
									</div>
									<?php if( !empty( $hide_departments ) && $hide_departments === 'no' ){?>
										<div class="wt-accordiondetails wt-emp-register">
											<div class="wt-radioboxholder">
												<div class="wt-title">
													<h4><?php esc_html_e('Your Department?', 'workreap_core'); ?></h4>
												</div>
												<?php do_action('worktic_get_departments_list'); ?>				
											</div>	
											<div class="wt-radioboxholder">
												<div class="wt-title">
													<h4><?php esc_html_e('No. of employees you have', 'workreap_core'); ?></h4>
												</div>
												<?php do_action('workreap_print_employees_list'); ?>
											</div>								
										</div>
									<?php }?>
								</li>
							<?php }?>
							
						</ul>
					</fieldset>
					<fieldset class="wt-termsconditions">
						<?php if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
							<div class="domain-captcha form-group">
								<div id="recaptcha_signup"></div>
							</div>
						<?php }?>
						<div class="wt-checkboxholder">								
							<span class="wt-checkbox">
								<input id="termsconditions" type="checkbox" name="termsconditions" value="checked">
								<label for="termsconditions"><?php echo esc_html( $term_text ); ?>
									<?php if( !empty( $terms_link ) ) { ?>
										<a target="_blank" href="<?php echo esc_url( $terms_link ); ?>"><?php esc_html_e('Terms & Conditions', 'workreap_core'); ?></a>
									<?php } ?>
								</label>
							</span>
							<a href="<?php echo esc_attr( $signup_page_slug ); ?>" class="wt-btn wt-back-to-one"><?php esc_html_e('Back', 'workreap_core'); ?></a>	
							<a href="#" class="wt-btn <?php echo esc_attr( $post_class );?>"><?php esc_html_e('Continue', 'workreap_core'); ?></a>								
						</div>
					</fieldset>					
				</form>
			</div>
		</div>		
		<?php
		if( !empty( $post_class ) && $post_class === 'wt-model-reg2'){
			$json['type'] 		= 'success';
			$json['html']		= ob_get_clean();
			wp_send_json($json);
		} else {
			echo ob_get_clean();
		}
	}
	add_action('workreap_registration_step_two', 'workreap_registration_step_two', 10);
	add_action('wp_ajax_workreap_registration_step_two', 'workreap_registration_step_two');
    add_action('wp_ajax_nopriv_workreap_registration_step_two', 'workreap_registration_step_two');
}

/**
 * @Social Registration Step Two
 * @return 
 */
if( !function_exists( 'workreap_social_registeration' ) ){
	function workreap_social_registeration($request='',$show=''){		
		$login_register = '';
		$step_two_title = '';
		$step_two_desc  = '';
		$terms_link 	= '';
		$terms_text 	= '';
		$display		= '';
		
		if( !empty( $show ) && $show === 'no' ) {
			$display		= 'display:none;';
		}
		
		$submit_btn_class	= !empty( $request ) && $request === 'social_login' ? 'social-step-two-poup' : 'social-step-two';
		if (function_exists('fw_get_db_settings_option')) {
            $login_register = fw_get_db_settings_option('enable_login_register');
            $step_two_title = fw_get_db_settings_option('social_title');
            $step_two_desc = fw_get_db_settings_option('social_desc');  
			$hide_departments = fw_get_db_settings_option('hide_departments', $default_value = null);
        }
       	
		$default_role	= !empty( $login_register['enable']['default_role'] ) ? $login_register['enable']['default_role'] : 'freelancer';
		
		if( empty( $step_two_title ) ){
        	$step_two_title = esc_html__('Join For a Good Start', 'workreap_core');
        }              

        if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {
            $terms_link = !empty( $login_register['enable']['registration']['enable']['terms_link'] ) ? $login_register['enable']['registration']['enable']['terms_link'] : '';
            $terms_link = !empty( $terms_link ) ? get_the_permalink($terms_link[0]) : '';
            $term_text = !empty( $login_register['enable']['registration']['enable']['term_text'] ) ? $login_register['enable']['registration']['enable']['term_text'] : esc_html__('Agree our terms and conditions', 'workreap_core');
        }
		
		ob_start(); ?>		
		<div class="wt-registerformmain">
			<?php if( !empty( $step_two_title ) || !empty( $step_two_desc ) ) { ?>
				<div class="wt-registerhead">
					<?php if( !empty( $step_two_title ) ){?>
						<div class="wt-title">
							<h3><?php echo esc_attr( $step_two_title ); ?></h3>
						</div>
					<?php }?>
					<?php if( !empty( $step_two_desc ) ) { ?>
						<div class="description">
							<?php echo do_shortcode( $step_two_desc ); ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="wt-joinforms">
				<ul class="wt-joinsteps">
					<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
					<li class="wt-active"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('02', 'workreap_core'); ?></a></li>
				</ul>
				<form class="wt-formtheme wt-formregister wt-formregister-step-two">
					<fieldset class="wt-registerformgroup">
						<div class="form-group">
							<?php do_action('worktic_get_locations_list','location',''); ?>	
						</div>
						<div class="form-group form-group-half toolip-wrapo">
							<input type="password" name="password" aautocomplete="off" class="form-control" placeholder="<?php esc_html_e('Password*', 'workreap_core' ); ?>">
							<?php do_action('workreap_get_tooltip','element','password');?>
						</div>
						<div class="form-group form-group-half">
							<input type="password" name="verify_password" autocomplete="off" class="form-control" placeholder="<?php esc_html_e('Retype Password*', 'workreap_core' ); ?>">
						</div>
					</fieldset>
					<fieldset class="wt-formregisterstart" style="<?php echo esc_attr($display);?>">
						<div class="wt-title wt-formtitle"><h4><?php esc_html_e('Start as :', 'workreap_core' ); ?></h4></div>
						<ul class="wt-accordionhold wt-formaccordionhold accordion">
							<li>
								<div class="wt-accordiontitle wt-ragister-social">
									<span class="wt-radio">
										<input id="wt-freelancer" type="radio" name="user_type" value="freelancer" <?php checked( $default_role, 'freelancer' ); ?>>
										<label for="wt-freelancer"><?php esc_html_e('Freelancer', 'workreap_core'); ?><span><?php esc_html_e(' (Signup as freelancer &amp; get hired)', 'workreap_core'); ?></span></label>
									</span>
								</div>
							</li>
							<li>
								<div class="wt-accordiontitle wt-ragister-social">
									<span class="wt-radio">
										<input id="wt-company" type="radio" name="user_type" value="employer" <?php checked( $default_role, 'employer' ); ?>>
										<label for="wt-company"><?php esc_html_e('Employer ', 'workreap_core'); ?><span> <?php esc_html_e('(Signup as company/service seeker &amp; post jobs)', 'workreap_core' ); ?></span></label>
									</span>
								</div>
								<?php if( !empty( $hide_departments ) && $hide_departments === 'no' ){?>
									<div class="wt-accordiondetails wt-emp-register">
										<div class="wt-radioboxholder">
											<div class="wt-title">
												<h4><?php esc_html_e('Your Department?', 'workreap_core'); ?></h4>
											</div>
											<?php do_action('worktic_get_departments_list'); ?>				
										</div>	
										<div class="wt-radioboxholder">
											<div class="wt-title">
												<h4><?php esc_html_e('No. of employees you have', 'workreap_core'); ?></h4>
											</div>
											<?php do_action('workreap_print_employees_list'); ?>
										</div>								
									</div>
								<?php }?>
							</li>
							
						</ul>
					</fieldset>
					<fieldset class="wt-termsconditions">
						<div class="wt-checkboxholder">								
							<span class="wt-checkbox">
								<input id="termsconditions" type="checkbox" name="termsconditions" value="checked">
								<label for="termsconditions"><?php echo esc_attr( $term_text ); ?>
									<?php if( !empty( $terms_link ) ) { ?>
										<a target="_blank" href="<?php echo esc_url( $terms_link ); ?>"><?php esc_html_e('Terms & Conditions', 'workreap_core'); ?></a>
									<?php } ?>
								</label>
							</span>	
							<a href="#" class="wt-btn <?php echo esc_attr( $submit_btn_class );?>"><?php esc_html_e('Continue', 'workreap_core'); ?></a>								
						</div>
					</fieldset>					
				</form>
			</div>
		</div>		
		<?php
		if( !empty( $request ) && $request === 'social_login' ) {
			return ob_get_clean();
		} else {
			echo ob_get_clean();	
		}
	}
	add_action('workreap_social_registeration', 'workreap_social_registeration', 10,2);
}


/**
 * @Registration Step Four
 * @return 
 */
if( !function_exists( 'workreap_registration_step_four' ) ){
	function workreap_registration_step_four(){
		global $current_user;
		$user_role = apply_filters('workreap_get_user_role', $current_user->ID);		
		$step_four_title 	= '';
		$step_four_desc  	= '';		
		$verify_user  		= 'verified';

		if (function_exists('fw_get_db_settings_option')) {                    
            $step_four_title 	= fw_get_db_settings_option('step_four_title');
            $step_four_desc 	= fw_get_db_settings_option('step_four_desc');   
			$verify_user 		= fw_get_db_settings_option('verify_user', $default_value = null);
        }                    

        if( empty( $step_four_title ) ){
        	$step_four_title = esc_html__('Congratulations', 'workreap_core');
        }

       	if( $user_role == 'employers' ){  
			$message_content = esc_html__('Would you like to add your first Job?', 'workreap_core');
		} else {
			$message_content = esc_html__('Complete your profile and get hired.', 'workreap_core');
		}
		
		if( !empty($current_user->roles) && in_array('administrator', $current_user->roles) ){ 
			return;
		}
		
		ob_start(); ?>
		<div class="row justify-content-md-center">
			<div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">		
				<div class="wt-registerformmain wt-registerformhold wt-registerformmain">
					<div class="wt-registerhead">
						<div class="wt-title">
							<h3><?php echo esc_attr( $step_four_title ); ?></h3>
						</div>
						<?php if( !empty( $step_four_desc ) ) { ?>
							<div class="description">
								<?php echo do_shortcode( $step_four_desc ); ?>
							</div>
						<?php } ?>
					</div>
					<div class="wt-joinforms">
						<ul class="wt-joinsteps">
							<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
							<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
							<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
						</ul>
					</div>				
					<div class="wt-gotodashboard">
						<span><?php echo esc_attr( $message_content ); ?></span>
						<a class="wt-btn" href="<?php echo workreap_registration_redirect(); ?>"><?php esc_html_e('Go to dashboard','workreap_core');?></a>
					</div>
				</div>	
			</div>	
		</div>			
		<?php
		echo ob_get_clean();
		
	}
	add_action('workreap_registration_step_four', 'workreap_registration_step_four', 10);
}

/**
 * @Registration Step Four
 * @return 
 */
if( !function_exists( 'workreap_registration_step_four_filter' ) ){
	function workreap_registration_step_four_filter($type='return'){
		global $current_user;
		$user_role = apply_filters('workreap_get_user_role', $current_user->ID);		
		$step_four_title 	= '';
		$step_four_desc  	= '';		
		$verify_user  		= 'verified';
		$post_class			= !empty( $_POST['key'] ) ? $_POST['key'] : '';
		
		if (function_exists('fw_get_db_settings_option')) {                    
            $step_four_title 	= fw_get_db_settings_option('step_four_title');
            $step_four_desc 	= fw_get_db_settings_option('step_four_desc');   
			$verify_user 		= fw_get_db_settings_option('verify_user', $default_value = null);
        }                    

        if( empty( $step_four_title ) ){
        	$step_four_title = esc_html__('Congratulations', 'workreap_core');
        }

       	if( $user_role == 'employers' ){  
			$message_content = esc_html__('Would you like to add your first Job?', 'workreap_core');
		} else {
			$message_content = esc_html__('Complete your profile and get hired.', 'workreap_core');
		}
		
		ob_start(); ?>
		<div class="row justify-content-md-center">
			<div class="wt-registerhead">
				<div class="wt-title">
					<h3><?php echo esc_attr( $step_four_title ); ?></h3>
				</div>
				<?php if( !empty( $step_four_desc ) ) { ?>
					<div class="description">
						<?php echo do_shortcode( $step_four_desc ); ?>
					</div>
				<?php } ?>
			</div>
			<div class="wt-joinforms">
				<ul class="wt-joinsteps">
					<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
					<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
					<li class="wt-done-next"><a href="#" onclick="event_preventDefault(event);"><i class="fa fa-check"></i></a></li>
				</ul>
			</div>				
			<div class="wt-gotodashboard">
				<span><?php echo esc_attr( $message_content ); ?></span>
				<a class="wt-btn" href="<?php echo workreap_registration_redirect(); ?>"><?php esc_html_e('Go to dashboard','workreap_core');?></a>
			</div>	
		</div>			
		<?php
		if( !empty( $post_class ) && $post_class === 'post'){
			$json['type'] 		= 'success';
			$json['html']		= ob_get_clean();
			wp_send_json($json);
		} else {
			return ob_get_clean();
		}
		
	}
	add_filter('workreap_registration_step_four_filter', 'workreap_registration_step_four_filter',10,1);
	add_action('wp_ajax_workreap_registration_step_four_filter', 'workreap_registration_step_four_filter');
    add_action('wp_ajax_nopriv_workreap_registration_step_four_filter', 'workreap_registration_step_four_filter');
}



/**
 * @Registration process Step One
 * @return 
 */
if( !function_exists( 'workreap_process_registration_step_one' ) ){
	function workreap_process_registration_step_one(){
		session_start(array('user_data'));
		
		//Check Security
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
            wp_send_json( $json );
        }
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		//Validation
		$validations = array(
            'gender' 		=> esc_html__('Gender field is required', 'workreap_core'),
			'username' 		=> esc_html__('Username is required', 'workreap_core'),
            'first_name' 	=> esc_html__('First Name is required', 'workreap_core'),
            'last_name' 	=> esc_html__('Last Name is required.', 'workreap_core'),
            'email'  		=> esc_html__('Email field is required.', 'workreap_core'),            
        );
		
		$phone_setting 		= '';
		$phone_mandatory	= '';
		$phone_option_reg	= '';
		if (function_exists('fw_get_db_settings_option')) {
			$phone_option		= fw_get_db_settings_option('phone_option');
			$remove_username 		= fw_get_db_settings_option('enable_login_register');
			$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
			$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
			$phone_option_reg	= !empty($phone_option['enable']['phone_option_registration']) ? $phone_option['enable']['phone_option_registration'] : '';
		}

		if( !empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_mandatory) && $phone_mandatory == 'enable'){
			$validations['user_phone_number']	= esc_html__('Phone number is required', 'workreap_core');
		}
		
		
		if(!empty($remove_username['gadget'])
		   && !empty($remove_username['enable']['remove_username']) 
		   && $remove_username['gadget'] === 'enable' 
		   && $remove_username['enable']['remove_username'] === 'yes'){
			unset($validations['username']);
		}

		$gender_list	= apply_filters('workreap_gender_types',array());
		if( empty( $gender_list ) ){
			unset($validations['gender']);
		}
		
        foreach ( $validations as $key => $value ) {
            if ( empty( $_POST[$key] ) ) {
                $json['type'] = 'error';
                $json['message'] = $value;
                echo json_encode($json);
                die;
            }

            //Validate email address
            if ( $key === 'email' ) {
                if ( !is_email( $_POST['email'] ) ) {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('Please add a valid email address.', 'workreap_core');
                    echo json_encode($json);
                	die;
            	}
       		}	
       	}	
		
		extract($_POST);
		
		$email		=  !empty( $email ) ? $email : '';
		$gender		=  !empty( $gender ) ? $gender : '';
		$first_name	=  !empty( $first_name ) ? $first_name : '';
		$last_name	=  !empty( $last_name ) ? $last_name : '';
		$username	=  !empty( $username ) ? $username : $email;
		
		$username_exist 	 = username_exists( $username );
       	$user_exists 		 = email_exists( $email );
		
		if( $username_exist ){
       		$json['type'] = 'error';
            $json['message'] = esc_html__('Username already registered', 'workreap_core');
            echo json_encode($json);
        	die;
       	}
		
		//check exists
       	if( $user_exists ){
       		$json['type'] = 'error';
            $json['message'] = esc_html__('This email already registered', 'workreap_core');
            echo json_encode($json);
        	die;
       	}
		
		$user_data							= array();
       	//Add user data to session
		if( !empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_setting) && $phone_setting == 'enable' ) {
			$user_phone_number  							= !empty( $_POST['user_phone_number'] ) ? $_POST['user_phone_number'] : '';
			$user_data['register']['user_phone_number'] 	= $user_phone_number;
		}
		$user_data['register']['gender'] 		= $gender;
		$user_data['register']['first_name'] 	= $first_name;
		$user_data['register']['last_name'] 	= $last_name;
		$user_data['register']['email'] 		= $email;
		$user_data['register']['username'] 		= $username;
		
		$_SESSION['user_data']	= $user_data;

		//Redirect URL		
     	$signup_page_slug = workreap_get_signup_page_url('step', '2');                   
        
		$json['type'] 	 = 'success';
        $json['message'] = esc_html__('A bit more details and its done', 'workreap_core');
        $json['retrun_url'] = htmlspecialchars_decode($signup_page_slug);
        echo json_encode($json);
        die;

	}
	add_action('wp_ajax_workreap_process_registration_step_one', 'workreap_process_registration_step_one');
    add_action('wp_ajax_nopriv_workreap_process_registration_step_one', 'workreap_process_registration_step_one');
}

/**
 * @Registration process Step Two
 * @return 
 */
if( !function_exists( 'workreap_process_registration_step_two' ) ){
	function workreap_process_registration_step_two(){
		session_start(array('user_data'));
		
		//Check Security
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security checks failed', 'workreap_core');
            wp_send_json( $json );
        }
		
		$verify_user	= '';
		if ( function_exists('fw_get_db_post_option' )) {
			$verify_user 	= fw_get_db_settings_option('verify_user', $default_value = null);
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
                    $json['message'] = esc_html__('An error occurred, please try again later.', 'workreap_core');
                    wp_send_json($json);
                } else {
					$json['type'] = 'error';
                    $json['message'] = esc_html__('Wrong reCaptcha. Please verify first.', 'workreap_core');
                    wp_send_json($json);
                }
            } else {
                wp_send_json(array('type' => 'error', 'message' => esc_html__('Please enter reCaptcha!', 'workreap_core')));
            }
        }
		
		//Validation
		$validations = array(
            'location' 			=> esc_html__('Location field is required', 'workreap_core'),
            'password' 			=> esc_html__('Password field is required', 'workreap_core'),
            'verify_password' 	=> esc_html__('Verify Password field is required.', 'workreap_core'),
            'user_type'  		=> esc_html__('User type field is required.', 'workreap_core'),            
            'termsconditions'  	=> esc_html__('You should agree to terms and conditions.', 'workreap_core'),     
		);
		
		$hide_location	= 'no';
		if (function_exists('fw_get_db_settings_option')) {
			$login_register = fw_get_db_settings_option('enable_login_register');
			$hide_location 	= !empty( $login_register['enable']['registration']['enable']['hide_loaction'] ) ? $login_register['enable']['registration']['enable']['hide_loaction'] : 'no';
			if( !empty($hide_location) && $hide_location == 'yes' ){
				unset($validations['location']);
			}
		}
		
        foreach ( $validations as $key => $value ) {
            if ( empty( $_POST[$key] ) ) {
                $json['type'] = 'error';
                $json['message'] = $value;
                echo json_encode($json);
                die;
            }     
			
			if ($key === 'password') {
				do_action('workreap_strong_password_validation',$_POST[$key]);
            } 

            if ($key === 'verify_password') {
                if ( $_POST['password'] != $_POST['verify_password']) {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('Password does not match.', 'workreap_core');
                    echo json_encode($json);
                    die;
                }
            } 

            if( $key == 'user_type'){
            	if( $_POST['user_type'] == 'company' ){
            		$employees  = !empty( $_POST['employees'] ) ? sanitize_text_field( $_POST['employees'] ) : '';
            		$department = !empty( $_POST['department'] ) ? sanitize_text_field( $_POST['department'] ) : '';
            		if( empty( $employees ) || empty( $department ) ){
            			$json['type'] = 'error';
	                    $json['message'] = esc_html__('Employee and department fields are required.', 'workreap_core');
	                    echo json_encode($json);
	                    die;
            		}
            	}
            }                 
       	}	

		$phone_setting 		= '';
		$phone_mandatory	= '';
		$phone_option_reg	= '';
		if (function_exists('fw_get_db_settings_option')) {
			$phone_option		= fw_get_db_settings_option('phone_option');
			$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
			$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
			$phone_option_reg	= !empty($phone_option['enable']['phone_option_registration']) ? $phone_option['enable']['phone_option_registration'] : '';
		}
       	//Get Data
       	$location   = !empty( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : '';
       	$password  	= !empty( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
       	$user_type 	= !empty( $_POST['user_type'] ) ? sanitize_text_field( $_POST['user_type'] ) : '';
       	$department = !empty( $_POST['department'] ) ? sanitize_text_field( $_POST['department'] ) : '';
       	$employees  = !empty( $_POST['employees'] ) ? sanitize_text_field( $_POST['employees'] ) : '';
		$termsconditions  = !empty( $_POST['termsconditions'] ) ? sanitize_text_field( $_POST['termsconditions'] ) : '';

       	//Set User Role
       	$db_user_role = 'employers';
       	if( $user_type === 'freelancer' ){
       		$db_user_role = 'freelancers';
       	} else {
       		$db_user_role = 'employers';
       	}
				
		$user_data	= isset($_SESSION['user_data']) ? $_SESSION['user_data'] : array();

       	//Get user data from session
       	$username 	= !empty( $user_data['register']['username'] ) ? sanitize_text_field( $user_data['register']['username'] ) : '';
		$first_name = !empty( $user_data['register']['first_name'] ) ? sanitize_text_field( $user_data['register']['first_name'] ) : '';
       	$last_name 	= !empty( $user_data['register']['last_name'] ) ? sanitize_text_field( $user_data['register']['last_name'] ) : '';
       	$gender 	= !empty( $user_data['register']['gender'] ) ? sanitize_text_field( $user_data['register']['gender'] ) : '';
       	$email 		= !empty( $user_data['register']['email'] ) ? sanitize_text_field( $user_data['register']['email'] ) : '';
		
		//Session data validation
		if( empty( $username ) 
		   || empty( $first_name ) 
		   || empty( $last_name ) 
		   || empty( $email ) 
		 ) {

			$signup_page_slug = workreap_get_signup_page_url('step', '1');		                

			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'All the fields are required added in first step', 'workreap_core' );
	        $json['retrun_url'] = htmlspecialchars_decode($signup_page_slug);
			echo json_encode( $json );
			die;
		}		

		//User Registration
		$random_password = $password;
		$full_name 		 = $first_name.' '.$last_name;
		$user_nicename   = sanitize_title( $full_name );
		
		$userdata = array(
			'user_login'  		=>  $username,
			'user_pass'    		=>  $random_password,
			'user_email'   		=>  $email,  
			'user_nicename'   	=>  $user_nicename,  
			'display_name'   	=>  $full_name,  
		);
		
        $user_identity 	 = wp_insert_user( $userdata );
		
        if ( is_wp_error( $user_identity ) ) {
            $json['type'] = "error";
            $json['message'] = esc_html__("User already exists. Please try another one.", 'workreap_core');
            wp_send_json($json);
        } else {
        	global $wpdb;
            wp_update_user( array('ID' => esc_sql( $user_identity ), 'role' => esc_sql( $db_user_role ), 'user_status' => 1 ) );

            $wpdb->update(
                    $wpdb->prefix . 'users', array('user_status' => 1), array('ID' => esc_sql($user_identity))
            );

			unset($_SESSION['user_data']);
            
			update_user_meta( $user_identity, 'first_name', $first_name );
            update_user_meta( $user_identity, 'last_name', $last_name );
			update_user_meta( $user_identity, 'gender', ( $gender ) );
			update_user_meta( $user_identity, 'termsconditions', esc_html( $termsconditions ) );     

			update_user_meta($user_identity, 'show_admin_bar_front', false);
            update_user_meta($user_identity, 'full_name', ($full_name));

            update_user_meta( $user_identity, '_is_verified', 'no' );
			
			//verification link
			$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
			update_user_meta( $user_identity, 'confirmation_key', $key_hash);
			$protocol = is_ssl() ? 'https' : 'http';
			$verify_link = esc_url(add_query_arg(array('key' => $key_hash.'&verifyemail='.$email), home_url('/', $protocol)));
			
			//Create Post
			$user_post = array(
                'post_title'    => wp_strip_all_tags( $full_name ),
                'post_status'   => 'publish',
                'post_author'   => $user_identity,
                'post_type'     => $db_user_role,
            );

            $post_id    = wp_insert_post( $user_post );
			
            if( !is_wp_error( $post_id ) ) {

				$shortname_option	= '';
                if( function_exists('fw_get_db_settings_option')  ){
                    $shortname_option	= fw_get_db_settings_option('shortname_option', $default_value = null);
                }
				
				if(!empty($shortname_option) && $shortname_option === 'enable' ){
					$shor_name			= workreap_get_username($user_identity);
					$shor_name_array	= array(
											'ID'        => $post_id,
											'post_name'	=> sanitize_title($shor_name)
										);
					wp_update_post($shor_name_array);
				}

				$fw_options = array();
				
				if( !empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_setting) && $phone_setting == 'enable' ) {
					$fw_options['user_phone_number'] 	= !empty( $user_data['register']['user_phone_number'] ) ? ( $user_data['register']['user_phone_number'] ) : '';
				}
				
				//Update user linked profile
				update_user_meta( $user_identity, '_linked_profile', $post_id );
				
				if( !empty($hide_location) && $hide_location == 'no' ){
					wp_set_post_terms( $post_id, $location, 'locations' );
				}
				
				update_post_meta( $post_id, '_is_verified', 'no' );
				update_post_meta( $post_id, '_hourly_rate_settings', 'off' );
				
            	if( $db_user_role == 'employers' ){
					
					update_post_meta($post_id, '_user_type', 'employer');
            		update_post_meta($post_id, '_employees', $employees);            		
					update_post_meta($post_id, '_followers', '');
					
					//update department
					if( !empty( $department ) ){
						$department_term = get_term_by( 'term_id', $department, 'department' );
						if( !empty( $department_term ) ){
							wp_set_post_terms( $post_id, $department, 'department' );
						}
					}

					//Fw Options
					$fw_options['department']         = array( $department );
					$fw_options['no_of_employees']    = $employees;

            	} elseif( $db_user_role == 'freelancers' ){
					update_post_meta($post_id, '_user_type', 'freelancer');
            		update_post_meta($post_id, '_perhour_rate', '');
            		update_post_meta($post_id, 'rating_filter', 0);
            		update_post_meta($post_id, '_freelancer_type', 'rising_talent');         		           		
            		update_post_meta($post_id, '_featured_timestamp', 0); 
					update_post_meta($post_id, '_english_level', 'basic');
					update_post_meta($post_id, '_have_avatar', 0);
					update_post_meta($post_id, '_profile_health_filter', 0); 
					
					//extra data in freelancer
					update_post_meta($post_id, '_gender', $gender);
					$fw_options['_perhour_rate']    = '';
					$fw_options['gender']    		= $gender;
            	}
				
				//Set country for unyson
				$locations = get_term_by( 'slug', $location, 'locations' );
				$location_data = array();
				if( !empty( $locations ) ){
					$location_data[0] = $locations->term_id;
					wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
				}
				
				if ( function_exists('fw_get_db_post_option' )) {
					$dir_latitude 	= fw_get_db_settings_option('dir_latitude');
            		$dir_longitude 	= fw_get_db_settings_option('dir_longitude');
					$verify_user 	= fw_get_db_settings_option('verify_user', $default_value = null);
				} else {
					$dir_latitude	= '';
					$dir_longitude	= '';
					$verify_user  	= 'verified';
				}
				
				//add extra fields as a null
				$tagline	= '';
				update_post_meta($post_id, '_tag_line', $tagline);
				update_post_meta($post_id, '_address', '');
				update_post_meta($post_id, '_latitude', $dir_latitude);
				update_post_meta($post_id, '_longitude', $dir_longitude);
				
				$fw_options['address']    	= '';
				$fw_options['longitude']    = $dir_longitude;
				$fw_options['latitude']    	= $dir_latitude;
				$fw_options['tag_line']     = $tagline;
				//end extra data
				
				//Update User Profile
				$fw_options['country']            = $location_data;
				fw_set_db_post_option($post_id, null, $fw_options);
				
				//update privacy settings
				$settings		 = workreap_get_account_settings($user_type);
				if( !empty( $settings ) ){
					foreach( $settings as $key => $value ){
						$val = $key === '_profile_blocked' || $key === '_hourly_rate_settings'? 'off' : 'on';
						update_post_meta($post_id, $key, $val);
					}
				}

				
				update_post_meta($post_id, '_linked_profile', $user_identity);
				
				//update trial package
				$user_type						= workreap_get_user_type( $user_identity );
				$freelancer_package_id			= workreap_get_package_type( 'package_type','trail_freelancer');
				$employer_package_id			= workreap_get_package_type( 'package_type','trail_employer');

				if( $user_type === 'employer' && !empty($employer_package_id) ) {
					workreap_update_pakage_data( $employer_package_id ,$user_identity,'' );
				} else if( $user_type === 'freelancer' && !empty($freelancer_package_id) ) {
					workreap_update_pakage_data( $freelancer_package_id ,$user_identity,'' );
				}
				
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				
            	//Send email to users
            	if (class_exists('Workreap_Email_helper')) {
					
					$emailData = array();
					$emailData['name'] 				= $first_name;
					$emailData['password'] 			= $random_password;
					$emailData['email'] 			= $email;
					$emailData['phone'] 			= !empty($fw_options['user_phone_number']) ? $fw_options['user_phone_number'] : '';
					$emailData['verification_link'] = $verify_link;
					$emailData['site'] = $blogname;
					
					//Welcome Email
					if( $db_user_role === 'employers' ){
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_employer_email($emailData);
						}
					} else if( $db_user_role === 'freelancers' ){
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_freelacner_email($emailData);
						}
					}
					
					//Send code
					if( isset( $verify_user ) && $verify_user === 'verified' ){
						$json['verify_user'] 			= 'verified';
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_verification($emailData);
						}
					} else{
						$json['verify_user'] 			= 'none';
					}
					
					//Send admin email
					if (class_exists('WorkreapRegisterEmail')) {
						$email_helper = new WorkreapRegisterEmail();
						$email_helper->send_admin_email($emailData);
					}
		        }		    
				
				//Push notification
				$push	= array();
				$push['receiver_id']	= $user_identity;
				$push['%name%']			= workreap_get_username($user_identity);
				$push['%email%']		= $email;
				$push['%password%']		= $random_password;
				$push['%site%']			= $blogname;
				$push['%verify_link%']	= $verify_link;
				$push['type']			= 'registration';
				
				$push['%replace_email%']		= $email;
				$push['%replace_password%']		= $random_password;
				$push['%replace_site%']			= $blogname;
				$push['%replace_verify_link%']	= $verify_link;

				if( $db_user_role == 'employers' ){
					do_action('workreap_user_push_notify',array($user_identity),'','pusher_employer_content',$push);
				}elseif( $db_user_role == 'freelancers' ){
					do_action('workreap_user_push_notify',array($user_identity),'','pusher_freelancers_content',$push);
				}
				
				do_action('workreap_user_push_notify',array($user_identity),'','pusher_verify_content',$push);

            } else {
            	$json['type'] = 'error';
                $json['message'] = esc_html__('Some error occurs, please try again later', 'workreap_core');                
                wp_send_json($json);
            }			
			
			//User Login
			$user_array = array();
			$user_array['user_login'] 	 = $email;
        	$user_array['user_password'] = $random_password;
			$status = wp_signon($user_array, false);
			
			if( isset( $verify_user ) && $verify_user === 'none' ){
				$json_message = esc_html__("Your account have been created. Please wait while your account is verified by the admin.", 'workreap_core');
			} else{
				$json_message = esc_html__("Your account have been created. Please verify your account, an email have been sent your email address.", 'workreap_core');
			}
			
			//Delete session data
	        unset( $_SESSION['user_data'] );

			//Redirect URL		
	     	$signup_page_slug = workreap_get_signup_page_url('step', '3');	      	               
	        
			$json['type'] 			= 'success';
	        $json['message'] 		= $json_message;
	        $json['retrun_url'] 	= htmlspecialchars_decode($signup_page_slug);
	        wp_send_json($json);
        }       

	}
	
	add_action('wp_ajax_workreap_process_registration_step_two', 'workreap_process_registration_step_two');
    add_action('wp_ajax_nopriv_workreap_process_registration_step_two', 'workreap_process_registration_step_two');
}

/**
 * @Migrate portfolios
 * @return 
 */
if( !function_exists( 'workreap_resend_verification' ) ){
	add_action('wp_ajax_workreap_resend_verification', 'workreap_resend_verification');
    add_action('wp_ajax_nopriv_workreap_resend_verification', 'workreap_resend_verification');
	function workreap_resend_verification(){
		global $current_user;
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
			wp_send_json( $json );
		}
		
		if (function_exists('fw_get_db_settings_option')) {           
			$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
        }

		if (class_exists('Workreap_Email_helper')) {
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
			update_user_meta( $current_user->ID, 'confirmation_key', $key_hash);
			$protocol = is_ssl() ? 'https' : 'http';
			$verify_link = esc_url(add_query_arg(array('key' => $key_hash.'&verifyemail='.$current_user->user_email), home_url('/', $protocol)));
			
			$emailData = array();
			$emailData['name'] 				= workreap_get_username($current_user->ID);
			$emailData['email'] 			= $current_user->user_email;
			$emailData['verification_link'] = $verify_link;
			$emailData['site'] 				= $blogname;

			//Send code
			if( isset( $verify_user ) && $verify_user === 'verified' ){
				$json['verify_user'] 			= 'verified';
				if (class_exists('WorkreapRegisterEmail')) {
					$email_helper = new WorkreapRegisterEmail();
					$email_helper->send_verification($emailData);
				}
				
				$json['type'] 			= 'success';
				$json['message'] 		= esc_html__('Verification emails has been sent', 'workreap_core');
				
			}else{
				$json['type'] = 'error';
                $json['message'] = esc_html__('Some error occurs, please try again later', 'workreap_core');                
			}
		}
		
		wp_send_json($json);
	}
}


/**
 * @Registration redirect
 * @return 
 */
if( !function_exists( 'workreap_login_redirect' ) ){
	function workreap_login_redirect($userID = ''){
		global $current_user;

		$user_id 	= !empty($userID) ? $userID : $current_user->ID;
		$json		= array();
		$user_type	= workreap_get_user_type( $user_id );
		if (function_exists('fw_get_db_settings_option')) {
			$redirect_registration = !empty($user_type) && $user_type == 'employer' ? fw_get_db_settings_option('redirect_employer_login') : fw_get_db_settings_option('redirect_login');
		}
		
		$redirect_registration	= !empty($redirect_registration) ?  $redirect_registration : 'settings';
		
		if($redirect_registration === 'settings'){
			return Workreap_Profile_Menu::workreap_profile_menu_link('profile', $user_id,true,'settings');
		} else if($redirect_registration === 'package'){
			if(apply_filters('workreap_is_listing_free',false,$user_id) === false ){
				return Workreap_Profile_Menu::workreap_profile_menu_link('package', $user_id,true);
			} else{
				return Workreap_Profile_Menu::workreap_profile_menu_link('profile', $user_id,true,'settings');
			}
			
		} else if($redirect_registration === 'insights'){
			return Workreap_Profile_Menu::workreap_profile_menu_link('insights', $user_id,true);
		} else if($redirect_registration === 'create_job'){
			return Workreap_Profile_Menu::workreap_profile_menu_link('post_job', $user_id,true);
		} else if($redirect_registration === 'home'){
			return esc_url(home_url('/'));
		} else{
			return Workreap_Profile_Menu::workreap_profile_menu_link('insights', $user_id,true);
		}
		
	}
}
/**
 * @Registration redirect
 * @return 
 */
if( !function_exists( 'workreap_registration_redirect' ) ){
	function workreap_registration_redirect($userID = ''){
		global $current_user;

		$user_id = !empty($userID) ? $userID : $current_user->ID;
		$json	= array();
		
		if (function_exists('fw_get_db_settings_option')) {
			$redirect_registration = fw_get_db_settings_option('redirect_registration');
		}
		
		$redirect_registration	= !empty($redirect_registration) ?  $redirect_registration : 'settings';
		
		if($redirect_registration === 'settings'){
			return Workreap_Profile_Menu::workreap_profile_menu_link('profile', $user_id,true,'settings');
		} else if($redirect_registration === 'package'){
			if(apply_filters('workreap_is_listing_free',false,$user_id) === false ){
				return Workreap_Profile_Menu::workreap_profile_menu_link('package', $user_id,true);
			} else{
				return Workreap_Profile_Menu::workreap_profile_menu_link('profile', $user_id,true,'settings');
			}
			
		} else if($redirect_registration === 'insights'){
			return Workreap_Profile_Menu::workreap_profile_menu_link('insights', $user_id,true);
		} else if($redirect_registration === 'home'){
			return esc_url(home_url('/'));
		} else{
			return Workreap_Profile_Menu::workreap_profile_menu_link('insights', $user_id,true);
		}
		
	}
}

/**
 * @Registration process Step Two
 * @return 
 */
if( !function_exists( 'workreap_process_social_registration_step_two' ) ){
	function workreap_process_social_registration_step_two(){
		global $current_user;
		$json	= array();
		
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
			wp_send_json( $json );
		}
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		$switch_account	= 'no';
		
		//Validation
		$validations = array(
            'location' 			=> esc_html__('Location field is required', 'workreap_core'),
            'password' 			=> esc_html__('Password field is required', 'workreap_core'),
            'verify_password' 	=> esc_html__('Verify Password field is required.', 'workreap_core'),
            'user_type'  		=> esc_html__('User type field is required.', 'workreap_core'),            
            'termsconditions'  	=> esc_html__('You should agree to terms and conditions.', 'workreap_core'),     
        );

        foreach ( $validations as $key => $value ) {
            if ( empty( $_POST[$key] ) ) {
                $json['type'] = 'error';
                $json['message'] = $value;
                echo json_encode($json);
                die;
            }     
			
			if ($key === 'password') {
				do_action('workreap_strong_password_validation',$_POST[$key]);
            }
			
			
            if ($key === 'verify_password') {
                if ( $_POST['password'] != $_POST['verify_password']) {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('Password does not match.', 'workreap_core');
                    echo json_encode($json);
                    die;
                }
            } 

            if( $key == 'user_type'){
            	if( $_POST['user_type'] == 'company' ){
            		$employees  = !empty( $_POST['employees'] ) ? sanitize_text_field( $_POST['employees'] ) : '';
            		$department = !empty( $_POST['department'] ) ? sanitize_text_field( $_POST['department'] ) : '';
            		if( empty( $employees ) || empty( $department ) ){
            			$json['type'] = 'error';
	                    $json['message'] = esc_html__('Employee and department fields are required.', 'workreap_core');
	                    echo json_encode($json);
	                    die;
            		}
            	}
            }                 
       	}	

       	//Get Data
       	$location   = !empty( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : '';
       	$password  	= !empty( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
       	$user_type 	= !empty( $_POST['user_type'] ) ? sanitize_text_field( $_POST['user_type'] ) : '';
       	$department = !empty( $_POST['department'] ) ? sanitize_text_field( $_POST['department'] ) : '';
       	$employees  = !empty( $_POST['employees'] ) ? sanitize_text_field( $_POST['employees'] ) : '';
		$termsconditions  = !empty( $_POST['termsconditions'] ) ? sanitize_text_field( $_POST['termsconditions'] ) : '';

		$user_identity 	 = $current_user->ID;
		$user_email		 = $current_user->user_email;
		
		//update shortner names
		$shortname_option	= '';
		if( function_exists('fw_get_db_settings_option')  ){
			$shortname_option	= fw_get_db_settings_option('shortname_option', $default_value = null);
			$social_verify_user	= fw_get_db_settings_option('social_verify_user');
		}
		
		//Set User Role
		$db_user_role = 'employers';
		if( $user_type === 'freelancer' ){
			$db_user_role = 'freelancers';
		} else {
			$db_user_role = 'employers';
		}
		
		//If not switch account
		if( !empty( $switch_account ) && $switch_account === 'no' ){
			//Update user password
			wp_set_password($password, $current_user->ID);
			
			//Get user data from session
			$username 	= $current_user->first_name;
			$first_name = $current_user->first_name;
			$last_name 	= '';
			$gender 	= '';
			$email 		= $current_user->user_email;	

			//User Registration
			$random_password = $password;
			$full_name 		 = $first_name;
			$user_nicename   = sanitize_title( $full_name );

            update_user_meta( $user_identity, '_is_verified', 'no' );
			update_user_meta( $user_identity, 'termsconditions', $termsconditions );
			
			//verification link
			$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
			update_user_meta( $user_identity, 'confirmation_key', $key_hash);
			$protocol = is_ssl() ? 'https' : 'http';
			$verify_link = esc_url(add_query_arg(array('key' => $key_hash.'&verifyemail='.$email), home_url('/', $protocol)));
			
			//Create Post
			$user_post = array(
				'post_title'    => wp_strip_all_tags( $full_name ),
				'post_status'   => 'publish',
				'post_author'   => $user_identity,
				'post_type'     => $db_user_role,
			);

			$post_id    = wp_insert_post( $user_post );

			if(!empty($shortname_option) && $shortname_option === 'enable' ){
				$shor_name			= workreap_get_username($user_identity);
				$shor_name_array	= array(
										'ID'        => $post_id,
										'post_name'	=> sanitize_title($shor_name)
									);
				wp_update_post($shor_name_array);
			}
			
			update_post_meta($post_id, '_linked_profile', $user_identity);
			
			//Send email to users
			if (class_exists('Workreap_Email_helper')) {
				if ( function_exists('fw_get_db_post_option' )) {
					$verify_user 	= fw_get_db_settings_option('social_verify_user', $default_value = null);
				} else {
					$verify_user  	= 'yes';
				}
				
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				$emailData = array();
				$emailData['name'] 				= $first_name;
				$emailData['password'] 			= $random_password;
				$emailData['email'] 			= $email;
				$emailData['verification_link'] = $verify_link;
				$emailData['site'] = $blogname;

				//Welcome Email
				if( $db_user_role === 'employers' ){

					if (class_exists('WorkreapRegisterEmail')) {
						$email_helper = new WorkreapRegisterEmail();
						$email_helper->send_employer_email($emailData);
					}
				} else if( $db_user_role === 'freelancers' ){
					if (class_exists('WorkreapRegisterEmail')) {
						$email_helper = new WorkreapRegisterEmail();
						$email_helper->send_freelacner_email($emailData);
					}
				}

				//Send code
				if( !empty( $verify_user ) && $verify_user === 'yes' ){
					$json['verify_user'] 			= 'verified';
					if (class_exists('WorkreapRegisterEmail')) {
						$email_helper = new WorkreapRegisterEmail();
						$email_helper->send_verification($emailData);
					}
				}else{
					update_user_meta( $user_identity, '_is_verified', 'yes' );
					update_post_meta( $post_id, '_is_verified', 'yes' );
				}

				//Send admin email
				if (class_exists('WorkreapRegisterEmail')) {
					$email_helper = new WorkreapRegisterEmail();
					$email_helper->send_admin_email($emailData);
				}
			}
			
		}

		if( !is_wp_error( $post_id ) ) {
			$fw_options = array();
			
			//update social profile
			$social_avatar	= !empty( $current_user->social_avatar ) ? $current_user->social_avatar :'';
			if (!empty($social_avatar)) {
				delete_post_thumbnail($post_id);
				set_post_thumbnail($post_id, $social_avatar);
			} 
			
			//Update user linked profile
			update_user_meta( $user_identity, '_linked_profile', $post_id );
			update_post_meta( $post_id, '_linked_profile', $user_identity );
			wp_set_post_terms( $post_id, $location, 'locations' );
			
			global $wpdb;

			$wp_user_object = new WP_User($current_user->ID);
			$wp_user_object->set_role($db_user_role);
			
			$wpdb->update(
					$wpdb->prefix . 'users', array('user_status' => 1), array('ID' => intval($user_identity))
			);
			
			if( $db_user_role == 'employers' ){

				update_post_meta($post_id, '_user_type', 'employer');
				update_post_meta($post_id, '_employees', $employees);            		
				update_post_meta($post_id, '_followers', '');

				//update department
				if( !empty( $department ) ){
					$department_term = get_term_by( 'term_id', $department, 'department' );
					if( !empty( $department_term ) ){
						wp_set_post_terms( $post_id, $department, 'department' );
					}
				}

				//Fw Options
				$fw_options['department']         = array( $department );
				$fw_options['no_of_employees']    = $employees;

			} elseif( $db_user_role == 'freelancers' ){
				update_post_meta($post_id, '_user_type', 'freelancer');
				update_post_meta($post_id, '_perhour_rate', '');
				update_post_meta($post_id, 'rating_filter', 0);
				update_post_meta($post_id, '_freelancer_type', 'rising_talent');         		           		
				update_post_meta($post_id, '_featured_timestamp', 0);
				update_post_meta($post_id, '_invitation_count', 0); 
				update_post_meta($post_id, '_english_level', 'basic');
				update_post_meta($post_id, '_have_avatar', 0); 
				update_post_meta($post_id, '_profile_health_filter', 0); 
				//extra data in freelancer
				update_post_meta($post_id, '_gender', '');
				$fw_options['_perhour_rate']    = '';
				$fw_options['gender']    		= '';
			}

			//Set country for unyson
			$locations = get_term_by( 'slug', $location, 'locations' );
			$location_data = array();
			if( !empty( $locations ) ){
				$location_data[0] = $locations->term_id;
				wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
			}

			if ( function_exists('fw_get_db_post_option' )) {
				$dir_latitude 	= fw_get_db_settings_option('dir_latitude');
				$dir_longitude 	= fw_get_db_settings_option('dir_longitude');
			} else {
				$dir_latitude	= '';
				$dir_longitude	= '';
			}

			//add extra fields as a null
			$tagline	= '';
			update_post_meta($post_id, '_tag_line', $tagline);
			update_post_meta($post_id, '_address', '');
			update_post_meta($post_id, '_latitude', $dir_latitude);
			update_post_meta($post_id, '_longitude', $dir_longitude);

			$fw_options['address']    	= '';
			$fw_options['longitude']    = $dir_longitude;
			$fw_options['latitude']    	= $dir_latitude;
			$fw_options['tag_line']     = $tagline;
			//end extra data

			//Update User Profile
			$fw_options['country']            = $location_data;
			fw_set_db_post_option($post_id, null, $fw_options);

			//update privacy settings
			$settings		 = workreap_get_account_settings($user_type);
			if( !empty( $settings ) ){
				foreach( $settings as $key => $value ){
					$val = $key === '_profile_blocked' ? 'off' : 'on';
					update_post_meta($post_id, $key, $val);
				}
			}
			
			//If not switch account
			if( !empty( $switch_account ) && $switch_account == 'no' ){
				if (!empty($wp_user_object->roles[0]) && $wp_user_object->roles[0] === 'freelancers') {
                    $user_type = 'freelancer';
                } else if (!empty($wp_user_object->roles[0]) && $wp_user_object->roles[0] === 'employers') {
                    $user_type = 'employer';
                } else{
                    $user_type = 'subscriber';
                }

				$freelancer_package_id			= workreap_get_package_type( 'package_type','trail_freelancer');
				$employer_package_id			= workreap_get_package_type( 'package_type','trail_employer');

				if( $user_type === 'employer' && !empty($employer_package_id) ) {
					workreap_update_pakage_data( $employer_package_id ,$current_user->ID,'' );
				} else if( $user_type === 'freelancer' && !empty($freelancer_package_id) ) {
					workreap_update_pakage_data( $freelancer_package_id ,$current_user->ID,'' );
				}
			}
			
			$user_array = array();
			$user_array['user_login'] 	 = $email;
        	$user_array['user_password'] = $random_password;
			$status = wp_signon($user_array, false);
			
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Your profile has been updated!', 'workreap_core');
			$json['retrun_url'] = workreap_registration_redirect();
			
	        wp_send_json($json);
			
		} else {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Some error occurs, please try again later', 'workreap_core');                
			wp_send_json($json);
		}			

	}
	add_action('wp_ajax_workreap_process_social_registration_step_two', 'workreap_process_social_registration_step_two');
    add_action('wp_ajax_nopriv_workreap_process_social_registration_step_two', 'workreap_process_social_registration_step_two');
}

/**
 * @Approve Profile 
 * @return 
 */
if( !function_exists( 'workreap_approve_profile' ) ){
	add_action('wp_ajax_workreap_approve_profile', 'workreap_approve_profile');
    add_action('wp_ajax_nopriv_workreap_approve_profile', 'workreap_approve_profile');
	function workreap_approve_profile(){
		//security check
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
			wp_send_json( $json );
		}
		
		$user_profile_id 	= !empty( $_POST['id'] ) ? $_POST['id'] : '';
		$type 				= !empty( $_POST['type'] ) ? $_POST['type'] : '';
		$user_id 			= !empty( $_POST['user_id'] ) ? $_POST['user_id'] : '';
		
		$is_verified 			= get_post_meta($user_profile_id, '_is_verified',true);
		if (isset($is_verified) && $is_verified === 'yes') {	
			$message_param = 'unapproved';
			
		} else {
			$message_param  = 'approved';
		}

		if(apply_filters('workreap_get_user_type', $user_id ) === 'freelancer'){
			//Prepare Params
			$params_array	= array();
			$params_array['user_profile_identity'] 	= (int) $user_profile_id;
			$params_array['profile_status'] = $message_param;
			$params_array['user_role'] 		= apply_filters('workreap_get_user_type', $user_id );
			$params_array['type'] 			= 'profile_approved';
			
			//child theme : update extra settings
			do_action('wt_process_profile_verified', $params_array);
			
			$update_post = array(
			  'ID'            => $user_profile_id,
			  'post_status'   => 'publish',
			);

			// Update the post into the database
			wp_update_post( $update_post );
		}
		
		$account_types_permissions	= '';
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$account_types_permissions	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
		} 

		if(!empty($user_id)){
			update_post_meta($user_profile_id,'_linked_profile', $user_id);
		}

		if( isset( $type ) && $type === 'reject' ){
			if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
				$switch_freelancer 	= get_user_meta( $user_id, '_linked_profile',true );
				update_post_meta($switch_employer,'_is_verified','no');
			}
			
			update_user_meta($user_id,'_is_verified', 'no');
			update_post_meta($user_profile_id,'_is_verified', 'no');
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Account has been disabled', 'workreap_core');
			
            wp_send_json($json);
		} else{
			$user_id   	= workreap_get_linked_profile_id($user_profile_id,'post');
			$user_id	= !empty($user_id) ?  intval($user_id) : '';
			$user_meta	= get_userdata($user_id);
			
			if( empty( $user_meta ) ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('No user exists', 'workreap_core');
				wp_send_json($json);
			}

			//Send verification email
			if (class_exists('Workreap_Published')) {
				if (class_exists('Workreap_Published')) {
					$email_helper = new Workreap_Published();
					
					update_post_meta($user_profile_id,'_is_verified', 'yes');
					update_user_meta($user_id,'_is_verified', 'yes');
					
					if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
						$switch_freelancer 	= get_user_meta( $user_id, '_linked_profile',true );
						
						update_post_meta($switch_freelancer,'_is_verified','yes');
					}

					$emailData 						= array();
					$name  							= workreap_get_username( '' ,$user_profile_id );
					$emailData['name'] 				= $name;
					$emailData['email_to']			= $user_meta->user_email;
					$emailData['site_url'] 			= esc_url(home_url('/'));
					$email_helper->publish_approve_user_acount($emailData);
				}
			}
			
			//Push notification
			$push	= array();
			$push['receiver_id']	= $user_id;
			$push['%name%']			= workreap_get_username($user_id);
			$push['%site_url%']		= $blogname;
			$push['type']			= 'approve_account';

			$push['%replace_site_url%']	= $blogname;
			
			do_action('workreap_user_push_notify',array($user_id),'','pusher_user_approve_content',$push);
			
			$json = array();
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Account has been approved and email has been sent to user.', 'workreap_core');        
			wp_send_json($json);
		}
	}
}

/**
 * @Approve project and services 
 * @return 
 */
if( !function_exists( 'workreap_approve_post' ) ){
	add_action('wp_ajax_workreap_approve_post', 'workreap_approve_post');
    add_action('wp_ajax_nopriv_workreap_approve_post', 'workreap_approve_post');
	function workreap_approve_post(){
		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		} //if user is not logged in then prevent
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
			wp_send_json( $json );
		}
		
		$user_id 	= !empty( $_POST['id'] ) ? $_POST['id'] : '';
		$post_id 	= !empty( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		$type 		= !empty( $_POST['type'] ) ? $_POST['type'] : '';
		
		if( empty( $user_id ) || empty( $post_id ) || empty( $type ) ){
			$json['type'] = 'success';
            $json['message'] = esc_html__('User ID or post ID is empty', 'workreap_core');
            wp_send_json($json);
		}
		
		$user_meta	= get_userdata($user_id);
			
		if( empty( $user_meta ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('No user exists', 'workreap_core');
			wp_send_json($json);
		}
		
		$emailData = array();
		$name  		= workreap_get_username( $user_id );
		$emailData['name'] 				= $name;
		$emailData['email_to']			= $user_meta->user_email;
		
		if( isset( $type ) && $type === 'project' ){
			$arg = array(
				'ID' 		=> $post_id,
				'ID' 		=> $post_id,
				'post_status' 	=> 'publish'
			);

			wp_update_post( $arg );
			
			if (class_exists('Workreap_Published')) {
				if (class_exists('Workreap_Published')) {
					$email_helper = new Workreap_Published();
					$employer_id 					= get_post_field('post_author', $post_id);
					$emailData['project_name'] 		= get_the_title($post_id);
					$emailData['link'] 				= get_the_permalink($post_id);
					$email_helper->publish_approve_project($emailData);
					
					
					//Push notification
					$push	= array();
					$push['employer_id']		= $employer_id;
					$push['project_id']			= $post_id;

					$employer_profile_id		= workreap_get_linked_profile_id($employer_id);
					$push['%employer_link%']	= get_the_title($employer_profile_id);
					$push['%employer_name%']	= get_permalink($employer_profile_id);
					$push['%project_title%']	= get_the_title($post_id);
					$push['%project_link%']		= get_permalink($post_id);

					$push['type']				= 'project_approved';

					do_action('workreap_user_push_notify',array($employer_id),'','pusher_emp_job_post_content',$push);
					
				}
			} 
			
			$json = array();
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Project has been published and email has been sent to user', 'workreap_core');        
			wp_send_json($json);
			
		} elseif( isset( $type ) && $type === 'service' ){
			$arg = array(
				'ID' => $post_id,
				'post_status' => 'publish'
			);

			wp_update_post( $arg );
			
			if (class_exists('Workreap_Published')) {
				if (class_exists('Workreap_Published')) {
					$email_helper = new Workreap_Published();
					$freelancer_id 					= get_post_field('post_author', $post_id);
					$emailData['service_name'] 		= get_the_title($post_id);
					$emailData['link'] 				= get_the_permalink($post_id);
					$email_helper->publish_approve_service($emailData);
					
					//Push notification
					$push	= array();
					$push['freelancer_id']		= $freelancer_id;
					$push['service_id']			= $post_id;

					$freelancer_profile_id		= workreap_get_linked_profile_id($freelancer_id);
					$push['%freelancer_link%']	= get_the_title($freelancer_profile_id);
					$push['%freelancer_name%']	= get_permalink($freelancer_profile_id);
					$push['%service_title%']	= get_the_title($post_id);
					$push['%service_link%']		= get_permalink($post_id);

					$push['type']				= 'service_approved';

					do_action('workreap_user_push_notify',array($freelancer_id),'','pusher_service_approved_content',$push);
				}
			} 
			
			$json = array();
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Service has been published and email has been sent to user', 'workreap_core');        
			wp_send_json($json);
		}
	}
}

/**
 * @Registration process Registration
 * @return 
 */
if( !function_exists( 'workreap_process_registration_complete' ) ) {
	function workreap_process_registration_complete(){
		global $current_user;
		$user_id = !empty( $_POST['id'] ) ? $_POST['id'] : '';
		
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
			wp_send_json( $json );
		}
		
		if( empty( $user_id ) || $current_user->ID != $user_id ){
			$json['type'] = 'error';
            $json['message'] = esc_html__('You are not authorized user to make the modifications', 'workreap_core');
            echo json_encode($json);
            die;
		}
		
	 	//All looks fine now
		update_user_meta( $current_user->ID, '_registerd', 'registered' );
		$profile_page	= '';
		if( function_exists('workreap_get_search_page_uri') ){
			$profile_page  = workreap_get_search_page_uri('dashboard');
		}
		
		$json['type'] = 'success';
        $json['message'] = esc_html__('Thank You', 'workreap_core');
        $json['retrun_url'] = htmlspecialchars_decode($profile_url);
        echo json_encode($json);
        die;
       
	}
	add_action('wp_ajax_workreap_process_registration_complete', 'workreap_process_registration_complete');
    add_action('wp_ajax_nopriv_workreap_process_registration_complete', 'workreap_process_registration_complete');
}

/**
 * @Login/Form
 * @return 
 */
if( !function_exists( 'workreap_print_login_form' ) ){
	add_action('workreap_print_login_form', 'workreap_print_login_form', 10);
	function workreap_print_login_form(){
		if (function_exists('fw_get_db_settings_option')) {
			$login_register = fw_get_db_settings_option('enable_login_register'); 
			$enable_google_connect 	 = fw_get_db_settings_option('enable_google_connect', $default_value = null);
			$enable_facebook_connect = fw_get_db_settings_option('enable_facebook_connect', $default_value = null);
			$enable_linkedin_connect = fw_get_db_settings_option('enable_linkedin_connect', $default_value = null);
			$header_type 			 = fw_get_db_settings_option('header_type');
			$enable_login_register   = fw_get_db_settings_option('enable_login_register');
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
			$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
		} 

		$is_auth			= !empty($login_register['gadget']) ? $login_register['gadget'] : ''; 
		$is_register		= !empty($login_register['enable']['registration']['gadget']) ? $login_register['enable']['registration']['gadget'] : ''; 
		$redirect           = !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';
		$signup_page_slug   = workreap_get_signup_page_url('step', '1');
		
		ob_start(); 	
		if ( apply_filters('workreap_get_domain',false) === true ) {
			$post_name = workreap_get_post_name();
			if( $post_name === "home-page-three" ){
				$header_type['gadget'] = 'header_v3';
			}
		}
		
		if ( is_user_logged_in() ) {
			Workreap_Profile_Menu::workreap_profile_menu_top();
		} else{
		if( $is_auth === 'enable'){?>
			<div class="wt-loginarea">
				<div class="wt-loginoption">
					<?php if( !empty( $header_type['gadget'] ) 
							 && ( $header_type['gadget'] === 'header_v2' 
								 || $header_type['gadget'] == 'header_v3' 
								 || $header_type['gadget'] == 'header_v5' 
								 || $header_type['gadget'] == 'header_v6' ) 
						){?>
						<div class="wt-loginoption wt-loginoptionvtwo">
							<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
								<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-btn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
							<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
								<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-btn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
							<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'pages' && !empty($enable_login_register['enable']['login_page'][0])){?>
								<a href="<?php echo esc_url(get_the_permalink( $enable_login_register['enable']['login_page'][0] ));?>"  class="wt-btn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
							<?php } else {?>
								<a href="#" onclick="event_preventDefault(event);" id="wt-loginbtn" class="wt-btn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
							<?php }?>
						</div>
					<?php }else {?>
						<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
							<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-loginbtn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
						<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
							<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-loginbtn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
						<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'pages' && !empty($enable_login_register['enable']['login_page'][0])){?>
								<a href="<?php echo esc_url(get_the_permalink( $enable_login_register['enable']['login_page'][0] ));?>"  class="wt-btn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
						<?php } else {?>
							<a href="#" onclick="event_preventDefault(event);" id="wt-loginbtn" class="wt-loginbtn"><i class="fa fa-sign-in"></i>&nbsp;<?php esc_html_e('Sign In','workreap_core');?></a>
						<?php }?>
					<?php }?>
					<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'pages' && !empty($enable_login_register['enable']['login_page'][0])){?>
					<div class="wt-loginformhold">
						<div class="wt-loginheader">
							<span><?php esc_html_e('Sign In','workreap_core');?></span>
							<a href="#" onclick="event_preventDefault(event);"><i class="fa fa-times"></i></a>
						</div>
						<form class="wt-formtheme wt-loginform do-login-form">
							<fieldset>
								<?php do_action('workreap_username_add_remove','');?>
								<div class="form-group toolip-wrapo">
									<input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password', 'workreap_core'); ?>">
									<?php do_action('workreap_get_tooltip','element','password');?>
								</div>
								<?php if( !empty( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
									<?php wp_enqueue_script('recaptcha');?>
									<div class="domain-captcha form-group">
										<div id="recaptcha_signin"></div>
									</div>
								<?php }?>
								<div class="wt-logininfo">
									<input type="submit" class="wt-btn do-login-button" value="<?php esc_attr_e('Sign In','workreap_core');?>">
									<span class="wt-checkbox">
										<input id="wt-login" type="checkbox" name="rememberme">
										<label for="wt-login"><?php esc_html_e('Keep me logged in','workreap_core');?></label>
									</span>
								</div>
								<?php wp_nonce_field('login_request', 'login_request'); ?>
								<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect );?>">
							</fieldset>
							<?php 
								if (  ( isset($enable_google_connect) && $enable_google_connect === 'enable' ) 
								   || ( isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) 
								   || ( isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) 
								) {?>
								<div class="wt-joinnowholder">
									<ul class="wt-socialicons wt-iconwithtext">
										<?php if (  isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) {?>
											<li class="wt-facebook"><a class="sp-fb-connect" href="#" onclick="event_preventDefault(event);"><i class="fa fa-facebook-f"></i><em><?php esc_html_e('Facebook', 'workreap_core'); ?></em></a></li>
										<?php }?>
										<?php if (  isset($enable_google_connect) && $enable_google_connect === 'enable' ) {?>
											<li class="wt-googleplus"><a class="wt-googlebox" id="wt-gconnect" href="#" onclick="event_preventDefault(event);"><i class="fa fa-google"></i><em><?php esc_html_e('Google', 'workreap_core'); ?></em></a></li>
										<?php }?>
										<?php if (  isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) {do_action('workreap_linkedin_login_button');}?>
									</ul>
								</div>
							<?php }?>
							<div class="wt-loginfooterinfo">
								<a href="#" onclick="event_preventDefault(event);" class="wt-forgot-password-v2"><?php esc_html_e('Forgot password?','workreap_core');?></a>
								<?php if ( !empty($is_register) && $is_register === 'enable' ) {?>
									<a href="<?php echo esc_url(  $signup_page_slug ); ?>"><?php esc_html_e('Create account','workreap_core');?></a>
								<?php }?>
							</div>
						</form>
						<form class="wt-formtheme wt-loginform do-forgot-password-form wt-hide-form">
							<fieldset>
								<div class="form-group">
									<input type="email" name="email" class="form-control get_password" placeholder="<?php esc_html_e('Email', 'workreap_core'); ?>">
								</div>
								<?php if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
									<?php wp_enqueue_script('recaptcha');?>
									<div class="domain-captcha form-group">
										<div id="recaptcha_forgot"></div>
									</div>
								<?php }?>
								<div class="wt-logininfo">
									<a href="#" onclick="event_preventDefault(event);" class="wt-btn do-get-password-btn"><?php esc_html_e('Get Password','workreap_core');?></a>
								</div>                                                               
							</fieldset>
							<div class="wt-loginfooterinfo">
								<a href="#" onclick="event_preventDefault(event);" class="login-revert-v2"><?php esc_html_e('Sign In','workreap_core');?></a>
								<?php if ( !empty($is_register) && $is_register === 'enable' ) {?>
									<a href="<?php echo esc_url(  $signup_page_slug ); ?>"><?php esc_html_e('Create account','workreap_core');?></a>
								<?php }?>
							</div>
						</form>
					</div>
					<?php }?>
				</div>
				<?php if ( !empty($is_register) && $is_register === 'enable' ) {?>
					<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
						<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn wt-joinnowbtn"><?php esc_html_e('Join Now','workreap_core');?></a>
					<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
						<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn wt-joinnowbtn"><?php esc_html_e('Join Now','workreap_core');?></a>
					<?php } else {?>
						<a href="<?php echo esc_url(  $signup_page_slug ); ?>"  class="wt-btn"><?php esc_html_e('Join Now','workreap_core');?></a>
					<?php }?>
				<?php }?> 
			</div>
			<?php }
		}
		
		echo ob_get_clean();
	}
}

/**
 * @save project post meta data
 * @type delete
 */
if (!function_exists('workreap_save_project_meta_data')) {
	add_action('save_post', 'workreap_save_project_meta_data');
    function workreap_save_project_meta_data($post_id) {
		if (!is_admin()) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		
		//save projects
		if (get_post_type() == 'projects') {
			if (!function_exists('fw_get_db_post_option')) {
				return;
			}
			
			if (!empty($_POST['fw_options'])) {
				$project_level 	= !empty( $_POST['fw_options']['project_level'] ) ? $_POST['fw_options']['project_level'] : '';
				$expiry_date 	= !empty( $_POST['fw_options']['expiry_date'] ) ? $_POST['fw_options']['expiry_date'] : '';
				$project_type 	= !empty( $_POST['fw_options']['project_type']['gadget'] ) ? $_POST['fw_options']['project_type']['gadget'] : '';
				
				if( $project_type == 'hourly'){
					$project_price 		= !empty( $_POST['fw_options']['project_type']['hourly']['hourly_rate'] ) ? $_POST['fw_options']['project_type']['hourly']['hourly_rate'] : '';
					$max_price 			= !empty( $_POST['fw_options']['project_type']['hourly']['max_price'] ) ? $_POST['fw_options']['project_type']['hourly']['max_price'] : '';
					$estimated_hours 	= !empty( $_POST['fw_options']['project_type']['hourly']['estimated_hours'] ) ? $_POST['fw_options']['project_type']['hourly']['estimated_hours'] : '';
				} elseif( $project_type == 'fixed'){
					$project_price 		= !empty( $_POST['fw_options']['project_type']['fixed']['project_cost'] ) ? $_POST['fw_options']['project_type']['fixed']['project_cost'] : '';
					$max_price 			= !empty( $_POST['fw_options']['project_type']['fixed']['max_price'] ) ? $_POST['fw_options']['project_type']['fixed']['max_price'] : '';
					$estimated_hours	= '';
				} else {
					$project_price 		= '';
					$max_price 			= '';
					$estimated_hours	= '';
				}
				
				if (function_exists('fw_get_db_settings_option')) {
					$job_price_option           = fw_get_db_settings_option('job_price_option', $default_value = null);
					$job_option           		= fw_get_db_settings_option('job_option', $default_value = null);
					$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
				}
				
				$job_price_option 			= !empty($job_price_option) ? $job_price_option : '';
				$job_option 				= !empty($job_option) ? $job_option : '';
				$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

				if( !empty($job_option) && ( $job_option === 'enable' ) && !empty( $_POST['fw_options']['job_option'] ) ){
					$job_option_val 	= !empty( $_POST['fw_options']['job_option'] ) ? $_POST['fw_options']['job_option'] : '';
					update_post_meta($post_id, '_job_option', $job_option_val );
				}
				
				if( !empty($milestone) && ( $milestone === 'enable' ) && !empty( $_POST['fw_options']['project_type']['fixed']['milestone'] ) ){
					$milestone_val 	= !empty( $_POST['fw_options']['project_type']['fixed']['milestone'] ) ? $_POST['fw_options']['project_type']['fixed']['milestone'] : 'off' ;
					update_post_meta($post_id, '_milestone', $milestone_val );
				}
				
				if (isset($_POST['fw_options']['address'])) {
					update_post_meta($post_id, '_address',sanitize_text_field( $_POST['fw_options']['address']));
				}

				if (isset($_POST['fw_options']['longitude'])) {
					update_post_meta($post_id, '_longitude',( $_POST['fw_options']['longitude']));
				}

				if (isset($_POST['fw_options']['latitude'])) {
					update_post_meta($post_id, '_latitude',( $_POST['fw_options']['latitude']));
				}

				//location 
				if (isset($_POST['fw_options']['country'])) {
					$locations = get_term_by( 'id', $_POST['fw_options']['country'], 'locations' );
					$location = array();
					if( !empty( $locations ) ){
						wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
						update_post_meta($post_id, '_country',sanitize_text_field( $locations->slug ));
					}
				}
				
				$project_duration 	= !empty( $_POST['fw_options']['project_duration'] ) ? $_POST['fw_options']['project_duration'] : '';
				$english_level 		= !empty( $_POST['fw_options']['english_level'] ) ? $_POST['fw_options']['english_level'] : '';
				$freelancer_level 	= !empty( $_POST['fw_options']['freelancer_level'] ) ? $_POST['fw_options']['freelancer_level'] : '';
				
				$freelancertype	= '';
				if( function_exists('fw_get_db_settings_option')  ){
					$freelancertype	= fw_get_db_settings_option('multiselect_freelancertype', $default_value = null);
				}
				
				$skills_list = wp_get_post_terms( $post_id, 'skills', array( 'fields' => 'names' ) );
				$project_cat_list = wp_get_post_terms( $post_id, 'project_cat', array( 'fields' => 'names' ) );

				if(!empty($freelancertype) && $freelancertype === 'enable'){
					$freelance_types	= explode("/*/",$freelancer_level);
					$freelancer_level	= $freelance_types;
				}
				
				
				//Add searchable data
				update_post_meta($post_id, '_expiry_date', $expiry_date);
				update_post_meta($post_id, '_project_expiry_string', strtotime($expiry_date));
				
				update_post_meta($post_id, '_skills_names', $skills_list);
				update_post_meta($post_id, '_categories_names', $project_cat_list); 
				update_post_meta($post_id, '_project_level', $project_level); 
				update_post_meta($post_id, '_project_type', $project_type); 
				update_post_meta($post_id, '_project_cost', $project_price);
				update_post_meta($post_id, '_max_price', $max_price);
				update_post_meta($post_id, '_estimated_hours', $estimated_hours);
				update_post_meta($post_id, '_project_duration', $project_duration);
				update_post_meta($post_id, '_english_level', $english_level);
				update_post_meta($post_id, '_freelancer_level', $freelancer_level);	
				
				//Featured Expiry
				if (!empty($_POST['fw_options']['featured_post']) && !empty( $_POST['fw_options']['featured_expiry'] )) {
					update_post_meta($post_id, '_featured_job_string',1);
					update_post_meta($post_id, '_expiry_string', strtotime( $_POST['fw_options']['featured_expiry'] ));
				}else{
					 $featured_str = get_post_meta($post_id, '_featured_job_string', true);
					 $featured_str	= !empty($featured_str) ? $featured_str : 0;
					 update_post_meta($post_id, '_featured_job_string',$featured_str);
				}
				
			}
		}
		
		//save freelancer
		if ( get_post_type() === 'freelancers' ) {
			if (isset($_POST['fw_options']['address'])) {
				update_post_meta($post_id, '_address',sanitize_text_field( $_POST['fw_options']['address']));
			}
			
			if (isset($_POST['fw_options']['longitude'])) {
				update_post_meta($post_id, '_longitude',( $_POST['fw_options']['longitude']));
			}
			
			if (isset($_POST['fw_options']['latitude'])) {
				update_post_meta($post_id, '_latitude',( $_POST['fw_options']['latitude']));
			}

			if (function_exists('fw_get_db_settings_option')) {
				$freelancer_price_option = fw_get_db_settings_option('freelancer_price_option', $default_value = null);
			}
			
			$freelancer_price_option 	= !empty($freelancer_price_option['gadget']) ? $freelancer_price_option['gadget'] : '';
	
			if(!empty($freelancer_price_option) && $freelancer_price_option === 'enable' ){
				$min_price   = !empty($_POST['fw_options']['min_price'] ) ? sanitize_text_field( $_POST['fw_options']['min_price'] ) : '';
				$max_price   = !empty($_POST['fw_options']['max_price'] ) ? sanitize_text_field( $_POST['fw_options']['max_price'] ) : '';
				update_post_meta($post_id, '_min_price', $min_price);
				update_post_meta($post_id, '_max_price', $max_price);
			}
			
			//location 
			if (isset($_POST['fw_options']['country'])) {
				$locations = get_term_by( 'id', $_POST['fw_options']['country'], 'locations' );
				$location = array();
				if( !empty( $locations ) ){
					wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
					update_post_meta($post_id, '_country',( $locations->slug ));
				}
			}
			
			//Skills
			$skills = !empty( $_POST['fw_options']['skills'] ) ? $_POST['fw_options']['skills'] : array();
			$skills_term 	= array();

			$counter = 0;
			$skills_names	= array();
			if( !empty( $skills ) ){
				foreach ($skills as $key => $value) {
					$skill_data		= json_decode (stripslashes($value));
					if( !empty($skill_data->skill[0]) ){
						$skills_term[]  = intval( $skill_data->skill[0] );
						$skills_names[] = get_term( $skill_data->skill[0] )->name;
						$counter++;
					}
				} 
			}
			
			if(!empty($skills_term)){
				update_post_meta($post_id, '_skills_names', $skills_names);
				do_action('workreap_update_profile_strength','skills',true,$post_id);
				wp_set_post_terms( $post_id, $skills_term, 'skills' );
			}else{
				update_post_meta($post_id, '_skills_names', array());
				do_action('workreap_update_profile_strength','skills',false,$post_id);
			}
			
			//tagline 
			if (!empty($_POST['fw_options']['tag_line'])) {
				do_action('workreap_update_profile_strength','tagline',true,$post_id);
			}else{
				do_action('workreap_update_profile_strength','tagline',false,$post_id);
			}
			
			//Update description Profile health
			if(!empty(get_the_content($post_id))){
				do_action('workreap_update_profile_strength','description',true,$post_id);
			}else{
				do_action('workreap_update_profile_strength','description',false,$post_id);
			}
			
			//document identity
			$identity_verified	= get_post_meta($post_id, 'identity_verified', true);
			if( !empty( $identity_verified ) ){
				do_action('workreap_update_profile_strength','identity_verification',true,$post_id);
			}else{
				do_action('workreap_update_profile_strength','identity_verification',false,$post_id);
			}
			
			//Profile avatar
			if (has_post_thumbnail($post_id)) {
				do_action('workreap_update_profile_strength','avatar',true,$post_id);
			} else {
				do_action('workreap_update_profile_strength','avatar',false,$post_id);
			}  
			
			//have experience 
			if (!empty($_POST['fw_options']['experience'])) {
				do_action('workreap_update_profile_strength','experience',true,$post_id);
			}else{
				do_action('workreap_update_profile_strength','experience',false,$post_id);
			}
			
			$freelancer_specialization	= array();
			if( function_exists('fw_get_db_settings_option')  ){
				$freelancer_specialization	= fw_get_db_settings_option('freelancer_specialization', $default_value = null);
			}
			
			$specialization 			= !empty($freelancer_specialization) ? $freelancer_specialization : '';
			$experience	= array();
			if( function_exists('fw_get_db_settings_option')  ){
				$experience	= fw_get_db_settings_option('freelancer_industrial_experience', $default_value = null);
				$specialization_check	= fw_get_db_settings_option('freelancer_specialization', $default_value = null);
			}

			$experience 	= !empty($experience ) ? $experience : '';

			if(!empty($experience) && $experience === 'enable' ){
				$industrial_experiences = !empty( $_POST['fw_options']['industrial_experiences'] ) ? $_POST['fw_options']['industrial_experiences'] : array();
				$industrial_experiences_term 	= array();

				$counter = 0;
				if( !empty( $industrial_experiences ) ){
					foreach ($industrial_experiences as $key => $value) {
						$experiences_data		= json_decode (stripslashes($value));
						if( !empty($experiences_data->exp[0]) ){
							$industrial_experiences_term[]  = intval( $experiences_data->exp[0] );
							$counter++;
						}
					} 
				}

				wp_set_post_terms( $post_id, $industrial_experiences_term, 'wt-industrial-experience' );
			}
			
			if(!empty($specialization_check) && $specialization_check === 'enable' ){
				$specialization = !empty( $_POST['fw_options']['specialization'] ) ? $_POST['fw_options']['specialization'] : array();
				$specialization_term 	= array();

				$counter = 0;
				if( !empty( $specialization ) ){
					foreach ($specialization as $key => $value) {
						$specialization_data		= json_decode (stripslashes($value));
						if( !empty($specialization_data->spec[0]) ){
							$specialization_term[]  = intval( $specialization_data->spec[0] );
							$counter++;
						}
					} 
				}
				
				wp_set_post_terms( $post_id, $specialization_term, 'wt-specialization' );
			}

			//tagline
			if (isset($_POST['fw_options']['tag_line'])) {
				update_post_meta($post_id, '_tag_line',sanitize_text_field( $_POST['fw_options']['tag_line']));
			}
			
			//perhour
			if (isset($_POST['fw_options']['_perhour_rate'])) {
				update_post_meta($post_id, '_perhour_rate',intval( $_POST['fw_options']['_perhour_rate'])); 
			}
			
			//gender
			if (isset($_POST['fw_options']['gender'])) {
				update_post_meta($post_id, '_gender',$_POST['fw_options']['gender']); 
			}

			//freelancer type
			if (isset($_POST['fw_options']['freelancer_type'])) {
				if (function_exists('fw_get_db_settings_option')) {
					$freelancerselecttype	= fw_get_db_settings_option('freelancertype_multiselect', $default_value = null);
				}
				
				$freelancer_type 	= !empty( $_POST['fw_options']['freelancer_type'] ) ? $_POST['fw_options']['freelancer_type'] : '';
				
				if(!empty($freelancerselecttype) && $freelancerselecttype === 'enable'){
					$freelancer_type	= explode("/*/",$freelancer_type);
					$freelancer_type	= $freelancer_type;
				}
				
				
				update_post_meta($post_id, '_freelancer_type', $freelancer_type);
				$freelancer_type_array	= !empty($freelancer_type) && is_array($freelancer_type) ? $freelancer_type : array($freelancer_type);
				
				$department_term = get_term_by( 'term_id', $department, 'department' );
				if( !empty( $freelancer_type_array ) ){
					$type_list	= array();
					foreach($freelancer_type_array as $key => $type){
						$freelancer_type_term = get_term_by( 'slug', $type, 'freelancer_type' );
						if(!empty($freelancer_type_term->term_id)){
							$type_list[] = $freelancer_type_term->term_id;
						}
						
					}
					
					wp_set_post_terms( $post_id, $type_list, 'freelancer_type' );
					
				}
			}
			
			//freelancer type
			if (isset($_POST['fw_options']['english_level'])) {
				update_post_meta($post_id, '_english_level',$_POST['fw_options']['english_level']); 
			}
			
			if( !empty( $_POST['payout_settings'] ) ){
				$linked_profile   	= workreap_get_linked_profile_id($post_id,'post');
				update_user_meta($linked_profile,'payrols',$_POST['payout_settings']);
			}

			//Featured Expiry
			if (!empty($_POST['fw_options']['featured_post']) && !empty( $_POST['fw_options']['featured_expiry'] )) {
				update_post_meta($post_id, '_featured_timestamp',1);
				update_post_meta($post_id, '_expiry_string',strtotime( $_POST['fw_options']['featured_expiry'] ));
			}else{
				update_post_meta($post_id, '_featured_timestamp',0);
				update_post_meta($post_id, '_expiry_string',0);
			}

			//update profile health
			$get_profile_data	= get_post_meta($post_id, 'profile_strength',true);
			$total_percentage	= !empty( $get_profile_data['data'] ) ? array_sum( $get_profile_data['data'] ) : 0;
			$total_percentage	= !empty( $total_percentage ) ? intval($total_percentage) : 0;
			update_post_meta($post_id, '_profile_health_filter', $total_percentage); 
			
		}
		
		//save proposals
		if ( get_post_type() === 'proposals' ) {
			
			if (isset($_POST['fw_options']['proposal_duration'])) {
				update_post_meta( $post_id, '_proposed_duration', $_POST['fw_options']['proposal_duration'] );
			}
			
			if (isset($_POST['fw_options']['estimeted_time'])) {
				update_post_meta( $post_id, '_estimeted_time', $_POST['fw_options']['estimeted_time'] );
			}
			
			if (isset($_POST['fw_options']['proposed_amount'])) {
				update_post_meta( $post_id, '_amount', $_POST['fw_options']['proposed_amount'] );
			}

			if (isset($_POST['fw_options']['per_hour_amount'])) {
				update_post_meta( $post_id, '_per_hour_amount', $_POST['fw_options']['per_hour_amount'] );
			}
			
			if (isset($_POST['fw_options']['proposal_docs'])) {
				 update_post_meta( $post_id, '_proposal_docs', $_POST['fw_options']['proposal_docs']);
			}
			if (isset($_POST['fw_options']['project'])) {
				 update_post_meta( $post_id, '_project_id', $_POST['fw_options']['project'] );
			}
			
		}

		//save employer
		if ( get_post_type() === 'employers' ) {

			if (isset($_POST['fw_options']['address'])) {
				update_post_meta($post_id, '_address',esc_html( $_POST['fw_options']['address']));
			}
			
			if (isset($_POST['fw_options']['longitude'])) {
				update_post_meta($post_id, '_longitude',esc_html( $_POST['fw_options']['longitude']));
			}
			
			if (isset($_POST['fw_options']['latitude'])) {
				update_post_meta($post_id, '_latitude',esc_html( $_POST['fw_options']['latitude']));
			}
			
			//location 
			if (isset($_POST['fw_options']['country'])) {
				$locations = get_term_by( 'id', $_POST['fw_options']['country'], 'locations' );
				$location = array();
				if( !empty( $locations ) ){
					wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
					update_post_meta($post_id, '_country',esc_html( $locations->slug ));
				}
			}
			
			if (isset($_POST['fw_options']['tag_line'])) {
				update_post_meta($post_id, '_tag_line',esc_html( $_POST['fw_options']['tag_line']));
			}

			if (isset($_POST['fw_options']['no_of_employees'])) {
				update_post_meta($post_id, '_employees',esc_html( $_POST['fw_options']['no_of_employees']));
			}
			
			//department 
			if (isset($_POST['fw_options']['department'])) {
				$departments = get_term_by( 'id', $_POST['fw_options']['department'], 'department' );
				if( !empty( $departments ) ){
					wp_set_post_terms( $post_id, $departments->term_id, 'department' );
					update_post_meta($post_id, '_department',esc_html( $departments->slug ));
				}
			}

			//Payout settings update meta
			if( !empty( $_POST['payout_settings'] ) ){
				$linked_profile   	= workreap_get_linked_profile_id($post_id,'post');
				update_user_meta($linked_profile,'payrols',$_POST['payout_settings']);
			}

		}
		
		//save portfolio
		if ( get_post_type() === 'wt_portfolio' ) {
			if (!empty($_POST['fw_options']['gallery_imgs'])) {
				if (function_exists('fw_get_db_post_option')) {
					$db_gallery_imgs   	= fw_get_db_post_option($post_id,'gallery_imgs');
					if( !empty($db_gallery_imgs[0]['attachment_id']) ){
						set_post_thumbnail( $post_id, intval( $db_gallery_imgs[0]['attachment_id'] ) );
					}
				}
			}
		}
		
		//save services
		if ( get_post_type() === 'micro-services' ) {
			
			if (!empty($_POST['fw_options']['docs'])) {
				if (function_exists('fw_get_db_post_option')) {
					$db_docs   	= fw_get_db_post_option($post_id,'docs');
					if( !empty($db_docs[0]['attachment_id']) ){
						set_post_thumbnail( $post_id, intval( $db_docs[0]['attachment_id'] ) );
					}
				}
			}
			
			if (isset($_POST['fw_options']['price'])) {
				update_post_meta( $post_id, '_price',esc_html( $_POST['fw_options']['price']) );
			}
			
			if (isset($_POST['fw_options']['downloadable'])) {
				update_post_meta( $post_id, '_downloadable',esc_html( $_POST['fw_options']['downloadable']) );
			}
			
			if (isset($_POST['fw_options']['english_level'])) {
				update_post_meta( $post_id, '_english_level',esc_html( $_POST['fw_options']['english_level']) );
			}
			
			//Featured Expiry
			if (!empty($_POST['fw_options']['featured_post']) && !empty( $_POST['fw_options']['featured_expiry'] )) {
				update_post_meta($post_id, '_featured_service_string',1);
				update_post_meta($post_id, '_expiry_string',strtotime( $_POST['fw_options']['featured_expiry'] ));
			} else {
				update_post_meta($post_id, '_featured_service_string',0);	
			}

			//location 
			if ( isset($_POST['fw_options']['country']) ) {
				$locations = get_term_by( 'id', $_POST['fw_options']['country'], 'locations' );
				$location = array();
				if( !empty( $locations ) ){
					wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
					update_post_meta( $post_id, '_country',esc_html( $locations->slug ));
				}
			}
			
			$addons	        = !empty( $_POST['service']['addons'] ) ? $_POST['service']['addons'] : array();
			if (!empty( $addons ) ) {
				update_post_meta( $post_id, '_addons', $addons );
			}
			
			//update for keyword search
			if (function_exists('fw_get_db_post_option') ) {
				$services_categories	= fw_get_db_settings_option('services_categories');
			}

			$services_categories	= !empty($services_categories) ? $services_categories : 'no';
			if( !empty($services_categories) && $services_categories === 'no' ) {
				$taxonomy_type	= 'project_cat';
			}else{
				$taxonomy_type	= 'service_categories';
			}

			$service_cat_list = wp_get_post_terms( $post_id, $taxonomy_type, array( 'fields' => 'names' ) );
			update_post_meta($post_id, '_categories_names', $service_cat_list);
						
		}
		
		if ( get_post_type() === 'addons-services' ) {
			if (isset($_POST['fw_options']['price'])) {
				update_post_meta( $post_id, '_price',esc_html( $_POST['fw_options']['price']) );
			}
		}
	}
}

/**
 * @save project post meta data
 * @type delete
 */
if (!function_exists('workreap_delete_wp_user')) {
	add_action( 'delete_user', 'workreap_delete_wp_user' );
    function workreap_delete_wp_user($user_id) {
		$linked_profile   	= workreap_get_linked_profile_id($user_id);
		if( !empty( $linked_profile ) ){
		 	wp_delete_post( $linked_profile, true);
		}
	}
}

/**
 * @Create profile from admin create user
 * @type delete
 */
if (!function_exists('workreap_create_wp_user')) {
	add_action( 'user_register', 'workreap_create_wp_user',10,1 );
	add_action( 'wcmo_after_update_users_role', 'workreap_create_wp_user',10,1 ); //woocommerce-members-only plugin compatibility
    function workreap_create_wp_user($user) {
		if(!empty($user->ID)){
			$user_meta	= get_userdata($user->ID);
			$user_id	= $user->ID;
		}else{
			$user_id	= $user;
			$user_meta	= get_userdata($user_id);
		}

		if( !empty( $user_id )  ) {
			$title		= $user_meta->first_name.' '.$user_meta->last_name;
			$roles		= !empty($user_meta->roles) ? $user_meta->roles : '';

			$linked_profile   	= workreap_get_linked_profile_id($user_id);
			if(!empty( $linked_profile )){
				if ( 'publish' == get_post_status ( $linked_profile ) ) {
					return true;
				}
			}

			$post_type	= '';
			if( !empty($roles) && in_array('freelancers',$roles)){
				$post_type = 'freelancers';
			}elseif(!empty($roles) && in_array('employers',$roles)){
				$post_type = 'employers';
			}

			if( !empty($post_type) && ( $post_type === 'freelancers' || $post_type	=== 'employers' ) ){
				$post_data	= array(
								'post_title'	=> wp_strip_all_tags($title),
								'post_author'	=> $user_id,
								'post_status'   => 'publish',
								'post_type'		=> $post_type,
							);

				$post_id	= wp_insert_post( $post_data );

				if( !empty( $post_id ) ) {
					update_post_meta($post_id, '_linked_profile',intval($user_id));
					add_user_meta( $user_id, '_linked_profile', $post_id);
					
					$fw_options = array();
	
					//Update user linked profile
					update_user_meta( $user_id, '_linked_profile', $post_id );
					update_post_meta( $post_id, '_is_verified', 'yes' );

					if( $post_type == 'employers' ){
						$user_type	= 'employer';
						update_post_meta($post_id, '_user_type', 'employer');
						update_post_meta($post_id, '_employees', '');            		
						update_post_meta($post_id, '_followers', '');

						//Fw Options
						$fw_options['department']         = array();
						$fw_options['no_of_employees']    = '';

					} elseif( $post_type == 'freelancers' ){
						$user_type	= 'freelancer';
						update_post_meta($post_id, '_user_type', 'freelancer');
						update_post_meta($post_id, '_perhour_rate', '');
						update_post_meta($post_id, 'rating_filter', 0);
						update_post_meta($post_id, '_freelancer_type', 'rising_talent');         		           		
						update_post_meta($post_id, '_featured_timestamp', 0); 
						update_post_meta($post_id, '_english_level', 'basic');
						update_post_meta($post_id, '_have_avatar', 0); 
						update_post_meta($post_id, '_profile_health_filter', 0); 
						
						//extra data in freelancer
						update_post_meta($post_id, '_gender', '');
						$fw_options['_perhour_rate']    = '';
						$fw_options['gender']    		= '';
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

					//Update User Profile
					fw_set_db_post_option($post_id, null, $fw_options);

					//update privacy settings
					$settings		 = workreap_get_account_settings($user_type);
					if( !empty( $settings ) ){
						foreach( $settings as $key => $value ){
							$val = $key === '_profile_blocked' ? 'off' : 'on';
							update_post_meta($post_id, $key, $val);
						}
					}

					//update post for users verification
					$linked_profile   	= workreap_get_linked_profile_id($user_id);
					update_post_meta($linked_profile, '_is_verified', 'yes');		

					$user_type						= workreap_get_user_type( $user_id );
					$freelancer_package_id			= workreap_get_package_type( 'package_type','trail_freelancer');
					$employer_package_id			= workreap_get_package_type( 'package_type','trail_employer');

					if( $user_type === 'employer' && !empty($employer_package_id) ) {
						workreap_update_pakage_data( $employer_package_id ,$user_id,'' );
					} else if( $user_type === 'freelancer' && !empty($freelancer_package_id) ) {
						workreap_update_pakage_data( $freelancer_package_id ,$user_id,'' );
					}
				}
			}
		}
	}
}

/**
 * @get default color schemes
 * @return 
 */
if (!function_exists('workreap_get_page_color')) {
	add_filter('workreap_get_page_color','workreap_get_page_color',10,1);
	function workreap_get_page_color($color='#5dc560'){
		$post_name = workreap_get_post_name();
		$pages_color	= array(
			'home-v5'		=> '#5dc560',
			'home-page-8'	=> '#017EBE',
			'home-v2'		=> '#5dc560',
			'header-v2'		=> '#5dc560',
		);

		if( isset( $_SERVER["SERVER_NAME"] ) && $_SERVER["SERVER_NAME"] === 'amentotech.com' ){
			if( isset( $pages_color[$post_name] ) ){
				return $pages_color[$post_name];
			} else{
				return $color;
			}
		} else{
			return $color;
		}
	}
}


/**
 * Removes the original author meta box and replaces it
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_replace_post_author_meta_box')) {
	add_action( 'add_meta_boxes', 'workreap_replace_post_author_meta_box' );
	function workreap_replace_post_author_meta_box() {
		$post_type = get_post_type();
		$post_type_object = get_post_type_object( $post_type );
		if( $post_type == 'projects'){
			if ( post_type_supports( $post_type, 'author' ) ) {
				if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) {
					remove_meta_box( 'authordiv', $post_type, 'core' );
					add_meta_box( 'authordiv', esc_html__( 'Author', 'workreap_core' ), 'workreap_post_author_meta_box', null, 'normal' );
				}
			}
		}
		
		if( $post_type == 'freelancers' ){
			if ( post_type_supports( $post_type, 'author' ) ) {
				if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) {
					remove_meta_box( 'authordiv', $post_type, 'core' );
					add_meta_box( 'authordiv', esc_html__( 'Author', 'workreap_core' ), 'workreap_post_author_meta_box_freelancer', null, 'normal' );
				}
			}
		}
		
		if( $post_type == 'micro-services' ){
			if ( post_type_supports( $post_type, 'author' ) ) {
				if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) {
					remove_meta_box( 'authordiv', $post_type, 'core' );
					add_meta_box( 'authordiv', esc_html__( 'Author', 'workreap_core' ), 'workreap_post_author_meta_box_services', null, 'normal' );
				}
			}
		}
		
		if( $post_type == 'addons-services' ){
			if ( post_type_supports( $post_type, 'author' ) ) {
				if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) {
					remove_meta_box( 'authordiv', $post_type, 'core' );
					add_meta_box( 'authordiv', esc_html__( 'Author', 'workreap_core' ), 'workreap_post_author_meta_box_services', null, 'normal' );
				}
			}
		}
		
		if( $post_type == 'services-orders' ){
			if ( post_type_supports( $post_type, 'author' ) ) {
				if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) {
					remove_meta_box( 'authordiv', $post_type, 'core' );
					add_meta_box( 'authordiv', esc_html__( 'Author', 'workreap_core' ), 'workreap_post_author_meta_box_order_services', null, 'normal' );
				}
			}
		}
	}
}

/**
 * @Demo Ready
 * @return {}
 */
if (!function_exists('workreap_is_demo_site')) {
	function workreap_is_demo_site($message=''){
		$json = array();
		$message	= !empty( $message ) ? $message : esc_html__("Sorry! you are restricted to perform this action on demo site.",'workreap_core' );

		if( isset( $_SERVER["SERVER_NAME"] ) 
			&& $_SERVER["SERVER_NAME"] === 'amentotech.com' ){
			$json['type']	    =  "error";
			$json['message']	=  $message;
			echo json_encode( $json );
			exit();
		}
	}
}

/**
 * @taxonomy admin radio button
 * @return {}
 */
if (!function_exists('workreap_Walker_Category_Radio_Checklist')) {
	add_filter( 'wp_terms_checklist_args', 'workreap_Walker_Category_Radio_Checklist', 10, 2 );
	function workreap_Walker_Category_Radio_Checklist( $args, $post_id ) {
		if ( !empty($args['taxonomy']) && ( $args['taxonomy'] === 'response_time' || $args['taxonomy'] === 'delivery' ) ) {
			if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) { 
				if ( ! class_exists( 'Workreap_Walker_Category_Radio' ) ) {
					
					class Workreap_Walker_Category_Radio extends Walker_Category_Checklist {
						public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
							
							if ( empty( $args['taxonomy'] ) ) {
								$taxonomy = 'category';
							} else {
								$taxonomy = $args['taxonomy'];
							}

							if ( $taxonomy == 'category' ) {
								$name = 'post_category';
							} else {
								$name = 'tax_input[' . $taxonomy . ']';
							}

							$args['popular_cats'] = empty( $args['popular_cats'] ) ? array() : $args['popular_cats'];
							$class = in_array( $category->term_id, $args['popular_cats'] ) ? ' class="main-category"' : '';

							$args['selected_cats'] = empty( $args['selected_cats'] ) ? array() : $args['selected_cats'];
							if ( ! empty( $args['list_only'] ) ) {
								$is_checked 	= 'false';
								$main_class 	= 'category';

								if ( in_array( $category->term_id, $args['selected_cats'] ) ) {
									$main_class 	.= ' selected';
									$is_checked 	 = 'true';
								}

								$output .= "\n" . '<li' . $class . '>' .
									'<div class="' . $main_class . '" data-term-id=' . $category->term_id .
									' tabindex="0" role="checkbox" aria-checked="' . $is_checked . '">' .
									esc_html( apply_filters( 'the_category', $category->name ) ) . '</div>';
							} else {
								$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
								'<label class="dc-radios"><input value="' . $category->term_id . '" type="radio" name="'.$name.'[]" id="dc-'.$taxonomy.'-' . $category->term_id . '"' .
								checked( in_array( $category->term_id, $args['selected_cats'] ), true, false ) .
								disabled( empty( $args['disabled'] ), false, false ) . ' /> ' .
								esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
							}
						}
					}
				}
				
				$args['walker'] = new Workreap_Walker_Category_Radio;
			}
		}
		return $args;
	}
}

/**
 * @Hide Quick edit form project and service
 * @return {}
 */
if (!function_exists('workreap_remove_quick_edit')) {
	function workreap_remove_quick_edit( $actions ) { 
		$post_type = get_post_type();
		if ( $post_type === 'micro-services' || $post_type === 'employers' || $post_type === 'freelancers' || $post_type === 'projects' || $post_type === 'services-orders') {
			unset($actions['inline hide-if-no-js']);
		}
		 return $actions;
	}
	add_filter('post_row_actions','workreap_remove_quick_edit',10,1);
}

/**
 * @create social login URL
 * Return{}
 */
if ( !function_exists( 'workreap_new_social_login_url' ) ) {
	function workreap_new_social_login_url($key='googlelogin') {
	  return site_url('wp-login.php') . '?'.$key.'=1';
	}
}

/**
 * @create social login uniqe ID
 * Return{}
 */
if(!function_exists('workreap_get_uniqid')){
    function workreap_get_uniqid(){
        if(isset($_COOKIE['workreap_uniqid'])){
            if(get_site_transient('n_'.$_COOKIE['workreap_uniqid']) !== false){
                return $_COOKIE['workreap_uniqid'];
            }
        }
		
        $_COOKIE['workreap_uniqid'] = uniqid('workreap_core', true);
        setcookie('workreap_uniqid', $_COOKIE['workreap_uniqid'], time() + 3600, '/');
        set_site_transient('n_'.$_COOKIE['workreap_uniqid'], 1, 3600);
        
        return $_COOKIE['workreap_uniqid'];
    }
}

/**
 * @create social users
 * Return{}
 */
if ( !function_exists( 'workreap_new_social_login' ) ) {
	add_action( 'login_init', 'workreap_new_social_login' );

	function workreap_new_social_login() {
		if ( isset( $_GET['googlelogin'] ) && $_GET['googlelogin'] == '1' ) {
			do_action('do_google_connect');
			workreap_new_social_redirect('google');
		} else if ( isset( $_GET['facebooklogin'] ) && $_GET['facebooklogin'] == '1' ) {
			do_action('do_facebook_connect');
			workreap_new_social_redirect('facebook');
		}else if ( isset( $_GET['linkedin'] ) && $_GET['linkedin'] == '1' ) {
			workreap_new_social_redirect('linkedin');
		}
	}
}

/**
 * @Send verification
 * @return 
 */
if( !function_exists( 'workreap_js_social_login') ){
	function workreap_js_social_login(){
		$json 		= array();
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
			wp_send_json( $json );
		}
		
		if( !empty( $_POST['email'] ) ){
			$userdata	= !empty( $_POST ) ? $_POST : array();
			$user_email	= !empty( $_POST['email'] ) && is_email( $_POST['email'] ) ? $_POST['email']: '';
			$login_type	= !empty( $_POST['login_type'] ) ? $_POST['login_type']: 'facebook';
			$ID 		= email_exists( $user_email );
			
			$profile_page	= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$profile_page  = workreap_get_search_page_uri('dashboard');
			}

			if ( $ID == false ) { // Real register
				$profile_url	= workreap_create_social_users($login_type,$userdata);
				$json['type'] 		= 'success';
				$json['redirect']	= $profile_url;
				$json['message'] 	= esc_html__('You have successfully logged in.', 'workreap_core');  
			} else if ( !empty( $ID ) ) {
				$profile_url    	= apply_filters('workreap_do_social_login',$ID,'');
				$json['type']		= 'success';
				$json['redirect']	= $profile_url;
				$json['message'] 	= esc_html__('You have successfully login.', 'workreap_core');
			}
		}
        
        wp_send_json($json);
	}
	add_action('wp_ajax_workreap_js_social_login', 'workreap_js_social_login');
    add_action('wp_ajax_nopriv_workreap_js_social_login', 'workreap_js_social_login');
}

/**
 * @get redirect URL
 * Return{}
 */
if (!function_exists('workreap_create_social_users')) {
	add_action('workreap_create_social_users','workreap_create_social_users',10,2);
	function workreap_create_social_users($type,$user) {
		$email = esc_html($user['email']);
		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$fb_prefix 			= fw_get_db_settings_option( 'fb_prefix' );
			$g_prefix 			= fw_get_db_settings_option( 'g_prefix' );
			$linkedin_prefix 	= fw_get_db_settings_option( 'linkedin_prefix' );
		}
		
		if( isset( $type ) && $type === 'facebook' && !empty( $fb_prefix ) ){
			$prefix	= $fb_prefix;
		} else if( isset( $type ) && $type === 'google' && !empty( $g_prefix ) ){
			$prefix	= $g_prefix;
		} else if( isset( $type ) && $type === 'linkedin' && !empty( $g_prefix ) ){
			$prefix	= $linkedin_prefix;
		} else{
			$prefix = '';
		}

		$sanitized_user_login = sanitize_title( $prefix . $user['name']);
		
		if ( !validate_username( $sanitized_user_login ) ) {
			$sanitized_user_login = sanitize_title( $type . $user['id']);
		}
		
		$defaul_user_name = $sanitized_user_login;
		
		$i = 1;
		while ( username_exists( $sanitized_user_login ) ) {
			$sanitized_user_login = $defaul_user_name . $i;
			$i++;
		}

		$ID = wp_create_user( $sanitized_user_login, $random_password, $email );
		
		if ( !is_wp_error( $ID ) ) {
			global $wpdb;
			$db_user_role = '';
			wp_update_user( array('ID' => esc_sql( $ID ), 'role' => 'subscriber', 'user_status' => 1 ) );

			update_user_meta( $ID, 'show_admin_bar_front', false);
			update_user_meta( $ID, 'register_with_social', 'yes' );
			update_user_meta( $ID, 'company_name', $user['name'] );
			update_user_meta( $ID, 'first_name', $user['name'] );
			update_user_meta( $ID, 'email', esc_html( $email ) );
			update_user_meta( $ID, 'rich_editing', 'true' );
			$verify_user	= 'no';
			
			update_user_meta( $ID, '_is_verified', $verify_user );

			//upload avatar
			do_action('workreap_do_upload_social_user_avatar',$user,$type,$ID);
						
			$user_info = get_userdata( $ID );
			update_user_meta( $ID, 'new_'.$type.'_default_password', $user_info->user_pass );

			return apply_filters('workreap_do_social_login',$ID,'');
			//Send email to user
		}
	}
}

/**
 * @get redirect URL
 * Return{}
 */
if (!function_exists('workreap_do_upload_social_user_avatar')) {
	add_action('workreap_do_upload_social_user_avatar','workreap_do_upload_social_user_avatar',10,3);
	function workreap_do_upload_social_user_avatar($user,$type,$user_id) {
		$filename	= $user['id'].'.jpg';
		$size_type  = 'avatar';
		$uploaddir 	= wp_upload_dir();
		$uploadfile = $uploaddir['path'] . '/' .$filename;

		if( isset( $type ) && $type === 'facebook' ){
			$url	= 'https://graph.facebook.com/'.$user['id'].'/picture?width=600';
		} else{
			$url	= $user['picture'];
		}

		if( empty( $url ) ){ return;}
		
		$request  = wp_remote_get( $url );
		$image_string = wp_remote_retrieve_body( $request );
		
		//$image_string = file_get_contents($url, false);
		$fileSaved 	  = file_put_contents($uploaddir['path'] . "/" . $filename, $image_string);

		$wp_filetype = wp_check_filetype($filename, null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $filename,
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment( $attachment, $uploadfile );

		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		
		$attach_data = wp_generate_attachment_metadata( $attach_id, $uploadfile );
		wp_update_attachment_metadata( $attach_id,  $attach_data );
	    update_user_meta($user_id, 'social_avatar', $attach_id);
		
	}
}

/**
 * @get redirect URL
 * Return{}
 */
if (!function_exists('workreap_do_social_login')) {
	add_filter('workreap_do_social_login','workreap_do_social_login',10,2);
	add_action('workreap_do_social_login','workreap_do_social_login',10,2);
	function workreap_do_social_login($ID,$return='') {
		$user_info = get_userdata( $ID );
		wp_clear_auth_cookie();
		wp_set_current_user( $user_info->ID );
		wp_set_auth_cookie( $user_info->ID );
		
		if( !empty( $return )  && $return === 'yes' ) {
			return $user_info->ID;
		}else{
			$profile_url    = workreap_registration_redirect($user_info->ID);
			return $profile_url;
		}
	}
}

/**
 * @get redirect URL
 * Return{}
 */
if (!function_exists('workreap_new_social_redirect')) {
	function workreap_new_social_redirect($key) {
		$profile_page	= '';
		if( function_exists('workreap_get_search_page_uri') ){
			$profile_page  = workreap_get_search_page_uri('dashboard');
		}
		
		$profile_url    = '';
		if( !empty($profile_page) ) {
			$profile_url    = workreap_registration_redirect();
		}

		$redirect   = $profile_url;
		$redirect = wp_sanitize_redirect($redirect);
		$redirect = wp_validate_redirect($redirect, site_url('/'));
		header('LOCATION: ' . $redirect);
		delete_site_transient( workreap_get_uniqid().'_'.$key.'_r');
		exit;
	}
}

/**
 * @Check if user is registered with social profiles
 * @return 
 */
if (!function_exists('workreap_is_social_user')) {

    function workreap_is_social_user($user_identity) {
		$is_social	= 'no';
        if (!empty($user_identity)) {
            $data = get_userdata($user_identity);
            if ( !empty($data->roles[0]) 
				&& $data->roles[0] === 'subscriber'
				&& !empty( $data->register_with_social_profiles ) 
				&& $data->register_with_social_profiles === 'yes'
			) {
                $is_social	= 'yes';
            } else{
				$is_social	= 'no';
			}
			
        }
		
		return $is_social;
    }

    add_filter('workreap_is_social_user', 'workreap_is_social_user', 10, 1);
}

/**
 * @Add Meta tag
 * @return 
 */
if (!function_exists('workreap_add_meta_tags')) {
	function workreap_add_meta_tags(){
		$gosocial_connect	= '';
		$client_id			= '';
		if ( function_exists('fw_get_db_settings_option' )) {
			$gosocial_connect	= fw_get_db_settings_option('enable_google_connect');
			$client_id			= fw_get_db_settings_option('client_id');
		}
		
		if( !empty( $client_id ) && !empty( $gosocial_connect ) && $gosocial_connect === 'enable' ){
			ob_start(); ?>
	  		<meta name="google-signin-client_id" content="<?php echo esc_attr( $client_id );?>">
		<?php 
			echo ob_get_clean();
		}
	}
	//add_action('wp_head', 'workreap_add_meta_tags');
}

/**
 * @Add async and defer to specfic file
 * @return 
 */

if (!function_exists('workreap_add_defer_attribute')) {
	function workreap_add_defer_attribute($tag, $handle) {
	   $scripts_to_defer = array('workreap-gconnect');

	   foreach($scripts_to_defer as $defer_script) {
		  if ($defer_script === $handle) {
			 return str_replace(' src', ' async defer src', $tag);
		  }
	   }
	   return $tag;
	}
	//add_filter('script_loader_tag', 'workreap_add_defer_attribute', 10, 2);
}

/**
 * Filters all menu item URLs for a #placeholder#.
 *
 * @param WP_Post[] $menu_items All of the nave menu items, sorted for display.
 *
 * @return WP_Post[] The menu items with any placeholders properly filled in.
 */
if (!function_exists('workreap_post_type_button')) {
	add_filter( 'wp_nav_menu_objects', 'workreap_post_type_button' );
	function workreap_post_type_button( $menu_items ) {
		global $current_user;
		$placeholders = array(
			'#post_job_button#' 	=> array(
				'shortcode' 	=> 'wt_post_button',
				'type' 			=> 'job',
			),
			'#post_service_button#' 	=> array(
				'shortcode' 	=> 'wt_post_button',
				'type' 			=> 'service',
			),
		);
		
		if (function_exists('fw_get_db_settings_option')) {
			$enable_login_register = fw_get_db_settings_option('enable_login_register');
		}

		foreach ( $menu_items as $menu_item ) {
			if ( isset( $placeholders[ $menu_item->url ] ) ) {
				global $shortcode_tags;
				$placeholder = !empty( $placeholders[ $menu_item->url ] ) ? $placeholders[ $menu_item->url ] : '';
				if ( isset( $shortcode_tags[ $placeholder['shortcode'] ] ) ) {
					if (is_user_logged_in()) {
						if ( apply_filters('workreap_get_user_type', $current_user->ID) === 'employer' ){
							$menu_item->url = Workreap_Profile_Menu::workreap_profile_menu_link('post_job', $current_user->ID,'return');
							if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] !== 'pages' ){
								$menu_item->classes[]	= 'wt-post-type-button';
							}
							
							if( isset( $placeholder['type'] ) && $placeholder['type'] === 'service' ){
								$menu_item->classes[]	= 'hide-post-menu';
								$menu_item->url 	= '';
								$menu_item->title   = '';
							}
							
						} elseif ( apply_filters('workreap_get_user_type', $current_user->ID) === 'freelancer' ){
							$menu_item->url = Workreap_Profile_Menu::workreap_profile_menu_link('micro_service', $current_user->ID,'return');
							if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] !== 'pages' ){
								$menu_item->classes[]	= 'wt-post-type-button';
							}
							
							if( isset($placeholder['type']) && $placeholder['type'] === 'job' ){
								$menu_item->classes[]	= 'hide-post-menu';
								$menu_item->url 	= '';
								$menu_item->title   = '';
							}
							
						}else{
							$menu_item->classes[]	= 'hide-post-menu';
							$menu_item->url 	= '';
							$menu_item->title   = '';
						}
					} else{
						if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] !== 'pages' ){
							$menu_item->classes[]	= 'wt-post-type-button wt-joinnowbtn';
						}
						$menu_item->url 	=  workreap_get_signup_page_url('step', '1');        
					}
				}
			}
		}

		return $menu_items;
	}
}


/**
 * @Registration process Step Two
 * @return 
 */
if( !function_exists( 'workreap_single_step_registration' ) ){
	function workreap_single_step_registration(){
		$json	= array();
		
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
			wp_send_json( $json );
		}
		
		$verify_user	= '';
		if ( function_exists('fw_get_db_post_option' )) {
			$verify_user 	= fw_get_db_settings_option('verify_user', $default_value = null);
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
                    $json['message'] = esc_html__('An error occurred, please try again later.', 'workreap_core');
                    wp_send_json($json);
                } else {
					$json['type'] = 'error';
                    $json['message'] = esc_html__('Wrong reCaptcha. Please verify first.', 'workreap_core');
                    wp_send_json($json);
                }
            } else {
                wp_send_json(array('type' => 'error', 'message' => esc_html__('Please enter reCaptcha!', 'workreap_core')));
            }
        }
		
		//Validation
		$validations = array(
			'first_name' 		=> esc_html__('First Name field is required.', 'workreap_core'),
			'last_name'  		=> esc_html__('Last Name field is required.', 'workreap_core'),
			'username'  		=> esc_html__('Username field is required.', 'workreap_core'),
            'email' 			=> esc_html__('Email field is required', 'workreap_core'),
            'password' 			=> esc_html__('Password field is required', 'workreap_core'),
			'location' 			=> esc_html__('Location field is required', 'workreap_core'),
            'user_type'  		=> esc_html__('User type field is required.', 'workreap_core'),   
			'termsconditions'  		=> esc_html__('Terms and conditions field is required.', 'workreap_core'),       
                 
        );
		
		$phone_setting 		= '';
		$phone_mandatory	= '';
		$phone_option_reg	= '';
		if (function_exists('fw_get_db_settings_option')) {
			$phone_option		= fw_get_db_settings_option('phone_option');
			$login_register 	= fw_get_db_settings_option('enable_login_register');
			$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
			$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
			$phone_option_reg	= !empty($phone_option['enable']['phone_option_registration']) ? $phone_option['enable']['phone_option_registration'] : '';
		}

		if( !empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_mandatory) && $phone_mandatory == 'enable'){
			$validations['user_phone_number']	= esc_html__('Phone number is required', 'workreap_core');
		}
		
		if(!empty($login_register['gadget'])
		   && !empty($login_register['enable']['remove_username']) 
		   && $login_register['gadget'] === 'enable' 
		   && $login_register['enable']['remove_username'] === 'yes'){
			unset($validations['username']);
		}

		$hide_location	= 'no';
		if (function_exists('fw_get_db_settings_option')) {
			$login_register = fw_get_db_settings_option('enable_login_register');
			$hide_location 	= !empty( $login_register['enable']['registration']['enable']['hide_loaction'] ) ? $login_register['enable']['registration']['enable']['hide_loaction'] : 'no';
			if( !empty($hide_location) && $hide_location == 'yes' ){
				unset($validations['location']);
			}
		}
		
		foreach ( $validations as $key => $value ) {
            if ( empty( $_POST[$key] ) ) {
                $json['type'] = 'error';
                $json['message'] = $value;
                wp_send_json($json);
            }     
			
			if ($key === 'password') {
				do_action('workreap_strong_password_validation',$_POST[$key]);
            }
			
			//Validate email address
            if ( $key === 'email' ) {
                if ( !is_email( $_POST['email'] ) ) {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('Please add a valid email address.', 'workreap_core');
                    wp_send_json($json);
				}
				
				$user_exists 		 = email_exists( $_POST['email'] );
				if( $user_exists ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('This email already registered', 'workreap_core');
					wp_send_json($json);
				}
       		}       
			
			if ($key === 'termsconditions' && $_POST['termsconditions'] === 'no' ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please select terms', 'workreap_core');
				wp_send_json($json);
			}
       	}	
		
		//Registration terms
		if(!empty($_POST['termsconditions'])){
			if($_POST['termsconditions'] === 'no'){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please select terms and conditions', 'workreap_core');
				wp_send_json($json);
			}
		}
		
       	//Get Data
		$location   = !empty( $_POST['location'] ) ? esc_html( $_POST['location'] ) : '';
		$first_name = !empty( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
		$last_name  = !empty( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
		$email   	= !empty( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$username   = !empty( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : $email;
       	$password  	= !empty( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
       	$user_type 	= !empty( $_POST['user_type'] ) ? sanitize_text_field( $_POST['user_type'] ) : '';
		$termsconditions 	= !empty( $_POST['termsconditions'] ) ? sanitize_text_field( $_POST['termsconditions'] ) : '';

       	//Set User Role
       	$db_user_role = 'employers';
       	if( $user_type === 'freelancer' ){
       		$db_user_role = 'freelancers';
       	} else {
       		$db_user_role = 'employers';
       	}

		//User Registration
		$random_password = $password;
		$full_name 		 = $first_name.' '.$last_name;
		$user_nicename   = sanitize_title( $full_name );
		$username		 = $username;
		
		$userdata = array(
			'user_login'  		=>  $username,
			'user_pass'    		=>  $random_password,
			'user_email'   		=>  $email,  
			'user_nicename'   	=>  $user_nicename,  
			'display_name'   	=>  $full_name,  
		);
		
        $user_identity 	 = wp_insert_user( $userdata );
		
        if ( is_wp_error( $user_identity ) ) {
            $json['type'] = "error";
            $json['message'] = esc_html__("User already exists. Please try another one.", 'workreap_core');
            wp_send_json($json);
        } else {
        	global $wpdb;
            wp_update_user( array('ID' => esc_sql( $user_identity ), 'role' => esc_sql( $db_user_role ), 'user_status' => 1 ) );

            $wpdb->update(
                    $wpdb->prefix . 'users', array('user_status' => 1), array('ID' => esc_sql($user_identity))
            );

            update_user_meta( $user_identity, 'first_name', $first_name );
            update_user_meta( $user_identity, 'last_name', $last_name );             

			update_user_meta($user_identity, 'show_admin_bar_front', false);
            update_user_meta($user_identity, 'full_name', esc_html($full_name));
			update_user_meta($user_identity, 'termsconditions', esc_html($termsconditions));
			
			//Set country for unyson
			$location_data = array();
			if( !empty($hide_location) && $hide_location == 'no' ){
				$locations = get_term_by( 'slug', $location, 'locations' );
				if( !empty( $locations ) ){
					$location_data[0] = $locations->term_id;
					wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
				}
			}

			$verify_link			= '';
			$document_verify_link	= '';
			if( isset( $verify_user ) && $verify_user === 'verified' ){
				update_user_meta( $user_identity, '_is_verified', 'no' );
				//verification link
				$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
				update_user_meta( $user_identity, 'confirmation_key', $key_hash);
				$protocol = is_ssl() ? 'https' : 'http';
				$verify_link = esc_url(add_query_arg(array('key' => $key_hash.'&verifyemail='.$email), home_url('/', $protocol)));
			} else {
				update_user_meta( $user_identity, '_is_verified', 'yes' );
			}
			
			//Create Post
			$user_post = array(
                'post_title'    => wp_strip_all_tags( $full_name ),
                'post_status'   => 'publish',
                'post_author'   => $user_identity,
                'post_type'     => $db_user_role,
            );

            $post_id    = wp_insert_post( $user_post );
			
            if( !is_wp_error( $post_id ) ) {

				if( function_exists('workreap_insert_new_user') ){
					$account_types_permissions	= '';
					if ( function_exists( 'fw_get_db_settings_option' ) ) {
						$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
					}
					
					if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
						if($db_user_role == 'freelancers' ){
							$switch_user_role	= 'employers';
						} else {
							$switch_user_role	= 'freelancers';
						}
						
						workreap_insert_new_user($user_identity,$switch_user_role,$full_name,$_POST);
					}
				}
				
				//update short names
				$shortname_option	= '';
                if( function_exists('fw_get_db_settings_option')  ){
                    $shortname_option	= fw_get_db_settings_option('shortname_option', $default_value = null);
                }
				
				if(!empty($shortname_option) && $shortname_option === 'enable' ){
					$shor_name			= workreap_get_username($user_identity);
					$shor_name_array	= array(
											'ID'        => $post_id,
											'post_name'	=> sanitize_title($shor_name)
										);
					wp_update_post($shor_name_array);
				}
				
				//update phone number
				$fw_options = array();
				if( !empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_setting) && $phone_setting == 'enable' ) {
					$user_phone_number  				= !empty( $_POST['user_phone_number'] ) ? $_POST['user_phone_number'] : '';
					$fw_options['user_phone_number']    = $user_phone_number;
				}
				
				//Update user linked profile
				update_post_meta( $post_id, '_is_verified', 'no' );
				update_post_meta( $post_id, '_hourly_rate_settings', 'off' );
				update_user_meta( $user_identity, '_linked_profile', $post_id );
				
            	if( $db_user_role == 'employers' ){
					
					update_post_meta($post_id, '_user_type', 'employer');
            		update_post_meta($post_id, '_employees', 'employer');            		
					update_post_meta($post_id, '_followers', '');

            	} elseif( $db_user_role == 'freelancers' ){
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
				$fw_options['country']            = $location_data;
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

				
				update_post_meta($post_id, '_linked_profile', $user_identity);
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				
            	//Send email to users
            	if (class_exists('Workreap_Email_helper')) {
					
					$emailData = array();
					$emailData['name'] 				= $first_name;
					$emailData['password'] 			= $random_password;
					$emailData['email'] 			= $email;
					$emailData['phone'] 			= !empty($fw_options['user_phone_number']) ? $fw_options['user_phone_number'] : '';
					$emailData['verification_link'] = $verify_link;
					$emailData['document_verification_link'] = $document_verify_link;
					$emailData['site'] = $blogname;
					
					//Welcome Email
					if( $db_user_role === 'employers' ){
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_employer_email($emailData);
						}
					} else if( $db_user_role === 'freelancers' ){
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_freelacner_email($emailData);
						}
					}
					
					//Send code
					if( isset( $verify_user ) && $verify_user === 'verified' ){
						$json['verify_user'] 			= 'verified';
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_verification($emailData);
						}
					} else{
						$json['verify_user'] 			= 'none';
					}
					
					//Send admin email
					if (class_exists('WorkreapRegisterEmail')) {
						$email_helper = new WorkreapRegisterEmail();
						$email_helper->send_admin_email($emailData);
					}
		        }		    
    			
				//Push notification
				$push	= array();
				$push['receiver_id']	= $user_identity;
				$push['%name%']			= workreap_get_username($user_identity);
				$push['%email%']		= $email;
				$push['%password%']		= $random_password;
				$push['%site%']			= $blogname;
				$push['type']			= 'registration';
				$push['%verification_link%']	= $verify_link;
				
				$push['%replace_email%']		= $email;
				$push['%replace_password%']		= $random_password;
				$push['%replace_site%']			= $blogname;
				$push['%replace_verification_link%']	= $verify_link;

				if( $db_user_role == 'employers' ){
					do_action('workreap_user_push_notify',array($user_identity),'','pusher_employer_content',$push);
				}elseif( $db_user_role == 'freelancers' ){
					do_action('workreap_user_push_notify',array($user_identity),'','pusher_freelancers_content',$push);
				}
				
				do_action('workreap_user_push_notify',array($user_identity),'','pusher_verify_content',$push);
				
            } else {
            	$json['type'] = 'error';
                $json['message'] = esc_html__('Some error occurs, please try again later', 'workreap_core');                
                wp_send_json($json);
            }			

			//User Login
			$user_array = array();
			$user_array['user_login'] 	 = $email;
        	$user_array['user_password'] = $random_password;
			$status = wp_signon($user_array, false);
			
			if( isset( $verify_user ) && $verify_user === 'none' ){
				$json_message = esc_html__("Your account have been created. Please wait while your account is verified by the admin.", 'workreap_core');
			} else{
				$json_message = esc_html__("Your account have been created. Please verify your account through verification email, an email have been sent your email address.", 'workreap_core');
			}
			
			$user_type						= workreap_get_user_type( $user_identity );
			$freelancer_package_id			= workreap_get_package_type( 'package_type','trail_freelancer');
			$employer_package_id			= workreap_get_package_type( 'package_type','trail_employer');

			if( $user_type === 'employer' && !empty($employer_package_id) ) {
				workreap_update_pakage_data( $employer_package_id ,$user_identity,'' );
			} else if( $user_type === 'freelancer' && !empty($freelancer_package_id) ) {
				workreap_update_pakage_data( $freelancer_package_id ,$user_identity,'' );
			}


			//Redirect URL		
			$dashboard_page	= workreap_registration_redirect($user_identity);
			//Prepare Params
			$params_array	= array();
			$params_array['user_identity'] = $user_identity;
			
			if( $db_user_role === 'employers' ){
				$params_array['user_role'] = esc_html__("Employer", 'workreap_core');
			} else if( $db_user_role === 'freelancers' ){
				$params_array['user_role'] = esc_html__("Freelancer", 'workreap_core');
			}

			$params_array['type'] = 'register';
			
			do_action('wt_process_registration_child', $params_array);
			
			
			$json['type'] 			= 'success';
	        $json['message'] 		= $json_message;
	        $json['retrun_url'] 	= htmlspecialchars_decode($dashboard_page);
			wp_send_json($json);
		}		

	}
	add_action('wp_ajax_workreap_single_step_registration', 'workreap_single_step_registration');
	add_action('wp_ajax_nopriv_workreap_single_step_registration', 'workreap_single_step_registration');
	
}



/**
 * @OWL Carousel RTL
 * @return {}
 */
if (!function_exists('workreap_owl_rtl_check')) {

    function workreap_owl_rtl_check() {
        if (is_rtl()) {
            return 'true';
        } else {
            return 'false';
        }
    }
}

/**
 * @Splide RTL
 * @return {}
 */
if (!function_exists('workreap_splide_rtl_check')) {

    function workreap_splide_rtl_check() {
        if (is_rtl()) {
            return 'rtl';
        } else {
            return 'ltr';
        }
    }
}

/**
 * @OWL  RTL
 * @return {}
 */
if (!function_exists('workreap_rtl_check')) {

    function workreap_rtl_check() {
        if (is_rtl()) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('workreap_prepare_image_source')) {

    function workreap_prepare_image_source($post_id, $width = '300', $height = '300') {
        global $post;
        $thumb_url = wp_get_attachment_image_src($post_id, array(
            $width,
            $height
                ), true);
        if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
            return !empty($thumb_url[0]) ? $thumb_url[0] : '';
        } else {
            $thumb_url = wp_get_attachment_image_src($post_id, 'full', true);
            return !empty($thumb_url[0]) ? $thumb_url[0] : '';
        }
    }

}