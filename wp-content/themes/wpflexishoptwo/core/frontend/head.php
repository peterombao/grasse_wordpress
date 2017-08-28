<?php 

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

add_filter( 'wp_title', 'prima_default_title', 10, 3);
function prima_default_title( $title, $sep = '', $seplocation = '' ) {
	if ( is_home() ) $title = get_bloginfo('name');
	global $wp_query;
	$doctitle = '';
	if ( is_404() )
		$doctitle = __('404 - Not Found', 'primathemes');
	elseif ( is_search() )
		$doctitle = sprintf( __( 'Search Results for "%1$s"', 'primathemes' ), esc_attr( get_search_query() ) );
	elseif ( ( is_home() || is_front_page() ) )
		$doctitle = get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
	elseif ( is_author() )
		$doctitle = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
	elseif ( is_date() ) {
		if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
			$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'g:i a', $domain ) ) );

		elseif ( get_query_var( 'minute' ) )
			$doctitle = sprintf( __( 'Archive for minute %1$s', $domain ), get_the_time( __( 'i', $domain ) ) );

		elseif ( get_query_var( 'hour' ) )
			$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'g a', $domain ) ) );

		elseif ( is_day() )
			$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'F jS, Y', $domain ) ) );

		elseif ( get_query_var( 'w' ) )
			$doctitle = sprintf( __( 'Archive for week %1$s of %2$s', $domain ), get_the_time( __( 'W', $domain ) ), get_the_time( __( 'Y', $domain ) ) );

		elseif ( is_month() )
			$doctitle = sprintf( __( 'Archive for %1$s', $domain ), single_month_title( ' ', false) );

		elseif ( is_year() )
			$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'Y', $domain ) ) );
	}
	elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
		$post_type = get_post_type_object( get_query_var( 'post_type' ) );
		$doctitle = $post_type->labels->name;
	}
	elseif ( is_category() || is_tag() || is_tax() ) {
		$term = $wp_query->get_queried_object();
		$doctitle = $term->name;
	}
	elseif ( is_singular() ) {
		$post_id = $wp_query->get_queried_object_id();
		$doctitle = get_post_field( 'post_title', $post_id );
	}
	if (get_query_var('paged')) {
		$doctitle .= ' - ' . __( 'Page ' , 'primathemes') . get_query_var('paged');
	}
	$doctitle = esc_attr($doctitle);
	// $doctitle = htmlentities(stripslashes($doctitle));
	if ($doctitle) return $doctitle;
	else return $title;
}

add_action( 'wp_head', 'prima_meta_content_type', 5);
function prima_meta_content_type() {
	echo '<meta http-equiv="Content-Type" content="' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) . '" />' . "\n";
}

add_action( 'wp_head', 'prima_head_title');
function prima_head_title() {
	echo '<title>';	wp_title(''); echo '</title>' . "\n";
}

// add_action( 'wp_head', 'prima_meta_template');
function prima_meta_template() {
	global $prima_child_data;
	$data = $prima_child_data;
	echo '<meta name="template" content="' . esc_attr( "{$data['Title']} {$data['Version']}" ) . '" />' . "\n";
}

// add_action( 'wp_head', 'prima_meta_author');
function prima_meta_author() {
	if ( !is_singular() ) return;
	global $wp_query;
	if ( $author = get_the_author_meta( 'display_name', $wp_query->post->post_author ) )
		echo '<meta name="author" content="' . esc_attr( $author ) . '" />' . "\n";
}

// add_action( 'wp_head', 'prima_meta_copyright');
function prima_meta_copyright() {
	if ( is_singular() ) $date = get_the_time( __( 'F Y', 'primathemes' ) );
	else $date = date( __( 'Y', 'primathemes' ) );
	echo '<meta name="copyright" content="' . sprintf( esc_attr__( 'Copyright (c) %1$s', 'primathemes' ), $date ) . '" />' . "\n";
}

// add_action( 'wp_head', 'prima_meta_revised');
function prima_meta_revised() {
	if ( !is_singular() ) return;
	echo '<meta name="revised" content="' . get_the_modified_time( esc_attr__( 'l, F jS, Y, g:i a', 'primathemes' ) ) . '" />' . "\n";
}

add_action( 'wp_head', 'prima_head_pingback');
function prima_head_pingback() {
	echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . "\n";
}

add_action( 'get_header', 'prima_scripts', 100);
function prima_scripts() {
	if (is_singular() && get_option('thread_comments') && comments_open())
		wp_enqueue_script('comment-reply');
	if(file_exists(PRIMA_DIR.'/js/primathemes.js'))
		wp_enqueue_script('primathemes', PRIMA_URI.'/js/primathemes.js', array('jquery'), '0.1', true);
}

add_action( 'wp_head', 'prima_fonts_output');
function prima_fonts_output() {
	if ( !is_ssl() ) 
		echo get_option( PRIMA_DESIGN_SETTINGS.'_fonts' );
	else 
		echo str_replace("http://", "https://", get_option( PRIMA_DESIGN_SETTINGS.'_fonts' ));
}

add_action( 'wp_head', 'prima_custom_styles', 101);
function prima_custom_styles() {
	echo '<style type="text/css" media="screen">'."\n";
	echo '/* Custom CSS Output */ ';
	do_action('prima_styles');
	echo "\n".'</style>'."\n";
}

add_action( 'prima_styles', 'prima_custom_css_style', 101 );
function prima_custom_css_style() {
	prima_setting( 'custom_css', PRIMA_DESIGN_SETTINGS );
}

/* TO DO: Simplify this options */
add_action( 'prima_styles', 'prima_design_styles' );
function prima_design_styles() {
	if ( !is_ssl() ) 
		echo get_option( PRIMA_DESIGN_SETTINGS.'_output' );
	else 
		echo str_replace("http://", "https://", get_option( PRIMA_DESIGN_SETTINGS.'_output' ));
}

add_filter( 'body_class', 'prima_body_class' );
function prima_body_class( $classes ) {
	global $wp_query;
	// $classes[] = get_bloginfo( 'text_direction' );
	$classes[] = get_locale();
	if ( is_singular() ) {
		$template = str_replace( array ( "{$wp_query->post->post_type}-", "{$wp_query->post->post_type}-template-", '.php' ), '', get_post_meta( $wp_query->post->ID, "_wp_{$wp_query->post->post_type}_template", true ) );
		if ( $template ) {
			//$template = str_replace(  ), '', $template );
			if( !is_page() ) $classes[] = "{$wp_query->post->post_type}-template-{$template}";
		}
		$classes[] = ( ( comments_open() ) ? 'comments-open' : 'comments-closed' );
		if ( is_attachment() ) {
			foreach ( explode( '/', get_post_mime_type() ) as $type )
				$classes[] = "post-attachment-{$type}";
		}
	}
	if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 ) {
		$page = intval( $page );
		$classes[] = 'paged paged-' . $page;
	}
	return $classes;
}
