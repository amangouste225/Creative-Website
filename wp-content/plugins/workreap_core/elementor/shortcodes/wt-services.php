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

if( !class_exists('Workreap_Services_Shortcode') ){
	class Workreap_Services_Shortcode extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_services';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Services', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-settings';
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
				'services',
				[
					'label'  => esc_html__( 'Add services', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'title',
							'label' => esc_html__( 'Add title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						
						[
							'name'  => 'description',
							'label' => esc_html__( 'Add Description', 'workreap_core' ),
							'type'  => Controls_Manager::TEXTAREA,
						],
				
						[
							'name'  => 'btn_title',
							'label' => esc_html__( 'Add button title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'btn_link',
							'label' => esc_html__( 'Add button link', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						
					],
					'default' => [],
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

			$services = !empty($settings['services']) ? $settings['services'] : array();
			?>
			<div class="wt-sc-services wt-haslayout">
				<?php if( !empty( $services ) ) { ?>
					<div class="wt-companydetails">
						<?php 
						foreach( $services as $service ) { 
							$title      = !empty( $service['title'] ) ? $service['title'] : '';
							$desc       = !empty( $service['description'] ) ? $service['description'] : '';
							$btn_title  = !empty( $service['btn_title'] ) ? $service['btn_title'] : '';
							$page_link  = !empty( $service['btn_link'] ) ? $service['btn_link'] : '#';

							if( !empty( $title ) ||
								!empty( $desc ) ||
								!empty( $btn_title ) ) { 
								?>
								<div class="wt-companycontent">
									<?php if( !empty( $title ) ) { ?>
										<div class="wt-companyinfotitle">
											<h2><?php echo esc_html( $title ); ?></h2>
										</div>
									<?php } ?>
									<?php if( !empty( $desc ) ) { ?>
										<div class="wt-description">
											<?php echo wp_kses_post( wpautop( do_shortcode( $desc ) ) ); ?>
										</div>
									<?php } ?>
									<?php if( !empty( $btn_title ) ) { ?>
										<div class="wt-btnarea">
											<a href="<?php echo esc_url($page_link); ?>" class="wt-btn"><?php echo esc_attr( $btn_title ); ?></a>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php 
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Services_Shortcode ); 
}