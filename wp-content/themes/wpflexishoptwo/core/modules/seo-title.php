<?php
add_filter( 'prima_seo_settings_args', 'prima_seo_title_settings' );
function prima_seo_title_settings( $settings ) {
	$settings[] = array( "name" => __('Title', 'primathemes'),
						"icon" => "title",
						"type" => "heading");
	$settings[] = array( "name" => __('Note:', 'primathemes'),
						"std" => __( 'Custom Title can also be edited on a <strong>per post/taxonomy basis</strong> when creating/editing posts/taxonimies.', 'primathemes' ),
						"type" => "info");
	$settings[] = array( "name" => __('Home Page', 'primathemes'),
						"desc" => __( 'Available tags:', 'primathemes' ).' <code>%sitename%</code>, <code>%sitedesc%</code>',
						"id" => "title_home",
						"std" => '%sitename% | %sitedesc%',
						"type" => "text");
	$post_types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'objects' );
	foreach ( $post_types as $type ) {
		$name = $type->labels->singular_name ? $type->labels->singular_name : $type->labels->name;
		if ($type->name == 'attachment') 
			$settings[] = array( "name" => __('Post Type:', 'primathemes').' '.__('Media/Attachment', 'primathemes'),
								"desc" => __( 'Available tags:', 'primathemes' ).' <code>%posttitle%</code>, <code>%sitename%</code>',
								"id" => "title_{$type->name}",
								"std" => __('Attachment', 'primathemes').' : %posttitle%',
								"type" => "text");
		else 
			$settings[] = array( "name" => __('Post Type:', 'primathemes').' '.$name,
								"desc" => __( 'Available tags:', 'primathemes' ).' <code>%posttitle%</code>, <code>%sitename%</code>',
								"id" => "title_{$type->name}",
								"std" => '%posttitle%',
								"type" => "text");
	}
	foreach (get_taxonomies(array('show_ui' => true)) as $taxonomy) { 
		$tax = get_taxonomy( $taxonomy );
		$name = $tax->labels->singular_name ? $tax->labels->singular_name : $tax->labels->name;
		$settings[] = array( "name" => __('Taxonomy:', 'primathemes').' '.$name,
							"desc" => __( 'Available tags:', 'primathemes' ).' <code>%term_name%</code>, <code>%sitename%</code>',
							"id" => "title_{$taxonomy}",
							"std" => $name.' : %term_name% | %sitename%',
							"type" => "text");
	}
	$settings[] = array( "name" => __('Author Page', 'primathemes'),
						"desc" => __( 'Available tags:', 'primathemes' ).' <code>%name%</code>, <code>%sitename%</code>',
						"id" => "title_author",
						"std" => __('Author :', 'primathemes').' %name% | %sitename%',
						"type" => "text");
	$settings[] = array( "name" => __('Search Result Page', 'primathemes'),
						"desc" => __( 'Available tags:', 'primathemes' ).' <code>%searchphrase%</code>, <code>%sitename%</code>',
						"id" => "title_search",
						"std" => __('Search Result :', 'primathemes').' %searchphrase% | %sitename%',
						"type" => "text");
	$settings[] = array( "name" => __('Date Archive Page', 'primathemes'),
						"desc" => __( 'Available tags:', 'primathemes' ).' <code>%currentdate%</code>, <code>%sitename%</code>',
						"id" => "title_date",
						"std" => __('Archive :', 'primathemes').' %currentdate% | %sitename%',
						"type" => "text");
	$settings[] = array( "name" => __('404 Page', 'primathemes'),
						"desc" => __( 'Available tags:', 'primathemes' ).' <code>%sitename%</code>',
						"id" => "title_404",
						"std" => __('404 - Not Found', 'primathemes'),
						"type" => "text");
	return $settings;
}
add_filter( 'prima_posts_meta_box_seo_args', 'prima_meta_box_seo_title_args' );
add_filter( 'prima_taxonomy_meta_box_seo_args', 'prima_meta_box_seo_title_args' );
function prima_meta_box_seo_title_args( $meta ) {
	$meta['seo-title'] = array( "label" => __('Custom Page Title', 'primathemes'),
						"desc" => __("Add custom title for this section.", 'primathemes'),
						"name" => "_prima_title",
						"counter" => true,
						"type" => "text");
	return $meta;
}
add_filter( 'wp_title', 'prima_meta_title', 10, 3);
function prima_meta_title( $title, $sep = '', $seplocation = '' ) {
	if ( is_home() ) $title = get_bloginfo('name');
	global $wp_query;
	$seotitle = '';
	if ( is_404()  && prima_get_setting( 'title_404', PRIMA_SEO_SETTINGS ) )
		$seotitle = prima_get_setting( 'title_404', PRIMA_SEO_SETTINGS );
	elseif ( is_search()  && prima_get_setting( 'title_search', PRIMA_SEO_SETTINGS ) )
		$seotitle = str_replace("%searchphrase%", esc_attr( get_search_query() ), prima_get_setting( 'title_search', PRIMA_SEO_SETTINGS ));
	elseif ( ( is_home() || is_front_page() ) && prima_get_setting( 'title_home', PRIMA_SEO_SETTINGS ) )
		$seotitle = str_replace("%sitedesc%", get_bloginfo('description'), prima_get_setting( 'title_home', PRIMA_SEO_SETTINGS ));
	elseif ( is_author()  && prima_get_setting( 'title_author', PRIMA_SEO_SETTINGS ) )
		$seotitle = str_replace("%name%", get_the_author_meta( 'display_name', get_query_var( 'author' ) ), prima_get_setting( 'title_author', PRIMA_SEO_SETTINGS ));
	elseif ( is_date()  && prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ) ) {
		if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
			$seotitle = str_replace("%currentdate%", get_the_time( __( 'g:i a', 'primathemes' ) ), prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ));
		elseif ( get_query_var( 'minute' ) )
			$seotitle = str_replace("%currentdate%", sprintf( __( 'minute %1$s', 'primathemes' ), get_the_time( __( 'i', 'primathemes' ) ) ), prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ));
		elseif ( get_query_var( 'hour' ) )
			$seotitle = str_replace("%currentdate%", get_the_time( __( 'g a', 'primathemes' ) ), prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ));
		elseif ( is_day() )
			$seotitle = str_replace("%currentdate%", get_the_time( __( 'F jS, Y', 'primathemes' ) ), prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ));
		elseif ( get_query_var( 'w' ) )
			$seotitle = str_replace("%currentdate%", sprintf( __( 'week %1$s of %2$s', 'primathemes' ), get_the_time( __( 'W', 'primathemes' ) ), get_the_time( __( 'Y', 'primathemes' ) ) ), prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ));
		elseif ( is_month() )
			$seotitle = str_replace("%currentdate%", single_month_title( ' ', false), prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ));
		elseif ( is_year() )
			$seotitle = str_replace("%currentdate%", get_the_time( __( 'Y', 'primathemes' ) ), prima_get_setting( 'title_date', PRIMA_SEO_SETTINGS ));
	}
	elseif ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		$seotitle_inpost = prima_get_post_meta( "_prima_title", $postid );
		if ( $seotitle_inpost ) $seotitle = $seotitle_inpost;
		if ( !$seotitle && prima_get_setting( "title_{$posttype}", PRIMA_SEO_SETTINGS ) )
			$seotitle = str_replace("%posttitle%", $wp_query->post->post_title, prima_get_setting( "title_{$posttype}", PRIMA_SEO_SETTINGS ));
	}
	elseif ( is_category() || is_tag() || is_tax() ) {
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
		$seotitle_intax = prima_get_taxonomy_meta( $term->term_id, $taxonomy, "_prima_title" );
		if ( $seotitle_intax ) 
			$seotitle = $seotitle_intax;
		if ( !$seotitle && prima_get_setting( "title_{$taxonomy}", PRIMA_SEO_SETTINGS ) )
			$seotitle = str_replace("%term_name%", $term->name, prima_get_setting( "title_{$taxonomy}", PRIMA_SEO_SETTINGS ));
	}
	$seotitle = str_replace("%sitename%", get_bloginfo('name'), $seotitle);
	if (get_query_var('paged')) {
		$seotitle .= ' - ' . __( 'Page ' , 'primathemes') . get_query_var('paged');
	}
	$seotitle = esc_attr($seotitle);
	// $seotitle = htmlentities(stripslashes($seotitle));
	if ($seotitle) return $seotitle;
	else return $title;
}
