<?php
/**
 * Shortcode home banner V2
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

if( !class_exists('Workreap_Search_Banner_V2') ){
	class Workreap_Search_Banner_V2 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_search_v2';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Search Banner V2', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-banner';
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
				'video_title',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Video title', 'workreap_core'),
        			'description' 	=> esc_html__('Add video title. Leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'video_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Video url', 'workreap_core'),
        			'description' 	=> esc_html__('Add video url. Leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label' 		=> esc_html__('Upload banner images', 'workreap_core'),
        			'description' 	=> esc_html__('Add banner images. Leave it empty to hide.', 'workreap_core'),
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
				'particles',
				[
					'type'      	=> \Elementor\Controls_Manager::SWITCHER,
					'label'     	=> esc_html__( 'Particles Enable/Disbale', 'workreap_core' ),
					'label_on' 		=> esc_html__( 'Enable', 'workreap_core' ),
					'label_off' 	=> esc_html__( 'Disable', 'workreap_core' ),
					'return_value' 	=> 'yes',
					'default' 		=> 'yes',
				]
			);
			
			$this->add_control(
				'revoslider',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Add slider shortcode', 'workreap_core'),
        			'description' 	=> esc_html__('You can add your revolution or any other slider shortcode in this textbox. Leave it empty to use particles', 'workreap_core'),
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
			$particles				= !empty($settings['particles']) ? $settings['particles'] : '';
			$revoslider				= !empty($settings['revoslider']) ? $settings['revoslider'] : '';
			$title				= !empty($settings['title']) ? $settings['title'] : '';
			$text_color			= !empty($settings['text_color']) ? $settings['text_color'] : '';
			$sub_title			= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$video_title		= !empty($settings['video_title']) ? $settings['video_title'] : '';
			$video_url			= !empty($settings['video_url']) ? $settings['video_url'] : '';
			$image	    		= !empty($settings['image']) ? $settings['image'] : array();
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
			
			$flag 	= rand(9999, 999999);
			?>
			<div class="wt-sc-banner-v2 wt-homebannerseven wt-bannersevenvtwo wt-haslayout dynamic-secton-<?php echo esc_attr( $flag );?>">
				<div class="container">
					<div class="row">
						<div class="col-xl-8">
							<div class="wt-bannercontent wt-bannercontentseven">
								<?php if( !empty($title) || !empty($sub_title) ) {?>
									<div class="wt-bannerhead">
										<div class="wt-title">
											<h1><?php if(!empty($title)) { ?>
												<span><?php echo esc_html($title);?></span>
											<?php } ?>
											<?php if(!empty($sub_title)) {
												echo esc_html($sub_title);
											}?></h1>
										</div>
									</div>
								<?php }?>
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
																$action_url	= '';
																if( function_exists('workreap_get_search_page_uri') ){
																	$action_url	= workreap_get_search_page_uri($search);
																}
																if( !empty($search) && $search === $defult_key) {
																	$checked	= 'checked';
																} else {
																	$checked	= '';
																}
																
																$search_title	= !empty( $list_names[$search] ) ? $list_names[$search] : '';
																$flag_key 	= rand(9999, 999999);
															?>
															<span class="wt-radio">
																<input id="wt-<?php echo esc_attr( $flag_key );?>" data-url="<?php echo esc_url($action_url);?>" data-title="<?php echo esc_attr( $search_title );?>" type="radio" name="searchtype" value="<?php echo esc_attr($search);?>" <?php echo esc_attr($checked);?>>
																<label for="wt-<?php echo esc_attr( $flag_key );?>"><?php echo esc_html( $search_title );?></label>
															</span>
														<?php } ?>
													</div>
													<button type="submit" class="wt-searchbtn"><i class="lnr lnr-magnifier"></i> <?php esc_html_e('Search', 'workreap_core'); ?></button>
												</div>
											</div>
										</fieldset>
									</form>
								<?php } ?>
								<?php if( !empty($video_title) ) {?>
									<div class="wt-videoholder">
										<div class="wt-videoshow">
											<a  class="venobox vbox-item" data-vbtype="video" data-autoplay="true" href="<?php echo esc_url($video_url); ?>"><i class="fa fa-play"></i></a>
										</div>
										<?php if( !empty($video_title) ) {?>
											<div class="wt-videocontent">
												<span><?php echo do_shortcode($video_title);?></span>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
						<?php if( !empty($image) ) { ?>
							<div class="col-xl-4">
								<figure class="wt-homeseven-img">
									<?php if( !empty($image['url'])) { ?>
										<img src="<?php echo esc_url( $image['url']);?>" alt="<?php esc_attr_e('Banner','workreap_core');?>">
									<?php } ?>
								</figure>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php if(!empty($revoslider)){?>
					<div class="wt-slider-revo"><?php echo do_shortcode($revoslider);?></div>
				<?php }else{?>
					<div id="particles-js" class="wt-particles particles-js"></div>
				<?php }?>
			</div>
		<?php
			if( !empty ( $text_color ) ) { ?>
				<style scoped>
					.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-bannercontent .wt-title h1, 
					.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-videocontent span { color : <?php echo esc_html($text_color);?>}
				</style>
			<?php 
			}
			
			if(!empty($particles) && $particles === 'yes'){
				$script = '
				jQuery(document).on("ready", function(){
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
					})
				});
				';
				wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
			}
			
			$script = 'jQuery(document).on("ready", function(){jQuery(".venobox").venobox();});';
			wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Search_Banner_V2 ); 
}