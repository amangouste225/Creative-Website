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

if( !class_exists('Workreap_Working_Process') ){
	class Workreap_Working_Process extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_working_process';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Working Process', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-product-info';
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
				'text_align',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Text Alignment', 'workreap_core'),
					'default' => 'left',
					'options' => [
						'left'  => esc_html__( 'Left', 'workreap_core' ),
						'right' => esc_html__( 'Right', 'workreap_core' ),
					],
				]
			);
			
			$this->add_control(
				'image_align',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Image Alignment', 'workreap_core'),
					'default' => 'right',
					'options' => [
						'yes'  => esc_html__( 'Yes', 'workreap_core' ),
						'right' => esc_html__( 'Right', 'workreap_core' ),
					],
				]
			);
			
			$this->add_control(
				'open',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Open First?', 'workreap_core'),
					'default' => 'no',
					'options' => [
									'yes'  	=> esc_html__( 'Yes', 'workreap_core' ),
									'no' 	=> esc_html__( 'No', 'workreap_core' ),
								],
				]
			);
			
			
			$this->add_control(
				'faqs',
				[
					'label'  => esc_html__( 'Add FAQs', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'title',
							'label' => esc_html__( 'Add title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'question',
							'label' => esc_html__( 'Add question', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
							
						],
						[
							'name'  => 'answer',
							'label' => esc_html__( 'Add Answer', 'workreap_core' ),
							'type'  => Controls_Manager::WYSIWYG,
							
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

			$image       = !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$title       = !empty($settings['title']) ? $settings['title'] : '';
			$sub_title   = !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	     = !empty($settings['description']) ? $settings['description'] : '';
			$text_align  = !empty($settings['text_align'] && $settings['text_align'] == 'left' ) ? 'float-left' : 'float-right';
			$image_align 	= !empty($settings['image_align'] && $settings['image_align'] == 'left' ) ? 'float-left' : 'float-right';
			$faqs        	= !empty($settings['faqs']) ? $settings['faqs'] : array();
			$open        	= !empty($settings['open']) ? $settings['open'] : 'no';
			$count_faq		= 0;
			?>
			<div class="wt-sc-how-it-works wt-haslayout">
				<?php 
					if( !empty( $image ) ||
						!empty( $title ) ||
						!empty( $sub_title ) ||
						!empty( $desc ) ||
						!empty( $faqs ) ) {
					?>
					<div class="wt-howitwork-hold wt-bgwhite wt-haslayout">
						<div class="wt-contentarticle">
							<div class="row">
								<div class="wt-starthiringhold wt-innerspace wt-haslayout">
								<?php 
									if( !empty( $title ) ||
										!empty( $sub_title ) ||
										!empty( $desc ) ||
										!empty( $faqs ) ) {
									?>
									<div class="col-12 col-sm-12 col-md-8 col-lg-7 <?php echo esc_attr($text_align); ?>">
										<div class="wt-starthiringcontent">
											<?php 
												if( !empty( $title ) || 
													!empty( $sub_title ) ||
													!empty( $desc ) ) { 
												?>
												<div class="wt-sectionhead">
													<?php if( !empty( $title ) || !empty( $sub_title ) ) { ?>
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
											<?php if( !empty( $faqs ) ) { 
												$counter	= 0;
												?>
												<ul class="wt-accordionhold accordion">
													<?php foreach( $faqs as $key => $faq ) { 
														$count_faq = rand(0,999);
														$title    = !empty( $faq['title'] ) ? $faq['title'] : '';
														$question = !empty( $faq['question'] ) ? $faq['question'] : '';
														$answer   = !empty( $faq['answer'] ) ? $faq['answer'] : '';
														$counter++; 
														$show = '';

														if( $open === 'yes' && $counter === 1 ) {
															$show = 'show';
														}
														?>
														<li>
															<?php if( !empty( $title ) ) { ?>
																<div class="wt-accordiontitle" id="heading-<?php echo intval($count_faq); ?>" data-toggle="collapse" data-target="#collapse-<?php echo intval($count_faq); ?>">
																	<span><?php echo esc_html( $title ); ?></span>
																</div>
															<?php } ?>
															<?php if( !empty( $question ) || !empty( $answer ) ) { ?>
																<div class="wt-collapse-item collapse <?php echo esc_attr( $show ); ?>" id="collapse-<?php echo intval( $count_faq ); ?>" aria-labelledby="heading-<?php echo intval( $count_faq ); ?>">
																	<div class="wt-accordiondetails">
																		<?php if( !empty( $question ) ) { ?>
																			<div class="wt-title">
																				<h3><?php echo esc_html( $question ); ?></h3>
																			</div>
																		<?php } ?>
																		<?php if( !empty( $answer ) ) { ?>
																			<div class="wt-description">
																				<?php echo wp_kses_post( wpautop( do_shortcode( $answer ) ) ); ?>
																			</div>
																		<?php } ?>
																	</div>
																</div>
															<?php } ?>
														</li>
													<?php } ?>
												</ul>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								<?php if( !empty( $image ) ) { ?>
									<div class="col-12 col-sm-12 col-md-4 col-lg-5 <?php echo esc_attr($image_align); ?>">
										<div class="wt-howtoworkimg">
											<figure>
												<img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'Working', 'workreap_core' ); ?>">
											</figure>
										</div>
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Working_Process ); 
}