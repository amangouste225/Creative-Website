<?php
/**
 *
 * Workreap function for menu
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfoliot
 * @since 1.0
 */
if (!class_exists('Workreap_Profile_Menu')) {

    class Workreap_Profile_Menu {

        protected static $instance = null;

        public function __construct() {
            //Do something
        }

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

		/**
		 * Profile Menu top
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_profile_menu_top() {
            global $current_user, $wp_roles, $userdata, $post;
            ob_start();
            $username 		= workreap_get_username($current_user->ID);
			$user_identity 	= $current_user->ID;
			$user_type		= apply_filters('workreap_get_user_type', $user_identity );
			$link_id		= workreap_get_linked_profile_id( $user_identity );
			
			if (function_exists('fw_get_db_post_option')) {
				$tag_line = fw_get_db_post_option($link_id, 'tag_line', false);
			}
			
			if ( $user_type === 'employer' ){
				$avatar = apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 50, 'height' => 50), $link_id), array('width' => 50, 'height' => 50) 
									);
			} else{
				$avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 50, 'height' => 50), $link_id), array('width' => 50, 'height' => 50) 
									);
			}
			$is_cometchat 	= false;
			$is_wpguppy 	= false;
			if (function_exists('fw_get_db_settings_option')) {
				$chat_api = fw_get_db_settings_option('chat');
				if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'cometchat') {
					$is_cometchat = true;
				}elseif (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
					$is_wpguppy = true;
				}
			}

			if( $user_type === 'employer' || $user_type === 'freelancer' || $user_type === 'subscriber' ) {?>
				
				<div class="wt-userlogedin sp-top-menu menu-item-has-children">
					<?php if (is_user_logged_in()) {?>
						<?php 
							if( !empty($is_wpguppy) && apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $user_identity) === true && in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins')))){ ?>
								<div class="notify-wrap-icon">
									<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('chat', $user_identity); ?>"><i class="ti-email"></i><span class="notify-counter"><?php echo apply_filters('wpguppy_count_all_unread_messages', $user_identity );?></span></a>
								</div>
						<?php } ?>
						<div class="notify-wrap-icon wt-notify-<?php do_action('workreap_count_unread_push_notification');?>" data-notify="<?php do_action('workreap_count_unread_push_notification');?>">
							<a href="#" onclick="event_preventDefault(event);"><i class="ti-bell"></i><span class="notify-counter"><?php do_action('workreap_count_unread_push_notification');?></span></a>
							<div class="notify-wrap-start dropdown-menu-right">
								<h3><?php esc_html_e('Notifications', 'workreap'); ?><span><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('notification', $user_identity); ?>"><?php esc_html_e('View all', 'workreap'); ?></a></span></h3>
								<ul class="notification-menu wt-verticalscrollbar">
									<?php do_action('workreap_push_notification_listings');?>
								</ul>
							</div>
						</div>
					<?php }?>
					<div class="avatar-wrap-icon">
						<figure class="wt-userimg">
							<img src="<?php echo esc_url($avatar); ?>" alt="<?php esc_attr_e('Profile Avatar', 'workreap'); ?>">
						</figure>
						<div class="wt-username">
							<h3><?php echo esc_html($username); ?></h3>
							<?php if( !empty( $tag_line ) ){?>
								<span><?php echo esc_html(stripslashes($tag_line)); ?></span>
							<?php }?>
						</div>
						<nav class="wt-usernav">
							<?php self::workreap_profile_menu('dashboard-menu-top'); ?>
						</nav>
					</div>
				</div>
            <?php
			}
			
            $data	= ob_get_clean();
			
			echo apply_filters( 'workreap_profile_menu_top', $data );
        }

		/**
		 * Profile Menu Left
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_profile_menu_left() {
            global $current_user, $wp_roles, $userdata, $post;
			if ( function_exists( 'fw_get_db_settings_option' ) ) {
				$db_left_menu 	= fw_get_db_settings_option( 'db_left_menu', $default_value = null );
			} 

			if( apply_filters('workreap_show_packages_if_expired',$current_user->ID) === true
				&& apply_filters('workreap_is_listing_free',false,$current_user->ID) === false ){
				$db_left_menu = 'yes';
			}

			if( isset( $db_left_menu ) && $db_left_menu === 'no' ){
				ob_start();
				?>
				<div class="wt-sidebarwrapper">
					<div id="wt-btnmenutoggle" class="wt-btnmenutoggle">
						<span class="menu-icon">
							<em></em>
							<em></em>
							<em></em>
						</span>
					</div>
					<div id="wt-verticalscrollbar" class="wt-verticalscrollbar">
						<?php self::workreap_do_process_userinfo(); ?>
						<nav id="wt-navdashboard" class="wt-navdashboard">
							<?php self::workreap_profile_menu('dashboard-menu-left'); ?>
						</nav>
					</div>
				</div>
				<?php
				$data	= ob_get_clean();
				
				echo apply_filters( 'workreap_profile_menu_left', $data );
			}
        }

		/**
		 * Profile Menu
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_profile_menu($menu_type = "dashboard-menu-left") {
            global $current_user, $wp_roles, $userdata, $post;
			$reference 		 	= (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
			$mode 			 	= (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
			$user_identity 	 	= intval($current_user->ID);
			$arg				= array();
			$arg['menu_type']	= $menu_type;
			$url_identity = $user_identity;
			if (isset($_GET['identity']) && !empty($_GET['identity'])) {
				$url_identity = intval($_GET['identity']);
			}
			
			if ( function_exists( 'fw_get_db_settings_option' ) ) {
				if(!empty($menu_type) && $menu_type == 'dashboard-menu-left'){
					$hide_menus 	= fw_get_db_settings_option( 'hide_left_menus', $default_value = null );
				}else{
					$hide_menus 	= fw_get_db_settings_option( 'hide_top_menus', $default_value = null );
				}
				
			} 

			$menu_list 	= workreap_get_dashboard_menu();

            ob_start();
            ?>
            <ul class="<?php echo sanitize_html_class($menu_type); ?>">
                <?php
					if ( $url_identity == $user_identity ) {
						if( !empty( $menu_list ) ){
							foreach($menu_list as $key => $value){
								if( !empty($menu_type) && $menu_type == 'dashboard-menu-left' && !empty($value['hide_left']) && $value['hide_left'] == 'no' ){
									//do nothing
								} else {
									if( !empty( $value['type'] ) && ( $value['type'] == apply_filters('workreap_get_user_type', $user_identity ) ) ){
										if( !empty($menu_type) && $menu_type == 'dashboard-menu-top' && !empty($value['hide_top']) && $value['hide_top'] == 'no' ){
											//do nothing
										} else{
											if(!empty($hide_menus) && in_array($key,$hide_menus) ){
												continue;	
											}
											
											get_template_part('directory/front-end/dashboard-menu-templates/'.$value['type'].'/profile-menu', $key,$arg);
										}
									} else{
										if( !empty($menu_type) && $menu_type == 'dashboard-menu-top' && !empty($value['hide_top']) && $value['hide_top'] == 'no' ){
											//do nothing
										} else{
											if(!empty($hide_menus) && in_array($key,$hide_menus) ){
												continue;	
											}
											get_template_part('directory/front-end/dashboard-menu-templates/profile-menu', $key,$arg);
										}
									}
								}
							}
						}
					}else{
						get_template_part('directory/front-end/dashboard-menu-templates/profile-menu', 'dashboard');
					} 
                ?>
            </ul>
            <?php
            $data	= ob_get_clean();
				
			echo apply_filters( 'workreap_profile_menu', $data );
        }

		/**
		 * Generate Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_profile_menu_link($slug = '', $user_identity = '', $return = false, $mode = '', $id = '') {
			$profile_page = ''; 
			$profile_page = workreap_get_search_page_uri('dashboard');  
			
            if ( empty( $profile_page ) ) {
                $permalink = home_url('/');
            } else {
                $query_arg['ref'] = urlencode($slug);

                //mode
                if (!empty($mode)) {
                    $query_arg['mode'] = urlencode($mode);
                }
				
                //id for edit record
                if (!empty($id)) {
                    $query_arg['id'] = urlencode($id);
                }

                $query_arg['identity'] = urlencode($user_identity);

                $permalink = add_query_arg(
                        $query_arg, esc_url( $profile_page  )
                );
				
            }

            if ($return) {
                return esc_url_raw($permalink);
            } else {
                echo esc_url_raw($permalink);
            }
        }

		/**
		 * Generate Profile Avatar Image Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_get_avatar() {
            global $current_user, $wp_roles, $userdata, $post;
            $user_identity 	= $current_user->ID;
			$user_type		= apply_filters('workreap_get_user_type', $user_identity );
			$link_id		= workreap_get_linked_profile_id( $user_identity );
			
			$width			= 150;
			$height			= 150;
			if ( apply_filters('workreap_get_user_type', $user_identity) === 'employer' ){
				
				$avatar = apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => $width, 'height' => $height), $link_id), array('width' => $width, 'height' => $height) 
									);
			} else{
				$avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => $width, 'height' => $height), $link_id), array('width' => $width, 'height' => $height) 
									);
			}
			
            ?>
            <figure><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_attr_e('avatar', 'workreap'); ?>"></figure>
            <?php
        }

		/**
		 * Generate Profile Banner Image Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_get_banner() {
            global $current_user, $wp_roles, $userdata, $post;

            $user_identity 	= $current_user->ID;
			$user_type		= apply_filters('workreap_get_user_type', $user_identity );
			$link_id		= workreap_get_linked_profile_id($user_identity );
			if ( apply_filters('workreap_get_user_type', $user_identity) === 'employer' ){
				$banner = apply_filters(
										'workreap_employer_banner_fallback', workreap_get_employer_banner(array('width' => 352, 'height' => 200), $link_id), array('width' => 352, 'height' => 200) 
										);
			} else{
				$banner = apply_filters(
										'workreap_freelancer_banner_fallback', workreap_get_freelancer_banner(array('width' => 352, 'height' => 200), $link_id), array('width' => 352, 'height' => 200) 
										);
			}
            ?>
            
            <figure class="wt-companysimg"><img src="<?php echo esc_url( $banner );?>" alt="<?php esc_attr_e('banner', 'workreap'); ?>"></figure>
            <?php
        }
		
		/**
		 * Generate Profile Information
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_get_user_info() {
            global $current_user;
            $user_identity = $current_user->ID;
            $user_identity = $user_identity;
            if (isset($_GET['identity']) && !empty($_GET['identity'])) {
                $user_identity = $_GET['identity'];
            }
			
			$link_id		= workreap_get_linked_profile_id( $user_identity );
			if (function_exists('fw_get_db_post_option')) {
				$tag_line = fw_get_db_post_option($link_id, 'tag_line', false);
			}
			
            $get_username 	= workreap_get_username($user_identity);
            ?>
            <div class="wt-title">
				<?php if (!empty($get_username)) { ?><h2><a target="_blank" href="<?php echo esc_url(get_the_permalink($link_id));?>"><?php echo esc_html($get_username); ?></a></h2><?php } ?>
				<?php if (!empty($tag_line)) { ?>
					<span><?php echo esc_html(stripslashes($tag_line)); ?></span>
				<?php } ?>
			</div>
            <?php
        }
		
		/**
		 * Get user info
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function workreap_do_process_userinfo() {
            global $current_user, $wp_roles, $userdata, $post;
            $reference 		= (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : $reference = '';
            $user_identity	= $current_user->ID;
			$link_id		= workreap_get_linked_profile_id($user_identity);
			workreap_return_system_access();
            ?>
            <div class="wt-companysdetails wt-usersidebar">
				<?php self::workreap_get_banner(); ?>
				<div class="wt-companysinfo">
					<?php self::workreap_get_avatar(); ?>
					<?php self::workreap_get_user_info(); ?>
					<?php if ( apply_filters('workreap_get_user_type', $user_identity) === 'employer' ){
						if( apply_filters('workreap_system_access','job_base') === true ){?>
						<div class="wt-btnarea"><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('post_job', $user_identity); ?>" class="wt-btn"><?php esc_html_e('Post a Job', 'workreap'); ?></a></div>
					<?php }}  elseif ( apply_filters('workreap_get_user_type', $user_identity) === 'freelancer' ) {
								if( apply_filters('workreap_module_access', 'projects') ){ ?>
									<div class="wt-btnarea"><a href="<?php echo esc_url(get_the_permalink( $link_id ) );?>" class="wt-btn"><?php esc_html_e('View Profile', 'workreap'); ?></a></div>
								<?php }?>
								<?php if ( apply_filters('workreap_system_access', 'service_base') === true) { ?>
									<div class="wt-btnarea">
										<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('micro_service', $user_identity); ?>" class="wt-btn"><?php esc_html_e('Post a Service', 'workreap'); ?></a>
									</div>
							<?php } ?>
					<?php } ?>
					<?php do_action('workreap_profile_strength_html',$link_id,true);?>		
				</div>
			</div>
            <?php
        }

    }

    new Workreap_Profile_Menu();
}
