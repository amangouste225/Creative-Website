<?php
/**
 *
 * Template Name: Search Portfolio
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
get_header();
global $paged;
$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
$paged = max($pg_page, $pg_paged);

if(function_exists('fw_get_db_settings_option')){
	$portfolios_per_page 	= fw_get_db_settings_option('portfolios_per_page');
}

$show_posts 	= !empty( $portfolios_per_page ) ? $portfolios_per_page : get_option('posts_per_page');
$portfolio_cats = workreap_get_taxonomy_array('portfolio_categories');

//Search parameters
$tag 		= !empty( $_GET['tag']) ? $_GET['tag'] : '';
$category 	= !empty( $_GET['category']) ? $_GET['category'] : '';

$tax_query_args  = array();
$tag_args  		 = array();

$flag 		= rand(9999, 999999);
$width		= intval(352);
$height		= intval(200);

$query_args = array(
    'posts_per_page' 	  => $show_posts,
    'post_type' 	 	  => 'wt_portfolio',
    'paged' 		 	  => $paged,
    'post_status' 	 	  => 'publish',
    'ignore_sticky_posts' => 1
);

$query_args['orderby']	 = array( 
	'ID'      		=> 'DESC'
); 

//Tag search
if( !empty($tag) ){
	$query_relation = array('relation' => 'OR',);
	$tag_args  	= array();

	$tag_args[] = array(
		'taxonomy' => 'portfolio_tags',
		'field'    => 'name',
		'terms'    => $tag,
		'compare'  => 'LIKE'
	);
	$tax_query_args[] = array_merge($query_relation, $tag_args);
	
	$query_args['s'] = $tag;
}

//Category search
if( !empty($category) && $category != 'all' ){
	$query_relation = array('relation' => 'OR',);
	$cat_args  	= array();

	$cat_args[] = array(
		'taxonomy' => 'portfolio_categories',
		'field'    => 'slug',
		'terms'    => $category,
	);
	$tax_query_args[] = array_merge($query_relation, $cat_args); 
}

//Taxonomy Query
if ( !empty( $tax_query_args ) ) {
    $query_relation = array('relation' => 'AND',);
    $query_args['tax_query'] = array_merge($query_relation, $tax_query_args);    
}

$portfolio_data = new WP_Query($query_args); 
$total_posts  	= $portfolio_data->found_posts; 
?>
<?php if( have_posts() ) {?>
<div class="wt-haslayout wt-haslayout page-data">
	<?php 
		while ( have_posts() ) : the_post();
			the_content();
			wp_link_pages( array(
								'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
								'after'       => '</ul></nav></div>',
							) );
		endwhile;
		wp_reset_postdata();
	?>
</div>
<?php }?>

<div class="wt-portfolio-grid wt-haslayout">
	<div class="row justify-content-md-center">	
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
			<div class="wt-articletabshold wt-articletwo-wrap">
				<div class="wt-searcharea">
					<form method="GET" class="wt-formtheme wt-formsearch portfolio-submit">
						<?php if(!empty($portfolio_cats)) { ?>
							<ul class="wt-navarticletab">
								<li>
									<span class="wt-radio">
										<input id="cat-0" type="radio" name="category" value="all" checked>
										<label for="cat-0"><?php esc_html_e('All', 'workreap'); ?></label>
									</span>
								</li>
								<?php foreach($portfolio_cats as $cat) {?>
									<li>
										<span class="wt-radio">
											<input id="cat-<?php echo esc_attr($cat->term_id); ?>" <?php checked( $cat->slug, $category,true ); ?> type="radio" name="category" value="<?php echo esc_attr($cat->slug); ?>">
											<label for="cat-<?php echo esc_attr($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></label>
										</span>
									</li>
								<?php } ?>
							</ul>
						<?php } ?>
						<fieldset>
							<div class="form-group">
								<input type="text" name="tag" value="<?php echo esc_attr($tag); ?>" class="form-control" placeholder="<?php esc_attr_e('Search tag here', 'workreap') ?>">
								<button type="submit" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></button>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="wt-freelancers-holder wt-freelancers-home">
					<?php if( $portfolio_data->have_posts() ) { ?>
						<ul class="wt-search-items">
							<?php
							while ($portfolio_data->have_posts()) : $portfolio_data->the_post();
								global $post;
								$random = rand(1,9999);
								$portfolio_url			= get_the_permalink();
								$author_id 				= get_the_author_meta( 'ID' );  
								$linked_profile 		= workreap_get_linked_profile_id($author_id);
								$user_link				= get_the_permalink( $linked_profile );
								$freelancer_title 		= get_the_title( $linked_profile );	

								$db_portfolio_cats 		= wp_get_post_terms($post->ID, 'portfolio_categories');
								$db_portfolio_tags 		= wp_get_post_terms($post->ID, 'portfolio_tags');
								$portfolio_views		= get_post_meta($post->ID, 'portfolio_views', true);
								$portfolio_views		= !empty( $portfolio_views ) ? $portfolio_views : 0;

								$gallery_imgs			= array();
								$videos					= array();
								$default_thumbnail 		= get_template_directory_uri().'/images/portfolio.jpg';
	
								if (function_exists('fw_get_db_post_option')) {
									$gallery_imgs   	= fw_get_db_post_option($post->ID,'gallery_imgs');
									$videos 			= fw_get_db_post_option($post->ID, 'videos');
								}
	
								$empty_image_class = '';
								$total_data	= count($gallery_imgs) + count($videos);
								if( !empty($total_data) && $total_data < 1 ) {
									$empty_image_class	= 'wt-empty-service-image';
								} 

								$script	= "new Splide( '.wtsplide-".$random."',{direction:'".workreap_splide_rtl_check()."'} ).mount();";
								wp_add_inline_script( 'splide', $script, 'after' );
								?>
								<li>
									<div class="wt-freelancers-info">
										<?php if( !empty( $gallery_imgs ) || !empty( $videos ) ) {?>
											<div class="wt-freelancers wtsplide-<?php echo esc_attr($random);?>">
												<div class="splide__track">
													<div class="splide__list">
														<?php if( !empty( $gallery_imgs ) ) {
															foreach( $gallery_imgs as $key => $item ){
																$attachment_id	= !empty( $item['attachment_id'] ) ? $item['attachment_id'] : '';
																$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
																if (strpos($thumbnail,'media/default.png') === false) {?>
																	<div class="splide__slide"><figure class="item">
																		<a  href="<?php echo esc_url( $portfolio_url );?>">
																			<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Portfolio ','workreap');?>" class="item">
																		</a>
																	</figure></div>
														<?php }}}?>
														<?php if( !empty( $videos ) ){
															foreach( $videos as $key => $vid ){
															?>
															<div class="splide__slide"><figure class="item">
																<a href="<?php echo esc_url( $portfolio_url );?>">
																	<img src="<?php echo esc_url($default_thumbnail);?>" alt="<?php esc_attr_e('Portfolio ','workreap');?>" class="item">
																</a>
															</figure></div>
														<?php }}?>
													</div>
												</div>
											</div>
										<?php }else{?>
											<div class="wt-freelancers">
												<div class="splide__slide"><figure class="item">
													<a  href="<?php echo esc_url( $portfolio_url );?>">
														<img src="<?php echo esc_url($default_thumbnail);?>" alt="<?php esc_attr_e('Portfolio ','workreap');?>" class="item">
													</a>
												</figure></div>
											</div>
										<?php }?>
										<?php do_action('workreap_portfolio_shortdescription', $post->ID, $linked_profile); ?>
									</div>
								</li>
								<?php
							endwhile;
							wp_reset_postdata(); ?>
						</ul>
					<?php } else {
						do_action('workreap_empty_records_html','wt-empty-person',esc_html__( 'No portfolios found.', 'workreap' ));
					} ?>
				</div>
				<?php
					if ( !empty($total_posts) && $total_posts > $show_posts ) {
						if (function_exists('workreap_prepare_pagination')) {
							workreap_prepare_pagination($total_posts, $show_posts);
						}
					} 
				?>
			</div>
		</div>
	</div>
</div>
<?php
 get_footer();