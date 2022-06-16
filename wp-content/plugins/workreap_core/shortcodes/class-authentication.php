<?php
/**
 * File Type : Authentication
 */
if (!class_exists('SC_Workreap_Authentication')) {

    class SC_Workreap_Authentication {

        /**
         * Construct Shortcode
         */
        public function __construct() {
            add_shortcode('workreap_authentication', array(&$this, 'shortCodeCallBack'));
            add_shortcode('workreap_authentication_single', array(&$this, 'workreap_authentication_single'));
            add_shortcode('workreap_authentication_signup', array(&$this, 'workreap_authentication_signup'));
			add_shortcode('workreap_authentication_signin', array(&$this, 'workreap_authentication_signin'));
			add_shortcode('wt_post_button', array(&$this, 'workreap_wt_post_button'));
        }

        /**
         * Signup Form
         */
        public function workreap_authentication_single($atts) {
			$type	= !empty($atts['type'])	? $atts['type'] : '';
			$verify_user  		= 'verified';
            if (function_exists('fw_get_db_settings_option')) {
                $login_register = fw_get_db_settings_option('enable_login_register');
				$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
            }
            
            ob_start();
            
            if (!is_user_logged_in()) {
                if ( !empty($login_register) && $login_register['gadget'] === 'enable' ) {
                    do_action( 'workreap_registration_single_step',$type );
                } else{?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
                        <div class="doc-myaccount-content">
                            <p><?php esc_html_e('Login and registration is disabled by administrator.','workreap_core');?></p>
                        </div>
                     </div>
                    <?php
                }
            }else{                        
                global $current_user;
                $redirect   = workreap_login_redirect($current_user->ID);
	
				if(!empty($current_user->roles) && in_array('administrator',$current_user->roles) ){
					//do nothing
				}else{
                    if (!empty( $redirect )) {
                        wp_redirect( $redirect );
                        exit;
                    }else{
                        $this->workreap_loggedin_view();       
                    }
                }          
            }
            
            return ob_get_clean();
        }
        /**
         * Return Authentication Result
         */
        public function shortCodeCallBack($atts) {
			global $current_user, $wp_roles;		
			$atts = shortcode_atts( array(
						'title' 		=> '',
					), $atts, 'workreap_authentication' );
			
			ob_start();			                     
            
            $enable_resgistration 	= '';
            $enable_login 			= '';                       
            $site_key 				= '';
            $login_reg_link 		= '';
            $signup_page_slug 		= '';
			$verify_user  			= 'verified';
            $protocol 				= is_ssl() ? 'https' : 'http';

            if (function_exists('fw_get_db_settings_option')) {               
                $login_register = fw_get_db_settings_option('enable_login_register');  
				$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
            }          
 
            if (!empty($login_register['enable']['login_reg_page'])) {
                $login_reg_link = $login_register['enable']['login_reg_page'];
            }

            if(!empty( $login_reg_link[0] )){
                $signup_page_slug = esc_url(get_permalink((int) $login_reg_link[0]));
            }
            
            $enable_registration = !empty( $login_register['enable']['registration']['gadget'] ) ? $login_register['enable']['registration']['gadget'] : 'enable';
            $enable_login = !empty( $login_register['enable']['login'] ) ? $login_register['enable']['login'] : 'enable';
			$step 		= !empty( $_GET['step'] ) ? intval( $_GET['step'] ) : 1;
            $redirect   = !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';  
			
            if (!is_user_logged_in()) {               
                if ( !empty( $enable_registration ) && $enable_registration === 'enable' ) {?>
				<div class="wt-content">
					<div class="wt-themeform wt-formlogin-register">
						<?php echo do_shortcode('[workreap_authentication_signup]');?>              
					</div>
				</div>
				<?php }else{ ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
						<div class="doc-myaccount-content">
							<p><?php esc_html_e('Registration is disabled by administrator.','workreap_core');?></p>
						</div>
					 </div>
					<?php
				}
            }else{
				$this->workreap_do_verify_registration();   
            }        
			
			echo ob_get_clean();
        }

		/**
         * Login View
         */
        public function workreap_loggedin_view() {
			global $current_user;
			$username = workreap_get_username($current_user->ID);
			$link_id		= workreap_get_linked_profile_id( $current_user->ID );
			if ( apply_filters('workreap_get_user_type', $current_user->ID) === 'employer' ){
				$avatar = apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 150, 'height' => 150), $link_id), array('width' => 150, 'height' => 150) 
									);
			} else{
				$avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 150, 'height' => 150), $link_id), array('width' => 150, 'height' => 150) 
									);
			}
			
			$profile_page	= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$profile_page  = workreap_get_search_page_uri('dashboard');
			}
			?>
			
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
				<div class="doc-myaccount-content">
					<div class="wt-haslayout wt-sectionspace">
						<div class="container">
							<div class="row justify-content-md-center">
								<div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
									<div class="wt-registerformhold wt-registerformmain">
										<div class="tab-content wt-registertabcontent">
											<div class="tab-pane active step-four-contents" id="four">
												<?php if( current_user_can('administrator') ) {?>
														<span><?php esc_html_e('You have not any privilege to view this page.','workreap_core');?></span>
														<?php
													}else{?>
													<p><?php esc_html_e('Hello','workreap_core');?> <strong><?php echo esc_attr( $username );?></strong> (<?php esc_html_e('not','workreap_core');?> <?php echo esc_attr( $username );?>? <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e('Sign out','workreap_core');?></a>)</p>
													<div class="form-group wt-btnarea">
														<p><?php esc_html_e('You can view your dashboard by clicking below link','workreap_core');?></p>
														<a class="wt-btn" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('profile', $current_user->ID,'','settings'); ?>"><?php esc_html_e('Go to dashboard','workreap_core');?></a>
													</div>
												<?php }?>
												
											</div>                                            
										</div>                                        
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			 </div>
			<?php
		}

        /**
         * Verify Registration
         */
        public function workreap_do_verify_registration() {
            global $current_user;            
            $verified_key = get_user_meta($current_user->ID, '_is_verified', true);
            if( !empty( $verified_key ) && $verified_key === 'yes' ){
                $this->workreap_loggedin_view();
            } else { ?>
                <?php do_action( 'workreap_registration_step_four' ); ?>
            <?php                    
            }            
        }      
        
        /**
         * Confirmation Message
         */
        public function workreap_show_confirmation_message() {
            global $current_user;            
            $verified_key = get_user_meta($current_user->ID, '_is_verified', true);
            if( !empty( $verified_key ) && $verified_key === 'yes' ){
                $this->workreap_loggedin_view();
            } else { ?>
                <div class="wt-haslayout wt-sectionspace">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
                                <div class="wt-registerformhold">
                                    <div class="tab-content wt-registertabcontent">
                                        <div class="tab-pane active step-four-contents" id="four">
                                            <?php do_action( 'workreap_registration_step_four' ); ?>
                                        </div>                                            
                                    </div>                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php                    
            }            
        }
        
		/**
         * Signup Form
         */
        public function workreap_authentication_signup($atts) {
			$verify_user  		= 'verified';
            if (function_exists('fw_get_db_settings_option')) {
                $login_register = fw_get_db_settings_option('enable_login_register');
				$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
            }

			$login_page	= '';     

			if (!empty($login_register['enable']['login_page'][0]) && !empty($login_register['enable']['login_signup_type']) && $login_register['enable']['login_signup_type'] == 'pages' ) {
				$login_page = get_the_permalink($login_register['enable']['login_page'][0]);
			}

            ob_start();
            
            if (!is_user_logged_in()) {
                if ( !empty($login_register) && $login_register['gadget'] === 'enable' ) {
                    $atts = shortcode_atts( array(
                        'title'     => '',
                        'single'    => 'false'
                    ), $atts, 'workreap_authentication_signup' );

                    $step 		= !empty( $_GET['step'] ) ? intval( $_GET['step'] ) : 1;                    
                    $action 	= !empty( $_GET['action'] ) ? $_GET['action'] : 1;
                    $protocol 	= is_ssl() ? 'https' : 'http';                                        
                ?>                
                <div class="wt-haslayout wt-sectionspace">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
                                <div class="wt-registerformhold">
                                    <div class="tab-content wt-registertabcontent">
                                        <?php if( $step == 2 ){ ?>                          
                                            <div class="tab-pane active step-two-contents" id="two">
                                                <?php do_action( 'workreap_registration_step_two' ); ?>
                                            </div>
                                        <?php } elseif( $step == 3 ){ ?>                          
                                            <?php if( is_user_logged_in() ){ ?>
                                                <div class="tab-pane active step-three-contents" id="three">
                                                    <?php do_action( 'workreap_registration_step_four' ); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="tab-pane active step-one-contents" id="one">
                                                    <?php do_action( 'workreap_registration_step_one' ); ?>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="tab-pane active step-one-contents" id="one">
                                                <?php do_action( 'workreap_registration_step_one' ); ?>
                                            </div> 
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
                <?php }else{?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
                        <div class="doc-myaccount-content">
                            <p><?php esc_html_e('Login and registration is disabled by administrator.','workreap_core');?></p>
                        </div>
                     </div>
                    <?php
                }
            }else{              
                global $current_user;
                $redirect   = workreap_login_redirect($current_user->ID);
	
				if(!empty($current_user->roles) && in_array('administrator',$current_user->roles) ){
					//do nothing
				}else{
                    if (!empty( $redirect )) {
                        wp_redirect( $redirect );
                        exit;
                    }else{
                        $this->workreap_loggedin_view();       
                    }
                }                     
            }
            
            return ob_get_clean();
        }
		
		/**
         * Signin Form
         */
        public function workreap_authentication_signin($atts) {
			$verify_user  		= 'verified';
            if (function_exists('fw_get_db_settings_option')) {
                $login_register = fw_get_db_settings_option('enable_login_register');
				$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
            }
 
            ob_start();
            
            if (!is_user_logged_in()) {
                if ( !empty($login_register) && $login_register['gadget'] === 'enable' ) {
                    $atts = shortcode_atts( array(
                        'title'     => '',
                        'single'    => 'false'
                    ), $atts, 'workreap_authentication_signin' );

                    $step 		= !empty( $_GET['step'] ) ? intval( $_GET['step'] ) : 1;                    
                    $action 	= !empty( $_GET['action'] ) ? $_GET['action'] : 1;
                    $protocol 	= is_ssl() ? 'https' : 'http';                                        
                ?>                
                <div class="wt-haslayout wt-sectionspace">
                    <div class="tab-content wt-registertabcontent">
						<?php do_action('workreap_login_single_step','shortcode');?>
					</div>
                </div>                
                <?php }else{?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
                        <div class="doc-myaccount-content">
                            <p><?php esc_html_e('Login and registration is disabled by administrator.','workreap_core');?></p>
                        </div>
                     </div>
                    <?php
                }
            }else{
                global $current_user;
                $redirect   = workreap_login_redirect($current_user->ID);
	
				if(!empty($current_user->roles) && in_array('administrator',$current_user->roles) ){
					//do nothing
				}else{
                    if (!empty( $redirect )) {
                        wp_redirect( $redirect );
                        exit;
                    }else{
                        $this->workreap_loggedin_view();       
                    }
                } 
            }
            
            return ob_get_clean();
        }
      
    }
    new SC_Workreap_Authentication();
}