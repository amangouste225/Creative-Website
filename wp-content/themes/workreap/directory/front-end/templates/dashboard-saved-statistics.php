<?php
/**
 *
 * The template part for Save Statistics
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$total_job			= get_post_meta( $linked_profile, '_saved_projects', true);
$total_freelancers	= get_post_meta( $linked_profile, '_saved_freelancers', true);
$total_compnies		= get_post_meta( $linked_profile, '_following_employers', true);
$saved_services		= get_post_meta( $linked_profile, '_saved_services', true);

$total_job 			= !empty( $total_job ) ? count( $total_job ) : 0;
$total_freelancers 	= !empty( $total_freelancers ) ? count( $total_freelancers ) : 0;
$total_compnies 	= !empty( $total_compnies ) ? count( $total_compnies ) : 0;
$saved_services 	= !empty( $saved_services ) ? count( $saved_services ) : 0;

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$freelancer_img = fw_get_db_settings_option( 'total_freelancers' );
	$freelancer_img = !empty( $freelancer_img['url'] ) ? esc_url( $freelancer_img['url'] ) : '';
	
	$employer_img = fw_get_db_settings_option( 'total_employers' );
	$employer_img = !empty( $employer_img['url'] ) ? esc_url( $employer_img['url'] ) : '';
	
	$jobs_img = fw_get_db_settings_option( 'total_jobs' );
	$jobs_img = !empty( $jobs_img['url'] ) ? esc_url( $jobs_img['url'] ) : '';
	
	$total_services = fw_get_db_settings_option( 'total_services' );
	$total_services = !empty( $total_services['url'] ) ? esc_url( $total_services['url'] ) : '';
	
} else {
	$total_services		= '';
	$total_services		= '';
	$employer_img	= '';
	$freelancer_img = '';
}

?>
<aside id="wt-sidebar" class="wt-sidebar wt-dashboardsave">
	<?php if( apply_filters('workreap_system_access','job_base') === true ){?>
		<div class="wt-proposalsr">
			<div class="wt-proposalsrcontent">
				<?php if( !empty( $jobs_img )) { ?>
					<figure><img src="<?php echo esc_url( $jobs_img );?>" alt="<?php esc_attr_e('jobs','workreap');?>"></figure>
				<?php } else{?>
					<figure><span class="lnr lnr-graduation-hat"></span></figure>
				<?php }?>
				<div class="wt-title">
					<h3><?php echo intval($total_job);?></h3>
					<span><?php esc_html_e('Saved jobs','workreap');?></span>
				</div>
			</div> 
		</div>
	<?php }?>
	<?php if( apply_filters('workreap_system_access','service_base') === true ){?>
		<div class="wt-proposalsr">
			<div class="wt-proposalsrcontent  wt-freelancelike">
				<?php if( !empty( $total_services )) {?>
					<figure><img src="<?php echo esc_url( $total_services );?>" alt="<?php esc_attr_e('Services','workreap');?>"></figure>
				<?php } else{?>
					<figure><span class="lnr lnr-text-align-left"></span></figure>
				<?php }?>
				<div class="wt-title">
					<h3><?php echo intval($saved_services);?></h3>
					<span><?php esc_html_e('Saved services','workreap');?></span>
				</div>
			</div> 
		</div>
	<?php }?>
	<div class="wt-proposalsr">
		<div class="wt-proposalsrcontent wt-componyfolow">
			<?php if( !empty( $employer_img )) { ?>
				<figure><img src="<?php echo esc_url( $employer_img );?>" alt="<?php esc_attr_e('Employers','workreap');?>"></figure>
			<?php } else{?>
				<figure><span class="lnr lnr-apartment"></span></figure>
			<?php }?>
			<div class="wt-title">
				<h3><?php echo intval($total_compnies);?></h3>
				<span><?php esc_html_e('followed companies','workreap');?></span>
			</div>
		</div> 
	</div>								
	<div class="wt-proposalsr">
		<div class="wt-proposalsrcontent  wt-freelancelike">
			<?php if( !empty( $freelancer_img )) {?>
				<figure><img src="<?php echo esc_url( $freelancer_img );?>" alt="<?php esc_attr_e('Freelancers','workreap');?>"></figure>
			<?php } else{?>
				<figure><span class="lnr lnr-users"></span></figure>
			<?php }?>
			<div class="wt-title">
				<h3><?php echo intval($total_freelancers);?></h3>
				<span><?php esc_html_e('Liked freelancers','workreap');?></span>
			</div>
		</div> 
	</div>
	
	<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
		<div class="wt-haslayout wt-dashside wt-proposalsr">
			<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
		</div>
	<?php }?>								
</aside>