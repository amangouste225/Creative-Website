<?php
/**
 * Shortcode for the Top Freelancers V2
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

if( !class_exists('Workreap_Top_Freelancers_V2') ){
	class Workreap_Top_Freelancers_V2 extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_top_freelancers_v2';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Top Freelancers V2', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-person';
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
        			'description' 	=> esc_html__('Add title. leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap_core'),
        			'description' 	=> esc_html__('Add description. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			

			$this->add_control(
				'freelancers',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Freelancers', 'workreap_core'),
        			'description' 	=> esc_html__('Add top freelancer\'s with comma separated ID\'s e.g(12,20). Leave it empty to show freelancers by below settings', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'listing_type',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Show freelancer by', 'workreap_core'),
					'description' 	=> esc_html__('Select type to list freelancers by featured, verified, latest', 'workreap_core'),
					'default' 		=> '',
					'options' 		=> [
										'' 			=> esc_html__('Select freelancer listing type', 'workreap_core'),
										'featured' 	=> esc_html__('Featured', 'workreap_core'),
										'DESC' 		=> esc_html__('Recents', 'workreap_core'),
										'ASC' 		=> esc_html__('Former', 'workreap_core'),
										'rand' 		=> esc_html__('Random', 'workreap_core'),
										]
				]
			);

			$this->add_control(
				'listing_numbers',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Number of freelancers', 'workreap_core'),
        			'description' 	=> esc_html__('Add no of freelancer that show on listing.If empty then 4 freelancers will be listed.', 'workreap_core'),
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
			$desc  	     = !empty($settings['description']) ? $settings['description'] : '';

			$listing_numbers  	 = !empty($settings['listing_numbers']) ? $settings['listing_numbers'] : intval(4);
			$listing_type  	     = !empty($settings['listing_type']) ? $settings['listing_type'] : '';
			$freelancers_ids  	 = !empty($settings['freelancers']) ? explode(',',$settings['freelancers']) : array();
			
			if( function_exists('fw_get_db_settings_option')  ){
				$freelancer_avatar_search 		= fw_get_db_settings_option('freelancer_avatar_search');
			}
			
			$args = array(
				'post_type'		=> 'freelancers',
				'post_status'   => 'publish',
			);

			$args['posts_per_page']	= $listing_numbers;

			$meta_query			= array();
			$meta_query[]		= array(
										'key'   	=> '_profile_blocked',
										'compare' 	=> '=',
										'value' 	=> 'off');
			$meta_query[]		= array(
										'key'   	=> '_is_verified',
										'compare' 	=> '=',
										'value' 	=> 'yes');
			
			if(!empty($freelancer_avatar_search) && $freelancer_avatar_search === 'enable'){
				$meta_query[]		= array(
										'key'   	=> '_have_avatar',
										'value' 	=> 1,
										'compare' 	=> '='
									);
			}
			
			$loop = 'true';
			if( !empty( $freelancers_ids ) ){
				$args['post__in']	= $freelancers_ids;
				$loop = 'false';
			} else if( !empty($listing_type) ) {
				if( $listing_type === 'featured' ){
					$meta_query[]		= array(
						'key'   => '_featured_timestamp',
						'value' => 1);
				} else if( $listing_type === 'DESC' ){
					$args['order']			= 'DESC';
				} else if( $listing_type === 'ASC' ){
					$args['order']			= 'ASC';
				}
				
				if( $listing_type === 'rand' ){
					$args['orderby']			= 'rand';
				} else{
					$args['orderby']		= 'ID';
				}
			}
			
			$args['meta_query']		= $meta_query;

			$freelancers = get_posts($args);
			
			$flag	= rand(999,99999);
			?>
			<div class="wt-sc-top-freelancers-v2 wt-haslayout">
				<div class="row justify-content-center">
					<?php if( !empty( $title ) || !empty( $desc ) ) {?>
						<div class="col-12 col-lg-8">
							<div class="wt-sectionheadvtwo wt-textcenter">
								<?php if( !empty( $title ) ) {?>
									<div class="wt-sectiontitlevtwo">
										<?php if( !empty( $title ) ) {?><h2><?php echo do_shortcode( $title );?></h2><?php }?>
									</div>
								<?php } ?>
								<?php if( !empty( $desc ) ) {?>
									<div class="wt-description"><?php echo wpautop(do_shortcode($desc));?></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<?php if( !empty( $freelancers ) ) {?>
					<div class="row">
						<div id="wt-freelancers-silder-<?php echo esc_attr( $flag );?>" class="wt-freelancers-silder owl-carousel">
							<?php 
								foreach( $freelancers as $freelancer ){
									$freelancer_title 		= workreap_get_username('',$freelancer->ID);	;
									$tagline = '';
									if(function_exists('workreap_get_tagline')){
										$tagline				= workreap_get_tagline($freelancer->ID);
									}
									
									$freelancer_avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 320, 'height' => 220), $freelancer->ID), array('width' => 320, 'height' => 220) 
									);

									if (function_exists('fw_get_db_post_option')) {
										$perhour_rate	= fw_get_db_post_option($freelancer->ID, '_perhour_rate', true);	
									} else {
										$perhour_rate	= "";
									}
									?>
									<div class="wt-freelancer">
										<figure class="wt-freelancer-img">
											<a href="<?php echo get_the_permalink($freelancer->ID); ?>"><img src="<?php echo esc_url($freelancer_avatar); ?>" alt="<?php echo esc_attr($tagline); ?>"></a>
										</figure>
										<div class="wt-freelancer-head">
											<div class="wt-freelancer-tag">
												<a href="<?php echo get_the_permalink($freelancer->ID); ?>"><?php echo esc_html($freelancer_title); ?></a>
											</div>
											<div class="wt-title">
												<h3><?php echo esc_html($tagline); ?></h3>
											</div>
											<div class="wt-freelancer-about">
												<?php if( !empty($perhour_rate) && ( apply_filters('workreap_user_perhour_rate_settings',$freelancer->ID) === true ) ){?>
													<div class="wt-freelancer-price"><span><i class="fa fa-money"></i><?php do_action('workreap_price_format',$perhour_rate);?>&nbsp;/&nbsp;<?php esc_html_e('hr','workreap_core');?></span></div>
												<?php }?>
												<?php do_action('workreap_freelancer_get_reviews', $freelancer->ID, 'v3');?>
											</div>
											<div class="wt-freelancer-social">
												<?php do_action( 'workreap_print_skills_html', $freelancer->ID, '', '7' );?>
											</div>
										</div>
										<ul class="wt-freelancer-footer">
											<?php do_action('workreap_print_location', $freelancer->ID);?>
											<li><?php do_action('workreap_save_freelancer_html', $freelancer->ID, 'v2');?></li>
										</ul>
									</div>
							<?php }?>
						</div>
					</div>
					<script>
						jQuery(document).on('ready',function () {
							var carousel_init = jQuery("#wt-freelancers-silder-<?php echo esc_attr( $flag );?>").owlCarousel({
								item: 5,
								rtl: <?php echo workreap_owl_rtl_check();?>,
								loop:<?php echo esc_js($loop);?>,
								nav:false,
								margin: 30,
								autoplay:false,
								dots: true,
								dotsClass: 'wt-sliderdots',
								responsiveClass:true,
								responsive:{
									0:{items:1,},
									680:{items:2,},
									1081:{items:3,},
									1440:{items:4,},
									1760:{items:5,}
								}
							});

							carousel_init.trigger('refresh.owl.carousel');
							setTimeout( function(){carousel_init.trigger('refresh.owl.carousel');}, 500);
						});
					</script>
				<?php } ?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Top_Freelancers_V2 ); 
}