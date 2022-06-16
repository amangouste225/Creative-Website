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

if( !class_exists('Workreap_AboutUs') ){
	class Workreap_AboutUs extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_about';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'About Us', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-info-box';
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
				'image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Background Image', 'workreap_core' ),
					'description'   => esc_html__( 'Upload image. leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add newsletter title. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Sub title', 'workreap_core'),
        			'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap_core'),
        			'description' 	=> esc_html__('Add newsletter description. leave it empty to hide.', 'workreap_core'),
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
				'buttons',
				[
					'label'  => esc_html__( 'Add Button', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'button_text',
							'label' => esc_html__( 'Button Text', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'button_link',
							'label' => esc_html__( 'Button Link', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
							
						],
						[
							'name'  		=> 'btn_styles',
							'label' 		=> esc_html__( 'Button Style', 'workreap_core' ),
							'type'  		=> Controls_Manager::SWITCHER,
							'label_on' 		=> esc_html__( 'Style 1', 'workreap_core' ),
							'label_off' 	=> esc_html__( 'Style 2', 'workreap_core' ),
							'return_value' 	=> 'de_active',
							'default' 		=> 'active',
						]
						,
						[
							'name'  => 'link_target',
							'label' => esc_html__( 'Link Target', 'workreap_core' ),
							'type'  => Controls_Manager::SELECT,
							'default' => '_self',
							'options' => [
								'_blank' 	=> esc_html__('New Tab', 'workreap_core'),
                    			'_self' 	=> esc_html__('Current Tab', 'workreap_core'),
							],
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

			$bg_image   = !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$title      = !empty($settings['title']) ? $settings['title'] : '';
			$sub_title  = !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	    = !empty($settings['description']) ? $settings['description'] : '';
			$text_color = !empty( $settings['text_color'] ) ? $settings['text_color'] : '';
			$buttons    = !empty($settings['buttons']) ? $settings['buttons'] : array();
			$flag 		= rand(9999, 999999);
			?>
			<div class="wt-sc-newsletter wt-haslayout dynamic-secton-<?php echo esc_attr( $flag );?>">
				<?php 
				if( !empty( $image ) ||
					!empty( $title ) ||
					!empty( $desc ) ||
					!empty( $buttons ) ) {
					?>
					<div class="wt-signupholder" style="background: url(<?php echo esc_attr($bg_image); ?>)">
						<?php 
							if( !empty( $title ) ||
								!empty( $desc ) ||
								!empty( $buttons ) ) {
							?>
							<div class="col-12 col-sm-12 col-md-12 col-lg-6 pull-right">
								<div class="wt-signupcontent">
									<?php if( !empty( $title ) ) { ?>
										<div class="wt-title">
											<h2>
												<?php if(!empty($sub_title) ) {?>
													<span><?php echo esc_html( $sub_title ); ?></span>
												<?php } ?>
												<?php echo esc_html( $title ); ?>
											</h2>
										</div>
									<?php } ?>
									<?php if( !empty( $desc ) ) { ?>
										<div class="wt-description">
											<?php echo wp_kses_post( wpautop( do_shortcode( $desc ) ) ); ?>
										</div>
									<?php } ?>
									<?php if( !empty( $buttons ) ) { ?>
										<div class="wt-btnarea">
											<?php 
												foreach($buttons as $key => $button) { 
													$btn_text = !empty($button['button_text']) ? $button['button_text'] : ''; 
													$btn_link = !empty($button['button_link']) ? $button['button_link'] : '#'; 
													$btn_styles = !empty($button['btn_styles']) && $button['btn_styles'] === 'active'  ? 'wt-btnvtwo'  : '';
													$target = !empty($button['link_target']) ? $button['link_target'] : '_self';
												?>
												<?php if( !empty( $btn_text ) ) { ?>
													<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_url($btn_link); ?>" class="wt-btn <?php echo esc_attr( $btn_styles ); ?> "><?php echo esc_html( $btn_text ); ?></a>
												<?php } ?>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<?php if( !empty ( $text_color ) ) {?>
						<style scoped>
							.dynamic-secton-<?php echo esc_html( $flag );?> .wt-title h2, 
							.dynamic-secton-<?php echo esc_html( $flag );?> .wt-description p {
								color: <?php echo esc_html( $text_color ); ?>;
							}
						</style>
					<?php 
						}
				 } ?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_AboutUs ); 
}