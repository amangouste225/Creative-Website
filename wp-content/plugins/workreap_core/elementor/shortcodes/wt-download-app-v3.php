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

if( !class_exists('Workreap_Download_APP_V3') ){
	class Workreap_Download_APP_V3 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_download_app_v3';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Download app V3', 'workreap_core' );
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
				'mobile_image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Mobile Image', 'workreap_core' ),
					'description'   => esc_html__( 'Upload Mobile Image. leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'version',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Version button title', 'workreap_core' ),
					'description'   => esc_html__( 'Add Version button title. leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'version_details',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Latest version', 'workreap_core' ),
					'description'   => esc_html__( 'Add Latest version details. leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Title', 'workreap_core' ),
					'description'   => esc_html__( 'Add title. leave it empty to hide.', 'workreap_core' ),
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
				'feature_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Features Title', 'workreap_core' ),
					'description'   => esc_html__( 'Add features title. leave it empty to hide.', 'workreap_core' ),
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
						],
						[
							'name'			=> 'hot_option',
							'type'      	=> \Elementor\Controls_Manager::SWITCHER,
							'label'     	=> esc_html__( 'Show HOT tag Enable/Disbale', 'workreap_core' ),
							'label_on' 		=> esc_html__( 'Enable', 'workreap_core' ),
							'label_off' 	=> esc_html__( 'Disable', 'workreap_core' ),
							'return_value' 	=> 'yes',
							'default' 		=> 'yes',
						]
					],
					'default' => [],
				]
			);

			$this->add_control(
				'mobile_apps',
				[
					'label'  => esc_html__( 'Add Mobile Apps', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name' 			=> 'title',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Add Title', 'workreap_core' ),
							'description'   => esc_html__( 'Add title or leave it empty to hide.', 'workreap_core' ),
						],
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Add Image', 'workreap_core' ),
							'description'   => esc_html__( 'Add App Image or leave it empty to hide.', 'workreap_core' ),
						],
						[
							'name' 			=> 'url',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Add App URL', 'workreap_core' ),
							'description'   => esc_html__( 'Add App URL.', 'workreap_core' ),
						]
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
			$settings 				= $this->get_settings_for_display();
			$image 	   				= !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$mobile_image 	   		= !empty($settings['mobile_image']['url']) ? $settings['mobile_image']['url'] : '';
			$version 				= !empty($settings['version']) ? $settings['version'] : '';
			$version_details 		= !empty($settings['version_details']) ? $settings['version_details'] : '';
			
			$title     				= !empty($settings['title']) ? $settings['title'] : '';
			$desc  	   				= !empty($settings['description']) ? $settings['description'] : '';
			$feature_title     		= !empty($settings['feature_title']) ? $settings['feature_title'] : '';

			$app_features  	   		= !empty($settings['app_features']) ? $settings['app_features'] : array();
			$mobile_apps  	   		= !empty($settings['mobile_apps']) ? $settings['mobile_apps'] : '';
			$flag 					= rand(9999, 999999);
			?>
			
			<div class="wt-appfeatures-section">
				<div class="container-fluid">
					<div class="row wt-appholder">
						<?php if( !empty($image) || !empty($mobile_image) ){?>
							<figure class="wt-appfeaturesimg">
								<?php if( !empty($image) ){?>
									<img src="<?php echo esc_url($image);?>" class="wt-appfeaturesimg__bg" alt="<?php echo esc_attr($title);?>">
								<?php } ?>
								<?php if( !empty($mobile_image) ){?>
									<img src="<?php echo esc_attr($mobile_image);?>" class="wt-appfeaturesimg__mob" alt="<?php echo esc_attr($title);?>">
								<?php } ?>
							</figure>
						<?php } ?>
						<div class="wt-appfeatures">
							<div class="wt-appfeatures__content">
								<?php if( !empty($version_details) || !empty($version) || !empty($title) || !empty($desc) ){?>
									<div class="wt-appfeatures__title">
										<?php if( !empty($version_details) || !empty($version) ){?>
											<span>
												<?php if( !empty($version) ) {?>
													<a href="javascript:void(0);" class="wt-vtag"><?php echo esc_html($version);?></a>
												<?php } ?>
												<?php if(!empty($version_details) ){?>
													<em><?php echo do_shortcode( $version_details );?></em>
												<?php } ?>
											</span>
										<?php } ?>
										<?php if( !empty($title) || !empty($desc) ){?>
											<div class="wt-sectiontitle wt-sectiontitlevthree">
												<?php if( !empty($title) ) {?>
													<h2><?php echo do_shortcode( $title );?></h2>
												<?php } ?>
												<?php if( !empty($desc) ){?>
													<p><?php echo do_shortcode( $desc );?></p>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
								<?php if( !empty($app_features) ){ ?>
									<ul class="wt-appfeatures__list">
										<?php if( !empty($feature_title) ){?>
											<li class="wt-applisttitle"><h3><?php echo esc_html($feature_title);?></h3></li>
										<?php } ?>
										<?php
											foreach($app_features as $feature ){
												$title		= !empty($feature['title']) ? $feature['title'] : '';
												$hot_option	= !empty($feature['hot_option']) ? $feature['hot_option'] : '';
												if( !empty($title) ){ ?>
													<li>
														<span>
															<?php 
															echo esc_html($title);
															if( !empty($hot_option) && $hot_option == 'yes' ){?>
																<em class="wt-hottag"><?php esc_html_e('HOT','workreap_core');?></em>
															<?php } ?>
														</span>
													</li>
											<?php } ?>
										<?php } ?>
									</ul>
								<?php } ?>
								<?php if( !empty($mobile_apps) ){?>
									<div class="wt-appfeatures__footer">
										<?php
											foreach($mobile_apps as $app){
												$app_image	= !empty($app['image']['url']) ? $app['image']['url'] : '';
												$app_url	= !empty($app['url']) ? $app['url'] : '';
												$app_title	= !empty($app['title']) ? $app['title'] : '';
												if( !empty($app_image) ) {?>
													<a href="<?php echo esc_url($app_url);?>"><img src="<?php echo esc_url($app_image);?>" alt="<?php echo esc_attr($app_title);?>"></a>
												<?php } ?>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Download_APP_V3 ); 
}