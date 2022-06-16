<?php
/**
 *
 * General Theme Functions
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfoliot
 * @since 1.0
 */

/**
 * @Add Images Sizes
 * @return sizes
 */
add_image_size('workreap_freelancer_banner', 1920, 400, true); 
add_image_size('workreap_blog_large', 1140, 400, true); 
add_image_size('workreap_blog_small', 730, 240, true); 
add_image_size('workreap_service_details', 670, 370, true);
add_image_size('workreap_classic_grid', 540, 240, true); 
add_image_size('workreap_blog_grid', 355, 352, true);
add_image_size('workreap_service', 352, 200, true);
add_image_size('workreap_top_freelancer_v2', 320, 220, true);
add_image_size('workreap_freelancers_v3', 290, 215, true);
add_image_size('workreap_portfolio_thumbnail', 235, 149, true);
add_image_size('workreap_freelancer', 225, 225, true);

/**
 * @Init Pagination Code Start
 * @return 
 */
if (!function_exists('workreap_prepare_pagination')) {

    function workreap_prepare_pagination($pages = '', $range = 4) {
        $max_num_pages	= !empty($pages) && !empty($range) ? ceil($pages/$range) : 1;
		
		$big            = 999999999; 
        $pagination = paginate_links( array(
            'base'       => str_replace( $big, '%#%', get_pagenum_link( $big,false ) ),
            'format'     => '?paged=%#%',
            'type'       => 'array',
            'current'    => max( 1, get_query_var('paged') ),
            'total'      => $max_num_pages,
            'prev_text'  => '<i class="lnr lnr-chevron-left"></i>',
            'next_text'  => '<i class="lnr lnr-chevron-right"></i>',
        ) );
		
        ob_start();
        if ( ! empty( $pagination ) ) { ?>
            <div class='wt-paginationvtwo'>
				<nav class="wt-pagination">					
					<ul>
						<?php
							foreach ( $pagination as $key => $page_link ) {
								$link           = htmlspecialchars($page_link);
								$link           = str_replace( ' current', '', $link);
								$activ_class    = '';
								
								if ( strpos( $page_link, 'current' ) !== false ) { 
									$activ_class    = 'class="wt-active"'; 
								} else if ( strpos( $page_link, 'next' ) !== false ) { 
									$activ_class    = 'class="wt-nextpage"'; 
								} else if ( strpos( $page_link, 'prev' ) !== false ) { 
									$activ_class    = 'class="wt-prevpage"'; 
								}
							?>
								<li <?php echo do_shortcode($activ_class);?> > <?php echo wp_specialchars_decode($link,ENT_QUOTES); ?> </li>
						<?php } ?>
					</ul>
				</nav>
            </div>
        <?php
        }
        echo ob_get_clean();
    }

}

/**
 * Add New User Roles
 *
 * @param json
 * @return string
 */
if (!function_exists('workreap_add_user_roles')) {

    function workreap_add_user_roles() {
        $provider = add_role('employers', esc_html__('Employer', 'workreap'));
        $provider = add_role('freelancers', esc_html__('Freelancer', 'workreap'));
    }

    add_action('admin_init', 'workreap_add_user_roles');
}

/**
 * Role Translation
 *
 * @param json
 * @return string
 */
if (!function_exists('workreap_translate_user_roles')) {
	function workreap_translate_user_roles( $translation, $text, $context, $domain ) {
		if ( version_compare( get_bloginfo( 'version' ), '5.2', '<' ) ) {
			return $translation;
		}

		if ( 'User role' === $context && 'default' === $domain && in_array( $text, array( 'Freelancer' ), true) ) {
			return esc_html__('Freelancer', 'workreap');
		} else if ( 'User role' === $context && 'default' === $domain && in_array( $text, array( 'Employer' ), true) ) {
			return esc_html__('Employer', 'workreap');
		}

		return $translation;
	}
	add_filter( 'gettext_with_context', 'workreap_translate_user_roles', 10, 4 );
}


/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('workreap_prepare_thumbnail')) {

    function workreap_prepare_thumbnail($post_id, $width = '300', $height = '300') {
        global $post;
        if (has_post_thumbnail()) {
            get_the_post_thumbnail();
            $thumb_id = get_post_thumbnail_id($post_id);
            $thumb_url = wp_get_attachment_image_src($thumb_id, array(
                $width,
                $height
                    ), true);
            if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            } else {
                $thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            }
        } else {
            return;
        }
    }

}

/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('workreap_prepare_thumbnail_from_id')) {

    function workreap_prepare_thumbnail_from_id($post_id, $width = '300', $height = '300') {
        global $post;
        $thumb_id = get_post_thumbnail_id($post_id);
        if (!empty($thumb_id)) {
            $thumb_url = wp_get_attachment_image_src($thumb_id, array(
                $width,
                $height
                    ), true);
            if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            } else {
                $thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            }
        } else {
            return 0;
        }
    }

}

/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('workreap_prepare_image_source')) {

    function workreap_prepare_image_source($post_id, $width = '300', $height = '300') {
        global $post;
        $thumb_url = wp_get_attachment_image_src($post_id, array(
            $width,
            $height
                ), true);
        if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
            return !empty($thumb_url[0]) ? $thumb_url[0] : '';
        } else {
            $thumb_url = wp_get_attachment_image_src($post_id, 'full', true);
            return !empty($thumb_url[0]) ? $thumb_url[0] : '';
        }
    }

}


/**
 * @get revolution sliders
 * @return link
 */
if (!function_exists('workreap_prepare_rev_slider')) {

    function workreap_prepare_rev_slider() {
		$revsliders	= array();
        $revsliders[] = esc_html__('Select Slider', 'workreap');
        if (class_exists('RevSlider')) {
            $slider = new RevSlider();
            $arrSliders = $slider->getArrSliders();
            $revsliders = array();
            if ($arrSliders) {
                foreach ($arrSliders as $key => $slider) {
                    $revsliders[$slider->getId()] = $slider->getAlias();
                }
            }
        }
        return $revsliders;
    }

}

/**
 * @get Excerpt
 * @return link
 */
if (!function_exists('workreap_prepare_excerpt')) {

    function workreap_prepare_excerpt($charlength = '255', $more = 'false', $text = 'Read More') {
        global $post;
        $excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_content()));
        if (strlen($excerpt) > $charlength) {
            if ($charlength > 0) {
                $excerpt = mb_substr($excerpt, 0, $charlength);
            } else {
                $excerpt = $excerpt;
            }
			
            if ($more == 'true') {
                $link = ' <a href="' . esc_url(get_permalink()) . '" class="serviceproviders-more">' . esc_html($text) . '</a>';
            } else {
                $link = '...';
            }
			
            echo do_shortcode($excerpt) . $link;
        } else {
            echo do_shortcode($excerpt);
        }
    }

}
/**
 * @Esc Data
 * @return categories
 */
if (!function_exists('workreap_esc_specialchars')) {

    function workreap_esc_specialchars($data = '') {
        return $data;
    }

}

/**
 * @Custom post types
 * @return {}
 */
if (!function_exists('workreap_prepare_custom_posts')) {

    function workreap_prepare_custom_posts($post_type = 'post') {
        $posts_array = array();
        $args = array(
            'posts_per_page' => "-1",
            'post_type' 	 => $post_type,
            'order' 		 => 'DESC',
            'orderby' 		 => 'ID',
            'post_status' 	 => 'publish',
            'ignore_sticky_posts' => 1
        );
		
        $posts_query = get_posts($args);
		
        foreach ($posts_query as $post_data):
            $posts_array[$post_data->ID] = $post_data->post_title;
        endforeach;
		
        return $posts_array;
    }

}

/**
 * @Get post name
 * @return {}
 */
if (!function_exists('workreap_get_post_name')) {

    function workreap_get_post_name() {
        global $post;
        if (isset($post)) {
            $post_name = $post->post_name;
        } else {
            $post_name = '';
        }
		
        return $post_name;
    }

}

/**
 * Sanitize a string, removes special charachters
 * @param type $string
 * @author amentotech
 */
if (!function_exists('workreap_sanitize_string')) {

    function workreap_sanitize_string($string) {
        $filterd_string = array();
        $strings = explode(' ', $string);
        foreach ($strings as $string) {
            $filterd_string[] = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        }
        return implode(' ', $filterd_string);
    }

}

if (!isset($content_width)) {
    $content_width = 640;
}


/**
 * @Mailchimp List
 * @return 
 */
if (!function_exists('workreap_mailchimp_list')) {

    function workreap_mailchimp_list() {
        $mailchimp_list 	= array();
        $mailchimp_list[0] 	= esc_html__('Select List', 'workreap');
        $mailchimp_option 	= '';
		
        if (!function_exists('fw_get_db_settings_option')) {
            $mailchimp_option = '';
        } else {
            $default_value = '';
            $mailchimp_option = fw_get_db_settings_option('mailchimp_key');
            if (isset($mailchimp_option) && !empty($mailchimp_option)) {
                $mailchimp_option = $mailchimp_option;
            } else {
                $mailchimp_option = '';
            }
        }

        if (!empty($mailchimp_option)) {
            if (class_exists('workreap_MailChimp')) {
                $mailchim_obj = new Workreap_MailChimp();
                $lists = $mailchim_obj->workreap_mailchimp_list($mailchimp_option);

                if (is_array($lists) && isset($lists['data'])) {
                    foreach ($lists['data'] as $list) {
                        if (!empty($list['name'])) :
                            $mailchimp_list[$list['id']] = $list['name'];
                        endif;
                    }
                }
            }
        }
        return $mailchimp_list;
    }

}

/**
 * @Search contents
 * @return 
 */
if (!function_exists('workreap_prepare_search_content')) {

    function workreap_prepare_search_content($limit = 30) {
        global $post;
        $content = '';
        $limit = $limit;
        $post = get_post($post->ID);
        $custom_excerpt = FALSE;
        $read_more = '[...]';
        $raw_content = wp_strip_all_tags(get_the_content($read_more), '<p>');
        $raw_content = preg_replace('/<(\w+)[^>]*>/', '<$1>', $raw_content);

        if ($raw_content && $custom_excerpt == FALSE) {
            $pattern = get_shortcode_regex();
            $content = $raw_content;
            $content = explode(' ', $content, $limit + 1);
            if (workreap_count_items($content) > $limit) {
                ;
                array_pop($content);
                $content = implode(' ', $content);
                if ($limit != 0) {
                    $content .= $read_more;
                }
            } else {
                $content = implode(' ', $content);
            }
        }

        if ($limit != 0) {
            $content = preg_replace('~(?:\[/?)[^/\]]+/?\]~s', '', $content); // strip shortcode and keep the content
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        }

        return strip_shortcodes(wp_strip_all_tags( $content ) );
    }

}

/* @Image HTML
 * $return {HTML}
 */
if (!function_exists('workreap_get_post_thumbnail')) {

    function workreap_get_post_thumbnail($url = '', $post_id = '', $linked = 'unlinked') {
        global $post;

        if (isset($linked) && $linked === 'linked') {
            echo '<a href="' . esc_url(get_the_permalink($post_id)) . '"><img src ="' . esc_url($url) . '" alt="' . esc_attr( get_the_title($post_id) ) . '"></a>';
        } else {
            echo '<img src ="' . esc_url($url) . '" alt="' . esc_attr( get_the_title($post_id) ) . '">';
        }
    }

}

/* @Get categories HTML
 * $return {HTML}
 */
if (!function_exists('workreap_get_post_categories')) {

    function workreap_get_post_categories($post_id = '', $classes = '', $categoty_type = 'category', $display_title = 'Categories', $enable_title = 'yes') {
        global $post;
        ob_start();
        $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
        $terms = wp_get_post_terms($post_id, $categoty_type, $args);
        if (!empty($terms)) {?>
            <div class="wtblog-tags">
                <?php if (!empty($display_title) && $enable_title === 'yes') { ?>
                    <span><?php echo esc_html($display_title); ?></span>
                <?php } ?>
                <i class="lnr lnr-tag"></i>
                <?php  foreach ($terms as $key => $terms) { ?>
                    <a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url(get_term_link($terms->term_id, $categoty_type)); ?>"><span><?php echo esc_html($terms->name);?></span></a>
                <?php } ?>

            </div>
            <?php
        }
        echo ob_get_clean();
    }

}

/* @Get tags HTML
 * $return {HTML}
 */
if ( !function_exists( 'workreap_get_post_tags' ) ) {

    function workreap_get_post_tags( $post_id = '', $categoty_type = 'tag', $display_title = 'yes' ) {
        global $post;
        ob_start();
        $args = array( 'orderby' => 'name', 'order' => 'ASC', 'fields' => 'all' );
        $tags = wp_get_post_tags( $post_id, $categoty_type, $args );
        if ( !empty( $tags ) ) { ?>
            <div class="wt-tag wt-widgettag">
                <?php if (isset($display_title) && $display_title === 'yes') { ?>
                <span>
                    <?php esc_html_e('Tags:', 'workreap'); ?>
                </span>
                <?php } ?>
                <?php foreach ($tags as $key => $tag) { ?>
                    <a href="<?php echo esc_url( get_tag_link($tag->term_id, 'tag')); ?>">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                <?php } ?>
            </div>
            <?php
        }

        echo ob_get_clean();
    }

}

/* @Post author HTML
 * $return {HTML}
 */
if (!function_exists('workreap_get_post_author')) {

    function workreap_get_post_author($post_author_id = '', $linked = 'linked', $post_id = '') {
       $user_type 	= workreap_get_user_type($post_author_id);
        if( !empty($user_type) && ($user_type == 'freelancer' || $user_type == 'employer')){
            $profile_id = workreap_get_linked_profile_id($post_author_id);
            $url        = get_permalink($profile_id);
        } else {
            $url    = get_author_posts_url($post_author_id);
        }
        global $post;
        echo '<a href="' . esc_url($url). '"><i class="lnr lnr-user"></i><span>' . get_the_author() . '</span></a>';
    }

}
/* @Post date HTML
 * $return {HTML}
 */
if (!function_exists('workreap_get_post_date')) {

    function workreap_get_post_date($post_id = '') {
        global $post;
        echo '<time datetime="' . date('Y-m-d', strtotime(get_the_date('Y-m-d', $post_id))) . '"><i class="lnr lnr-clock"></i><span>' . date_i18n(get_option('date_format'), strtotime(get_the_date('Y-m-d', $post_id))) . '</span></time>';
    }

}

/* @Post title HTML
 * $return {HTML}
 */
if (!function_exists('workreap_get_post_title')) {

    function workreap_get_post_title($post_id = '') {
        global $post;
        echo '<a href="' . esc_url(get_the_permalink($post_id)) . '">' . esc_html( get_the_title($post_id) ) . '</a>';
    }

}
/* @Play button HTML
 * $return {HTML}
 */
if (!function_exists('workreap_get_play_link')) {

    function workreap_get_play_link($post_id = '') {
        global $post;
        echo '<a class="wt-btnplay" href="' . esc_url(get_the_permalink($post_id)) . '"></a>';
    }

}

/**
 * @coming soon BG
 * @return {}
 */
if (!function_exists('workreap_comingsoon_background')) {

    function workreap_comingsoon_background() {
        $background_comingsoon = '';
        if (function_exists('fw_get_db_post_option')) {
            $background = fw_get_db_settings_option('background');
            if (isset($background['url']) && !empty($background['url'])) {
                //Do Nothing
            } else {
                $background['url'] = get_template_directory_uri() . '/images/commingsoon-bg.jpg';
            }
        } else {
            $background['url'] = get_template_directory_uri() . '/images/commingsoon-bg.jpg';
        }

        if (isset($background['url']) && !empty($background['url'])) {
            $background_comingsoon = $background['url'];
        }

        return $background_comingsoon;
    }

}

/**
 * Get Social Icon Name
 * $return HTML
 */
if (!function_exists('workreap_get_social_icon_name')) {

    function workreap_get_social_icon_name($icon_class = '') {
        $icons = array(
            'fa-facebook' => 'wt-facebook',
            'fa-facebook-square' => 'wt-facebook',
            'fa-facebook-official' => 'wt-facebook',
            'fa-facebook-f' => 'wt-facebook',
            'fa-twitter' => 'wt-twitter',
            'fa-twitter-square' => 'wt-twitter',
            'fa-linkedin' => 'wt-linkedin',
            'fa-linkedin-square' => 'wt-linkedin',
            'fa-google-plus' => 'wt-googleplus',
            'fa-google-plus-square' => 'wt-googleplus',
            'fa-google' => 'wt-googleplus',
            'fa-rss' => 'wt-rss',
            'fa-rss-square' => 'wt-rss',
            'fa-dribbble' => 'wt-dribbble',
            'fa-youtube' => 'wt-youtube',
            'fa-youtube-play' => 'wt-youtube',
            'fa-youtube-square' => 'wt-youtube',
            'fa-pinterest-square' => 'wt-pinterest',
            'fa-pinterest-p' => 'wt-pinterest',
            'fa-pinterest' => 'wt-pinterest',
            'fa-flickr' => 'wt-flickr',
            'fa-whatsapp' => 'wt-whatsapp',
            'fa-tumblr-square' => 'wt-tumblr',
            'fa-tumblr' => 'wt-tumblr',
			'fa-instagram' => 'wt-instagram',
			'fa-twitch' => 'wt-twitch',
        );
		
        if (!empty($icon_class)) {
            $substr_icon_class = substr($icon_class, 3);
            if (array_key_exists($substr_icon_class, $icons)) {
                return $icons[$substr_icon_class];
            }
        }
    }

}


/**
 * Get Image Src
 * @return 
 */
if (!function_exists('workreap_get_image_metadata')) {

    function workreap_get_image_metadata($attachment_id) {

        if (!empty($attachment_id)) {
            $attachment = get_post($attachment_id);
            if (!empty($attachment)) {
                return array(
                    'alt' 			=> get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
                    'caption' 		=> $attachment->post_excerpt,
                    'description' 	=> $attachment->post_content,
                    'href' 			=> get_permalink($attachment->ID),
                    'src' 			=> $attachment->guid,
                    'title' 		=> $attachment->post_title
                );
            } else {
                return array();
            }
        }
    }

}

/**
 * A custom sanitization function that will take the incoming input, and sanitize
 * the input before handing it back to WordPress to save to the database.
 *
 */
if (!function_exists('workreap_sanitize_array')) {

    function workreap_sanitize_array($input) {
        if (!empty($input)) {
            // Initialize the new array that will hold the sanitize values
            $new_input = array();

            // Loop through the input and sanitize each of the values
            foreach ($input as $key => $val) {
                $new_input[$key] = isset($input[$key]) ? sanitize_text_field($val) : '';
            }

            return $new_input;
        } else {
            return $input;
        }
    }

}

/**
 * Sanitize Wp editor
 *
 */
if (!function_exists('workreap_sanitize_wp_editor')) {

    function workreap_sanitize_wp_editor($data) {
        return wp_kses($data, array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
        ));
    }

}

/**
 * @OWL Carousel RTL
 * @return {}
 */
if (!function_exists('workreap_owl_rtl_check')) {

    function workreap_owl_rtl_check() {
        if (is_rtl()) {
            return 'true';
        } else {
            return 'false';
        }
    }
}

/**
 * @Splide RTL
 * @return {}
 */
if (!function_exists('workreap_splide_rtl_check')) {

    function workreap_splide_rtl_check() {
        if (is_rtl()) {
            return 'rtl';
        } else {
            return 'ltr';
        }
    }
}

/**
 * @OWL  RTL
 * @return {}
 */
if (!function_exists('workreap_rtl_check')) {

    function workreap_rtl_check() {
        if (is_rtl()) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @Workreap Unique Increment
 * @return {}
 */
if (!function_exists('workreap_unique_increment')) {

    function workreap_unique_increment($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}

/**
 * @Custom Title Linking
 * @return {}
 */
if (!function_exists('workreap_get_registered_sidebars')) {

    function workreap_get_registered_sidebars() {
        global $wp_registered_sidebars;
        $sidebars = array();
		
        foreach ($wp_registered_sidebars as $key => $sidebar) {
            $sidebars[$key] = $sidebar['name'];
        }
		
        return $sidebars;
    }

}

/**
 * @Add http from URL
 * @return {}
 */
if (!function_exists('workreap_add_http_protcol')) {

    function workreap_add_http_protcol($url) {
        $protolcol = is_ssl() ? "https" : "http";
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = $protolcol . ':' . $url;
        }
        return $url;
    }

}

/**
 * Get Page or Post Slug by id
 * @return slug
 */
if (!function_exists('workreap_get_slug')) {

    function workreap_get_slug($post_id) {
        if (!empty($post_id)) {
            return get_post_field('post_name', $post_id);
        }
    }

}

/**
 * @Get Dob Date Format 
 * @return {Expected Day}
 */
if (!function_exists('workreap_get_dob_format')) {

    function workreap_get_dob_format($date, $return_type = 'echo') {
        ob_start();
        $current_month 	= date("n");
        $current_day 	= date("j");

        $dob 		= strtotime($date);
        $dob_month 	= date("n", $dob);
        $dob_day 	= date("j", $dob);

        if ($current_month == $dob_month) {
            if ($current_day == $dob_day) {
                esc_html_e('Today', 'workreap');
            } elseif ($current_day == $dob_day + 1) {
                esc_html_e('Yesterday', 'workreap');
            } elseif ($current_day == $dob_day - 1) {
                esc_html_e('Tomorrow', 'workreap');
            } else {
                esc_html_e('In this month', 'workreap');
            }
        } elseif ($current_month < $dob_month) {
            esc_html_e('In future', 'workreap');
        } else {
            esc_html_e('Long back', 'workreap');
        }

        if (isset($return_type) && $return_type === 'return') {
            return ob_get_clean();
        } else {
            echo ob_get_clean();
        }
    }

}
/**
 * comment form fields
 * @return slug
 */
    if (!function_exists('workreap_modify_comment_form_fields')) {
        function workreap_modify_comment_form_fields(){
            $commenter = wp_get_current_commenter();
            $req       = get_option( 'require_name_email' );

            $fields['author'] = '<div class="form-group form-group-half"><input type="text" name="author" id="author" value="'. esc_attr( $commenter['comment_author'] ) .'" placeholder="'. esc_attr__("Your name (required)", "workreap").'" size="22" tabindex="1" '. ( $req ? 'aria-required="true"' : '' ).' class="form-control" /></div>';

            $fields['email'] = '<div class="form-group form-group-half"><input type="text" name="email" id="email" value="'. esc_attr( $commenter['comment_author_email'] ) .'" placeholder="'. esc_attr__("Your email (required)", "workreap").'" size="22" tabindex="2" '. ( $req ? 'aria-required="true"' : '' ).' class="form-control"  /></div>';
            
            $fields['url'] = '<div class="form-group form-group-half"><input type="text" name="url" id="url" value="'. esc_attr( $commenter['comment_author_url'] ) .'" placeholder="'. esc_attr__("Website", "workreap").'" size="22" tabindex="3" class="form-control" /></div>';

            return $fields;
        }
        add_filter('comment_form_default_fields','workreap_modify_comment_form_fields');     
    }   

/**
 * comments listings
 * @return slug
 */
if (!function_exists('workreap_comments')) {

    function workreap_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;

    $post_author_id	= get_the_author_meta('ID');
    $user_type 		= workreap_get_user_type($post_author_id);
    if( !empty($user_type) && ($user_type == 'freelancer' || $user_type == 'employer')){
        $profile_id = workreap_get_linked_profile_id($post_author_id);
        $url        = get_permalink($profile_id);
    } else {
        $url    = get_author_posts_url($post_author_id);
    }
	$args['reply_text'] = '<span class="wt-clickreply">'.esc_html__('Click To Reply','workreap').'</span><i class="fa fa-reply"></i>';
	?>
	<li <?php comment_class('comment-entry clearfix'); ?> id="comment-<?php comment_ID(); ?>">
		<div class="wt-author">
            <div class="wt-authordetails">
    			<figure><?php echo get_avatar($comment, 80); ?> </figure>
    			<div class="wt-authorcontent">
					<div class="wt-authorhead">
						<div class="wt-boxleft">
                            <h3><a href="<?php echo esc_url( $url ); ?>"><?php comment_author(); ?></a></h3>
                            <span><?php echo sprintf( _x( '%s ago', '%s = human-readable time difference', 'workreap' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
						</div>
						<div class="wt-boxright">
							<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
						</div>
					</div>
					<div class="wt-description">
						<?php if ($comment->comment_approved == '0') : ?>
							<p class="comment-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'workreap'); ?></p>
						<?php endif; ?>
						<?php comment_text(); ?>
					</div>
    			</div>
            </div>
		</div>
		<?php
	}

}


/**
 * comments wrap start
 * @return slug
 */
if (!function_exists('workreap_comment_form_top')) {
	add_action('comment_form_top', 'workreap_comment_form_top');

	function workreap_comment_form_top() {
		// Adjust this to your needs:
		$output = '';
		$output .='<fieldset>';

		echo do_shortcode( $output);
	}

}

/**
 * @count items in array
 * @return {}
 */
if (!function_exists('workreap_count_items')) {
    function workreap_count_items($items) {
        if( is_array($items) ){
			return count($items);
		} else{
			return 0;
		}
    }
}

/**
 * comments wrap start
 * @return slug
 */
if (!function_exists('workreap_comment_form')) {
	add_action('comment_form', 'workreap_comment_form');

	function workreap_comment_form() {
		$output = '';
		$output .= '</fieldset>';

		echo do_shortcode( $output );
	}

}

if (!function_exists('workreap_extract_typography')) {

	function workreap_extract_typography($field) {
		$output = '';

		$output .= 'font-family: ' . ($field['family']) . ';';
		$output .= "\r\n";
		if (isset($field['google_font']) && $field['google_font'] === true) {
			if (isset($field['variation'])) {
				$pattern = '/(\d+)|(regular|italic)/i';
				preg_match_all($pattern, $field['variation'], $matches);
				foreach ($matches[0] as $value) {
					if ($value == 'italic') {
						$output .= 'font-style: ' . $value . ';';
						$output .= "\r\n";
					} else if ($value == 'regular') {
						$output .= 'font-style: normal;';
						$output .= "\r\n";
					} else {
						$output .= 'font-weight: ' . $value . ';';
						$output .= "\r\n";
					}
				}
			}
		} else {
			$output .= 'font-style: ' . ($field['style']) . ';';
			$output .= "\r\n";
			$output .= 'font-weight: ' . ($field['weight']) . ';';
			$output .= "\r\n";
		}
		$output .= 'font-size: ' . ($field['size']) . 'px;';
		$output .= "\r\n";
		$output .= 'line-height: ' . ($field['line-height']) . 'px;';
		$output .= "\r\n";
		$output .= 'letter-spacing: ' . ($field['letter-spacing']) . 'px;';
		$output .= 'color: ' . ($field['color']) . ';';
		$output .= "\r\n";

		return $output;
	}

}

/**
 * Get term name
 */
if(!function_exists('workreap_get_slug_name')){
    function workreap_get_slug_name($term_id = '', $taxonomy = ''){
        $term_name = '';
        if(!empty($term_id) && !empty($taxonomy)){
            $term = get_term( $term_id, $taxonomy );
            $term_name = $term->name;
        }
        return $term_name;
    }
}

/**
 * Get earning for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_get_row_earnings' ) ) {
    function workreap_get_row_earnings( $user_identity,$project_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wt_earnings";
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_identity) ) {
				$e_query	= $wpdb->prepare("SELECT * FROM $table_name where user_id =%d and project_id =%s", $user_identity,$project_id);
				$earning = $wpdb->get_row( $e_query );
			} else {$earning	= 0;
			}
		} else{
			$earning	= 0;
		}
		
		return $earning;
		
	}
}

/**
 * @override parent theme files
 * @return link
 */
if (!function_exists('workreap_override_templates')) {
	function workreap_override_templates($file) {
		if ( file_exists( get_stylesheet_directory() . $file ) ) {
			$template_load = get_stylesheet_directory() . $file;
		} else {
			$template_load = get_template_directory() . $file;
		}
		
		return $template_load;
	}
}

/**
 * Get AtomChat UnRead Messages For Specific User
 */
if (!function_exists('workreap_count_unread_cometchat_msgs')) {
    /**
     * Get Unread Messages
     *
     * @param string $uid
     * @return void
     */
    function workreap_count_unread_cometchat_msgs($uid = '') {
        $comet_apikey = get_option('atomchat_api_key');
        if (!empty($comet_apikey) && !empty($uid)) {
            $endpoint = 'https://api.cometondemand.net/api/v2/getUnreadMessageCounts';
            $body = [
                'UID'  => intval($uid)
            ];
            $options = array(
                'body'        => $body,
                'headers'     => array(
                    'api-key' => trim($comet_apikey)
                ),
                'timeout'     => 60,
                'redirection' => 5,
                'blocking'    => true,
                'httpversion' => '1.0',
                'sslverify'   => false,
                'data_format' => 'body',
            );
            
            $response	= wp_remote_post( esc_url($endpoint), $options );
            $response	= wp_remote_retrieve_body($response);
            $response   = json_decode($response);

            if(!empty($response) ) {
                if (!empty($response->success->status) && $response->success->status == 2000) {
                    echo !empty($response->success->totalcount) ? intval($response->success->totalcount) : 0;
                } else {
                    echo !empty($response->failed->totalcount) ? intval($response->failed->totalcount) : 0;
                }
            } else{
				echo 0;
			}
        }
    }

    add_action('workreap_get_unread_msgs', 'workreap_count_unread_cometchat_msgs', 10, 1);
}