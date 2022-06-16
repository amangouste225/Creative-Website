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

if( !class_exists('Workreap_Home_Slider_V4') ){
	class Workreap_Home_Slider_V4 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_slider_v4';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Search Banner v4', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-slider-album';
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
			$list_names	= '';
			if( function_exists('worktic_get_search_list') ){
				$list_names	= worktic_get_search_list('yes');
			}
			
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
        			'description' 	=> esc_html__('Add title. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Sub Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap_core' ),
					'rows' 			=> 10,
					'description'   => esc_html__( 'Add section description. Leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'video_title',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Video title', 'workreap_core'),
					'rows' 			=> 10,
        			'description' 	=> esc_html__('Add video title. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'video_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Video Url', 'workreap_core'),
        			'description' 	=> esc_html__('Add video url. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'search',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label' 		=> esc_html__('Search options', 'workreap_core'),
        			'multiple' 		=> true,
					'options' 		=> $list_names,
					'default' => [ 'job', 'freelancer' ],
					'label_block' 	=> true,
				]
			);
			
			$this->add_control(
				'slider',
				[
					'label'  => esc_html__( 'Add slide', 'workreap_core' ),
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
							'label'     	=> esc_html__( 'Upload slide Image', 'workreap_core' ),
							'description'   => esc_html__( 'Upload image.', 'workreap_core' ),
						]
						,
					],
					'default' => [],
				]
			);
			
			$this->add_control(
				'animation',
				[
					'label'  => esc_html__( 'Add Animation image', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Upload Image', 'workreap_core' ),
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

			$title				= !empty($settings['title']) ? $settings['title'] : '';
			$sub_title			= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$description		= !empty($settings['description']) ? $settings['description'] : '';
			$video_title		= !empty($settings['video_title']) ? $settings['video_title'] : '';
			$video_url			= !empty($settings['video_url']) ? $settings['video_url'] : '';
			$searchs	    	= !empty($settings['search']) ? $settings['search'] : array();
			if(function_exists('get_final_search_list')){
				$searchs			= get_final_search_list($searchs);
			}
			$defult_key			= !empty($searchs) ? reset($searchs) : '';
			$defult_url			= '';
			
			if( function_exists('workreap_get_search_page_uri') ){
				$defult_url			= !empty($defult_key) ? workreap_get_search_page_uri($defult_key) : '';
			}
			
			$list_names	= '';
			if( function_exists('worktic_get_search_list') ){
				$list_names	= worktic_get_search_list('yes');
			}
			
			$sliders			= !empty($settings['slider']) ? $settings['slider'] : '';
			$animated			= !empty($settings['animation']) ? $settings['animation'] : '';
			$flag 				= rand(9999, 999999);
			?>
			<div class="wt-sc-slider wt-haslayout wt-bannerholdervtwo wt-bannerholderthree dynamic-secton-<?php echo esc_attr( $flag );?>">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
							<div class="wt-bannercontent">
								<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $description ) ) {?>
									<div class="wt-bannerhead">
										<?php if( !empty( $title ) || !empty( $sub_title ) ) {?>
											<div class="wt-title">
												<h1>
													<?php if( !empty( $title ) ) {?><span><?php echo do_shortcode( $title );?></span> <?php } ?>
													<?php echo esc_html($sub_title);?>
												</h1>
											</div>
										<?php } ?>
										<?php if( !empty( $description )){?>
											<div class="wt-description"><?php echo do_shortcode( $description );?></div>
										<?php } ?>
									</div>
								<?php } ?>
								<?php if( !empty($searchs) ) {?>
									<form class="wt-formtheme wt-formbanner" action="<?php echo esc_url($defult_url);?>" method="get">
										<fieldset>
											<div class="form-group">
												<input type="text" name="keyword" class="form-control" placeholder="<?php esc_attr_e('I’m looking for','workreap_core');?>">
												<div class="wt-formoptions">
													<?php if( !empty($list_names[$defult_key]) ) { ?>
														<div class="wt-dropdown">
															<span><em class="selected-search-type"><?php echo esc_html( $list_names[$defult_key] );?></em><i class="lnr lnr-chevron-down"></i></span>
														</div>
													<?php } ?>
													<div class="wt-radioholder">
														<?php 
														  foreach( $searchs as $search ) {
															$action_url		= '';
															if( function_exists('workreap_get_search_page_uri') ){
																$action_url		= workreap_get_search_page_uri($search);
															}
															  
															if( !empty($search) && $search === $defult_key) {
																$checked	= 'checked';
															} else {
																$checked	= '';
															}
															  
															if(!empty($list_names[$search])){
																$flag_key 	= rand(9999, 999999);
															?>
															<span class="wt-radio">
																<input id="wt-<?php echo esc_attr( $flag_key );?>" data-url="<?php echo esc_url($action_url);?>" data-title="<?php echo esc_attr( $list_names[$search] );?>" type="radio" name="searchtype" value="<?php echo esc_attr($search);?>" <?php echo esc_attr($checked);?>>
																<label for="wt-<?php echo esc_attr( $flag_key );?>"><?php echo esc_html( $list_names[$search] );?></label>
															</span>
														<?php }} ?>
													</div>
													<button type="submit" class="wt-searchbtn">
														<span><?php esc_html_e('Search Now','workreap_core');?></span>
														<i class="fa fa-search"></i>
													</button>
												</div>
											</div>
										</fieldset>
									</form>
								<?php } ?>
								<?php if( !empty($video_url) || !empty($video_title) ) {?>
									<div class="wt-videoholder">
										<?php if( !empty( $video_url ) ){?>
											<div class="wt-videoshow">
												<a  class="venobox vbox-item" data-vbtype="video" data-autoplay="true" href="<?php echo esc_url( $video_url );?>"><i class="fa fa-play"></i></a>
											</div>
										<?php }?>
										<?php if( !empty( $video_title ) ){?>
											<div class="wt-videocontent">
												<span><?php echo do_shortcode( $video_title );?></span>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
							<?php if( !empty( $sliders ) || !empty( $animated )) {?>
								<div class="wt-bannerslider-holder">
									<?php if( !empty( $sliders ) ){?>
										<div id="wt-bannerslider-two-<?php echo intval($flag);?>" class="wt-bannerslider-two owl-carousel">
											<?php 
												foreach( $sliders as $slide ) {
													$image	= !empty( $slide['image']['url']) ? $slide['image']['url'] : '';
													?>
													<div class="item">
														<figure class="wt-bannerimg">
															<img src="<?php echo esc_url( $image );?>" alt="<?php esc_attr_e('Slider','workreap_core');?>">
														</figure>
													</div>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if( !empty( $animated ) ){?>
										<figure class="wt-slidericons">
											<?php 
												foreach( $animated as $slide ) {
													$image	= !empty( $slide['image']['url']) ? $slide['image']['url'] : ''; ?>
													<img src="<?php echo esc_url( $image );?>" alt="<?php esc_attr_e('Slider','workreap_core');?>">
												<?php } ?>
										</figure>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<script>
				jQuery(document).ready(function () {
					jQuery('#wt-bannerslider-two-<?php echo esc_js($flag);?>').owlCarousel({
						rtl: <?php echo workreap_owl_rtl_check();?>,
						items: 1,
						nav:false,
						loop:false,
						dots: false,
						autoplay:true,
						autoplayTimeout:5000,
						animateOut: 'fadeOut',
						animateIn: 'fadeIn',
						touchDrag: false,
						mouseDrag: false
					});
				});
			</script>
		<?php
			$script = 'jQuery(document).on("ready", function(){jQuery(".venobox").venobox();});';
			wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Home_Slider_V4 ); 
}