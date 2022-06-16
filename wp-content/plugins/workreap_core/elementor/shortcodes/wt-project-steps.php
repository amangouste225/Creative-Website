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

if( !class_exists('Workreap_Project_Steps') ){
	class Workreap_Project_Steps extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_project_steps';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Project Steps', 'workreap_core' );
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
				'details',
				[
					'label' => esc_html__( 'Add Title', 'workreap_core' ),
					'type'  => Controls_Manager::TEXTAREA,
				]
			);

			$this->add_control(
				'btn_text',
				[
					'label' 		=> esc_html__( 'Add Button text', 'workreap_core' ),
					'type'  		=> Controls_Manager::TEXT,
					'description'   => esc_html__( 'Add Button text. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'btn_url',
				[
					'label' 		=> esc_html__( 'Add Button URL', 'workreap_core' ),
					'type'  		=> Controls_Manager::TEXT
				]
			);

			$this->add_control(
				'steps',
				[
					'label'  => esc_html__( 'Add Project Setp', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Upload Image', 'workreap_core' )
						],
						[
							'name'  => 'bg_color',
							'label' => esc_html__( 'Add Box background color', 'workreap_core' ),
							'type' => Controls_Manager::COLOR
						],
						[
							'name'  => 'step_title',
							'label' => esc_html__( 'Add Setp Title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'step_color',
							'label' => esc_html__( 'Add Setp title background color', 'workreap_core' ),
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
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Description', 'workreap_core' )
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
			$settings 		= $this->get_settings_for_display();
			$title     		= !empty($settings['title']) ? $settings['title'] : '';
			$details    	= !empty($settings['details']) ? $settings['details'] : '';
			$btn_text   	= !empty($settings['btn_text']) ? $settings['btn_text'] : '';
			$btn_url    	= !empty($settings['btn_url']) ? $settings['btn_url'] : '';
			$steps     		= !empty($settings['steps']) ? $settings['steps'] : array();
			$uniq_flag  	= rand(1,9999);
			$style			= '';
			?>
			<div class="wt-projectsteps-section wt-comsectionwrap">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="wt-sectionhead wt-sectionheadvfour wt-communityhead">
								<div class="wt-sectiontitle wt-sectiontitlevthree">
									<?php if( !empty($title) ) {?><h2><?php echo esc_html($title);?></h2><?php } ?>
									<?php if( !empty($details) ) {?><p><?php echo esc_html($details);?></p><?php } ?>
								</div>
								<?php if( !empty($btn_text) ){?>
									<div class="wt-communityhead-btn">
										<a href="<?php echo esc_url($btn_url);?>" class="wt-btnthree"><?php echo esc_html($btn_text);?></a>
									</div> 
								<?php } ?> 
							</div>
							<?php if( !empty($steps) ){?>
								<ul class="wt-community-list">
									<?php 
										$counter	= 0;
										foreach($steps as $step ){
											$counter++;
											$step_number	= !empty($step['step_title']) ? $step['step_title'] : '';
											$title			= !empty($step['title']) ? $step['title'] : '';
											$details		= !empty($step['details']) ? $step['details'] : '';
											$url			= !empty($step['url']) ? $step['url'] : '';
											$image			= !empty($step['image']['url']) ? $step['image']['url'] : '';
											$bg_color		= !empty($step['bg_color']) ? $step['bg_color'] : '';
											$step_color		= !empty($step['step_color']) ? $step['step_color'] : '';
											$style			= $style.' .wt-comitem-bg-'.intval($uniq_flag).'-'.intval($counter).' {background:linear-gradient(to bottom, '.$bg_color.' 0%,rgba(198,240,215,0) 100%)} .wt-comitem-img-'.intval($uniq_flag).'-'.intval($counter).' span{background:'.$step_color.' !important}';
										?>
										<li>
											<div class="wt-comitem-bg wt-comitem-bg-<?php echo (intval($uniq_flag).'-'.intval($counter));?>"><span></span></div>
											<figure class="wt-comitem-img wt-comitem-img-<?php echo (intval($uniq_flag).'-'.intval($counter));?>">
												<?php if( !empty($image) ){?>
													<img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($title);?>">
												<?php } ?>
												<?php if( !empty($step_number) ) {?><span><?php echo esc_html($step_number);?></span><?php } ?>
											</figure>
											<div class="wt-comitem-title">
												<?php if( !empty($title) || !empty($details) ) {?>
													<h3><a href="<?php echo esc_url($url);?>"><?php echo esc_html($title);?></a><?php if( !empty($details) ) {?><span><?php echo esc_html($details);?></span><?php } ?></h3>
												<?php } ?>
											</div>
										</li>
									<?php } ?>
								</ul>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			if( !empty($style) ) {?>
				<style scoped><?php echo do_shortcode( $style );?></style>
			<?php }
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Project_Steps ); 
}