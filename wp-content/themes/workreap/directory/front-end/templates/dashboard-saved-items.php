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
global $current_user;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$mode 			 = !empty($_GET['mode']) ? sanitize_text_field( $_GET['mode'] ) : 'jobs';
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9">		
	<form class="wt-user-profile">	
		<div class="wt-dashboardbox wt-dashboardtabsholder">
			<?php get_template_part('directory/front-end/templates/dashboard', 'saved-menu'); ?>	
			<div class="wt-tabscontent tab-content tab-savecontent">
				<?php if( $mode === 'jobs' && apply_filters('workreap_system_access','job_base') === true ) { ?>
					<div class="wt-personalskillshold tab-pane active fade show" id="wt-jobs">
						<?php get_template_part('directory/front-end/templates/dashboard', 'saved-jobs'); ?>	
					</div>
				<?php } ?>
				<?php if( $mode === 'employer' ) { ?>
					<div class="wt-educationholder tab-pane active fade show" id="wt-companies">
						<?php get_template_part('directory/front-end/templates/dashboard', 'saved-companies'); ?>	
					</div>
				<?php } ?>
				<?php if( $mode === 'freelancer' ) { ?>
					<div class="wt-awardsholder tab-pane active fade show" id="wt-freelancer">
						<?php get_template_part('directory/front-end/templates/dashboard', 'saved-freelancers'); ?>	
					</div>
				<?php } ?>
				<?php 
					if( $mode === 'service' ) { 
						if( apply_filters('workreap_system_access','service_base') === true ){ ?>
							<div class="wt-awardsholder tab-pane active fade show" id="wt-freelancer">
								<?php get_template_part('directory/front-end/templates/dashboard', 'saved-service'); ?>	
							</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</form>		
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
	<?php get_template_part('directory/front-end/templates/dashboard', 'saved-statistics'); ?>	
</div>