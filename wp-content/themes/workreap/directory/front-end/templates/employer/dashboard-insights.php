<?php
/**
 *
 * The template part for displaying the dashboard.
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
get_header();
global $current_user;
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$allow_freelancers_withdraw 	= fw_get_db_settings_option( 'allow_freelancers_withdraw', $default_value = null );
	$hide_payout_employers = fw_get_db_settings_option('hide_payout_employers');
}

$user_type	= apply_filters('workreap_get_user_type', $current_user->ID );
?>
<div class="wt-haslayout wt-moredetailsholder">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="row">
			<?php 
				if( apply_filters('workreap_employer_insights','messages') === true ){ get_template_part('directory/front-end/templates/dashboard', 'statistics-messages'); }
			
				if( apply_filters('workreap_system_access','job_base') === true ){
					if( apply_filters('workreap_employer_insights','latest_proposal') === true ){ get_template_part('directory/front-end/templates/dashboard', 'statistics-proposals');}
				}
					
				if( apply_filters('workreap_employer_insights','expiry_box') === true ){ get_template_part('directory/front-end/templates/dashboard', 'statistics-package-expiry');}
				if( apply_filters('workreap_employer_insights','saved_items') === true ){ get_template_part('directory/front-end/templates/dashboard', 'statistics-saved-items');}
				
				if( !empty($user_type) && $user_type === 'employer' && $hide_payout_employers === 'no' ){
					if( apply_filters('workreap_employer_insights','available_balance') === true ){ 
						if(!empty($allow_freelancers_withdraw) && $allow_freelancers_withdraw === 'freelancers'){
							get_template_part('directory/front-end/templates/dashboard-withdraw-available', 'balance'); 
						}else{
							get_template_part('directory/front-end/templates/dashboard', 'available-balance'); 
						}
					}
				}
			
				if( apply_filters('workreap_system_access','job_base') === true ){
					if( apply_filters('workreap_employer_insights','jobs') === true ){ get_template_part('directory/front-end/templates/employer/dashboard', 'insights-jobs-totals');}
				}
			
				if( apply_filters('workreap_system_access','service_base') === true ){
					if( apply_filters('workreap_employer_insights','services') === true ){ get_template_part('directory/front-end/templates/employer/dashboard', 'insights-services');}
				}
			?>
		</div>
	</div>
	<?php if( apply_filters('workreap_system_access','job_base') === true && apply_filters('workreap_employer_insights','ongoing_projects') === true ){ ?>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 float-left">
					<?php get_template_part('directory/front-end/templates/dashboard', 'insghts-ongoing-jobs');?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'package-detail');?>