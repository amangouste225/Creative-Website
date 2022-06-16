<?php
/**
 *
 * The template part for managing portfolios
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $post, $paged;
$user_identity 	 = $current_user->ID;
$post_id 		 = workreap_get_linked_profile_id($user_identity);

$show_posts 	 = get_option('posts_per_page') ? get_option('posts_per_page') : 20;

$pg_page 		 = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		 = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var

//paged works on single pages, page - works on homepage
$paged 		= max($pg_page, $pg_paged);
$order 		= 'DESC';
$sorting 	= 'ID';

$search_keyword  = !empty($_GET['keyword']) ? $_GET['keyword'] : "";

$args = array('posts_per_page' => $show_posts,
    'post_type' 		=> 'wt_portfolio',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'post_status' 		=> array('publish','draft','pending'),
    'author' 			=> $user_identity,
    'paged' 			=> $paged,
    'suppress_filters'  => false,
	's'                 => $search_keyword,
);

$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
	<div class="wt-dashboardbox wt-dashboardportfolios">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e('Portfolios Listing','workreap');?></h2>
			<?php do_action('workreap_dashboard_search_keyword','portfolios','posted');?>
		</div>
		<div class="wt-dashboardboxcontent wt-categoriescontentholder">
			<?php if( $query->have_posts() ){ ?>
				<table class="wt-tablecategories wt-tableservice">
					<thead>
						<tr>
							<th><?php esc_html_e('Portfolio Title','workreap');?></th>
							<th><?php esc_html_e('Portfolio Status','workreap');?></th>
							<th><?php esc_html_e('Action','workreap');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$perma_link			= get_the_permalink($post->ID);
								$post_status		= get_post_status($post->ID);
								?>
								<tr>
									<td>
										<?php do_action('workreap_portfolio_listing_basic', $post->ID ); ?>
									</td>
									<td>
										<form class="wt-formtheme wt-formsearch">
											<fieldset>
												<div class="form-group">
													<?php if( !empty($post_status) && $post_status === 'pending' ){ ?>
														<span><i class="fa fa-spinner fa-spin"></i>&nbsp;<?php esc_html_e('Under Review','workreap');?></span>
													<?php }else{?>
														<span class="wt-select">
															<select class="wt-select-status">
																<option value="" disabled=""><?php esc_html_e('Portfolio Status','workreap');?></option>
																<option value="<?php echo esc_attr('publish');?>" <?php if( $post->post_status === 'publish') { echo 'selected'; }?>><?php esc_html_e('Published','workreap');?></option>
																<option <?php if( $post->post_status === 'draft') { echo 'selected'; }?> value="<?php echo esc_attr('draft');?>"><?php esc_html_e('Draft','workreap');?></option>
															</select>
														</span>
														<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval($post->ID);?>" class="wt-searchgbtn wt-service-status"><i class="fa fa-check"></i></a>
													<?php }?>
												</div>
											</fieldset>
										</form>
									</td>
									<td>
										<div class="wt-actionbtn">
											<?php if( $post->post_status != 'draft') { ?>
												<a target="_blank" href="<?php echo esc_url( $perma_link );?>" class="wt-viewinfo">
													<i class="lnr lnr-eye"></i>
												</a>
											<?php } ?>
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('portfolios', $user_identity, '','edit',$post->ID); ?>" class="wt-addinfo">
												<i class="lnr lnr-pencil"></i>
											</a>
											<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval( $post->ID );?>" class="wt-deleteinfo wt-delete-portfolio"><i class="lnr lnr-trash"></i></a>
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
					<?php do_action('workreap_empty_records_html', 'wt-empty-projects',esc_html__( 'No portfolios found yet.', 'workreap' )); ?>
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