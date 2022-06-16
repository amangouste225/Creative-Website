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

if( !class_exists('Workreap_Clients') ){
	class Workreap_Clients extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_clients';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Clients', 'workreap_core' );
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
				'clients',
				[
					'label'  => esc_html__( 'Add Client', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'title',
							'label' => esc_html__( 'Add Title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Upload Image', 'workreap_core' ),
							'description'   => esc_html__( 'Upload image.', 'workreap_core' ),
						],
						[
							'name' 			=> 'url',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Url', 'workreap_core' ),
							'description'   => esc_html__( 'Add url. Leave it empty to hide.', 'workreap_core' ),
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
			$settings 		= $this->get_settings_for_display();
			$clients     	= !empty($settings['clients']) ? $settings['clients'] : array();
			$uniq_flag  	= rand(1,9999);
			
			if( !empty( $clients ) ){ ?>
				<div class="wt-clients-section">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<ul class="wt-clientslogo">
									<?php 
										foreach($clients as $client ){
											$img_url	= !empty($client['image']['url']) ? $client['image']['url'] : '';
											$title		= !empty($client['title']) ? $client['title'] : '';
											$url		= !empty($client['url']) ? $client['url'] : '';
											if( !empty($img_url) ){ ?>
												<li>
													<a href="<?php echo esc_url($url);?>"><img src="<?php echo esc_url($img_url);?>" alt="<?php echo esc_attr($title);?>"></a>
												</li>
										<?php } ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			<?php } 
		}
	}
	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Clients ); 
}