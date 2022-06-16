<?php 
/**
 *
 * The template part for displaying the template to manage messages in project history
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;

$user_identity 	 	= $current_user->ID;
$url_identity 	 	= $user_identity;

$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$edit_id			= !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_type			= get_post_type($edit_id);

$user_type		= apply_filters('workreap_get_user_type', $user_identity );

if( !empty( $post_type ) && $post_type === 'services-orders') {
	$employeer_id				= get_post_field('post_author', $edit_id);
	$freelancer_id				= get_post_meta( $edit_id, '_service_author', true);

	$service_id					= get_post_meta( $edit_id, '_service_id', true);
	$hire_linked_profile		= workreap_get_linked_profile_id($freelancer_id); 
	$hired_freelancer_title 	= get_the_title( $hire_linked_profile );
	$title						= esc_html__('Service History', 'workreap');
	$post_status				= get_post_field('post_status',$edit_id);
	$post_comment_id			= $edit_id;
} else if( !empty( $post_type ) && $post_type === 'proposals') {	
	$proposal_id		= $edit_id;
	$title				= esc_html__('Project History', 'workreap');
	$post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
	$project_id			= get_post_meta( $proposal_id, '_project_id', true);
	$post_status		= get_post_field('post_status',$project_id);
} else {
	$proposal_id		= get_post_meta($edit_id,'_proposal_id',true);
	$title				= esc_html__('Project History', 'workreap');
	$post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
	$post_status		= get_post_field('post_status',$edit_id);
}

$description 		= '';
$name 				= 'chat_desc';	
$settings 			= array('media_buttons' => false,'textarea_name'=> $name,'editor_class'=> 'wt-tinymceeditor','quicktags' => false,'editor_height'=>300,'tinymce'       => array(
	'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,bullist,numlist,formatselect',
	'toolbar2'      => '',
	'toolbar3'      => '',
) );
$args 				= array(
						'post_id' => $post_comment_id
					);

$comments 			= get_comments( $args );
if( !empty( $post_comment_id ) ) {
?>
<div class="wt-haslayout wo-project-history">
	<div class="wt-tabscontenttitle">
		<h2><?php echo esc_html($title); ?></h2>
	</div>
	<div class="wt-historycontent">
		<ul id="accordion" class="wt-historycontentcol">
		<?php if( !empty( $comments ) ){ ?>
			<li class="wt-historycolhead">
				<h3>
					<span><?php esc_html_e('Date', 'workreap'); ?></span>
					<span><?php esc_html_e('Message', 'workreap'); ?></span>
					<span><?php esc_html_e('Attachment', 'workreap'); ?></span>
				</h3>
			</li>	
			<?php 
			$counter = 0;
			foreach ($comments as $key => $value) { 
					$counter++;
					$date 			= !empty( $value->comment_date ) ? $value->comment_date : '';
					$user_id 		= !empty( $value->user_id ) ? $value->user_id : '';
					$comments_ID 	= !empty( $value->comment_ID ) ? $value->comment_ID : '';
					$message 		= $value->comment_content;
					$date 			= !empty( $date ) ? date_i18n('F j, Y', strtotime($date)) : '';

					if ( apply_filters('workreap_get_user_type', $user_id) === 'employer' ){
						$employer_post_id   		= workreap_get_linked_profile_id($user_id);
						$avatar = apply_filters(
							'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_post_id), array('width' => 100, 'height' => 100) 
						);
					} else {
						$freelancer_post_id   		= workreap_get_linked_profile_id($user_id);
						$avatar = apply_filters(
							'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_post_id), array('width' => 100, 'height' => 100) 
						);
					}  

					$username 		= workreap_get_username( $user_id );		
					$project_files  = get_comment_meta( $value->comment_ID, 'message_files', true);
					?>
					<li class="collapsed" data-toggle="collapse" data-target="#collapse<?php echo esc_attr( $counter ); ?>">
						<div class="wt-dateandmsg">
							<span><img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $username ); ?>"><?php if( !empty( $date ) ){ echo esc_attr( $date ); } ?></span>
							<span><?php echo esc_html ( wp_strip_all_tags( $message ) ); ?></span>
						</div>
						<div class="wt-rightarea wt-msgbtns">
							<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-msgbtn"><i class="lnr lnr-chevron-up"></i><?php esc_html_e('Message', 'workreap'); ?></a>
							<?php if( !empty( $project_files ) ){ ?>
								<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo esc_attr( $comments_ID ); ?>" class="wt-btn wt-attachmentbtn wt-download-attachment"><i class="lnr lnr-download"></i><?php esc_html_e('Attachment(s)', 'workreap'); ?></a>
							<?php } ?>
						</div>
					</li>
					<li class="wt-historydescription collapse" id="collapse<?php echo esc_attr( $counter ); ?>" data-parent="#accordion">
						<div class="wt-description">
							<p><?php echo nl2br( do_shortcode( $message ) ); ?></p>
						</div>									
					</li>
				<?php }} else{ 
					if( $post_status === 'completed' || $post_status === 'cancelled') { 
						do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No history found.', 'workreap' ));
					}
				}
			?>
		</ul>
	<?php if( $post_status === 'hired' ) { ?>
		<form class="wt-formtheme wt-userform wt-project-chat-form">
			<fieldset>
				<div class="form-group">
					<?php wp_editor($description, 'wt-tinymceeditor', $settings);?>
				</div>								
				<div class="form-group form-group-label">
					<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
						<div class="" id="wt-project-container">
							<div class="wt-labelgroup" id="project-drag">
								<label for="file" class="wt-job-file">
									<span class="wt-btn" id="project-chat-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>
								</label>
								<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
								<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
							</div>
						</div>
						<div class="form-group">
							<ul class="wt-attachfile uploaded-placeholder"></ul>
						</div>											
					</div>
				</div>	
				<?php wp_nonce_field('wt_project_chat_data_nonce', 'message_submit'); ?>	
				<div class="form-group wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-submit-project-chat" data-id="<?php echo esc_attr( $post_comment_id ); ?>"><?php esc_html_e('Send Now', 'workreap'); ?></a>
				</div>
			</fieldset>
		</form>
	<?php } ?>
	</div>
	<script type="text/template" id="tmpl-load-project-chat-attachments">
		<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
			<span class="uploadprogressbar uploadprogressbar-0"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
			<input type="hidden" class="attachment_url" name="temp_files[]" value="{{data.url}}">	
		</li>
	</script>
	<script type="text/template" id="tmpl-load-project-chat">
	
		<li class="collapsed" data-toggle="collapse" data-target="#collapse{{data.counter}}">
			<div class="wt-dateandmsg">
				<span><img src="{{data.img}}" alt="{{data.name}}">{{data.date}}</span>
				<span>{{data.message}}</span>
			</div>
			<div class="wt-rightarea wt-msgbtns">
				<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-msgbtn"><i class="lnr lnr-chevron-up"></i><?php esc_html_e('Message', 'workreap'); ?></a>
				<# if( data.is_files == 'yes' ){ #>
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-attachmentbtn wt-download-attachment" data-id="{{data.comment_id}}"><i class="lnr lnr-download"></i><?php esc_html_e('Attachment', 'workreap'); ?></a>
				<# } #>
			</div>
		</li>
		<li class="wt-historydescription collapse active fade show" id="collapse{{data.counter}}" data-parent="#accordion">
			<div class="wt-description">
				<p>{{data.message}}</p>
			</div>
		</li>
	</script>
</div>
<?php } 