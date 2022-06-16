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

if( !class_exists('Workreap_Best_Services') ){
	class Workreap_Best_Services extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_best_services';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Best Services', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-settings';
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
			$categories	= elementor_get_taxonomies('micro-services', 'service_categories', 0);
			$categories	= !empty($categories) ? $categories : array();

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
				'link_target',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Select listing type', 'workreap_core'),
					'desc'			=> esc_html__('Do you want to list by categories or Services ids?', 'workreap_core'),
					'options' 		=> [
										'categories' => esc_html__('Categories', 'workreap_core'),
										'services_ids' => esc_html__('Services IDs', 'workreap_core'),
										],
					'default' 		=> 'project_ids',
				]
			);
			$this->add_control(
				'services_ids',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Services ID\'s', 'workreap_core'),
					'description' 	=> esc_html__('Add services ID\'s with comma(,) separated e.g(15,21). Leave it empty to show latest servicess.', 'workreap_core'),
					'condition' => [
						'link_target' => 'services_ids',
					],
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
					'condition' => [
						'link_target' => 'categories',
					],
				]
			);

			$this->add_control(
				'show_posts',
				[
					'label' => __( 'Number of posts', 'workreap_core' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'micro-services' ],
					'range' => [
						'micro-services' => [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'micro-services',
						'size' => 9,
					]
				]
			);

			$this->add_control(
				'order',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Order','workreap_core' ),
					'description'   => esc_html__('Select posts Order.', 'workreap_core' ),
					'default' 		=> 'DESC',
					'options' 		=> [
						'ASC' 	=> esc_html__('ASC', 'workreap_core'),
						'DESC' 	=> esc_html__('DESC', 'workreap_core'),
					],
				]
			);
			
			$this->add_control(
				'orderby',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Post Order','workreap_core' ),
					'description'   => esc_html__('View Posts By.', 'workreap_core' ),
					'default' 		=> 'ID',
					'options' 		=> [
						'ID' 		=> esc_html__('Order by post id', 'workreap_core'),
						'author' 	=> esc_html__('Order by author', 'workreap_core'),
						'title' 	=> esc_html__('Order by title', 'workreap_core'),
						'name' 		=> esc_html__('Order by post name', 'workreap_core'),
						'date' 		=> esc_html__('Order by date', 'workreap_core'),
						'rand' 		=> esc_html__('Random order', 'workreap_core'),
						'comment_count' => esc_html__('Order by number of comments', 'workreap_core'),
					],
				]
			);
			
			
			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Button Title', 'workreap_core' ),
					'description' 	=> esc_html__('Add button or leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Button Link', 'workreap_core' ),
					'description' 	=> esc_html__('Add button link, or default will be #.', 'workreap_core'),
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
		/**
		 * Render shortcode
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();
			$service_video_option		= '';
			if ( function_exists('fw_get_db_post_option' )) {
				$service_video_option 			= fw_get_db_settings_option('service_video_option');
				$default_service_banner    	= fw_get_db_settings_option('default_service_banner');
			}

			$pg_page  = get_query_var('page') ? get_query_var('page') : 1;
			$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$paged    	= max($pg_page, $pg_paged);

			$show_posts = !empty($settings['show_posts']['size']) ? $settings['show_posts']['size'] : -1;
			$order 		= !empty($settings['order']) ? $settings['order'] : 'ASC';
			$orderby 	= !empty($settings['orderby']) ? $settings['orderby'] : 'ID';

			$title     = !empty($settings['title']) ? $settings['title'] : '';
			$btn_title = !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link  = !empty($settings['btn_link']) ? $settings['btn_link'] : '';

			$link_target  = !empty($settings['link_target']) ? $settings['link_target'] : '';
			
			$query_args = array(
				'posts_per_page' 	  => $show_posts,
				'post_type' 	 	  => 'micro-services',
				'orderby' 	 	  	  => $orderby,
				'order' 	 	  	  => $order,
				'paged' 		 	  => $paged,
				'post_status' 	 	  => array( 'publish'),
				'ignore_sticky_posts' => 1
			);

			if( !empty($link_target) && $link_target == 'categories' ){
				$categories  = !empty($settings['categories']) ? $settings['categories'] : array();
				$tax_query_args	= array();
				if( !empty($categories) ){
					$query_relation = array('relation' => 'AND',);
					$category_args  = array();
					foreach( $categories as $key => $cat ){
						$category_args[] = array(
								'taxonomy' => 'service_categories',
								'field'    => 'term_id',
								'terms'    => $cat,
							);
					}
					
					$query_args['tax_query'] = array_merge($query_relation, $category_args); 
					
				}
			} else if( !empty($link_target) && $link_target == 'services_ids' ){
				$services_ids  = !empty($settings['services_ids']) ? explode(',',$settings['services_ids']) : array();
				if( !empty($services_ids) ){
					$query_args['post__in']	= $services_ids;
				}

			}
			
			$services_posts = new \WP_Query($query_args); 
			$total_posts   = $services_posts->found_posts;
			$flag 				= rand(9999, 999999);
			?>
			<div class="wt-bestservices-section">
				<div class="theme-container">
					<?php if( !empty($title) ){?>
						<div class="wt-sectionhead wt-sectionheadvfour">
							<div class="wt-sectiontitle wt-sectiontitlevthree">
								<h2><?php echo esc_html($title);?></h2>
							</div>
						</div>
					<?php } ?>
					<?php if ($services_posts->have_posts()) { ?>
						<div class="wt-bestserviceholder">
							<div class="row">
							<?php 
								while($services_posts->have_posts()) {
									$services_posts->the_post();
									global $post;
									$random = rand(1,9999);
									$author_id 			= get_the_author_meta( 'ID' );  
									$linked_profile 	= workreap_get_linked_profile_id($author_id);	
									$service_url		= get_the_permalink();
									$db_docs			= array();
									$delivery_time		= '';
									$order_details		= '';
									$db_videos			= array();
									if (function_exists('fw_get_db_post_option')) {
										$db_docs   			= fw_get_db_post_option($post->ID,'docs');
										$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
										$order_details   	= fw_get_db_post_option($post->ID,'order_details');
										$db_videos   		= fw_get_db_post_option($post->ID,'videos');
									}
									
									if( empty($db_docs) && !empty($default_service_banner) ){
										$db_docs[0]	= $default_service_banner;
									}
	
									$images_count	= !empty($db_docs) && is_array($db_docs) ? count($db_docs) : 0;
									if( empty($images_count) ){
										$images_count	= !empty($db_videos) && is_array($db_videos) ? count($db_videos) : 0;
									}

									$is_featured	= apply_filters( 'workreap_service_print_featured', $post->ID,'yes' );
									$script	= "new Splide( '.wtsplide-".$random."',{direction:'".workreap_splide_rtl_check()."'} ).mount();";
									wp_add_inline_script( 'splide', $script, 'after' );
									?>
									<div class="col-sm-6 col-lg-4 col-xl-3">
										<div class="wt-bestservice">
											<div class="wt-bestservice__img">
												<?php if( !empty( $images_count ) ) {?>
													<div class="wtsplide-wrapper wtsplide-<?php echo esc_attr($random);?> wt-freelancers">
														<div class="splide__track">
															<div class="splide__list">
																<?php 
																	if( !empty($service_video_option) && $service_video_option == 'yes' ){
																		do_action( 'workreap_services_videos', $post->ID ,352,200);
																	}
																?>
																<?php
																	foreach( $db_docs as $key => $doc ){
																		$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
																		$thumbnail      = workreap_prepare_image_source($attachment_id, 352, 200);
																		if (strpos($thumbnail,'media/default.png') === false) {?>
																		<div class="splide__slide">
																			<figure class="wt-cards__img item">
																				<a href="<?php echo esc_url( $service_url );?>">
																					<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Service','workreap_core');?>">
																					<?php if( !empty($is_featured) ){?>
																						<em class="wt-featuretag__shadow">
																							<span class="wt-featuretag"><?php esc_html_e('Featured','workreap_core');?><i class="fa fa-bolt"></i> </span>
																						</em>
																					<?php } ?>
																				</a>
																			</figure>
																		</div>
																<?php } }?>
															</div>
														</div>
													</div>
												<?php } ?>
												<?php do_action('workreap_service_type_html',$post->ID);?>
											</div>
											<?php do_action('workreap_service_shortdescriptionv2', $post->ID,$linked_profile); ?>
										</div>
									</div>
									<?php
									} 
									wp_reset_postdata();
								?>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty($btn_title) ){?>
						<div class="wt-sectionbtn">
							<a href="<?php echo esc_url($btn_link);?>" class="wt-btn wt-btnv2"><?php echo esc_html($btn_title);?></a>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Best_Services ); 
}