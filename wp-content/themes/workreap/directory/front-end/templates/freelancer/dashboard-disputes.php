<?php
/**
 *
 * The template part for dispute
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$url_identity 	 = $user_identity;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$list			 	= workreap_project_ratings('dispute_options_freelancer');
$user_type			= apply_filters('workreap_get_user_type', $user_identity );
$dispute_args = array('posts_per_page' => -1,
	'post_type' 		=> array('proposals'),
	'orderby' 			=> 'ID',
	'order' 			=> 'DESC',
	'author'			=> $user_identity,
	'post_status' 		=> array('publish', 'cancelled'),
	'suppress_filters'  => false,
	'meta_query'		=> array(
		'relation' => 'AND',
		array( 'key' 			=> '_send_by',
			   'value' 			=> $linked_profile,
			   'compare' 		=> '='
			),
		array(
			'key' => 'dispute',
			'compare' => 'NOT EXISTS'
			),
	)
);

$dispute_service_args = array('posts_per_page' => -1,
	'post_type' 		=> array( 'services-orders'),
	'orderby' 			=> 'ID',
	'order' 			=> 'DESC',
	'post_status' 		=> array('cancelled', 'hired'),
	'suppress_filters'  => false,
	'meta_query'		=> array(
		array( 'key' 			=> '_service_author',
			   'value' 			=> $user_identity,
			   'compare' 		=> '='
		),
		array(
			'key' => 'dispute',
			'compare' => 'NOT EXISTS'
			),
	)
);

$dispute_query 				= get_posts($dispute_args);
$dispute_service_query 		= get_posts($dispute_service_args);

if( !empty($dispute_query) && !empty( $dispute_service_query ) ){
	$dispute_query	= array_merge($dispute_query,$dispute_service_query);
} else if( empty($dispute_query) && !empty( $dispute_service_query ) ){
	$dispute_query	= $dispute_service_query;
} else if( !empty($dispute_query) && empty( $dispute_service_query ) ){
	$dispute_query	= $dispute_query;
} else{
	$dispute_query	= array();
}

?>
<div class="wt-uploadimages modal fade wt-dispute-form" id="wt-dispute-form" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="wt-modaldialog modal-dialog" role="document">
		<div class="wt-modalcontent modal-content">
			<div class="wt-boxtitle">
				<h2>
					<?php esc_html_e('Create Dispute','workreap');?>
					<i class="wt-btncancel fa fa-times" data-dismiss="modal" aria-label="<?php esc_attr_e('Close','workreap');?>"></i>
				</h2>
			</div>
			<div class="wt-modalbody modal-body">
				<form class="wt-formtheme wt-disputeform wt-formfeedback">
					<fieldset>
						<div class="form-group">
							<p><?php esc_html_e('You can only create a dispute against the cancelled and ongoing projects.', 'workreap');?></p>
						</div>
						<div class="form-group">
							<span class="wt-select">
								<select name="dispute[project]">
									<option value=""><?php esc_html_e('Select project/service', 'workreap'); ?></option>
									<?php
										foreach ($dispute_query as $key => $item) {
											$post_title	= $item->post_title;
											$post_type	= get_post_type($item->ID);
											if( !empty($post_type) && $post_type === 'services-orders' ){
												$post_author	= get_post_field( 'post_author', $item->ID );
												$user_id		= workreap_get_linked_profile_id($post_author);
												$user_name		= workreap_get_username('',$user_id);
												$post_title		= $post_title.' ('.$user_name.')';
											}
										?>
										<option value="<?php echo esc_attr( $item->ID ); ?>"><?php echo esc_html( $post_title ); ?></option>
									<?php } ?>										
								</select>
							</span>
						</div>
						<div class="form-group">
							<span class="wt-select">
								<select name="dispute[reason]">
									<option value=""><?php esc_html_e('Select reason', 'workreap'); ?></option>
									<?php foreach ($list as $key => $value) { ?>
										<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
									<?php } ?>										
								</select>
							</span>
						</div>
						<div class="form-group">
							<textarea class="form-control" name="dispute[description]" placeholder="<?php esc_attr_e('Add dispute detail','workreap');?>*"></textarea>
						</div>
						<div class="form-group wt-btnarea">
							<a class="wt-btn wt-add-dispute" href="#" onclick="event_preventDefault(event);">
								<?php esc_html_e('Send dispute','workreap');?>
							</a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>