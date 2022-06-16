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

if( !class_exists('Workreap_Awards') ){
	class Workreap_Awards extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_awards';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Award winning platform', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-lock-user';
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
				'image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label' 		=> esc_html__('Image Award section', 'workreap_core'),
        			'description' 	=> esc_html__('Add Award section image.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'video_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Video URL Award section', 'workreap_core'),
        			'description' 	=> esc_html__('Add video URL', 'workreap_core'),
				]
			);

			$this->add_control(
				'award_side_img',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label' 		=> esc_html__('Image Award', 'workreap_core'),
        			'description' 	=> esc_html__('Add Award image.', 'workreap_core'),
				]
			);
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Title Award section', 'workreap_core'),
        			'description' 	=> esc_html__('Add title. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Sub Title Award section', 'workreap_core'),
        			'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'services',
				[
					'label'  => esc_html__( 'Add services', 'workreap_core' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name' 			=> 'icon_class',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Icon class', 'workreap_core' ),
							'description'   => esc_html__( 'Icon class.', 'workreap_core' ),
						],
						[
							'name' 			=> 'title',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Add Heading', 'workreap_core' ),
							'description'   => esc_html__( 'Add heading content', 'workreap_core' ),
						],
						[
							'name' 			=> 'detail',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Add sub heading', 'workreap_core' ),
							'description'   => esc_html__( 'Add sub heading content', 'workreap_core' ),
						]
					]
				]
			);
			$this->add_control(
				'community_section',
				[
					'type'      	=> \Elementor\Controls_Manager::SWITCHER,
					'label'     	=> esc_html__( 'Join Community Section Enable/Disbale', 'workreap_core' ),
					'label_on' 		=> esc_html__( 'Enable', 'workreap_core' ),
					'label_off' 	=> esc_html__( 'Disable', 'workreap_core' ),
					'return_value' 	=> 'yes',
					'default' 		=> 'yes',
				]
			);
			$this->add_control(
				'community_contact_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Community contact title', 'workreap_core'),
					'rows' 			=> 5,
        			'description' 	=> esc_html__('Add contact title. leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'community_contact_contact_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Community contact number text', 'workreap_core'),
					'rows' 			=> 5,
        			'description' 	=> esc_html__('Add contact number. leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'community_contact_contact_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Community contact number link', 'workreap_core'),
					'rows' 			=> 5,
        			'description' 	=> esc_html__('Add contact number link. leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'btn_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore Button Text', 'workreap_core'),
					'rows' 			=> 5,
        			'description' 	=> esc_html__('Add text. leave it empty to hide.', 'workreap_core'),
				]
			);
			$this->add_control(
				'btn_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore Button URL', 'workreap_core'),
        			'description' 	=> esc_html__('Add url. leave it empty to hide.', 'workreap_core'),
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
			$settings 			= $this->get_settings_for_display();
			$image				= !empty( $settings['image']['url'] ) ? $settings['image']['url'] : '';
			$title				= !empty( $settings['title'] ) ? $settings['title'] : '';
			$sub_title			= !empty( $settings['sub_title'] ) ? $settings['sub_title'] : '';
			$video_url			= !empty( $settings['video_url'] ) ? $settings['video_url'] : '';
			$btn_text			= !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
			$btn_url			= !empty( $settings['btn_url'] ) ? $settings['btn_url'] : '';
			$community_section	= !empty( $settings['community_section'] ) ? $settings['community_section'] : '';
			
			$award_side_img		= !empty( $settings['award_side_img']['url'] ) ? $settings['award_side_img']['url'] : '';
			$services			= !empty( $settings['services'] ) ? $settings['services'] : '';

			$community_contact_title			= !empty( $settings['community_contact_title'] ) ? $settings['community_contact_title'] : '';
			$community_contact_contact_text		= !empty( $settings['community_contact_contact_text'] ) ? $settings['community_contact_contact_text'] : '';

			$community_contact_contact_link		= !empty( $settings['community_contact_contact_link'] ) ? $settings['community_contact_contact_link'] : '';
			
			?>
			<div class="container-fluid">
                <div class="row wt-freelanceplatform">
					<?php if( !empty($image) ){?>
						<div class="wt-communityvideo">
                        	<figure class="wt-video">
								<?php if( !empty($video_url) ){?>
									<a class="venobox vbox-item" data-vbtype="video" data-autoplay="true" href="<?php echo esc_url($video_url);?>">
									<span class="wt-video__icon"></span>
								<?php } ?>
									<img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($title);?>">
								<?php if( !empty($video_url) ){?>
									</a>
								<?php } ?>
							</figure>
                    	</div>
					<?php } ?>
                    <div class="wt-community">
						<?php if( !empty($title) || !empty($sub_title) || !empty($award_side_img) ){?>
							<div class="wt-community__title">
								<?php if( !empty($award_side_img) ){?>
									<img src="<?php echo esc_url($award_side_img);?>" alt="<?php echo esc_attr($title);?>">
								<?php } ?>
								<?php if( !empty($title) || !empty($sub_title) ){?>
									<div class="wt-sectiontitle wt-sectiontitlevthree">
										<?php if( !empty($title) ) {?><h2><?php echo esc_html($title);?></h2><?php } ?>
										<?php if( !empty($sub_title) ) {?><p><?php echo esc_html($sub_title);?></p><?php } ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
						<?php if( !empty($services) ){?>
							<ul class="wt-community__list">
								<?php
									foreach($services as $service ){
										$service_title		= !empty($service['title']) ? $service['title'] : '';
										$detail				= !empty($service['detail']) ? $service['detail'] : '';
										$icon_class			= !empty($service['icon_class']) ? $service['icon_class'] : '';
									?>
									<li>
										<?php if( !empty($icon_class) ){?>
											<span><i class="<?php echo esc_attr($icon_class);?>"></i></span>
										<?php } ?>
										<div class="wt-csubtitle">
											<?php if( !empty($service_title) ){?>
												<h3><?php echo esc_html($service_title);?></h3>
											<?php } ?>
											<?php if( !empty($detail) ){?>
												<p><?php echo esc_html($detail);?></p>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
							</ul>
						<?php } ?>
						<?php if( !empty($community_section) && $community_section == 'yes' ){?>
							<div class="wt-community__footer">
								<?php if( !empty($btn_text) ){?>
									<a href="<?php echo esc_url($btn_url);?>" class="wt-btn wt-btnv2">
										<?php echo esc_html($btn_text);?>
										<span class="rippleholder wt-jsripple"><em class="ripplecircle"></em></span>
									</a>
								<?php } ?>
								<span>
									<?php if( !empty($community_contact_title) ){?>
										<em><?php echo esc_html($community_contact_title);?></em>
									<?php } ?>
									<?php  if( !empty($community_contact_contact_text) ){ ?>
										<a href="<?php echo do_shortcode( $community_contact_contact_link );?>"><?php echo do_shortcode( $community_contact_contact_text );?></a>
									<?php } ?>
								</span>
							</div>
						<?php } ?>
                    </div>
                </div>
            </div>
		
		<?php 
			$script = '
			jQuery(document).on("ready", function(){
				jQuery(".venobox").venobox();
			});
			';
			wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Awards ); 
}