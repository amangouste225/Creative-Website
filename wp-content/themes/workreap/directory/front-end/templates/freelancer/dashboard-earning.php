<?php
/**
 *
 * The template part for displaying the freeelancer earning
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$wpdb;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$user_type			= apply_filters('workreap_get_user_type', $user_identity );
$date_formate		= get_option('date_format');
$earnings			= workreap_get_earning_freelancer($user_identity,6);
$currency			= workreap_get_current_currency();
?>
<div class="wt-dashboardbox wt-earningsholder">
	<div class="wt-dashboardboxtitle wt-titlewithsearch">
		<h2><?php esc_html_e('Past Earnings','workreap');?></h2>
		<?php if(!empty($earnings) && count( $earnings ) > 6 ) {?>
			<div class="wt-formtheme wt-formsearch">
				<fieldset>
					<div class="form-group">
						<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('earnings', $user_identity); ?>" class="wt-btn"><?php esc_html_e('View all','workreap');?></a>
					</div>
				</fieldset>
			</div>
		<?php } ?>
	</div>
	<?php if(!empty($earnings)) {?>
		<div class="wt-dashboardboxcontent">
			<table class="wt-tablecategories">
				<thead>
					<tr>
						<th><?php esc_html_e('Project Title','workreap');?></th>
						<th><?php esc_html_e('Date','workreap');?></th>
						<th><?php esc_html_e('Earnings','workreap');?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach( $earnings as $earning ) { 
							$project_title	= !empty($earning->project_id) ? esc_html( get_the_title($earning->project_id)) :"";
							$amount			= !empty($earning->freelancer_amount) ? floatval( $earning->freelancer_amount ) :0;
							$timestamp		= !empty($earning->process_date) ? $earning->process_date :'';
						?>
						<tr class="wt-earning-contents">
							<td class="wt-earnig-single"><?php echo esc_html($project_title);?></td>
							<td><?php echo date_i18n($date_formate ,strtotime($timestamp));?></td>
							<td><?php workreap_price_format($amount);?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	<?php 
		} else {
			do_action('workreap_empty_records_html','wt-empty-person',esc_html__( 'No Earning has been made yet.', 'workreap' ),true);
		}
	?>
</div>