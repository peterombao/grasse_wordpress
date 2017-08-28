<?php 
add_filter( 'prima_theme_settings_args', 'prima_theme_feed_settings', 15 );
function prima_theme_feed_settings( $settings ) {
	$settings[] = array( "name" => __('Feed Settings', 'primathemes'),
						"icon" => "feed",
						"type" => "heading");
	$settings[] = array( "name" => __('Alternate Feed Url', 'primathemes'),
						"desc" => '',
						"id" => "feed_url",
						"std" => "",
						"type" => "text");
	$settings[] = array( "name" => __('Comment Feed Url', 'primathemes'),
						"desc" => '',
						"id" => "comment_feed_url",
						"std" => "",
						"type" => "text");
	$settings[] = array( "name" => __('Hide Extra Feed', 'primathemes'),
						"desc" => __('Hide Extra Feed', 'primathemes'),
						"id" => "hide_extra_feed",
						"std" => false,
						"type" => "checkbox");
	return $settings;
}
function prima_feed_link( $output, $feed ) {
	$feed_url = esc_url( prima_get_setting( 'feed_url' ) );
	$output = ( $feed_url && !strpos($output, 'comments') ) ? $feed_url : $output;
	$comment_feed_url = esc_url( prima_get_setting( 'comment_feed_url' ) );
	$output = ( $comment_feed_url && strpos($output, 'comments') ) ? $comment_feed_url : $output;
	return $output;
}
function prima_hide_extra_feed() {
	if ( prima_get_setting( 'hide_extra_feed' ) )
		remove_action( 'wp_head', 'feed_links_extra', 3 );
}
if ( !is_admin() ) {
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	add_action( 'init', 'prima_hide_extra_feed');
	add_filter( 'feed_link', 'prima_feed_link', 1, 2 );
}