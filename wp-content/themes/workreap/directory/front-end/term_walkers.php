<?php
/**
 *
 * All wolker classed would be in this file
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfoliot
 * @since 1.0
 */

if( !class_exists('Workreap_Walker_Location') ){
	
	class Workreap_Walker_Location extends Walker_CategoryDropdown {
		/**
		 * @see Walker::$tree_type
		 * @since 2.1.0
		 * @var string
		 */
		var $tree_type = 'category';

		/**
		 * @see Walker::$db_fields
		 * @since 2.1.0
		 * @todo Decouple this
		 * @var array
		 */
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

		/**
		 * Start the element output.
		 *
		 * @see Walker::start_el()
		 * @since 2.1.0
		 *
		 * @param string $output   Passed by reference. Used to append additional content.
		 * @param object $category Category data object.
		 * @param int    $depth    Depth of category. Used for padding.
		 * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
		 */
		function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			$pad = str_repeat('&nbsp;', $depth * 1);

			/** This filter is documented in wp-includes/category-template.php */
			$cat_name 		= apply_filters( 'list_cats', $category->name, $category );
			$cat_permalink 	= get_term_link( $category );
			$random	 		= 'term-'.rand();

			//get location flag
			if( function_exists('fw_get_db_term_option') ){
				$country 	= fw_get_db_term_option($category->term_id, 'locations', 'image');
			}

			$flag	= '';
			if( !empty( $country['url'] ) ){
				$flag	= '<img class="wt-checkflag" alt="'.esc_attr__('location','workreap').'" src="'.esc_url( $country['url'] ).'">';
			}

			$output .= "\t<span class='wt-checkbox loclevel-$depth'><input name='location[]' type='checkbox' id=".esc_attr( $random )." data-permalink=\"".esc_url( $cat_permalink )."\" class=\"loclevel-$depth\" value=\"".$category->slug."\"";


			if ( !empty( $args['current_category'] ) && in_array($category->slug,$args['current_category']) )
				$output .= ' checked="checked"';
			$output .= '>';

			$output .= '<label for="'.$random.'">'.$flag.$cat_name.'</label>';

			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
			$output .= "</span>\n";
		}
	}
}

if( !class_exists('Workreap_Walker_Location_Dropdown') ){
	
	class Workreap_Walker_Location_Dropdown extends Walker_CategoryDropdown {
		/**
     * @see Walker::$tree_type
     * @since 2.1.0
     * @var string
     */
    var $tree_type = 'category';

    /**
     * @see Walker::$db_fields
     * @since 2.1.0
     * @todo Decouple this
     * @var array
     */
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category. Used for padding.
     * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
     */
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        $pad = str_repeat('-&nbsp;', $depth * 1);

        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters( 'list_cats', $category->name, $category );
        $cat_permalink = get_term_link( $category );
		
		//get location flag
		if( function_exists('fw_get_db_term_option') ){
			$country 	= fw_get_db_term_option($category->term_id, 'locations', 'image');
		}

		$flag	= '';
		$class  = ''; 
		if( !empty( $country['url'] ) ){
			$flag	= 'background-image:url('.$country['url'].'); background-repeat : no-repeat; background-size: 18px auto; ';
			$class  = 'option-with-flag'; 
		}

        $output .= "\t<option style='$flag'  class=\"$class level-$depth\" value=\"".$category->slug."\"";
        if ( $category->term_id === $args['selected'] )
            $output .= ' selected="selected"';
        $output .= '>';
        $output .= $pad.$cat_name;
		
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
        $output .= "</option>\n";
    }
	}
}

if( !class_exists('Workreap_Walker_Category_Dropdown') ){
	
	class Workreap_Walker_Category_Dropdown extends Walker_CategoryDropdown {
		/**
     * @see Walker::$tree_type
     * @since 2.1.0
     * @var string
     */
    var $tree_type = 'category';

    /**
     * @see Walker::$db_fields
     * @since 2.1.0
     * @todo Decouple this
     * @var array
     */
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category. Used for padding.
     * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
     */
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        $pad = str_repeat('-&nbsp;', $depth * 1);

        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters( 'list_cats', $category->name, $category );
        $cat_permalink = get_term_link( $category );

		$class  = ''; 

        $output .= "\t<option  class=\"$class level-$depth\" value=\"".$category->term_id."\"";
        
		if ( !empty( $args['current'] ) && !empty( $category->term_id ) && in_array( $category->term_id, $args['current'] ) ){
            $output .= ' selected="selected"';
		}
		
        $output .= '>';
        $output .= $pad.$cat_name;
		
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
        $output .= "</option>\n";
    }
	}
}

if( !class_exists('Workreap_Walker_Category') ){
	
	class Workreap_Walker_Category extends Walker_CategoryDropdown {
		/**
		 * @see Walker::$tree_type
		 * @since 2.1.0
		 * @var string
		 */
		var $tree_type = 'category';

		/**
		 * @see Walker::$db_fields
		 * @since 2.1.0
		 * @todo Decouple this
		 * @var array
		 */
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

		/**
		 * Start the element output.
		 *
		 * @see Walker::start_el()
		 * @since 2.1.0
		 *
		 * @param string $output   Passed by reference. Used to append additional content.
		 * @param object $category Category data object.
		 * @param int    $depth    Depth of category. Used for padding.
		 * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
		 */
		function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			$pad = str_repeat('&nbsp;', $depth * 1);

			/** This filter is documented in wp-includes/category-template.php */
			$cat_name 		= apply_filters( 'list_cats', $category->name, $category );
			$cat_permalink 	= get_term_link( $category );
			$random	 		= 'term-'.rand();


			$output .= "\t<span class='wt-checkbox loclevel-$depth'><input name='category[]' type='checkbox' id=".esc_attr( $random )." data-permalink=\"".esc_url( $cat_permalink )."\" class=\"loclevel-$depth\" value=\"".$category->slug."\"";


			if ( !empty( $args['current_category'] ) && in_array($category->slug,$args['current_category']) )
				$output .= ' checked="checked"';
			$output .= '>';

			$output .= '<label for="'.$random.'">'.$cat_name.'</label>';

			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
			$output .= "</span>\n";
		}
	}
}

//Specialization
if( !class_exists('Workreap_Walker_Specialization') ){
	
	class Workreap_Walker_Specialization extends Walker_CategoryDropdown {
		/**
		 * @see Walker::$tree_type
		 * @since 2.1.0
		 * @var string
		 */
		var $tree_type = 'category';

		/**
		 * @see Walker::$db_fields
		 * @since 2.1.0
		 * @todo Decouple this
		 * @var array
		 */
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

		/**
		 * Start the element output.
		 *
		 * @see Walker::start_el()
		 * @since 2.1.0
		 *
		 * @param string $output   Passed by reference. Used to append additional content.
		 * @param object $category Category data object.
		 * @param int    $depth    Depth of category. Used for padding.
		 * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
		 */
		function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			$pad = str_repeat('&nbsp;', $depth * 1);

			/** This filter is documented in wp-includes/category-template.php */
			$cat_name 		= apply_filters( 'list_cats', $category->name, $category );
			$cat_permalink 	= get_term_link( $category );
			$random	 		= 'term-'.rand();

			$output .= "\t<span class='wt-checkbox loclevel-$depth'><input name='specialization[]' type='checkbox' id=".esc_attr( $random )." data-permalink=\"".esc_url( $cat_permalink )."\" class=\"loclevel-$depth\" value=\"".$category->slug."\"";


			if ( !empty( $args['current_category'] ) && in_array($category->slug,$args['current_category']) )
				$output .= ' checked="checked"';
			$output .= '>';

			$output .= '<label for="'.$random.'">'.$cat_name.'</label>';

			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
			$output .= "</span>\n";
		}
	}
}

if( !class_exists('Workreap_Walker_Specialization_Dropdown') ){
	
	class Workreap_Walker_Specialization_Dropdown extends Walker_CategoryDropdown {
		/**
     * @see Walker::$tree_type
     * @since 2.1.0
     * @var string
     */
    var $tree_type = 'category';

    /**
     * @see Walker::$db_fields
     * @since 2.1.0
     * @todo Decouple this
     * @var array
     */
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category. Used for padding.
     * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
     */
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        $pad = str_repeat('-&nbsp;', $depth * 1);

        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters( 'list_cats', $category->name, $category );
        $cat_permalink = get_term_link( $category );

        $output .= "\t<option class=\" level-$depth\" value=\"".$category->term_id."\"";
        if ( $category->term_id === $args['selected'] )
            $output .= ' selected="selected"';
        $output .= '>';
        $output .= $pad.$cat_name;
		
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
        $output .= "</option>\n";
    	}
	}
}

//Experience
if( !class_exists('Workreap_Walker_Experience') ){
	
	class Workreap_Walker_Experience extends Walker_CategoryDropdown {
		/**
		 * @see Walker::$tree_type
		 * @since 2.1.0
		 * @var string
		 */
		var $tree_type = 'category';

		/**
		 * @see Walker::$db_fields
		 * @since 2.1.0
		 * @todo Decouple this
		 * @var array
		 */
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

		/**
		 * Start the element output.
		 *
		 * @see Walker::start_el()
		 * @since 2.1.0
		 *
		 * @param string $output   Passed by reference. Used to append additional content.
		 * @param object $category Category data object.
		 * @param int    $depth    Depth of category. Used for padding.
		 * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
		 */
		function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			$pad = str_repeat('&nbsp;', $depth * 1);

			/** This filter is documented in wp-includes/category-template.php */
			$cat_name 		= apply_filters( 'list_cats', $category->name, $category );
			$cat_permalink 	= get_term_link( $category );
			$random	 		= 'term-'.rand();

			$output .= "\t<span class='wt-checkbox loclevel-$depth'><input name='industrial_experience[]' type='checkbox' id=".esc_attr( $random )." data-permalink=\"".esc_url( $cat_permalink )."\" class=\"loclevel-$depth\" value=\"".$category->slug."\"";


			if ( !empty( $args['current_category'] ) && in_array($category->slug,$args['current_category']) )
				$output .= ' checked="checked"';
			$output .= '>';

			$output .= '<label for="'.$random.'">'.$cat_name.'</label>';

			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
			$output .= "</span>\n";
		}
	}
}

if( !class_exists('Workreap_Walker_Experience_Dropdown') ){
	
	class Workreap_Walker_Experience_Dropdown extends Walker_CategoryDropdown {
		/**
     * @see Walker::$tree_type
     * @since 2.1.0
     * @var string
     */
    var $tree_type = 'category';

    /**
     * @see Walker::$db_fields
     * @since 2.1.0
     * @todo Decouple this
     * @var array
     */
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category. Used for padding.
     * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
     */
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        $pad = str_repeat('-&nbsp;', $depth * 1);

        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters( 'list_cats', $category->name, $category );
        $cat_permalink = get_term_link( $category );

        $output .= "\t<option class=\" level-$depth\" value=\"".$category->term_id."\"";
        if ( $category->term_id === $args['selected'] )
            $output .= ' selected="selected"';
        $output .= '>';
        $output .= $pad.$cat_name;
		
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
        $output .= "</option>\n";
    	}
	}
}

if( !class_exists('Workreap_Walker_Skills_Dropdown') ){
	
	class Workreap_Walker_Skills_Dropdown extends Walker_CategoryDropdown {
		/**
     * @see Walker::$tree_type
     * @since 2.1.0
     * @var string
     */
    var $tree_type = 'category';

    /**
     * @see Walker::$db_fields
     * @since 2.1.0
     * @todo Decouple this
     * @var array
     */
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category. Used for padding.
     * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
     */
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        $pad = str_repeat('-&nbsp;', $depth * 1);

        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters( 'list_cats', $category->name, $category );
        $cat_permalink = get_term_link( $category );

        $output .= "\t<option class=\" level-$depth\" value=\"".$category->term_id."\"";
        if ( $category->term_id == $args['selected'] )
            $output .= ' selected="selected"';
        $output .= '>';
        $output .= $pad.$cat_name;
		
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
        $output .= "</option>\n";
    	}
	}
}

if( !class_exists('Workreap_Walker_Skills') ){
	
	class Workreap_Walker_Skills extends Walker_CategoryDropdown {
		/**
		 * @see Walker::$tree_type
		 * @since 2.1.0
		 * @var string
		 */
		var $tree_type = 'category';

		/**
		 * @see Walker::$db_fields
		 * @since 2.1.0
		 * @todo Decouple this
		 * @var array
		 */
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

		/**
		 * Start the element output.
		 *
		 * @see Walker::start_el()
		 * @since 2.1.0
		 *
		 * @param string $output   Passed by reference. Used to append additional content.
		 * @param object $category Category data object.
		 * @param int    $depth    Depth of category. Used for padding.
		 * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
		 */
		function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			$pad = str_repeat('&nbsp;', $depth * 1);

			/** This filter is documented in wp-includes/category-template.php */
			$cat_name 		= apply_filters( 'list_cats', $category->name, $category );
			$cat_permalink 	= get_term_link( $category );
			$random	 		= 'term-'.rand();

			$output .= "\t<span class='wt-checkbox loclevel-$depth'><input name='skills[]' type='checkbox' id=".esc_attr( $random )." data-permalink=\"".esc_url( $cat_permalink )."\" class=\"loclevel-$depth\" value=\"".$category->slug."\"";


			if ( !empty( $args['current_category'] ) && in_array($category->slug,$args['current_category']) )
				$output .= ' checked="checked"';
			$output .= '>';

			$output .= '<label for="'.$random.'">'.$cat_name.'</label>';

			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
			$output .= "</span>\n";
		}
	}
}