<?php
/**
 * Shortcode for listing packages
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

if( !class_exists('Workreap_Packages_List') ){
	class Workreap_Packages_List extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_packages_list';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Packages List', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-woocommerce';
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
					'label' 		=> esc_html__( 'Add Title', 'workreap_core' ),
					'description' 	=> esc_html__('Add title or leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'tax_note',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Tax Note', 'workreap_core' ),
					'description' 	=> esc_html__('Add tax note or leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'desc',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__( 'Add Description', 'workreap_core' ),
					'description' 	=> esc_html__('Add description or leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'packages_type',
				[
					'label' => esc_html__( 'Packages Type', 'workreap_core' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'freelancer',
					'options' => [
						'both' 	=> esc_html__('Show both with toggle button', 'workreap_core'),
						'freelancer' 	=> esc_html__('Show freelancer packages', 'workreap_core'),
						'employer' 	=> esc_html__('Show employer packages', 'workreap_core'),
					],
					'description' 	=> esc_html__('Select packages type to show on the front-end', 'workreap_core'),
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
			global $current_user;
			$settings = $this->get_settings_for_display();

			$selection_btn	= !empty($settings['selection_btn']) ? $settings['selection_btn'] : '';
			$packages_type	= !empty($settings['packages_type']) ? $settings['packages_type'] : '';
			$title			= !empty($settings['title']) ? $settings['title'] : '';
			$tax_note		= !empty($settings['tax_note']) ? $settings['tax_note'] : '';
			$sub_title		= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$description	= !empty($settings['desc']) ? $settings['desc'] : '';
			$flag 			= rand(9999, 999999);

			$currency_symbol	= workreap_get_current_currency();
			$package_features 	= workreap_get_pakages_features();
			$meta_query_args	= array();
			$args 				= array(
				'post_type' 			=> 'product',
				'posts_per_page' 		=> -1,
				'post_status' 			=> 'publish',
				'ignore_sticky_posts' 	=> 1
			);
			
			$meta_query_args = array(
				array(
					'key' 		=> 'package_type',
					'value' 	=> 'freelancer',
					'compare' 	=> '='
				),
				array(
					'key' 		=> 'package_type',
					'value' 	=> 'employer',
					'compare' 	=> '='
				),
			);
			
			$query_relation 	= array('relation' => 'OR',);
			$meta_query_args 	= array_merge($query_relation, $meta_query_args);
			$args['meta_query'] = $meta_query_args;
			$packages 			= new \WP_Query( $args );
			
			$user_type = 'administrator';
			if(is_user_logged_in()){
				$user_type		= apply_filters('workreap_get_user_type', $current_user->ID );
			}
			
			$classLogin	= 'renew-package-shortcode';
			if(!is_user_logged_in()){
				$classLogin	= 'wt-please-login';
			}
			
			if( !empty($user_type) && $user_type !== 'administrator' && $user_type !== $packages_type && $packages_type !== 'both' ) {
				return;
			}

			?>
			<div class="wt-sc-packages-list wt-packages-wrap dynamic-secton-<?php echo esc_attr( $flag );?>">
				<div class="row justify-content-center">
					<?php if(!empty($title) || !empty($description)) { ?>
						<div class="col-12 col-lg-8">
							<div class="wt-sectionheadvtwo wt-textcenter">
								<?php if(!empty($title)) { ?>
									<div class="wt-sectiontitlevtwo">
										<h2><?php echo do_shortcode($title); ?></h2>
									</div>
								<?php } ?>
								<?php if(!empty($description)) { ?>
									<div class="wt-description">
										<?php echo wpautop(do_shortcode($description)); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if (class_exists('WooCommerce')) { ?>
						<div class="wt-packagestwo">
							<?php if( ( !is_user_logged_in()  || ( is_user_logged_in() && $user_type === 'administrator' ) ) && $packages_type === 'both' ) { ?>
								<div class="col-12">
									<div class="wt-switcharea">
										<div class="wt-switchtitle">
											<h6><?php esc_html_e('I’m Freelancer', 'workreap_core'); ?></h6>
										</div>
										<div class="wt-switch">
											<input type="checkbox" id="wt-package-switch" name="switch">
											<label for="wt-package-switch"><i></i></label>
										</div>
										<div class="wt-switchtitle">
											<h6><?php esc_html_e('I’m Employer', 'workreap_core'); ?></h6>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php 
								if ($packages->have_posts()) {
									while ($packages->have_posts()) :
										$packages->the_post();
										global $product,$current_user;
										$post_id 		= intval($product->get_id());
										$duration_type	= get_post_meta($post_id, 'wt_duration_type', true);
										$duration_title = workreap_get_duration_types($duration_type, 'title');
										$package_img	= get_the_post_thumbnail_url($post_id,array(100,100));
										$role_type		= get_post_meta($post_id, 'package_type', true);
										
										$typeClass = '';
									    if(is_user_logged_in() && $user_type !== 'administrator'){
											if ( !empty($packages_type) && $packages_type === 'both'){
												
												if ( $role_type === 'freelancer' ) {
													$typeClass	= 'freelancer-packages';
												}else if ( $role_type === 'employer' ) {
													$typeClass	= 'employer-packages';
												}else{
													$typeClass	= 'employer-packages elm-display-none';
												}
											}else if ( !empty($packages_type) && $packages_type === 'freelancer'){
												
												if ( $role_type === 'employer' ) {
													$typeClass	= 'employer-packages elm-display-none';
												}else if ( $role_type === 'freelancer' ) {
													$typeClass	= 'freelancer-packages';
												}

											}else if ( !empty($packages_type) && $packages_type === 'employer'){
												if ( $role_type === 'freelancer' ) {
													$typeClass	= 'freelancer-packages elm-display-none';
												}
											}
											
										}else{
											if (!empty($role_type) && !empty($packages_type) && $role_type === $packages_type ) {
												$typeClass	= 'freelancer-packages';
											} else if ( !empty($packages_type) && $packages_type === 'both' && $role_type === 'freelancer' ) {
												$typeClass	= 'freelancer-packages';
											}else{
												$typeClass	= 'employer-packages elm-display-none';
											}	
										}

										if( $role_type === $user_type || $user_type === 'administrator' ){
										?>
										<div class="col-md-6 col-lg-4 <?php echo esc_attr($typeClass);?>">
											<div class="wt-packagetwo">
												<div class="wt-package-content">
													<h5><?php echo esc_html(get_the_title()); ?></h5>
													<?php if(!empty($package_img)){?>
														<img src="<?php echo esc_url($package_img); ?>" alt="<?php esc_attr_e('Package', 'workreap_core'); ?>">
													<?php }?>
													<strong><?php echo do_shortcode($product->get_price_html()); ?><sub> <?php echo  esc_html($duration_title);?></sub></strong>
													
													<?php if (!empty($tax_note)) { ?>
														<em><?php echo esc_html($tax_note); ?></em>
													<?php } ?>
												</div>
												<div class="jb-package-feature">
													<h6><?php esc_html_e('Package Features', 'workreap_core'); ?>:</h6>
													<ul>
													<?php
														if (!empty($package_features)) {
															foreach ($package_features as $key => $vals) {
																if( $vals['user_type'] === $role_type || $vals['user_type'] === 'common' ) {
																	do_action('workreap_print_pakages_features', $key, $vals, $post_id, 'v2');
																}
																
															}
														}
													?>
													</ul>
													<div class="wt-btnarea">
														<a class="wt-btntwo <?php echo esc_attr( $classLogin );?>" data-key="<?php echo intval($post_id);?>" href="#" onclick="event_preventDefault(event);"><span><?php esc_html_e('Buy Now', 'workreap_core');?></span></a>
													</div>
												</div>
											</div>
										</div>
									<?php } 
									endwhile;
									wp_reset_postdata();
								} ?>
							</div>
					<?php }?>
				</div>
			</div>
		<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Packages_List ); 
}