<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Projects')) {

    class Workreap_Projects {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_directory_type'));
			add_filter('manage_projects_posts_columns', array(&$this, 'projects_columns_add'));
			add_action('manage_projects_posts_custom_column', array(&$this, 'projects_columns'),10, 2);	
			add_action('add_meta_boxes', array(&$this, 'add_custom_meta_box'), 10, 2);
        }
		
		
        /**
         * @Init Post Type
         * @return {post}
         */
        public function init_directory_type() {
            $this->prepare_post_type();
        }

        /**
         * @Prepare Post Type Category
         * @return post type
         */
        public function prepare_post_type() {
            $labels = array(
                'name' 				=> esc_html__('Projects', 'workreap_core'),
                'all_items' 		=> esc_html__('Projects', 'workreap_core'),
                'singular_name' 	=> esc_html__('Project', 'workreap_core'),
                'add_new' 			=> esc_html__('Add Project', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New Project', 'workreap_core'),
                'edit' 				=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit Project', 'workreap_core'),
                'new_item' 			=> esc_html__('New Project', 'workreap_core'),
                'view' 				=> esc_html__('View Project', 'workreap_core'),
                'view_item' 		=> esc_html__('View Project', 'workreap_core'),
                'search_items' 		=> esc_html__('Search Project', 'workreap_core'),
                'not_found' 		=> esc_html__('No Project found', 'workreap_core'),
                'not_found_in_trash'=> esc_html__('No Project found in trash', 'workreap_core'),
                'parent' 			=> esc_html__('Parent Projects', 'workreap_core'),
            );
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new projects ', 'workreap_core'),
                'public' 				=> true,
                'supports' 				=> array('title','editor','author','excerpt'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> true,
                'exclude_from_search' 	=> false,
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
				'menu_icon'				=> 'dashicons-code-standards',
                'rewrite' 				=> array('slug' => 'project', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array('create_posts' => false)
				
            );
			
			if( apply_filters('workreap_system_access','job_base') === true ){
            	register_post_type('projects', $args);
			}
           
			//Regirster Category Taxonomy
            $cat_labels = array(
                'name' 					=> _x('Project category', 'Categories for projects', 'workreap_core' ),
                'singular_name' 		=> _x('Project category', 'Categories for projects','workreap_core'),
                'search_items'			=> _x('Search category', 'Categories for projects', 'workreap_core'),
                'all_items' 			=> _x('All category', 'Categories for projects', 'workreap_core'),
                'parent_item' 			=> _x('Parent category', 'Categories for projects', 'workreap_core'),
                'parent_item_colon' 	=> _x('Parent category:', 'Categories for projects', 'workreap_core'),
                'edit_item' 			=> _x('Edit category', 'Categories for projects', 'workreap_core'),
                'update_item' 			=> _x('Update category', 'Categories for projects', 'workreap_core'),
                'add_new_item' 			=> _x('Add New category', 'Categories for projects', 'workreap_core'),
                'new_item_name' 		=> _x('New category Name', 'Categories for projects', 'workreap_core'),
                'menu_name' 			=> _x( 'Categories', 'Categories for projects', 'workreap_core' ),
            );
			
            $cat_args = array(
                'hierarchical' 			=> true,
                'labels' 				=> $cat_labels,
                'show_in_quick_edit' 	=> true,
				'show_in_nav_menus' 	=> false,
				'show_admin_column' 	=> true,
                'query_var' 			=> true,
				'show_ui'               => true,
                'rewrite' 			=> array('slug' => 'project_cat'),
            );
			
			$service_labels = array(
                'name' 					=> _x('Service category', 'Categories for services', 'workreap_core' ),
                'singular_name' 		=> _x('Service category', 'Categories for services','workreap_core'),
                'search_items'			=> _x('Search category', 'Categories for services', 'workreap_core'),
                'all_items' 			=> _x('All category', 'Categories for services', 'workreap_core'),
                'parent_item' 			=> _x('Parent category', 'Categories for services', 'workreap_core'),
                'parent_item_colon' 	=> _x('Parent category:', 'Categories for services', 'workreap_core'),
                'edit_item' 			=> _x('Edit category', 'Categories for services', 'workreap_core'),
                'update_item' 			=> _x('Update category', 'Categories for services', 'workreap_core'),
                'add_new_item' 			=> _x('Add New category', 'Categories for services', 'workreap_core'),
                'new_item_name' 		=> _x('New category Name', 'Categories for services', 'workreap_core'),
                'menu_name' 			=> _x( 'Categories', 'Categories for services', 'workreap_core' ),
            );
			
            $service_args = array(
                'hierarchical' 		=> true,
                'labels' 			=> $service_labels,
                'show_ui' 			=> true,
                'show_admin_column' => false,
                'query_var' 		=> true,
                'show_in_nav_menus' => true,
                'rewrite' 			=> array('slug' => 'service_category', 'with_front' => true),
            );
			
			
			if (function_exists('fw_get_db_post_option') ) {
				$services_categories			= fw_get_db_settings_option('services_categories');
			}

			$services_categories			= !empty($services_categories) ? $services_categories : 'no';
			
			register_taxonomy('project_cat', array('projects'), $cat_args);	
			
			if(!empty($services_categories) && $services_categories === 'no'){
				register_taxonomy('project_cat', array('micro-services','projects'), $cat_args);
			}else{
				register_taxonomy('service_categories', array('micro-services'), $service_args);
			}
			
			//Regirster skills Taxonomy
            $skill_labels = array(
                'name' 				=> esc_html__('Skills', 'workreap_core'),
                'singular_name' 	=> esc_html__('Skill','workreap_core'),
                'search_items' 		=> esc_html__('Search Skill', 'workreap_core'),
                'all_items' 		=> esc_html__('All Skill', 'workreap_core'),
                'parent_item' 		=> esc_html__('Parent Skill', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Skill:', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit Skill', 'workreap_core'),
                'update_item' 		=> esc_html__('Update Skill', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New Skill', 'workreap_core'),
                'new_item_name' 	=> esc_html__('New Skill Name', 'workreap_core'),
                'menu_name' 		=> esc_html__('Skills', 'workreap_core'),
            );

            $skill_args = array(
                'hierarchical' 			=> true,
                'labels' 				=> $skill_labels,
                'show_in_quick_edit' 	=> true,
				'show_in_nav_menus' 	=> false,
				'show_admin_column' 	=> true,
                'query_var' 			=> true,
				'show_ui'               => true,
				'show_in_quick_edit'    => true,
                'rewrite' 				=> array('slug' => 'skill'),
            );
			
			if( apply_filters('workreap_system_access','job_base') === true ){
            	register_taxonomy('skills', array('projects','freelancers'), $skill_args);
			} else {
				register_taxonomy('skills', array('freelancers'), $skill_args);
			}
			
			//Regirster skills Taxonomy
            $location_labels = array(
                'name' => esc_html__('Locations', 'workreap_core'),
                'singular_name' => esc_html__('Location','workreap_core'),
                'search_items' => esc_html__('Search Location', 'workreap_core'),
                'all_items' => esc_html__('All Location', 'workreap_core'),
                'parent_item' => esc_html__('Parent Location', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Location:', 'workreap_core'),
                'edit_item' => esc_html__('Edit Location', 'workreap_core'),
                'update_item' => esc_html__('Update Location', 'workreap_core'),
                'add_new_item' => esc_html__('Add New Location', 'workreap_core'),
                'new_item_name' => esc_html__('New Location Name', 'workreap_core'),
                'menu_name' => esc_html__('Locations', 'workreap_core'),
            );
            $location_args = array(
                'hierarchical' => true,
                'labels'			=> $location_labels,
                'show_admin_column' => false,
                'query_var'			=> true,
				'show_in_quick_edit'=> false,
				'show_in_nav_menus' 	=> false,
				'meta_box_cb'       => false,
				'show_ui'                    => true,
				'show_in_quick_edit'         => false,
                'rewrite' => array('slug' => 'location'),
            );
			if( apply_filters('workreap_system_access','job_base') === true ){
            	register_taxonomy('locations', array('projects','freelancers', 'employers','micro-services'), $location_args);
			} else {
				register_taxonomy('locations', array('freelancers', 'employers','micro-services'), $location_args);
			}

            //Regirster Languages Taxonomy
            $languages_labels = array(
                'name' => esc_html__('Languages', 'workreap_core'),
                'singular_name' => esc_html__('Language','workreap_core'),
                'search_items' => esc_html__('Search Language', 'workreap_core'),
                'all_items' => esc_html__('All Language', 'workreap_core'),
                'parent_item' => esc_html__('Parent Language', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Language:', 'workreap_core'),
                'edit_item' => esc_html__('Edit Language', 'workreap_core'),
                'update_item' => esc_html__('Update Language', 'workreap_core'),
                'add_new_item' => esc_html__('Add New Language', 'workreap_core'),
                'new_item_name' => esc_html__('New Language Name', 'workreap_core'),
                'menu_name' => esc_html__('Languages', 'workreap_core'),
            );
            
            $language_args = array(
                'hierarchical' => true,
                'labels' => $languages_labels,
                'show_ui' => true,
				'show_in_nav_menus' 	=> false,
                'show_admin_column' => false,
                'query_var' => true,
                'rewrite' => array('slug' => 'languages'),
            );
			
			if( apply_filters('workreap_system_access','job_base') === true ){
            	register_taxonomy('languages', array('projects','freelancers'), $language_args);
			} else {
				register_taxonomy('languages', array('freelancers'), $language_args);
			}
			
			
			//Regirster Project Levels Taxonomy
            $project_labels = array(
                'name' => esc_html__('Project levels', 'workreap_core'),
                'singular_name' => esc_html__('Project level','workreap_core'),
                'search_items' => esc_html__('Search Project level', 'workreap_core'),
                'all_items' => esc_html__('All Project level', 'workreap_core'),
                'parent_item' => esc_html__('Parent Project level', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Project level:', 'workreap_core'),
                'edit_item' => esc_html__('Edit Project level', 'workreap_core'),
                'update_item' => esc_html__('Update Project level', 'workreap_core'),
                'add_new_item' => esc_html__('Add New Project level', 'workreap_core'),
                'new_item_name' => esc_html__('New Project level Name', 'workreap_core'),
                'menu_name' => esc_html__('Project levels', 'workreap_core'),
            );
            
            $durations_args = array(
                'hierarchical' => true,
                'labels' => $project_labels,
                'show_ui' => true,
				'show_in_nav_menus' 	=> false,
                'show_admin_column' => false,
                'query_var' => false,
                'rewrite' => array('slug' => 'project_levels'),
            );
			register_taxonomy('project_levels', array('projects'), $durations_args);
			
			if (function_exists('fw_get_db_settings_option')) {
                $job_experience_option = fw_get_db_settings_option('job_experience_option', $default_value = null);
            }
            
            $job_experience_option = !empty($job_experience_option) ? $job_experience_option['gadget'] : '';
			
            if(!empty($job_experience_option) && $job_experience_option ==='enable' ){
                //Regirster Project Experience  Taxonomy
                $project_experience_labels = array(
                    'name'              => esc_html__('Project Experience', 'workreap_core'),
                    'singular_name'     => esc_html__('Project Experience','workreap_core'),
                    'search_items'      => esc_html__('Search Project Experience', 'workreap_core'),
                    'all_items'         => esc_html__('All Project Experience', 'workreap_core'),
                    'parent_item'       => esc_html__('Parent Project Experience', 'workreap_core'),
                    'parent_item_colon' => esc_html__('Parent Project Experience:', 'workreap_core'),
                    'edit_item'         => esc_html__('Edit Project Experience', 'workreap_core'),
                    'update_item'       => esc_html__('Update Project Experience', 'workreap_core'),
                    'add_new_item'      => esc_html__('Add New Project Experience', 'workreap_core'),
                    'new_item_name'     => esc_html__('New Project Experience Name', 'workreap_core'),
                    'menu_name'         => esc_html__('Project Experience', 'workreap_core'),
                );
                
                $experience_args = array(
                    'hierarchical'  => true,
                    'labels'        => $project_experience_labels,
                    'show_ui'       => true,
                    'show_admin_column' => false,
                    'query_var'         => false,
					'public' => false,
                    'rewrite'           => array('slug' => 'project_experience'),
                );
                register_taxonomy('project_experience', array('projects'), $experience_args);
            }
			
			//Regirster Project Levels Taxonomy
            $duration_labels = array(
                'name' => esc_html__('Project duration', 'workreap_core'),
                'singular_name' => esc_html__('Project Duration','workreap_core'),
                'search_items' => esc_html__('Search Project Duration', 'workreap_core'),
                'all_items' => esc_html__('All Project Duration', 'workreap_core'),
                'parent_item' => esc_html__('Parent Project Duration', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Project Duration:', 'workreap_core'),
                'edit_item' => esc_html__('Edit Project Duration', 'workreap_core'),
                'update_item' => esc_html__('Update Project Duration', 'workreap_core'),
                'add_new_item' => esc_html__('Add New Project Duration', 'workreap_core'),
                'new_item_name' => esc_html__('New Project Duration Name', 'workreap_core'),
                'menu_name' => esc_html__('Project duration', 'workreap_core'),
            );
            
            $duration_args = array(
                'hierarchical' => true,
                'labels' => $duration_labels,
                'show_ui' => true,
                'show_admin_column' => false,
				'show_in_nav_menus' 	=> false,
                'query_var' => false,
                'rewrite' => array('slug' => 'project_duration'),
            );
			register_taxonomy('durations', array('projects'), $duration_args);
			
			//Regirster Project english level Taxonomy
            $english_labels = array(
                'name' => esc_html__('Freelancer English Level', 'workreap_core'),
                'singular_name' => esc_html__('Freelancer English Level','workreap_core'),
                'search_items' => esc_html__('Search Freelancer English Level', 'workreap_core'),
                'all_items' => esc_html__('All Freelancer English Level', 'workreap_core'),
                'parent_item' => esc_html__('Parent Freelancer English Level', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Freelancer English Level:', 'workreap_core'),
                'edit_item' => esc_html__('Edit Freelancer English Level', 'workreap_core'),
                'update_item' => esc_html__('Update Freelancer English Level', 'workreap_core'),
                'add_new_item' => esc_html__('Add New Freelancer English Level', 'workreap_core'),
                'new_item_name' => esc_html__('New Freelancer English Level Name', 'workreap_core'),
                'menu_name' => esc_html__('Freelancer English Level', 'workreap_core'),
            );
            
            $english_args = array(
                'hierarchical' => true,
                'labels' => $english_labels,
                'show_ui' => true,
                'show_admin_column' => false,
				'show_in_nav_menus' 	=> false,
                'query_var' => false,
                'rewrite' => array('slug' => 'english_level'),
            );
			register_taxonomy('english_level', array('freelancers'), $english_args);
			
			//Regirster freelancer type Taxonomy
            $freelancer_labels = array(
                'name' => esc_html__('Freelancer Type', 'workreap_core'),
                'singular_name' => esc_html__('Freelancer Type','workreap_core'),
                'search_items' => esc_html__('Search Freelancer Type', 'workreap_core'),
                'all_items' => esc_html__('All Freelancer Type', 'workreap_core'),
                'parent_item' => esc_html__('Parent Freelancer Type', 'workreap_core'),
                'parent_item_colon' => esc_html__('Parent Freelancer Type:', 'workreap_core'),
                'edit_item' => esc_html__('Edit Freelancer Type', 'workreap_core'),
                'update_item' => esc_html__('Update Freelancer Type', 'workreap_core'),
                'add_new_item' => esc_html__('Add New Freelancer Type', 'workreap_core'),
                'new_item_name' => esc_html__('New Freelancer Type Name', 'workreap_core'),
                'menu_name' => esc_html__('Freelancer Type', 'workreap_core'),
            );
            
            $freelancer_type_args = array(
                'hierarchical' => true,
                'labels' => $freelancer_labels,
                'show_in_quick_edit' 	=> true,
				'show_in_nav_menus' 	=> false,
				'show_admin_column' 	=> true,
                'query_var' 			=> true,
				'show_ui'               => true,
				'show_in_quick_edit'    => true,
                'rewrite' => array('slug' => 'freelancer_type'),
            );
			
			
			register_taxonomy('freelancer_type', array('freelancers','projects'), $freelancer_type_args);
			register_taxonomy('freelancer_type', array('projects','freelancers'), $freelancer_type_args);

        }
		
		/**
		 * @metabox
		 * @return {post}
		 */
		public function add_custom_meta_box($post_type,$post) {
			if ($post_type === 'projects') {
				if (function_exists('fw_get_db_settings_option')) {        
					$job_status = fw_get_db_settings_option('job_status', $default_value = null);
				}
				
				if(!empty($post->ID)){
                    $post_status	= get_post_meta($post->ID,'post_rejected',true);	
                }
    
                if(!empty($post_status) && $post_status === 'yes'){return;}
                
				if( ( $post->post_status === 'pending' )){
					add_meta_box( 'publish_custom_post', esc_html__('Approve Project', 'workreap_core'), array(&$this, 'approve_project_meta_box_print'), 'projects', 'side', 'high');
				}
            }
		}
		
		/**
		 * @Approve metabox
		 * @return {post}
		 */
		public function approve_project_meta_box_print($post) {
			$linked_profile	= $post->post_author;
			if(empty( $linked_profile )){return;}
			?>
			<ul class="review-info">
                <?php if( ( $post->post_status === 'pending' ) ){?>
					<li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_approve_post" data-type="project" data-post="<?php echo esc_attr( $post->ID );?>" data-id="<?php echo esc_attr( $linked_profile );?>"><?php esc_html_e('Approve Project', 'workreap_core'); ?></a>
						</span>
					</li>
                    <li>
						<span class="push-right">
							<a href="#" onclick="event_preventDefault(event);" class="do_reject_post" data-type="jobs" data-post="<?php echo esc_attr( $post->ID );?>" data-id="<?php echo esc_attr( $linked_profile );?>"><?php esc_html_e('Reject Project', 'workreap_core'); ?></a>
						</span>
					</li>
                <?php }?>
			</ul>
			<?php
		}
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function projects_columns_add($columns) {
			$columns['type'] 			= esc_html__('Project Type','workreap_core');
			$columns['featured'] 		= esc_html__('Featured','workreap_core');
			$columns['status'] 			= esc_html__('Status','workreap_core');
			$columns['price'] 			= esc_html__('Price','workreap_core');
		 
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function projects_columns($case) {
			global $post;
			
			$is_featured		= get_post_meta( get_the_ID(), '_featured_job_string',true);
			$status				= get_post_field('post_status',get_the_ID());
			
			if(!empty($status) && $status === 'hired'){
				$status_title		= esc_html__('Hired','workreap_core');
			}else if(!empty($status) && $status === 'completed'){
				$status_title		= esc_html__('Completed','workreap_core');
			}else if(!empty($status) && ( $status === 'published' || $status === 'publish' )){
				$status_title		= esc_html__('Published','workreap_core');
			}else if(!empty($status) && ( $status === 'pending' || $status === 'draft' )){
				$status_title		= esc_html__('Pending','workreap_core');
			}else {
				$status_title		= ucfirst( $status );
			}
			
			
				
			if( !empty( $is_featured ) && $is_featured > 0 ) {
				$featured		= esc_html__('Yes','workreap_core');
			} else {
				$featured		= esc_html__('No','workreap_core');
			}
			
			if (function_exists('fw_get_db_settings_option')) {
				$db_project_type 	= fw_get_db_post_option(get_the_ID(), 'project_type', true);
				$db_job_type 		= !empty( $db_project_type['gadget'] ) ? ($db_project_type['gadget']) : '';
				$db_project_cost 	= !empty( $db_project_type['fixed']['project_cost'] ) ? $db_project_type['fixed']['project_cost'] : '';
			} else {
				$db_project_cost	= '';
				$db_job_type		= '';
			}
			
			if(!empty($db_job_type) && $db_job_type === 'fixed'){
				$db_job_type_title		= esc_html__('Fixed','workreap_core');
			}else {
				$db_job_type_title		= esc_html__('Hourly','workreap_core');
			}
			
			switch ($case) {
				case 'type':
					echo '<span class="wt-status-'.strtolower($db_job_type).'">'.esc_attr( $db_job_type_title ).'</span>';
				break;
				
				case 'featured':
					echo esc_attr( $featured );
				break;
				
				case 'status':
					echo '<span class="wt-status-'.strtolower($status).'">'.esc_attr( $status_title ).'</span>';
				break;
				case 'price':
					workreap_price_format( $db_project_cost );
				break;
				
			}
		}

    }

    new Workreap_Projects();
}


