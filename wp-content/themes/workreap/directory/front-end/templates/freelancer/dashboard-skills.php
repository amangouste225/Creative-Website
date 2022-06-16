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
$skills 		 = array();

if (function_exists('fw_get_db_post_option')) {
	$skills 	 = fw_get_db_post_option($post_id, 'skills', true);
}

$all_skills 	 = workreap_get_all_skills();
$years 	 		 = workreap_experience_years();

$display_type	= 'number';
if( function_exists('fw_get_db_settings_option')  ){
	$display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
	$allow_skills	= fw_get_db_settings_option('allow_skills');
}

$field_type		= !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Year(s)','workreap');
$year_text_singular		= esc_html__('Year','workreap');
$year_text_plural		= esc_html__('Years','workreap');
?>
<div class="wt-skills">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('My Skills', 'workreap'); ?></h2>
	</div>
	<div class="wt-skillscontent-holder">
		<div class="wt-formtheme wt-skillsforms wt-skillsform-load-temp">
			<fieldset>
				<div class="form-group form-group-half">
					<span class="wt-selects">
						<?php do_action('worktic_get_skills_list','skills','');?>
					</span>
				</div>
				<div class="form-group form-group-half">
					<?php if( !empty($display_type) && $display_type === 'year'){?>
							<span class="wt-select">
								<select class="skill-val wt-skill-val chosen-select">
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
						<input type="number" class="form-control wt-skill-val" min="0" max="100" placeholder="<?php  esc_html_e('add % value e.g. 95','workreap'); ?>" validate="true">
					<?php }?>
				</div>
				<div class="form-group wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-add-skill-box" data-display_type="<?php echo esc_attr($display_type);?>"><?php esc_html_e('Add Skills', 'workreap'); ?></a>
				</div>
				<?php if(!empty($allow_skills) && $allow_skills === 'yes'){?>
					<p><?php esc_html_e('Don’t see your option listed? ', 'workreap'); ?><a class="wt-create-custom-skills" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Create one.', 'workreap'); ?></a></p>
				<?php }?>
			</fieldset>
		</div>
		<div class="wt-myskills wt-listskill">		
			<ul class="sortable list" id="skills_sortable">
			<?php
			if( !empty( $skills ) && is_array($skills) ){
				$skill_count = 0; 
				foreach ($skills as $key => $value) {
					$skill_count++;
					$term_id 	= !empty( $value['skill'][0] ) ? $value['skill'][0] : '';
					$title 		= !empty( $term_id ) ? workreap_get_term_name($term_id , 'skills') : '';
					$skill 		= !empty( $value['value'] ) ? $value['value'] : '';
					if(!empty($display_type) && $display_type === 'year'){
						$key_type		= !empty($skill) && $skill == 1 ? esc_html__('Year','workreap') : esc_html__('Years','workreap');
					}else{
						$key_type		= esc_html__('%','workreap');
					}
					
					if( !empty( $title ) && !empty( $term_id ) ){?>
					<li class="wt-skill-list dbskill-<?php echo esc_attr( $term_id ); ?>">
						<div class="wt-dragdroptool">
							<a href="#" onclick="event_preventDefault(event);" class="fa fa-arrows-alt"></a>
						</div>
						<span class="skill-dynamic-html"><?php echo esc_html( $title ); ?> (<em class="skill-val"><?php echo esc_html( $skill ); ?></em>&nbsp;<?php echo esc_attr($key_type);?>)</span>
						<span class="skill-dynamic-field">
						<?php if( !empty($display_type) && $display_type === 'year'){?>
							<span class="wt-select">
								<select class="skill-val" id="skill-val-<?php echo esc_attr( $skill_count ); ?>" name="settings[skills][<?php echo esc_attr( $skill_count ); ?>][value]">
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
							<input type="number" name="settings[skills][<?php echo esc_attr( $skill_count ); ?>][value]" value="<?php echo esc_attr( $skill ); ?>">
						<?php }?>
							<input type="hidden" name="settings[skills][<?php echo esc_attr( $skill_count ); ?>][skill]" value="<?php echo esc_attr( $term_id ); ?>">	
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
<?php
$script = "jQuery(document).ready(function (e) {
		addSortable(skills_sortable);                    
	});";
	wp_add_inline_script('workreap-user-dashboard', $script, 'after');
?>
<script type="text/template" id="tmpl-load-skill">
	<li class="wt-skill-list dbskill-{{data.name}}">
		<div class="wt-dragdroptool">
			<a href="#" onclick="event_preventDefault(event);" class="fa fa-arrows-alt"></a>
		</div>
		<?php if(!empty($display_type) && $display_type === 'year'){?>
			<span class="skill-dynamic-html">{{data.text}} (<em class="skill-val">{{data.value}}</em>&nbsp;<# if( data.value == 1 ){#><?php echo esc_html($year_text_singular);?><# }else{ #><?php echo esc_html($year_text_plural);?><# } #>)</span>
		<?php }else{?>
			<span class="skill-dynamic-html">{{data.text}} (<em class="skill-val">{{data.value}}</em>&nbsp;<?php echo esc_attr($field_type);?>)</span>
		<?php }?>
		<span class="skill-dynamic-field">
			<?php if( !empty($display_type) && $display_type === 'year'){?>
				<span class="wt-select">
					<select class="skill-val" id="skill-val-{{data.counter}}" name="settings[skills][{{data.counter}}][value]">
						<option value=""><?php esc_html_e('Years of Experience', 'workreap'); ?></option>
						<?php 
							if( !empty( $years ) ){							
								foreach ($years as $key => $value) {?>
									<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
									<?php 
								}
							}
						?>											
					</select>
				</span>
				
			<?php }else{?>
				<input type="text" name="settings[skills][{{data.counter}}][value]" value="{{data.value}}">
			<?php }?>
			<input type="hidden" name="settings[skills][{{data.counter}}][skill]" value="{{data.name}}">
		</span>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo" data-display_type="<?php echo esc_attr($display_type);?>"><i class="lnr lnr-pencil"></i></a>
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-skill"><i class="lnr lnr-trash"></i></a>
		</div>
	</li>
</script>
<script type="text/template" id="tmpl-load-custom-skill">
	<li class="wt-skill-list">
		<div class="wt-dragdroptool">
			<a href="javascript:" class="fa fa-arrows-alt"></a>
		</div>
		<span class="skill-dynamic-html">{{data.text}} (<em class="skill-val">{{data.value}}</em>&nbsp;<?php echo esc_attr($field_type);?>)</span>
		<span class="skill-dynamic-field">
			<?php if( !empty($display_type) && $display_type === 'year'){?>
				<span class="wt-select">
					<select class="skill-val" id="skill-val-{{data.counter}}" name="settings[custom_skills][{{data.counter}}][value]">
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
				<input type="text" name="settings[custom_skills][{{data.counter}}][value]" value="{{data.value}}">
			<?php }?>
			<input type="hidden" name="settings[custom_skills][{{data.counter}}][skill]" value="{{data.name}}">
		</span>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo" data-display_type="<?php echo esc_attr($display_type);?>"><i class="lnr lnr-pencil"></i></a>
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-skill"><i class="lnr lnr-trash"></i></a>
		</div>
	</li>
</script>
<script type="text/template" id="tmpl-add-skill-custom">
<fieldset class="wt-skillsform wt-custom-skillsform">

	<div class="form-group-holder">
		<div class="form-group">
			<input type="text" class="form-control wt-custom-skill-title" placeholder="<?php esc_attr_e('Enter Your Skill', 'workreap'); ?>" validate="true">
		</div>
		<div class="form-group">
			<?php if( !empty($display_type) && $display_type === 'year'){?>
				<span class="wt-select">
					<select class="skill-val wt-custom-skill-val">
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
				<input type="number" class="form-control wt-custom-skill-val" min="0" max="100" placeholder="<?php  esc_html_e('add % value e.g. 95','workreap'); ?>" validate="true">
			<?php }?>
		</div>
	</div>
	<div class="form-group wt-btnarea">
		<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-add-custom-skill-box" data-display_type="<?php echo esc_attr($display_type);?>"><?php esc_html_e('Add Skill', 'workreap'); ?></a>
	</div>
</fieldset>
</script>