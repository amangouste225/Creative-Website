<?php 
/**
 *
 * The template part for displaying the freelancer profile basics
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
$experiences	= array();
$years 	 		= workreap_experience_years();
$display_type	= 'number';
if( function_exists('fw_get_db_settings_option')  ){
	$display_type	= fw_get_db_settings_option('display_type', $default_value = null);
}
$field_type		= !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Years','workreap');
$placeholder	= !empty($display_type) && ($display_type === 'number') ? esc_html__('add % value e.g. 95','workreap') : esc_html__('e.g 10 Year(s)','workreap');

if (function_exists('fw_get_db_post_option')) {
	$experiences 	 = fw_get_db_post_option($post_id, 'industrial_experiences', true);
}

$all_experiences 	 = workreap_get_texanomy_list('wt-industrial-experience');
?>
<div class="wt-skills">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Your industrial experience', 'workreap'); ?></h2>
	</div>
	<div class="wt-skillscontent-holder">
		<div class="wt-formtheme wt-userform wt-experience-form">
			<fieldset>
				<div class="form-group form-group-half">
					<span class="wt-select">
						<?php do_action('worktic_get_industrial_exprience_list','industrial_exprience','');?>
					</span>
				</div>
				<div class="form-group form-group-half toolip-wrapo">
					<?php if( !empty($display_type) && $display_type === 'year'){?>
						<span class="wt-select">
							<select class="industrial-val">
								<option value=""><?php esc_html_e('Years of Experience', 'workreap'); ?></option>
								<?php 
									if( !empty( $years ) ){							
										foreach ($years as $key => $value) {							
											?>
											<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
											<?php 
										}
									}
								?>											
							</select>
						</span>
					<?php }else{?>
						<input type="<?php echo esc_attr( $display_type );?>" class="form-control industrial-val" min="0" max="100" placeholder="<?php  echo esc_attr($placeholder); ?>" validate="true">
					<?php } ?>
					<?php do_action('workreap_get_tooltip','element','industrial_experience');?>
				</div>
				<div class="form-group wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-add-industrial-exp-box" data-display_type="<?php echo esc_attr($display_type);?>"><?php esc_html_e('Add industrial experience', 'workreap'); ?></a>
				</div>
			</fieldset>
		</div>
		<div class="wt-myskills wt-industrial-exprience">		
			<ul class="sortable list" id="experiences_sortable">
			<?php 
			if( !empty( $experiences ) && is_array($experiences) ){
				$skill_count = 0; 
				foreach ($experiences as $key => $value) {
					$skill_count++;
					$term_id 	= !empty( $value['exp'][0] ) ? $value['exp'][0] : '';
					$title 		= !empty( $term_id ) ? workreap_get_term_name($term_id , 'wt-industrial-experience') : '';
					$skill 		= !empty( $value['value'] ) ? $value['value'] : '';		
							
					if( !empty( $title ) && !empty( $term_id ) ){?>
					<li class="wt-skill-list dbskill-<?php echo esc_attr( $term_id ); ?>">
						<div class="wt-dragdroptool">
							<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-menu"></a>
						</div>
						<span class="skill-dynamic-html"><?php echo esc_html( $title ); ?> (<em class="skill-val"><?php echo esc_html( $skill ); ?></em>&nbsp;<?php echo esc_attr($field_type);?>)</span>
						<span class="skill-dynamic-field">
						<?php if( !empty($display_type) && $display_type === 'year'){?>
							<span class="wt-select">
								<select class="industrial-vals" id="industrial-val-<?php echo esc_attr( $skill_count ); ?>" name="settings[industrial_experiences][<?php echo esc_attr( $skill_count ); ?>][value]">
									<option value=""><?php esc_html_e('Years of Experience', 'workreap'); ?></option>
									<?php 
									if( !empty( $years ) ){	
										$selected	= '';						
										foreach ($years as $key => $value) {
											if( $skill == $key){
												$selected	= 'selected="select"';
											} else {
												$selected	= '';
											}							
											?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr($selected);?>><?php echo esc_html( $value ); ?></option>
											<?php 
										}
									}
									?>											
								</select>
							</span>
							
						<?php }else{?>
							<input type="text" name="settings[industrial_experiences][<?php echo esc_attr( $skill_count ); ?>][value]" value="<?php echo esc_attr( $skill ); ?>">
						<?php } ?>
							<input type="hidden" name="settings[industrial_experiences][<?php echo esc_attr( $skill_count ); ?>][exp]" value="<?php echo esc_attr( $term_id ); ?>">	
						</span>
						<div class="wt-rightarea">
							<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo" data-display_type="<?php echo esc_attr($display_type);?>"><i class="lnr lnr-pencil"></i></a>
							<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-skill"><i class="lnr lnr-trash"></i></a>
						</div>
					</li>		
				<?php } } } ?>						
			</ul>
		</div>
	</div>
</div>
<script type="text/template" id="tmpl-load-industrial_experiences">
	<li class="wt-skill-list dbskill-{{data.name}}"">
		<div class="wt-dragdroptool">
			<a href="javascript:" class="lnr lnr-menu"></a>
		</div>
		<span class="skill-dynamic-html">{{data.text}} (<em class="skill-val">{{data.value}}</em>&nbsp;<?php echo esc_attr($field_type);?>)</span>
		<span class="skill-dynamic-field">
		<?php if( !empty($display_type) && $display_type === 'year'){?>
				<span class="wt-select">
					<select class="skill-vals" id="industrial-val-{{data.counter}}" name="settings[industrial_experiences][{{data.counter}}][value]">
						<option value=""><?php esc_html_e('Years of Experience', 'workreap'); ?></option>
						<?php 
						if( !empty( $years ) ){							
							foreach ($years as $key => $value) {							
								?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
								<?php 
							}
						}
						?>											
					</select>
				</span>
				
			<?php }else{?>
				<input type="text" name="settings[industrial_experiences][{{data.counter}}][value]" value="{{data.value}}">
			<?php } ?>
			<input type="hidden" name="settings[industrial_experiences][{{data.counter}}][exp]" value="{{data.name}}">
		</span>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo" data-display_type="<?php echo esc_attr($display_type);?>"><i class="lnr lnr-pencil"></i></a>
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-skill"><i class="lnr lnr-trash"></i></a>
		</div>
	</li>
</script>