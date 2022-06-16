<?php
/**
 *
 * The template part for displaying saved jobs
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles,$woocommerce;
$user_identity 	 = $current_user->ID;
$post_id 		 = workreap_get_linked_profile_id($user_identity);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$payrols	= workreap_get_payouts_lists();
?>
<div class="wt-uploadimages modal fade wt-withdraw-form" id="wt-withdraw-form" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="wt-modaldialog modal-dialog" role="document">
		<div class="wt-modalcontent modal-content">
			<div class="wt-boxtitle">
				<h2>
					<?php esc_html_e('Withdraw earnings','workreap');?>
					<i class="wt-btncancel fa fa-times" data-dismiss="modal" aria-label="<?php esc_attr_e('Close','workreap');?>"></i>
				</h2>
			</div>
			<div class="wt-modalbody modal-body">
				<form class="wt-formtheme wt-withdrawform wt-formfeedback">
					<fieldset>
						<div class="form-group">
							<p><?php esc_html_e('Please select one of the payment gateway to withdraw your earnings', 'workreap');?></p>
						</div>
						<div class="form-group">
							<span class="wt-select">
								<select name="withdraw[gateway]">
									<option value=""><?php esc_html_e('Select payment gateway', 'workreap'); ?></option>
									<?php foreach ($payrols as $key => $value) { ?>
										<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value['title'] ); ?></option>
									<?php } ?>										
								</select>
							</span>
						</div>
						<div class="form-group">
							<input type="text" class="form-control wt-numeric" name="withdraw[amount]" placeholder="<?php esc_attr_e('Add amount','workreap');?>*">
						</div>
						<div class="form-group wt-btnarea">
							<a class="wt-btn wt-add-withdraw" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Withdraw money','workreap');?></a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>