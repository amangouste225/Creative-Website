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

if( !class_exists('Workreap_Registration_Button') ){
	class Workreap_Registration_Button extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_registration_button';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Registration Button', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-person';
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
			$button_title     	= !empty($settings['button_title']) ? $settings['button_title'] : esc_html__('Signup Now','workreap_core');;
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
				$enable_login_register   = fw_get_db_settings_option('enable_login_register');
			} 

			$is_auth			= !empty($login_register['gadget']) ? $login_register['gadget'] : ''; 
			$is_register		= !empty($login_register['enable']['registration']['gadget']) ? $login_register['enable']['registration']['gadget'] : ''; 
			$signup_page_slug   = workreap_get_signup_page_url('step', '1');

			if( ( $is_auth === 'enable' && !is_user_logged_in() ) || is_admin() ){?>
			<div class="wt-register-widget wt-loginarea elementor-align-<?php echo esc_html($align);?>">
				<?php if ( !empty($is_register) && $is_register === 'enable' ) {?>
					<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
						<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn wt-joinnowbtn"><?php echo esc_attr($button_title);?></a>
					<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
						<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn wt-joinnowbtn"><?php echo esc_attr($button_title);?></a>
					<?php } else {?>
						<a href="<?php echo esc_url(  $signup_page_slug ); ?>"  class="wt-btn"><?php echo esc_attr($button_title);?></a>
					<?php }?>
				<?php }?> 

				<?php if( ( !empty($button_width) && !empty($button_height) ) || ( !empty($text_color) && !empty($bg_color) ) || ( !empty($hover_color) && !empty($hover_text) ) || $custom_css ) {?>
					<style>
						<?php if(!empty($button_width) && !empty($button_height)){?>
						.wt-register-widget a.wt-btn,
						.wt-register-widget a{
							width:<?php echo esc_html($button_width);?>px;
							height: <?php echo esc_html($button_height);?>px;
							line-height: <?php echo esc_html($button_height);?>px;
						}
						<?php }?>
						<?php if(!empty($text_color) && !empty($bg_color)){?>
							.wt-register-widget a.wt-btn,
							.wt-register-widget a{
								background: <?php echo esc_html($bg_color);?>;
								color: <?php echo esc_html($text_color);?>;
							}
						<?php }?>
						<?php if(!empty($hover_color) && !empty($hover_text)){?>
							.wt-register-widget a.wt-btn:hover,
							.wt-register-widget a:hover{
								background: <?php echo esc_html($hover_color);?>;
								color: <?php echo esc_html($hover_text);?>;
							}
						<?php }?>

						<?php echo esc_html($custom_css);?>
					</style>
				<?php }?>
			</div>
			<?php 
			}
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Registration_Button ); 
}