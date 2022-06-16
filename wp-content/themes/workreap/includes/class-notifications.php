<?php

/**
 *
 * Class used as base to create theme Notifications
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
if (!class_exists('Workreap_Prepare_Notification')) {

    class Workreap_Prepare_Notification {

        function __construct() {
            // 
        }

        /**
         * 
         * @param type $message
         */
        public static function workreap_success($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="wt-jobalerts">';
            $output .= '<div class = "alert alert-success alert-dismissible fade show">';
			$output .= '<span>';
			if( !empty( $msg_type ) ){
				$output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
			}
			
            
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a href="'.esc_attr( $button ).'" class="wt-alertbtn success">'.$button_text.'</a>';
			}
			
			$output .= '<a href="#" onclick="event_preventDefault(event);" class="close" data-dismiss="alert"><i class="fa fa-close"></i></a>';
            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

        /**
         * 
         * @param type $message
         */
        public static function workreap_error($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="wt-jobalerts">';
            $output .= '<div class = "alert alert-danger alert-dismissible fade show">';
			$output .= '<span>';
			if( !empty( $msg_type ) ){
				$output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
			}
			
            
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a href="'.esc_attr( $button ).'" class="wt-alertbtn danger">'.esc_html( $button_text ).'</a>';
			}
			
			$output .= '<a href="#" onclick="event_preventDefault(event);" class="close" data-dismiss="alert"><i class="fa fa-close"></i></a>';
            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

        /**
         * 
         * @param type $message
         */
        public static function workreap_info($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="wt-jobalerts">';
            $output .= '<div class = "alert alert-primary alert-dismissible fade show">';
			$output .= '<span>';
			if( !empty( $msg_type ) ){
				$output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
			}
			
            
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a href="'.esc_attr( $button ).'" class="wt-alertbtn primary">'.esc_html( $button_text ).'</a>';
			}
			
			$output .= '<a href="#" onclick="event_preventDefault(event);" class="close" data-dismiss="alert"><i class="fa fa-close"></i></a>';
            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

        /**
         * 
         * @param type $message
         */
        public static function workreap_warning($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="wt-jobalerts">';
            $output .= '<div class = "alert alert-warning alert-dismissible fade show">';
			$output .= '<span>';
			
			if( !empty( $msg_type ) ){
				$output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
			}
			
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a href="'.esc_attr( $button ).'" class="wt-alertbtn warning">'.esc_html( $button_text ).'</a>';
			}
			
			$output .= '<a href="#" onclick="event_preventDefault(event);" class="close" data-dismiss="alert"><i class="fa fa-close"></i></a>';
            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

    }

    new Workreap_Prepare_Notification();
}
