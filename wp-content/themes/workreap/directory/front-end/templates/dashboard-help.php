<?php
/**
 *
 * The template part for displaying the dashboard Help and Support
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';

if ( function_exists('fw_get_db_settings_option') ) {
	$help 	= fw_get_db_settings_option('help_support');
}

$access		= !empty ($help['gadget']) ? $help['gadget'] : '';
if( !empty($access) && $access === 'enable'){
	$title			= !empty( $help['enable']['help_title'] ) ? $help['enable']['help_title'] : '';
	$desc			= !empty( $help['enable']['help_desc'] ) ? $help['enable']['help_desc'] : '';
	$faqs			= !empty( $help['enable']['faq'])	? $help['enable']['faq'] : '';
	$query_type		= workreap_support_type();
	$query_types	= !empty($query_type) ? $query_type : array();
	$count			= 1;
	
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-6 float-left">
	<div class="wt-dashboardbox">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e('Help &amp; Support' ,'workreap');?></h2>
			<form class="wt-formtheme wt-formsearch" id="search-help-support">
				<fieldset>
					<div class="form-group">
						<input type="text" name="Search" class="form-control wt-filter-faqs" placeholder="<?php esc_attr_e('Search Query' ,'workreap');?>">
						<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-filter"></i></a>
					</div>
				</fieldset>
			</form>
		</div>
		<div class="wt-dashboardboxcontent wt-helpsupporthead">
			<div class="wt-tabscontenttitle">
				<h2><?php echo esc_html($title);?></h2>
			</div>
			<div class="wt-helpsupportcontent">
				<div class="wt-description">
					<p><?php echo esc_html($desc);?></p>
				</div>
				<?php if( !empty( $faqs ) ) {?>	
					<ul class="wt-accordionhold accordion">
					<?php 
						foreach ( $faqs as $faq ) {
							if(!empty($faq['faq_question'])) {
							?>
							<li class="faq-search">
								<div class="wt-accordiontitle collapsed" id="headingtwo-<?php echo intval($count);?>" data-toggle="collapse" data-target="#collapsetwo-<?php echo intval($count);?>">
										<span><?php echo esc_html($faq['faq_question']);?></span>
									</div>
									<div class="collapse" id="collapsetwo-<?php echo intval($count);?>" aria-labelledby="headingtwo-<?php echo intval($count);?>">
										<div class="wt-accordiondetails">
											<div class="wt-title">
												<h3><?php echo esc_html($faq['faq_question']);?></h3>
											</div>
											<div class="wt-description">
												<?php echo do_shortcode($faq['faq_answer']);?>
											</div>
										</div>
									</div>
								</li>
							<?php 
							$count++;
							}
						}
						?>
					</ul>
				<?php } else {
					do_action('workreap_empty_records_html','wt-empty-person',esc_html__( 'No FAQ found.', 'workreap' ));
				} ?>
			</div>
		</div>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-6 float-left">
	<div class="wt-dashboardbox wt-holdersolution">
		<div class="wt-dashboardboxtitle">
			<h2><?php esc_html_e("Didn't find your solution?","workreap");?></h2>
		</div>
		<div class="wt-dashboardboxcontent wt-querycontent">
			<div class="wt-tabscontenttitle">
				<h2><?php esc_html_e("Ask your query","workreap");?></h2>
			</div>
			<form class="wt-formtheme wt-userform wt-faqform">
				<fieldset>
					<div class="form-group">
						<span class="wt-select">
							<select name="query_type" class="query_type">
								<option value=""><?php esc_html_e("Select query type","workreap");?></option>
								<?php 
								if( !empty($query_types)) {
									foreach( $query_types as $key =>$val ) {?>
										<option value="<?php echo esc_attr($val);?>"><?php echo esc_html($val);?></option>
								<?php }}?>
							</select>
						</span>
					</div>
					<div class="form-group">
						<textarea name="message" class="form-control faq_message" placeholder="<?php esc_attr__("Query description","workreap");?>"></textarea>
					</div>
					<div class="form-group form-group-half wt-btnarea">
						<a href="#" onclick="event_preventDefault(event);" class="wt-btn faq_submit"><?php esc_html_e("Submit","workreap");?></a>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<?php }
