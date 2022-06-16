<?php

/**
 *
 * Class used as base to create theme header
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('Workreap_Prepare_Theme_Setup')) {

    class Workreap_Prepare_Theme_Setup {

        function __construct() {
            add_action('after_setup_theme' , array (&$this ,'workreap_theme_setup' ));
			add_action('after_switch_theme' , array (&$this ,'workreap_after_switch_theme' ),10,2);
        }
		
		/**
         * 
         * @global type $pagenow
         */
        public function workreap_after_switch_theme($old,$new) {
			//add index.html file into folder
			$uploadDir = wp_upload_dir();
			$dir	= $uploadDir['basedir'];
			$directories	= workreapGetRecursiveFolderList($dir,$currentA=false);
			$source			= get_template_directory_uri().'/images/index.html';
			foreach($directories as $key => $folder){
				$path	= !empty($folder['path']) ? $folder['path'].'/index.html' : '';
				
				if( !file_exists($path) ){
					if(!empty($path)){
						copy($source, $path);
					}
				}

				unset($folder['path']);

				if(!empty($folder)){
					foreach($folder as $subfolder ){
						$path	= !empty($subfolder['path']) ? $subfolder['path'].'/index.html' : '';
						if( !file_exists($path) ){
							if(!empty($path)){
								copy($source, $path);
							}
						}
					}
				}
			}
		}
		
        /**
         * 
         * @global type $pagenow
         */
        public function workreap_theme_setup() {
            global $pagenow;

            load_theme_textdomain('workreap' , get_template_directory() . '/languages');
            
			// Add default posts and comments RSS feed links to head.
            add_theme_support('automatic-feed-links');
			
            /*
             * Let WordPress manage the document title.        
             * By adding theme support, we declare that this theme does not use a       
             * hard-coded <title> tag in the document head, and expect WordPress to      
             * provide it for us.       
             */
            add_theme_support('title-tag');
			
            /*
             * Enable support for Post Thumbnails on posts and pages.       
             *      
             * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails       
             */
            add_theme_support('post-thumbnails');
			
            // This theme uses wp_nav_menu() in one location.
            register_nav_menus(array (
                'primary-menu'   	=> esc_html__('Header Main Menu' , 'workreap'),
				'freelancers'   	=> esc_html__('Freelancers Menu' , 'workreap') ,
				'employers'   		=> esc_html__('Employers Menu' , 'workreap') ,
                'footer-menu' 		=> esc_html__('Footer Menu' , 'workreap') ,
				'pages-menu' 		=> esc_html__('Side bar pages Menu' , 'workreap') ,
				'categories-menu' 	=> esc_html__('Categories Menu' , 'workreap') ,
            ));

			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );

			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );

			// Add support for editor styles.
			add_theme_support( 'editor-styles' );
			
			// Add custom editor font sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => esc_html__( 'Small', 'workreap' ),
						'size'      => 14,
						'slug'      => 'small',
					),
					array(
						'name'      => esc_html__( 'Normal', 'workreap' ),
						'size'      => 16,
						'slug'      => 'normal',
					),
					array(
						'name'      => esc_html__( 'Large', 'workreap' ),
						'size'      => 36,
						'slug'      => 'large',
					),
					array(
						'name'      => esc_html__( 'Extra Large', 'workreap' ),
						'size'      => 48,
						'slug'      => 'extra-large',
					),
				)
			);
			
			//theme default color 
			add_theme_support( 
				'editor-color-palette', array(
				array(
					'name' => esc_html__( 'Theme Color', 'workreap' ),
					'slug' => 'strong-theme-color',
					'color' => '#ff5851',
				),
				array(
					'name' => esc_html__( 'Theme Light text color', 'workreap' ),
					'slug' => 'light-gray',
					'color' => '#767676',
				),
				array(
					'name' => esc_html__( 'Theme Very Light text color', 'workreap' ),
					'slug' => 'very-light-gray',
					'color' => '#eee',
				),
				array(
					'name' => esc_html__( 'Theme Dark text color', 'workreap' ),
					'slug' => 'very-dark-gray',
					'color' => '#323232',
				),
			) );
			
            /*
             * Switch default core markup for search form, comment form, and comments      
             * to output valid HTML5.     
             */
            add_theme_support('html5' , array (
                'search-form' ,
                'comment-form' ,
                'comment-list' ,
                'gallery' ,
                'caption'
            ));
			
            /*
             * Enable support for Post Formats.  
             * See http://codex.wordpress.org/Post_Formats     
             */
            add_theme_support('post-formats' , array (
                ''
            ));
			
			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );
			
			//Woocommerce
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			
            // Set up the WordPress core custom background feature.
            add_theme_support('custom-background' , apply_filters('workreap_custom_background_args' , array (
                'default-color' => 'ffffff' ,
                'default-image' => ''
            )));

            
			add_theme_support('custom-header' , array (
                'default-color' => '' ,
                'flex-width'    => true ,
                'flex-height'   => true ,
                'default-image' => ''
            ));
			
            if (!get_option('workreap_theme_installation')) {
                update_option('workreap_theme_installation' , 'installed');
                wp_redirect(admin_url('themes.php?page=install-required-plugins'));
            }

            add_filter('edit_user_profile' , 'workreap_edit_user_profile_edit' , 10 , 1);
            add_filter('show_user_profile' , 'workreap_edit_user_profile_edit' , 10 , 1);
            add_action('edit_user_profile_update' , 'workreap_personal_options_save');
            add_action('personal_options_update' , 'workreap_personal_options_save');

        }

    }

    new Workreap_Prepare_Theme_Setup();
}