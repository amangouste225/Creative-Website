<?php
/**
 *
 * Class used as base to create theme header
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
if (!class_exists('Workreap_Prepare_Headers')) {

    class Workreap_Prepare_Headers {

        function __construct() {
            add_action('workreap_do_process_headers', array(&$this, 'workreap_do_process_headers'));
            add_action('workreap_prepare_headers', array(&$this, 'workreap_prepare_header'));
            add_action('workreap_systemloader', array(&$this, 'workreap_systemloader'));
            add_action('workreap_app_available', array(&$this, 'workreap_app_available'));
			add_action('wp_head', array(&$this, 'workreap_update_metatags'));
        }

		/**
         * @system Update metadata
         * @return {}
         * @author amentotech
         */

		public function workreap_update_metatags() {
			$shortname_option	= '';
			if( function_exists('fw_get_db_settings_option')  ){
				$shortname_option	= fw_get_db_settings_option('shortname_option', $default_value = null);
			}
			
			if( (is_singular( 'freelancers') || is_singular( 'employers') )  && !empty($shortname_option) && $shortname_option === 'enable'  ){
				
				$post_id			= get_the_ID();
				$am_seo_title		= '';
				if( function_exists(  'workreap_get_username' ) && !empty($post_id) ) {
					$am_seo_title		= workreap_get_username('',$post_id);
				}
				if( !empty($am_seo_title) ){
					ob_start(); ?>
					<meta name="title" content="<?php echo esc_attr($am_seo_title);?>" />
					<?php
						echo ob_get_clean(); 
				}
			}
			
		}
        /**
         * @system app available
         * @return {}
         * @author amentotech
         */
        public function workreap_app_available() {
            ob_start();
			if (function_exists('fw_get_db_settings_option')) {
                $app_available = fw_get_db_settings_option('app_available', $default_value = null);
            }

            if ( !empty( $app_available['gadget'] ) && $app_available['gadget'] === 'enable' && $app_available['enable']['link'] ) {?>
               <a target="_blank" class="wt-appavailable" href="<?php echo esc_url($app_available['enable']['link']);?>">
                    <img class="bubbleicon" src="<?php echo esc_url( get_template_directory_uri()); ?>/images/bubble.png" alt="<?php esc_attr_e('App Available','workreap');?>">
                    <img class="android-logo" src="<?php echo esc_url( get_template_directory_uri()); ?>/images/android-logo.png" alt="<?php esc_attr_e('App Available','workreap');?>">
                </a>
               <?php
            }
			echo ob_get_clean();
        }

        /**
         * @system loader
         * @return {}
         * @author amentotech
         */
        public function workreap_systemloader() {
            $preloader = '';
            if (function_exists('fw_get_db_settings_option')) {
                $preloader = fw_get_db_settings_option('preloader', $default_value = null);
                $maintenance = fw_get_db_settings_option('maintenance');
            }

            if (isset($maintenance) && $maintenance === 'disable') {
                if (isset($preloader['gadget']) && $preloader['gadget'] === 'enable') {
                    if ( isset($preloader['enable']['preloader']['gadget']) && $preloader['enable']['preloader']['gadget'] === 'default' ) {?>
                        <div class="preloader-outer">
                            <div class="wt-preloader-holder">
                                <div class="wt-loader"></div>
                            </div>
                        </div>
                        <?php
                    } elseif (isset($preloader['enable']['preloader']['gadget']) && $preloader['enable']['preloader']['gadget'] === 'custom' && !empty($preloader['enable']['preloader']['custom']['loader']['url'])
                    ) {
                        ?>
                        <div class="preloader-outer">
                            <div class="preloader-customloader">
                                <img src="<?php echo esc_url($preloader['enable']['preloader']['custom']['loader']['url']); ?>" alt="<?php esc_attr_e('loader', 'workreap'); ?>" />
                            </div>
                        </div>
                        <?php
                    }
                }
            }
        }

        /**
         * @Prepare headers
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_headers() {
            global $current_user;

            $loaderDisbale = '';
            if (function_exists('fw_get_db_settings_option')) {
                $header_type = fw_get_db_settings_option('header_type');
                $maintenance = fw_get_db_settings_option('maintenance');
				$enable_login_register = fw_get_db_settings_option('enable_login_register');
            } else {
                $maintenance = '';
            }

            $post_name = workreap_get_post_name();

            if (( isset($maintenance) && $maintenance == 'enable' && !is_user_logged_in() ) || $post_name === "coming-soon"
            ) {
                $loaderDisbale = 'elm-display-none';
            }

            get_template_part('template-parts/template', 'comingsoon');

            //demo ready
			if ( apply_filters('workreap_get_domain',false) === true ) {
				if( $post_name === "home-page-three" ){
					$header_type['gadget'] = 'header_v3';
				} else if( $post_name === "home-page-four" ){
					$header_type['gadget'] = 'header_v5';
				}
			}
			
			if( is_page_template('directory/dashboard.php') ) {
				$this->workreap_do_process_header_v1();
			}else{
				if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v2' ){
					$this->workreap_do_process_header_v2();
				} elseif( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v3' ){
					$this->workreap_do_process_header_v3();
				} elseif( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v4' ){
					$this->workreap_do_process_header_v4();
				} elseif( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v5' ){
					$this->workreap_do_process_header_v5();
				} elseif( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v6' ){
					$this->workreap_do_process_header_v6();
				} else{
					$this->workreap_do_process_header_v1();
				}
			}
			
        }

        /**
         * @Prepare header v1
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_header_v1() {
            global $current_user;

            $login_register = '';
            if (function_exists('fw_get_db_settings_option')) {
                $header_type = fw_get_db_settings_option('header_type');
                $main_logo = fw_get_db_settings_option('main_logo');
            } else {
                $main_logo = '';
                $header_type = '';
            }

            if (!empty($main_logo['url'])) {
                $logo = $main_logo['url'];
            } else {
                $logo = get_template_directory_uri() . '/images/logo.png';
            }
			
			//just for demo
			if ( apply_filters('workreap_get_domain',false) === true ) {
				$post_name = workreap_get_post_name();
				if( $post_name === "home-page-v5" ){
					$logo = get_template_directory_uri() . '/images/logo_head_v2.png';
				}
			}

            $search_form        = !empty($header_type['header_v1']['search_form']) ? $header_type['header_v1']['search_form'] : '';

            $header_search = 'wt-header-not';
            if( $search_form === 'show_all' || $search_form === 'hide_on_home' ){
                if( ( is_home() || is_front_page() ) && $search_form === 'hide_on_home' ) {
                    $header_search = 'wt-search-not';
                } else{
                    $header_search = 'wt-search-have';
                }
            }

            ?>
            <header id="wt-header" class="wt-header wt-haslayout workreap-header-v1 <?php echo esc_attr($header_search);?>">
				<div class="wt-navigationarea">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php $this->workreap_prepare_logo($logo); ?>
								<?php $this->workreap_prepare_search_form();?>
								<div class="wt-rightarea">
									<nav id="wt-nav" class="wt-nav navbar-expand-lg">
										<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
											<i class="lnr lnr-menu"></i>
										</button>
										<div class="collapse navbar-collapse wt-navigation" id="navbarNav">
											<?php Workreap_Prepare_Headers::workreap_prepare_navigation('primary-menu', '', 'navbar-nav nav-Js', '0'); ?>
										</div>
									</nav>
                                    <?php $this->workreap_prepare_registration(); ?>
                                    <?php if (!is_page_template('directory/dashboard.php')) {?>
                                    	<div class="wt-respsonsive-search"><a href="#" onclick="event_preventDefault(event);" class="wt-searchbtn"><i class="fa fa-search"></i></a></div>
                                    <?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
            <?php
        }
		
		 /**
         * @Prepare header v2
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_header_v2() {
            global $current_user;

            $login_register = '';
            if (function_exists('fw_get_db_settings_option')) {
                $header_type 	= fw_get_db_settings_option('header_type');
                $main_logo		= fw_get_db_settings_option('main_logo');
            } else {
                $main_logo = '';
                $header_type = '';
            }

			$search_form       		 = !empty($header_type['header_v2']['search_form']) ? $header_type['header_v2']['search_form'] : '';
			$transparent_logo        = !empty($header_type['header_v2']['main_logo']) ? $header_type['header_v2']['main_logo'] : '';
			
            if (!empty($transparent_logo['url'])) {
                $t_logo = $transparent_logo['url'];
            }else {
                $t_logo = get_template_directory_uri() . '/images/transparent.png';
            }

            if (!empty($main_logo['url'])) {
                $logo = $main_logo['url'];
            }else {
                $logo = get_template_directory_uri() . '/images/logo.png';
            } 

            $header_search = 'wt-header-not';
            if( $search_form === 'show_all' || $search_form === 'hide_on_home' ){
                if( ( is_home() || is_front_page() ) && $search_form === 'hide_on_home' ) {
                    $header_search = 'wt-search-not';
                } else{
                    $header_search = 'wt-search-have';
                }
            }

			//is titlebar enabled
			 $page_id = '';
			 $object_id = get_queried_object_id();
			
			if((get_option('show_on_front') && get_option('page_for_posts') && is_home()) ||
				(get_option('page_for_posts') && is_archive() && !is_post_type_archive()) && !(is_tax('product_cat') || is_tax('product_tag')) || (get_option('page_for_posts') && is_search())) {
					$page_id = get_option('page_for_posts');		
			} else {
				if(isset($object_id)) {
					$page_id = $object_id;
				}
			}
			
			$titlebar_disabled = 'wt-headervthhree';
			
			if(!is_home() && !is_front_page()){
				if( is_404() 
					|| is_archive() 
					|| is_search() 
					|| is_category() 
					|| is_tag() 
				) {
					if(function_exists('fw_get_db_settings_option')){
						$titlebar_type 	= fw_get_db_settings_option('titlebar_type', true);
						if(  isset( $titlebar_type['gadget'] ) && $titlebar_type['gadget'] === 'none' ) {
							$titlebar_disabled = 'wt-titlebar-disabled';
						} 
					}
				} else {
					if(function_exists('fw_get_db_settings_option')){
						$titlebar_type 		    = fw_get_db_post_option($page_id, 'titlebar_type', true);
						$default_titlebar_type 	= fw_get_db_settings_option('titlebar_type', true);

						if( isset( $titlebar_type ) && is_array( $titlebar_type ) ){
							if( isset( $titlebar_type ) && is_array( $titlebar_type ) ){
								if(  isset( $titlebar_type['gadget'] ) && $titlebar_type['gadget'] === 'none' ){
									$titlebar_disabled = 'wt-titlebar-disabled';
								} else{
									if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
										$titlebar_disabled = 'wt-titlebar-disabled';
									}
								}
							} else{
								if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
									$titlebar_disabled = 'wt-titlebar-disabled';
								}
							}
						}else{
							if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
								$titlebar_disabled = 'wt-titlebar-disabled';
							}
						}
					}
				}
			}
			
            ?>
            <header id="wt-header" class="wt-header wt-haslayout workreap-header-v2 <?php echo esc_attr($header_search);?> <?php echo esc_attr($titlebar_disabled);?>">
				<div class="wt-navigationarea">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <?php $this->workreap_prepare_logo($logo,$t_logo,'transparent_v2'); ?>
								<?php $this->workreap_prepare_search_form();?>
								<div class="wt-rightarea">
									<nav id="wt-nav" class="wt-nav navbar-expand-lg">
										<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
											<i class="lnr lnr-menu"></i>
										</button>
										<div class="collapse navbar-collapse wt-navigation" id="navbarNav">
											<?php Workreap_Prepare_Headers::workreap_prepare_navigation('primary-menu', '', 'navbar-nav nav-Js', '0'); ?>
										</div>
									</nav>
                                    <?php $this->workreap_prepare_registration(); ?>
                                     <?php if (!is_page_template('directory/dashboard.php')) {?>
                                    	<div class="wt-respsonsive-search"><a href="#" onclick="event_preventDefault(event);" class="wt-searchbtn"><i class="fa fa-search"></i></a></div>
                                    <?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
            <?php
        }
		
		/**
         * @Prepare header v3
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_header_v3() {
            global $current_user;

            $login_register = '';
            if (function_exists('fw_get_db_settings_option')) {
                $header_type = fw_get_db_settings_option('header_type');
                $main_logo = fw_get_db_settings_option('main_logo');
            } else {
                $main_logo = '';
                $header_type = '';
            }

            if (!empty($main_logo['url'])) {
                $logo = $main_logo['url'];
            } else {
                $logo = get_template_directory_uri() . '/images/logo.png';
            }

            ?>
			<header id="wt-header" class="wt-header wt-headervfour wt-haslayout workreap-header-v3">
				<div class="wt-navigationarea">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php $this->workreap_prepare_logo($logo); ?>
                                <div class="wt-rightarea">
                                    <?php $this->workreap_prepare_registration(); ?>
                                </div>
								<nav id="wt-nav" class="wt-nav wt-navfour navbar-expand-lg">
									<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
										<i class="lnr lnr-menu"></i>
									</button>
									<div class="collapse navbar-collapse wt-navigation" id="navbarNav">
										<?php Workreap_Prepare_Headers::workreap_prepare_navigation('primary-menu', '', 'navbar-nav nav-Js', '0'); ?>
									</div>
									<?php $this->workreap_prepare_search_formv3();?>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</header>            
            <?php
        }
		
		/**
         * @Registration modal box
         * @return {}
         * @author amentotech
         */
		public function workreap_registration_model() {
			$login_register = '';
			$step_two_title = '';
			$step_two_desc  = '';
			$terms_link 	= '';
			$terms_text 	= '';
			$verify_user  	= 'verified';
			$hide_departments  	= '';

			$signup_page_slug = workreap_get_signup_page_url('step', '1');	           

			if (function_exists('fw_get_db_settings_option')) {
				$login_register = fw_get_db_settings_option('enable_login_register');
				$step_two_title = fw_get_db_settings_option('step_two_title');
				$step_two_desc = fw_get_db_settings_option('step_two_desc');   
				$verify_user = fw_get_db_settings_option('verify_user', $default_value = null);
				$hide_departments = fw_get_db_settings_option('hide_departments', $default_value = null);
			}

			if( empty( $step_two_title ) ){
				$step_two_title = esc_html__('Join For a Good Start', 'workreap');
			}              

			if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {
				$terms_link = !empty( $login_register['enable']['registration']['enable']['terms_link'] ) ? $login_register['enable']['registration']['enable']['terms_link'] : '';
				$terms_link = !empty( $terms_link ) ? get_the_permalink($terms_link[0]) : '';
				$term_text = !empty( $login_register['enable']['registration']['enable']['term_text'] ) ? $login_register['enable']['registration']['enable']['term_text'] : esc_html__('Agree our terms and conditions', 'workreap');
			}
			ob_start();
			?>
			<div class="modal fade wt-loginpopup wt-registration-parent-model" tabindex="-1" role="dialog" id="joinpopup" data-backdrop="static">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="wt-modalcontentvtwo modal-content">
						<a href="#" onclick="event_preventDefault(event);" class="wt-closebtn close"><i class="lnr lnr-cross" data-dismiss="modal"></i></a>
						<div class="wt-registration-content-model"><?php do_action('workreap_registration_step_one','wt-model-reg1');?></div>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();
		}

		/**
         * @ Single page Registration modal box
         * @return {}
         * @author amentotech
         */
		public function workreap_single_step_registration_model() {
			ob_start();
            if (function_exists('fw_get_db_settings_option')) { 
                $login_register = fw_get_db_settings_option('enable_login_register');
                $image_url      = !empty($login_register['enable']['single_step_image']['url']) ? $login_register['enable']['single_step_image']['url'] : ''; 

            }
            
            $class = ''; 
            if(empty($image_url)){
                $class = 'wt-without'; 
            }
			?>
				<div class="modal fade wt-joinnowpopup <?php echo esc_attr($class);?>" tabindex="-1" role="dialog" id="joinpopup" data-backdrop="static">
					<div class="modal-dialog" role="document">
						<div class="wt-modalcontent modal-content">
							<a href="#" class="wt-closebtn close"><i class="ti-close" data-dismiss="modal"></i></a>
							<?php do_action('workreap_registration_single_step');?>
						</div>
					</div>
				</div>
			<?php
			echo ob_get_clean();
		}

		
		/**
         * @Single page login
         * @return {}
         * @author amentotech
         */
		public function workreap_single_step_login_model() {
			ob_start();
            if (function_exists('fw_get_db_settings_option')) { 
                $login_register = fw_get_db_settings_option('enable_login_register');
                $image_url      = !empty($login_register['enable']['single_step_image']['url']) ? $login_register['enable']['single_step_image']['url'] : ''; 

            }
            
            $class = ''; 
            if(empty($image_url)){
                $class = 'wt-without'; 
            }
			?>
				<div class="modal fade wt-joinnowpopup <?php echo esc_attr($class);?>" tabindex="-1" role="dialog" id="loginpopup" data-backdrop="static">
					<div class="modal-dialog" role="document">
						<div class="wt-modalcontent modal-content">
							<a href="#" class="wt-closebtn close"><i class="ti-close" data-dismiss="modal"></i></a>
							<?php do_action('workreap_login_single_step');?>
						</div>
					</div>
				</div>
			<?php
			echo ob_get_clean();
		}
		
		/**
         * @Login POP UP
         * @return {}
         * @author amentotech
         */
		public function workreap_login_model() {
			$terms_link 	= '';
			$login_register	= array();
			$captcha_settings = '';
			if (function_exists('fw_get_db_settings_option')) {
				$login_register = fw_get_db_settings_option('enable_login_register');
				$enable_google_connect 	 = fw_get_db_settings_option('enable_google_connect', $default_value = null);
				$enable_facebook_connect = fw_get_db_settings_option('enable_facebook_connect', $default_value = null);
				$enable_linkedin_connect = fw_get_db_settings_option('enable_linkedin_connect', $default_value = null);
				$captcha_settings = fw_get_db_settings_option('captcha_settings', $default_value = null);
			}

			if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {
				
				$terms_link 	= !empty( $login_register['enable']['registration']['enable']['terms_link'] ) ? $login_register['enable']['registration']['enable']['terms_link'] : '';
				
				$terms_link 	= !empty( $terms_link ) ? get_the_permalink($terms_link[0]) : '';
				$term_text 		= !empty( $login_register['enable']['registration']['enable']['term_text'] ) ? $login_register['enable']['registration']['enable']['term_text'] : esc_html__('Agree our terms and conditions', 'workreap');
			}
			
			ob_start();
			?>
			
			<div class="modal fade wt-loginpopup custom-login-wrapper" tabindex="-1" role="dialog" id="loginpopup" data-backdrop="static">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="wt-modalcontentvtwo modal-content">
						<div class="wt-popuptitle">
							<h4><?php esc_html_e('Login','workreap');?></h4>
							<a href="#" onclick="event_preventDefault(event);" class="wt-closebtn close"><i class="lnr lnr-cross" data-dismiss="modal"></i></a>
						</div>
						<div class="modal-body">
							<div class="login-wt-wrap do-login-form">
								<form class="wt-formtheme wt-formlogin do-login-form">
									<fieldset>
										<div class="form-group">
											<input type="text" name="username" value="" class="form-control" placeholder="<?php esc_attr_e('Type email or username','workreap');?>" required="">
										</div>
										<div class="form-group">
											<input type="password" name="password" value="" class="form-control" placeholder="<?php esc_attr_e('Password*','workreap');?>">
										</div>
										<?php if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
											<div class="domain-captcha form-group">
												<div id="recaptcha_signin"></div>
											</div>
										<?php }?>
										<div class="form-group wt-btnarea">
											<span class="wt-checkbox">
												<input id="wt-loginp" type="checkbox" name="rememberme">
												<label for="wt-loginp"><?php esc_html_e('Keep me logged in','workreap');?></label>
											</span>
											<button type="submit" class="wt-btn do-login-button"><?php esc_html_e('login','workreap');?></button>
										</div>
									</fieldset>
								</form>
								<?php 
								if (  ( isset($enable_google_connect) && $enable_google_connect === 'enable' ) 
								   || ( isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) 
								   || ( isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) 
								) {?>
									<span class="wt-optionsbar"><em><?php esc_html_e('or','workreap');?></em></span>
									<div class="wt-loginicon">
										<ul>
											<?php if (  isset($enable_facebook_connect) && $enable_facebook_connect === 'enable' ) {?><li class="wt-facebook"><a href="#" onclick="event_preventDefault(event);" class="sp-fb-connect"><i class="fa fa-facebook-f"></i><?php esc_html_e('Facebook','workreap');?></a></li><?php }?>
											<?php if (  isset($enable_google_connect) && $enable_google_connect === 'enable' ) {?><li class="wt-googleplus"><a href="#" onclick="event_preventDefault(event);" class="" id="wt-gconnect"><i class="fa fa-google"></i><?php esc_html_e('Google','workreap');?></a></li><?php }?>
											<?php if (  isset($enable_linkedin_connect) && $enable_linkedin_connect === 'enable' ) {do_action('workreap_linkedin_login_button');}?>
										</ul>
									</div>
								<?php }?>
							</div>
							<form class="wt-formtheme wt-loginform do-forgot-password-form wt-hide-form">
								<div class="form-group">
									<input type="email" name="email" class="form-control get_password" placeholder="<?php esc_attr_e('Email', 'workreap'); ?>">
								</div>
								<?php if( isset( $captcha_settings ) && $captcha_settings === 'enable' ) {?>
									<div class="domain-captcha form-group">
										<div id="recaptcha_forgot"></div>
									</div>
								<?php }?>
								<div class="wt-logininfo">
									<a href="#" onclick="event_preventDefault(event);" class="wt-btn do-get-password-btn"><?php esc_html_e('Get Password','workreap');?></a>
								</div> 
							</form>
						</div>
						<div class="modal-footer">
							<?php if( !empty($term_text) && !empty( $terms_link ) ) { ?>
							<div class="wt-popup-footerterms">
								<span>
									<?php echo esc_html( $term_text ); ?>
									<?php if( !empty( $terms_link ) ) { ?>
										<a href="<?php echo esc_url( $terms_link ); ?>"><?php esc_html_e('Terms & Conditions', 'workreap'); ?></a>
									<?php } ?>
								</span>
							</div>
							<?php } ?>
							
							<div class="wt-loginfooterinfo">
								<?php if (!empty( $login_register ) && $login_register['enable']['registration']['gadget'] === 'enable') {?>
									<a href="#" onclick="event_preventDefault(event);" class="wt-registration-poup"><em><?php esc_html_e('Not a member?','workreap');?></em>&nbsp;<?php esc_html_e('Signup Now','workreap');?></a>
								<?php } ?>
								<a href="#" onclick="event_preventDefault(event);" class="wt-forgot-password"><?php esc_html_e('Reset password?','workreap');?></a>
								<a href="#" onclick="event_preventDefault(event);" class="login-revert wt-hide-form"><?php esc_html_e('Sign In','workreap');?></a>
							</div>
							
						 </div>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();
		}
		
		/**
         * @Prepare header v4
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_header_v4() {
            global $current_user;

            $login_register = '';
            if (function_exists('fw_get_db_settings_option')) {
                $header_type = fw_get_db_settings_option('header_type');
                $main_logo = fw_get_db_settings_option('main_logo');
            } else {
                $main_logo = '';
                $header_type = '';
            }

            if (!empty($main_logo['url'])) {
                $logo = $main_logo['url'];
            } else {
                $logo = get_template_directory_uri() . '/images/logo.png';
            }

            ?>
			<header id="wt-header" class="wt-header wt-headervthhree wt-headernine wt-haslayout workreap-header-v4">
				<div class="wt-navigationarea">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php $this->workreap_prepare_logo($logo); ?>
                                <nav id="wt-nav" class="wt-nav navbar-expand-lg">
									<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation','workreap');?>">
											<i class="lnr lnr-menu"></i>
									</button>
                               		<div class="collapse navbar-collapse wt-navigation" id="navbarNav">
										<?php Workreap_Prepare_Headers::workreap_prepare_navigation('primary-menu', '', 'navbar-nav nav-Js', '0'); ?>
									</div>
                                </nav>
                                <?php $this->workreap_prepare_search_formv3('header_v4');?>
                                <div class="wt-rightarea wt-headeroptions">
                                    <?php $this->workreap_prepare_registration(); ?>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</header>           
            <?php
		}

		/**
         * @Prepare header v5
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_header_v5() {
            global $current_user;

            if (function_exists('fw_get_db_settings_option')) {
                $header_type 	= fw_get_db_settings_option('header_type');
                $main_logo		= fw_get_db_settings_option('main_logo');
            } else {
                $main_logo = '';
                $header_type = '';
            }

			$search_form		 = !empty($header_type['header_v5']['search_form']) ? $header_type['header_v5']['search_form'] : '';
			$transparent_logo    = !empty($header_type['header_v5']['main_logo']) ? $header_type['header_v5']['main_logo'] : '';
			$show_categories    = !empty($header_type['header_v5']['show_categories']) ? $header_type['header_v5']['show_categories'] : '';
			
			
            if (!empty($transparent_logo['url'])) {
                $t_logo = $transparent_logo['url'];
            }else {
                $t_logo = get_template_directory_uri() . '/images/prologo_transparent.png';
            }
			
            if (!empty($main_logo['url'])) {
                $logo = $main_logo['url'];
            }else {
                $logo = get_template_directory_uri() . '/images/prologo.png';
            } 

            $header_search = 'wt-header-not';
            if( $search_form === 'show_all' || $search_form === 'hide_on_home' ){
                if( ( is_home() || is_front_page() ) && $search_form === 'hide_on_home' ) {
                    $header_search = 'wt-search-not';
                } else{
                    $header_search = 'wt-search-have';
                }
            }

            ?>
            <header id="wt-header" class="wt-header wt-haslayout wt-headereleven wt-header-v5 <?php echo esc_attr($header_search);?>">
				<div class="wt-navigationarea">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php $this->workreap_prepare_logo($logo, $t_logo,'transparent_v2'); ?>
								<?php $this->workreap_prepare_search_form();?>
								<div class="wt-rightarea">
									<nav id="wt-nav" class="wt-nav navbar-expand-lg">
										<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
											<i class="lnr lnr-menu"></i>
										</button>
										<div class="collapse navbar-collapse wt-navigation" id="navbarNav">
											<?php Workreap_Prepare_Headers::workreap_prepare_navigation('primary-menu', '', 'navbar-nav nav-Js', '0'); ?>
										</div>
									</nav>
                                    <?php $this->workreap_prepare_registration(); ?>
                                     <?php if (!is_page_template('directory/dashboard.php')) {?>
                                    	<div class="wt-respsonsive-search"><a href="#" onclick="event_preventDefault(event);" class="wt-searchbtn"><i class="fa fa-search"></i></a></div>
                                    <?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if(!empty($show_categories) && $show_categories === 'yes' && !is_page_template('directory/dashboard.php')){?>
					<div class="wt-categoriesnav-holder">
						<div class="container-fluid">
							<div class="row">
								<nav class="wt-categories-nav navbar-expand-lg">
									<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavbar" aria-controls="navbarNavbar" aria-expanded="false" aria-label="Toggle navigation">
										<i class="lnr lnr-menu"></i>
									</button>
									<div class="wt-categories-navbar wt-navigation navbar-collapse collapse" id="navbarNavbar">
										<?php Workreap_Prepare_Headers::workreap_prepare_navigation('categories-menu', '', '', '0'); ?>
									</div>
								</nav>
							</div>
						</div>
					</div>
				<?php }?>
			</header>
            <?php
		}
		
		/**
         * @Prepare header v6
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_header_v6() {
            global $current_user;

            if (function_exists('fw_get_db_settings_option')) {
                $header_type 	= fw_get_db_settings_option('header_type');
                $main_logo		= fw_get_db_settings_option('main_logo');
            } else {
                $main_logo 	 = '';
                $header_type = '';
			}
			
			$transparent_logo    = !empty($header_type['header_v6']['main_logo']) ? $header_type['header_v6']['main_logo'] : '';
			
            if (!empty($transparent_logo['url'])) {
                $t_logo = $transparent_logo['url'];
            }else {
                $t_logo = get_template_directory_uri() . '/images/prologo_transparent.png';
            }

            if (!empty($main_logo['url'])) {
                $logo = $main_logo['url'];
            }else {
                $logo = get_template_directory_uri() . '/images/prologo.png';
            } 

            ?>
            <header id="wt-header" class="wt-header wt-headereleven wt-headerelevenb wt-header-v6">
				<div class="wt-navigationarea">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php $this->workreap_prepare_logo($logo, $t_logo, 'transparent_v2'); ?>
								<div class="wt-rightarea">
									<nav id="wt-nav" class="wt-nav navbar-expand-lg">
										<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
											<i class="lnr lnr-menu"></i>
										</button>
										<div class="collapse navbar-collapse wt-navigation" id="navbarNav">
										<?php Workreap_Prepare_Headers::workreap_prepare_navigation('primary-menu', '', 'navbar-nav nav-Js', '0'); ?>
										</div>
									</nav>
                                    <?php $this->workreap_prepare_registration(); ?>
                                    <?php if (!is_page_template('directory/dashboard.php')) {?>
                                    	<div class="wt-respsonsive-search"><a href="#" onclick="event_preventDefault(event);" class="wt-searchbtn"><i class="fa fa-search"></i></a></div>
                                    <?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
            <?php
        }
		
		/**
         * @Prepare search form
         * @return {}
         * @author amentotech
         */
        public function workreap_prepare_search_formv3($header_key='header_v3') {
            global $post, $woocommerce;
			
			if (function_exists('fw_get_db_settings_option')) {
				$header_type = fw_get_db_settings_option('header_type');
            } else {
                $header_type 		= '';
            }
			
			$header_key 		= $header_key;

			$search_form	    = !empty($header_type[$header_key]['search_form']) ? $header_type[$header_key]['search_form'] : '';
			$searchs_array    	= !empty($header_type[$header_key]['search_options']) ? $header_type[$header_key]['search_options'] : array();

			$searchs	    	= !empty($searchs_array) ? array_keys($searchs_array) : array();
			$defult_key			= !empty($searchs) ? reset($searchs) : '';
			$defult_url			= !empty($defult_key) ? workreap_get_search_page_uri($defult_key) : '';
			$list_names			= worktic_get_search_list('yes');
			$keyword	    	= !empty($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
			$searchtype	    	= !empty($_GET['searchtype']) ? sanitize_text_field($_GET['searchtype']) : $defult_key;

			
			if( $search_form === 'hide_all' ){
				return;
			}
			
			if( ( is_home() || is_front_page() ) && $search_form === 'hide_on_home' ) {
				return;
			}
			
			if( is_page_template('directory/dashboard.php') ) {
				return;
			}
			
            ob_start();
            if( !empty($searchs) ) {?>
				<div class="wt-headersearch">
					<a href="#" onclick="event_preventDefault(event);" class="wt-headerbtn"><i class="ti-search"></i></a>
					<div class="wt-loginformhold">
						<div class="wt-loginheader">
							<span><?php esc_html_e('Start Your Search','workreap');?></span>
						</div>
						<form class="wt-formtheme wt-loginform do-append-url" action="<?php echo esc_url($defult_url);?>" method="get">
							<fieldset>
								<div class="form-group">
									<input type="text" name="keyword" value="<?php echo esc_attr($keyword);?>" class="form-control" placeholder="<?php esc_attr_e('I’m looking for','workreap');?>">
								</div>
								<div class="form-group">
									<span class="wt-select">
										<select name="searchtype">
											<?php 
											  foreach( $searchs as $search ) {
												$action_url	= workreap_get_search_page_uri($search);

												if( !empty( $searchtype ) && $search === $searchtype) {
													$selected	= 'selected';
												} else{
													$selected	= '';
												}
											?>
											<option <?php echo esc_attr( $selected );?> data-url="<?php echo esc_url($action_url);?>" value="<?php echo esc_attr($search);?>"><?php echo esc_html( $list_names[$search]);?></option>

										<?php } ?>
										</select>
									</span>
								</div>
								<div class="wt-logininfo">
									<button type="submit" class="wt-btn"><?php esc_html_e('Search','workreap');?></button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			<?php }
            echo ob_get_clean();
        }
		
		/**
         * @Prepare search form
         * @return {}
         * @author amentotech
         */
        public function workreap_prepare_search_form() {
            global $post, $woocommerce;
			
			if (function_exists('fw_get_db_settings_option')) {
				$header_type = fw_get_db_settings_option('header_type');
            } else {
                $header_type 		= '';
            }
			
            if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v2' ){
                $header_key = 'header_v2';
            } else if(!empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v5'){ 
				$header_key = 'header_v5';
			} else if(!empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v6'){ 
				$header_key = 'header_v6';
			} else {
                $header_key = 'header_v1';
            }

			$search_form	    = !empty($header_type[$header_key]['search_form']) ? $header_type[$header_key]['search_form'] : '';
			$searchs_array    	= !empty($header_type[$header_key]['search_options']) ? $header_type[$header_key]['search_options'] : array();
			$searchs_array		= get_final_search_list($searchs_array);
			$searchs	    	= !empty($searchs_array) ? array_keys($searchs_array) : array();
			
			if(!empty($_GET['searchtype'])){
				$defult_key	= $_GET['searchtype'];
			}else{
				$defult_key			= !empty($searchs) ? reset($searchs) : 'projects-search';
			}

			$defult_url			= !empty($defult_key) ? workreap_get_search_page_uri($defult_key) : '';
			$list_names			= worktic_get_search_list('yes');
			$keyword	    	= !empty($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
			$searchtype	    	= !empty($_GET['searchtype']) ? sanitize_text_field($_GET['searchtype']) : $defult_key;

			if( $search_form === 'hide_all' ){
				return;
			}
			
			if( ( is_home() || is_front_page() ) && $search_form === 'hide_on_home' ) {
				return;
			}
			
			if( is_page_template('directory/dashboard.php') ) {
				return;
			}
			
			//just for demo
			if ( apply_filters('workreap_get_domain',false) === true ) {
				$post_name = workreap_get_post_name();
				if( $post_name === "home-page-v5" || $post_name === 'home-page-four' ){
					return;
				}
			}
			
            ob_start();
            ?>
				<form class="wt-formtheme wt-formbanner wt-formbannervtwo" action="<?php echo esc_url($defult_url);?>" method="get">
					<fieldset>
						<div class="form-group">
							<input type="text" name="keyword" value="<?php echo esc_attr($keyword);?>" class="form-control" placeholder="<?php esc_attr_e('I’m looking for','workreap');?>">
							<div class="wt-formoptions">
								<?php if( !empty($searchs) ) {?>
									<div class="wt-dropdown">
										<span><em class="selected-search-type"><?php echo esc_html( $list_names[$searchtype]);?></em><i class="lnr lnr-chevron-down"></i></span>
									</div>
									<div class="wt-radioholder">
										<?php 
											foreach( $searchs as $search ) {
												$action_url	= workreap_get_search_page_uri($search);
												$checked	= '';
												
												if( !empty( $searchtype ) && $search === $searchtype) {
													$checked	= 'checked';
												}
												
												$flag_key 	= rand(9999, 999999);
											?>
											<span class="wt-radio">
												<input id="wtheader-<?php echo esc_attr( $flag_key );?>" data-url="<?php echo esc_url($action_url);?>" data-title="<?php echo esc_attr( $list_names[$search]);?>" type="radio" name="searchtype" value="<?php echo esc_attr($search);?>" <?php echo esc_attr($checked);?>>
												<label for="wtheader-<?php echo esc_attr( $flag_key );?>"><?php echo esc_html( $list_names[$search]);?></label>
											</span>
										<?php } ?>
									</div>
								<?php } ?>
								<button type="submit" class="wt-searchbtn"><i class="fa fa-search"></i><span><?php esc_html_e('Search Now','workreap');?></span></button>
							</div>
                        </div>
                        <div class="wt-btn-remove-holder">
                            <a href="#" onclick="event_preventDefault(event);" class="wt-search-remove"><?php esc_html_e('Cancel','workreap');?></a>
                            <a href="#" onclick="event_preventDefault(event);" class="wt-search-remove"><i class="fa fa-close"></i></a>
                        </div>
					</fieldset>
				</form>
				<?php
			
            echo ob_get_clean();
        }
		
        /**
         * @Prepare Logo
         * @return {}
         * @author amentotech
         */
        public function workreap_prepare_logo($logo = '',$t_logo = '',$classes='') {
            global $post, $woocommerce;
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
            ob_start();
            ?>
            <strong class="wt-logo"> 
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php 
						if (!empty($logo)) {?>
							<img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($blogname); ?>">
							<?php
						} else {
							echo esc_html($blogname);
						}
                    ?>
                    <?php 
                        if( $classes === 'transparent_v2' ){
                            if (!empty($t_logo)) {?>
                                <img class="<?php echo esc_attr( $classes );?>" src="<?php echo esc_url($t_logo); ?>" alt="<?php echo esc_attr($blogname); ?>">
                                <?php
                            } 
                        }
                    ?>
                </a>
            </strong>
            <?php
            echo ob_get_clean();
        }

        /**
         * @Registration and Login
         * @return {}
         */
        public function workreap_prepare_registration($header_type = '') {
            global $current_user, $wp_roles, $userdata, $post;
            $redirect           = !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';
            $signup_page_slug   = workreap_get_signup_page_url('step', '1');              
			do_action('workreap_print_login_form');              
        }

        /**
         * @Main Navigation
         * @return {}
         */
        public static function workreap_prepare_navigation($location = '', $id = 'menus', $class = '', $depth = '0') {
			global $current_user;
			if(is_user_logged_in()){
				if(!empty($location) && $location === 'primary-menu'){
					if ( in_array( 'freelancers', (array) $current_user->roles ) ) {
						if( has_nav_menu('freelancers')){
							$location	= 'freelancers';
						}
					} else if ( in_array( 'employers', (array) $current_user->roles )  && has_nav_menu($location) ) {
						if( has_nav_menu('employers')){
							$location	= 'employers';
						}
					}
				}
			}
            
			$defaults = array(
				'theme_location' => "$location",
				'menu' => '',
				'container' => 'ul',
				'container_class' => '',
				'container_id' => '',
				'menu_class' => "$class",
				'menu_id' => "$id",
				'echo' => false,
				'fallback_cb' => 'wp_page_menu',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'depth' => "$depth",
			);
			
			echo do_shortcode(wp_nav_menu($defaults));
        }

    }

    new Workreap_Prepare_Headers();
}