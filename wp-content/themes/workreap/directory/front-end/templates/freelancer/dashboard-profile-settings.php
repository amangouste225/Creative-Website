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
$freelancer_avatar = apply_filters(
		'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $post_id ), array( 'width' => 225, 'height' => 225 )
	);

$freelancer_gallery_option	= '';
$freelancer_faq_option		= '';
$socialmediaurls	= array();
$experience	= '';
$specialization	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$freelancer_gallery_option	= fw_get_db_settings_option('freelancer_gallery_option', $default_value = null);
	$frc_remove_awards	= fw_get_db_settings_option('frc_remove_awards', $default_value = null);
	$specialization		= fw_get_db_settings_option('freelancer_specialization', $default_value = null);
	$experience			= fw_get_db_settings_option('freelancer_industrial_experience', $default_value = null);
	$socialmediaurl		= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
	$remove_experience	= fw_get_db_settings_option('frc_remove_experience', 'no');
	$remove_education	= fw_get_db_settings_option('frc_remove_education', 'no');
	$freelancer_faq_option	= fw_get_db_settings_option('freelancer_faq_option', $default_value = null);
}

$socialmediaurl 		= !empty($socialmediaurl['gadget'] ) ? $socialmediaurl['gadget'] : '';
$portfolio_settings		= apply_filters('workreap_portfolio_settings','no');

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9">		
	<form class="wt-user-profile">	
		<div class="wt-dashboardbox wt-dashboardtabsholder">
			<div class="wt-dashboardtabs">
				<ul class="wt-tabstitle nav navbar-nav">
					<li class="nav-item">
						<a class="active wt-list-basics" data-toggle="tab" href="#wt-skills"><?php esc_html_e('Personal Details &amp; Skills', 'workreap'); ?></a>
					</li>
					<?php if( ( !empty($remove_experience) && $remove_experience === 'no' ) || (!empty($remove_education) && $remove_education === 'no' ) ){?>
						<li class="nav-item wt-list-experience"><a data-toggle="tab" href="#wt-education"><?php esc_html_e('Experience &amp; Education', 'workreap'); ?></a></li>
					<?php } ?>
					<?php if(!empty($portfolio_settings) && $portfolio_settings === 'no' ){ ?>
						<li class="nav-item wt-list-projects"><a data-toggle="tab" href="#wt-projects"><?php esc_html_e('Projects', 'workreap'); ?></a></li>
					<?php } ?>
					<?php if(!empty($frc_remove_awards) && $frc_remove_awards === 'no' ){ ?>
						<li class="nav-item wt-list-awards"><a data-toggle="tab" href="#wt-awards"><?php esc_html_e('Awards/Certifications', 'workreap'); ?></a></li>
					<?php } ?>
					<?php if(!empty($portfolio_settings) && $portfolio_settings === 'no' ){ ?>
					<li class="nav-item wt-list-videos"><a data-toggle="tab" href="#wt-videos"><?php esc_html_e('Profile Videos', 'workreap'); ?></a></li>
					<?php } ?>
					<?php if(!empty($specialization) && $specialization === 'enable' ){ ?>
						<li class="nav-item wt-list-specialization"><a data-toggle="tab" href="#wt-specialization"><?php esc_html_e('Specialization', 'workreap'); ?></a></li>
					<?php } ?>
					<?php if(!empty($experience) && $experience === 'enable' ){ ?>
						<li class="nav-item wt-list-industrial"><a data-toggle="tab" href="#wt-industrial-experience"><?php esc_html_e('Industrial experience', 'workreap'); ?></a></li>
					<?php } ?>
					<?php if(!empty($socialmediaurl) && $socialmediaurl  ==='enable'){?>
						<li class="nav-item wt-list-socials">
							<a data-toggle="tab" href="#wt-socials-profile"><?php esc_html_e('Social profiles', 'workreap'); ?></a>
						</li>
					<?php } ?>
					<?php if(!empty($freelancer_faq_option) && $freelancer_faq_option == 'yes' ) {?>
						<li class="nav-item wt-list-socials">
							<a data-toggle="tab" href="#wt-faq-profile"><?php esc_html_e('Profile FAQ', 'workreap'); ?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div class="wt-tabscontent tab-content">
				<div class="wt-personalskillshold tab-pane active fade show" id="wt-skills">
					<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'basics'); ?>
					<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'avatar'); ?>
					<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'banner'); ?>
					<?php
						if(!empty($portfolio_settings) && $portfolio_settings === 'no' ){
							if(!empty($freelancer_gallery_option) && $freelancer_gallery_option === 'enable' ){
								get_template_part('directory/front-end/templates/freelancer/dashboard', 'gallery'); 
							}
						}
					?>
					<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'resume'); ?>
					<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'location'); ?>
					<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'skills'); ?>	
					
				</div>
				<?php if(!empty($freelancer_faq_option) && $freelancer_faq_option == 'yes' ) { ?>
					<div class="wt-faqholder tab-pane fade" id="wt-faq-profile">
						<?php get_template_part('directory/front-end/templates/dashboard', 'faq',array('post_id'=>$post_id,'title'=> esc_html__('FAQ list', 'workreap'),'add'=> esc_html__('+Add profile faq', 'workreap')) ); ?>
					</div>
				<?php } ?>
				<?php if( ( !empty($remove_experience) && $remove_experience === 'no' ) || (!empty($remove_education) && $remove_education === 'no' ) ){?>
					<div class="wt-educationholder tab-pane fade" id="wt-education">
						<?php if(!empty($remove_experience) && $remove_experience === 'no' ){get_template_part('directory/front-end/templates/freelancer/dashboard', 'experience');} ?>	
						<?php if(!empty($remove_education) && $remove_education === 'no' ){get_template_part('directory/front-end/templates/freelancer/dashboard', 'education');} ?>
					</div>
				<?php } ?>
				<?php if(!empty($portfolio_settings) && $portfolio_settings === 'no' ){ ?>
					<div class="wt-awardsholder tab-pane fade" id="wt-projects">
						<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'projects'); ?>
					</div>
				<?php } ?>
				<?php if(!empty($frc_remove_awards) && $frc_remove_awards === 'no' ){ ?>
					<div class="wt-awardsholder tab-pane fade" id="wt-awards">
						<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'awards'); ?>	
					</div>
				<?php } ?>
				<div class="wt-awardsholder tab-pane fade" id="wt-videos">
					<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'videos'); ?>
				</div>
				<?php if(!empty($specialization) && $specialization === 'enable' ){ ?>
					<div class="wt-awardsholder tab-pane fade" id="wt-specialization">
						<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'specialization'); ?>
					</div>
				<?php } ?>
				<?php if(!empty($experience) && $experience === 'enable' ){ ?>
					<div class="wt-awardsholder tab-pane fade" id="wt-industrial-experience">
						<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'industrial-experience'); ?>
					</div>
				<?php } ?>
				<?php if(!empty($socialmediaurl) && $socialmediaurl  ==='enable'){?>
					<div class="wt-personalskillshold wt-socials-profile  tab-pane fade" id="wt-socials-profile">
					<?php get_template_part('directory/front-end/templates/dashboard', 'social-profile'); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="wt-updatall">
			<?php wp_nonce_field('wt_freelancer_data_nonce', 'profile_submit'); ?>
			<i class="ti-announcement"></i>
			<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'workreap'); ?></span>
			<a class="wt-btn wt-update-profile-freelancer" data-id="<?php echo esc_attr( $user_identity ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
		</div>	
	</form>		
</div>
<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4 col-xl-3">
	<div class="wt-authorcodescan wt-codescanholder">
		<?php  do_action('workreap_get_qr_code','freelancer',intval( $post_id ));?>
	</div>
	<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
		<div class="wt-haslayout wt-dashside">
			<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
		</div>
	<?php }?>
</div>

