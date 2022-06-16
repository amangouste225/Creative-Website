<?php
/**
 * Shortcode for brands V2
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

if( !class_exists('Workreap_Brands_V2') ){
	class Workreap_Brands_V2 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_brands_v2';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Brands V2', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-review';
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
				'brand_images',
				[
					'label'  => esc_html__( 'Add images', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Upload slide Image', 'workreap_core' ),
							'description'   => esc_html__( 'Upload image.', 'workreap_core' ),
						]
						,
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
			$settings	= $this->get_settings_for_display();

			$images	 	= !empty($settings['brand_images']) ? $settings['brand_images'] : array();
			$flag 		= rand(9999, 999999);
			?>
			<div class="wt-sc-brand-images-v2 wt-brands-wrap wt-haslayout dynamic-secton-<?php echo esc_attr( $flag );?>">
				<?php if(!empty($images)) { ?>
					<div class="row">
						<div class="col-12">
							<ul class="wt-brands">
							<?php 
							foreach ($images as $img) {
								$img_id     = !empty($img['image']['id']) ? $img['image']['id'] : '';
								$brand_img  =  wp_get_attachment_image_src($img_id);
								if (!empty($brand_img)) { ?>
									<li><img src="<?php echo esc_url($brand_img[0]); ?>" alt="<?php esc_attr_e('Brand', 'workreap_core'); ?>"></li>
							<?php } } ?>
							</ul>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Brands_V2 ); 
}