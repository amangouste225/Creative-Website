<?php
/**
 * Register all email templates
 * @since    1.0.0
 */

$dir = WorkreapGlobalSettings::get_plugin_path();
$scan_PostTypes = glob("$dir/helpers/templates/*.*");
if( !empty( $scan_PostTypes ) ){
	foreach ($scan_PostTypes as $filename) {
		$file = pathinfo($filename);
    	if( !empty( $file['filename'] ) ){
			@include workreap_template_exsits( 'helpers/templates/'.$file['filename'] );
		} 
	}
}