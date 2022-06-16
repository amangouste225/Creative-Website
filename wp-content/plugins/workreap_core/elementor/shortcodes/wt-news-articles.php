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

if( !class_exists('Workreap_News') ){
	class Workreap_News extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_news';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Blog listing', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-posts-ticker';
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
			$categories	= elementor_get_taxonomies();
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
					'label'     	=> esc_html__( 'Title', 'workreap_core' ),
					'description'   => esc_html__( 'Add section title. Leave it empty to hide.', 'workreap_core' ),
				]
			);
			
			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__('Description','workreap_core' )
				]
			);
			
			$this->add_control(
				'blog_view',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Listing type','workreap_core' ),
					'description'   => esc_html__('List/Grid settings are below.', 'workreap_core' ),
					'default' 		=> 'list',
					'options' 		=> [
										'grid' 	=> esc_html__('Grid', 'workreap_core'),
										'list' 	=> esc_html__('List', 'workreap_core'),
										],
				]
			);
			$this->add_control(
				'get_method',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('News By','workreap_core' ),
					'description'   => esc_html__('Select news by category or item.', 'workreap_core' ),
					'default' 		=> 'by_cats',
					'options' 		=> [
										'by_posts' 	=> esc_html__('By item', 'workreap_core'),
										'by_cats' 	=> esc_html__('By Categories', 'workreap_core'),
										],
				]
			);
			
			$this->add_control(
				'show_pagination',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Pagination option','workreap_core' ),
					'description'   => esc_html__('Select pagination option.', 'workreap_core' ),
					'default' 		=> 'no',
					'options' 		=> [
										'yes' 	=> esc_html__('Yes', 'workreap_core'),
										'no' 	=> esc_html__('No', 'workreap_core'),
										],
				]
			);
			
			$this->add_control(
				'categories_options',
				[
					'label' => esc_html__( 'Categories settings', 'workreap_core' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			
			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Categories', 'workreap_core'),
					'desc' 			=> esc_html__('Select categories to display posts.', 'workreap_core'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
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
				'show_posts',
				[
					'label' => __( 'Number of posts', 'workreap_core' ),
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
						'size' => 9,
					]
				]
			);
			
			$this->add_control(
				'posts_options',
				[
					'label' => esc_html__( 'Posts/Items settings', 'workreap_core' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			
			$this->add_control(
				'posts',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label'     	=> esc_html__('Add posts ID\'s','workreap_core' ),
					'description'   => esc_html__('Add posts ID\'s with comma seprated e.g (10,19) if News by selection is By item.', 'workreap_core' ),
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
				'list_section',
				[
					'label' => esc_html__( 'List settings', 'workreap_core' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'list',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Listing type','workreap_core' ),
					'default' 		=> 'full',
					'options' 		=> [
										'full' 	=> esc_html__('Full', 'workreap_core'),
										'small' => esc_html__('Small', 'workreap_core'),
										],
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
				'grid_section',
				[
					'label' => esc_html__( 'Grid settings', 'workreap_core' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'columns',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Description','workreap_core' ),
					'default' 		=> '2_cols',
					'options' 		=> [
										'2_cols' 	=> esc_html__('Classic View Two Columns', 'workreap_core'),
										'3_cols' 	=> esc_html__('Three Columns', 'workreap_core'),
										'4_cols' 	=> esc_html__('Four Columns', 'workreap_core')
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
			global $paged;
			$blog_view	= !empty($settings['blog_view']) ? $settings['blog_view'] : '';	
			$pg_page  = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
			$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
			//paged works on single pages, page - works on homepage
			$paged    = max($pg_page, $pg_paged);
			$title    = !empty($settings['title']) ? $settings['title'] : '';
			$desc     = !empty($settings['description']) ? $settings['description'] : '';
			
			$size     =  !empty( $settings['list'] )? $settings['list'] : 'full';

			if (isset($settings['get_method']) && $settings['get_method'] === 'by_posts' && !empty($settings['posts'])) {
				$posts_in['post__in'] 	= !empty($settings['posts']) ? explode(',',$settings['posts']) : array();
				$order      			= 'DESC';
				$orderby    			= 'ID';
				$show_posts 			= -1;
			} else {
				$cat_sepration = array();
				$cat_sepration = $settings['categories'];
				$order         = !empty($settings['order']) ? $settings['order'] : 'DESC';
				$orderby       = !empty($settings['orderby']) ? $settings['orderby'] : 'ID';
				$show_posts    = !empty($settings['show_posts']['size']) ? $settings['show_posts']['size'] : -1;
				if (!empty($cat_sepration)) {
					$slugs = array();
					foreach ($cat_sepration as $value) {
						$term    = get_term($value, 'category');
						$slugs[] = $term->slug;
					}
					
					$filterable = $slugs;
					$tax_query['tax_query'] = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'category',
							'terms'    => $filterable,
							'field'    => 'slug',
					));
				}
			}
			//Main Query 
			$query_args = array(
				'posts_per_page' 		=> $show_posts,
				'post_type' 			=> 'post',
				'paged' 				=> $paged,
				'order' 				=> $order,
				'orderby' 				=> $orderby,
				'paged' 				=> $paged,
				'post_status' 			=> 'publish',
				'ignore_sticky_posts' 	=> 1
			);

			//By Categories
			if (!empty($cat_sepration)) {
				$query_args = array_merge($query_args, $tax_query);
			}
			//By Posts 
			if (!empty($posts_in)) {
				$query_args = array_merge($query_args, $posts_in);
			}
			$query      = new \WP_Query($query_args);
			$count_post = $query->found_posts;
			if( !empty( $blog_view ) && $blog_view === 'list' ) { ?>
				<div class="wt-sc-articlelist wt-haslayout wt-articlelist">
					<?php if(!empty( $title ) || !empty( $desc )) { ?>
						<div class="wt-classicaricle-header">
							<?php if( !empty( $title ) ) { ?>
								<div class="wt-title">
									<h2><?php echo esc_html( $title ); ?></h2>
								</div>
							<?php } ?>
							<?php if( !empty( $desc ) ) { ?>
								<div class="wt-description">
									<?php echo wp_kses_post( do_shortcode( $desc ) ); ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					<?php if ($query->have_posts()) { ?>
						<div class="wt-article-holder">
							<?php 
								while ($query->have_posts()) { 
									$query->the_post();
									global $post;
									if( $size === 'small' ){
										$height = intval(240);
										$width  = intval(730);
									} else{
										$height = intval(400);
										$width  = intval(1140);
									}

									$user_ID           = get_the_author_meta('ID');
									$post_thumbnail_id = get_post_thumbnail_id($post->ID);
									$thumbnail         = workreap_prepare_thumbnail($post->ID, $width, $height);
									$enable_author     = '';
									if (function_exists('fw_get_db_post_option')) {
										$enable_author = fw_get_db_post_option($post->ID, 'enable_author', true);
									}

									$thumb_meta = array();
									if (!empty($post_thumbnail_id)) {
										$thumb_meta = workreap_get_image_metadata($post_thumbnail_id);
									}

									$image_title = !empty($thumb_meta['title']) ? $thumb_meta['title'] : 'thumbnail';
									$image_alt   = !empty($thumb_meta['alt']) ? $thumb_meta['alt'] : $image_title;
									?>
									<div class="wt-article">
										<?php if (!empty($thumbnail)) { ?>
											<figure>
												<a href="<?php echo esc_url(get_permalink()); ?>">
													<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($image_alt); ?>">
												</a>
											</figure>
										<?php } ?>
										<div class="wt-articlecontent">
											<div class="wt-title">
												<h2><?php workreap_get_post_title($post->ID); ?></h2>
											</div>
											<ul class="wt-postarticlemeta">
												<li><?php workreap_get_post_date($post->ID); ?></li>
												<?php if (!empty($enable_author) && $enable_author === 'enable') { ?>
													<li><?php workreap_get_post_author( $user_ID , 'linked', $post->ID ); ?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
							<?php } wp_reset_postdata(); ?>
						</div>
				   <?php } ?>
				   <?php if (isset($settings['show_pagination']) && $settings['show_pagination'] == 'yes' && $count_post > $show_posts ) : ?>
						<div class="wt-paginationvtwo"><?php workreap_prepare_pagination($count_post, $show_posts); ?></div>
				   <?php endif; ?>
				</div>
			<?php } else if( !empty( $blog_view ) && $blog_view === 'grid' ) {	
					$columns = !empty($settings['columns']) ? $settings['columns'] : '';

					$col_class = '';
					if(!empty($columns) && $columns === '2_cols'){
						$col_class  = "col-12 col-sm-12 col-md-6 col-lg-6";
						$img_width  = intval(540);
						$img_height = intval(240);
					} elseif(!empty($columns) && $columns === '3_cols') {
						$col_class  = "col-12 col-sm-12 col-md-6 col-lg-4";
						$img_width  = intval(355);
						$img_height = intval(352);
					} else {
						$col_class = "col-12 col-sm-12 col-md-6 col-lg-3";
						$img_width  = intval(355);
						$img_height = intval(352);
					}	
				?>
				<div class="wt-sc-articles wt-haslayout">
					<div class="row">
						<?php if(!empty( $title ) || !empty( $desc )) { ?>
							<div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-12 push-lg-3">
								<div class="wt-sectionhead wt-textcenter">
									<div class="wt-sectiontitle">
										<?php if( !empty( $title ) ) { ?>
											<h2><?php echo esc_html( $title ); ?></h2>
										<?php } ?>
										<?php if( !empty( $desc ) ) { ?>
											<span><?php echo do_shortcode( $desc ); ?></span>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
						<?php
						if ($query->have_posts()) {
							while ($query->have_posts()) {
								$query->the_post();
								global $post;
								$height = $img_height;
								$width  = $img_width;

								$post_thumbnail_id = get_post_thumbnail_id($post->ID);
								$thumbnail 		   = workreap_prepare_thumbnail($post->ID, $width, $height);
								$user_ID 		   = get_the_author_meta('ID');
								$enable_author     = '';
								if (function_exists('fw_get_db_post_option')) {
									$enable_author = fw_get_db_post_option($post->ID, 'enable_author', true);
								}

								$thumb_meta = array();
								if (!empty($post_thumbnail_id)) {
									$thumb_meta = workreap_get_image_metadata($post_thumbnail_id);
								}
								$image_title = !empty($thumb_meta['title']) ? $thumb_meta['title'] : 'no-name';
								$image_alt   = !empty($thumb_meta['alt']) ? $thumb_meta['alt'] : $image_title;
								?>
								<div class="<?php echo esc_attr($col_class); ?>">
									<div class="wt-article">
										<?php if (!empty($thumbnail)) { ?>
											<figure>
												<a href="<?php echo esc_url(get_permalink()); ?>">
													<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($image_alt); ?>">
												</a>
											</figure>
										<?php } ?>
										<div class="wt-articlecontent">
											<div class="wt-title">
												<h2><?php workreap_get_post_title($post->ID); ?></h2>
											</div>
											<ul class="wt-postarticlemeta">
												<li><?php workreap_get_post_date($post->ID); ?></li>
												<?php if (!empty($enable_author) && $enable_author === 'enable') { ?>
													<li><?php workreap_get_post_author( $user_ID , 'linked', $post->ID ); ?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
								<?php
							} wp_reset_postdata();
						}
						?>
						<?php if (isset($settings['show_pagination']) && $settings['show_pagination'] == 'yes' && $count_post > $show_posts ) : ?>
							<div class="wt-paginationvtwo"><?php workreap_prepare_pagination($count_post, $show_posts); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php } ?>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_News ); 
}