<?php
add_action( 'admin_init', 'prima_create_taxonomy_meta_box' ); 
function prima_create_taxonomy_meta_box() { 
	foreach (get_taxonomies(array('show_ui' => true)) as $taxonomy) { 
		if ( prima_taxonomy_meta_box_general_args( $taxonomy ) )
			add_action($taxonomy . '_edit_form', 'prima_taxonomy_meta_box_general', 10, 2); 
		if ( prima_taxonomy_meta_box_template_args( $taxonomy ) )
			add_action($taxonomy . '_edit_form', 'prima_taxonomy_meta_box_template', 10, 2); 
		if ( current_theme_supports( 'prima-seo-settings' ) && prima_taxonomy_meta_box_seo_args( $taxonomy ) )
			add_action($taxonomy . '_edit_form', 'prima_taxonomy_meta_box_seo', 10, 2); 
	} 
}
add_action( 'admin_menu', 'prima_load_taxonomy_meta_box' );
function prima_load_taxonomy_meta_box() {
	add_action( "load-edit-tags.php", 'prima_meta_box_enqueue_script' );
	add_action( "load-edit-tags.php", 'prima_meta_box_enqueue_style' );
}
function prima_taxonomy_meta_box_general_args( $taxonomy = '' ) {
	$meta = array();
	if ( empty( $taxonomy ) ) $taxonomy = 'category';
	$meta = apply_filters( "prima_taxonomy_meta_box_general_args", $meta, $taxonomy );
	return apply_filters( "prima_{$taxonomy}_meta_box_general_args", $meta, $taxonomy );
}
function prima_taxonomy_meta_box_template_args( $taxonomy = '' ) {
	$meta = array();
	if ( empty( $taxonomy ) ) $taxonomy = 'category';
	$meta = apply_filters( "prima_taxonomy_meta_box_template_args", $meta, $taxonomy );
	return apply_filters( "prima_{$taxonomy}_meta_box_template_args", $meta, $taxonomy );
}
function prima_taxonomy_meta_box_seo_args( $taxonomy = '' ) {
	$meta = array();
	if ( empty( $taxonomy ) ) $taxonomy = 'category';
	$meta = apply_filters( "prima_taxonomy_meta_box_seo_args", $meta, $taxonomy );
	return apply_filters( "prima_{$taxonomy}_meta_box_seo_args", $meta, $taxonomy );
}
function prima_taxonomy_meta_box_general( $term, $taxonomy ) {
	$meta = prima_taxonomy_meta_box_general_args( $taxonomy );
	if ( !$meta ) return;
	echo '<p class="submit"><input type="submit" value="'.__('Update', 'primathemes').'" class="button-primary" id="submit" name="submit"></p>';
	global $prima_child_data;
	$theme_data = $prima_child_data;
	echo '<div id="prima-meta-box-general">';
	echo '<h3>'.sprintf( __( '%1$s Settings', 'primathemes' ), $name ).'</h3>';
	echo prima_meta_generator( $meta, 'taxonomy', $term->term_id, $taxonomy );
	echo '</div>';
}
function prima_taxonomy_meta_box_template( $term, $taxonomy ) {
	$meta = prima_taxonomy_meta_box_template_args( $taxonomy );
	if ( !$meta ) return;
	echo '<p class="submit"><input type="submit" value="'.__('Update', 'primathemes').'" class="button-primary" id="submit" name="submit"></p>';
	echo '<div id="prima-meta-box-template">';
	$tax = get_taxonomy( $taxonomy );
	$name = $tax->labels->singular_name ? $tax->labels->singular_name : $tax->labels->name;
	echo '<h3>'.sprintf( __( '%1$s Template Settings', 'primathemes' ), $name ).'</h3>';
	echo prima_meta_generator( $meta, 'taxonomy', $term->term_id, $taxonomy );
	echo '</div>';
}
function prima_taxonomy_meta_box_seo( $term, $taxonomy ) {
	$meta = prima_taxonomy_meta_box_seo_args( $taxonomy );
	if ( !$meta ) return;
	echo '<p class="submit"><input type="submit" value="'.__('Update', 'primathemes').'" class="button-primary" id="submit" name="submit"></p>';
	$tax = get_taxonomy( $taxonomy );
	$name = $tax->labels->singular_name ? $tax->labels->singular_name : $tax->labels->name;
	echo '<div id="prima-meta-box-seo">';
	echo '<h3>'.sprintf( __( '%1$s SEO Settings', 'primathemes' ), $name ).'</h3>';
	echo prima_meta_generator( $meta, 'taxonomy', $term->term_id, $taxonomy );
	echo '</div>';
}
add_action('edit_term', 'prima_save_taxonomy_meta', 10, 3);
function prima_save_taxonomy_meta( $term_id, $tt_id, $taxonomy ) {
	$tax = get_taxonomy( $taxonomy ); if ( !$tax->show_ui ) return;
	$tax_meta = (array) get_option( 'prima_taxonomy_meta' );
	$meta_general = prima_taxonomy_meta_box_general_args( $taxonomy );
	$meta_template = prima_taxonomy_meta_box_template_args( $taxonomy );
	$meta_seo = prima_taxonomy_meta_box_seo_args( $taxonomy );
	$metadata = array_merge($meta_general, $meta_template, $meta_seo);
	if ( $metadata ) {
		foreach ( $metadata as $meta ) {
			// $meta_value = stripslashes( $_POST[ preg_replace( "/[^A-Za-z_-]/", '-', $meta['name'] ) ] );
			$meta_value = stripslashes( $_POST[ $meta['name'] ] );
			$tax_meta[$taxonomy][$term_id][$meta['name']] = $meta_value;
		}
	}
	update_option('prima_taxonomy_meta', (array) $tax_meta);
}
add_action('delete_term', 'prima_delete_taxonomy_meta', 10, 3);
function prima_delete_taxonomy_meta( $term_id, $tt_id, $taxonomy ) {
	$tax = get_taxonomy( $taxonomy ); if ( !$tax->show_ui ) return;
	$tax_meta = (array) get_option( 'prima_taxonomy_meta' );
	unset( $tax_meta[$taxonomy][$term_id] );
	update_option('prima_taxonomy_meta', (array) $tax_meta);
}