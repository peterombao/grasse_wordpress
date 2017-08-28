<?php

function prima_get_templates( $args = array() ) {
	$args = wp_parse_args( $args, array( 'label' => array( 'Page Template' ), 'type' => 'Template Files', 'key' => 'name' ) );
	$themes = get_themes();
	$theme = get_current_theme();
	$templates = $themes[$theme][$args['type']];
	// return $args['label'];
	$post_templates = array();
	if ( is_array( $templates ) ) {
		$base = array( trailingslashit( get_template_directory() ), trailingslashit( get_stylesheet_directory() ) );
		foreach ( $templates as $template ) {
			$basename = str_replace( $base, '', $template );
			$template_data = implode( '', file( $template ) );
			$name = '';
			foreach ( $args['label'] as $label ) {
				if ( preg_match( "|{$label}:(.*)$|mi", $template_data, $name ) ) {
					$name = _cleanup_header_comment( $name[1] );
					break;
				}
			}
			if ( !empty( $name ) ) {
				if ( $args['key'] == 'name' ) 
					$post_templates[trim( $name )] = $basename;
				elseif ( $args['key'] == 'basename' ) 
					$post_templates[$basename] = trim( $name );
			}
		}
	}
	return $post_templates;
}
