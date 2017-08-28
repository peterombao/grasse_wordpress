<?php
global $prima_registered_sidebar_areas;
$prima_registered_sidebar_areas = array();
function prima_register_sidebar_area( $args = array() ) {
	global $prima_registered_sidebar_areas;
	if ( !is_array($prima_registered_sidebar_areas) )
		$prima_registered_sidebar_areas = array();
	$i = count($prima_registered_sidebar_areas) + 1;
	$defaults = array(
		'id' => '', 
		'label' => ''
	);
	$sidebar = wp_parse_args($args, $defaults);
	$id = $sidebar['id'] ? $sidebar['id'] : $i;
	$prima_registered_sidebar_areas[$id] = $sidebar;
	return $id;
}

function prima_sidebar( $sid = '' ) {
	global $prima_registered_layouts;
	wp_reset_query();
	$layout = prima_get_layout();
	if ( !$layout ) return;
	if ( isset( $prima_registered_layouts[$layout]["sidebar{$sid}"] ) && $prima_registered_layouts[$layout]["sidebar{$sid}"] )
		get_sidebar( $sid );
}

add_action('init', 'prima_register_generated_sidebars', 15);
function prima_register_generated_sidebars() {
	$_sidebars = stripslashes_deep( get_option( PRIMA_SIDEBAR_SETTINGS ) );
	if ( !$_sidebars ) return;
	foreach ( (array)$_sidebars as $id => $info ) {
		if ( $id != '0' ) {
			register_sidebar(array(
				'name' => esc_html( $info['name'] ),
				'id' => $id,
				'description' => esc_html( $info['description'] ),
				'editable' => 1,
				'before_widget' => '<div id="%1$s" class="widget widget-container widget-sidebar %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3>',
				'after_title' => '</h3>',
			));
		}
	}
}

function prima_get_sidebar( $default = '', $sid = '' ) {
	if ( !current_theme_supports('prima-sidebar-settings') ) return $default;
	global $wp_query, $wp_registered_sidebars;
	$prima_registered_sidebars = $wp_registered_sidebars;
	$pre_sidebar = apply_filters("prima_sidebar{$sid}", false);
	if ( false !== $pre_sidebar )
		return esc_attr($pre_sidebar);
	if ( is_front_page() && get_option('show_on_front') == 'page' && get_option('page_on_front') > 0 ) {
		$sidebar_inpost = prima_get_post_meta( "_prima_sidebar{$sid}", get_option('page_on_front') );
		if ( $sidebar_inpost && isset($prima_registered_sidebars[$sidebar_inpost]) )
			return $sidebar_inpost;
	}
	elseif ( is_home() && get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) {
		$sidebar_inpost = prima_get_post_meta( "_prima_sidebar{$sid}", get_option('page_for_posts') );
		if ( $sidebar_inpost && isset($prima_registered_sidebars[$sidebar_inpost]) )
			return $sidebar_inpost;
	}
	elseif ( is_front_page() ) {
		$sidebar_home = prima_get_setting( "sidebar{$sid}_home" );
		if ( $sidebar_home && isset($prima_registered_sidebars[$sidebar_home]) )
			return $sidebar_home;
	}
	elseif ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		$sidebar_inpost = prima_get_post_meta( "_prima_sidebar{$sid}", $postid );
		if ( $sidebar_inpost && isset($prima_registered_sidebars[$sidebar_inpost]) )
			return $sidebar_inpost;
		$sidebar_post = prima_get_setting( "sidebar{$sid}_{$posttype}" );
		if ( $sidebar_post && isset($prima_registered_sidebars[$sidebar_post]) )
			return $sidebar_post;
	}
	elseif ( is_archive() ) {
		if ( is_category() || is_tag() || is_tax() ) {
			$term = $wp_query->get_queried_object();
			if ( !$term && is_category() && get_query_var('cat') ) 
				$term = get_term( get_query_var('cat'), 'category' );
			if ( !$term && is_category() && get_query_var('category_name') ) 
				$term = get_term( prima_slug2id( get_query_var('category_name') ), 'category' );
			if ( !$term && is_tag() && get_query_var('tag_id') ) 
				$term = get_term( get_query_var('tag_id'), 'post_tag' );
			if ( !$term && is_tag() && get_query_var('tag') ) 
				$term = get_term( prima_slug2id( get_query_var('tag') ), 'post_tag' );
			$taxonomy = $term->taxonomy;
			$sidebar_intax = prima_get_taxonomy_meta( $term->term_id, $taxonomy, "_prima_sidebar{$sid}" );
			if ( $sidebar_intax && isset($prima_registered_sidebars[$sidebar_intax]) )
				return $sidebar_intax;
			$sidebar_tax = prima_get_setting( "sidebar{$sid}_{$taxonomy}" );
			if ( $sidebar_tax && isset($prima_registered_sidebars[$sidebar_tax]) )
				return $sidebar_tax;
		}
		elseif ( is_post_type_archive() ) {
			$posttype = get_query_var( 'post_type' );
			$sidebar_posttype = prima_get_setting( "sidebar{$sid}_archive_{$posttype}" );
			if ( $sidebar_posttype && isset($prima_registered_sidebars[$sidebar_posttype]) )
				return $sidebar_posttype;
		}
		elseif ( is_author() ) {
			$sidebar_author = prima_get_setting( "sidebar{$sid}_author" );
			if ( $sidebar_author && isset($prima_registered_sidebars[$sidebar_author]) )
				return $sidebar_author;
		}
		elseif ( is_date() ) {
			$sidebar_date = prima_get_setting( "sidebar{$sid}_date" );
			if ( $sidebar_date && isset($prima_registered_sidebars[$sidebar_date]) )
				return $sidebar_date;
		}
	}
	elseif ( is_search() ) {
		$sidebar_search = prima_get_setting( "sidebar{$sid}_search" );
		if ( $sidebar_search && isset($prima_registered_sidebars[$sidebar_search]) )
			return $sidebar_search;
	}
	elseif ( is_404() ) {
		$sidebar_404 = prima_get_setting( "sidebar{$sid}_404" );
		if ( $sidebar_404 && isset($prima_registered_sidebars[$sidebar_404]) )
			return $sidebar_404;
	}
	$sidebar_default = prima_get_setting( "sidebar{$sid}_default" );
	if ( $sidebar_default && isset($prima_registered_sidebars[$sidebar_default]) )
		return $sidebar_default;
	return $default;
}

function prima_dynamic_sidebar( $default = '', $sid = '' ) {
	$sidebar = prima_get_sidebar( $default, $sid );
	if ( $sidebar ) dynamic_sidebar( $sidebar );
}

function prima_dynamic_sidebar_name( $default = '', $sid = '' ) {
	global $wp_registered_sidebars;
	$sidebar = prima_get_sidebar( $default, $sid );
	if ( !$sidebar ) return false;
	if ( !isset($wp_registered_sidebars[$sidebar]) ) return false;	
	return $wp_registered_sidebars[$sidebar]['name'];
}