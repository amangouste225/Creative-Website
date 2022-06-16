<?php
/**
 *
 * The template used for displaying freelancer post style
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

get_header();

do_action('workreap_restict_user_view_search'); //check user restriction

while ( have_posts() ) {
	the_post();
	global $post;
	$post_id = $post->ID;
	
	$linked_profile 	= get_post_meta($post_id, '_linked_profile', true);
	
	$freelancer_gallery_option	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$freelancer_gallery_option	= fw_get_db_settings_option('freelancer_gallery_option', $default_value = null);
	}

	$freelancer_specialization	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$freelancer_specialization	= fw_get_db_settings_option('freelancer_specialization', $default_value = null);
	}

	$freelancer_gallery_option	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$freelancer_gallery_option	= fw_get_db_settings_option('freelancer_gallery_option', $default_value = null);
	}
	
	$experience	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$experience	= fw_get_db_settings_option('freelancer_industrial_experience', $default_value = null);
	}

	$freelancer_faq_option	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$freelancer_faq_option	= fw_get_db_settings_option('freelancer_faq_option', $default_value = null);
	}
	
	$portfolio_settings	= apply_filters('workreap_portfolio_settings','no');
	?>
	<div class="wt-sectionspacetop wt-haslayout">
		<?php get_template_part('directory/front-end/templates/freelancer/single/banner'); ?>
		<div class="wt-sectionspacetop wt-haslayout">
			<?php 
				get_template_part('directory/front-end/templates/freelancer/single/basic'); 
				if( apply_filters('workreap_system_access','service_base') === true ){
					get_template_part('directory/front-end/templates/freelancer/single/services'); 
				}
			?>
			<div class="container">
				<div class="row">
					<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
						<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
							<div class="wt-usersingle">
								<?php 
									
									if(!empty($portfolio_settings) && $portfolio_settings === 'no' ){
										if(!empty($freelancer_gallery_option) && $freelancer_gallery_option === 'enable' ){
											get_template_part('directory/front-end/templates/freelancer/single/gallery');
										}
										
										get_template_part('directory/front-end/templates/freelancer/single/crafted-videos'); 
										get_template_part('directory/front-end/templates/freelancer/single/crafted-projects'); 
									}
	
									$portfolio_settings	= apply_filters('workreap_portfolio_settings','gadget');
									if( isset($portfolio_settings) && $portfolio_settings == 'enable' ){
										get_template_part('directory/front-end/templates/freelancer/single/portfolios-listing'); 
									}

									get_template_part('directory/front-end/templates/freelancer/single/experience'); 
									get_template_part('directory/front-end/templates/freelancer/single/education');
									
									if(!empty($freelancer_faq_option) && $freelancer_faq_option == 'yes' ) {
										get_template_part('directory/front-end/templates/dashboard', 'front-faq',array('post_id'=>$post->ID,'title'=> esc_html__('Fequently asked questions','workreap')));
									}
	
									if( apply_filters('workreap_system_access','job_base') === true ){
										get_template_part('directory/front-end/templates/freelancer/single/projects');
									}
								?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
							<aside id="wt-sidebar" class="wt-sidebar">
								<?php 
									get_template_part('directory/front-end/templates/freelancer/single/sidebar-social'); 
									get_template_part('directory/front-end/templates/freelancer/single/sidebar-resume'); 
									get_template_part('directory/front-end/templates/freelancer/single/sidebar-skills'); 
									
									if(!empty($freelancer_specialization) && $freelancer_specialization === 'enable' ){
										get_template_part('directory/front-end/templates/freelancer/single/sidebar-specialization');
									}
									
									if(!empty($experience) && $experience === 'enable' ){
										get_template_part('directory/front-end/templates/freelancer/single/sidebar-industrial_experiences');
									}
									
									get_template_part('directory/front-end/templates/freelancer/single/sidebar-awards');
									do_action('workreap_get_qr_code','freelancer',intval( $post_id ));
									get_template_part('directory/front-end/templates/freelancer/single/similar-freelancer');
									get_template_part('directory/front-end/templates/freelancer/single/languages');
									get_template_part('directory/front-end/templates/freelancer/single/english-level');
									get_template_part('directory/front-end/templates/freelancer/single/freelancer-type');
									get_template_part('directory/front-end/templates/freelancer/single/share'); 
									do_action('workreap_report_post_type_form',$post_id,'freelancer');
								?>
							</aside>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php do_action('workreap_chat_modal',$linked_profile, ''); 
		if ( is_user_logged_in() && $linked_profile != $current_user->ID && apply_filters('workreap_get_user_type', $current_user->ID ) === 'employer' ) {
			if( apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $linked_profile) === true ){
				if( apply_filters('workreap_chat_window_floating', 'disable') === 'enable' ){
					get_template_part('directory/front-end/templates/messages');
				}
			}
		}
	
		$script = "
				jQuery(document).ready(function () {
					var read_more      	= scripts_vars.read_more;
					var less      		= scripts_vars.less;
					var _readmore = jQuery('.wt-userdetails .wt-description');
					_readmore.readmore({
						speed: 500,
						collapsedHeight: 247,
						moreLink: '<a class=\"wt-btntext\" href=\"#\" onclick=\"event.preventDefualt();\">".esc_html__('Read More','workreap')."</a>',
						lessLink: '<a class=\"wt-btntext\" href=\"#\" onclick=\"event.preventDefualt();\">".esc_html__('Less','workreap')."</a>',
					});
				});
			";
			wp_add_inline_script( 'workreap-all', $script, 'after' );
		} 

		get_footer(); 
