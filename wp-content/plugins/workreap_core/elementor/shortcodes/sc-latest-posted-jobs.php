<?php
/**
 * Shortcode for latest posted jobs
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

if( !class_exists('Workreap_Latest_Posted_jobs') ){
	class Workreap_Latest_Posted_jobs extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_latest_jobs';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Latest Posted Jobs', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-sort-amount-desc';
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
				'desc',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__( 'Add Description', 'workreap_core' ),
					'description' 	=> esc_html__('Add description or leave it empty to hide.', 'workreap_core'),
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
		
			$this->add_control(
				'show_posts',
				[
					'label' => __( 'Number of posts', 'workreap_core' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'projects' ],
					'range' => [
						'projects' => [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'projects',
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

			$pg_page  = get_query_var('page') ? get_query_var('page') : 1;
			$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$paged    	= max($pg_page, $pg_paged);

			$show_posts = !empty($settings['show_posts']['size']) ? $settings['show_posts']['size'] : -1;
			$order 		= !empty($settings['order']) ? $settings['order'] : 'ASC';
			$orderby 	= !empty($settings['orderby']) ? $settings['orderby'] : 'ID';

			$title     = !empty($settings['title']) ? $settings['title'] : '';
			$desc      = !empty($settings['desc']) ? $settings['desc'] : '';
			$btn_title = !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link  = !empty($settings['btn_link']) ? $settings['btn_link'] : '#';
			
			$query_args = array(
				'posts_per_page' 	  => $show_posts,
				'post_type' 	 	  => 'projects',
				'orderby' 	 	  	  => $orderby,
				'order' 	 	  	  => $order,
				'paged' 		 	  => $paged,
				'post_status' 	 	  => array( 'publish'),
				'ignore_sticky_posts' => 1
			);

			$project_posts = new \WP_Query($query_args); 
			$total_posts   = $project_posts->found_posts;

			?>
			<div class="sc-lastest-jobs wt-haslayout wt-latestjobs-wrap">
				<div class="row justify-content-center">
					<?php if(!empty($title) || !empty($desc)) { ?>
						<div class="col-12 col-lg-8">
							<div class="wt-sectionheadvtwo wt-textcenter">
								<?php if(!empty($title)) { ?>
									<div class="wt-sectiontitlevtwo">
										<h2><?php echo do_shortcode( $title ); ?></h2>
									</div>
								<?php } ?>
								<?php if(!empty($desc)) { ?>
									<div class="wt-description">
										<?php echo wpautop(do_shortcode($desc)); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if ($project_posts->have_posts()) { ?>
						<div class="col-12">
							<div class="wt-latestjobs">
							<ul>
								<?php 
                                    while($project_posts->have_posts()) {
                                        $project_posts->the_post();
										global $post; 

										$author_id 		 = get_the_author_meta( 'ID' );  
										$linked_profile  = workreap_get_linked_profile_id($author_id);
										$employer_title  = workreap_get_username( $author_id );
										$location 		 = wp_get_post_terms( $post->ID, 'locations');	
										$employer_avatar = apply_filters(
											'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
										);

										$project_price	= workreap_project_price($post->ID);
										$project_type	= !empty($project_price['type']) ? $project_price['type'] : '';

										$job_type_text = '';
										if($project_type == 'hourly') {
											$job_type_text = esc_html__('/ HOUR', 'workreap_core'); 
										}										

										?>
										<li>
											<div class="wt-latestjob-holder">
												<div class="wt-latestjob-head">
													<figure class="wt-latestjob-logo">
														<img src="<?php echo esc_url($employer_avatar); ?>" alt="<?php esc_attr_e('Employer', 'workreap_core'); ?>">
													</figure>
													<div class="wt-latestjob-title">
														<div class="wt-latestjob-tag">
															<a href="<?php echo get_the_permalink($linked_profile); ?>"> <?php echo esc_html($employer_title); ?> </a>
														</div>
														<h4><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h4>
														<?php if(!empty($location[0]->name)){?>
															<span><?php echo esc_html($location[0]->name); ?></span>
														<?php }?>
													</div>
													<div class="wt-latestjob-right">
														<span><?php esc_html_e('Remuneration', 'workreap_core'); ?> <?php echo esc_html($job_type_text); ?></span>
														<?php do_action('workreap_print_project_price', $post->ID, 'v2');?>
														<span><?php do_action('workreap_print_project_duration_html', $post->ID, 'v2'); ?></span>
													</div>
												</div>
												<div class="wt-latestjob-footer">
													<?php do_action('workreap_print_skills_html', $post->ID, '', '8');?>
													<div class="wt-btnarea">
														<?php  do_action('workreap_save_project_html', $post->ID, 'v3'); ?>
														<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="wt-btntwo"><?php esc_html_e('Apply now', 'workreap_core'); ?></a>
													</div>
												</div>
											</div>
										</li>
									<?php
								} wp_reset_postdata(); ?>
							</ul>
							<?php if (!empty($total_posts) && $total_posts > $show_posts && !empty($btn_title)) { ?>
								<div class="wt-btnarea">
									<a href="<?php echo esc_url($btn_link); ?>" class="wt-btntwo"><?php echo esc_html($btn_title); ?></a>
								</div>
							<?php }?>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
			<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Latest_Posted_jobs ); 
}