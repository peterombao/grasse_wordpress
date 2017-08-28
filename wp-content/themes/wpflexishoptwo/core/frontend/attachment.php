<?php

remove_filter( 'the_content', 'prepend_attachment' );

function prima_nav_attachment_above() {
	if( !is_singular('attachment') ) return;
	$meta_nav_left = apply_filters('meta_nav_left', '<span class="meta-nav">&laquo;</span>');
	$meta_nav_right = apply_filters('meta_nav_right', '<span class="meta-nav">&raquo;</span>');
	global $post;
	if ( ! empty( $post->post_parent ) ) {
        echo '<div id="nav-above" class="navigation">';
		echo '<a href="'.get_permalink( $post->post_parent ).'" title="'.esc_attr( sprintf( __( 'Back to post: %s', 'primathemes' ), get_the_title( $post->post_parent ) ) ).'" rel="gallery">'.$meta_nav_left.' '.sprintf( __( 'Back to post: %s', 'primathemes' ), get_the_title( $post->post_parent ) ).'</a>';
        echo '</div><!-- #nav-above -->';
    }
}

function prima_nav_attachment_below() {
	if( !is_singular('attachment') ) return;
	$meta_nav_left = apply_filters('meta_nav_left', '<span class="meta-nav">&laquo;</span>');
	$meta_nav_right = apply_filters('meta_nav_right', '<span class="meta-nav">&raquo;</span>');
	global $post;
	if ( wp_attachment_is_image() ) {
		ob_start();
		previous_image_link();
		$prev_image = ob_get_clean();
		$prev = $prev_image ? '<div class="nav-left"><p>'.$meta_nav_left.' '.__('Previous Image','primathemes').'</p>'.$prev_image.'</div>' : '';
		ob_start();
		next_image_link();
		$next_image = ob_get_clean();
		$next = $next_image ? '<div class="nav-right"><p>'.__('Next Image','primathemes').' '.$meta_nav_right.'</p>'.$next_image.'</div>' : '';
		if ( $prev || $next ) 
			echo '<div id="nav-below" class="navigation">' . $prev . $next . '</div><!-- #nav-below -->';
	}
}

function prima_prepend_attachment() { 
	if( !is_singular('attachment') ) return;
	$file = wp_get_attachment_url();
	$mime = get_post_mime_type();
	$mime_type = explode( '/', $mime );
	foreach ( $mime_type as $type ) {
		if ( function_exists( "prima_attachment_{$type}" ) )
			echo call_user_func( "prima_attachment_{$type}" );
	}
	// echo '<a href="'.wp_get_attachment_url().'" title="'.esc_attr( get_the_title() ).'" rel="attachment">'.basename( get_permalink() ).'</a>';
    // echo wp_get_attachment_link(0, 'large', false);
}

function prima_attachment_image() {
	global $post;
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	if ( count( $attachments ) > 1 ) {
		if ( isset( $attachments[ $k ] ) )
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
		else
			$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} 
	else {
		$next_attachment_url = wp_get_attachment_url();
	}
	$image = '<div class="entry-attachment">';
	$image .= '<div class="entry-attachment-image">';
	$image .= '<a href="'.$next_attachment_url.'" title="'.esc_attr( get_the_title() ).'" rel="attachment">';
	$size = wp_embed_defaults();
	$image .= wp_get_attachment_image( $post->ID, array( $size['width'], 9999 ) ); 
	$image .= '</a>';
	$image .= '</div>';
	if ( $post->post_excerpt ) 
	$image .= '<div class="caption">'.$post->post_excerpt.'</div>';
	$image .= '</div>';
	return $image;
}
