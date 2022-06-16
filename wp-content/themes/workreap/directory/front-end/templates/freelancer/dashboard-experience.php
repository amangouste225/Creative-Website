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

$post_id 		= $linked_profile;
$experience 	= array();
if (function_exists('fw_get_db_post_option')) {
	$experience 	    = fw_get_db_post_option($post_id, 'experience', true);
}

?>
<div class="wt-userexperience wt-tabsinfo">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Add Your Experience', 'workreap'); ?></h2>
		<span class="wt-add-experience"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add Experience', 'workreap'); ?></a></span>
	</div>
	<ul class="wt-experienceaccordion accordion" id="expsortable">
		<?php 
			if( !empty( $experience ) && is_array($experience) ) { 
				$counter = 0;
				foreach ($experience as $key => $value) {
					$counter++;		
					$period 		= '';									
					$title 			= !empty( $value['title'] ) ? stripslashes($value['title']): '';
					$company  		= !empty( $value['company'] ) ? stripslashes($value['company']) : '';
					$startdate 		= !empty( $value['startdate'] ) ? str_replace('/','-',$value['startdate']) : '';
					$enddate 		= !empty( $value['enddate'] ) ? str_replace('/','-',$value['enddate']) : '';
					$description 	= !empty( $value['description'] ) ? wp_kses_post( stripslashes( $value['description'] ) ) : '';
					$start_date		= !empty( $startdate ) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix',$startdate ))) : '';
					$end_date 		= !empty( $enddate ) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix',$enddate )) ) : '';		

					if( empty( $end_date ) ){
						$end_date = '';
					}else{
						$end_date	= ' - ' .$end_date;
					}

					if( !empty( $start_date ) ){
						$period = $start_date.$end_date;		
					}

				?>
				<li class="dateinit-<?php echo esc_attr( $counter ); ?>">
					<div class="wt-accordioninnertitle">
						<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
						<span id="accordioninnertitle" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $counter ); ?>"><span class="wt-head-title"><?php echo esc_html( $title ); ?></span>&nbsp;<em><?php if( !empty( $period ) ) { ?>(<?php echo esc_html( $period ); ?>) <?php } ?></em></span>
						<div class="wt-rightarea">
							<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $counter ); ?>" aria-expanded="false"><i class="lnr lnr-pencil"></i></a>
							<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
						</div>
					</div>
					<div class="wt-collapseexp collapse" id="innertitle<?php echo esc_attr( $counter ); ?>" aria-labelledby="accordioninnertitle" data-parent="#accordion">
						<div class="wt-formtheme wt-userform">
							<fieldset>
								<div class="form-group form-group-half">
									<input type="text" name="settings[experience][<?php echo esc_attr( $counter ); ?>][title]" class="wt-head-input form-control" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php esc_attr_e('Add experience title', 'workreap'); ?>">
								</div>
								<div class="form-group form-group-half">
									<input type="text" name="settings[experience][<?php echo esc_attr( $counter ); ?>][startdate]" class="form-control wt-start-pick" value="<?php echo esc_attr( apply_filters('workreap_date_format_field',$startdate ) ); ?>" placeholder="<?php esc_attr_e('Starting date', 'workreap'); ?>">
								</div>
								<div class="form-group form-group-half">
									<input type="text" name="settings[experience][<?php echo esc_attr( $counter ); ?>][enddate]" class="form-control wt-end-pick" value="<?php echo esc_attr( apply_filters('workreap_date_format_field',$enddate ) ); ?>" placeholder="<?php esc_attr_e('Ending date *', 'workreap'); ?>">
								</div>
								<div class="form-group form-group-half">
									<input type="text" name="settings[experience][<?php echo esc_attr( $counter ); ?>][job]" class="form-control wt-job-title" value="<?php echo esc_attr( $company ); ?>" placeholder="<?php esc_attr_e('Company title', 'workreap'); ?>">
								</div>
								<div class="form-group">
									<textarea name="settings[experience][<?php echo esc_attr( $counter ); ?>][details]" class="form-control" placeholder="<?php esc_attr_e('Experience description', 'workreap' ); ?>"><?php echo esc_html( $description ); ?></textarea>
								</div>
								<div class="form-group">
									<span><?php esc_html_e('* Leave ending date empty if its your current job', 'workreap'); ?></span>
								</div>
							</fieldset>
						</div>
						<?php
							$script = "jQuery(document).ready(function (e) {
										init_datepicker_max('".esc_js( $counter )."','wt-start-pick','wt-end-pick');                    
									});";
							wp_add_inline_script('workreap-user-dashboard', $script, 'after');
						?>
					</div>
				</li>					
		<?php } } ?>
	</ul>
</div>
<script type="text/template" id="tmpl-load-experience">
<li class="dateinit-{{data.counter}}">
	<div class="wt-accordioninnertitle">
		<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
		<span id="accordioninnertitle" data-toggle="collapse" data-target="#innertitle{{data.counter}}"><span class="wt-head-title"><?php esc_html_e('Experience title', 'workreap'); ?></span>&nbsp;<em><?php esc_html_e('(Start date - End date)', 'workreap'); ?></em></span>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle" data-toggle="collapse" data-target="#innertitle{{data.counter}}" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
		</div>
	</div>
	<div class="wt-collapseexp collapse show" id="innertitle{{data.counter}}" aria-labelledby="accordioninnertitle" data-parent="#accordion">
		<div class="wt-formtheme wt-userform">
			<fieldset>
				<div class="form-group form-group-half">
					<input type="text" name="settings[experience][{{data.counter}}][title]" class="wt-head-input form-control" placeholder="<?php esc_attr_e('Add experience title', 'workreap'); ?>">
				</div>
				<div class="form-group form-group-half">
					<input type="text" name="settings[experience][{{data.counter}}][startdate]" class="form-control wt-start-pick" placeholder="<?php esc_attr_e('Starting date', 'workreap'); ?>">
				</div>
				<div class="form-group form-group-half">
					<input type="text" name="settings[experience][{{data.counter}}][enddate]" class="form-control wt-end-pick" placeholder="<?php esc_attr_e('Ending date *', 'workreap'); ?>">
				</div>
				<div class="form-group form-group-half">
					<input type="text" name="settings[experience][{{data.counter}}][job]" class="form-control wt-job-title" placeholder="<?php esc_attr_e('Company title', 'workreap'); ?>">
				</div>
				<div class="form-group">
					<textarea name="settings[experience][{{data.counter}}][details]" class="form-control" placeholder="<?php esc_attr_e('Experience description', 'workreap'); ?>"></textarea>
				</div>
				<div class="form-group">
					<span><?php esc_html_e('* Leave ending date empty if its your current job', 'workreap'); ?></span>
				</div>
			</fieldset>
		</div>
	</div>
</li>
</script>
<?php
$script = "jQuery(document).ready(function (e) {
			addSortable(expsortable);                    
		});";
wp_add_inline_script('workreap-user-dashboard', $script, 'after');