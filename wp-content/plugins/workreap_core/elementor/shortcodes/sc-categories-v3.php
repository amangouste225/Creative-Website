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

if( !class_exists('Workreap_Caregories_V3') ){
	class Workreap_Caregories_V3 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_categories_v3';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Explore Categories V3', 'workreap_core' );
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
				'section_desc',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap_core' ),
					'description'   => esc_html__( 'Add section description. Leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add button title. Leave it empty to hide button.', 'workreap_core'),
				]
			);

			$this->add_control(
				'explore_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add explore title, which will be displayed above show all button. Leave it empty to hide button.', 'workreap_core'),
				]
			);

			$this->add_control(
				'explore_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore Link', 'workreap_core'),
        			'description' 	=> esc_html__('Add explore link, default will be #', 'workreap_core'),
				]
			);

			$this->add_control(
				'explore_desc',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Explore Description', 'workreap_core'),
        			'description' 	=> esc_html__('Add explore description, which will be displayed above show all button. Leave it empty to hide button.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Link', 'workreap_core'),
        			'description' 	=> esc_html__('Add button link. Leave it empty to hide.', 'workreap_core'),
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
					'label'			=> esc_html__('Project Categories?', 'workreap_core'),
					'desc' 			=> esc_html__('Select categories to display.', 'workreap_core'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
				]
			);
			
			$this->add_control(
				'service_categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Service Categories?', 'workreap_core'),
					'desc' 			=> esc_html__('Select service categories to display. It will override above selected categories', 'workreap_core'),
					'options'   	=> $service_categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
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
			$section_desc 		= !empty($settings['section_desc']) ? $settings['section_desc'] : '';
			$btn_title          = !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link           = !empty($settings['btn_link']) ? $settings['btn_link'] : '#';
			$explore_title      = !empty($settings['explore_title']) ? $settings['explore_title'] : '';
			$explore_link       = !empty($settings['explore_link']) ? $settings['explore_link'] : '#';
			$explore_desc       = !empty($settings['explore_desc']) ? $settings['explore_desc'] : '';
			$post_type          = !empty($settings['post_type']) ? $settings['post_type'] : 'jobs';
			$categories         = !empty($settings['categories']) ? $settings['categories'] : array();
			$service_categories          	= !empty($settings['service_categories']) ? $settings['service_categories'] : array();
			
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
			
			$search_page	= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$search_page  = workreap_get_search_page_uri($post_type);
			}
			?>
			<div class="wt-sc-explore-categories-v3 wt-haslayout wt-categoriestwo-wrap">
				<div class="row justify-content-center">
					<?php if (!empty($section_heading) || !empty($section_desc)) { ?>
						<div class="col-12 col-lg-8">
							<div class="wt-sectionheadvtwo wt-textcenter">
								<?php if (!empty($section_heading)) { ?>
									<div class="wt-sectiontitlevtwo">
										<h2><?php echo do_shortcode($section_heading); ?></h2>
									</div>
								<?php } ?>
								<?php if (!empty($section_desc)) { ?>
									<div class="wt-description">
										<?php echo wpautop(do_shortcode($section_desc)); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $categories )  ) { ?>
						<div class="col-12">
							<ul class="wt-categoryvtwo wt-categoryvthree">
								<?php foreach( $categories as $key => $cat_id ) { 
									$category      = get_term($cat_id);
									$icon          = array();
									$query_arg     = array();
									$category_icon = array();
									if( function_exists( 'fw_get_db_term_option' ) ) {
										$icon          = fw_get_db_term_option($cat_id, $taxonomy_type);
										$category_icon = !empty($icon['category_icon']) ? $icon['category_icon'] : array();
									}
					
									$query_arg['category[]']   = urlencode($category->slug);
									$permalink                 = add_query_arg( $query_arg, esc_url($search_page));
									?>
									<li>
										<div class="wt-categorycontentvtwo">
											<?php
												if (!empty($category_icon) && $category_icon['type'] === 'icon-font') {
													do_action('enqueue_unyson_icon_css');
													if (!empty($category_icon['icon-class'])) {?>
														<figure><i class="<?php echo esc_attr($category_icon['icon-class']); ?>"></i></figure>
													<?php
													}
												} elseif (!empty($category_icon['type']) && $category_icon['type'] === 'custom-upload') {
													if (!empty($category_icon['url'])) {?>
														<figure><img src="<?php echo esc_url($category_icon['url']); ?>" alt="<?php esc_attr_e('Category','workreap_core'); ?>"></figure>
														<?php
													}
												}
											?>
											<div class="wt-cattitlevtwo">
												<h4><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_html($category->name); ?></a></h4>
											</div>
											<?php if( !empty( $category->description ) ) { ?>
												<div class="wt-description">
													<p><?php echo esc_html($category->description); ?></p>
												</div>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
								<?php if(!empty($btn_title) || !empty($explore_title) || !empty($explore_desc)) { ?>
									<li class="wt-morecategory">
										<div class="wt-categorycontentvtwo">
											<?php if(!empty($explore_title)) { ?>
												<div class="wt-cattitlevtwo">
													<h4><a href="<?php echo esc_url($explore_link); ?>"><?php echo esc_html($explore_title); ?></a></h4>
												</div>
											<?php } ?>
											<?php if(!empty($explore_desc)) { ?>
												<div class="wt-description">
													<p><?php echo esc_html($explore_desc); ?></p>
												</div>
											<?php } ?>
											<?php if( $btn_title ) { ?>
											<div class="wt-btnarea">
												<a href="<?php echo esc_url( $btn_link ); ?>" class="wt-btntwo"><?php echo esc_html( $btn_title ); ?></a>
											</div>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Caregories_V3 ); 
}