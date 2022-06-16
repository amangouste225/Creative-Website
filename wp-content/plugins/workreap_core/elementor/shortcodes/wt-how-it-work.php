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

if( !class_exists('Workreap_How_Works') ){
	class Workreap_How_Works extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_how_works';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'How It works', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-click';
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
				'text_color',
				[
					'type'      	=> Controls_Manager::COLOR,
					'label'     	=> esc_html__( 'Text color', 'workreap_core' ),
					'description'   => esc_html__( 'Add text color. leave it empty to use default color.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Title', 'workreap_core' ),
					'description'   => esc_html__( 'Add section title. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Sub title', 'workreap_core' ),
					'description'   => esc_html__( 'Add section sub title. Leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap_core'),
        			'description' 	=> esc_html__('Add description. Leave it empty to hide description.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'work_process',
				[
					'label'  => esc_html__( 'Add work process', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'title',
							'label' => esc_html__( 'Add title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'sub_title',
							'label' => esc_html__( 'Add sub title', 'workreap_core' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Upload image', 'workreap_core' ),
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
			$settings = $this->get_settings_for_display();

			$text_color		= !empty($settings['text_color']) ? $settings['text_color'] : '';
			$title       	= !empty($settings['title']) ? $settings['title'] : '';
			$sub_title   	= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	     	= !empty($settings['description']) ? $settings['description'] : '';

			$work_process  		= !empty($settings['work_process']) ? $settings['work_process'] : array();
			$count_process		= 0;
			$flag 				= rand(9999, 999999);
			?>
			<div class="wt-sc-how-it-work wt-workholder dynamic-secton-<?php echo esc_attr( $flag );?>">
				<?php if( !empty( $title )  || !empty( $sub_title ) || !empty( $desc ) ){?>
					<div class="row justify-content-center align-self-center">
						<div class="col-12 col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2">
							<div class="wt-sectionhead wt-textcenter wt-howswork">
								<?php if( !empty( $title )  || !empty( $sub_title ) ){?>
									<div class="wt-sectiontitle">
										<?php if( !empty( $title ) ) {?><h2><?php echo esc_html( $title );?></h2><?php }?>
										<?php if( !empty( $sub_title ) ) {?><span><?php echo esc_html( $sub_title );?></span><?php } ?>
									</div>
								<?php }?>
								<?php if( !empty( $desc ) ){?>
									<div class="wt-description"><?php echo do_shortcode( $desc );?></div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if( !empty( $work_process ) ) {?>
					<div class="wt-haslayout wt-workprocess">
						<div class="row">
							<?php
								foreach ( $work_process as $work_proc ){
									$count_process	++;
									if( intval( $count_process ) == 2 ) {
										$class	= 'wt-workdetails-border';
									} else if( intval( $count_process ) == 3 ) {
										$class	= 'wt-workdetails-bordertwo';
									} else {
										$class	= '';
									}

									$img_url	= !empty( $work_proc['image']['url'] ) ? $work_proc['image']['url'] : '';
									$title		= !empty( $work_proc['title'] ) ? $work_proc['title'] : '';
									$sub_title	= !empty( $work_proc['sub_title'] ) ? $work_proc['sub_title'] : '';?>
									<div class="col-12 col-sm-12 col-md-6 col-lg-4 float-left">
										<div class="wt-workdetails <?php echo esc_attr( $class );?>">
											<?php if( !empty( $img_url ) ) {?>
												<div class="wt-workdetail">
													<figure><img src="<?php echo esc_url( $img_url );?>" alt="<?php echo esc_attr( $title );?>"></figure>
												</div>
											<?php } ?>
											<div class="wt-title">
												<?php if( !empty( $title ) ) {?>
													<span><?php echo esc_html( $title );?></span>
												<?php }?>
												<?php if( !empty( $sub_title ) ){?>
													<h3><?php echo esc_html( $sub_title );?></h3>
												<?php }?>
											</div>
										</div>
									</div>
							<?php }?>
						</div>
					</div>
				<?php } ?>	
			</div>
			<?php 
			if( !empty ( $text_color ) ) { ?>
				<style scoped>
					.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-howswork .wt-sectiontitle h2, .wt-howswork .wt-sectiontitle span, .wt-howswork .wt-description p, 
					.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-workdetails .wt-title span,
					.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-workdetails .wt-title h3 a{ color : <?php echo esc_html($text_color);?>}
				</style>
			<?php 
			} 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_How_Works ); 
}