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

if( !class_exists('Workreap_Authentication') ){
	class Workreap_Authentication extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_authentication';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Authentication', 'workreap_core' );
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
				'form_type',
				[
					'label' => esc_html__( 'Form type', 'workreap_core' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'register',
					'options' => [
						'register_single' 	=> esc_html__('Register Single Step Form', 'workreap_core'),
						'register' 	=> esc_html__('Register Form', 'workreap_core'),
						'login' 	=> esc_html__('Login Form', 'workreap_core')
					],
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
			?>
			<div class="wt-sc-shortcode wt-haslayout wc-<?php echo esc_attr($settings['form_type']);?>">
				<?php
					if( isset( $settings['form_type'] ) && $settings['form_type'] === 'register' ){
						echo do_shortcode('[workreap_authentication]');
					} else if( isset( $settings['form_type'] ) && $settings['form_type'] === 'register_single' ){
						echo do_shortcode('[workreap_authentication_single type="shortcode"]');
					} else if( isset( $settings['form_type'] ) && $settings['form_type'] === 'login' ){
						echo do_shortcode('[workreap_authentication_signin]');
					} 
				?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Authentication ); 
}