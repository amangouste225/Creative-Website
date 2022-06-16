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
if (function_exists('fw_get_db_post_option') && !empty($post_id) ) {
	$faqs = fw_get_db_post_option($post_id, 'faq', true);		
}

$title		= !empty($args['title']) ? esc_html($args['title']) : esc_html__('Frequently asked questions', 'workreap');

if( !empty( $faqs ) ) {?>	
	<div class="wt-faq-details wt-haslayout">
		<h3><?php echo esc_html( $title ); ?></h3>
		<ul class="wt-accordionhold accordion" id="wt-accordion">
		<?php 
			$counter	= 0;
			$count		= 0;
			foreach ( $faqs as $faq ) {
				$counter++;
				if( !empty($counter) && $counter == 1 ){
					$active_content		= 'collapsed';
					$active_expanded	= 'true';
					$active_tab			= 'collapse show';
				} else {
					$active_content		= 'collapsed';
					$active_expanded	= 'false';
					$active_tab			= 'collapse hide';
				}
				
				if(!empty($faq['faq_question'])) { ?>
				<li class="faq-search wt-haslayout">
					<div class="wt-accordiontitle <?php echo esc_attr($active_content);?>" id="headingtwo-<?php echo intval($count);?>" data-toggle="collapse" aria-expanded="<?php echo esc_attr($active_expanded);?>" data-target="#collapsetwo-<?php echo intval($count);?>">
							<span><?php echo esc_html(stripslashes($faq['faq_question']));?></span>
						</div>
						<div class="<?php echo esc_attr($active_tab);?>" data-parent="#wt-accordion" id="collapsetwo-<?php echo intval($count);?>" aria-labelledby="headingtwo-<?php echo intval($count);?>">
							<div class="wt-accordiondetails">
								<div class="wt-description">
									<?php echo wpautop(nl2br( stripslashes($faq['faq_answer'])));?>
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
	</div>
<?php } 