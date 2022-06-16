<?php
/**
 * Shortcode for categories v3
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

if( !class_exists('Workreap_Popular_Services') ){
	class Workreap_Popular_Services extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_poupular_services';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Explore popular services', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-product-categories';
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
			
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap_core' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'section_heading',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Heading', 'workreap_core' ),
					'description'   => esc_html__( 'Add section heading. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'post_type',
				[
					'label' => esc_html__( 'Post Type?', 'workreap_core' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'services',
					'options' => [
						'jobs' => esc_html__('Jobs', 'workreap_core'),
						'services' => esc_html__('Services', 'workreap_core'),
					],
				]
			);
			
			$this->add_control(
				'service_categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Service categories?', 'workreap_core'),
					'desc' 			=> esc_html__('Select service categories to display.', 'workreap_core'),
					'options'   	=> $service_categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
				]
			);
			
			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Project Categories?', 'workreap_core'),
					'desc' 			=> esc_html__('Select categories to display.', 'workreap_core'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
				]
			);

			$this->add_control(
				'version',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Select version','workreap_core' ),
					'description'   => esc_html__('Select version', 'workreap_core' ),
					'default' 		=> 'v1',
					'options' 		=> [
										'v1' => esc_html__('V1', 'workreap_core'),
										'v2' => esc_html__('V2', 'workreap_core'),
										],
				]
			);

			$this->add_control(
				'btn_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore button text', 'workreap_core'),
					'rows' 			=> 5,
					'description' 	=> esc_html__('Add text. leave it empty to hide.', 'workreap_core'),
					'condition'		=> [
						'version'	=> 'v2'
					]
				]
			);
			$this->add_control(
				'btn_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore button URL', 'workreap_core'),
					'description' 	=> esc_html__('Add url. leave it empty to hide.', 'workreap_core'),
					'condition'		=> [
						'version'	=> 'v2'
					]
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

			$section_heading	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$service_categories = !empty($settings['service_categories']) ? $settings['service_categories'] : array();
			$categories         = !empty($settings['categories']) ? $settings['categories'] : array();
			$version			= !empty($settings['version']) ? $settings['version'] : 'v1';
			$post_type          = !empty($settings['post_type']) ? $settings['post_type'] : 'jobs';
			
			$flag 				= rand(9999, 999999);
			
			$search_page	= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$search_page  = workreap_get_search_page_uri($post_type);
			}
			
			$taxonomy_type	= 'project_cat';
			if(!empty($post_type) && $post_type === 'services'){
				if (function_exists('fw_get_db_post_option') ) {
					$services_categories	= fw_get_db_settings_option('services_categories');
				}

				$services_categories	= !empty($services_categories) ? $services_categories : 'no';
				if( !empty($services_categories) && $services_categories === 'no' ) {
					$taxonomy_type	= 'project_cat';
				}else{
					$taxonomy_type	= 'service_categories';
					$categories     = !empty($service_categories) ? $service_categories : array();
				}
			}

			if( !empty($version) && $version == 'v1' ) { ?>
				<div class="wt-popularservice-section">
					<div class="row">
						<div class="col-12">
							<?php if( !empty($section_heading) ){?>
								<div class="wt-sectionhead wt-sectionheadvfour">
									<div class="wt-sectiontitle wt-sectiontitlevthree">
										<h2><?php echo esc_html($section_heading);?></h2>
									</div>
								</div>
							<?php } ?>
							<?php if( !empty($categories) ){?>
								<div id="wt-ourservices-<?php echo intval($flag);?>" class="wt-ourservices owl-carousel">
									<?php 
										foreach( $categories as $key => $cat_id ) { 
											$icon          = array();
											$query_arg     = array();
											$category_icon = array();
											
											if( function_exists( 'fw_get_db_term_option' ) ) {
												$icon          = fw_get_db_term_option($cat_id, $taxonomy_type);
												$category_icon = !empty($icon['category_image']) ? $icon['category_image'] : array();
											}
											
											$term_data		= get_term($cat_id,$taxonomy_type);
											$count			= !empty($term_data->count) ? intval($term_data->count) : 0;
											$term_name		= !empty($term_data->name) ? $term_data->name : '';
											
											$query_arg['category[]']   = urlencode($term_data->slug);
											$permalink                 = add_query_arg( $query_arg, esc_url($search_page));
											
											if (!empty($category_icon['url'])) { ?>
											<figure class="wt-ourservices__item">
												<img src="<?php echo esc_url($category_icon['url']);?>" alt="<?php echo esc_attr($term_name);?>">
												<?php if( !empty($term_name) || !empty($count) ){?>
													<figcaption>
														<h3><a href="<?php echo esc_url($permalink);?>"><?php echo esc_html($term_name);?></a></h3>
														<span><a href="<?php echo esc_url($permalink);?>"><?php echo sprintf(esc_html__('%s Listings','workreap_core'),$count);?></a></span>
													</figcaption>
												<?php } ?>
											</figure>
										<?php } ?>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<script>
					jQuery(document).ready(function () {
						var _wt_ourservices = jQuery("#wt-ourservices-<?php echo esc_js($flag);?>");
						_wt_ourservices.owlCarousel({
						items: 4,
						rtl: <?php echo workreap_owl_rtl_check();?>,
						loop: true,
						nav: true,
						autoplay: false,
						dots: false,
						margin: 30,
						smartSpeed: 500,
						responsiveClass: true,
						navClass: ["ti-prev", "ti-next"],
						navContainerClass: "ti-slidernav",
						navText: [
							'<span class="ti-angle-left"></span>',
							'<span class="ti-angle-right"></span>',
						],
						responsive:{
						0:{
							items:1,
						},
						480:{
							items:2,
						},
						767:{
							items:3,
						},
						991:{
							items:4,
						},
						}
						});
					});
				</script>
			<?php } elseif ( !empty($version) && $version == 'v2' ) {
				$btn_text			= !empty($settings['btn_text']) ? $settings['btn_text'] : '';
				$btn_url			= !empty($settings['btn_url']) ? $settings['btn_url'] : ''; ?>
				<div class="row">
                    <div class="col-12">
						<?php if( !empty($section_heading) ){?>
							<div class="wt-sectionhead wt-sectionheadvfour">
								<div class="wt-sectiontitle wt-sectiontitlevthree">
									<h2><?php echo esc_html($section_heading);?></h2>
								</div>
							</div>
						<?php } ?>
						<?php if( !empty($service_categories) ){?>
							<div class="wt-categorieslist">
								<ul>
									<?php
										foreach( $categories as $key => $cat_id ) { 
											$icon          = array();
											$query_arg     = array();
											$category_icon = array();
											
											if( function_exists( 'fw_get_db_term_option' ) ) {
												$icon          = fw_get_db_term_option($cat_id, $taxonomy_type);
												$category_icon = !empty($icon['category_icon']) ? $icon['category_icon'] : array();
											}
											
											$term_data		= get_term($cat_id,$taxonomy_type);
											$count			= !empty($term_data->count) ? intval($term_data->count) : 0;
											$term_name		= !empty($term_data->name) ? $term_data->name : '';
											
											$query_arg['category[]']   = urlencode($term_data->slug);
											$permalink                 = add_query_arg( $query_arg, esc_url($search_page));
											$description	= !empty($term_data->description) ? $term_data->description : "";
										?>
										<li>
											<div class="wt-categories">
												<?php
													if (!empty($category_icon['type']) && $category_icon['type'] === 'icon-font') {
														do_action('enqueue_unyson_icon_css');
														if (!empty($category_icon['icon-class'])) {?>
															<span><i class="<?php echo esc_attr($category_icon['icon-class']); ?>"></i></span>
														<?php
														}
													} elseif (!empty($category_icon['type']) && $category_icon['type'] === 'custom-upload') {
														if (!empty($category_icon['url'])) {?>
																<span><figure><img src="<?php echo esc_url($category_icon['url']); ?>" alt="<?php esc_attr_e('Category','workreap_core'); ?>"></figure></span>
															<?php
														}
													}
												?>
												<?php if( !empty($term_name) ){?>
													<h3>
														<a href="<?php echo esc_url($permalink);?>"><?php echo esc_html($term_name);?></a>
														<span><?php echo sprintf(esc_html__('%s Listings','workreap_core'),$count);?></span>
													</h3>
												<?php } ?>
												<?php if( !empty($description) ){?>
													<p><?php echo esc_html($description);?></p>
												<?php } ?>
												<a href="<?php echo esc_url($permalink);?>" class="wt-btn wt-btnv2"><?php esc_html_e('Explore','workreap_core');?> <i class="ti-arrow-right"></i></a>
											</div>
										</li>
									<?php } ?>
								</ul>
								<?php if( !empty($btn_text) ){?>
									<div class="wt-sectionbtn">
										<a href="<?php echo esc_url($btn_url);?>" class="wt-btn wt-btnv2"><?php echo esc_html($btn_text);?></a>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
                    </div>
                </div>
			<?php }
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Popular_Services ); 
}