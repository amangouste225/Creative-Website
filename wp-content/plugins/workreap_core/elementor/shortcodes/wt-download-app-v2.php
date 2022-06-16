<?php
/**
 * Shortcode for download app v2
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

if( !class_exists('Workreap_Download_APP_V2') ){
	class Workreap_Download_APP_V2 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_download_app_v2';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Download App V2', 'workreap_core' );
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
				'app_features',
				[
					'label'  => esc_html__( 'Add Features', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name' 			=> 'title',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Add Feature', 'workreap_core' ),
							'description'   => esc_html__( 'Add applications features or leave it empty to hide.', 'workreap_core' ),
						]
						,
					],
					'default' => [],
				]
			);

			$this->add_control(
				'features_description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Features Description', 'workreap_core' ),
					'description'   => esc_html__( 'Add feature description. leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'apple_btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Apple button title', 'workreap_core' ),
					'description'   => esc_html__( 'Add apple button title. leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'apple_btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Apple button link', 'workreap_core' ),
					'description'   => esc_html__( 'Add apple button title. leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'apple_link_target',
				[
					'type' => \Elementor\Controls_Manager::SELECT,
					'label'     	=> esc_html__( 'Apple link target', 'workreap_core' ),
					'options' => [
						'_blank'  => esc_html__( 'New Tab', 'workreap_core' ),
						'_self' => esc_html__( 'Current Tab', 'workreap_core' ),
					],
					'description'   => esc_html__( 'Add apple link target. leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'android_btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Android button title', 'workreap_core' ),
					'description'   => esc_html__( 'Add android button title. leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'android_btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Android button link', 'workreap_core' ),
					'description'   => esc_html__( 'Add android button title. leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'android_link_target',
				[
					'type' => \Elementor\Controls_Manager::SELECT,
					'label'     	=> esc_html__( 'Android link target', 'workreap_core' ),
					'options' => [
						'_blank'  => esc_html__( 'New Tab', 'workreap_core' ),
						'_self' => esc_html__( 'Current Tab', 'workreap_core' ),
					],
					'description'   => esc_html__( 'Add apple link target. leave it empty to hide.', 'workreap_core' ),
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
			$settings 				= $this->get_settings_for_display();
			$image 	   				= !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$title     				= !empty($settings['title']) ? $settings['title'] : '';
			$sub_title 				= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	   				= !empty($settings['description']) ? $settings['description'] : '';
			$app_features  	   		= !empty($settings['app_features']) ? $settings['app_features'] : array();
			$features_desc  	   	= !empty($settings['features_description']) ? $settings['features_description'] : '';
			$apple_btn_link			= !empty($settings['apple_btn_link']) ? $settings['apple_btn_link'] : '#';
			$apple_btn_title		= !empty($settings['apple_btn_title']) ? $settings['apple_btn_title'] : '';
			$apple_link_target		= !empty($settings['apple_link_target']) ? $settings['apple_link_target'] : esc_html__('_self', 'workreap_core');
			$android_btn_link		= !empty($settings['android_btn_link']) ? $settings['android_btn_link'] : '#';
			$android_btn_title		= !empty($settings['android_btn_title']) ? $settings['android_btn_title'] : '';
			$android_link_target	= !empty($settings['android_link_target']) ? $settings['android_link_target'] : esc_html__('_self', 'workreap_core');
			?>
			<div class="wt-sc-download-app-v2 wt-haslayout">
			<?php 
			if( !empty( $image ) ||
				!empty( $title ) ||
				!empty( $sub_title ) ||
				!empty( $desc ) ||
				!empty( $features_desc ) ||
				!empty( $apple_btn_link ) ||
				!empty( $apple_btn_title ) || 
				!empty( $apple_link_target ) ||
				!empty( $app_features ) ||
				!empty( $android_btn_title ) ||
				!empty( $android_btn_link ) ||
				!empty( $android_link_target ) ) { ?>
					<div class="row">
						<?php 
						if( !empty( $title ) ||
							!empty( $sub_title ) ||
							!empty( $desc ) ) {
							?>
							<div class="col-lg-6">
								<div class="wt-mobapp-wrap">
									<div class="wt-sectionheadvtwo">
										<?php if(!empty( $title )  || !empty( $sub_title )) { ?>
											<div class="wt-sectiontitlevtwo">
												<?php if( !empty($sub_title) ) { ?>
													<span><?php echo esc_html( $sub_title ); ?></span>
												<?php } ?>
												<?php if( !empty( $title ) ) { ?>
													<h2><?php echo do_shortcode($title); ?></h2>
												<?php } ?>
											</div>
										<?php } ?>
										<?php if(!empty($desc)) { ?>
											<div class="wt-description">
												<?php echo wpautop(do_shortcode($desc)); ?>
											</div>
										<?php } ?>
									</div>
									<?php if(!empty($app_features)) { ?>
									<ul class="wt-mobapp-listing">
										<?php foreach($app_features as $feature) { 
											if(!empty($feature['title'])) { ?>
											<li>
												<span><?php echo esc_html($feature['title']); ?></span>
											</li>
										<?php } } ?>
									</ul>
									<?php } ?>
									<?php if(!empty($features_desc)) { echo do_shortcode($features_desc); } ?>
									<?php if(!empty($apple_btn_title) || !empty($android_btn_title)) { ?>
										<div class="wt-mobapp-btns">
										<?php if(!empty($apple_btn_title)) { ?>
											<a href="<?php echo esc_url($apple_btn_link); ?>" target="<?php echo esc_attr($apple_link_target); ?>" class="wt-appbtn"> <i class="fa fa-apple"></i> <span><?php echo esc_html($apple_btn_title); ?></span> </a>
										<?php } ?>
										<?php if(!empty($android_btn_title)) { ?>
											<a href="<?php echo esc_url($android_btn_link); ?>" target="<?php echo esc_attr($android_link_target); ?>" class="wt-appbtn wt-android"><i class="fa fa-android"></i><span><?php echo esc_html($android_btn_title); ?></span></a>
										<?php } ?>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						<?php if( !empty( $image ) ) { ?>
							<div class="col-lg-6">
								<figure class="wt-mobapp-img">
									<img src="<?php echo esc_url($image); ?>" alt="<?php esc_attr_e('Download App', 'workreap_core') ?>">
								</figure>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Download_APP_V2 ); 
}