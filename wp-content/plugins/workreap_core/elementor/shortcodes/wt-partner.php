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

if( !class_exists('Workreap_Partner') ){
	class Workreap_Partner extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_partner';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Partner', 'workreap_core' );
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
				'title',
				[
					'label' => esc_html__( 'Add Title', 'workreap_core' ),
					'type'  => Controls_Manager::TEXT,
				]
			);
			
			$this->add_control(
				'size',
				[
					'label' => esc_html__( 'Add title font size', 'workreap_core' ),
					'type'  => Controls_Manager::NUMBER,
					'min' => 5,
					'max' => 100,
					'step' => 5,
					'default' => 14,
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Title Color', 'workreap_core' ),
					'type' => Controls_Manager::COLOR,
				]
			);
			
			$this->add_control(
				'slider',
				[
					'label'  => esc_html__( 'Add team Partner', 'workreap_core' ),
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
			$title     		= !empty($settings['title']) ? $settings['title'] : '';
			$title_color    = !empty($settings['title_color']) ? $settings['title_color'] : '#767676';
			$size    		= !empty($settings['size']) ? $settings['size'] : '14';
			$partners     	= !empty($settings['slider']) ? $settings['slider'] : array();
			$uniq_flag  	= rand(1,9999);
			
			if( !empty( $partners ) ){ ?>
				<div class="wt-sc-partner wt-haslayout wt-logoholder dynamic-secton-<?php echo esc_attr( $uniq_flag );?>">
					<div class="container-fluid">
						<div class="row">
							<?php if( !empty( $title ) ){ ?>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<span class="wt-partner-class" style="color:<?php echo esc_attr( $title_color );?>; font-size: <?php echo esc_attr( $size );?>px"><?php echo esc_attr( $title );?></span>
								</div>
							<?php }?>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="row">
									<ul class="wt-logos owl-carousel" id="wt-logos-<?php echo esc_attr( $uniq_flag );?>">
										<?php 
											foreach( $partners as $partner ) {
												$image	= !empty( $partner['image']['url']) ? $partner['image']['url'] : '';
												$title	= !empty( $partner['title']) ? $partner['title'] : esc_html__('Partner','workreap_core');
												$url	= !empty( $partner['url']) ? $partner['url'] : '#';
											?>
											<li class="item">
												<a href="<?php echo esc_url( $url );?>" target="_blank"><img src="<?php echo esc_url( $image );?>" alt="<?php echo esc_attr( $title );?>"></a>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<script type="application/javascript">
					jQuery(document).ready(function () {
						jQuery('#wt-logos-<?php echo $uniq_flag;?>').owlCarousel({
							rtl: <?php echo workreap_owl_rtl_check();?>,
							items: 7,
							nav:false,
							loop:true,
							dots: false,
							autoplay:true,
							responsiveClass:true,
							responsive:{
								0:{items:1,},
								200:{items:2,},
								320:{items:2,},												 
								481:{items:3,},
								767:{items:4,},
								992:{items:5,},
								1200:{items:7,}
							}
						});
					});
				</script>
			<?php } 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Partner ); 
}