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
global $current_user, $wp_roles,$userdata,$post,$paged,$woocommerce;
global $wpdb;

////testing end
$identity 		= !empty($_GET['identity']) ? $_GET['identity'] : "";
$ref 			= !empty($_GET['ref']) ? $_GET['ref'] :"";

$user_identity 	 = $current_user->ID;
$post_id 		 = workreap_get_linked_profile_id($user_identity);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$show_posts 	 = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		 = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		 = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
	
//paged works on single pages, page - works on homepage
$paged 		= max($pg_page, $pg_paged);
$order 		= 'DESC';
$sorting 	= 'ID';

$args = array('posts_per_page' => $show_posts,
    'post_type' 		=> 'disputes',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'post_status' 		=> array('publish','pending'),
    'author' 			=> $user_identity,
    'paged' 			=> $paged,
    'suppress_filters'  => false
);

$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
	<div class="wt-dashboardbox wt-dashboardinvocies disputes-header">
		<div class="wt-dashboardboxtitle wt-addnew">
			<h2><?php esc_html_e( 'Dispute', 'workreap' ); ?></h2>
			<span><a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target=".wt-dispute-form"><?php esc_html_e( '+ Create Disputes', 'workreap' ); ?></a></span>
		</div>
		<div class="wt-dashboardboxcontent wt-categoriescontentholder">
			<?php if( $query->have_posts() ){ ?>
				<table class="wt-tablecategories wt-tableservice">
					<thead>
						<tr>
							<th><?php esc_html_e('Subject','workreap');?></th>
							<th><?php esc_html_e('Project/Service','workreap');?></th>
							<th><?php esc_html_e('Status','workreap');?></th>
							<th><?php esc_html_e('Action','workreap');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$post_project		= get_post_meta($post->ID,'_dispute_project',true);
								$post_status		= get_post_status($post->ID);
								
								if( $post_status === 'publish' ){
									$post_status	= esc_html__('Resolved','workreap');
								}elseif( $post_status === 'pending' ){
									$post_status	= esc_html__('Pending','workreap');
								}
								
								$project_title	= esc_html__('NILL','workreap');
								if( !empty( $post_project ) ){
									$project_title	= get_the_title($post_project);
								}
								?>
								<tr>
									<td><?php the_title();?></td>
									<td><span><?php echo ucwords( $project_title );?></span></td>
									<td><span><?php echo ucwords( $post_status );?></span></td>
									<td>
										<div class="wt-actionbtn">
											<a href="#" onclick="event_preventDefault(event);" class="wt-viewinfo viewinfo-dispute"  data-id="<?php echo intval( $post->ID );?>"><i class="lnr lnr-eye"></i></a>
										</div>
									</td>
								</tr>
							<?php
							endwhile;
							wp_reset_postdata();
						?>	
					</tbody>
				</table>
			<?php } else{ ?>
				<div class="wt-emptydata-holder">
					<?php do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No disputes have submitted yet.', 'workreap' )); ?>
				</div>
			<?php } ?>
			<?php
				if (!empty($count_post) && $count_post > $show_posts) {
					workreap_prepare_pagination($count_post, $show_posts);
				}
			?>
		</div>
	</div>
</div>
<div class="wt-uploadimages modal fade" id="wt-dispute-feedback" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="wt-modaldialog modal-dialog" role="document">
		<div class="wt-modalcontent modal-content">
			<div class="wt-boxtitle">
				<h2><?php esc_html_e('Admin Feedback','workreap');?><i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i></h2>
			</div>
			<div class="wt-modalbody modal-body">
				<p id="dispute_contents"></p>
			</div>
		</div>
	</div>
</div>
<?php 
if( isset( $user_type ) && $user_type === 'employer' ){
	get_template_part('directory/front-end/templates/employer/dashboard', 'disputes');
} else if( isset( $user_type ) && $user_type === 'freelancer' ){
	get_template_part('directory/front-end/templates/freelancer/dashboard', 'disputes');
}