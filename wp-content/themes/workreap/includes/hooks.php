<?php
/**
 *
 * Custom Hooks
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
/**
 * @get next post
 * @return link
 */
if (!function_exists('workreap_next_post')) {

    function workreap_next_post($format) {
        $format = str_replace('href=', 'class="btn-prevpost fa fa-arrow-left" href=', $format);
        return $format;
    }

    add_filter('next_post_link', 'workreap_next_post');
}

/**
 * @pages links before after
 * @return link
 */
if (!function_exists('workreap_pages_link_before_after')) {
	function workreap_pages_link_before_after( $link ){
		return '<li>' . $link . '</li>';
	}
	add_filter( 'wp_link_pages_link',  'workreap_pages_link_before_after' );
}

/**
 * @get next post
 * @return link
 */
if (!function_exists('workreap_previous_post')) {

    function workreap_previous_post($format) {
        $format = str_replace('href=', 'class="btn-nextpost fa fa-arrow-right" href=', $format);
        return $format;
    }

    add_filter('previous_post_link', 'workreap_previous_post');
}


/**
 * @Naigation Filter
 * @return {sMenu Item class}
 */
if (!function_exists('workreap_add_menu_parent_class')) {
    add_filter('wp_nav_menu_objects', 'workreap_add_menu_parent_class');

    function workreap_add_menu_parent_class($items) {
        $parents = array();
        foreach ($items as $item) {
            if ($item->menu_item_parent && $item->menu_item_parent > 0) {
                $parents[] = $item->menu_item_parent;
            }
        }
        foreach ($items as $item) {
            if (in_array($item->ID, $parents)) {
                $item->classes[] = 'dropdown';
            }
        }
        return $items;
    }

}

/**
 * @get custom Excerpt
 * @return link
 */
if (!function_exists('workreap_prepare_custom_excerpt')) {

    function workreap_prepare_custom_excerpt($more = '...') {
        return '....';
    }

    add_filter('excerpt_more', 'workreap_prepare_custom_excerpt');
}

/**
 * @Change Reply link Class
 * @return sizes
 */
if (!function_exists('workreap_replace_reply_link_class')) {
    add_filter('comment_reply_link', 'workreap_replace_reply_link_class');

    function workreap_replace_reply_link_class($class) {
        $class = str_replace("class='comment-reply-link'", "class='comment-reply-link wt-btnreply'", $class);
        return $class;
    }

}

/**
 * @Section wraper before
 * @return 
 */
if (!function_exists('workreap_prepare_section_wrapper_before')) {

    function workreap_prepare_section_wrapper_before() {
        echo '<div class="main-page-wrapper">';
    }

    add_action('workreap_prepare_section_wrapper_before', 'workreap_prepare_section_wrapper_before');
}

/**
 * @Section wraper after
 * @return 
 */
if (!function_exists('workreap_prepare_section_wrapper_after')) {

    function workreap_prepare_section_wrapper_after() {
        echo '</div>';
    }

    add_action('workreap_prepare_section_wrapper_after', 'workreap_prepare_section_wrapper_after');
}


/**
 * @Post Classes
 * @return 
 */
if (!function_exists('workreap_post_classes')) {

    function workreap_post_classes($classes, $class, $post_id) {
        //Add Your custom classes
        return $classes;
    }

    add_filter('post_class', 'workreap_post_classes', 10, 3);
}
/**
 * @Add Body Class
 * @return 
 */
if (!function_exists('workreap_content_classes')) {

    function workreap_content_classes($classes) {
		global $current_user;
        if (is_singular()) {
            $_post = get_post();
            if ($_post != null) {
                if ($_post && preg_match('/vc_row/', $_post->post_content)) {
                    $classes[] = 'vc_being_used';
                }
            }
        }

        //check if maintenance is enable
        if (function_exists('fw_get_db_settings_option')) {
            $maintenance = fw_get_db_settings_option('maintenance');
            $body_bg_color = fw_get_db_settings_option('body_bg_color');
        }

        $post_name = workreap_get_post_name();
        
        if (( isset($maintenance) && $maintenance == 'enable' && !is_user_logged_in() ) || $post_name == "coming-soon"
        ) {
            $classes[] = 'wt-comingsoon-page';
        }

        if (class_exists('Woocommerce') && is_woocommerce() && is_shop()) {
            $classes[] = 'wt-shop-page';
        }
		
		//add dashboard class
		if (is_page_template('directory/dashboard.php')) {
			$classes[] = 'wt-dashboard';
		}
		
		if (function_exists('fw_get_db_settings_option')) {
			$login_register = fw_get_db_settings_option('enable_login_register');
			$db_left_menu 	= fw_get_db_settings_option( 'db_left_menu', $default_value = null );
		} 
		
        if( apply_filters('workreap_show_packages_if_expired',$current_user->ID) === true
		    && apply_filters('workreap_is_listing_free',false,$current_user->ID) === false ){
            $db_left_menu = 'yes';
        }

		$is_auth			= !empty($login_register['gadget']) ? $login_register['gadget'] : ''; 
		
		if( $is_auth === 'disable' ){
			$classes[] = 'admin-bar';
		} else{
			$user_type		= apply_filters('workreap_get_user_type', $current_user->ID );
			if (is_user_logged_in() && ( $user_type === 'employer' || $user_type === 'freelancer' ) ) {
				$classes[] = 'registration-enabled';
			} else if ( !is_user_logged_in() ) {
				$classes[] = 'registration-enabled';
			}
		}
		
		if (function_exists('fw_get_db_settings_option')) {
			$header_type = fw_get_db_settings_option('header_type');
			$body_bg_color = fw_get_db_settings_option('body_bg_color');
		}
		
		if ( apply_filters('workreap_get_domain',false) === true ) {
			$post_name = workreap_get_post_name();
			if( $post_name === "home-page-three" ){
				$header_type['gadget'] = 'header_v3';
			}
		}
		
		if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v2' ){
			$classes[] = 'header-variation-two';
		} else if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v3' ){
            $classes[] = 'header-variation-three';
        }  else if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v4' ){
            $classes[] = 'header-variation-four';
        } else if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v5' ){
            $classes[] = 'header-variation-five';
        } else if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v6' ){
            $classes[] = 'header-variation-six';
        } else{
			$classes[] = 'default-header';
		}
		
		if( isset( $db_left_menu ) && $db_left_menu === 'yes' ){
			$classes[] = 'left-menu-yes';
		} else{
			$classes[] = 'left-menu-no';
		}
		
		if(empty($body_bg_color) || ( isset($body_bg_color) && $body_bg_color === '#ffffff' ) ){
			$classes[] = 'wtbody-white';
		}else if (!empty($body_bg_color) && ( $body_bg_color != '#ffffff' || $body_bg_color != '#fff' ) ) {
            $classes[] = 'wtbody-dark';
        }
			

        return $classes;
    }

    add_filter('body_class', 'workreap_content_classes', 1);
}

/**
 * @Remove VC Classes
 * @return 
 */
if (!function_exists('workreap_classes_for_vc_row_and_vc_column')) {

    function workreap_classes_for_vc_row_and_vc_column($class_string, $tag) {
        if ($tag == 'vc_row' || $tag == 'vc_row_inner') {
            $class_string = preg_replace('/vc_row/', 'wt-elm-section vc-workreap-section', $class_string);
            $class_string = $class_string . ' wt-elm-section';
        }

        return $class_string; // Important: you should always return modified or original $class_string
    }

    add_filter('vc_shortcodes_css_class', 'workreap_classes_for_vc_row_and_vc_column', 10, 2);
}


/**
 * Add theme version to admin footer
 * @return CSS
 */
if (!function_exists('add_workreap_version_to_footer_admin')) {

    function add_workreap_version_to_footer_admin($html) {
		$theme_version 	  = wp_get_theme('workreap');
        $workreap_version = $theme_version->get('Version');
        $workreap_name = '<a href="' . esc_url($theme_version->get('AuthorURI')) . '" target="_blank">' . $theme_version->get('Name') . '</a>';

        return ( empty($html) ? '' : $html . ' | ' ) . $workreap_name . ' ' . $workreap_version;
    }

    if (is_admin()) {
        add_filter('update_footer', 'add_workreap_version_to_footer_admin', 13);
    }
}


/**
 * @Admin Menu 
 * @return 
 */
if (!function_exists('workreap_theme_options')) {
	//add_action('admin_bar_menu', 'workreap_theme_options', 1000);
	function workreap_theme_options(){
		global $wp_admin_bar;
		if(!is_super_admin() || !is_admin_bar_showing()) return;
		
		$url = admin_url();
		if ( function_exists('fw_get_db_post_option') ) {
			// Add Parent Menu
			$argsParent	= array(
				'id' 		=> 'workreap_setup',
				'title' 	=> esc_html__('Workreap Theme Settings','workreap'),
				'href' 		=> $url.'themes.php?page=fw-settings',
			);

			$wp_admin_bar->add_node( $argsParent );	
		}
	}
}

/**
 * @Product Image 
 * @return {}
 */
if (!function_exists('workreap_prepare_post_thumbnail')) {

    function workreap_prepare_post_thumbnail($object, $atts) {
        extract(shortcode_atts(array(
            "width" => '300',
            "height" => '300',
                        ), $atts));

        if (isset($object) && !empty($object)) {
            return $object;
        } else {
            $object_url = get_template_directory_uri() . '/images/fallback-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
            return '<img width="' . intval( $width ) . '" height="' . intval( $height ) . '" src="' . esc_url($object_url) . '" alt="' . esc_attr__('Placeholder', 'workreap') . '">';
        }
    }

    add_filter('workreap_prepare_post_thumbnail', 'workreap_prepare_post_thumbnail', 10, 3);
}

/**
 * @ Prevoius Links
 * @return 
 */
if (!function_exists('workreap_do_process_next_previous_link')) {

    function workreap_do_process_next_previous_link($post_type = 'post') {
        global $post;
        $prevous_post_id = $next_post_id = '';
        $post_type 		 = get_post_type($post->ID);
        $count_posts 	 = wp_count_posts($post_type)->publish;
		
        $args = array(
            'posts_per_page' 	=> -1,
            'order' 			=> 'ASC',
            'post_type' 		=> $post_type,
        );

        $all_posts = get_posts($args);

        $ids = array();
        foreach ($all_posts as $current_post) {
            $ids[] = $current_post->ID;
        }
		
        $current_index = array_search($post->ID, $ids);

        if (isset($ids[$current_index - 1])) {
            $prevous_post_id = $ids[$current_index - 1];
        }

        if (isset($ids[$current_index + 1])) {
            $next_post_id = $ids[$current_index + 1];
        }
        ?>
        <ul class="wt-postnav">
            <?php
            if (isset($prevous_post_id) && !empty($prevous_post_id) && $prevous_post_id >= 0) {
                $prev_thumb = workreap_prepare_thumbnail_from_id($prevous_post_id, 71, 71);
                if (empty($prev_thumb)) {
                    $prev_thumb = get_template_directory_uri() . '/images/img-71x71.jpg';
                }
                ?>
                <li class="wt-postprev">
                    <article class="wt-themepost th-thumbpost">
                        <figure class="wt-themepost-img">
                            <a href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>"><img alt="<?php echo esc_attr(get_the_title($next_post_id)); ?>" src="<?php echo esc_url($prev_thumb); ?>"></a>
                        </figure>
                        <div class="wt-contentbox">
                            <a class="wt-btnprevpost" href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>"><?php esc_html_e('previous post', 'workreap'); ?></a>
                            <div class="wt-posttitle">
                                <h2><a href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>"><?php echo esc_html(get_the_title($next_post_id)); ?></a></h2>
                            </div>
                        </div>
                    </article>
                </li>

            <?php } ?>
            <?php
            if (isset($next_post_id) && !empty($next_post_id) && $next_post_id >= 0) {
                $next_thumb = workreap_prepare_thumbnail_from_id($next_post_id, 71, 71);

                if (empty($next_thumb)) {
                    $next_thumb = get_template_directory_uri() . '/images/img-71x71.jpg';
                }
                ?>
                <li class="wt-postnext">
                    <article class="wt-themepost wt-thumbpost">
                        <figure class="wt-themepost-img"> 
                            <a href="<?php echo esc_url(get_permalink($next_post_id)); ?>"><img alt="<?php echo esc_attr(get_the_title($next_post_id)); ?>" src="<?php echo esc_url($next_thumb); ?>"></a> 
                        </figure>
                        <div class="wt-contentbox"> 
                            <a class="wt-btnnextpost" href="<?php echo esc_url(get_permalink($next_post_id)); ?>"><?php esc_html_e('Next post', 'workreap'); ?></a>
                            <div class="wt-posttitle">
                                <h2><a href="<?php echo esc_url(get_permalink($next_post_id)); ?>"><?php echo esc_html(get_the_title($next_post_id)); ?></a></h2>
                            </div>
                        </div>
                    </article>
                </li>
            <?php } ?>
        </ul>
        <?php
        wp_reset_postdata();
    }

    add_action('do_process_next_previous_link', 'workreap_do_process_next_previous_link');
}

/**
 * @ Next/Prevoius Products
 * @return 
 */
if (!function_exists('workreap_do_process_next_previous_product')) {

    function workreap_do_process_next_previous_product() {
        global $post;

        $post_type 			= 'product';
        $prevous_post_id 	= $next_post_id = '';
        $post_type 			= get_post_type($post->ID);
        $count_posts 		= wp_count_posts($post_type)->publish;
        $args = array(
            'posts_per_page' => -1,
            'post_type' => $post_type,
        );

        $all_posts = get_posts($args);

        $ids = array();
        foreach ($all_posts as $current_post) {
            $ids[] = $current_post->ID;
        }
		
        $current_index = array_search($post->ID, $ids);

        if (isset($ids[$current_index - 1])) {
            $prevous_post_id = $ids[$current_index - 1];
        }

        if (isset($ids[$current_index + 1])) {
            $next_post_id = $ids[$current_index + 1];
        }
        ?>
        <div class="wt-nextprevpost">
            <?php if (isset($prevous_post_id) && !empty($prevous_post_id) && $prevous_post_id >= 0) { ?>
                <div class="wt-btnprevpost">
                    <a href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>">
                        <i class="lnr lnr-chevron-left"></i>
                        <div class="wt-booknameandtitle">
                            <h3><?php echo esc_html(get_the_title($next_post_id)); ?></h3>
                        </div>
                    </a>
                </div>
            <?php } ?>
            <?php if (isset($next_post_id) && !empty($next_post_id) && $next_post_id >= 0) { ?>
                <div class="wt-btnnextpost">
                    <a href="<?php echo esc_url(get_permalink($next_post_id)); ?>">
                        <div class="wt-booknameandtitle">
                            <h3><?php echo esc_html(get_the_title($next_post_id)); ?></h3> 
                        </div>
                        <i class="lnr lnr-chevron-right"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
        <?php
        wp_reset_postdata();
    }

    add_action('workreap_do_process_next_previous_product', 'workreap_do_process_next_previous_product');
}

/**
 * @IE Compatibility
 * @return 
 */
if (!function_exists('workreap_ie_compatibility')) {

    function workreap_ie_compatibility() {
        ?>
        <!--[if lt IE 9]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php
    }

    add_action('workreap_ie_compatibility', 'workreap_ie_compatibility');
}


/**
 * @Fallback Image 
 * @return {}
 */
if (!function_exists('workreap_get_fallback_image')) {

    function workreap_get_fallback_image($object, $atts = array()) {
        extract(shortcode_atts(array(
            "width" => '300',
            "height" => '300',
                        ), $atts));

        if (isset($object) && !empty($object) && $object != NULL
        ) {
            return $object;
        } else {
            return get_template_directory_uri() . '/images/fallback' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
        }
    }

    add_filter('workreap_get_fallback_image', 'workreap_get_fallback_image', 10, 3);
}

/**
 * Enqueue Unyson Icons CSS
 */
if (!function_exists('enqueue_unyson_icons_css')) {

    function enqueue_unyson_icons_css() {
        /**
         * Detect plugin.
         */
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if (function_exists('fw_get_db_post_option')) {
            fw()->backend->option_type('icon-v2')->packs_loader->enqueue_frontend_css();
        }
    }

    add_action('enqueue_unyson_icon_css', 'enqueue_unyson_icons_css');
}


/**
 * @Filter to return Default image if no image found.
 * @return {}
 */
if (!function_exists('workreap_get_media_fallback')) {

    function workreap_get_media_fallback($object, $atts = array()) {
        extract(shortcode_atts(array(
            "width" => '150',
            "height" => '150',
                        ), $atts));

        if (isset($object) && !empty($object) && $object != NULL
        ) {
            return $object;
        } else {
			return get_template_directory_uri() . '/images/img-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
        }
    }

    add_filter('workreap_get_media_filter', 'workreap_get_media_fallback', 10, 3);
}

/**
 * @schema data
 * @return schema data
 */
if(!function_exists('workreap_print_schema_tags')){
    function workreap_print_schema_tags($data = array()){
        if ( !empty( $data ) ) {            
            echo '<script type="application/ld+json">';
            echo json_encode($data);
            echo  '</script>';
        }
    }
    add_action( 'print_schema_tags', 'workreap_print_schema_tags', 10, 1 );
}


/**
 * @non strict characters allow
 * @return allow non strict characters
 */
if( !function_exists( 'workreap_allow_non_strict_login' ) ) {    

    function workreap_allow_non_strict_login( $username, $raw_username, $strict ) {

        if( !$strict )
        return $username;
        return sanitize_user(stripslashes($raw_username), false);
    }

    add_filter('sanitize_user', 'workreap_allow_non_strict_login', 10, 3);
}