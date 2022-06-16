<?php
/**
 * Shortcode for categories v3
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

if( !class_exists('Workreap_Freelancer_By_Specialization') ){
	class Workreap_Freelancer_By_Specialization extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_freelancer_by_specialization';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Freelancers by specialization', 'workreap_core' );
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
			$categories	= elementor_get_taxonomies('projects', 'wt-specialization', 0);
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
				'section_heading',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Heading', 'workreap_core' ),
					'description'   => esc_html__( 'Add section heading. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'section_desc',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap_core' ),
					'description'   => esc_html__( 'Add section description. Leave it empty to hide.', 'workreap_core' ),
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
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add explore title, which will be displayed above show all button. Leave it empty to hide button.', 'workreap_core'),
				]
			);

			$this->add_control(
				'button_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Link', 'workreap_core'),
        			'description' 	=> esc_html__('Add button link, default will be #', 'workreap_core'),
				]
			);

			$this->add_control(
				'desc',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Description', 'workreap_core'),
        			'description' 	=> esc_html__('Add description, which will be displayed above show all button. Leave it empty to hide button.', 'workreap_core'),
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
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Specializations?', 'workreap_core'),
					'desc' 			=> esc_html__('Select specializations to display.', 'workreap_core'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
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

			$section_heading	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$section_desc 		= !empty($settings['section_desc']) ? $settings['section_desc'] : '';
			$btn_title          = !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link           = !empty($settings['btn_link']) ? $settings['btn_link'] : '#';
			$title      		= !empty($settings['title']) ? $settings['title'] : '';
			$link       		= !empty($settings['link']) ? $settings['link'] : '#';
			$desc       		= !empty($settings['desc']) ? $settings['desc'] : '';
			$post_type          = 'freelancers';
			$categories         = !empty($settings['categories']) ? $settings['categories'] : array();

			$search_page	= '';
			if( function_exists('workreap_get_search_page_uri') ){
				$search_page  = workreap_get_search_page_uri($post_type);
			}
			
			$rand	= rand(1,99999);
			?>
			<div class="wt-sc-explore-categories-v3 wt-haslayout wt-categoriestwo-wrap sc-<?php echo esc_attr($rand);?>">
				<div class="row justify-content-center">
					<?php if (!empty($section_heading) || !empty($section_desc)) { ?>
						<div class="col-12 col-lg-8">
							<div class="wt-sectionheadvtwo wt-textcenter">
								<?php if (!empty($section_heading)) { ?>
									<div class="wt-sectiontitlevtwo">
										<h2><?php echo do_shortcode($section_heading); ?></h2>
									</div>
								<?php } ?>
								<?php if (!empty($section_desc)) { ?>
									<div class="wt-description">
										<?php echo wpautop(do_shortcode($section_desc)); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $categories )  ) { ?>
						<div class="col-12">
							<ul class="wt-categoryvtwo wt-categoryvthree">
								<?php foreach( $categories as $key => $cat_id ) { 
									$category      = get_term($cat_id);
									$icon          = array();
									$query_arg     = array();
									$query_arg['specialization[]']   = urlencode($category->slug);
									$permalink                 = add_query_arg( $query_arg, esc_url($search_page));
				
									$category_icon = array();
									if( function_exists( 'fw_get_db_term_option' ) ) {
										$icon          = fw_get_db_term_option($cat_id, 'wt-specialization');
										$category_icon = !empty($icon['specialization_icon']) ? $icon['specialization_icon'] : array();
									}
									?>
									<li>
										<a href="<?php echo esc_url( $permalink );?>">
											<div class="wt-categorycontentvtwo">
												<?php
													if (!empty($category_icon) && $category_icon['type'] === 'icon-font') {
														do_action('enqueue_unyson_icon_css');
														if (!empty($category_icon['icon-class'])) {?>
															<figure><i class="<?php echo esc_attr($category_icon['icon-class']); ?>"></i></figure>
														<?php
														}
													} elseif (!empty($category_icon['type']) && $category_icon['type'] === 'custom-upload') {
														if (!empty($category_icon['url'])) {?>
															<figure><img src="<?php echo esc_url($category_icon['url']); ?>" alt="<?php esc_attr_e('Category','workreap_core'); ?>"></figure>
															<?php
														}
													}
												?>
												<div class="wt-cattitlevtwo">
													<h4><?php echo esc_html($category->name); ?></h4>
												</div>
												<?php if( !empty( $category->description ) ) { ?>
													<div class="wt-description">
														<p><?php echo esc_html($category->description); ?></p>
													</div>
												<?php } ?>
											</div>
										</a>
									</li>
								<?php } ?>
								<?php if(!empty($btn_title) || !empty($title) || !empty($desc)) { ?>
									<li class="wt-morecategory">
										<div class="wt-categorycontentvtwo">
											<?php if(!empty($title)) { ?>
												<div class="wt-cattitlevtwo">
													<h4><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h4>
												</div>
											<?php } ?>
											<?php if(!empty($desc)) { ?>
												<div class="wt-description">
													<p><?php echo esc_html($desc); ?></p>
												</div>
											<?php } ?>
											<?php if( $btn_title ) { ?>
											<div class="wt-btnarea">
												<a href="<?php echo esc_url( $btn_link ); ?>" class="wt-btntwo"><?php echo esc_html( $btn_title ); ?></a>
											</div>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Freelancer_By_Specialization ); 
}