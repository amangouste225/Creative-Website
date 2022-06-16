<?php
/**
 *
 * The template part for displaying Hired freelancer
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 			= $current_user->ID;
$edit_id					= !empty($_GET['id']) ? intval($_GET['id']) : '';
$proposal_id				= get_post_meta( $edit_id, '_proposal_id', true);
$hired_freelance_id			= get_post_field('post_author',$proposal_id);
$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';
$hire_linked_profile		= workreap_get_linked_profile_id($hired_freelance_id);
$hired_freelancer_title 	= esc_html( get_the_title( $hire_linked_profile ));
$hired_freelancer_avatar 	= apply_filters(
	'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $hire_linked_profile ), array( 'width' => 225, 'height' => 225 )
);	

if( !empty($hired_freelance_id) ) {?>
<div class="wt-rcvproposalholder wt-hiredfreelancer wt-tabsinfo">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Hired freelancer', 'workreap'); ?></h2>
	</div>
	<div class="wt-managejobcontent">
		<div class="wt-userlistinghold wt-featured wt-proposalitem">
			<?php do_action('workreap_featured_freelancer_tag', $hired_freelance_id); ?>
			<figure class="wt-userlistingimg">
				<img src="<?php echo esc_url( $hired_freelancer_avatar );?>" alt="<?php esc_attr_e('freelancer','workreap');?>" class="template-content">
			</figure>
			<div class="wt-proposaldetails">
				<div class="wt-contenthead">
					<div class="wt-title">
						<?php do_action( 'workreap_get_verification_check', $hire_linked_profile, $hired_freelancer_title ); ?>
					</div>
				</div>
				<?php do_action('workreap_freelancer_get_reviews',$hire_linked_profile,'v1');?>												
			</div>
			<div class="wt-rightarea">
				<div class="wt-btnarea">
					<a class="wt-btn" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','history',$edit_id); ?>"><?php esc_html_e('View History','workreap');?></a>
				</div>
				<?php do_action('worrketic_proposal_duration_and_amount',$proposal_id);?>
				<?php do_action('worrketic_proposal_cover',$proposal_id);?>
				<?php do_action('worrketic_proposal_attachments',$proposal_id);?>
			</div>
		</div>
	</div>
</div>
<?php }
				