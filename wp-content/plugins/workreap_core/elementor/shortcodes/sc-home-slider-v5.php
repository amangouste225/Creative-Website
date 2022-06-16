<?php
/**
 * Shortcode for home slider v5
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

if( !class_exists('Workreap_Home_Slider_V5') ){
	class Workreap_Home_Slider_V5 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_slider_v5';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Search Banner V5', 'workreap_core' );
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
			$list_names	= array();
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
				'search_form',
				[
					'type'      	=> \Elementor\Controls_Manager::SWITCHER,
					'label'     	=> esc_html__( 'Form Enable/Disbale', 'workreap_core' ),
					'label_on' 		=> esc_html__( 'Enable', 'workreap_core' ),
					'label_off' 	=> esc_html__( 'Disable', 'workreap_core' ),
					'return_value' 	=> 'yes',
					'default' 		=> 'yes',
				]
			);
			$this->add_control(
				'search_form_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Form title', 'workreap_core'),
					'description' 	=> esc_html__('Add title. leave it empty to hide.', 'workreap_core'),
					'condition' => [
						'search_form' => [ 'yes' ],
					],
				]
			);
			
			$this->add_control(
				'search_form_subtitle',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Form sub title', 'workreap_core'),
					'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap_core'),
					'condition' => [
						'search_form' => [ 'yes' ],
					],
				]
			);

			$this->add_control(
				'search',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label' 		=> esc_html__('Search options', 'workreap_core'),
        			'multiple' 		=> true,
					'options' 		=> $list_names,
					'label_block' 	=> true,
					'default' => [ 'job', 'freelancer' ],
					'condition' => [
						'search_form' => [ 'yes' ],
					],
				]
			);

			$this->add_control(
				'top_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Top Title', 'workreap_core' ),
					'description' 	=> esc_html__('Add top title or leave it empty to hide.', 'workreap_core'),
				]
			);

			

			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Title', 'workreap_core' ),
					'description' 	=> esc_html__('Add title or leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add  SubTitle', 'workreap_core' ),
					'description' 	=> esc_html__('Add sub title or leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'desc',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__( 'Add Description', 'workreap_core' ),
					'description' 	=> esc_html__('Add description or leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'slides',
				[
					'label'  => esc_html__( 'Add slide', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
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
				'particles',
				[
					'type'      	=> \Elementor\Controls_Manager::SWITCHER,
					'label'     	=> esc_html__( 'Particles Enable/Disbale', 'workreap_core' ),
					'label_on' 		=> esc_html__( 'Enable', 'workreap_core' ),
					'label_off' 	=> esc_html__( 'Disable', 'workreap_core' ),
					'return_value' 	=> 'yes',
					'default' 		=> 'no',
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
			$list_names	= '';
			if( function_exists('worktic_get_search_list') ){
				$list_names	= worktic_get_search_list('yes');
			}
			
			$settings = $this->get_settings_for_display();

			$particles				= !empty($settings['particles']) ? $settings['particles'] : '';
			$search_form			= !empty($settings['search_form']) ? $settings['search_form'] : '';
			$search_form_title		= !empty($settings['search_form_title']) ? $settings['search_form_title'] : '';
			$search_form_subtitle	= !empty($settings['search_form_subtitle']) ? $settings['search_form_subtitle'] : '';
			$searchs	    		= !empty($settings['search']) ? $settings['search'] : array();
			if(function_exists('get_final_search_list')){
				$searchs			= get_final_search_list($searchs);
			}
			$top_title				= !empty($settings['top_title']) ? $settings['top_title'] : '';
			$title					= !empty($settings['title']) ? $settings['title'] : '';
			$sub_title				= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$description			= !empty($settings['desc']) ? $settings['desc'] : '';
			$slides				    = !empty($settings['slides']) ? $settings['slides'] : array();
			
			$background_color		= !empty($settings['_background_color']) ? $settings['_background_color'] : '';
			$background_color_b		= !empty($settings['_background_color_b']) ? $settings['_background_color_b'] : '';
			$background_color_dgree		= !empty($settings['_background_gradient_angle']['size']) ? $settings['_background_gradient_angle']['size'].''.$settings['_background_gradient_angle']['unit'] : '';
			
			$background_color_radial		= !empty($settings['_background_gradient_position']) ?  'at '.$settings['_background_gradient_position'] : '';
			$background_color_a_unit		= !empty($settings['_background_color_stop']['size']) ? $settings['_background_color_stop']['size'].''.$settings['_background_color_stop']['unit'] : '';
			$background_color_b_unit		= !empty($settings['_background_color_b_stop']['size']) ? $settings['_background_color_b_stop']['size'].''.$settings['_background_color_b_stop']['unit'] : '';
			
			$default_key			= !empty($searchs) ? reset($searchs) : '';
			$flag 					= rand(9999, 999999);

			$default_url			= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$default_url	= !empty($default_key) ? workreap_get_search_page_uri($default_key) : '';
			}
			?>
			<div class="wt-sc-slider-v5 wt-bannerholdervthree wt-haslayout dynamic-secton-<?php echo esc_attr( $flag );?>">
				<div class="wt-bannercontent-wrap">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<div class="wt-bannerthree-content">
									<?php if(!empty($search_form) && $search_form === 'yes') { ?>
										<div class="wt-bannerthree-form">
											<?php if( !empty($searchs) ) {?>
												<form class="wt-formtheme wt-form-service search-form do-append-url" action="<?php echo esc_url($default_url);?>" method="get">
													<?php if(!empty($search_form_title) || !empty($search_form_subtitle)) { ?>
														<div class="wt-formtitlethree">
															<h3><?php if(!empty($search_form_title)) { ?><span><?php echo esc_html($search_form_title); ?></span><?php } ?>
															<?php if(!empty($search_form_subtitle)) { echo esc_html($search_form_subtitle); } ?></h3>
														</div>
													<?php } ?>
													<fieldset>
														<div class="form-group">
															<input name="keyword" type="text" placeholder="<?php esc_attr_e('I’m looking for', 'workreap_core'); ?>">
														</div>
														<div class="form-group">
															<span class="wt-select">
																<select name="searchtype">
																<?php foreach( $searchs as $search ) { 
																	$action_url		= '';
																	if( function_exists('workreap_get_search_page_uri') ){
																		$action_url		= workreap_get_search_page_uri($search);
																	}
				
																	$search_title	= !empty( $list_names[$search] ) ? $list_names[$search] : '';
																	
																	?> 
																	<option value="<?php echo esc_attr($search); ?>" data-url="<?php echo esc_url($action_url);?>"><?php echo esc_html($search_title); ?></option>
																<?php } ?>
																</select>
															</span>
														</div>
														<div class="form-group">
															<?php do_action('worktic_get_locations_list','location[]',''); ?>
														</div>
														<div class="form-group wt-pricerange-group">
															<?php do_action('workreap_print_price_range', '', 'top', 'disable', esc_html__('more than 2500 listings available','workreap_core'), esc_html__('Price Range:','workreap_core')); ?>
														</div>
													</fieldset>
												</form>
												<div class="wt-bannerthreeform-footer">
													<button type="submit" class="wt-btntwo search-form-submit"><?php echo esc_html__('Search Now', 'workreap_core'); ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if(!empty($top_title) || !empty($title) || !empty($sub_title) || !empty($description)) { ?>
										<div class="wt-bannerthree-title">
											<?php if(!empty($top_title)) { ?><span><?php echo esc_html($top_title); ?></span><?php } ?>
											<?php if(!empty($title) || !empty($sub_title)) { ?>
												<h2><?php if(!empty($title)) { ?><em><?php echo esc_html($title); ?></em><?php } ?>
												<?php if(!empty($sub_title)) { echo esc_html($sub_title); } ?></h2>
											<?php } ?>
											<?php if(!empty($description)) { ?>
												<div class="wt-description">
													<?php echo wpautop(do_shortcode( $description )); ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if(!empty($slides)) { ?>
					<div id="particles-js" class="wt-particles particles-js"></div>
					<div id="wt-home-slider-v5-<?php echo intval($flag); ?>" class="wt-home-slider owl-carousel">
						<?php 
						foreach ($slides as $slide) {
							$slide_img = !empty($slide['image']['url']) ? $slide['image']['url'] : '';
							if (!empty($slide_img)) { ?>
							<figure class="item">
								<img src="<?php echo esc_url($slide_img); ?>" alt="<?php esc_attr_e('Slide', 'workreap_core'); ?>">
							</figure>
						<?php } } ?>
					</div>
				<?php } ?>
			</div>
			<script>
				jQuery(document).on('ready',function () {
					var carouselv5_init = jQuery('#wt-home-slider-v5-<?php echo esc_js($flag);?>').owlCarousel({
						rtl: <?php echo workreap_owl_rtl_check();?>,
						items: 1,
						nav:false,
						loop:true,
						dots: false,
						autoplay:true,
						autoplayTimeout:5000,
						animateOut: 'fadeOut',
						animateIn: 'fadeIn',
						touchDrag: false,
						mouseDrag: false
					});

					carouselv5_init.trigger('refresh.owl.carousel');
					setTimeout( function(){carouselv5_init.trigger('refresh.owl.carousel');}, 500);
				});
			</script>
			
			<?php if(!empty($background_color) && $background_color_b){?>
				<style>
					.wt-home-slider:after,
					.wt-bannerholdervthree:before{
						background-color: transparent;
                        <?php if(!empty($background_color_dgree) ){?>
							background-image: linear-gradient(<?php echo esc_html($background_color_dgree);?>, <?php echo esc_html($background_color);?> <?php echo esc_html($background_color_a_unit);?>, <?php echo esc_html($background_color_b);?> <?php echo esc_html($background_color_b_unit);?>);
						<?php }elseif(!empty($background_color_radial) ){?>
							background-image: radial-gradient(<?php echo esc_html($background_color_radial);?>, <?php echo esc_html($background_color);?> <?php echo esc_html($background_color_a_unit);?>, <?php echo esc_html($background_color_b);?> <?php echo esc_html($background_color_b_unit);?>);
						<?php }?>
						
					}
				</style>
			<?php }?>
			
			<?php 
			if(!empty($particles) && $particles === 'yes'){
				$script = '
				function init_paricles(){
					particlesJS("particles-js",{
						"particles": {
							"number": {
							  "value": 65,
							  "density": {
								"value_area": 600
							  }
							},
							"size": {
								"value": 4,
								"random": true,
							},
							"opacity": {
								"value": 0.9,
							},
							"move": {
								"enable": true,
								"speed": 6,
								"direction": "none",
								"random": false,
								"straight": false,
								"out_mode": "out",
								"bounce": false,
								"attract": {
								  "enable": false,
								  "rotateX": 600,
								  "rotateY": 1200
								}
							  }
						}
					});
				}
				jQuery(document).on("ready", function(){
					init_paricles();
					setTimeout(
					  function() 
					  {
						init_paricles();
					  }, 3000);
				});
				';
				wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
			}
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Home_Slider_V5 ); 
}