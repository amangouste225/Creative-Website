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

if( !class_exists('Workreap_APPS') ){
	class Workreap_APPS extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_apps';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Mobile APPS', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-device-mobile';
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
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add newsletter title. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Sub title', 'workreap_core'),
        			'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap_core'),
        			'description' 	=> esc_html__('Add newsletter description. leave it empty to hide.', 'workreap_core'),
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
				'google_image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Google Play store image', 'workreap_core' ),
					'description'   => esc_html__( 'Add Google Play store image.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'button_text',
				[
					'label' => esc_html__( 'Add button text', 'workreap_core' ),
					'type'  => Controls_Manager::TEXT,
					'description'   => esc_html__( 'Add text to display button, it will override logo image and display simple button', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'play_store_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Google Play store link', 'workreap_core'),
        			'description' 	=> esc_html__('Add Google Play store link. Leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'app_store_image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'App store image', 'workreap_core' ),
					'description'   => esc_html__( 'Add App store image.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'button_text_ios',
				[
					'label' => esc_html__( 'Add button text', 'workreap_core' ),
					'type'  => Controls_Manager::TEXT,
					'description'   => esc_html__( 'Add text to display button, it will override logo image and display simple button', 'workreap_core' ),
				]
			);
			$this->add_control(
				'app_store_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('App store link', 'workreap_core'),
        			'description' 	=> esc_html__('Add App store link. Leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Image', 'workreap_core' ),
					'description'   => esc_html__( 'Upload image. leave it empty to hide.', 'workreap_core' ),
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

			$title       = !empty($settings['title']) ? $settings['title'] : '';
			$sub_title   = !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	     = !empty($settings['description']) ? $settings['description'] : '';
			$google_image  	    = !empty($settings['google_image']['url']) ? $settings['google_image']['url'] : '';
			$app_store_image    = !empty($settings['app_store_image']['url']) ? $settings['app_store_image']['url'] : '';
			$app_store_url   	= !empty($settings['app_store_url']) ? $settings['app_store_url'] : '';
			$play_store_url   	= !empty($settings['play_store_url']) ? $settings['play_store_url'] : '';
			$image  	    	= !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$text_color			= !empty($settings['text_color']) ? $settings['text_color'] : '';
			
			$button_text   		= !empty($settings['button_text']) ? $settings['button_text'] : '';
			$button_text_ios  	= !empty($settings['button_text_ios']) ? $settings['button_text_ios'] : '';
			
			$button_text_class   		= !empty($settings['button_text']) ? 'wt-btn' : '';
			$button_text_ios_class 		= !empty($settings['button_text_ios']) ? 'wt-btn' : '';
			
			$flag 				= rand(9999, 999999);
			?>
			<div class="wt-sc-mobile-apps wt-haslayout wt-nativeholder dynamic-secton-<?php echo esc_attr( $flag );?>">
				<div class="row justify-content-center align-self-center">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
						<div class="wt-sectionhead wt-textcenter wt-howswork">
							<?php if( !empty( $title ) || !empty( $sub_title ) ) {?>
								<div class="wt-sectiontitle">
									<?php if( !empty( $title ) ) {?><h2><?php echo esc_html( $title );?></h2><?php }?>
									<?php if( !empty( $sub_title ) ) {?><h3><?php echo esc_html( $sub_title );?></h3><?php }?>
								</div>
							<?php }?>
							<?php if( !empty( $desc ) ){?>
								<div class="wt-description"><?php echo do_shortcode( $desc );?></div>
							<?php }?>
							<?php if( ( !empty( $google_image ) && !empty( $play_store_url ) ) || ( !empty( $app_store_image ) && !empty( $app_store_url ) ) ) {?>
								<ul class="wt-appicons">
									<?php if( !empty( $google_image ) && !empty( $play_store_url ) ) {?>
										<li><a class="<?php echo esc_attr($button_text_class);?>" href="<?php echo esc_url( $play_store_url );?>"><?php if(!empty($button_text)){ echo esc_attr($button_text);}else{?><img src="<?php echo esc_url( $google_image );?>" alt="<?php esc_attr_e('Play store','workreap_core');?>"><?php }?></a></li>
									<?php }?>
									<?php if( !empty( $app_store_image ) && !empty( $app_store_url ) ) {?>
										<li><a class="<?php echo esc_attr($button_text_ios_class);?>" href="<?php echo esc_url( $app_store_url );?>"><?php if(!empty($button_text_ios)){ echo esc_attr($button_text_ios);}else{?><img src="<?php echo esc_url( $app_store_image );?>" alt="<?php esc_attr_e('App store','workreap_core');?>"><?php }?></a></li>
									<?php }?>
								</ul>
							<?php }?>
						</div>
					</div>
					<?php if( !empty( $image ) ){?>
						<div class="d-none d-lg-block col-lg-12">
							<div class="wt-nativemobile">
								<figure><img src="<?php echo esc_url( $image );?>" alt="<?php esc_attr_e('Mobile Apps','workreap_core');?>"></figure>										
							</div>
						</div>
					<?php }?>
				</div>
			</div>
			<?php 
				if( !empty ( $text_color ) ) {?>
					<style scoped>
						.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-howswork .wt-sectiontitle h2, 
						.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-howswork .wt-sectiontitle h3,
						.dynamic-secton-<?php echo esc_attr( $flag );?> .wt-howswork .wt-description p{ color : <?php echo esc_html($text_color);?>}
					</style>
				<?php 
				}	
			}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_APPS ); 
}