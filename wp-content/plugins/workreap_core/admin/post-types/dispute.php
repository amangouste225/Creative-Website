<?php

/**
 * @package   Workreap Core
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Dispute')) {

    class Workreap_Dispute {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_directory_type'));
            add_action('add_meta_boxes', array(&$this, 'linked_details_add_meta_boxes'), 10, 2);
            add_action('add_meta_boxes', array(&$this, 'messages_add_meta_box'), 10, 2);	
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
                'name'				=> esc_html__('Disputes', 'workreap_core'),
                'all_items' 		=> esc_html__('Disputes', 'workreap_core'),
                'singular_name' 	=> esc_html__('Disputes', 'workreap_core'),
                'add_new' 			=> esc_html__('Add Dispute', 'workreap_core'),
                'add_new_item' 		=> esc_html__('Add New Dispute', 'workreap_core'),
                'edit' 				=> esc_html__('Edit', 'workreap_core'),
                'edit_item' 		=> esc_html__('Edit Dispute', 'workreap_core'),
                'new_item' 			=> esc_html__('New Dispute', 'workreap_core'),
                'view' 				=> esc_html__('View Dispute', 'workreap_core'),
                'view_item' 		=> esc_html__('View Dispute', 'workreap_core'),
                'search_items' 		=> esc_html__('Search Dispute', 'workreap_core'),
                'not_found' 		=> esc_html__('No Dispute found', 'workreap_core'),
                'not_found_in_trash'=> esc_html__('No Dispute found in trash', 'workreap_core'),
                'parent' 			=> esc_html__('Parent Dispute', 'workreap_core'),
            );
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new Dispute ', 'workreap_core'),
                'public' 				=> false,
                'supports' 				=> array('title','editor'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> false,
                'exclude_from_search' 	=> true,
                'hierarchical' 			=> false,
				'show_in_menu' 			=> 'edit.php?post_type=freelancers',
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'dispute', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array('create_posts' => false)
            );
            register_post_type('disputes', $args);     
        }

        /**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function linked_details_add_meta_boxes($post_type, $post) {
            $user_id        = get_post_meta($post->ID, '_send_by', true);
            $linked_profile = workreap_get_linked_profile_id( $user_id );
            
            if(empty($linked_profile)) {return;}

            add_meta_box(
                    'linked_profile', esc_html__('Linked Details', 'workreap_core'), array(&$this, 'linked_details_meta_box_print'), 'disputes', 'side', 'high'
            );

        }
        
        /**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function linked_details_meta_box_print($post) {
            $project_id             = get_post_meta($post->ID, '_project_id', true);
            $disputed_project_id    = get_post_meta($post->ID, '_dispute_project', true);
            $post_type              = get_post_type($disputed_project_id);
            $post_status            = get_post_status( $post->ID );

            if (!empty($post_type) && $post_type === 'proposals') {
                $title                       = esc_html__('View Project', 'workreap_core'); 
                $freelancer_id               = get_post_field('post_author', $disputed_project_id);
                $employer_id	             = get_post_field('post_author', $project_id);
                $linked_freelancer_profile	 = workreap_get_linked_profile_id($freelancer_id);
                $linked_employer_profile	 = workreap_get_linked_profile_id($employer_id);
                $proj_serv_id                = $project_id;
            } else if (!empty($post_type) && $post_type === 'services-orders') {
                $service_order_id            = $disputed_project_id;
                $title                       = esc_html__('View Service', 'workreap_core');
                $employer_id                 = get_post_field('post_author', $disputed_project_id);
                $linked_employer_profile	 = workreap_get_linked_profile_id($employer_id);
                $freelancer_id	             = get_post_meta( $disputed_project_id, '_service_author', true );
                $linked_freelancer_profile	 = workreap_get_linked_profile_id($freelancer_id);
                $proj_serv_id                = !empty($service_order_id) ? $service_order_id : '';
            }
            ?>
			<ul class="review-info">
                <li>
                    <span class="push-right">
                        <a target="_blank" href="<?php echo esc_url(get_edit_post_link( $disputed_project_id ));?>"><?php echo esc_html($title); ?></a>
                    </span>
                </li>
                <li>
                    <span class="push-right">
                        <a target="_blank" href="<?php echo esc_url(get_the_permalink($linked_freelancer_profile));?>"><?php esc_html_e('View Freelancer Profile', 'workreap_core'); ?></a>
                    </span>
                </li>
                <li>
                    <span class="push-right">
                        <a target="_blank" href="<?php echo esc_url(get_the_permalink( $linked_employer_profile ));?>"><?php esc_html_e('View Employer Profile', 'workreap_core'); ?></a>
                    </span>
                </li>
                <?php if(!empty($post_status) && $post_status != 'publish') { ?>
                    <li>
                        <span class="push-right">
                            <a href="#TB_inline?width=600&height=550&inlineId=dispute-content" rel="ajaxcontentarea" title="<?php echo esc_attr('Resolve Dispute', 'workreap_core'); ?>" class="thickbox"><?php esc_html_e('Resolve Dispute', 'workreap_core'); ?></a>
                        </span>
                    </li>
                <?php } ?>
			</ul>
            <div id="dispute-content" style="display:none;">
                <div class="wt-boxmycontent">
                    <h3><?php esc_html_e('Resolve Dispute', 'workreap_core'); ?></h3>
                    <form method="POST" class="resolve-dispute">
                        <p><?php echo  esc_html__('Please select one of the user who is winning party. An email will be sent to both parties with your message', 'workreap_core') ?></p>
                        <div class="input-radio">
                            <label>
                                <input  type="radio" id="employer" name="user_id" value="<?php echo esc_attr($employer_id); ?>" checked><?php esc_html_e('Employer', 'workreap_core'); ?>
                            </label>
                            <label>
                                <input  type="radio" id="freelancer" name="user_id" value="<?php echo esc_attr($freelancer_id); ?>"><?php esc_html_e('Freelancer', 'workreap_core'); ?>
                            </label>
                        </div>
                        <div class="input-textarea freelancer_message">
                            <p><?php echo  esc_html__('Write your message for freelancer', 'workreap_core') ?></p>
                            <textarea rows="6" cols="80" id="freelancer_msg" name="freelancer_msg" placeholder="<?php echo esc_attr('Your message', 'workreap_core'); ?>"></textarea>
                        </div>
                        <div class="input-textarea employer_message">
                            <p><?php echo  esc_html__('Write your message for employer', 'workreap_core') ?></p>
                            <textarea rows="6" cols="80" id="employer_msg" name="employer_msg" placeholder="<?php echo esc_attr('Your message', 'workreap_core'); ?>"></textarea>
                        </div>
                        <div class="wt-actionbtnss">
                            <p class="submit"><input type="submit" data-service_order_id="<?php echo esc_attr($proj_serv_id); ?>" data-freelancer-id="<?php echo esc_attr($freelancer_id); ?>" data-employer-id="<?php echo esc_attr($employer_id); ?>" data-dispute-id="<?php echo esc_attr($post->ID); ?>" data-dispute-project-id="<?php echo esc_attr($disputed_project_id); ?>" data-proj-serv-id="<?php echo esc_attr($proj_serv_id); ?>" name="submit" id="submit" class="button button-primary wt-btn wt-attachmentbtn resolve-dispute-btn" value="<?php esc_html_e('Submit', 'workreap_core'); ?>"></p>
                        </div>
                    </form>
                </div>
            </div>
			<?php
        }

        /**
		 * @Linked Chat metabox
		 * @return {post}
		 */
		public function messages_add_meta_box($post_type, $post) {
			if ($post_type === 'disputes') {
				add_meta_box(
					'workreap_chat',
					esc_html__('Project History', 'workreap_core'),
					array(&$this, 'messages_meta_box_print'),
					$post_type,
					'advanced',
					'high'
				);
			}
        }
        
        /**
		 * @Linked chat metabox
		 * @return {post}
		 */
		public function messages_meta_box_print($post) {
            $dispute_project    = get_post_meta( $post->ID, '_project_id', true );
            $project_id			= !empty($dispute_project) ? $dispute_project : '';
            $post_type			= get_post_type($project_id);

            if( !empty( $post_type ) && $post_type === 'services-orders') {
                $proposal_id		= get_post_meta($post->ID,'_dispute_project',true);
                $edit_id		    = get_post_meta($post->ID,'_project_id',true);
                $service_id			= get_post_meta( $edit_id, '_service_id', true);
                $post_status		= get_post_field('post_status',$proposal_id);
                $post_comment_id	= $edit_id;
            } else if( !empty( $post_type ) && $post_type === 'proposals') {	
                $proposal_id		= $edit_id;
                $post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
                $project_id			= get_post_meta( $proposal_id, '_project_id', true);
                $post_status		= get_post_field('post_status',$project_id);
            } else {
                $proposal_id		= get_post_meta($post->ID,'_dispute_project',true);
                $edit_id		    = get_post_meta($post->ID,'_project_id',true);
                $post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
                $post_status		= get_post_field('post_status',$edit_id);
            }

            $args 				= array(
                                    'post_id' => $post_comment_id
                                );

            $comments 			= get_comments( $args );

            if (!empty($post_comment_id)) { ?>
                <div class="wt-haslayout wo-project-history">                    
                    <div class="wt-historycontent">
                        <?php if (!empty($comments)) { ?>
                            <ul class="wt-historycontentcol">
                                <li class="wt-historycolhead">
                                    <h3>
                                        <span><?php esc_html_e('Date', 'workreap_core'); ?></span>
                                        <span><?php esc_html_e('Attachment', 'workreap_core'); ?></span>
                                    </h3>
                                </li>	
                                <?php
                                $counter = 0;
                                foreach ($comments as $key => $value) {
                                    $counter++;
                                    $date 			= !empty($value->comment_date) ? $value->comment_date : '';
                                    $user_id 		= !empty($value->user_id) ? $value->user_id : '';
                                    $comments_ID 	= !empty($value->comment_ID) ? $value->comment_ID : '';
                                    $message 		= $value->comment_content;
                                    $date 			= !empty($date) ? date_i18n('F j, Y', strtotime($date)) : '';

                                    if (apply_filters('workreap_get_user_type', $user_id) === 'employer') {
                                        $employer_post_id   		= workreap_get_linked_profile_id($user_id);
                                        $avatar = apply_filters(
                                                'workreap_employer_avatar_fallback',
                                                workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_post_id),
                                                array('width' => 100, 'height' => 100)
                                            );
                                    } else {
                                        $freelancer_post_id   		= workreap_get_linked_profile_id($user_id);
                                        $avatar = apply_filters(
                                                'workreap_freelancer_avatar_fallback',
                                                workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_post_id),
                                                array('width' => 100, 'height' => 100)
                                            );
                                    }
                                        
                                    $username 		= workreap_get_username($user_id);
                                    $project_files  = get_comment_meta($value->comment_ID, 'message_files', true); ?>
                                    <li class="collapsed">
                                        <div class="wt-dateandmsg">
                                            <span><img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($username); ?>"></span>
                                            <span>
                                                <?php if (!empty($date)) {echo esc_attr($date);} ?>
                                                <?php echo do_shortcode($message); ?>
                                            </span>
                                            <div class="wt-actionbtns">
                                                <a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-msgbtn"><i class="lnr lnr-chevron-up"></i><?php esc_html_e('Messages', 'workreap_core'); ?></a>
                                                <?php if (!empty($project_files)) { ?>
                                                        <a href="#" onclick="event_preventDefault(event);" data-id="<?php echo esc_attr($comments_ID); ?>" class="wt-btn wt-attachmentbtn wt-download-attachment"><i class="lnr lnr-download"></i><?php esc_html_e('Attachment(s)', 'workreap_core'); ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="wt-historydescription collapse" id="collapse<?php echo esc_attr($counter); ?>" data-parent="#accordion">
                                            <div class="wt-description">
                                                <p><?php echo do_shortcode($message); ?></p>
                                            </div>									
                                        </div>
                                    </li>
                                <?php
                                } ?>
                            </ul>
                        <?php } else {
                            do_action('workreap_empty_records_html', 'wt-empty-projects', esc_html__('No history found.', 'workreap_core'));
                        } ?>
                    </div>
                </div>
                <?php
            } else {
                do_action('workreap_empty_records_html', 'wt-empty-projects', esc_html__('No history found.', 'workreap_core'));
            }
        }
    
    }

    new Workreap_Dispute();
}
