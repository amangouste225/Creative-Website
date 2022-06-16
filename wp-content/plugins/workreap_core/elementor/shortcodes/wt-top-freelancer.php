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

if( !class_exists('Workreap_Top_Freelancers') ){
	class Workreap_Top_Freelancers extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_top_freelancers';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Top Freelancers', 'workreap_core' );
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
					'description' 	=> esc_html__('Select type to list freelancers by featured,verified,latest', 'workreap_core'),
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
        			'description' 	=> esc_html__('Add no of freelancer that show on listing.If empty then 4 freelancers are listing.', 'workreap_core'),
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

			$listing_numbers  	 = !empty($settings['listing_numbers']) ? $settings['listing_numbers'] : 4;
			$listing_type  	     = !empty($settings['listing_type']) ? $settings['listing_type'] : '';
			$freelancers_ids  	 = !empty($settings['freelancers']) ? explode(',',$settings['freelancers']) : array();
			
			if(function_exists('fw_get_db_settings_option')){
				$freelancer_avatar_search 	= fw_get_db_settings_option('freelancer_avatar_search');
			}
			
			$args = array(
				'post_type'		=> 'freelancers',
				'post_status'   => 'publish',
			);

			$args['posts_per_page']	= $listing_numbers;

			$meta_query			= array();
			$meta_query[]		= array(
										'key'   => '_profile_blocked',
										'value' => 'off');
			$meta_query[]		= array(
										'key'   => '_is_verified',
										'value' => 'yes');
			
			if( !empty( $freelancers_ids ) ){
				$args['post__in']	= $freelancers_ids;
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
			
			if(!empty($freelancer_avatar_search) && $freelancer_avatar_search === 'enable'){
				$meta_query[] = array(
					'key' 			=> '_have_avatar',
					'value' 		=> 1,
					'compare' 		=> '='
				); 
			}
			
			$args['meta_query']		= $meta_query;

			$freelancers = get_posts($args);
			?>
			<div class="wt-sc-top-freelancers wt-latearticles">
				<div class="row justify-content-md-center">
					<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $desc ) ) {?>
						<div class="col-12 col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2">
							<div class="wt-sectionhead wt-textcenter">
								<?php if( !empty( $title ) || !empty( $sub_title ) ) {?>
									<div class="wt-sectiontitle">
										<?php if( !empty( $title ) ) {?><h2><?php echo esc_html( $title );?></h2><?php }?>
										<?php if( !empty( $sub_title ) ) {?><span><?php echo esc_html( $sub_title );?></span><?php }?>
									</div>
								<?php } ?>
								<?php if( !empty( $desc ) ) {?>
									<div class="wt-description"><?php echo do_shortcode( $desc );?></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $freelancers ) ) {?>
						<div class="wt-topfreelancers">
							<?php 
								foreach( $freelancers as $freelancer ){
									$author_id 				= workreap_get_linked_profile_id($freelancer->ID, 'post');
									$freelancer_title 		= esc_html( get_the_title( $freelancer->ID ));
									$tagline = '';
									if(function_exists('workreap_get_tagline')){
										$tagline				= workreap_get_tagline($freelancer->ID);
									}
									
									$freelancer_avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 225, 'height' => 225), $freelancer->ID), array('width' => 225, 'height' => 225) 
									);

									$class	= apply_filters('workreap_featured_freelancer_tag',$author_id,'yes');
									$class	= !empty($class) ? $class : '';

									if (function_exists('fw_get_db_post_option')) {
										$perhour_rate	= fw_get_db_post_option($freelancer->ID, '_perhour_rate', true);	
									} else {
										$perhour_rate	= "";
									}

									$reviews_data 	= get_post_meta( $freelancer->ID , 'review_data');
									$reviews_rate	= !empty( $reviews_data[0]['wt_average_rating'] ) ? floatval( $reviews_data[0]['wt_average_rating'] ) : 0 ;
									$total_rating	= !empty( $reviews_data[0]['wt_total_rating'] ) ? intval( $reviews_data[0]['wt_total_rating'] ) : 0 ;
									$round_rate 		= number_format((float) $reviews_rate, 1);
									$rating_average		= ( $round_rate / 5 )*100;
									
									?>

									<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 float-left wt-verticaltop">
										<div class="wt-freelanceritems">
											<div class="wt-userlistinghold <?php echo esc_attr($class);?>">
												<?php do_action('workreap_featured_freelancer_tag',$author_id);?>
												<div class="wt-userlistingcontent">
													<figure>
														<a href="<?php echo esc_url(get_the_permalink($freelancer->ID));?>"><img src="<?php echo esc_url($freelancer_avatar); ?>" alt="<?php echo esc_attr($tagline); ?>"></a>
														<?php echo do_action('workreap_print_user_status',$author_id);?>
													</figure>
													<div class="wt-contenthead">
														<div class="wt-title">
															<?php do_action( 'workreap_get_verification_check', $freelancer->ID, $freelancer_title ); ?>
															<h2><?php echo esc_html($tagline); ?></h2>
														</div>
													</div>
													<div class="wt-viewjobholder">
														<ul>
															<?php if( !empty($perhour_rate) && ( apply_filters('workreap_user_perhour_rate_settings',$freelancer->ID) === true ) ){?>
																<li><span><i class="fa fa-money"></i><?php do_action('workreap_price_format',$perhour_rate);?>&nbsp;/&nbsp;<?php esc_html_e('hr','workreap_core');?></span></li>
															<?php }?>
															<?php do_action('workreap_print_location',$freelancer->ID);?>
															<li><?php do_action('workreap_save_freelancer_html',$freelancer->ID);?></li>
															<li>
																<a href="#" onclick="event_preventDefault(event);" class="wt-freestars">
																	<i class="fa fa-star"></i><?php echo esc_html( $round_rate );?>/<?php esc_html_e('5','workreap_core');?>&nbsp;<em>(<?php echo esc_html( $total_rating );?>&nbsp;<?php esc_html_e('Feedback','workreap_core');?>)</em>
																</a>
															</li>
														</ul>	
													</div>
												</div>
											</div>
										</div>
									</div>
							<?php }?>
						</div>
					<?php }?>
				</div>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Top_Freelancers ); 
}