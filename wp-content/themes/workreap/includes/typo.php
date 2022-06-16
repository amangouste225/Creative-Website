<?php
/**
 * @Set Post Views
 * @return {}
 */
if (!function_exists('workreap_add_dynamic_styles')) {

    function workreap_add_dynamic_styles() {
        if (function_exists('fw_get_db_settings_option')) {
            $color_base = fw_get_db_settings_option('color_settings');
			$logo_x = fw_get_db_settings_option('logo_x');
			$logo_y = fw_get_db_settings_option('logo_y');
			$body_bg_color = fw_get_db_settings_option('body_bg_color');
            $enable_typo = fw_get_db_settings_option('enable_typo');
            $background = fw_get_db_settings_option('background');
            $custom_css = fw_get_db_settings_option('custom_css');
            $body_font = fw_get_db_settings_option('body_font');
            $body_p = fw_get_db_settings_option('body_p');
            $h1_font = fw_get_db_settings_option('h1_font');
            $h2_font = fw_get_db_settings_option('h2_font');
            $h3_font = fw_get_db_settings_option('h3_font');
            $h4_font = fw_get_db_settings_option('h4_font');
            $h5_font = fw_get_db_settings_option('h5_font');
            $h6_font = fw_get_db_settings_option('h6_font');
			$freelancer_overlay = fw_get_db_settings_option('freelancer_overlay');
			$employer_overlay = fw_get_db_settings_option('employer_overlay');
			$footer_settings = fw_get_db_settings_option('footer_settings');
			$preloader 		= fw_get_db_settings_option('preloader');

			
			$nrf_favorites 	= fw_get_db_settings_option('nrf_favorites');
			$nrf_messages 	= fw_get_db_settings_option('nrf_messages');
			$nrf_create 	= fw_get_db_settings_option('nrf_create');
			$nrf_found 		= fw_get_db_settings_option('nrf_found');
        }
		
		$preloader_opt			= !empty($preloader['gadget']) && $preloader['gadget'] === 'enable' ? 'yes' : '';
		$preloader_custom		= !empty($preloader['enable']['preloader']['gadget']) && $preloader['enable']['preloader']['gadget'] === 'custom' ? 'yes' : '';
		$preloader_custom_spin	= !empty($preloader['enable']['preloader']['custom']['loader']['url']) ? $preloader['enable']['preloader']['custom']['loader']['url'] : '';
		
		$loader_wide		= !empty($preloader['enable']['preloader']['custom']['loader_x']) ? $preloader['enable']['preloader']['custom']['loader_x'] : 30;
		$loader_long		= !empty($preloader['enable']['preloader']['custom']['loader_y']) ? $preloader['enable']['preloader']['custom']['loader_y'] : 30;
		$loader_wide_half	= $loader_wide/2;
		
        $post_name = workreap_get_post_name();
		
		$titlebar_overlay_secondary	= '';
		$titlebar_overlay = '';

		if( is_404() 
			|| is_archive() 
			|| is_search() 
			|| is_category() 
			|| is_tag() 
		) {

			if(function_exists('fw_get_db_settings_option')){
				$titlebar_type 	= fw_get_db_settings_option('titlebar_type');
				if(  isset( $titlebar_type['gadget'] )  && $titlebar_type['gadget'] === 'default' 
				) {
					$titlebar_overlay 		= !empty( $titlebar_type['default']['titlebar_overlay'] ) ? $titlebar_type['default']['titlebar_overlay'] : '';
					$titlebar_overlay_secondary	= !empty( $titlebar_type['default']['titlebar_overlay_secondary'] ) ? $titlebar_type['default']['titlebar_overlay_secondary'] : $titlebar_overlay;
				}
			}
		} else{
			$object_id = get_queried_object_id();
			if((get_option('show_on_front') && get_option('page_for_posts') && is_home()) ||
				(get_option('page_for_posts') && is_archive() && !is_post_type_archive()) && !(is_tax('product_cat') || is_tax('product_tag')) || (get_option('page_for_posts') && is_search())) {
					$page_id = get_option('page_for_posts');		
			}else {
				if(isset($object_id)) {
					$page_id = $object_id;
				}
			}
			
			if(function_exists('fw_get_db_settings_option')){
				$titlebar_type 		= fw_get_db_post_option($page_id, 'titlebar_type', true);
				if(  isset( $titlebar_type['gadget'] ) && ( $titlebar_type['gadget'] === 'custom' ) ){
					$titlebar_overlay 			= !empty( $titlebar_type['custom']['titlebar_overlay'] ) ? $titlebar_type['custom']['titlebar_overlay'] : '';
					$titlebar_overlay_secondary	= !empty( $titlebar_type['custom']['titlebar_overlay'] ) ? $titlebar_type['custom']['titlebar_overlay'] : '';
				} else {
					$titlebar_type 			= fw_get_db_settings_option('titlebar_type');
					$titlebar_overlay 		= !empty( $titlebar_type['default']['titlebar_overlay'] ) ? $titlebar_type['default']['titlebar_overlay'] : '';
					$titlebar_overlay_secondary	= !empty( $titlebar_type['default']['titlebar_overlay_secondary'] ) ? $titlebar_type['default']['titlebar_overlay_secondary'] : $titlebar_overlay;
				}

			}
		}
		
		if (function_exists('fw_get_db_settings_option')) {
			$footer_type = fw_get_db_settings_option('footer_type');
		}

		$f_primary_color      	= !empty($footer_type['footer_v2']['primary_color']) ? $footer_type['footer_v2']['primary_color'] : '';
		$f_secondary_color   	= !empty($footer_type['footer_v2']['secondary_color']) ? $footer_type['footer_v2']['secondary_color'] : '';
		
		$f_primary_color_v3     = !empty($footer_type['footer_v3']['primary_color']) ? $footer_type['footer_v3']['primary_color'] : '';
		$f_secondary_color_v3   = !empty($footer_type['footer_v3']['secondary_color']) ? $footer_type['footer_v3']['secondary_color'] : '';
		$footer_bg 				= get_template_directory_uri().'/images/homeseven/footer.svg';

        ob_start();

        if (isset($enable_typo) && $enable_typo == 'on') { ?>
            body{<?php echo workreap_extract_typography($body_font); ?>}
            body p{<?php echo workreap_extract_typography($body_p); ?>}
            body ul {<?php echo workreap_extract_typography($body_font); ?>}
            body li {<?php echo workreap_extract_typography($body_font); ?>}
            body h1{<?php echo workreap_extract_typography($h1_font); ?>}
            body h2{<?php echo workreap_extract_typography($h2_font); ?>}
            body h3{<?php echo workreap_extract_typography($h3_font); ?>}
            body h4{<?php echo workreap_extract_typography($h4_font); ?>}
            body h5{<?php echo workreap_extract_typography($h5_font); ?>}
            body h6{<?php echo workreap_extract_typography($h6_font); ?>}
            .wt-navigation>ul>li.menu-item-has-mega-menu>a {font-family: <?php echo workreap_extract_typography($body_font); ?>;}
			.wt-navigation ul li a {font-family: <?php echo workreap_extract_typography($body_font); ?>;}
        <?php } ?>

        <?php
        if (isset($color_base['gadget']) && $color_base['gadget'] === 'custom') {
            if (!empty($color_base['custom']['primary_color']) 
				|| !empty($color_base['custom']['secondary_color']) 
				|| !empty($color_base['custom']['tertiary_color'])
			) {
                $theme_color 	 = !empty( $color_base['custom']['primary_color'] ) ? $color_base['custom']['primary_color'] : '';
				$secondary_color = !empty( $color_base['custom']['secondary_color'] ) ? $color_base['custom']['secondary_color'] : '';
				$tertiary_color  = !empty( $color_base['custom']['tertiary_color'] ) ? $color_base['custom']['tertiary_color'] : '';
				
				//just for demo
				if ( apply_filters('workreap_get_domain',false) === true ) {
					$post_name = workreap_get_post_name();
					if( $post_name === "home-page-v5" ){
						$theme_color 	 = '#2f3180';
						$secondary_color = '#2f3180';
						$tertiary_color  = '#2f3180';
					}
				}
				
				$footer_bg_color = !empty( $color_base['custom']['footer_bg_color'] ) ? $color_base['custom']['footer_bg_color'] : '';
				$footer_text_color = !empty( $color_base['custom']['footer_text_color'] ) ? $color_base['custom']['footer_text_color'] : '';
				
				if(!empty( $theme_color )){?>
                	:root {--primthemecolor: <?php echo esc_html($theme_color);?>; }
                <?php }?>
                
				<?php if(!empty( $secondary_color )){?>
					:root {--secthemecolor: <?php echo esc_html($secondary_color);?>; }
				<?php }?>
				
				<?php if(!empty( $tertiary_color )){?>
					:root {--terthemecolor: <?php echo esc_html($tertiary_color);?>; }
          		<?php } 
			} 
        }
		
		if( !empty( $logo_x ) && !empty( $logo_y ) ){?>
			.wt-logo{max-width:none;}
			.wt-logo a img{width:<?php echo esc_html( $logo_x );?>px; height:<?php echo esc_html( $logo_y );?>px;}
		<?php } else if( !empty( $logo_x ) ){?>
			.wt-logo{max-width:none;}
			.wt-logo a img{width:<?php echo esc_html( $logo_x );?>px;}
		<?php }
		if( !empty( $body_bg_color ) ){?>
			main.wt-main{background: <?php echo esc_html($body_bg_color);?> !important;}
		<?php
		}
		
		if( !empty( $freelancer_overlay ) ){?>
		.single-micro-services .wt-companysimg,
		.single-freelancers .frinnerbannerholder:after{background:<?php echo esc_html( $freelancer_overlay );?>;}
		<?php }
		
		if( !empty( $employer_overlay ) ){?>
			.page-template-employer-search .wt-companysimg,
			.single-projects .wt-companysimg,
			.single-employers .wt-comsingleimg figure{background:<?php echo esc_html( $employer_overlay );?>;}
        <?php }
		
		if( !empty( $nrf_found['url'] ) ){?>
			.wt-emptydetails span{background:url(<?php echo esc_url( $nrf_found['url'] );?>);margin-bottom: 30px;}
		<?php }
		
		if( !empty( $nrf_found['url'] ) ){?>				  
			.wt-empty-invoice span{background:url(<?php echo esc_url( $nrf_found['url'] );?>);margin-bottom: 30px;}
		<?php }
		
		if( !empty( $nrf_user['url'] ) ){?>	
			.wt-empty-person span{background:url(<?php echo esc_url( $nrf_user['url'] );?>);margin-bottom: 30px;}
		<?php }
		
		if( !empty( $nrf_create['url'] ) ){?>	
			.wt-empty-projects span{background:url(<?php echo esc_url( $nrf_create['url'] );?>);margin-bottom: 30px;}
		<?php }
		
		if( !empty( $nrf_messages['url'] ) ){?>	
			.wt-empty-message span{background:url(<?php echo esc_url( $nrf_messages['url'] );?>);margin-bottom: 30px;}
		<?php }
		
		if( !empty( $nrf_favorites['url'] ) ){?>	
		.wt-empty-saved span{background:url(<?php echo esc_url( $nrf_favorites['url'] );?>);margin-bottom: 30px;}
		<?php }
		
		if( !empty( $titlebar_overlay ) ){?>
			.wt-titlebardynmic.wt-innerbannerholder:before{background:linear-gradient(180deg, <?php echo esc_html( $titlebar_overlay );?> 0%, <?php echo esc_html( $titlebar_overlay_secondary );?> 100%) ;}
        <?php }
		 if (isset($footer_settings['gadget']) && $footer_settings['gadget'] === 'custom' && $footer_settings['custom']['footer_bg_color'] && !empty( $footer_settings['custom']['footer_text_color'] )) {?>
			.wt-footer{background:<?php echo esc_html( $footer_settings['custom']['footer_bg_color'] );?>;}
			<?php if( !empty( $footer_settings['custom']['footer_text_color'] ) ){?>
				.wt-footer .wt-socialiconfooter.wt-socialiconssimple li a i,
				.wt-footer .wt-footerholder .wt-fwidgetcontent li a,
				.wt-footer .wt-footerholder .wt-fwidgettitle h3,
				.wt-footer .wt-footerholder .wt-fwidgetcontent li.wt-viewmore a,
				.wt-footer .wt-copyrights,
				.wt-footer .wt-addnav ul li a,
				.wt-footer .wt-footerlogohold .wt-description p a,
				.wt-footer .wt-footerlogohold>.wt-description>p{color:<?php echo esc_html( $footer_settings['custom']['footer_text_color'] );?>;}
			<?php }?>
		<?php }
		echo (isset($custom_css)) ? $custom_css : '';
		
		if( !empty($f_primary_color) && !empty($f_secondary_color) ){?>
			.wt-footertwo:after {
				background: linear-gradient(to right, <?php echo esc_html($f_primary_color); ?> 0%,<?php echo esc_html($f_secondary_color); ?> 100%);
			}
        <?php }
		
		if( !empty($f_primary_color_v3) && !empty($f_secondary_color_v3) ){?>
       		.wt-footerthreevtwo {
				background-image: url(<?php echo esc_html($footer_bg); ?>),linear-gradient(to top, <?php echo esc_html($f_primary_color_v3); ?> 0%,<?php echo esc_html($f_secondary_color_v3); ?> 100%);
			}
        <?php }
		if( ( !empty($preloader_opt) && $preloader_opt === 'yes' ) && ( !empty($preloader_custom) && $preloader_custom === 'yes' ) && !empty($preloader_custom_spin) ){?>
			.preloader-customloader {
				width: <?php echo esc_html($loader_wide); ?>px;
				height: <?php echo esc_html($loader_long); ?>px;
				margin: -<?php echo esc_html($loader_wide_half); ?>px 0 0 -<?php echo esc_html($loader_wide_half); ?>px;
			}
        <?php }
        return ob_get_clean();
    }
}