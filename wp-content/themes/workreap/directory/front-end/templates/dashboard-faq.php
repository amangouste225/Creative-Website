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

$faqs 		= array();
$post_id	= !empty($args['post_id']) ? intval($args['post_id']) : 0;
$title		= !empty($args['title']) ? esc_html($args['title']) : esc_html__('Frequently asked questions', 'workreap');
$add_new_title		= !empty($args['add']) ? esc_html($args['add']) : esc_html__('+ Add FAQ', 'workreap');
if (function_exists('fw_get_db_post_option') && !empty($post_id) ) {
	$faqs = fw_get_db_post_option($post_id, 'faq', true);		
}
?>
<div class="wt-faqdataholder wt-tabsinfo">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php echo esc_html( $title ); ?></h2>
		<span class="wt-add-faq"><a href="#" onclick="event_preventDefault(event);"><?php echo esc_html( $add_new_title ); ?></a></span>
	</div>
	<ul class="wt-experienceaccordion accordion" id="faqsortable">
		<?php 
		if( !empty( $faqs ) && is_array($faqs) ) {
			$count = 0;
			foreach ($faqs as $key => $value) {
				$count++;
				$rand 				= rand(999999, 99999);
				$faq_question 		= !empty( $value['faq_question'] ) ? esc_attr( $value['faq_question'] ) : '';
				$faq_answer 		= !empty( $value['faq_answer'] ) ? esc_attr( $value['faq_answer'] ) : '';					
				if( !empty( $faq_question ) ){?>
				<li id="wt-faq-<?php echo esc_attr( $rand ); ?>" data-id="<?php echo esc_attr( $rand ); ?>" class="wt-placehoder-img">
					<div class="wt-accordioninnertitle">
						<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
						<div class="wt-projecttitle">
							<h3>
								<?php if( !empty( $faq_question ) ){ ?>
									<span class="head-title">
										<?php echo esc_html(stripslashes( $faq_question ) ); ?>
									</span>
								<?php } ?>
							</h3>
						</div>
						<div class="wt-rightarea">
							<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle1" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $rand ); ?>" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
							<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
						</div>
					</div>
					<div class="wt-collapseexp collapse" id="innertitle<?php echo esc_attr( $rand ); ?>" aria-labelledby="accordioninnertitle1" data-parent="#accordion">
						<div class="wt-formtheme wt-userform wt-formprojectinfo">
							<fieldset>
								<div class="form-group">
									<input type="text" name="settings[faq][<?php echo esc_attr( $rand ); ?>][faq_question]" class="wt-input-title form-control" value="<?php echo esc_attr( stripslashes($faq_question) ); ?>" placeholder="<?php esc_attr_e('Question', 'workreap'); ?>">
								</div>
								<div class="form-group">
									<textarea class="form-control" name="settings[faq][<?php echo esc_attr( $rand ); ?>][faq_answer]" placeholder="<?php esc_attr_e('Answer', 'workreap'); ?>"><?php echo esc_html( stripslashes($faq_answer) ); ?></textarea>
								</div>												
							</fieldset>
						</div>
					</div>
				</li>		
		<?php } } } ?>													
	</ul>
</div>
<script type="text/template" id="tmpl-load-faqs">
<li id="wt-faq-{{data.counter}}" data-id="{{data.counter}}" class="wt-placehoder-img">
	<div class="wt-accordioninnertitle">
		<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
		<div class="wt-projecttitle">
			<h3><span class="head-title"><?php esc_html_e('Question', 'workreap'); ?></span></h3>
		</div>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle" data-toggle="collapse" data-target="#innertitle-{{data.counter}}" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
		</div>
	</div>
	<div class="wt-collapseexp collapse show" id="innertitle-{{data.counter}}" aria-labelledby="accordioninnertitle" data-parent="#accordion">
		<div class="wt-formtheme wt-userform wt-formprojectinfo">
			<fieldset>
				<div class="form-group">
					<input type="text" name="settings[faq][{{data.counter}}][faq_question]" class="wt-input-title form-control" placeholder="<?php esc_attr_e('Question', 'workreap'); ?>">
				</div>
				<div class="form-group">
					<textarea class="form-control" name="settings[faq][{{data.counter}}][faq_answer]" placeholder="<?php esc_attr_e('Answer', 'workreap'); ?>"></textarea>
				</div>
			</fieldset>
		</div>
	</div>	
</li>
</script>
<?php
$script = "jQuery(document).ready(function (e) {
		addSortable(faqsortable);                    
	});";
	wp_add_inline_script('workreap-user-dashboard', $script, 'after');
?>