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

if( !class_exists('Workreap_Home_Slider_V7') ){
	class Workreap_Home_Slider_V7 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_slider_v7';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Search Banner V7', 'workreap_core' );
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
			
			if (function_exists('fw_get_db_post_option') ) {
				$services_categories	= fw_get_db_settings_option('services_categories');
			}
			
			$service_categories		= array();
			$services_categories	= !empty($services_categories) ? $services_categories : 'no';

			$categories	= elementor_get_taxonomies('projects', 'project_cat', 0);
			$categories	= !empty($categories) ? $categories : array();
			
			if( !empty($services_categories) && $services_categories === 'no' ) {
				$categories	= !empty($categories) ? $categories : array();
			}else{
				$service_categories	= elementor_get_taxonomies('micro-services', 'service_categories', 0);
				$service_categories	= !empty($service_categories) ? $service_categories : array();
			}
			
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap_core' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'link_target',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Select Desgin type', 'workreap_core'),
					'desc'			=> esc_html__('Do you want to list by categories or Services ids?', 'workreap_core'),
					'options' 		=> [
										'v1' => esc_html__('V1', 'workreap_core'),
										'v2' => esc_html__('V2', 'workreap_core'),
										],
					'default' 		=> 'v1',
				]
			);
			$this->add_control(
				'top_tag_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Top tag text', 'workreap_core' ),
					'description' 	=> esc_html__('Add top text or leave it empty to hide.', 'workreap_core'),
					'condition' => [
						'link_target' => 'v1',
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
				'top_sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Top sub Title', 'workreap_core' ),
					'description' 	=> esc_html__('Add top sub title or leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'top_description',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__( 'Add description', 'workreap_core' ),
					'description' 	=> esc_html__('Add description or leave it empty to hide.', 'workreap_core'),
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
				'cat_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Popular categories title', 'workreap_core' ),
					'description' 	=> esc_html__('Add text or leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'post_type',
				[
					'label' => esc_html__( 'Post Type?', 'workreap_core' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'jobs',
					'options' => [
						'jobs' => esc_html__('Jobs', 'workreap_core'),
						'services' => esc_html__('Services', 'workreap_core'),
					],
				]
			);
			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Categories?', 'workreap_core'),
					'desc' 			=> esc_html__('Select categories to display.', 'workreap_core'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true
				]
			);
			$this->add_control(
				'service_categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Service Categories?', 'workreap_core'),
					'desc' 			=> esc_html__('Select service categories to display.', 'workreap_core'),
					'options'   	=> $service_categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
				]
			);
			
			$this->add_control(
				'search_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Search related text', 'workreap_core' ),
					'description' 	=> esc_html__('Add search related text or leave it empty to hide.', 'workreap_core'),
					'condition' => [
						'link_target' => 'v1',
					],
				]
			);
			$this->add_control(
				'image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label' 		=> esc_html__('Upload Image', 'workreap_core' ),
					'description' 	=> esc_html__('Upload Image or leave it empty to hide.', 'workreap_core'),
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
			$list_names	= '';
			if( function_exists('worktic_get_search_list') ){
				$list_names	= worktic_get_search_list('yes');
			}
			
			$settings 				= $this->get_settings_for_display();
			$top_tag_title			= !empty($settings['top_tag_title']) ? $settings['top_tag_title'] : '';
			$top_title				= !empty($settings['top_title']) ? $settings['top_title'] : '';
			$top_sub_title			= !empty($settings['top_sub_title']) ? $settings['top_sub_title'] : '';
			$top_description		= !empty($settings['top_description']) ? $settings['top_description'] : '';
			$revoslider				= !empty($settings['revoslider']) ? $settings['revoslider'] : '';
			$cat_title				= !empty($settings['cat_title']) ? $settings['cat_title'] : '';
			$categories				= !empty($settings['categories']) ? $settings['categories'] : array();
			$search_text			= !empty($settings['search_text']) ? $settings['search_text'] : '';
			$image					= !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$post_type          	= !empty($settings['post_type']) ? $settings['post_type'] : 'jobs';
			$service_categories     = !empty($settings['service_categories']) ? $settings['service_categories'] : array();
			
			$search_form		= !empty($settings['search_form']) ? $settings['search_form'] : '';
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
			
			$default_url			= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$default_url	= !empty($default_key) ? workreap_get_search_page_uri($default_key) : '';
			}
			
			$link_target		= !empty($settings['link_target']) ? $settings['link_target'] : '';
			$flag 				= rand(9999, 999999);
			
			$categories_list	= $categories;
			$categories_term	= 'project_cat';
			if(!empty($post_type) && $post_type === 'services'){
				if (function_exists('fw_get_db_post_option') ) {
					$services_categories	= fw_get_db_settings_option('services_categories');
				}

				$services_categories	= !empty($services_categories) ? $services_categories : 'no';
				if( !empty($services_categories) && $services_categories === 'no' ) {
					$categories_list	= $service_categories;
				}else{
					$categories_term	= 'service_categories';
					$categories_list	= $service_categories;
				}
			}
			
			if( !empty($link_target) && $link_target == 'v1' ){ ?>
			<div class="wt-homebanner-wrap wt-homebanner-transparent">
				<div class="container">
					<div class="row align-items-center justify-content-between">
						<div class="col-lg-10 col-xl-8">
							<div class="wt-homebannerv2">
								<div class="wt-homebanner__title">
									<?php if( !empty($top_tag_title) ){?>
										<span class="wt-hometitletag"><?php echo esc_html($top_tag_title);?></span>
									<?php } ?>
									<?php if( !empty($top_title) || !empty($top_sub_title) ){?>
										<h1>
											<?php if( !empty($top_title) ){?>
												<span><?php echo esc_html($top_title);?></span>
											<?php } ?>
											<?php echo esc_html($top_sub_title);?>
										</h1>
									<?php } ?>
									<?php if( !empty($top_description) ) {?>
										<p><?php echo esc_html($top_description);?></p>
									<?php } ?>
								</div>
								<?php if(!empty($search_form) && $search_form === 'yes') { ?>
									<form class="wt-formtheme wt-formbanner search-form" action="<?php echo esc_url($defult_url);?>" method="get">
										<fieldset>
											<div class="form-group">
												<input name="keyword" type="text" class="form-control" placeholder="<?php esc_attr_e('I’m looking for', 'workreap_core'); ?>">
												<div class="wt-formoptions">
													<?php if( !empty($list_names[$defult_key]) ) { ?>
														<div class="wt-dropdown">
															<span><em class="selected-search-type"><?php echo esc_html( $list_names[$defult_key] );?></em><i class="lnr lnr-chevron-down"></i></span>
														</div>
													<?php } ?>
													<div class="wt-radioholder">
														<?php foreach( $searchs as $search ) { 
															$action_url		= '';
															if( function_exists('workreap_get_search_page_uri') ){
																$action_url		= workreap_get_search_page_uri($search);
															}
				
															$search_title	= !empty( $list_names[$search] ) ? $list_names[$search] : '';
															$checked		= '';
				
															if( !empty($list_names[$defult_key]) && $list_names[$defult_key] == $search_title ){
																$checked	= 'checked';
															}
				
															$flag_key 	= rand(9999, 999999);
															?> 
															<span class="wt-radio">
																<input id="wt-<?php echo esc_attr( $flag_key );?>" data-url="<?php echo esc_url($action_url);?>" data-title="<?php echo esc_attr( $search_title );?>" type="radio" name="searchtype" value="<?php echo esc_attr($search);?>" <?php echo esc_attr($checked);?>>
																<label for="wt-<?php echo esc_attr($flag_key); ?>"><?php echo esc_html($search_title); ?></label>
															</span>
														<?php } ?>
													</div>
													<button type="submit" class="wt-btn wt-btnv2 search-form-submit"><i class="lnr lnr-magnifier"></i> <?php esc_html_e('Search','workreap_core');?></button>
												</div>
											</div>
										</fieldset>
									</form>
								<?php } ?>
								<?php if( !empty($cat_title) || !empty($categories) ){?>
									<div class="wt-populartags">
										<?php if( !empty($cat_title) ){?>
											<span><?php echo esc_html($cat_title);?>:</span>
										<?php } ?>
										<?php
											if( !empty($categories_list) ){ 
												foreach($categories_list as $category ){
													$term_data 	= get_term( $category, $categories_term );
													$term_url	= !empty($term_data) ? get_term_link($term_data) : '';
													if( !empty($term_data->name) && !is_wp_error( $term_url ) ){ ?>
														<a href="<?php echo esc_url($term_url);?>"><?php echo esc_html($term_data->name);?></a>
													<?php }
												}
											}
										?>
									</div>
								<?php } ?>
								<?php if( !empty($search_text) ){?>
									<div class="wt-formfocus">
										<span><?php echo esc_html($search_text);?></span>
									</div>
								<?php } ?>
							</div>
						</div>
						<?php if( !empty($image) ){?>
							<div class="col-12 col-lg-4">
								<figure class="wt-bannerimgv2">
									<img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($top_title);?>">
								</figure>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php if(!empty($revoslider)){?>
					<div class="wt-slider-revo"><?php echo do_shortcode($revoslider);?></div>
				<?php }?>
			</div>			
			<?php } else if( !empty($link_target) && $link_target == 'v2' ){ 
				$particles	= !empty($settings['particles']) ? $settings['particles'] : '';?>
				<div class="wt-homebanner-wrapv2 wt-homebanner-transparent">
					<div class="theme-container theme-containerv3">
						<div class="row align-items-center justify-content-between">
							<div class="col-md-10 col-lg-9 col-xl-8">
								<div class="wt-homebannerv2 wt-homebannerv3">
									<?php if( !empty($top_title) || !empty($top_sub_title) || !empty($top_description) ){?>
										<div class="wt-homebanner__title">
											<?php if( !empty($top_title) || !empty($top_sub_title) ){?>
												<h1><span><?php echo esc_html($top_title);?></span><?php echo do_shortcode( $top_sub_title );?></h1>
											<?php } ?>
											<?php if( !empty($top_description) ){?>
												<p><?php echo esc_html($top_description);?></p>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if(!empty($search_form) && $search_form === 'yes') { ?>
										<form class="wt-formtheme wt-formbanner search-form" action="<?php echo esc_url($defult_url);?>" method="get">
											<fieldset>
												<div class="form-group">
													<input type="text" name="keyword" class="form-control" placeholder="<?php esc_attr_e('What you’re looking for?', 'workreap_core'); ?>">
													
													<div class="wt-formoptions">
														<?php if( !empty($list_names[$defult_key]) ) { ?>
															<div class="wt-dropdown">
																<span><em class="selected-search-type"><?php echo esc_html( $list_names[$defult_key] );?></em><i class="lnr lnr-chevron-down"></i></span>
															</div>
														<?php } ?>
														<div class="wt-radioholder">
															<?php foreach( $searchs as $search ) { 
																$action_url		= '';
																if( function_exists('workreap_get_search_page_uri') ){
																	$action_url		= workreap_get_search_page_uri($search);
																}
					
																$search_title	= !empty( $list_names[$search] ) ? $list_names[$search] : '';
																$checked		= '';
					
																if( !empty($list_names[$defult_key]) && $list_names[$defult_key] == $search_title ){
																	$checked	= 'checked';
																}
													
																$flag_key 	= rand(9999, 999999);
																?> 
																<span class="wt-radio">
																	<input id="wt-<?php echo esc_attr( $flag_key );?>" data-url="<?php echo esc_url($action_url);?>" data-title="<?php echo esc_attr( $search_title );?>" type="radio" name="searchtype" value="<?php echo esc_attr($search);?>" <?php echo esc_attr($checked);?>>
																	<label for="wt-<?php echo esc_attr($flag_key); ?>"><?php echo esc_html($search_title); ?></label>
																</span>
															<?php } ?>
														</div>
														<a href="javascript:void(0);" class="wt-btnthree search-form-submit"><?php esc_html_e('Search Now','workreap_core');?></a>
													</div>
												</div>
											</fieldset>
										</form>
									<?php } ?>
										<?php if( !empty($cat_title) || !empty($categories) ){?>
										<div class="wt-populartags wt-populartagsv2">
											<?php if( !empty($cat_title) ){?>
												<span><?php echo esc_html($cat_title);?></span>
											<?php } ?>
											<?php
												if( !empty($categories_list) ){ 
													foreach($categories_list as $category ){
														$term_data 	= get_term( $category, $categories_term );
														$term_url	= !empty($term_data) ? get_term_link($term_data) : '';
														if( !empty($term_data->name) && !is_wp_error( $term_url ) ){ ?>
															<a href="<?php echo esc_url($term_url);?>"><?php echo esc_html($term_data->name);?></a>
														<?php }
													}
												}
											?>
										</div>
									<?php } ?>
								</div>
							</div>
							<?php if( !empty($image) ){?>
								<div class="col-12 col-lg-4">
									<div class="wt-headerimgs">
										<figure>
											<img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($top_title);?>">
										</figure>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php 
				} 
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Home_Slider_V7 ); 
}