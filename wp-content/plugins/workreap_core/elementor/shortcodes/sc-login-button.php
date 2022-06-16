<?php
/**
 * Shortcode
 *
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 * @author     Amentotech <theamentotech@gmail.com>
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists('Workreap_Login_Button') ){
	class Workreap_Login_Button extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_login_button';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Login Button', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-lock-user';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      category of shortcode
		 */
		public function get_categories() {
			return [ 'workreap-elements' ];
		}

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap_core' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'button_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Button title?', 'workreap_core' ),
					'description'   => esc_html__( 'Add button title here', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'button_width',
				[
					'label' => __( 'Button Width', 'workreap_core' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 500,
					'step' => 1,
				]
			);
			
			$this->add_control(
				'button_height',
				[
					'label' => __( 'Button Height', 'workreap_core' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 500,
					'step' => 1,
				]
			);
			
			$this->add_control(
				'custom_css',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label'     	=> esc_html__( 'Add Custom CSS', 'workreap_core' ),
					'description'   => esc_html__( 'Add your custom css to style the button', 'workreap_core' ),
				]
			);
			
			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'workreap_core' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => __( 'Left', 'workreap_core' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'workreap_core' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'workreap_core' ),
							'icon' => 'eicon-text-align-right',
						],
						'justify' => [
							'title' => __( 'Justified', 'workreap_core' ),
							'icon' => 'eicon-text-align-justify',
						],
					],
					'prefix_class' => 'elementor%s-align-',
					'default' => '',
				]
			);
			
			$this->add_control(
				'text_color',
				[
					'type'      	=> Controls_Manager::COLOR,
					'label'     	=> esc_html__( 'Text color', 'workreap_core' ),
					'description'   => esc_html__( 'Add text color. leave it empty to use default color.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'bg_color',
				[
					'type'      	=> Controls_Manager::COLOR,
					'label'     	=> esc_html__( 'Background color', 'workreap_core' ),
					'description'   => esc_html__( 'Add background color', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'hover_color',
				[
					'type'      	=> Controls_Manager::COLOR,
					'label'     	=> esc_html__( 'Background hover color', 'workreap_core' ),
					'description'   => esc_html__( 'Add background hover color', 'workreap_core' ),
				]
			);
			$this->add_control(
				'hover_text',
				[
					'type'      	=> Controls_Manager::COLOR,
					'label'     	=> esc_html__( 'Text hover color', 'workreap_core' ),
					'description'   => esc_html__( 'Add text hover color', 'workreap_core' ),
				]
			);
			
			$this->end_controls_section();
		}

		/**
		 * Render shortcode
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();
			$button_title     	= !empty($settings['button_title']) ? $settings['button_title'] : esc_html__('Login/Registration','workreap_core');;
			$custom_css     	= !empty($settings['custom_css']) ? $settings['custom_css'] : '';
			$button_width     	= !empty($settings['button_width']) ? $settings['button_width'] : '';
			$button_height     	= !empty($settings['button_height']) ? $settings['button_height'] : '';
			
			
			$align     			= !empty($settings['align']) ? $settings['align'] : '';
			$text_color     	= !empty($settings['text_color']) ? $settings['text_color'] : '';
			$bg_color     		= !empty($settings['bg_color']) ? $settings['bg_color'] : '';
			$hover_color     	= !empty($settings['hover_color']) ? $settings['hover_color'] : '';
			$hover_text     	= !empty($settings['hover_text']) ? $settings['hover_text'] : '';

			if (function_exists('fw_get_db_settings_option')) {
				$login_register = fw_get_db_settings_option('enable_login_register'); 
				$enable_google_connect 	 = fw_get_db_settings_option('enable_google_connect', $default_value = null);
				$enable_facebook_connect = fw_get_db_settings_option('enable_facebook_connect', $default_value = null);
				$enable_linkedin_connect = fw_get_db_settings_option('enable_linkedin_connect', $default_value = null);
				$header_type 			 = fw_get_db_settings_option('header_type');
				$enable_login_register   = fw_get_db_settings_option('enable_login_register');
			} 

			$is_auth			= !empty($login_register['gadget']) ? $login_register['gadget'] : ''; 
			$is_register		= !empty($login_register['enable']['registration']['gadget']) ? $login_register['enable']['registration']['gadget'] : ''; 
			$redirect           = !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';
			$signup_page_slug   = workreap_get_signup_page_url('step', '1');

			if ( apply_filters('workreap_get_domain',false) === true ) {
				$post_name = workreap_get_post_name();
				if( $post_name === "home-page-three" ){
					$header_type['gadget'] = 'header_v3';
				}
			}

			if ( is_user_logged_in() && !is_admin()  ) {
				echo '<div class="wt-login-widget">';
				get_template_part('directory/front-end/dashboard-menu-templates/menu', 'shortcode');
				echo '</div>';
			} else{
			
			if( $is_auth === 'enable' ){?>
				<div class="wt-login-widget wt-loginarea elementor-align-<?php echo esc_html($align);?>">
					<?php if( !empty( $header_type['gadget'] ) && 
						 ( $header_type['gadget'] === 'header_v2' 
						  ||  $header_type['gadget'] == 'header_v3' 
						  || $header_type['gadget'] == 'header_v5' 
						  || $header_type['gadget'] == 'header_v6' ) 
					){?>
						<div class="wt-loginoption wt-loginoptionvtwo">
							<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
								<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-btn"><?php echo esc_html($button_title);?></a>
							<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
								<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-btn"><?php echo esc_html($button_title);?></a>
							<?php } else {?>
								<a href="#" onclick="event_preventDefault(event);" id="wt-loginbtn" class="wt-btn wt-loginbtn"><?php echo esc_html($button_title);?></a>
							<?php }?>
						</div>
					<?php }else {?>
						<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
							<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-btn wt-joinnowbtn wt-loginbtn"><?php echo esc_html($button_title);?></a>
						<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
							<a href="#" onclick="event_preventDefault(event);"  data-toggle="modal" data-target="#loginpopup" class="wt-btn wt-joinnowbtn wt-loginbtn"><?php echo esc_html($button_title);?></a>
						<?php } else {?>
							<a href="#" onclick="event_preventDefault(event);" id="wt-loginbtn" class="wt-btn wt-joinnowbtn wt-loginbtn"><?php echo esc_html($button_title);?></a>
						<?php }?>
					<?php }?>
					<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'pages' ){?>
						<div class="wt-loginformhold">
							<div class="wt-loginheader">
								<span><?php esc_html_e('Login','workreap_core');?></span>
								<a href="#" onclick="event_preventDefault(event);"><i class="fa fa-times"></i></a>
							</div>
							<form class="wt-formtheme wt-loginform do-login-form">
								<fieldset>
									<div class="form-group">
										<input type="text" name="username" class="form-control" placeholder="<?php esc_html_e('Username or email', 'workreap_core'); ?>">
									</div>
									<div class="form-group">
										<input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password', 'workreap_core'); ?>">
									</div>
									<div class="wt-logininfo">
										<input type="submit" class="wt-btn do-login-button" value="<?php esc_attr_e('Login','workreap_core');?>">
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
									<a href="#" onclick="event_preventDefault(event);" class="wt-forgot-password"><?php esc_html_e('Forgot password?','workreap_core');?></a>
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

									<div class="wt-logininfo">
										<a href="#" onclick="event_preventDefault(event);" class="wt-btn do-get-password-btn"><?php esc_html_e('Get Password','workreap_core');?></a>
									</div>                                                               
								</fieldset>
								<div class="wt-loginfooterinfo">
									<a href="#" onclick="event_preventDefault(event);" class="wt-show-login"><?php esc_html_e('Login Now','workreap_core');?></a>
									<?php if ( !empty($is_register) && $is_register === 'enable' ) {?>
										<a href="<?php echo esc_url(  $signup_page_slug ); ?>"><?php esc_html_e('Create account','workreap_core');?></a>
									<?php }?>
								</div>
							</form>
						</div>
					<?php }?>
					<?php if( ( !empty($button_width) && !empty($button_height) ) || ( !empty($text_color) && !empty($bg_color) ) || ( !empty($hover_color) && !empty($hover_text) ) || $custom_css ) {?>
						<style>
							<?php if(!empty($button_width) && !empty($button_height)){?>
							.wt-login-widget a.wt-btn,
							.wt-login-widget a{
								width:<?php echo esc_html($button_width);?>px;
								height: <?php echo esc_html($button_height);?>px;
								line-height: <?php echo esc_html($button_height);?>px;
								display: table-cell;
							}
							<?php }?>
							<?php if(!empty($text_color) && !empty($bg_color)){?>
								.wt-login-widget a.wt-btn,
								.wt-login-widget a{
									background: <?php echo esc_html($bg_color);?>;
									color: <?php echo esc_html($text_color);?>;
								}
							<?php }?>
							<?php if(!empty($hover_color) && !empty($hover_text)){?>
								.wt-login-widget a.wt-btn:hover,
								.wt-login-widget a:hover{
									background: <?php echo esc_html($hover_color);?>;
									color: <?php echo esc_html($hover_text);?>;
								}
							<?php }?>

							<?php echo esc_html($custom_css);?>
						</style>
					<?php }?>
				</div>
				<?php }
			}
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Login_Button ); 
}