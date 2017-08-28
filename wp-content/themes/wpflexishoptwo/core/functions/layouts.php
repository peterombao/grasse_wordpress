<?php
global $prima_registered_layouts, $prima_default_layout;
$prima_registered_layouts = array();
$prima_default_layout = array();
function prima_register_layout( $args = array() ) {
	global $prima_registered_layouts;
	if ( !is_array($prima_registered_layouts) )
		$prima_registered_layouts = array();
	$i = count($prima_registered_layouts) + 1;
	$defaults = array(
		'id' => "layout-$i", 
		'class' => '', 
		'label' => '', 
		'description' => '', 
		'sidebar' => '',
		'image' => ''
	);
	$layout = wp_parse_args($args, $defaults);
	$prima_registered_layouts[$layout['id']] = $layout;
	return $layout['id'];
}
function prima_get_layout() {
	global $wp_query, $prima_default_layout, $prima_registered_layouts;
	wp_reset_query();
	$pre_layout = apply_filters('prima_layout', false);
	if ( false !== $pre_layout )
		return esc_attr($pre_layout);
	if ( is_front_page() && get_option('show_on_front') == 'page' && get_option('page_on_front') > 0 ) {
		$layout_inpost = prima_get_post_meta( "_prima_layout", get_option('page_on_front') );
		if ( $layout_inpost && isset($prima_registered_layouts[$layout_inpost]) )
			return $layout_inpost;
	}
	elseif ( is_home() && get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) {
		$layout_inpost = prima_get_post_meta( "_prima_layout", get_option('page_for_posts') );
		if ( $layout_inpost && isset($prima_registered_layouts[$layout_inpost]) )
			return $layout_inpost;
	}
	elseif ( is_front_page() ) {
		$layout_home = prima_get_setting( 'layout_home' );
		if ( $layout_home && isset($prima_registered_layouts[$layout_home]) )
			return $layout_home;
	}
	elseif ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		$layout_inpost = prima_get_post_meta( "_prima_layout", $postid );
		if ( post_type_supports( $posttype, "prima-layout-settings" ) && $layout_inpost && isset($prima_registered_layouts[$layout_inpost]) )
			return $layout_inpost;
		$layout_post = prima_get_setting( "layout_{$posttype}" );
		if ( post_type_supports( $posttype, "prima-layout-settings" ) && $layout_post && isset($prima_registered_layouts[$layout_post]) )
			return $layout_post;
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
			$layout_intax = prima_get_taxonomy_meta( $term->term_id, $taxonomy, "_prima_layout" );
			if ( $layout_intax && isset($prima_registered_layouts[$layout_intax]) )
				return $layout_intax;
			$layout_tax = prima_get_setting( "layout_{$taxonomy}" );
			if ( $layout_tax && isset($prima_registered_layouts[$layout_tax]) )
				return $layout_tax;
		}
		elseif ( is_post_type_archive() ) {
			$posttype = get_query_var( 'post_type' );
			$layout_posttype = prima_get_setting( "layout_archive_{$posttype}" );
			if ( $layout_posttype && isset($prima_registered_layouts[$layout_posttype]) )
				return $layout_posttype;
		}
		elseif ( is_author() ) {
			$layout_author = prima_get_setting( 'layout_author' );
			if ( $layout_author && isset($prima_registered_layouts[$layout_author]) )
				return $layout_author;
		}
		elseif ( is_date() ) {
			$layout_date = prima_get_setting( 'layout_date' );
			if ( $layout_date && isset($prima_registered_layouts[$layout_date]) )
				return $layout_date;
		}
	}
	elseif ( is_search() ) {
		$layout_search = prima_get_setting( 'layout_search' );
		if ( $layout_search && isset($prima_registered_layouts[$layout_search]) )
			return $layout_search;
	}
	elseif ( is_404() ) {
		$layout_404 = prima_get_setting( 'layout_404' );
		if ( $layout_404 && isset($prima_registered_layouts[$layout_404]) )
			return $layout_404;
	}
	$layout_default = prima_get_setting( 'layout_default' );
	if ( $layout_default && isset($prima_registered_layouts[$layout_default]) )
		return $layout_default;
	return false;
}
add_filter( 'body_class', 'prima_layout_body_class' );
function prima_layout_body_class( $classes ) {
	global $prima_registered_layouts;
	$layout = prima_get_layout();
	if ( !empty( $layout ) ) {
		$classes[] = $layout;
		if(isset($prima_registered_layouts[$layout]['class'])) {
			$layout_class = $prima_registered_layouts[$layout]['class'];
			if($layout_class) $classes[] = $layout_class;
		}
	}
	return $classes;
}
add_filter( 'prima_posts_meta_box_template_args', 'prima_meta_box_posts_layout_args', null, 2 );
function prima_meta_box_posts_layout_args( $meta, $type ) {
	global $prima_registered_layouts, $prima_default_layout;
	if ( !post_type_supports( $type, "prima-layout-settings" ) ) return $meta;
	$layouts = array();
	if ($prima_registered_layouts) { foreach ( $prima_registered_layouts as $layout ) { 
		$layouts[$layout['id']] = $layout['image'];
	} }
	if ( 0 != count( $layouts ) ) {
		$layouts = array_merge( array( '' => $prima_default_layout['image'] ), $layouts );
		$meta['templatelayout'] = array (	"label" => __("Layout", 'primathemes'),
									"desc" => __("Select a specific layout for this section. Overrides default layout setting.", 'primathemes'),
									"name" => "_prima_layout",
									"type" => "images",
									"options" => $layouts );
	}
	return $meta;
}
add_filter( 'prima_taxonomy_meta_box_template_args', 'prima_meta_box_taxonomy_layout_args', null, 2 );
function prima_meta_box_taxonomy_layout_args( $meta, $taxonomy ) {
	global $prima_registered_layouts, $prima_default_layout;
	if ( !prima_taxonomy_supports( $taxonomy, "prima-layout-settings" ) ) return $meta;
	$layouts = array();
	if ($prima_registered_layouts) { foreach ( $prima_registered_layouts as $layout ) { 
		$layouts[$layout['id']] = $layout['image'];
	} }
	if ( 0 != count( $layouts ) ) {
		$layouts = array_merge( array( '' => $prima_default_layout['image'] ), $layouts );
		$meta['templatelayout'] = array (	"label" => __("Layout", 'primathemes'),
									"desc" => __("Select a specific layout for this section. Overrides default layout setting.", 'primathemes'),
									"name" => "_prima_layout",
									"type" => "images",
									"options" => $layouts );
	}
	return $meta;
}
