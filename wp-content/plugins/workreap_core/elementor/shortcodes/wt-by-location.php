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

if( !class_exists('Workreap_Location') ){
	class Workreap_Location extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_by_location';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Jobs by locations', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-google-maps';
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
			$locations	= elementor_get_taxonomies('projects','locations');
			$locations	= !empty($locations) ? $locations : array();
			
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
				'location',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Location?', 'workreap_core'),
					'desc' 			=> esc_html__('Select location to display.', 'workreap_core'),
					'options'   	=> $locations,
					'multiple' 		=> true,
					'label_block' 	=> true,
				]
			);
			
			$this->add_control(
				'link_target',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Link Target', 'workreap_core'),
					'desc'			=> esc_html__('Do you want to search freelancers or jobs?', 'workreap_core'),
					'options' 		=> [
										'jobs' => esc_html__('Jobs', 'workreap_core'),
										'freelancer' => esc_html__('Freelancer', 'workreap_core'),
										'services' => esc_html__('Services', 'workreap_core'),
										],
					'default' 		=> 'jobs',
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
			$locations				= !empty($settings['location']) ? $settings['location'] : array();
			$view_title				= !empty( $settings['btn_title'] )  ? $settings['btn_title'] : '';
			$view_url				= !empty( $settings['btn_link'] )  ? $settings['btn_link'] : '';
			$search_page	= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$link_target     = !empty($settings['link_target']) ? $settings['link_target'] : 'jobs';
				$search_page     = workreap_get_search_page_uri($link_target);
			}
			?>
			<div class="wt-sc-by-location wt-haslayout">
				<?php if( !empty($locations) && apply_filters('workreap_check_plugin_activated','core') === true ) {?>
					<div class="wt-widgetskills">
						<div class="wt-fwidgettitle">
							<h3><?php echo esc_html($section_heading);?></h3>
						</div>
						<ul class="wt-fwidgetcontent">
							<?php foreach( $locations as $location ) { 
									$location      = get_term($location);
									if(!empty($location->slug)){
									$query_arg['location[]'] 	= urlencode($location->slug);
									$url                 		= add_query_arg( $query_arg, esc_url($search_page));?>
									<li><a href="<?php echo esc_url($url);?>"><?php echo esc_html($location->name);?></a></li>
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

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Location ); 
}