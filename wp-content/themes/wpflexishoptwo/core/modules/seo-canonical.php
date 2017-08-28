<?php
add_filter( 'prima_posts_meta_box_seo_args', 'prima_meta_box_seo_canonical_args' );
add_filter( 'prima_taxonomy_meta_box_seo_args', 'prima_meta_box_seo_canonical_args' );
function prima_meta_box_seo_canonical_args( $meta ) {
	$meta['seo-canonical'] = array( "label" => __('Custom Canonical', 'primathemes'),
						"desc" => __("Add custom canonical link for this section.", 'primathemes'),
						"name" => "_prima_canonical",
						"type" => "text");
	return $meta;
}
add_action( 'wp_head', 'prima_head_canonical');
function prima_head_canonical() {
	remove_action( 'wp_head', 'rel_canonical');
	global $wp_query;
	$canonical = '';
	if ( is_author() ) {
		if ( !$id = $wp_query->get_queried_object_id() ) return;
		if ( is_paged() ) $canonical = get_author_posts_url( $id );
	}
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
		if ( is_paged() ) $canonical = get_term_link( (int)$term->term_id, $taxonomy );
		$canonical_intax = prima_get_taxonomy_meta( $term->term_id, $taxonomy, "_prima_canonical" );
		if ( $canonical_intax ) $canonical = $canonical_intax;
	}
	elseif ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		if ( is_paged() ) $canonical = get_permalink( $postid );
		$canonical_inpost = prima_get_post_meta( "_prima_canonical", $postid );
		if ( $canonical_inpost ) $canonical = $canonical_inpost;
	}
	if ( !empty( $canonical ) )
		$canonical = '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
	echo $canonical;
}
