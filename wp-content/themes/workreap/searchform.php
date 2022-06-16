<?php
/**
 *
 * Theme Search form
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
?>
<form class="wt-formtheme wt-formsearch" method="get" role="search" action="<?php echo esc_url(home_url('/')); ?>">
	<fieldset>
		<div class="form-group">
			<input type="search" name="s" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="<?php esc_attr_e('Searching Might Help', 'workreap') ?>">
			<button type="submit" class="wt-searchgbtn"><i class="fa fa-search"></i></button>
		</div>
	</fieldset>
</form>


