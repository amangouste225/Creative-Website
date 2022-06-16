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

if( !class_exists('Workreap_Micro_Services') ){
	class Workreap_Micro_Services extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_micro_services';
		}


		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Micro Services', 'workreap_core' );
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
			if(function_exists('fw_get_db_settings_option')){
				$services_categories	= fw_get_db_settings_option('services_categories');
			}
			$services_categories	= !empty($services_categories) ? $services_categories : 'no';

			if( !empty($services_categories) && $services_categories === 'no' ) {
				$taxonomy_type	= 'project_cat';
			}else{
				$taxonomy_type	= 'service_categories';
			}
			
			$services	= elementor_get_taxonomies('micro-services', $taxonomy_type);
			$services	= !empty($services) ? $services : array();
			
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
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add button title. Leave it empty to hide button.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Link', 'workreap_core'),
        			'description' 	=> esc_html__('Add button link. Leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'services',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Choose Category', 'workreap_core'),
					'desc' 			=> esc_html__('Select category services to display.', 'workreap_core'),
					'options'   	=> $services,
					'label_block' 	=> true,
					'multiple' 		=> true,
				]
			);
			
			$this->add_control(
				'layout',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Column type','workreap_core' ),
					'description'   => esc_html__('Select column type', 'workreap_core' ),
					'default' 		=> 'no',
					'options' 		=> [
										'two' => esc_html__('2 Columns', 'workreap_core'),
										'three' => esc_html__('3 Columns', 'workreap_core'),
										'four' => esc_html__('4 Columns', 'workreap_core'),
										],
				]
			);
			
			$this->add_control(
				'show_posts',
				[
					'label' => esc_html__( 'Number of posts', 'workreap_core' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'posts' ],
					'range' => [
						'posts' => [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'posts',
						'size' => 6,
					]
				]
			);
			
			$this->add_control(
				'pagination',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Show pagination','workreap_core' ),
					'description'   => esc_html__('Show pagination or show only view more button', 'workreap_core' ),
					'default' 		=> 'no',
					'options' 		=> [
										'yes' => esc_html__('Yes', 'workreap_core'),
										'no' => esc_html__('No', 'workreap_core'),
										],
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
			$service_video_option		= '';
			if ( function_exists('fw_get_db_post_option' )) {
				$service_video_option 			= fw_get_db_settings_option('service_video_option');
				$default_service_banner    	= fw_get_db_settings_option('default_service_banner');
			}
			
			$title      = !empty( $settings['title'] ) ? $settings['title'] : '';
			$sub_title  = !empty( $settings['sub_title'] ) ? $settings['sub_title'] : '';
			$desc       = !empty( $settings['description'] ) ? $settings['description'] : '';
			$btn_title  = !empty( $settings['btn_title'] ) ? $settings['btn_title'] : '';
			$show_posts = !empty( $settings['show_posts']['size'] ) ? $settings['show_posts']['size'] : 6;
			$layout  	= !empty( $settings['layout'] ) ? $settings['layout'] : 'three';
			$page_link  = !empty( $settings['btn_link'] ) ? $settings['btn_link'] : '';
			$pagination		= !empty( $settings['pagination'] ) ? $settings['pagination']  : '';
			$catgories		= !empty( $settings['services'] ) ? $settings['services']  : '';
			$catgories		= is_array($catgories) ? $catgories : array($catgories);
			
			global $paged;
			$pg_page  = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
			$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
			//paged works on single pages, page - works on homepage
			$paged    = max($pg_page, $pg_paged);
			
			
			$width			= 352;
			$height			= 200;
			$flag 			= rand(9999, 999999);
			
			$column			= 4;
			$columnClass	= 'three-column-holder';	

			if( !empty( $layout ) && $layout === 'four'  ){
				$column			= 3;
				$columnClass	= 'four-column-holder';	
			}else if( !empty( $layout ) && $layout === 'two'  ){
				$column			= 6;
				$columnClass	= 'two-column-holder';	
				$width			= 670;
				$height			= 370;
			}
			
			if(function_exists('fw_get_db_settings_option')){
				$services_categories	= fw_get_db_settings_option('services_categories');
			}
			$services_categories	= !empty($services_categories) ? $services_categories : 'no';

			if( !empty($services_categories) && $services_categories === 'no' ) {
				$taxonomy_type	= 'project_cat';
			}else{
				$taxonomy_type	= 'service_categories';
			}
			
			$taxonomy_args = array();
			$tax_query_args  = array();
			if(!empty($catgories)){
				foreach($catgories as $key => $cat){
					if(!empty($cat)){
						$taxonomy_args[]	= array (
											'taxonomy' 	=> $taxonomy_type,
											'field'		=> 'term_id',
											'terms'		=> $cat,
										);
					}
				}
				
				$query_relation = array('relation' => 'OR',);
				$tax_query_args[] = array_merge($query_relation, $taxonomy_args);   
				
			}

			$micro_services = array(
								'posts_per_page' 	=> $show_posts,
								'post_type' 	 	=> 'micro-services',
								'paged' 			=> $paged,
								'orderby' 			=> 'ID',
								'order' 			=> 'DESC',
							);
			
			//Taxonomy Query
			if ( !empty( $tax_query_args ) ) {
				$query_relation = array('relation' => 'AND',);
				$micro_services['tax_query'] = array_merge($query_relation, $tax_query_args);
			}
			
			
			$service_data = new \WP_Query($micro_services); 
			$count_post = $service_data->found_posts;
			?>
			<div class="wt-sc-micro-services-main wt-haslayout">
				<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $desc ) ) {?>
					<div class="row justify-content-md-center">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
							<div class="wt-sectionhead wt-textcenter wt-topservices-title">
								<div class="wt-sectiontitle">
									<?php if( !empty( $title ) ) { ?><h2><?php echo esc_html( $title );?></h2><?php } ?>
									<?php if( !empty( $sub_title ) ) { ?><span><?php echo esc_html( $sub_title);?></span><?php } ?>
								</div>
								<?php if( !empty( $desc ) ) { ?>
									<div class="wt-description">
										<p><?php echo do_shortcode( $desc ) ;?></p>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div class="wt-freelancers-holder wt-freelancers-home <?php echo esc_attr($columnClass);?>">
						<?php 
						if ($service_data->have_posts()) {
							while( $service_data->have_posts() ) { 
								$service_data->the_post();
								global $post;
								$random = rand(1,9999);
								$author_id 			= get_the_author_meta( 'ID' );  
								$linked_profile 	= workreap_get_linked_profile_id($author_id);	
								$service_url		= get_the_permalink();
								$db_docs			= array();
								$delivery_time		= '';
								$order_details		= '';

								if (function_exists('fw_get_db_post_option')) {
									$db_docs   			= fw_get_db_post_option($post->ID,'docs');
									$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
									$order_details   	= fw_get_db_post_option($post->ID,'order_details');
								}

								if( empty($db_docs) && !empty($default_service_banner) ){
									$db_docs[0]	= $default_service_banner;
								}
								
								if( empty($db_docs) ) {
									$empty_image_class	= 'wt-empty-service-image';
									$is_featured		= workreap_service_print_featured( $post->ID, 'yes');
									$is_featured    	= !empty( $is_featured ) ? 'wt-featured-service' : '';
								} else {
									$empty_image_class	= '';
									$is_featured		= '';
								}

								$script	= "new Splide( '.wtsplide-".$random."',{direction:'".workreap_splide_rtl_check()."'} ).mount();";
								wp_add_inline_script( 'splide', $script, 'after' );
							?>
							<div class="col-12 col-sm-12 col-md-6 col-lg-<?php echo intval($column);?> float-left wt-services-grid">
								<div class="wt-freelancers-info <?php echo esc_attr( $empty_image_class );?> <?php echo esc_attr( $is_featured );?>">
									<?php if( !empty( $db_docs ) ) {?>
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
														if(function_exists('workreap_prepare_image_source')){
															$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
														}
														
														if(!empty($thumbnail)){
														?>
														<div class="splide__slide"><figure class="item">
															<a href="<?php echo esc_url( $service_url );?>">
																<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Service ','workreap_core');?>" class="item">
															</a>
														</figure></div>
													<?php }} ?>
												</div>
											</div>
										</div>
									<?php } ?>
									<?php do_action('workreap_service_print_featured', $post->ID); ?>
									<?php do_action('workreap_service_shortdescription', $post->ID,$linked_profile); ?>
									<?php do_action('workreap_service_type_html',$post->ID);?>
								</div>
							</div>
						<?php } wp_reset_postdata();?>
						<?php }?>
					</div>
					<?php if(!empty($pagination) && $pagination === 'yes'){?>
						<?php if ($count_post > $show_posts ) : ?>
							<div class="col-12 col-sm-12 col-md-12 col-lg-12">
								<?php workreap_prepare_pagination($count_post, $show_posts); ?>
							</div>
						<?php endif; ?>
					<?php }else{?>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
								<div class="wt-btnarea btn-viewservices">
									<a href="<?php echo esc_url( $page_link );?>" class="wt-btn"><?php echo esc_html($btn_title);?></a>
								</div>
							</div>
					<?php }?>
				</div>
			</div>
		<?php 

		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Micro_Services ); 
}