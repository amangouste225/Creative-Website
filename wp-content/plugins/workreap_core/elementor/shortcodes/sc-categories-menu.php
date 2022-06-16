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

if( !class_exists('Workreap_Categories_Menu') ){
	class Workreap_Categories_Menu extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_categories_menu';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Categories Menu', 'workreap_core' );
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
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap_core' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'background_color',
				[
					'type'      	=> Controls_Manager::COLOR,
					'label'     	=> esc_html__( 'Background color', 'workreap_core' ),
					'description'   => esc_html__( 'Add background color. leave it empty to use default color.', 'workreap_core' ),
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
			$background_color     	= !empty($settings['background_color']) ? $settings['background_color'] : 'var(--primthemecolor)';
			$text_color     		= !empty($settings['text_color']) ? $settings['text_color'] : '#FFF';
			
			$location	= 'categories-menu';
			?>
			<div class="wt-categoriesnav-holder wt-cat-menu-wrap">
				<div class="container-fluid">
					<div class="row">
						<nav class="wt-categories-nav navbar-expand-lg">
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavbar" aria-controls="navbarNavbar" aria-expanded="false" aria-label="Toggle navigation">
								<i class="lnr lnr-menu"></i>
							</button>
							<div class="wt-categories-navbar wt-navigation navbar-collapse collapse" id="navbarNavbar">
								<?php
									if (has_nav_menu($location)) {
										$defaults = array(
											'theme_location' => "$location",
											'menu' => '',
											'container' => 'ul',
											'container_class' => '',
											'container_id' => '',
											'menu_class' => "",
											'menu_id' => "",
											'echo' => false,
											'fallback_cb' => 'wp_page_menu',
											'before' => '',
											'after' => '',
											'link_before' => '',
											'link_after' => '',
											'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
											'depth' => 0,
										);
										echo do_shortcode(wp_nav_menu($defaults));
									} 
								?>
								<style scoped>
									.wt-cat-menu-wrap .wt-categories-nav{background:<?php echo esc_attr($background_color);?>;}
									.wt-cat-menu-wrap .wt-categories-nav > ul > li > a{color:<?php echo esc_attr($text_color);?>;}
									.wt-cat-menu-wrap .wt-navigation>ul>li>.sub-menu{border-color:<?php echo esc_attr($background_color);?>;}
								</style>
							</div>
						</nav>
					</div>
				</div>
			</div>
			<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Categories_Menu ); 
}