<?php
add_filter( 'prima_seo_settings_args', 'prima_seo_indexation_settings' );
function prima_seo_indexation_settings( $settings ) {
	$settings[] = array( "name" => __('Meta Robots / Indexation', 'primathemes'),
						"icon" => "indexation",
						"type" => "heading");
	$settings[] = array( "name" => __('Note:', 'primathemes'),
						"std" => __( 'Meta Robots / Indexation can also be edited on a <strong>per post/taxonomy basis</strong> when creating/editing posts/taxonimies.', 'primathemes' ),
						"type" => "info");
	$settings[] = array( "name" => __('Home Page', 'primathemes'),
						"desc" => "",
						"id" => "indexation_home",
						"std" => 'index,follow',
						"type" => "select",
						"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	$post_types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'objects' );
	foreach ( $post_types as $type ) {
		$name = $type->labels->singular_name ? $type->labels->singular_name : $type->labels->name;
		if ($type->name == 'attachment') $name = __('Media/Attachment', 'primathemes');
		$settings[] = array( "name" => __('Post Type:', 'primathemes').' '.$name,
							"desc" => "",
							"id" => "indexation_{$type->name}",
							"std" => 'index,follow',
							"type" => "select",
							"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	}
	foreach (get_taxonomies(array('show_ui' => true)) as $taxonomy) { 
		$tax = get_taxonomy( $taxonomy );
		$name = $tax->labels->singular_name ? $tax->labels->singular_name : $tax->labels->name;
		if($taxonomy == 'post_tag')
			$settings[] = array( "name" => __('Taxonomy:', 'primathemes').' '.$name,
								"desc" => "",
								"id" => "indexation_{$taxonomy}",
								"std" => 'noindex,follow',
								"type" => "select",
								"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
		else
			$settings[] = array( "name" => __('Taxonomy:', 'primathemes').' '.$name,
								"desc" => "",
								"id" => "indexation_{$taxonomy}",
								"std" => 'index,follow',
								"type" => "select",
								"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	}
	$settings[] = array( "name" => __('Author Page', 'primathemes'),
						"desc" => "",
						"id" => "indexation_author",
						"std" => 'index,follow',
						"type" => "select",
						"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	$settings[] = array( "name" => __('Search Result Page', 'primathemes'),
						"desc" => "",
						"id" => "indexation_search",
						"std" => 'noindex,follow',
						"type" => "select",
						"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	$settings[] = array( "name" => __('Date Archive Page', 'primathemes'),
						"desc" => "",
						"id" => "indexation_date",
						"std" => 'noindex,follow',
						"type" => "select",
						"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	$settings[] = array( "name" => __('Login Page', 'primathemes'),
						"desc" => "",
						"id" => "indexation_login",
						"std" => 'noindex,nofollow',
						"type" => "select",
						"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	$settings[] = array( "name" => __('Admin Page', 'primathemes'),
						"desc" => "",
						"id" => "indexation_admin",
						"std" => 'noindex,nofollow',
						"type" => "select",
						"options" => array ( 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	$settings[] = array( "name" => '"noarchive" '.__(' meta robot term', 'primathemes'),
						"desc" => __("Google will not archive a copy of the document (Google's cached page)", 'primathemes'),
						"id" => "indexation_noarchive",
						"std" => "",
						"type" => "checkbox");
	$settings[] = array( "name" => '"noodp" '.__(' meta robot term', 'primathemes'),
						"desc" => __("Don't use DMOZ/ODP information for the page's description", 'primathemes'),
						"id" => "indexation_noodp",
						"std" => "",
						"type" => "checkbox");
	$settings[] = array( "name" => '"noydir" '.__(' meta robot term', 'primathemes'),
						"desc" => __("Don't use Yahoo! Directory information for the page's description", 'primathemes'),
						"id" => "indexation_noydir",
						"std" => "",
						"type" => "checkbox");
	return $settings;
}
add_filter( 'prima_posts_meta_box_seo_args', 'prima_meta_box_seo_indexation_args' );
add_filter( 'prima_taxonomy_meta_box_seo_args', 'prima_meta_box_seo_indexation_args' );
function prima_meta_box_seo_indexation_args( $meta ) {
	$meta['seo-indexation'] = array( "label" => __('Custom Meta Robots', 'primathemes'),
						"desc" => __("Add custom meta robots for this section.", 'primathemes'),
						"name" => "_prima_indexation",
						"type" => "select2",
						"options" => array ( '' => 'default', 'index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow' ) );
	return $meta;
}
add_action( 'admin_head', 'prima_admin_indexation' );
function prima_admin_indexation() {
	if ( prima_get_setting( 'indexation_admin', PRIMA_SEO_SETTINGS ) )
		echo '<meta name="robots" content="' . prima_get_setting( 'indexation_admin' ) . '" />' . "\n";
}
add_action( 'wp_head', 'prima_meta_indexation');
function prima_meta_indexation() {
	if ( get_option('blog_public') == 0 ) return;
	global $wp_query;
	$indexation = '';
	if ( ( is_home() || is_front_page() ) && prima_get_setting( 'indexation_home', PRIMA_SEO_SETTINGS ) )
		$indexation = prima_get_setting( 'indexation_home', PRIMA_SEO_SETTINGS );
	elseif ( is_search()  && prima_get_setting( 'indexation_search', PRIMA_SEO_SETTINGS ) )
		$indexation = prima_get_setting( 'indexation_search', PRIMA_SEO_SETTINGS );
	elseif ( is_author()  && prima_get_setting( 'indexation_author', PRIMA_SEO_SETTINGS ) )
		$indexation = prima_get_setting( 'indexation_author', PRIMA_SEO_SETTINGS );
	elseif ( is_date()  && prima_get_setting( 'indexation_date', PRIMA_SEO_SETTINGS ) )
		$indexation = prima_get_setting( 'indexation_date', PRIMA_SEO_SETTINGS );
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
		$indexation_intax = prima_get_taxonomy_meta( $term->term_id, $taxonomy, "_prima_indexation" );
		if ( $indexation_intax ) $indexation = $indexation_intax;
		if ( !$indexation && prima_get_setting( "indexation_{$taxonomy}", PRIMA_SEO_SETTINGS ) )
			$indexation = prima_get_setting( "indexation_{$taxonomy}", PRIMA_SEO_SETTINGS );
	}
	elseif ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		$indexation_inpost = prima_get_post_meta( "_prima_indexation", $postid );
		if ( $indexation_inpost ) $indexation = $indexation_inpost;
		if ( !$indexation && prima_get_setting( "indexation_{$posttype}", PRIMA_SEO_SETTINGS ) )
			$indexation = prima_get_setting( "indexation_{$posttype}", PRIMA_SEO_SETTINGS );
	}
	if ( $indexation == 'index,follow' ) $indexation = '';
	if ( prima_get_setting( "indexation_noarchive", PRIMA_SEO_SETTINGS ) )
		$indexation = $indexation ? $indexation.',noarchive' : 'noarchive';
	if ( prima_get_setting( "indexation_noodp", PRIMA_SEO_SETTINGS ) )
		$indexation = $indexation ? $indexation.',noodp' : 'noodp';
	if ( prima_get_setting( "indexation_noydir", PRIMA_SEO_SETTINGS ) )
		$indexation = $indexation ? $indexation.',noydir' : 'noydir';
	if ( !empty( $indexation ) )
		echo '<meta name="robots" content="' . $indexation . '" />' . "\n";
}
add_action( 'login_head', 'prima_login_indexation' );
function prima_login_indexation() {
	if ( prima_get_setting( 'indexation_login', PRIMA_SEO_SETTINGS ) )
		echo '<meta name="robots" content="' . prima_get_setting( 'indexation_login', PRIMA_SEO_SETTINGS ) . '" />' . "\n";
}
