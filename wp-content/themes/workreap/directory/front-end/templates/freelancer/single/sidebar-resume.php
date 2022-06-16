<?php
/**
 *
 * The template used for displaying freelancer resume
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$current_user;
$post_id 	= $post->ID;

if (function_exists('fw_get_db_post_option')) {
	$resume  = fw_get_db_post_option($post_id, 'resume', true);
}

if ( is_user_logged_in() ) {
	$user_type	= apply_filters('workreap_get_user_type', $current_user->ID);
	if(!empty($user_type) && $user_type === 'freelancer'){
		$linked_id	= workreap_get_linked_profile_id($post_id,'post');
		
		if( intval( $linked_id ) !== intval( $current_user->ID ) ){
			return;
		}
	}	
}

$attachment	=  !empty($resume['attachment_id']) ? $resume['attachment_id'] : '';

if( !empty( $resume ) ){?>
	<div id="wt-resume" class="wt-widget resume-item-download">
		<div class="wt-widgetcontent wt-skillscontent data-list">
			<?php if ( is_user_logged_in() ) {?>
				<a href="javascript:void(0);" data-id="<?php echo esc_attr($attachment);?>" class="wt-download-single-file"><?php esc_html_e('Download my resume','workreap');?></a>
			<?php }else{?>
				<a href="javascript:void(0);" data-id="<?php echo esc_attr($attachment);?>" class="wt-download-login"><?php esc_html_e('Download my resume','workreap');?></a>
			<?php }?>
		</div>
	</div>
<?php }?>