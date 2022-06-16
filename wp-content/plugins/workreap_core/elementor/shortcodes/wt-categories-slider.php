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

if( !class_exists('Workreap_Category_Slider') ){
	class Workreap_Category_Slider extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_category_slider';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Category Slider', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-slider-full-screen';
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
					'desc' 			=> esc_html__('Select service categories to display.', 'workreap_core'),
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

			$section_heading     	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$categories          	= !empty($settings['categories']) ? $settings['categories'] : array();
			$post_type           	= !empty($settings['post_type']) ? $settings['post_type'] : 'jobs';
			$search_page	= '';
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

			if(!empty($categories)){
				$categories = get_terms( array(
					'taxonomy' 		=> $taxonomy_type,
					'hide_empty' 	=> false,
					'include'       => $categories,
				) );
			}else{
				$categories = get_terms( array(
					'taxonomy' 		=> $taxonomy_type,
					'hide_empty' 	=> false,
					'number'        => 50,
				) );
			}

			if(is_wp_error($categories)){$categories = array();}

			if( function_exists('workreap_get_search_page_uri') ){
				$search_page  = workreap_get_search_page_uri($post_type);
			}
			
			$uniq_flag  			= fw_unique_increment();
			if( is_rtl() ) {
				$rtl	= 'true';
			} else {
				$rtl	= 'false';
			}
			?>
			<div class="wt-sc-categories-freelancer">
				<div class="wt-categoriesslider-holder wt-haslayout">
					<?php if(!empty($section_heading) ) {?>
						<div class="wt-title">
							<h2><?php echo esc_html($section_heading);?>&nbsp;</h2>
						</div>
					<?php }?>
					<?php if(!empty($categories) && count($categories)>0 && apply_filters('workreap_check_plugin_activated','core') === true ) {?>
						<div id="wt-categoriesslider-<?php echo esc_attr($uniq_flag); ?>" class="wt-categoriesslider owl-carousel">
							<?php foreach( $categories as $key => $category ) { 
								if(!empty($category)){
									$icon          = array();
									$category_icon = array();
									if( function_exists( 'fw_get_db_term_option' ) ) {
										$icon          = fw_get_db_term_option($category->term_id, $taxonomy_type);
										$category_icon = !empty($icon['category_icon']) ? $icon['category_icon'] : array();
									}

									$query_arg['category[]'] = urlencode($category->slug);
									$permalink                 = add_query_arg( $query_arg, esc_url($search_page));
									?>
									<div class="wt-categoryslidercontent item">
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
										<div class="wt-cattitle">
											<h3><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_html($category->name); ?></a></h3>
											<?php if(!empty($category) ){?>
												<span><?php esc_html_e('Items','workreap_core'); ?>: <?php echo intval($category->count);?></span>
											<?php }?>
										</div>
									</div>
							<?php }}?>
						</div>
					<?php }?>
				</div>
			</div>
			<script type="application/javascript">
				jQuery(document).ready(function () {
					var _wt_categoriesslider = jQuery('#wt-categoriesslider-<?php echo esc_js($uniq_flag);?>')
					_wt_categoriesslider.owlCarousel({
						item: 6,
						loop:false,
						nav:false,
						margin: 0,
						rtl: <?php echo workreap_owl_rtl_check();?>,
						autoplay:true,
						center: false,
						responsiveClass:true,
						responsive:{
							0:{items:1,},
							481:{items:2,},
							768:{items:3,},
							1440:{items:4,},
							1760:{items:6,}
						}
					});
				});
			</script>
	<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Category_Slider ); 
}