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

if( !class_exists('Workreap_Welcome') ){
	class Workreap_Welcome extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_welcome';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Welcome Note', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-testimonial';
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
				'video_thumbnail',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label' 		=> esc_html__('Upload Image', 'workreap_core'),
        			'description' 	=> esc_html__('Upload video thumbnail. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'video_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Video link', 'workreap_core'),
        			'description' 	=> esc_html__('Upload video link. leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'counters',
				[
					'label'  => esc_html__( 'Add Counter', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'counter_title',
							'label' => esc_html__( 'Counter Title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'counter_start',
							'label' => esc_html__( 'Start Number', 'workreap_core' ),
							'type'  => Controls_Manager::NUMBER,
						],
						[
							'name'  => 'counter_end',
							'label' => esc_html__( 'End Number', 'workreap_core' ),
							'type'  => Controls_Manager::NUMBER,
						],
						[
							'name'  => 'counter_symbol',
							'label' => esc_html__( 'Symbol', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'counter_interval',
							'label' => esc_html__( 'Interval', 'workreap_core' ),
							'type' => Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 1,
									'max' => 50,
									'step' => 1,
								]
							],
							'default' => [
								'unit' => '%',
								'size' => 50,
							],
							'selectors' => [
								'{{WRAPPER}} .box' => 'width: {{SIZE}}{{UNIT}};',
							],
					],
						[
							'name'  => 'counter_speed',
							'label' => esc_html__( 'Speed', 'workreap_core' ),
							'type' => Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 1,
									'max' => 50,
									'step' => 1,
								]
							],
							'default' => [
								'unit' => '%',
								'size' => 50,
							],
							'selectors' => [
								'{{WRAPPER}} .box' => 'width: {{SIZE}}{{UNIT}};',
							],
					]
				]
			]);
			
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

			$thumbnail  = !empty($settings['video_thumbnail']['url']) ? $settings['video_thumbnail']['url'] : '';
			$video_link = !empty($settings['video_link']) ? $settings['video_link'] : '';
			$title      = !empty($settings['title']) ? $settings['title'] : '';
			$sub_title  = !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	    = !empty($settings['description']) ? $settings['description'] : '';
			$counters   = !empty($settings['counters']) ? $settings['counters'] : array();
			
			$uniq_flag   = fw_unique_increment();
			?>
			<div class="wt-sc-greetings wt-haslayout">
				<?php 
				if( !empty( $thumbnail ) ||
					!empty( $video_link ) ||
					!empty( $title ) ||
					!empty( $sub_title ) ||
					!empty( $desc ) ||
					!empty( $counters ) ) {
					?>
					<div class="wt-greeting-holder">
						<div class="row">
							<?php 
							if( !empty( $title ) ||
								!empty( $sub_title ) ||
								!empty( $desc ) ||
								!empty( $counters ) ) {
								?>
								<div class="col-12 col-sm-12 col-md-12 col-lg-7 float-left">
									<div class="wt-greetingcontent">
										<?php 
											if( !empty( $title ) ||
												!empty( $sub_title ) ||
												!empty( $desc ) ) {
											?>
											<div class="wt-sectionhead">
												<?php 
													if( !empty( $title ) ||
														!empty( $sub_title ) 
													) {
													?>
													<div class="wt-sectiontitle">
														<?php if( !empty( $title ) ) { ?>
															<h2><?php echo esc_html( $title ); ?></h2>
														<?php } ?>
														<?php if( !empty( $sub_title ) ) { ?>
															<span><?php echo esc_html( $sub_title ); ?></span>
														<?php } ?>
													</div>
												<?php } ?>
												<?php if( !empty( $desc ) ) { ?>
													<div class="wt-description">
														<?php echo wp_kses_post( wpautop( do_shortcode( $desc ) ) ); ?>
													</div>
												<?php } ?>
											</div>
										<?php } ?>
										<?php 
											if( !empty( $counters ) ) { ?>
												<div id="wt-statistics-<?php echo esc_attr($uniq_flag); ?>" class="wt-statistics">
													<?php
														$counter_star	= 0;
														foreach ($counters as $counter) {
															$counter_star ++;
															$counter_title 		= !empty($counter['counter_title']) ? $counter['counter_title'] : '' ;
															$start_from 		=  !empty($counter['counter_start']) ? $counter['counter_start'] : intval(0);
															$counter_end 		= isset($counter['counter_end']) && $counter['counter_end'] != '' ? $counter['counter_end'] : intval(1000);
															$counter_interval 	= !empty($counter['counter_interval']['size'])  ? $counter['counter_interval']['size'] : intval(50);
															$counter_speed 		= !empty($counter['counter_speed']['size']) ? $counter['counter_speed']['size'] : intval(8000);
															$counter_symbol 	=  !empty($counter['counter_symbol']) ? $counter['counter_symbol'] : '';
														?>
														<div class="wt-statisticcontent wt-countercolor<?php echo esc_attr( $counter_star );?>">
															<h3 data-from="<?php echo esc_attr($start_from); ?>" data-to="<?php echo esc_attr($counter_end) ?>" data-speed="<?php echo esc_attr($counter_speed); ?>" data-refresh-interval="<?php echo esc_attr($counter_interval); ?>"><?php echo esc_html($counter_end) ?></h3>
															<?php if( !empty( $counter_title ) ) { ?>
																<h4><?php echo esc_html( $counter_title ); ?></h4>
																<?php if( !empty( $counter_symbol ) ) {?>
																	<em><?php echo esc_html( $counter_symbol );?></em>
																<?php } ?>
															<?php } ?>
														</div>
													<?php } ?>
												</div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<?php if( !empty( $thumbnail ) ) { ?>
								<div class="col-12 col-sm-12 col-md-12 col-lg-5 float-left">
									<div class="wt-greetingvideo">
										<figure>
											<?php if(!empty($video_link)){?>
												<a class="wt-venobox" data-autoplay="true" data-vbtype="video"  href="<?php echo esc_url( $video_link ); ?>">
													<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php esc_attr_e('Welcome & Greetings', 'workreap_core') ?>">
												</a>
											<?php }else{?>
												<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php esc_attr_e('Welcome & Greetings', 'workreap_core') ?>">
											<?php }?>
										</figure>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<script type="application/javascript">
						jQuery(document).ready(function () {
							try {
								var _wt_statistics = jQuery('#wt-statistics-<?php echo esc_js($uniq_flag);?>');
								_wt_statistics.appear(function () {
									var _wt_statistics = jQuery('.wt-statisticcontent h3');
									_wt_statistics.countTo({
										formatter: function (value, options) {
											return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
										}
									});
								});
							} catch (err) {} 
						});
					</script>
				<?php 
				$script	= "jQuery('.wt-venobox').venobox();";
				wp_add_inline_script( 'venobox', $script, 'after' );
				}
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Welcome ); 
}