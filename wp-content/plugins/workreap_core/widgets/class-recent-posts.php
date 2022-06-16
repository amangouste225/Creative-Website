<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-out-authors-widget
 *
 * @author ab
 */
 
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('Workreap_RecentPosts')) {

    class Workreap_RecentPosts extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                    'workreap_recentposts' , // Base ID
                    esc_html__('Popular posts | Workreap' , 'workreap_core') , // Name
                array (
                	'classname' => 'wt-widget wt-widgetarticlesholder',
					'description' => esc_html__('Workreap Popular posts' , 'workreap_core') , 
				) // Args
            );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args , $instance) {
            // outputs the content of the widget
			global $post;
			
            extract($instance);
			$number_of_posts = isset($instance['number_of_posts']) && !empty($instance['number_of_posts']) ? $instance['number_of_posts'] : 3;
			$title = isset($instance['title']) && !empty($instance['title']) ? $instance['title'] : '';
			  
            $before	= ($args['before_widget']);
			$after	 = ($args['after_widget']);
			
			$img_width  	= intval(65);
			$img_height 	= intval(65);
			
			$date_formate	= get_option('date_format');
			
			echo ($before);
			$query_args = array(
				'posts_per_page' => $number_of_posts,
				'post_type' => 'post',
				'order' => 'DESC',
				'post_status' => 'publish',
				'orderby' => 'ID',
				'suppress_filters' => false,
				'ignore_sticky_posts' => 1
			);

			if (!empty($title) ) {
				echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			}

			$p_query = new WP_Query($query_args);
			if( $p_query->have_posts() ) {
				while ($p_query->have_posts()) : $p_query->the_post();
					global $post;
					$post_thumbnail_id = get_post_thumbnail_id($post->ID);
					$thumbnail 		   = workreap_prepare_thumbnail($post->ID, $img_width, $img_height);
					$thumb_meta = array();
					$wclass = 'wt-no-thumb';

					if (!empty($post_thumbnail_id)) {
						$thumb_meta = workreap_get_image_metadata($post_thumbnail_id);
						$wclass = '';
					}

					$image_title = !empty($thumb_meta['title']) ? $thumb_meta['title'] : '';
					$image_alt   = !empty($thumb_meta['alt']) ? $thumb_meta['alt'] : $image_title;
					?>
					<div class="wt-particlehold <?php echo esc_attr( $wclass );?>">
						<?php if (!empty($thumbnail)) { ?>
							<figure>
								<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($image_alt); ?>">
							</figure>
						<?php } ?>
						<div class="wt-particlecontent">
							<h3><?php workreap_get_post_title($post->ID); ?></h3>
							<span><i class="lnr lnr-clock"></i> <?php echo date_i18n($date_formate,strtotime(get_the_date()));?></span>
						</div>
					</div>
				<?php 
				endwhile;
				wp_reset_postdata();
			}
			echo ( $after );
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance) {
            // outputs the options form on admin
            $title           = !empty($instance['title']) ? $instance['title'] : esc_html__('Popular Articles' , 'workreap_core');
            $number_of_posts = !empty($instance['number_of_posts']) ? $instance['number_of_posts'] : 3;
            ?>
			<p>
                <label for="<?php echo ( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:','workreap_core'); ?></label> 
                <input class="widefat" id="<?php echo ( $this->get_field_id('title') ); ?>" name="<?php echo ( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo ( $this->get_field_id('number_of_posts') ); ?>"><?php esc_html_e('Number of posts to show:','workreap_core'); ?></label> 
                <input class="widefat" id="<?php echo ( $this->get_field_id('number_of_posts') ); ?>" name="<?php echo ( $this->get_field_name('number_of_posts')); ?>" type="number" min="1" value="<?php echo esc_attr($number_of_posts); ?>">
            </p>
            <?php
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        public function update($new_instance , $old_instance) {
            // processes widget options to be saved
            $instance                    = $old_instance;
            $instance['title']           = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
            $instance['number_of_posts'] = (!empty($new_instance['number_of_posts']) ) ? strip_tags($new_instance['number_of_posts']) : '';

            return $instance;
        }

    }

}
//register widget
function workreap_register_recentposts_widgets() {
	register_widget( 'Workreap_RecentPosts' );
}
add_action( 'widgets_init', 'workreap_register_recentposts_widgets' );