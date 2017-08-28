<?php
global $prima_parent_data, $prima_child_data;
$prima_parent_data = get_theme_data( PRIMA_DIR . '/style.css' );
$prima_child_data = get_theme_data( THEME_DIR . '/style.css' );

function prima_add_taxonomy_support( $taxonomy, $feature ) {
	global $_prima_taxonomy_features;
	$features = (array) $feature;
	foreach ($features as $feature) {
		if ( func_num_args() == 2 )
			$_prima_taxonomy_features[$taxonomy][$feature] = true;
		else
			$_prima_taxonomy_features[$taxonomy][$feature] = array_slice( func_get_args(), 2 );
	}
}

function prima_remove_taxonomy_support( $taxonomy, $feature ) {
	global $_prima_taxonomy_features;
	if ( !isset($_prima_taxonomy_features[$taxonomy]) )
		return;
	if ( isset($_prima_taxonomy_features[$taxonomy][$feature]) )
		unset($_prima_taxonomy_features[$taxonomy][$feature]);
}

function prima_taxonomy_supports( $taxonomy, $feature ) {
	global $_prima_taxonomy_features;
	if ( !isset( $_prima_taxonomy_features[$taxonomy][$feature] ) )
		return false;
	if ( func_num_args() <= 2 )
		return true;
	return true;
}

add_action( 'init', 'prima_add_post_type' );
function prima_add_post_type() {
	register_post_type( 'primaframework', array(
		'labels' => array(
			'name' => 'Prima Framework',
		),
		'public' => true,
		'show_ui' => false,
		'capability_type' => 'post',
		'exclude_from_search' => true,
		'hierarchical' => false,
		'rewrite' => false,
		'supports' => array( 'title', 'editor' ),
		'can_export' => true,
		'show_in_nav_menus' => false,
	) );
}

function prima_framework_post_id( $title = '' ) {
	global $wpdb;
	return $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '{$title}' AND post_type = 'primaframework' AND post_status != 'trash'");
}

function prima_get_setting( $option = '', $field = null, $format = '' ) {
	global $prima;
	$field = $field ? $field : PRIMA_THEME_SETTINGS;
	if ( !$option )
		return false;
	$pre_setting = apply_filters('prima_get_setting_'.$option, false, $field);
	if ( false !== $pre_setting )
		return $pre_setting;
	$settings = get_option( $field );
	if ( !array_key_exists($option, (array) $settings) )
		return false;
	$output = wp_kses_stripslashes( $settings[$option] );
	if ( !$output ) return false;
	if ( !$format ) return $output;
	else return str_replace("%setting%", $output, $format);
}

function prima_setting( $option, $field = null, $format = '' ) {
	echo prima_get_setting( $option, $field, $format );
}

function prima_get_post_meta( $meta, $postid = '', $format = '' ) {
	if ( !$postid ) { 
		global $post;
		if ( null === $post ) return FALSE;
		else $postid = $post->ID;
	}
	$meta_value = get_post_meta($postid, $meta, true);
	if ( !$meta_value ) return FALSE;
	$meta_value = wp_kses_stripslashes( wp_kses_decode_entities( $meta_value ) );
	if ( !$format ) return $meta_value;
	else return str_replace("%meta%", $meta_value, $format);
}

function prima_post_meta( $meta, $postid = '', $format = '' ) {
	echo prima_get_post_meta( $meta, $postid, $format );
}

function prima_get_taxonomy_meta( $term_id, $taxonomy, $meta ) {
	$tax_meta = get_option( 'prima_taxonomy_meta' );
	return (isset($tax_meta[$taxonomy][$term_id][$meta])) ? $tax_meta[$taxonomy][$term_id][$meta] : false;
}

function prima_taxonomy_meta( $term_id, $taxonomy, $meta ) {
	echo prima_get_taxonomy_meta( $term_id, $taxonomy, $meta );
}

function prima_get_transient_expiration() {
	return apply_filters( 'prima_transient_expiration', 43200 );
}

function prima_slug2id( $slug ) {
	global $wpdb;
	$sqlstr  = "SELECT term_id FROM $wpdb->terms WHERE slug = '". $wpdb->escape($slug) ."'";
	$term_id = (int) $wpdb->get_var($sqlstr);
	if ( $term_id ) return $term_id;
	else return false;
}

