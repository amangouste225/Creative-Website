<?php
/*
Plugin Name: instant.page
Plugin URI: https://instant.page/wordpress
Description: Make your site’s pages instant in 1 minute.
Author: Alexandre Dieulot
Version: 5.6.0
Author URI: https://dieulot.fr/
*/

add_action( 'wp_enqueue_scripts', 'instantpage_wp_enqueue_scripts' );
add_filter( 'script_loader_tag', 'instantpage_script_loader_tag', 10, 2 );

function instantpage_wp_enqueue_scripts() {
  wp_enqueue_script( 'instantpage', plugin_dir_url( __FILE__ ) . 'instantpage.js', array(), '5.6.0', true );
}

function instantpage_script_loader_tag( $tag, $handle ) {
  if ( 'instantpage' === $handle ) {
    if ( strpos( $tag, 'text/javascript' ) !== false ) {
      $tag = str_replace( 'text/javascript', 'module', $tag );
    }
    else {
      $tag = str_replace( '<script ', "<script type='module' ", $tag );
    }
  }
  return $tag;
}
