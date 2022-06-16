<?php
/**
 *
 * The template used for displaying page content
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="block-list">
        <?php the_title(sprintf('<h2><a href="%s" rel="bookmark">' , esc_url(get_permalink())) , '</a></h2>'); ?>
        <?php if ('post' == get_post_type()) : ?>
            <div class="entry-meta">
                <?php workreap_posted_on(); ?>
            </div>
        <?php endif; ?>
        <div class="entry-content">
            <?php
				the_content(sprintf(
								wp_kses(__('Continue reading %s <span class="meta-nav">&rarr;</span>' , 'workreap') , array (
					'span' => array (
						'class' => array () ) )) , the_title('<span class="screen-reader-text">"' , '"</span>' , false)
				));
            ?>

        </div>
    </div>
    <?php
		wp_link_pages(array (
			'before' => '<div class="page-links">' . esc_html__('Pages:' , 'workreap') ,
			'after'  => '</div>' ,
		));
    ?>
</article>