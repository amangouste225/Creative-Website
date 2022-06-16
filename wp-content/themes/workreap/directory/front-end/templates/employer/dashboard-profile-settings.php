<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$employer_avatar = apply_filters(
        'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $post_id), array('width' => 100, 'height' => 100) 
	);
$socialmediaurls	= array();
if( function_exists('fw_get_db_settings_option')  ){
	$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
	$hide_brochures       = fw_get_db_settings_option('hide_brochures', 'no');
}
$socialmediaurl 		= !empty($socialmediaurls) ? $socialmediaurls['gadget'] : '';
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9">		
	<form class="wt-user-profile">	
		<div class="wt-dashboardbox wt-dashboardtabsholder">
			<div class="wt-dashboardtabs">
				<ul class="wt-tabstitle nav navbar-nav">
					<li class="nav-item wt-list-skills">
						<a class="active" data-toggle="tab" href="#wt-skills"><?php esc_html_e('Personal details', 'workreap'); ?></a>
					</li>
					<?php if(!empty($socialmediaurl) && $socialmediaurl  ==='enable'){?>
						<li class="nav-item wt-list-socials-profile">
							<a data-toggle="tab" href="#wt-socials-profile"><?php esc_html_e('Social profiles', 'workreap'); ?></a>
						</li>
					<?php } ?>
					<?php if(!empty($hide_brochures) && $hide_brochures == 'no'){?>
						<li class="nav-item wt-list-brochures">
							<a data-toggle="tab" href="#wt-brochures"><?php esc_html_e('Brochures', 'workreap'); ?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div class="wt-tabscontent tab-content">
				<div class="wt-personalskillshold tab-pane active fade show" id="wt-skills">
					<?php get_template_part('directory/front-end/templates/employer/dashboard', 'basics'); ?>
					<?php get_template_part('directory/front-end/templates/employer/dashboard', 'avatar'); ?>
					<?php get_template_part('directory/front-end/templates/employer/dashboard', 'banner'); ?>
					<?php get_template_part('directory/front-end/templates/employer/dashboard', 'company-info'); ?>
					<?php get_template_part('directory/front-end/templates/employer/dashboard', 'location'); ?>
				</div>
				<?php if(!empty($socialmediaurl) && $socialmediaurl  ==='enable'){?>
					<div class="wt-personalskillshold wt-socials-profile tab-pane fade" id="wt-socials-profile">
					<?php get_template_part('directory/front-end/templates/dashboard', 'social-profile'); ?>
					</div>
				<?php } ?>
				<?php if(!empty($hide_brochures) && $hide_brochures == 'no'){?>
					<div class="wt-personalskillshold wt-brochures tab-pane fade" id="wt-brochures">
						<?php get_template_part('directory/front-end/templates/employer/dashboard', 'brochures'); ?>
					</div>
				<?php }?>
			</div>
		</div>
		<div class="wt-updatall">
			<?php wp_nonce_field('wt_employer_data_nonce', 'profile_submit'); ?>
			<i class="ti-announcement"></i>
			<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'workreap'); ?></span>
			<a class="wt-btn wt-update-profile-employer" data-id="<?php echo esc_attr( $user_identity ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
		</div>	
	</form>		
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-authorcodescan wt-codescanholder">
		<?php  do_action('workreap_get_qr_code','freelancer',intval( $post_id ));?>
	</div>
	<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
		<div class="wt-haslayout wt-dashside">
			<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
		</div>
	<?php }?>
</div>
