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
global $current_user,$woocommerce;
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$allow_freelancers_withdraw 	= fw_get_db_settings_option( 'allow_freelancers_withdraw', $default_value = null );
}

if( apply_filters('workreap_system_access','job_base') === true 
   && apply_filters('workreap_freelancer_insights','jobs') === true
   && apply_filters('workreap_freelancer_insights','earnings') === true
){
	$column = 'col-xs-12 col-sm-12 col-md-12 col-lg-6 float-left';
}else{
	$column = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 float-left';
}

?>
<div class="wt-haslayout wt-moredetailsholder">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="row">
			<?php if( apply_filters('workreap_freelancer_insights','messages') === true ){ get_template_part('directory/front-end/templates/dashboard', 'statistics-messages'); }?>
			<?php 
				if( apply_filters('workreap_system_access','job_base') === true && apply_filters('workreap_freelancer_insights','latest_proposal') === true ){
					get_template_part('directory/front-end/templates/dashboard', 'statistics-proposals');
				}
			?>
			<?php if( apply_filters('workreap_freelancer_insights','expiry_box') === true ){ get_template_part('directory/front-end/templates/dashboard', 'statistics-package-expiry'); }?>
			<?php if( apply_filters('workreap_freelancer_insights','saved_items') === true ){ get_template_part('directory/front-end/templates/dashboard', 'statistics-saved-items'); }?>
			<?php if( apply_filters('workreap_freelancer_insights','pending_balance') === true ){ get_template_part('directory/front-end/templates/dashboard', 'pending-balance'); }?>
			<?php 
				if( apply_filters('workreap_freelancer_insights','available_balance') === true ){
					if(!empty($allow_freelancers_withdraw) && $allow_freelancers_withdraw === 'freelancers'){
						get_template_part('directory/front-end/templates/dashboard-withdraw-available', 'balance'); 
					}else{
						get_template_part('directory/front-end/templates/dashboard', 'available-balance'); 
					}
				}
			?>
			<?php 
				if( apply_filters('workreap_system_access','job_base') === true ){
					if( apply_filters('workreap_freelancer_insights','jobs') === true ){ get_template_part('directory/front-end/templates/freelancer/dashboard', 'insights-jobs-totals'); }
				}
					
				if( apply_filters('workreap_system_access','service_base') === true ){
					if( apply_filters('workreap_freelancer_insights','services') === true ){ get_template_part('directory/front-end/templates/freelancer/dashboard', 'insights-services'); }
				}
			?>
		</div>
	</div>
	<?php if( apply_filters('workreap_freelancer_insights','jobs') === true || apply_filters('workreap_freelancer_insights','earnings') === true ){?>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="row">
				<?php if( apply_filters('workreap_system_access','job_base') === true && apply_filters('workreap_freelancer_insights','jobs') === true ){ ?>
					<div class="<?php echo esc_attr($column);?>">
						<?php get_template_part('directory/front-end/templates/dashboard', 'insghts-ongoing-jobs');?>
					</div>
				<?php } ?>
				<?php if( apply_filters('workreap_freelancer_insights','earnings') === true ){ ?>
					<div class="<?php echo esc_attr($column);?>">
						<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'earning');?>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'package-detail');