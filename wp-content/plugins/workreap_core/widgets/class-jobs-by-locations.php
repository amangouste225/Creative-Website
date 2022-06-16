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

if (!class_exists('Workreap_JobsByLocations')) {

    class Workreap_JobsByLocations extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                    'workreap_jobs_by_locations' , // Base ID
                    esc_html__('Jobs by locations | Workreap' , 'workreap_core') , // Name
                array (
                	'classname' => 'wt-footercol',
					'description' => esc_html__('Workreap jobs by locations' , 'workreap_core') , 
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
			
			$title 			 	= !empty($instance['title']) ? $instance['title'] : '';
			$project_locations  = !empty($instance['project_locations']) ? $instance['project_locations'] : array();

			$view_all_link  	= !empty($instance['view_all_link']) ? $instance['view_all_link'] : '';
			$locations_list 	= !empty($project_locations) ? $project_locations : '';
			$search_page	= '';

			if( function_exists('workreap_get_search_page_uri') ){
				$search_page  = workreap_get_search_page_uri('jobs');
			}
			
            $before	 = ($args['before_widget']);
			$after	 = ($args['after_widget']);
			
			if(empty($locations_list)){return;}
			
			echo ($before);

			if (!empty($title) ) {
				echo ($args['before_title'] . apply_filters('widget_title', esc_attr($title)) . $args['after_title']);
			}

			?>
			<ul class="wt-fwidgetcontent">
				<?php
					if(!empty($locations_list) && is_array($locations_list)){
						foreach ($locations_list as $key => $location) {
							$term	= get_term_by('ID',$location,'locations');
							$query_arg['location[]'] 	= urlencode($term->slug);
							$url                 		= add_query_arg( $query_arg, esc_url($search_page));
							?>
							<li><a href="<?php echo esc_url($url);?>"><?php esc_html_e('Jobs In', 'workreap_core'); ?> <?php echo esc_html($term->name); ?></a></li>                                    
						<?php 
						}
					}
				?>
				<?php if(!empty($view_all_link)) { ?>
					<li class="wt-viewmore"><a href="<?php echo esc_url($view_all_link); ?>"><?php esc_html_e('+ View All', 'workreap_core'); ?></a></li>
				<?php } ?>
			</ul>
			<?php
			echo ( $after );
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance) {
            // outputs the options form on admin
			$title           	= !empty($instance['title']) ? $instance['title'] : esc_html__('Search by location' , 'workreap_core');
			$view_all_link  	= !empty($instance['view_all_link']) ? $instance['view_all_link'] : '';
			$project_locations  = !empty($instance['project_locations']) ? $instance['project_locations'] : array();
            ?>
			<p>
                <label for="<?php echo ( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:','workreap_core'); ?></label> 
                <input class="widefat" id="<?php echo ( $this->get_field_id('title') ); ?>" name="<?php echo ( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
				<label><?php esc_html_e( 'Select locations:', 'workreap_core'); ?></label><br/>
				<?php $args = array(
						'post_type' => 'projects',
						'taxonomy'  => 'locations',
						'hide_empty' => false,
					);
			
					$terms = get_terms( $args );
					foreach( $terms as $id => $name ) { 
						$checked = "";
						if(!empty($project_locations) && is_array($project_locations) && in_array($name->term_id, $project_locations)){
							$checked = "checked";
						}
					?>
					<input type="checkbox" class="checkbox" id="<?php echo ( $this->get_field_id('project_locations') ); ?>" name="<?php echo $this->get_field_name('project_locations[]'); ?>" value="<?php echo esc_attr($name->term_id); ?>"  <?php echo esc_html($checked); ?>/>
					<label><?php echo esc_attr($name->name); ?></label><br/>
				<?php } ?>
			</p>
			<p>
                <label for="<?php echo ( $this->get_field_id('view_all_link') ); ?>"><?php esc_html_e('Add "View All" button link here, or leave it empty to hide button:','workreap_core'); ?></label> 
                <input class="widefat" id="<?php echo ( $this->get_field_id('view_all_link') ); ?>" name="<?php echo ( $this->get_field_name('view_all_link') ); ?>" type="text" value="<?php echo esc_attr($view_all_link); ?>">
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
            $instance                    	= $old_instance;
            $instance['title']           	= !empty($new_instance['title'])  ? strip_tags($new_instance['title']) : '';
            $instance['view_all_link']      = !empty($new_instance['view_all_link'])  ? $new_instance['view_all_link'] : '';
            $instance['project_locations']  = !empty($new_instance['project_locations']) ? $new_instance['project_locations'] : 0;

            return $instance;
        }

    }

}
//register widget
function workreap_register_jobs_by_location_widgets() {
	register_widget( 'Workreap_JobsByLocations' );
}
add_action( 'widgets_init', 'workreap_register_jobs_by_location_widgets' );