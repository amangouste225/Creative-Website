<?php
/**
 * Dashboard backend
 *
 * @package Workreap
 * @since Workreap 1.0
 * @desc Template used for front end dashboard.
 */
/* Define Global Variables */

/**
 * @User Public Profile
 * @return {}
 */
if (!function_exists('workreap_edit_user_profile_edit')) {

    function workreap_edit_user_profile_edit($user) {
		
		if ( ( $user->roles[0] === 'freelancers' || $user->roles[0] === 'employers' ) ){
			$profile_settings	= workreap_profile_backend_settings();
			$profile_settings	= apply_filters('workreap_filter_profile_back_end_settings',$profile_settings);

			foreach( $profile_settings as $key => $value  ){
				get_template_part('directory/back-end/author-partials/template-author', $key);
			}
		} else if ( $user->roles[0] === 'administrator' ){
			$display_img_url 			= '';
			$display = $display_image 	= 'block';
			$display_img_url 			= workreap_get_user_avatar( 0, $user->ID );
			
			if ( empty( $display_img_url ) ) {
				$display_image = 'elm-display-none';
			}
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th><?php esc_html_e('Display Photo', 'workreap'); ?></th>
						<td>
							<input type="hidden" name="author_profile_avatar" class="media-image" id="author_profile_avatar" value="<?php echo workreap_get_user_avatar(0, $user->ID); ?>"/>
							<input type="button" id="upload-user-avatar" class="button button-secondary" value="<?php esc_attr_e('Upload Public Avatar', 'workreap'); ?>"/>
						</td>
					</tr>
					<tr id="avatar-wrap" class="<?php echo esc_attr($display_image); ?>">
						<td class="backgroud-image">
							<a href="#" onclick="event_preventDefault(event);" class="delete-auhtor-media"><i class="fa fa-times"></i></a>
							<img class="avatar-src-style" height="100px" src="<?php echo esc_url($display_img_url); ?>" id="avatar-src"/>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
	}
}


/**
 * @Get User Avatar
 * @return {}
 */
if ( !function_exists( 'workreap_get_user_avatar' ) ) {

	function workreap_get_user_avatar( $size = 0, $workreap_user_id = '' ) {
		if ( $workreap_user_id != '' ) {
			$workreap_user_avatars = get_the_author_meta( 'author_profile_avatar', $workreap_user_id );
			if ( is_array( $workreap_user_avatars ) && isset( $workreap_user_avatars[ $size ] ) ) {
				return $workreap_user_avatars[ $size ];
			} else if ( !is_array( $workreap_user_avatars ) && $workreap_user_avatars <> '' ) {
				return $workreap_user_avatars;
			}
		}
	}

}

/**
 * @User Public Profile Save
 * @return {}
 */
if (!function_exists('workreap_personal_options_save')) {

    function workreap_personal_options_save($user_identity) {
        if ( current_user_can('edit_user',$user_identity) ) {
			$current_date		= current_time('mysql');
			$post_package		= !empty($_POST['package_id']) ? intval( $_POST['package_id'] ) : '';
			$package_include	= !empty($_POST['package_include']) ? intval( $_POST['package_include'] ) : '';
			$package_exclude	= !empty($_POST['package_exclude']) ? intval( $_POST['package_exclude'] ) : '';
			$wt_subscription	= array();
			
			if( !empty( $post_package ) ) {
				workreap_update_pakage_data( $post_package, $user_identity, '' );
			}
		}
		
		//admin profile update
		$author_profile_avatar = !empty( $_POST[ 'author_profile_avatar' ] ) ? sanitize_text_field( $_POST[ 'author_profile_avatar' ] ) : '0';
		if( !empty( $author_profile_avatar ) ){
			update_user_meta( $user_identity, 'author_profile_avatar', $author_profile_avatar );
		}
		
	}
}