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

if( !class_exists('Workreap_Mailchimp_Newsletter') ){
	class Workreap_Mailchimp_Newsletter extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_mailchimp';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Mailchimp newsletter', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-mail';
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
				'left_image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Image', 'workreap_core' ),
					'description'   => esc_html__( 'Add left side image. Leave it empty to hide.', 'workreap_core' ),
				]
			);

			$this->add_control(
				'right_image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Image', 'workreap_core' ),
					'description'   => esc_html__( 'Add right side image. Leave it empty to hide.', 'workreap_core' ),
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
				'side_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Sub title', 'workreap_core' ),
					'description'   => esc_html__( 'Add section sub/side title. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			$this->add_control(
				'details',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label'     	=> esc_html__( 'Description', 'workreap_core' ),
					'description'   => esc_html__( 'Add description. Leave it empty to hide.', 'workreap_core' ),
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
			$settings 		= $this->get_settings_for_display();
			$left_image		= !empty($settings['left_image']) ? $settings['left_image']['url'] : '';
			$right_image	= !empty($settings['right_image']) ? $settings['right_image']['url'] : '';
			$title			= !empty($settings['title']) ? $settings['title'] : '';
			$sub_title		= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$title			= !empty($settings['title']) ? $settings['title'] : '';

			$details			= !empty($settings['details']) ? $settings['details'] : '';
			$flag 			= rand(9999, 999999);
			?>
			<div class="container-fluid wt-mailchimpnewsltter">
                <div class="wt-joinnow">
                    <div class="wt-sectiontitle__center">
                        <div class="row">
                            <div class="col-xl-5 col-lg-8 col-md-10 col-sm-11">
								<?php if( !empty($title) || !empty($sub_title) || !empty($details) ){?>
									<div class="wt-sectiontitle wt-sectiontitlevthree">
										<?php if( !empty($title) || !empty($sub_title) ){?>
											<h2>
												<?php echo esc_html($title) ?>
												<?php if(!empty($sub_title) ){?> 
													<span><?php echo esc_html($sub_title);?></span>
												<?php } ?>
											</h2>
										<?php } ?>
										<?php if( !empty($details) ){?>
											<p><?php echo esc_html( $details );?></p>
										<?php } ?>
									</div>
								<?php } ?>
                           		<div class="wt-haslayout wt-joininfotwo wt-joininfotwonew">
								  <div class="justify-content-md-center">
									<?php if(class_exists('Workreap_MailChimp')) {
											$mailchimp = new \Workreap_MailChimp();
											$mailchimp->workreap_mailchimp_form();
										}
									?>
								  </div>
							    </div>
                            </div>
                        </div>
					</div>
					<?php if( !empty($left_image) ){?>
						<div class="wt-joinnow__imgleft">
							<figure>
								<img class="wt-joinnow__imgleft--img" src="<?php echo esc_url($left_image);?>" alt="<?php esc_attr_e('Joun now','workreap_core');?>">
							</figure>
						</div>
					<?php } ?>
					<?php if( !empty($right_image) ){?>
						<div class="wt-joinnow__imgright">
							<figure>
								<img class="wt-joinnow__imgright--img" src="<?php echo esc_url($right_image);?>" alt="<?php esc_attr_e('Joun now','workreap_core');?>">
							</figure>
						</div>
					<?php } ?>
                </div>
            </div>
		<?php
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Mailchimp_Newsletter ); 
}