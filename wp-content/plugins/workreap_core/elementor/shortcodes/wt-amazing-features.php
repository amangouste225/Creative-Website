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

if( !class_exists('Workreap_Amazing_Features') ){
	class Workreap_Amazing_Features extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_amazing_features';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Amazing Features', 'workreap_core' );
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
				'top_side_img',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Sidebar Image', 'workreap_core' ),
					'description'   => esc_html__( 'Upload image. leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'main_img',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Main Image', 'workreap_core' ),
					'description'   => esc_html__( 'Upload image. leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'bg_image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Background Image', 'workreap_core' ),
					'description'   => esc_html__( 'Upload image. leave it empty to hide.', 'workreap_core' ),
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
				'details',
				[
					'label' => esc_html__( 'Add description', 'workreap_core' ),
					'type'  => Controls_Manager::TEXTAREA,
				]
			);
		
			
			$this->add_control(
				'features',
				[
					'label'  => esc_html__( 'Add Features', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name' 			=> 'icon',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Add icon code', 'workreap_core' ),
							'description'   => esc_html__( 'Add icon code like(fa fa-chart-bar,fa fa-eye-slash or fa fa-comment-o)', 'workreap_core' ),
						],
						[
							'name'  => 'bg_color',
							'label' => esc_html__( 'Add icon background color', 'workreap_core' ),
							'type' => Controls_Manager::COLOR
						],
						[
							'name'  => 'icon_color',
							'label' => esc_html__( 'Add icon color', 'workreap_core' ),
							'type' => Controls_Manager::COLOR
						],
						[
							'name'  => 'title',
							'label' => esc_html__( 'Add Title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'url',
							'label' => esc_html__( 'Add URL', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name' 			=> 'details',
							'type'      	=> Controls_Manager::TEXTAREA,
							'label'     	=> esc_html__( 'Description', 'workreap_core' ),
							'description'   => esc_html__( 'Add description. Leave it empty to hide.', 'workreap_core' ),
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
			$details    	= !empty($settings['details']) ? $settings['details'] : '';
			$top_side_img   = !empty($settings['top_side_img']['url']) ? $settings['top_side_img']['url'] : '';
			$main_img    	= !empty($settings['main_img']['url']) ? $settings['main_img']['url'] : '';
			$bg_image    	= !empty($settings['bg_image']['url']) ? $settings['bg_image']['url'] : '';
			$features     	= !empty($settings['features']) ? $settings['features'] : array();
			$uniq_flag  	= rand(1,9999);
			$style			= "";
			?>
			<div class="wt-amazing-section">
				<div class="container">
					<div class="row align-items-center">
						<?php if( !empty($main_img) || !empty($top_side_img) || !empty($bg_image) ){?>
							<div class="col-12 col-lg-5 col-xl-6">
								<figure class="wt-servicesimg">
									<?php if( !empty($main_img) ){ ?>
										<img src="<?php echo esc_url($main_img);?>" class="wt-servicesimg_img" alt="<?php echo esc_attr($title);?>">
									<?php } ?>
									<?php if( !empty($bg_image) ){ ?>
										<img src="<?php echo esc_url($bg_image);?>" class="wt-servicesimg-bg" alt="<?php echo esc_attr($title);?>">
									<?php } ?>
									<?php if( !empty($top_side_img) ){ ?>
										<img src="<?php echo esc_url($top_side_img);?>" class="wt-servicesimg-icon" alt="<?php echo esc_attr($title);?>">
									<?php } ?>
								</figure>
							</div>
						<?php } ?>
						<div class="col-12 col-lg-7 col-xl-6">
							<div class="wt-servicesvtwo">
								<div class="wt-sectiontitletwo">
									<?php if( !empty($title) ) {?>
										<h2><?php echo esc_html($title);?></h2>
									<?php } ?>
									<?php if( !empty($details) ) {?>
										<p><?php echo esc_html($details);?></p>
									<?php } ?>
								</div>
								<?php if( !empty($features) ){?>
									<ul class="wt-servicesvtwo-list">
										<?php 
											$counter	= 0;
											foreach($features as $feature ){
												$counter++;
												$icon		= !empty($feature['icon']) ? $feature['icon'] : '';
												$title		= !empty($feature['title']) ? $feature['title'] : '';
												$details	= !empty($feature['details']) ? $feature['details'] : '';
												$bg_color	= !empty($feature['bg_color']) ? $feature['bg_color'] : '';
												$icon_color	= !empty($feature['icon_color']) ? $feature['icon_color'] : '';
												$url		= !empty($feature['url']) ? $feature['url'] : '';
												$show_class	= !empty($counter) ? 'lx-serbg-'.intval($uniq_flag).'-'.intval($counter) : '';
												
												$style		= $style.' .'.$show_class.' span{color:'.$icon_color.';background: '.$bg_color.';}';
												?>
												<li>
													<?php if( !empty($icon) ){?>
														<div class="wt-srvlist-icon <?php echo esc_attr($show_class);?>">
															<span class="<?php echo esc_attr($icon);?>"></span>
														</div>
													<?php } ?>
													<?php if( !empty($title) || !empty($details) ){?>
														<div class="wt-srvlist-title">
															<?php if( !empty($title) ){?>
																<h3><a href="<?php echo esc_url($url);?>"><?php echo esc_html($title);?></a></h3>
															<?php } ?>
															<?php if( !empty($details) ){?>
																<p><?php echo esc_html($details);?></p>
															<?php } ?>
														</div>
													<?php } ?>
												</li>
										<?php  } ?>
									</ul>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			if( !empty($style) ){ ?>
			<style scoped><?php echo do_shortcode( $style );?></style>
			<?php }
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Amazing_Features ); 
}