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

if (!class_exists('Workreap_RecentJobs')) {

    class Workreap_RecentJobs extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                    'workreap_recentjobs' , // Base ID
                    esc_html__('Recent Jobs | Workreap' , 'workreap_core') , // Name
                array (
                	'classname' => 'wt-footercol wt-widgetexplore',
					'description' => esc_html__('Workreap Recent Jobs' , 'workreap_core') , 
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
			$title 			 = !empty($instance['title']) ? $instance['title'] : '';
			$number_of_posts = !empty($instance['number_of_posts']) ? $instance['number_of_posts'] : intval(3);
			  
            $before	= ($args['before_widget']);
			$after	 = ($args['after_widget']);
			
			echo ($before);
			$query_args = array(
				'posts_per_page' => $number_of_posts,
				'post_type' => 'projects',
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
			if( $p_query->have_posts() ) {?>
				<ul class="wt-fwidgetcontent wt-recentposted">
				<?php 
					while ($p_query->have_posts()) : $p_query->the_post();
						global $post;
						$author_id 		 	= get_the_author_meta( 'ID' );  
						$linked_profile  	= workreap_get_linked_profile_id($author_id);
						$employer_title   	= workreap_get_username( '',$linked_profile );
				
						$location 			= wp_get_post_terms( $post->ID, 'locations');
						$employer_avatar 	= apply_filters(
							'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
						);
						?>
						<li>
							<div class="wt-latestjob-head">
								<?php if (!empty($employer_avatar)) { ?>
									<figure class="wt-latestjob-logo">
										<img src="<?php echo esc_url($employer_avatar); ?>" alt="<?php esc_attr_e('Avatar', 'workreap_core'); ?>">
									</figure>
								<?php } ?>
								<div class="wt-latestjob-title">
									<div class="wt-latestjob-tag">
										<a href="<?php echo get_the_permalink($linked_profile); ?>"> <?php echo esc_html($employer_title); ?> </a>
									</div>
									<h4><?php workreap_get_post_title($post->ID); ?></h4>
									<?php if(!empty($location[0]->name)){?>
										<span><?php echo esc_html($location[0]->name);  ?></span>
									<?php }?>
								</div>
							</div>
						</li>
					<?php 
					endwhile;
					wp_reset_postdata(); ?>
				</ul>
				<?php
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
            $title           = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posted Jobs' , 'workreap_core');
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
            $instance['title']           = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
            $instance['number_of_posts'] = !empty($new_instance['number_of_posts']) ? $new_instance['number_of_posts'] : '';

            return $instance;
        }

    }

}
//register widget
function workreap_register_recentjobs_widgets() {
	register_widget( 'Workreap_RecentJobs' );
}
add_action( 'widgets_init', 'workreap_register_recentjobs_widgets' );