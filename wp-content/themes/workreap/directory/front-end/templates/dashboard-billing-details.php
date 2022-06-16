<?php 
/**
 *
 * The template part for displaying the template reset password
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post,$woocommerce;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
if (class_exists('WooCommerce')) {
	$countries_obj   = new WC_Countries();
	$countries   = $countries_obj->__get('countries');
}

$billing_first_name	= get_user_meta( $user_identity, 'billing_first_name', true );
$billing_last_name	= get_user_meta( $user_identity, 'billing_last_name', true );
$billing_company	= get_user_meta( $user_identity, 'billing_company', true );
$billing_address_1	= get_user_meta( $user_identity, 'billing_address_1', true );
$billing_country	= get_user_meta( $user_identity, 'billing_country', true );
$billing_city		= get_user_meta( $user_identity, 'billing_city', true );
$billing_postcode	= get_user_meta( $user_identity, 'billing_postcode', true );
$billing_phone		= get_user_meta( $user_identity, 'billing_phone', true );
$billing_email		= get_user_meta( $user_identity, 'billing_email', true );
?>
<div class="wt-yourdetails wt-tabsinfo wt-reset-password">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Billing details', 'workreap'); ?></h2>
	</div>
	<form class="wt-formtheme wt-userform billing-user-form">
		<fieldset>
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_first_name]" value="<?php echo esc_attr($billing_first_name);?>" class="form-control" placeholder="<?php esc_attr_e('First name', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_last_name]" value="<?php echo esc_attr($billing_last_name);?>" class="form-control" placeholder="<?php esc_attr_e('Last name', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_company]" value="<?php echo esc_attr($billing_company);?>" class="form-control" placeholder="<?php esc_attr_e('Company name', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_address_1]" value="<?php echo esc_attr($billing_address_1);?>" class="form-control" placeholder="<?php esc_attr_e('Your address', 'workreap'); ?>">
			</div>
			<?php if (class_exists('WooCommerce')) {?>
			<div class="form-group form-group-half">
				<span class="wt-select ">
					<select data-placeholder="<?php esc_attr_e('Select a country', 'workreap'); ?>" name="billing[billing_country]" class="chosen-select">
							<option value=""><?php esc_html_e('Select a country','workreap');?></option>
							<?php if( !empty( $countries ) ){								
								foreach( $countries as $key => $item ){
									$selected = '';
									if( !empty($billing_country) && $billing_country === $key ) {
										$selected = 'selected';
									}
								?>
								<option <?php echo esc_attr($selected);?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $item );?></option>
							<?php }}?>
						</select>
				</span>
			</div>
			<?php }?>
			
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_city]" value="<?php echo esc_attr($billing_city);?>" class="form-control" placeholder="<?php esc_attr_e('City', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_postcode]" value="<?php echo esc_attr($billing_postcode);?>" class="form-control" placeholder="<?php esc_attr_e('Zipcode', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_phone]" value="<?php echo esc_attr($billing_phone);?>" class="form-control" placeholder="<?php esc_attr_e('Phone', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="text" name="billing[billing_email]" value="<?php echo esc_attr($billing_email);?>" class="form-control" placeholder="<?php esc_attr_e('Your email address', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half wt-btnarea">
				<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval($post_id);?>" class="wt-btn update-billing"><?php esc_html_e('Update billing details','workreap');?></a>
			</div>
		</fieldset>
	</form>
</div>
