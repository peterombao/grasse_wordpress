<?php
add_filter( 'prima_theme_settings_args', 'prima_theme_scripts_settings', 15 );
function prima_theme_scripts_settings( $settings ) {
	$settings[] = array( "name" => __('Custom Scripts', 'primathemes'),
						"icon" => "scripts",
						"type" => "heading");
	$settings[] = array( "name" => __('Header Script', 'primathemes'),
						"desc" => __( 'This script will be executed using <code>wp_head</code> hook.', 'primathemes' ),
						"id" => "script_header",
						"type" => "textarea");
	$settings[] = array( "name" => __('Footer Script', 'primathemes'),
						"desc" => __( 'This script will be executed using <code>wp_footer</code> hook.', 'primathemes' ),
						"id" => "script_footer",
						"type" => "textarea");
	return $settings;
}
function prima_header_scripts() {
	if ( prima_get_setting( 'script_header' ) )
		echo prima_get_setting( 'script_header' );
}
function prima_footer_scripts() {
	if ( prima_get_setting( 'script_footer' ) )
		echo prima_get_setting( 'script_footer' );
}
if ( !is_admin() ) {
	add_action( 'wp_head', 'prima_header_scripts');
	add_action( 'wp_footer', 'prima_footer_scripts' );
}
