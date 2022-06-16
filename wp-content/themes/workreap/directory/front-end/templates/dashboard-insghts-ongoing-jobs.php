<?php
/**
 *
 * The template part for displaying the insights ongoing jobs
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$user_type			= apply_filters('workreap_get_user_type', $user_identity );
$user_type			= !empty ($user_type) ? $user_type : '';
$class_name			= !empty($user_type) && $user_type === 'freelancer' ? 'wt-freelancer-table' : '';

$milestone	= array();
if (function_exists('fw_get_db_settings_option')) {
	$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
}
$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

$args 	= array(
			'posts_per_page' 	=> 4,
			'post_type' 		=> 'projects',
			'orderby' 			=> 'ID',
			'order' 			=> 'DSC',
			'post_status' 		=> array('hired'),
		);

if( !empty($user_type) && $user_type === 'employer' ) {
	$args['author']	= $user_identity;
} else {
	$meta_query_args[] = array(
						'key' 		=> '_freelancer_id',
						'value' 	=> $linked_profile,
						'compare' 	=> '='
					);
	$query_relation 	= array('relation' => 'AND',);
	$args['meta_query'] = array_merge($query_relation, $meta_query_args);
}

$query 					= new WP_Query($args);

?>
<div class="wt-dashboardbox wt-earningsholder wt-ongoing-dash">
	<div class="wt-dashboardboxtitle wt-titlewithsearch">
		<h2><?php esc_html_e('Ongoing projects','workreap');?></h2>
	</div>
	<div class="wt-dashboardboxcontent">
		<?php if( $query->have_posts() ){ ?>
			<table class="wt-tablecategories <?php echo esc_attr($class_name);?>">
				<thead>
					<tr>
						<th><?php esc_html_e('Project title','workreap');?></th>
						<th><?php esc_html_e('Hired freelancer','workreap');?></th>
						<th><?php esc_html_e('Proposed cost','workreap');?></th>
						<th><?php esc_html_e('Actions','workreap');?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						while ($query->have_posts()) : $query->the_post();
							global $post;
							$author_id 		= get_the_author_meta( 'ID' );  
							$linked_profile = workreap_get_linked_profile_id($author_id);
							$employer_title = esc_html( get_the_title( $linked_profile ) );
														
							$project_title	= esc_html( get_the_title( $post->ID ));
							$project_title	= !empty( $project_title ) ? $project_title : '';
							$project_url	= esc_url( get_the_permalink( $post->ID ));
							$project_url	= !empty( $project_url ) ? $project_url : '';
										 
							$hired_profile	= get_post_meta( $post->ID, '_freelancer_id', true);
							$hired_title	= esc_html( get_the_title( $hired_profile ));
							
							$milestone_option	= 'off';
							if( !empty($milestone) && $milestone ==='enable' ){
								$milestone_option	= get_post_meta( $post->ID, '_milestone', true );
							}
							$proposal_id	= get_post_meta( $post->ID, '_proposal_id', true );

							if (function_exists('fw_get_db_post_option')) {
								$db_project_type      = fw_get_db_post_option($post->ID,'project_type');
							}
							
							$project_cost	= get_post_meta( $proposal_id, '_amount', true );
							$project_cost	= !empty($project_cost) ? $project_cost : 0;

							?>
							<tr>
								<td><a target="_blank" href="<?php echo esc_url($project_url);?>"><?php echo esc_html($project_title);?></a></td>
								<td><?php do_action( 'workreap_get_verification_check', intval($hired_profile), esc_html($hired_title) ); ?></td>
								<td><?php do_action('workreap_price_format',$project_cost);?></td>
								<td>
									<div class="wt-btnarea">
										<?php if(!empty($milestone_option) && $milestone_option ==='on' ){ ?>
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('milestone', $user_identity, '','listing',$proposal_id); ?>" class="wt-btn"><?php esc_html_e('View history','workreap');?></a>
										<?php } else if( !empty( $user_type ) && $user_type === 'employer' ) { ?>
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity, '','proposals',$post->ID); ?>" class="wt-btn"><?php esc_html_e('View history','workreap');?></a>
										<?php } elseif ( $user_type === 'freelancer' ) {?>
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity, '','history',$post->ID); ?>" class="wt-btn"><?php esc_html_e('View history','workreap');?></a>
										<?php } ?>
									</div>
								</td>
							</tr>
					<?php
						endwhile;
						wp_reset_postdata();
					?>
				</tbody>
			</table>
		<?php 
			} else{
				do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No ongoing projects', 'workreap' ),true);
			}
		?>
	</div>
</div>