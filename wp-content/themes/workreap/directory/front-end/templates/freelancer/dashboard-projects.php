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
$projects 		= array();

if (function_exists('fw_get_db_post_option')) {
	$projects 		    = fw_get_db_post_option($post_id, 'projects', true);	
}

$default_img = get_template_directory_uri().'/images/project-65x65.jpg';

?>
<div class="wt-addprojectsholder">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Add Your Projects', 'workreap'); ?></h2>
		<span class="wt-add-project"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add project', 'workreap'); ?></a></span>
	</div>
	<ul class="wt-experienceaccordion accordion" data-id="<?php echo esc_url( $default_img ); ?>" id="projectsortable">
		<?php 
		if( !empty( $projects ) && is_array($projects) ) {		
			$counter = 0;	
			foreach ($projects as $key => $value) {
				$counter++;
				$count_rand = rand(89878, 2222);
				$title   	= !empty( $value['title'] ) ? stripslashes( $value['title'] ) : '';
				$link    	= !empty( $value['link'] ) ? $value['link'] : '#';
				$image   	= !empty( $value['image'] ) ? $value['image'] : array();	
				
				$img_url 	= !empty( $image ) ? wp_get_attachment_image_src( $image['attachment_id'], array(100,100), true ) : '';	
				
				if( empty( $img_url[0] ) ){
					$image_data = $default_img;
				} else {
					$image_data = $img_url[0];
				}

				$file_size 			= !empty( $image['attachment_id'] ) ? filesize( get_attached_file( $image['attachment_id'] ) ) : '';	
				$document_name   	= !empty( $image['attachment_id'] ) ? esc_html( get_the_title( $image['attachment_id'] ) ) : '';
				$filetype        	= !empty( $image['attachment_id'] ) ? wp_check_filetype( $image['url'] ) : '';
				$extension       	= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';	
				if( !empty($extension) && $extension ==='pdf' ){
					$image_data	= get_template_directory_uri() . '/images/pdf.jpg';
				}
				
				if( !empty( $title ) ){
			?>
			<li id="wt-award-<?php echo esc_attr( $count_rand ); ?>" data-id="<?php echo esc_attr( $count_rand ); ?>" class="wt-placehoder-img">
				<div class="wt-accordioninnertitle">
					<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
					<div class="wt-projecttitle collapsed" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $count_rand ); ?>">						
						<figure class="award-thumb">
							<img src="<?php echo esc_attr( $image_data ); ?>" alt="<?php echo esc_attr( $title ); ?>">
						</figure>						
						<h3>
							<span class="head-title"><?php echo esc_html( $title ); ?></span>
							<span class="head-sub-title"><?php echo esc_html( $link ); ?></span>
						</h3>
					</div>
					<div class="wt-rightarea">
						<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $count_rand ); ?>"><i class="lnr lnr-pencil"></i></a>
						<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
					</div>
				</div>
				<div class="wt-collapseexp collapse" id="innertitle<?php echo esc_attr( $count_rand ); ?>" aria-labelledby="accordioninnertitle" data-parent="#accordion">
					<div class="wt-formtheme wt-userform wt-formprojectinfo">
						<fieldset>
							<div class="form-group form-group-half">
								<input type="text" name="settings[project][<?php echo esc_attr( $count_rand ); ?>][title]" class="wt-input-title form-control" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php echo esc_attr_e('Project Title', 'workreap'); ?>">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="settings[project][<?php echo esc_attr( $count_rand ); ?>][link]" class="wt-input-subtitle form-control" value="<?php echo esc_attr( $link ); ?>" placeholder="<?php esc_attr_e('Project URL', 'workreap'); ?>">
							</div>
							<div class="form-group form-group-label" id="wt-award-container-<?php echo esc_attr( $count_rand ); ?>">
								<div class="wt-labelgroup" id="award-drag-<?php echo esc_attr( $count_rand ); ?>">
									<label for="file" class="wt-award-file">
										<span class="wt-btn" id="award-btn-<?php echo esc_attr( $count_rand ); ?>"><?php esc_html_e('Select File', 'workreap'); ?></span>								
									</label>
									<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
									<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
								</div>
							</div>					
							<div class="form-group uploaded-placeholder">
								<?php if( !empty( $image ) ){ ?>
									<ul class="wt-attachfile">
										<li class="wt-doc-parent">
											<span><?php echo esc_html( $document_name ); ?>.<?php echo esc_html( $extension ); ?></span>
											<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo esc_html( size_format($file_size, 2) ); ?><a href="#" onclick="event_preventDefault(event);" class="wt-remove-award-image lnr lnr-cross"></a></em>			
											<input type="hidden" name="settings[project][<?php echo esc_attr( $count_rand ); 
											?>][image][attachment_id]" value="<?php echo esc_attr( $image['attachment_id'] ); ?>">
											<input type="hidden" name="settings[project][<?php echo esc_attr( $count_rand ); 
											?>][image][url]" value="<?php echo esc_attr( $image['url'] ); ?>">
										</li>
									</ul>
								<?php } ?>
							</div>							
						</fieldset>
					</div>
				</div>
				<?php
					$inline_script = 'jQuery(document).on("ready", function() { init_image_uploader("' . esc_js( $count_rand ). '", "project"); });';
					wp_add_inline_script( 'workreap-user-dashboard', $inline_script, 'after' );
				?>
			</li>
		<?php } } } ?>
	</ul>
</div>
<script type="text/template" id="tmpl-load-project-image">
	<ul class="wt-attachfile">
		<li class="wt-uploading award-new-item wt-doc-parent" id="thumb-{{data.id}}">
			<span class="uploadprogressbar uploadprogressbar-0"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-award-image"></a></em>
			<input type="hidden" name="settings[{{data.type}}][{{data.counter}}][image]" value="{{data.url}}">	
		</li>
	</ul>
</script>
<script type="text/template" id="tmpl-load-project">
<li id="wt-award-{{data.counter}}" data-id="{{data.counter}}" class="wt-placehoder-img">
	<div class="wt-accordioninnertitle">
		<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
		<div class="wt-projecttitle collapsed" data-toggle="collapse" data-target="#innertitle-{{data.counter}}">
			<figure class="award-thumb"><img src="<?php echo esc_url( $default_img ); ?>" alt="<?php esc_attr_e('Title', 'workreap'); ?>"></figure>
			<h3><span class="head-title"><?php esc_html_e('Project Title Here', 'workreap'); ?></span><span class="head-sub-title"><?php esc_html_e('www.example.com', 'workreap'); ?></span></h3>
		</div>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" data-toggle="collapse" data-target="#innertitle-{{data.counter}}"><i class="lnr lnr-pencil"></i></a>
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
		</div>
	</div>
	<div class="wt-collapseexp collapse" id="innertitle-{{data.counter}}" aria-labelledby="accordioninnertitle" data-parent="#accordion">
		<div class="wt-formtheme wt-userform wt-formprojectinfo">
			<fieldset>
				<div class="form-group form-group-half">
					<input type="text" name="settings[project][{{data.counter}}][title]" class="wt-input-title form-control" placeholder="<?php esc_attr_e('Project Title', 'workreap'); ?>">
				</div>
				<div class="form-group form-group-half">
					<input type="text" name="settings[project][{{data.counter}}][link]" class="wt-input-subtitle form-control" placeholder="<?php esc_attr_e('Project URL', 'workreap'); ?>">
				</div>
				<div class="form-group form-group-label" id="wt-award-container-{{data.counter}}">
					<div class="wt-labelgroup" id="award-drag-{{data.counter}}">
						<label for="file">
							<span class="wt-btn" id="award-btn-{{data.counter}}"><?php esc_html_e('Select file', 'workreap'); ?></span>	
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
						<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>				
				<div class="form-group uploaded-placeholder"></div>				
			</fieldset>
		</div>
	</div>
</li>
</script>
<?php
$script = "jQuery(document).ready(function (e) {
		addSortable(projectsortable);                    
	});";
	wp_add_inline_script('workreap-user-dashboard', $script, 'after');
?>