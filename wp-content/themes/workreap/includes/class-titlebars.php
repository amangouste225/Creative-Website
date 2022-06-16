<?php

/**
 *
 * Class used as base to create theme Sub Header
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
if (!class_exists('Workreap_Prepare_TitleBar')) {

    class Workreap_Prepare_TitleBar {

        function __construct() {
            add_action('workreap_prepare_titlebars' , array (&$this , 'workreap_prepare_titlebars'));
        }

        /**
         * @Prepare Sub headers settings
         * @return {}
         * @author amentotech
         */
        public function workreap_prepare_titlebars() {
            global $post;
			$page_id = '';
			
			$object_id = get_queried_object_id();
			$current_object_type	= get_post_type();

			//hide for dashboard
			if (is_page_template('directory/dashboard.php')) {
				return false;
			}
			
			//Is singular freelancer
			if( is_singular( 'freelancers' )){
				return false;
			} 

			if((get_option('show_on_front') && get_option('page_for_posts') && is_home()) ||
				(get_option('page_for_posts') && is_archive() && !is_post_type_archive()) && !(is_tax('product_cat') || is_tax('product_tag')) || (get_option('page_for_posts') && is_search())) {
					$page_id = get_option('page_for_posts');		
			} else {
				if(isset($object_id)) {
					$page_id = $object_id;
				}
			}
			
			if( is_404() 
				|| is_archive() 
				|| is_search() 
				|| is_category() 
				|| is_tag() 
			) {
				if(function_exists('fw_get_db_settings_option')){
					$titlebar_type 	= fw_get_db_settings_option('titlebar_type', true);
					if(  isset( $titlebar_type['gadget'] ) && $titlebar_type['gadget'] === 'default' ) {
						$this->workreap_get_titlebars($page_id);
					} 
				} else{
					$this->workreap_get_titlebars($page_id);
				}
			} else {
				if(function_exists('fw_get_db_settings_option')){
					$titlebar_type 		    = fw_get_db_post_option($page_id, 'titlebar_type', true);
					$default_titlebar_type 	= fw_get_db_settings_option('titlebar_type', true);
					
					if( isset( $titlebar_type ) && is_array( $titlebar_type ) ){
						if( isset( $titlebar_type ) && is_array( $titlebar_type ) ){
							if(  isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'rev_slider' 
								&& !empty( $titlebar_type['rev_slider']['rev_slider'] )
							){
								echo '<div class="workreap-slider-container wt-haslayout">';
								echo do_shortcode( '[rev_slider '.$titlebar_type['rev_slider']['rev_slider'].']' );
								echo '</div>';
							}else if(  isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'custom_shortcode' 
								&& !empty( $titlebar_type['custom_shortcode']['custom_shortcode'] )
							){
								echo '<div class="workreap-shortcode-container wt-haslayout">';
								echo do_shortcode( $titlebar_type['custom_shortcode']['custom_shortcode'] );
								echo '</div>';
							} else if(  isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'default' 
							){
								$titlebar_type 	= fw_get_db_settings_option('titlebar_type', true);
								if(  isset( $titlebar_type['gadget'] ) && $titlebar_type['gadget'] === 'default' ) {
									$this->workreap_get_titlebars($page_id);
								} 
							} else if( isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'custom' 
							){
								$this->workreap_get_titlebars($page_id);
							} else if(  isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'none' 
							){
								//do nothing
							} else{
								if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
									//do nothing
								} else{
									$this->workreap_get_titlebars($page_id);
								}
							}
						} else{
							if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
								//do nothing
							} else{
								$this->workreap_get_titlebars($page_id);
							}
						}
					}else{
						if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
							//do nothing
						} else{
							$this->workreap_get_titlebars($page_id);
						}
					}
				} else{
					$this->workreap_get_titlebars($page_id);
				}
			}
        }
        
        /**
         * @Prepare Subheaders
         * @return {}
         * @author amentotech
         */
        public function workreap_get_titlebars($page_id='') {
			global $post,$titlebar_overlay;
			$title = '';
			$page_title		= false;
			$titlebar_bg 	= 'rgba(54, 59, 77, 0.40)';
			
			if( is_404() 
				|| is_archive() 
				|| is_search() 
				|| is_category() 
				|| is_tag() 
			) {
				
				if(function_exists('fw_get_db_settings_option')){
					$titlebar_type 	= fw_get_db_settings_option('titlebar_type', true);
					if(  isset( $titlebar_type['gadget'] ) 
					   	 && $titlebar_type['gadget'] === 'default' 
					) {
						$titlebar_type 	= fw_get_db_settings_option('titlebar_type', true);
						$titlebar_bg_image 	    = !empty( $titlebar_type['default']['titlebar_bg_image']['url'] ) ? $titlebar_type['default']['titlebar_bg_image']['url'] : get_template_directory_uri().'/images/tb.jpg';
						$enable_breadcrumbs 	= !empty( $titlebar_type['default']['enable_breadcrumbs'] ) ? $titlebar_type['default']['enable_breadcrumbs'] : '';
						$titlebar_overlay 		= !empty( $titlebar_type['default']['titlebar_overlay'] ) ? $titlebar_type['default']['titlebar_overlay'] : 'rgba(0, 0, 0, 0)';
					} else{
						$titlebar_bg_image 		= get_template_directory_uri().'/images/tb.jpg';;
						$enable_breadcrumbs 	= '';
						$titlebar_overlay 		= 'rgba(0, 0, 0, 0)';
					}
				} else{
					$titlebar_bg_image 	= get_template_directory_uri().'/images/tb.jpg';;
					$enable_breadcrumbs = '';
					$titlebar_overlay 		= 'rgba(0, 0, 0, 0)';
				}
				
				
				$background_image	= '';
				
				if( isset( $titlebar_bg_image['url'] ) && !empty( $titlebar_bg_image['url'] ) ) {
					$background_image = $titlebar_bg_image['url'];
				} else if( isset( $titlebar_bg_image ) && !empty( $titlebar_bg_image ) ) {
					 $background_image = $titlebar_bg_image;
				}
				
				
				if (is_404()) {
 					$title = esc_html__('404', 'workreap');
                } else if( class_exists( 'Woocommerce' ) 
					&& is_woocommerce() 
					&& ( is_product() || is_shop() || is_checkout() || is_cart() ) 
					&& ! is_search() 
				) {
					if( ! is_product() ) {
						$title = woocommerce_page_title( false );
					} else{
						$title = esc_html__('Shop', 'workreap');
					}
				}else if ( is_category() ) {
                    $title = single_cat_title("", false);
                } else if ( is_tax() ) {
					$title	= single_term_title("",false);
                }else if ( is_archive() ) {
                    $title = esc_html__('Archive', 'workreap');
                } else if (is_search()) {
                    $title = esc_html__('Search', 'workreap');
                }
			} else{
				
				$object_id = get_queried_object_id();
				if((get_option('show_on_front') && get_option('page_for_posts') && is_home()) ||
					(get_option('page_for_posts') && is_archive() && !is_post_type_archive()) && !(is_tax('product_cat') || is_tax('product_tag')) || (get_option('page_for_posts') && is_search())) {
						$page_id = get_option('page_for_posts');		
				} else if (is_home()) {
                    $title = esc_html__('Home', 'workreap');
                }else {
					if(isset($object_id)) {
						$page_id = $object_id;
					}
				}
						
				if(function_exists('fw_get_db_settings_option')){
					$titlebar_type 		= fw_get_db_post_option($page_id, 'titlebar_type', true);
					$title 				= fw_get_db_post_option($page_id, 'titlebar_title', true);
					
					if(  isset( $titlebar_type['gadget'] ) && ( $titlebar_type['gadget'] === 'custom' ) ){
						$titlebar_bg_image 	    = !empty( $titlebar_type['custom']['titlebar_bg_image']['url'] ) ? $titlebar_type['custom']['titlebar_bg_image']['url'] : get_template_directory_uri().'/images/tb.jpg';
						$enable_breadcrumbs 	= !empty( $titlebar_type['custom']['enable_breadcrumbs'] ) ? $titlebar_type['custom']['enable_breadcrumbs'] : '';
						$titlebar_overlay 		= !empty( $titlebar_type['custom']['titlebar_overlay'] ) ? $titlebar_type['custom']['titlebar_overlay'] : 'rgba(0, 0, 0, 0)';
					} else {
						$titlebar_type 			= fw_get_db_settings_option('titlebar_type', true);
						$titlebar_bg_image 	    = !empty( $titlebar_type['default']['titlebar_bg_image']['url'] ) ? $titlebar_type['default']['titlebar_bg_image']['url'] : get_template_directory_uri().'/images/tb.jpg';
						$enable_breadcrumbs    	= !empty( $titlebar_type['default']['enable_breadcrumbs'] ) ? $titlebar_type['default']['enable_breadcrumbs'] : '';
						$titlebar_overlay 		= !empty( $titlebar_type['default']['titlebar_overlay'] ) ? $titlebar_type['default']['titlebar_overlay'] : 'rgba(0, 0, 0, 0)';
					}
					
				} else{
					$titlebar_bg_image 	    = get_template_directory_uri().'/images/tb.jpg';
					$enable_breadcrumbs 	= '';
					$titlebar_overlay 		= 'rgba(0, 0, 0, 0)';
				}
				
				$background_image	= '';

				if( isset( $titlebar_bg_image['url'] ) && !empty( $titlebar_bg_image['url'] ) ) {
					$background_image	= $titlebar_bg_image['url'];
				} else if( isset( $titlebar_bg_image ) && !empty( $titlebar_bg_image ) ) {
					 $background_image = $titlebar_bg_image;
				}
				
				
				//Title
				if( empty( $title ) || $title == 1 ) {
					$title	= esc_html( get_the_title( $page_id ));
				}
			}
			
			$background_image	= is_ssl() ? preg_replace("/^http:/i", "https:", $background_image ) : $background_image ;

			if( class_exists( 'Woocommerce' ) 
				&& is_woocommerce() 
				&& ( is_product() || is_shop() ) 
				&& ! is_search() 
			) {
				if( is_singular('product') ) {
					$title = get_the_title();
				}elseif( class_exists( 'Woocommerce' ) && is_checkout() ) {
					$title = esc_html__('Checkout', 'workreap');
				} else if( class_exists( 'Woocommerce' ) && is_cart() ) {
					$title = esc_html__('Cart', 'workreap');
				}else{
					$title = woocommerce_page_title( false );
				}
			}else if( class_exists( 'Woocommerce' ) && is_checkout() ) {
				$title = esc_html__('Checkout', 'workreap');
			} else if( class_exists( 'Woocommerce' ) && is_cart() ) {
				$title = esc_html__('Cart', 'workreap');
			} else if( is_home() || is_front_page() ) {
				//$title = esc_html__('Home', 'workreap');
			}
			
			
			
			//Custom Titles
			if( is_singular( 'projects' )){
				$title = esc_html__('Job Detail', 'workreap');
			} else if( is_singular( 'employers' )){
				$title = esc_html__('Company Detail', 'workreap');
			} else if( is_singular( 'freelancers' )){
				$title = esc_html__('Freelancer Detail', 'workreap');
			} else if( is_singular( 'proposals' )){
				$title = esc_html__('Job Proposal', 'workreap');
			} else if( is_singular( 'micro-services' )){
				$title = esc_html__('Service Detail', 'workreap');
			} else if( is_singular( 'wt_portfolio' )){
				$title = esc_html__('Portfolio Detail', 'workreap');
			}
		 
		 	if (function_exists('fw_get_db_settings_option')) {
				$header_type = fw_get_db_settings_option('header_type');
			}

			$column	= 'col-12 col-lg-10 push-lg-1';
			$titleClass	= ''; 
			if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v6' ){
				$column	= 'col-lg-4';
				$titleClass	= 'wt-bannertitletwo'; 
			}
		 ?>
         <div class="wt-haslayout wt-innerbannerholder wt-titlebardynmic" style="background-image: url('<?php echo esc_url( $background_image );?>');">
			<div class="container">
				<div class="row justify-content-md-center align-items-center">
					<div class="<?php echo esc_attr($column);?>">
						<div class="wt-innerbannercontent <?php echo esc_attr($titleClass);?>">
							<?php if( !empty( $title ) ){?><div class="wt-title"><h2><?php echo esc_html($title);?></h2></div><?php }?>
							<?php
								if( isset( $enable_breadcrumbs ) && $enable_breadcrumbs == 'enable' ) {
									if( function_exists('fw_ext_breadcrumbs') ) { fw_ext_breadcrumbs(''); }
								}
							?>
						</div>
					</div>
					<?php if( !empty( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v6' ){?>
						<div class="col-lg-8">
							<div class="wt-bannercontent wt-bannercontentseven">
								<?php 
									$header	= new Workreap_Prepare_Headers();
									$header->workreap_prepare_search_form();
								?>
							</div>
						</div>
					<?php }?>
				</div>
			</div>
		</div>
        <?php 
		}
    }
    new Workreap_Prepare_TitleBar();
}