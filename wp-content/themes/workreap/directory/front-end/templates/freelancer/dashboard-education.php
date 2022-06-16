<?php
/**
 *
 * The template part for displaying the dashboard education
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
$education 		= array();

if (function_exists('fw_get_db_post_option')) {
	$education 	= fw_get_db_post_option($post_id, 'education', true);
}
?>
<div class="wt-userexperience">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Add Your Education', 'workreap'); ?></h2>
		<span class="wt-add-education"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add education', 'workreap'); ?></a></span>
	</div>
	<ul class="wt-experienceaccordion accordion" id="edusortable">
		<?php 
		if( !empty( $education ) && is_array($education) ){
			$counter_data = 0;
			foreach ($education as $key => $value) {			
			$counter 		= rand(99, 999);
			$period 		= '';
			$title 			= !empty( $value['title'] ) ? stripslashes( $value['title'] ) : '';
			$institute 		= !empty( $value['institute'] ) ? stripslashes( $value['institute'] ) : '';
			$startdate 		= !empty( $value['startdate'] ) ? $value['startdate'] : '';
			$enddate 		= !empty( $value['enddate'] ) ? $value['enddate'] : '';
			$description 	= !empty( $value['description'] ) ? wp_kses_post( stripslashes( $value['description'] ) ) : '';
			$start_date 	= !empty( $startdate ) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix',$startdate ))) : '';
			$end_date 		= !empty( $enddate ) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix',$enddate ))) : '';
				
			if( empty( $end_date ) ){
				$end_date = '';
			}else{
				$end_date	= ' - '.$end_date;
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
					<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle1" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $counter ); ?>" aria-expanded="false"><i class="lnr lnr-pencil"></i></a>
					<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
				</div>
			</div>
			<div class="wt-collapseexp collapse " id="innertitle<?php echo esc_attr( $counter ); ?>" aria-labelledby="accordioninnertitle1" data-parent="#accordion">
				<div class="wt-formtheme wt-userform">
					<fieldset>
						<div class="form-group form-group-half">
							<input type="text" name="settings[education][<?php echo esc_attr( $counter_data ); ?>][degree]" class="wt-head-input form-control" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php esc_attr_e('Degree title', 'workreap'); ?>">
						</div>
						<div class="form-group form-group-half">
							<input type="text" name="settings[education][<?php echo esc_attr( $counter_data ); ?>][startdate]" class="form-control wt-start-pick" value="<?php echo esc_attr( apply_filters('workreap_date_format_field',$startdate ) ); ?>" placeholder="<?php esc_attr_e('Starting date', 'workreap'); ?>">
						</div>
						<div class="form-group form-group-half">
							<input type="email" name="settings[education][<?php echo esc_attr( $counter_data ); ?>][enddate]" class="form-control wt-end-pick" value="<?php echo esc_attr( apply_filters('workreap_date_format_field',$enddate ) ); ?>" placeholder="<?php esc_attr_e('End date', 'workreap'); ?>">
						</div>
						<div class="form-group form-group-half">
							<input type="text" name="settings[education][<?php echo esc_attr( $counter_data ); ?>][university]" class="form-control" value="<?php echo esc_attr( $institute ); ?>" placeholder="<?php esc_attr_e('Institute name', 'workreap'); ?>">
						</div>
						<div class="form-group">
							<textarea name="settings[education][<?php echo esc_attr( $counter_data ); ?>][details]" class="form-control" placeholder="<?php  echo _x('Description', 'Description for education', 'workreap' );?>"><?php echo esc_textarea( $description ); ?></textarea>
						</div>
						<div class="form-group">
							<span><?php esc_html_e('* Leave ending date empty if its your current degree', 'workreap'); ?></span>
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
		<?php $counter_data++; } } ?>							
	</ul>
</div>
<script type="text/template" id="tmpl-load-education">
	<li class="dateinit-{{data.counter}}">
		<div class="wt-accordioninnertitle">
			<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
			<span id="accordioninnertitle1" data-toggle="collapse" data-target="#innertitle{{data.counter}}"><span class="wt-head-title"><?php esc_html_e('Education title', 'workreap'); ?></span>&nbsp;<em><?php esc_html_e('(Start date - End date)', 'workreap'); ?></em></span>
			<div class="wt-rightarea">
				<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle1" data-toggle="collapse" data-target="#innertitle{{data.counter}}" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
				<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
			</div>
		</div>
		<div class="wt-collapseexp collapse show" id="innertitle{{data.counter}}" aria-labelledby="accordioninnertitle1" data-parent="#accordion">
			<div class="wt-formtheme wt-userform">
				<fieldset>
					<div class="form-group form-group-half">
						<input type="text" name="settings[education][{{data.counter}}][degree]" class="wt-head-input form-control" placeholder="<?php esc_attr_e('Degree title', 'workreap'); ?>">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="settings[education][{{data.counter}}][startdate]" class="wt-start-pick form-control" placeholder="<?php esc_attr_e('Starting date', 'workreap'); ?>">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="settings[education][{{data.counter}}][enddate]" class="wt-end-pick form-control" placeholder="<?php esc_attr_e('Ending date', 'workreap'); ?>">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="settings[education][{{data.counter}}][university]" class="form-control" placeholder="<?php esc_attr_e('Institute name', 'workreap'); ?>">
					</div>
					<div class="form-group">
						<textarea name="settings[education][{{data.counter}}][details]" class="form-control" placeholder="<?php esc_attr_e('Description', 'workreap'); ?>"></textarea>
					</div>
					<div class="form-group">
						<span><?php esc_html_e('* Leave ending date empty if its your current degree', 'workreap'); ?></span>
					</div>
				</fieldset>
			</div>
		</div>
	</li>
</script>
<?php
$script = "jQuery(document).ready(function (e) {
		addSortable(edusortable);                    
	});";
	wp_add_inline_script('workreap-user-dashboard', $script, 'after');
