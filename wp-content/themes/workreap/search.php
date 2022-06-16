<?php
/**
 *
 * Theme Search Page
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
get_header();
?>
<div class="container">
    <div class="row">
        <div class="workreap-search-page wt-haslayout">
			<?php get_template_part('template-parts/content' , 'search'); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>