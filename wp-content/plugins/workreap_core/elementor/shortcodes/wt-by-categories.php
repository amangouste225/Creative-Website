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

if( !class_exists('Workreap_By_Categories') ){
	class Workreap_By_Categories extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_by_categories';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Jobs by categories', 'workreap_core' );
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
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Button title', 'workreap_core' ),
					'description'   => esc_html__( 'Add button title. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Button link', 'workreap_core' ),
					'description'   => esc_html__( 'Add button link. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'version',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Select Version', 'workreap_core'),
					'options' 		=> [
										'v_1' => 'Version 1',
										'v_2' => 'Version 2',
										],
					'default' 		=> 'v1',
				]
			);
			
			$this->add_control(
				'link_target',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Link Target', 'workreap_core'),
					'desc'			=> esc_html__('Do you want to search service or jobs?', 'workreap_core'),
					'options' 		=> [
										'jobs' => esc_html__('Jobs', 'workreap_core'),
										'services' => esc_html__('Services', 'workreap_core'),
										],
					'default' 		=> 'jobs',
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

			$section_heading = !empty( $settings['section_heading'] ) ? $settings['section_heading'] : '';
			$categories		 = !empty( $settings['categories'] ) ? $settings['categories'] : array();
			$view_title		 = !empty( $settings['btn_title'] )  ? $settings['btn_title'] : '';
			$view_url		 = !empty( $settings['btn_link'] )  ? $settings['btn_link'] : '';
			$version  	     = !empty($settings['version']) ? $settings['version'] : 'v1';
			$link_target     = !empty($settings['link_target']) ? $settings['link_target'] : 'jobs';
			$service_categories     = !empty($settings['service_categories']) ? $settings['service_categories'] : array();

			if(!empty($link_target) && $link_target === 'services'){
				if (function_exists('fw_get_db_post_option') ) {
					$services_categories	= fw_get_db_settings_option('services_categories');
				}

				$services_categories	= !empty($services_categories) ? $services_categories : 'no';
				if( !empty($services_categories) && $services_categories === 'no' ) {
					$categories          = !empty($categories) ? $categories : array();
				}else{
					$categories          = !empty($service_categories) ? $service_categories : array();
				}
			}
			
			$search_page	= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$search_page  = workreap_get_search_page_uri('jobs');
				$search_page     = workreap_get_search_page_uri($link_target);
			}
			
			$class = '';
			if($version === 'v_2') {
				$class = 'wt-footeraboutus-two';
			}
			?>
			<div class="wt-sc-by-categories wt-haslayout <?php echo esc_attr($class); ?>">
				<?php if( !empty($categories) && apply_filters('workreap_check_plugin_activated','core') === true) {?>
					<div class="wt-widgetskills">
						<div class="wt-fwidgettitle">
							<h3><?php echo esc_html($section_heading);?></h3>
						</div>
						<ul class="wt-fwidgetcontent">
							<?php foreach( $categories as $category ) { 
									$category      = get_term($category);
									if(!empty($category->slug)){
									$query_arg['category[]'] 	= urlencode($category->slug);
									$url                 		= add_query_arg( $query_arg, esc_url($search_page));
							?>
							<li><a href="<?php echo esc_url($url);?>"><?php echo esc_html($category->name);?></a></li>
							<?php }}?>
							<?php if( !empty($view_title) ) {?>
								<li class="wt-viewmore"><a href="<?php echo esc_url($view_url);?>"><?php echo esc_html($view_title);?></a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</div>
		<?php 
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_By_Categories ); 
}