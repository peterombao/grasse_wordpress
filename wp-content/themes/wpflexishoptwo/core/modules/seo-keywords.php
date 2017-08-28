<?php
add_filter( 'prima_seo_settings_args', 'prima_seo_keywords_settings' );
function prima_seo_keywords_settings( $settings ) {
	$settings[] = array( "name" => __('Meta Keywords', 'primathemes'),
						"icon" => "meta-keywords",
						"type" => "heading");
	$settings[] = array( "name" => __('Note:', 'primathemes'),
						"std" => __( 'Meta Keywords can also be edited on a <strong>per post/taxonomy basis</strong> when creating/editing posts/taxonimies.', 'primathemes' ),
						"type" => "info");
	$settings[] = array( "name" => __('Home Page', 'primathemes'),
						"desc" => '',
						"id" => "keywords_home",
						"std" => '',
						"type" => "text");
	return $settings;
}
add_filter( 'prima_posts_meta_box_seo_args', 'prima_meta_box_seo_keywords_args' );
add_filter( 'prima_taxonomy_meta_box_seo_args', 'prima_meta_box_seo_keywords_args' );
function prima_meta_box_seo_keywords_args( $meta) {
	$meta['keywords'] = array( "label" => __('Custom Meta Keywords', 'primathemes'),
						"desc" => __("Add a custom meta keywords for this section. (comma seperated)", 'primathemes'),
						"name" => "_prima_keywords",
						"std" => "",
						"type" => "text");
	return $meta;
}
add_action( 'wp_head', 'prima_meta_keywords');
function prima_meta_keywords() {
	global $wp_query;
	$keywords = '';
	if ( ( is_home() || is_front_page() ) && prima_get_setting( 'keywords_home', PRIMA_SEO_SETTINGS ) )
		$keywords = prima_get_setting( 'keywords_home', PRIMA_SEO_SETTINGS );
	elseif ( is_author() ) {
		$keywords = get_the_author_meta( 'keywords', get_query_var( 'author' ) );
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
		$keywords_intax = prima_get_taxonomy_meta( $term->term_id, $taxonomy, "_prima_keywords" );
		if ( $keywords_intax ) $keywords = $keywords_intax;
	}
	elseif ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		$keywords_inpost = prima_get_post_meta( "_prima_keywords", $postid );
		if ( $keywords_inpost ) $keywords = $keywords_inpost;
		/*
		if ( empty( $keywords ) ) {
			$taxonomies = get_object_taxonomies( $wp_query->post->post_type );
			if ( is_array( $taxonomies ) ) {
				foreach ( $taxonomies as $tax ) {
					if ( $terms = get_the_term_list( $wp_query->post->ID, $tax, '', ', ', '' ) )
						$keywords[] = $terms;
				}
			}
			if ( !empty( $keywords ) )
				$keywords = join( ', ', $keywords );
		}
		*/
	}
	$keywords = esc_attr( strip_tags( $keywords ) );
	if ( !empty( $keywords ) )
		$keywords = '<meta name="keywords" content="' . $keywords . '" />' . "\n";
	echo $keywords;
}
