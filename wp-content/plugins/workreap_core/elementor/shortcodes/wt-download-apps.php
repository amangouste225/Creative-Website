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

if( !class_exists('Workreap_Download_APP') ){
	class Workreap_Download_APP extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_download_app';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Download APPS', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-download-button';
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
					'label'     	=> esc_html__( 'Upload Image', 'workreap_core' ),
					'description'   => esc_html__( 'Upload Image. leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Title', 'workreap_core' ),
					'description'   => esc_html__( 'Add title. leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Sub Title', 'workreap_core' ),
					'description'   => esc_html__( 'Add subtitle. leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap_core' ),
					'description'   => esc_html__( 'Add description. leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'logos',
				[
					'label'  => esc_html__( 'Logos', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'image',
							'label' => esc_html__( 'Select logo', 'workreap_core' ),
							'type'  => Controls_Manager::MEDIA,
						],
						[
							'name'  => 'button_text',
							'label' => esc_html__( 'Add button text', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
							'description'   => esc_html__( 'Add text to display button, it will override logo image and display simple button', 'workreap_core' ),
						],
						[
							'name'  => 'link_url',
							'label' => esc_html__( 'Button Text', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'link_target',
							'label' => esc_html__( 'Link Target', 'workreap_core' ),
							'type'  => Controls_Manager::SELECT,
							'default' => '_blank',
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
			$image 	   = !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$title     = !empty($settings['title']) ? $settings['title'] : '';
			$sub_title = !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	   = !empty($settings['description']) ? $settings['description'] : '';
			$logos 	   = !empty($settings['logos']) ? $settings['logos'] : array();
			?>
			<div class="wt-sc-explore-cat wt-haslayout">
				<div class="row">
					<?php 
					if( !empty( $image ) ||
						!empty( $title ) ||
						!empty( $sub_title ) ||
						!empty( $desc ) ||
						!empty( $logos ) ) {
						?>
						<?php if( !empty( $image ) ) { ?>
							<div class="col-12 col-sm-12 col-md-6 col-lg-6 float-left">
								<figure class="wt-mobileimg">
									<img src="<?php echo esc_url($image); ?>" alt="<?php esc_attr_e('APP' ,'workreap_core') ?>">
								</figure>
							</div>
						<?php } ?>
						<?php 
						if( !empty( $title ) ||
							!empty( $sub_title ) ||
							!empty( $desc ) ||
							!empty( $logos ) ) {
							?>
							<div class="col-12 col-sm-12 col-md-6 col-lg-6 float-left">
								<div class="wt-experienceholder">
									<div class="wt-sectionhead">
										<?php if(!empty( $title )  || !empty( $sub_title )) { ?>
											<div class="wt-sectiontitle">
												<?php if( !empty( $title ) ) { ?>
													<h2><?php echo esc_html($title); ?></h2>
												<?php } ?>
												<?php if( !empty($sub_title) ) { ?>
													<span><?php echo esc_html( $sub_title ); ?></span>
												<?php } ?>
											</div>
										<?php } ?>
										<?php if( !empty( $desc) ) { ?>
											<div class="wt-description">
												<?php echo wp_kses_post( wpautop( do_shortcode( $desc ) ) ); ?>
											</div>
										<?php } ?>
										<?php if( !empty( $logos ) ) { ?>
											<ul class="wt-appicon">
												<?php 
													foreach( $logos as $key => $logo ) { 
														$image  = !empty( $logo['image']['url'] ) ? $logo['image']['url'] : '';
														$url    = !empty( $logo['link_url'] ) ? $logo['link_url'] : '#';
														$button_text    = !empty( $logo['button_text'] ) ? $logo['button_text'] : '';
														$target = !empty( $logo['link_target'] ) ? $logo['link_target'] : '_blank';
														$buttonCalss	= !empty($button_text) ?  'wt-btn' : '';
														if( !empty( $image ) || !empty($button_text) ) { ?>
														<li>
															<a target="<?php echo esc_attr($target); ?>" class="<?php echo esc_attr($buttonCalss); ?>" href="<?php echo esc_url($url); ?>">
																<?php if( !empty( $button_text ) ) { ?>
																	<?php echo esc_attr($button_text);?>
																<?php }else if( !empty( $image ) ) {?>
																	<figure><img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e('Logo', 'workreap_core'); ?>"></figure>
																<?php } ?>
															</a>
														</li>
													<?php } ?>
												<?php } ?>
											</ul>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Download_APP ); 
}