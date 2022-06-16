<?php
/**
 *
 * Class used as base to create theme footer
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
if (!class_exists('Workreap_Prepare_Footers')) {

    class Workreap_Prepare_Footers {

        function __construct() {
            add_action('workreap_do_process_footers', array(&$this, 'workreap_do_process_footers'));
        }

        /**
         * @Prepare Footer
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_footers() {?>
            </main>    
			<?php 
			$post_name = workreap_get_post_name();
			$footer_type  = array();
            if (function_exists('fw_get_db_settings_option')) {
                $footer_type = fw_get_db_settings_option('footer_type');
            }

			//demo ready
			if ( apply_filters('workreap_get_domain',false) === true ) {
				if( $post_name === "home-page-four" ){
					$footer_type['gadget'] = 'footer_v2';
				}
			}
			
			//hide for dashboard
			if (is_page_template('directory/dashboard.php')) {
				echo '</div>';
			} else{
				if( !empty( $footer_type['gadget'] ) && $footer_type['gadget'] === 'footer_v2' ){
					$this->workreap_do_process_footer_v2();
				} elseif( !empty( $footer_type['gadget'] ) && $footer_type['gadget'] === 'footer_v3' ){
					$this->workreap_do_process_footer_v3();
				} else {
					$this->workreap_do_process_footer_v1();
				}
			}

        }
        
        /**
         * @Prepare Footer V1
         * @return {}
         * @author amentotech
         */
        public static function workreap_do_process_footer_v1() {
            $footer_type = array();
            $footer_copyright = 'Copyright &copy; ' . date('Y') . '&nbsp;' . esc_html__('Workreap. All rights reserved.', 'workreap') . get_bloginfo();
            $enable_footer_menu = '';
            $question_title = '';
            $footer_email = '';
			
            if (function_exists('fw_get_db_settings_option')) {
                $footer_type 		= fw_get_db_settings_option('footer_type');
                $question_title 	= fw_get_db_settings_option('question_title');
                $footer_email 	= fw_get_db_settings_option('footer_email');
				$login_register = fw_get_db_settings_option('enable_login_register');
				$enable_login_register   = fw_get_db_settings_option('enable_login_register');
            }
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
            $menu  				= !empty($footer_type['footer_v1']['menu']) ? $footer_type['footer_v1']['menu'] : '';
            $footer_copyright   = !empty($footer_type['footer_v1']['copyright']) ? $footer_type['footer_v1']['copyright'] : $footer_copyright;
			$join   			= !empty($footer_type['footer_v1']['join']) ? $footer_type['footer_v1']['join'] : array();
			$widget_area   		= !empty($footer_type['footer_v1']['widget_area']) ? $footer_type['footer_v1']['widget_area'] : '';
			$footer_logo   		= !empty($footer_type['footer_v1']['footer_logo']) ? $footer_type['footer_v1']['footer_logo'] : '';
			$footer_content   	= !empty($footer_type['footer_v1']['footer_content']) ? $footer_type['footer_v1']['footer_content'] : '';
			$socials   			= !empty($footer_type['footer_v1']['socials']) ? $footer_type['footer_v1']['socials'] : array();
			$footer_links  		= !empty($footer_type['footer_v1']['footer_links']) ? $footer_type['footer_v1']['footer_links'] : '';
			$question_title  	= !empty($footer_type['footer_v1']['question_title']) ? $footer_type['footer_v1']['question_title'] : '';
			$footer_email  	= !empty($footer_type['footer_v1']['footer_email']) ? $footer_type['footer_v1']['footer_email'] : '';
			$signup_page_slug   = workreap_get_signup_page_url('step', '1');

			$is_active_widgets	= 'wt-widgets-active';
			$login_footer	= '';
			
			if (!empty($join) && $join['gadget'] === 'enable' && is_user_logged_in() ) {
				$login_footer	= 'wt-joininfo';
			}


			$is_auth			= !empty($login_register['gadget']) ? $login_register['gadget'] : ''; 
			$is_register		= !empty($login_register['enable']['registration']['gadget']) ? $login_register['enable']['registration']['gadget'] : ''; 
			
			$footer_logo		= !empty($footer_logo['url']) ? $footer_logo['url'] : '';
			//just for demo
			if ( apply_filters('workreap_get_domain',false) === true ) {
				$post_name = workreap_get_post_name();
				if( $post_name === "home-page-v5" ){
					$footer_logo = get_template_directory_uri() . '/images/logo_foot_v2.png';
				}
			}
			
            ob_start();
            ?>
            <footer id="wt-footer" class="wt-footer wt-footerfour wt-haslayout wt-footer-v1">
              <?php 
			  if( !empty( $menu ) && $menu === 'enable' && 
				 ( !empty( $footer_logo )
				  || !empty($footer_content)
				  || !empty($socials) )
			  ){?>
				  <div class="wt-footerholder wt-haslayout wt-sectionspace <?php echo esc_attr( $is_active_widgets );?>">
					<div class="container">
					  <div class="row">
					    <?php 
						if( !empty( $footer_logo )
							  || !empty($footer_content)
							  || !empty($socials)
							  || !empty($question_title)
							  || !empty($footer_email)
						){?>
							<div class="col-md-9 col-lg-6">
							  <div class="wt-footerlogohold"> 
								<?php if( !empty( $footer_logo ) ){?>
									<strong class="wt-logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url( $footer_logo );?>" alt="<?php echo esc_attr($blogname); ?>"></a></strong>
								<?php }?>
								<?php if( !empty( $footer_content ) ){?>
									<div class="wt-description">
									  <?php echo do_shortcode( $footer_content );?>
									</div>
								<?php }?>
								<?php if(!empty($question_title) || !empty($footer_email)) { ?>
									<div class="wt-questions-option">
										<?php if(!empty($question_title)) { ?><h4><?php echo esc_html($question_title); ?></h4> <?php } ?>
										<?php if(!empty($footer_email)) { ?>
											<span><?php esc_html_e('Email us at', 'workreap'); ?> <a href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a></span>
										<?php } ?>
									</div>
								<?php } ?>
								<?php if( !empty( $socials ) ){?>
									<ul class="wt-socialiconssimple wt-socialiconfooter">
										<?php
											foreach ($socials as $key => $value) {
												$social_name = !empty($value['social_name']) ? $value['social_name'] : '';
												$social_class = !empty($value['social_icons_list']) ? $value['social_icons_list'] : '';
												$social_link = !empty($value['social_url']) ? $value['social_url'] : '#';
												$social_main_class = '';
												if (!empty($social_class)) {
													$social_main_class	= workreap_get_social_icon_name($social_class);
												}
												
												if (!empty($social_class)) {?>
													<li class="<?php echo esc_attr($social_main_class); ?>"><a target="_blank" href="<?php echo esc_url($social_link); ?>"><i class="<?php echo esc_attr($social_class); ?>"></i></a></li>
													<?php
												}
											}
										?>
									</ul>
								<?php }?>
							  </div>
							</div>
						<?php }?>
						<?php 
						if( !empty( $footer_links ) ){
							$counter	= 0;
							foreach( $footer_links as $key => $value ){
								$counter++;
								?>
								<div class="col-sm-6 col-lg-3">
									<div class="wt-footercol wt-widgetcompany footer-link-<?php echo esc_attr( $counter );?>">
										<?php if( !empty( $value['heading'] ) ){?>
											<div class="wt-fwidgettitle">
												<h3><?php echo esc_html( $value['heading'] );?></h3>
											</div>
										<?php }?>
										<?php if( !empty( $value['links'] ) ){?>
											<ul class="wt-fwidgetcontent">
												<?php
												foreach( $value['links'] as $lkey => $item ){
													$target = !empty($item['target']) ? $item['target'] : '_self';
													if( !empty($item['text']) ){
													?>
													<li><a target="<?php echo esc_attr( $target );?>" href="<?php echo esc_url( $item['link'] );?>"><?php echo esc_html( $item['text'] );?></a></li>
												<?php }}?>
												<?php if( !empty( $value['more'] ) ){?>
													<li class="wt-viewmore"><a target="<?php echo esc_attr( $target );?>" href="<?php echo esc_url( $value['more'] );?>"><?php esc_html_e('+ View All','workreap');?></a></li>
												<?php }?>
											</ul>
										<?php }?>
									</div>
								</div>
						<?php }}?>
						<?php if ( is_active_sidebar('sidebar-footer-1') || is_active_sidebar('sidebar-footer-2') ) {?>
							<?php if (is_active_sidebar('sidebar-footer-1')) : ?>
								<div class="col-12 col-sm-6 col-md-3 col-lg-3">
									<?php dynamic_sidebar('sidebar-footer-1'); ?>
								</div>
							<?php endif; ?>
							<?php if (is_active_sidebar('sidebar-footer-2')) : ?>
								<div class="col-12 col-sm-6 col-md-3 col-lg-3">
									<div class="wt-footercol wt-widgetexplore"><?php dynamic_sidebar('sidebar-footer-2'); ?></div>
								</div>
							<?php endif; ?>
						<?php }?>
					  </div>
					</div>
				  </div>
			  <?php }?>
			  <?php if (!empty($join) && $join['gadget'] === 'enable' && !is_user_logged_in() ) { ?>
			  <div class="wt-haslayout wt-joininfo">
				<div class="container">
				  <div class="row justify-content-md-center">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 push-lg-1">
					  <?php 
						if( !empty( $join['enable']['title'] ) ){
							$content = strip_tags($join['enable']['title'], '<a><br>');
							?>
							<div class="wt-companyinfo"><span><?php echo do_shortcode($content); ?></span></div>
						<?php }?>
					  	<?php if ( !empty($is_register) && $is_register === 'enable' ) {?>
							<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
								<div class="wt-fbtnarea"><a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn"><?php esc_html_e('Join Now','workreap');?></a></div>
							<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
								<div class="wt-fbtnarea"><a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn"><?php esc_html_e('Join Now','workreap');?></a></div>
							<?php } else {?>
								<div class="wt-fbtnarea"><a href="<?php echo esc_url(  $signup_page_slug ); ?>" class="wt-btn"><?php esc_html_e('Join Now','workreap');?></a></div>
							<?php }?>
						<?php }?> 
				
					</div>
				  </div>
				</div>
			  </div>
			  <?php } ?>
			  <div class="wt-haslayout wt-footerbottom <?php echo esc_attr( $login_footer );?>">
				<div class="container">
				  <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				  	  <?php if (!empty($footer_copyright)) { ?>
							<p class="wt-copyrights"><?php echo do_shortcode($footer_copyright); ?></p>
					  <?php } ?>
					  <?php if (isset($menu) && $menu === 'enable') { ?>
						  <nav class="wt-addnav">
							<?php Workreap_Prepare_Headers::workreap_prepare_navigation('footer-menu', '', '', '0'); ?>
						  </nav>
					  <?php } ?>
					</div>
				  </div>
				</div>
			  </div>
			  <a id="wt-btnscrolltop" class="wt-btnscrolltop" href="javascript:void(0);"><i class="lnr lnr-chevron-up"></i></a>
			</footer>
            </div>
            <?php
            echo ob_get_clean();
		}
		
		/**
         * @Prepare Footer V2
         * @return {}
         * @author amentotech
         */
        public static function workreap_do_process_footer_v2() {
            $footer_type = array();
            $footer_copyright = 'Copyright &copy; ' . date('Y') . '&nbsp;' . esc_html__('Workreap. All rights reserved.', 'workreap') . get_bloginfo();
            $enable_footer_menu = '';
			
            if (function_exists('fw_get_db_settings_option')) {
                $footer_type = fw_get_db_settings_option('footer_type');
            }
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
            $menu  				= !empty($footer_type['footer_v2']['menu']) ? $footer_type['footer_v2']['menu'] : '';
            $footer_copyright   = !empty($footer_type['footer_v2']['copyright']) ? $footer_type['footer_v2']['copyright'] : $footer_copyright;
			$join   			= !empty($footer_type['footer_v2']['join']) ? $footer_type['footer_v2']['join'] : array();
			$footer_bg   		= !empty($footer_type['footer_v2']['footer_bg_img']['url']) ? $footer_type['footer_v2']['footer_bg_img']['url'] : '';
			$footer_logo   		= !empty($footer_type['footer_v2']['footer_logo']['url']) ? $footer_type['footer_v2']['footer_logo']['url'] : '';
			$footer_content   	= !empty($footer_type['footer_v2']['footer_content']) ? $footer_type['footer_v2']['footer_content'] : '';
			$socials   			= !empty($footer_type['footer_v2']['socials']) ? $footer_type['footer_v2']['socials'] : array();
			$newsletter_img   	= !empty($footer_type['footer_v2']['newsletter_img']['url']) ? $footer_type['footer_v2']['newsletter_img']['url'] : '';
			$primary_color      = !empty($footer_type['footer_v2']['primary_color']) ? $footer_type['footer_v2']['primary_color'] : '';
			$secondary_color   	= !empty($footer_type['footer_v2']['secondary_color']) ? $footer_type['footer_v2']['secondary_color'] : '';
			
            ob_start();
            ?>
            <footer id="wt-footer" class="wt-footertwo wt-haslayout">
              <?php 
			  if( !empty( $footer_logo )
				  || !empty($footer_bg)
				  || !empty($newsletter_img)
				  || !empty($footer_content)
				  || !empty($socials)
			  ){?>
			   <div class="wt-footer-bg" style="background-image:url(<?php echo esc_url($footer_bg); ?>)"></div>
				<div class="wt-footerholder wt-haslayout">
					<div class="container">
						<div class="row">
						<?php if( !empty( $footer_logo )
								|| !empty($footer_content)
								|| !empty($socials)
							){?>
							<div class="col-lg-6">
								<div class="wt-footerlogohold"> 
								<?php if( !empty( $footer_logo ) ){?>
									<strong class="wt-logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url( $footer_logo );?>" alt="<?php echo esc_attr($blogname); ?>"></a></strong>
								<?php }?>
								<?php if( !empty( $footer_content ) ){?>
									<div class="wt-description">
										<?php echo do_shortcode( $footer_content );?>
									</div>
								<?php }?>
								<?php if( !empty( $socials ) ){?>
									<ul class="wt-socialiconssimple wt-socialiconfooter">
										<?php
											foreach ($socials as $key => $value) {
												$social_name = !empty($value['social_name']) ? $value['social_name'] : '';
												$social_class = !empty($value['social_icons_list']) ? $value['social_icons_list'] : '';
												$social_link = !empty($value['social_url']) ? $value['social_url'] : '#';
												$social_main_class = '';
												if (!empty($social_class)) {
													$social_main_class	= workreap_get_social_icon_name($social_class);
												}
												
												if (!empty($social_class)) {?>
													<li class="wt-<?php echo esc_attr($social_main_class); ?>"><a target="_blank" href="<?php echo esc_url($social_link); ?>"><i class="<?php echo esc_attr($social_class); ?>"></i></a></li>
													<?php
												}
											}
										?>
									</ul>
								<?php }?>
								</div>
							</div>
						<?php }?>
						<?php if ( is_active_sidebar('sidebar-footer-1') || is_active_sidebar('sidebar-footer-2') ) {?>
							<?php if (is_active_sidebar('sidebar-footer-1')) : ?>
								<div class="col-sm-6 col-lg-3">
									<?php dynamic_sidebar('sidebar-footer-1'); ?>
								</div>
							<?php endif; ?>
							<?php if (is_active_sidebar('sidebar-footer-2')) : ?>
								<div class="col-sm-6 col-lg-3">
									<?php dynamic_sidebar('sidebar-footer-2'); ?>
								</div>
							<?php endif; ?>
						<?php }?>
						</div>
					</div>
				</div>
			  <?php }?>
			  <?php if (!empty($join['gadget']) && $join['gadget'] === 'enable') { ?>
			  <div class="wt-haslayout wt-joininfotwo wt-joininfotwonew">
				<div class="container">
				  <div class="row justify-content-md-center">
					<div class="col-12">
						<?php if(!empty($newsletter_img)) { ?>
							<figure class="wt-joininfotwo-img">
								<img src="<?php echo esc_url($newsletter_img); ?>" alt="<?php esc_attr_e('Newsletter', 'workreap'); ?>">
							</figure>
						<?php } ?>
						<?php if(class_exists('Workreap_MailChimp')) {
								$mailchimp = new Workreap_MailChimp();
								$mailchimp->workreap_mailchimp_form();
							}
						?>
					</div>
				  </div>
				</div>
			  </div>
			  <?php } ?>
			  <?php if (!empty($footer_copyright) || (isset($menu) && $menu === 'enable') ) { ?>
				  <div class="wt-haslayout wt-footerbottom">
					<div class="container">
					  <div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						  <?php if (!empty($footer_copyright)) { ?>
								<p class="wt-copyrights"><?php echo do_shortcode($footer_copyright); ?></p>
						  <?php } ?>
						  <?php if (isset($menu) && $menu === 'enable') { ?>
							  <nav class="wt-addnav">
								<?php Workreap_Prepare_Headers::workreap_prepare_navigation('footer-menu', '', '', '0'); ?>
							  </nav>
						  <?php } ?>
						</div>
					  </div>
					</div>
				  </div>
			  <?php } ?>
			  <a id="wt-btnscrolltop" class="wt-btnscrolltop" href="javascript:void(0);"><i class="lnr lnr-chevron-up"></i></a>
			</footer>
			</div>
			<?php
			
            echo ob_get_clean();
		}
		
		/**
         * @Prepare Footer V2
         * @return {}
         * @author amentotech
         */
        public static function workreap_do_process_footer_v3() {
            $footer_type        = array();
            $footer_copyright   = 'Copyright &copy; ' . date('Y') . '&nbsp;' . esc_html__('Workreap. All rights reserved.', 'workreap') . get_bloginfo();
            $enable_footer_menu = '';
			
            if (function_exists('fw_get_db_settings_option')) {
                $footer_type = fw_get_db_settings_option('footer_type');
            }
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
            $menu  				= !empty($footer_type['footer_v3']['menu']) ? $footer_type['footer_v3']['menu'] : '';
            $footer_copyright   = !empty($footer_type['footer_v3']['copyright']) ? $footer_type['footer_v3']['copyright'] : $footer_copyright;
			$join   			= !empty($footer_type['footer_v3']['join']) ? $footer_type['footer_v3']['join'] : array();
			$newsletter_img   	= !empty($footer_type['footer_v3']['newsletter_img']['url']) ? $footer_type['footer_v3']['newsletter_img']['url'] : '';
			$footer_logo   		= !empty($footer_type['footer_v3']['footer_logo']['url']) ? $footer_type['footer_v3']['footer_logo']['url'] : '';
			$footer_content   	= !empty($footer_type['footer_v3']['footer_content']) ? $footer_type['footer_v3']['footer_content'] : '';
			$socials   			= !empty($footer_type['footer_v3']['socials']) ? $footer_type['footer_v3']['socials'] : array();
			$footer_bg 			= get_template_directory_uri().'/images/homeseven/footer.svg';
			$primary_color      = !empty($footer_type['footer_v3']['primary_color']) ? $footer_type['footer_v3']['primary_color'] : '';
			$secondary_color   	= !empty($footer_type['footer_v3']['secondary_color']) ? $footer_type['footer_v3']['secondary_color'] : '';
			
            ob_start();
            ?>
            <footer id="wt-footer" class="wt-footertwo wt-footerthree wt-footerthreevtwo wt-haslayout">
              <?php if( !empty( $footer_logo )
				  || !empty($newsletter_img)
				  || !empty($footer_content)
				  || !empty($socials)
			  ){?>
				<div class="wt-footerholder wt-haslayout">
					<div class="container">
						<div class="row">
						<?php if( !empty( $footer_logo )
							|| !empty($footer_content)
							|| !empty($socials)
						){?>
							<div class="col-lg-6">
								<div class="wt-footerlogohold"> 
								<?php if( !empty( $footer_logo ) ){?>
									<strong class="wt-logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url( $footer_logo );?>" alt="<?php echo esc_attr($blogname); ?>"></a></strong>
								<?php }?>
								<?php if( !empty( $footer_content ) ){?>
									<div class="wt-description">
										<?php echo do_shortcode( $footer_content );?>
									</div>
								<?php }?>
								<?php if( !empty( $socials ) ){?>
									<ul class="wt-socialiconssimple wt-socialiconfooter">
										<?php
											foreach ($socials as $key => $value) {
												$social_name = !empty($value['social_name']) ? $value['social_name'] : '';
												$social_class = !empty($value['social_icons_list']) ? $value['social_icons_list'] : '';
												$social_link = !empty($value['social_url']) ? $value['social_url'] : '#';
												$social_main_class = '';
												if (!empty($social_class)) {
													$social_main_class	= workreap_get_social_icon_name($social_class);
												}
												
												if (!empty($social_class)) {?>
													<li class="wt-<?php echo esc_attr($social_main_class); ?>"><a target="_blank" href="<?php echo esc_url($social_link); ?>"><i class="<?php echo esc_attr($social_class); ?>"></i></a></li>
													<?php
												}
											}
										?>
									</ul>
								<?php }?>
								</div>
							</div>
						<?php }?>
						<?php if ( is_active_sidebar('sidebar-footer-1') || is_active_sidebar('sidebar-footer-2') ) {?>
							<?php if (is_active_sidebar('sidebar-footer-1')) : ?>
								<div class="col-sm-6 col-lg-3">
									<?php dynamic_sidebar('sidebar-footer-1'); ?>
								</div>
							<?php endif; ?>
							<?php if (is_active_sidebar('sidebar-footer-2')) : ?>
								<div class="col-sm-6 col-lg-3">
									<?php dynamic_sidebar('sidebar-footer-2'); ?>
								</div>
							<?php endif; ?>
						<?php }?>
						</div>
					</div>
				</div>
			  <?php }?>
			  <?php if ( !empty($join) && $join['gadget'] === 'enable' ) { ?>
			  <div class="wt-haslayout wt-joininfotwo wt-joininfotwonew">
				<div class="container">
				  <div class="row justify-content-md-center">
				  	<div class="col-12">
						<?php if(!empty($newsletter_img)) { ?>
							<figure class="wt-joininfotwo-img">
								<img src="<?php echo esc_url($newsletter_img); ?>" alt="<?php esC_attr_e('Newsletter', 'workreap'); ?>">
							</figure>
						<?php } ?>
						<?php if(class_exists('Workreap_MailChimp')) {
							$mailchimp = new Workreap_MailChimp();
							$mailchimp->workreap_mailchimp_form();
						} ?>
					</div>
				  </div>
				</div>
			  </div>
			  <?php } ?>
			  <?php if (!empty($footer_copyright) || (isset($menu) && $menu === 'enable') ) { ?>
				  <div class="wt-haslayout wt-footerbottom">
					<div class="container">
					  <div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						  <?php if (!empty($footer_copyright)) { ?>
								<p class="wt-copyrights"><?php echo do_shortcode($footer_copyright); ?></p>
						  <?php } ?>
						  <?php if (isset($menu) && $menu === 'enable') { ?>
							  <nav class="wt-addnav">
								<?php Workreap_Prepare_Headers::workreap_prepare_navigation('footer-menu', '', '', '0'); ?>
							  </nav>
						  <?php } ?>
						</div>
					  </div>
					</div>
				  </div>
			 <?php } ?>
			 <a id="wt-btnscrolltop" class="wt-btnscrolltop" href="javascript:void(0);"><i class="lnr lnr-chevron-up"></i></a>
			</footer>
            </div>
			<?php 
            echo ob_get_clean();
        }

    }

    new Workreap_Prepare_Footers();
}