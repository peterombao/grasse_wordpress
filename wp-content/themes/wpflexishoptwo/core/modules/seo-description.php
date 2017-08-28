<?php
add_filter( 'prima_seo_settings_args', 'prima_seo_description_settings' );
function prima_seo_description_settings( $settings ) {
	$settings[] = array( "name" => __('Meta Description', 'primathemes'),
						"icon" => "meta-description",
						"type" => "heading");
	$settings[] = array( "name" => __('Note:', 'primathemes'),
						"std" => __( 'Meta Description can also be edited on a <strong>per post/taxonomy basis</strong> when creating/editing posts/taxonimies.', 'primathemes' ),
						"type" => "info");
	$settings[] = array( "name" => __('Home Page', 'primathemes'),
						"desc" => __( 'Available tags:', 'primathemes' ).' <code>%sitedesc%</code>',
						"id" => "desc_home",
						"std" => '%sitedesc%',
						"type" => "textarea");
	$post_types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'objects' );
	foreach ( $post_types as $type ) {
		$name = $type->labels->singular_name ? $type->labels->singular_name : $type->labels->name;
		if ($type->name == 'attachment') $name = __('Media/Attachment', 'primathemes');
		$settings[] = array( "name" => __('Post Type:', 'primathemes').' '.$name,
							"desc" => __( 'Available tags:', 'primathemes' ).' <code>%posttitle%</code> <br/><code>%excerpt%</code> ('.__( 'auto-generated', 'primathemes' ).') <br/><code>%excerpt_only%</code> ('.__( 'without auto-generation', 'primathemes' ).') ',
							"id" => "desc_{$type->name}",
							"std" => '%excerpt_only%',
							"type" => "text");
	}
	foreach (get_taxonomies(array('show_ui' => true)) as $taxonomy) { 
		$tax = get_taxonomy( $taxonomy );
		$name = $tax->labels->singular_name ? $tax->labels->singular_name : $tax->labels->name;
		$settings[] = array( "name" => __('Taxonomy:', 'primathemes').' '.$name,
							"desc" => __( 'Available tags:', 'primathemes' ).' <code>%term_name%</code>, <code>%term_description%</code>',
							"id" => "desc_{$taxonomy}",
							"std" => '%term_description%',
							"type" => "text");
	}
	$settings[] = array( "name" => __('Author Page', 'primathemes'),
						"desc" => __( 'Available tags:', 'primathemes' ).' <code>%name%</code>, <code>%userdesc%</code>',
						"id" => "desc_author",
						"std" => '%userdesc%',
						"type" => "text");
	return $settings;
}
add_filter( 'prima_posts_meta_box_seo_args', 'prima_meta_box_seo_description_args' );
add_filter( 'prima_taxonomy_meta_box_seo_args', 'prima_meta_box_seo_description_args' );
function prima_meta_box_seo_description_args( $meta ) {
	$meta['seo-description'] = array( "label" => __('Custom Meta Description', 'primathemes'),
						"desc" => __("Add custom meta description for this section.", 'primathemes'),
						"name" => "_prima_description",
						"std" => "",
						"counter" => true,
						"type" => "textarea");
	return $meta;
}
add_action( 'wp_head', 'prima_meta_description');
function prima_meta_description() {
	global $wp_query;
	$description = '';
	if ( ( is_home() || is_front_page() ) && prima_get_setting( 'desc_home', PRIMA_SEO_SETTINGS ) )
		$description = str_replace("%sitedesc%", get_bloginfo('description'), prima_get_setting( 'desc_home', PRIMA_SEO_SETTINGS ));
	elseif ( is_author()  && prima_get_setting( 'desc_author', PRIMA_SEO_SETTINGS ) ) {
		$description = prima_get_setting( 'desc_author', PRIMA_SEO_SETTINGS );
		$description = str_replace("%name%", get_the_author_meta( 'display_name', get_query_var( 'author' ) ), $description);
		$description = str_replace("%userdesc%", get_the_author_meta( 'description', get_query_var( 'author' ) ), $description);
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
		$description_intax = prima_get_taxonomy_meta( $term->term_id, $taxonomy, "_prima_description" );
		if ( $description_intax ) 
			$description = $description_intax;
		if ( !$description && prima_get_setting( "desc_{$taxonomy}", PRIMA_SEO_SETTINGS ) ) {
			$description = prima_get_setting( "desc_{$taxonomy}", PRIMA_SEO_SETTINGS );
			$description = str_replace("%term_name%", $term->name, $description);
			$description = str_replace("%term_description%", $term->description, $description);
		}
	}
	elseif ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		$description_inpost = prima_get_post_meta( "_prima_description", $postid );
		if ( $description_inpost ) $description = $description_inpost;
		if ( !$description && prima_get_setting( "desc_{$posttype}", PRIMA_SEO_SETTINGS ) ) {
			$description = prima_get_setting( "desc_{$posttype}", PRIMA_SEO_SETTINGS );
			$description = str_replace("%posttitle%", $wp_query->post->post_title, $description);
			$description = str_replace("%excerpt_only%", $wp_query->post->post_excerpt, $description);
			$description = str_replace("%excerpt%", wp_trim_excerpt($wp_query->post->post_content), $description);
		}
	}
	$description = str_replace( array( "\r", "\n", "\t" ), '', esc_attr( strip_tags( $description ) ) );
	if ( !empty( $description ) )
		$description = '<meta name="description" content="' . $description . '" />' . "\n";
	echo $description;
}