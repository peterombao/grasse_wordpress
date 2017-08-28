<?php
/*
This page is a modified version of Get The Image plugin by Justin Tadlock http://justintadlock.com
*/

/* Adds theme support for post images. */
add_theme_support( 'post-thumbnails' );

function prima_image( $args = array() ) {
	echo prima_get_image( $args );
}

function prima_get_image( $args = array() ) {
	global $post;
	$defaults = array(
		'image_id' => false,
		'parent_id' => false,
		'post_id' => false,
		'meta_key' => false,
		'the_post_thumbnail' => true, // WP 2.9+ image function
		'attachment' => true,
		'image_scan' => false,
		'default_image' => false,
		'order_of_image' => 1,
		'link_to_parent' => false,
		'link_to_post' => false,
		'link_to_image' => false,
		'link_to_meta' => false,
		'link_to' => false,
		'link_attr' => false,
		'size' => false,
		'width' => false,
		'height' => false,
		'crop' => true,
		'image_class' => false,
		'output' => 'image',
		'meta_key_save' => false,
		'before' => '',
		'after' => '',
		'callback' => null,
	);
	if ( is_object( $post ) ) 
		$defaults['post_id'] = $post->ID;
	$args = apply_filters( 'prima_image_args', $args );
	$args = wp_parse_args( $args, $defaults );
	/* Check size. */
	if (!$args['size']) {
		if (!$args['width'] && !$args['height']) {
			$args['size'] = 'full';
		}
		else {
			$args['size'] = (int)$args['width'].'x'.(int)$args['height'];
		}
	}
	else {
		if ($args['size'] == 'thumbnail') {
			$args['width'] = get_option('thumbnail_size_w');
			$args['height'] = get_option('thumbnail_size_h');
		}
		elseif ($args['size'] == 'medium') {
			$args['width'] = get_option('medium_size_w');
			$args['height'] = get_option('medium_size_h');
		}
		elseif ($args['size'] == 'large') {
			$args['width'] = get_option('large_size_w');
			$args['height'] = get_option('large_size_h');
		}
		else {
			global $_wp_additional_image_sizes;
			if ( isset($_wp_additional_image_sizes[$args['size']]) ) {
				$args['width'] = $_wp_additional_image_sizes[$args['size']]['width'];
				$args['height'] = $_wp_additional_image_sizes[$args['size']]['height'];
				$args['crop'] = $_wp_additional_image_sizes[$args['size']]['crop'];
			}
		}
	}
	if ( !empty( $args['image_id'] ) )
		$args['post_id'] = $args['image_id'];
	/* Extract the array to allow easy use of variables. */
	extract( $args );
	/* If it is an image attachment. */
	if ( !empty( $image_id ) )
		$image = image_by_image_id( $args );
	/* If a custom field key (array) is defined, check for images by custom field. */
	if ( empty( $image ) && !empty( $meta_key ) )
		$image = image_by_custom_field( $args );
	/* If no image found and $the_post_thumbnail is set to true, check for a post image (WP feature). */
	if ( empty( $image ) && !empty( $the_post_thumbnail ) )
		$image = image_by_the_post_thumbnail( $args );
	/* If no image found and $attachment is set to true, check for an image by attachment. */
	if ( empty( $image ) && !empty( $attachment ) )
		$image = image_by_attachment( $args );
	/* If no image found and $image_scan is set to true, scan the post for images. */
	if ( empty( $image ) && !empty( $image_scan ) )
		$image = image_by_scan( $args );
	/* If no image found and a callback function was given. */
	if ( empty( $image ) && !is_null( $callback ) && function_exists( $callback ) )
		$image = call_user_func( $callback, $args );
	/* If no image found and a $default_image is set, get the default image. */
	if ( empty( $image ) && !empty( $default_image ) )
		$image = image_by_default( $args );
	/* If $meta_key_save was set, save the image to a custom field. */
	if ( !empty( $image ) && !empty( $meta_key_save ) )
		prima_image_meta_key_save( $args, $image );
	if ( !empty( $image ) ) {
		if ( isset ( $image['post_thumbnail_id'] ) ) {
			$post_thumbnail_id = $image['post_thumbnail_id'];
		}
	}
	if ( $args['output'] == 'image' ) { 
		if ($image) {
			$image = display_the_image( $args, $image );
			$image = $args['before'].$image.$args['after'];
			/* Allow plugins/theme to override the final output. */
			$image = apply_filters( 'prima_image', $image );
			return $image;
		}
		else return false;
	}
	elseif ( $args['output'] == 'url' ) { 
		if ($image)	return $image['url'];
		else return false;
	}
}

function image_by_image_id( $args = array() ) {
	if ( 'attachment' != get_post_type( $args['image_id'] ) )
		return false;
	if ( !$args['width'] && $args['height'] ) $args['width'] = 1024;
	if ( $args['width'] && !$args['height'] ) $args['height'] = 1024;
	$alt = get_post_meta( $args['image_id'], '_wp_attachment_image_alt', true );
	if(!$alt) $alt = get_post_field( 'post_title', $args['image_id'] );
	if ( function_exists("gd_info") && $args['size'] != 'full' ) {
		$vt_image = vt_resize( $args['image_id'], '', $args['width'], $args['height'], $args['crop'] );
		if ($vt_image) {
			if ( $args['link_to_image'] ) {
				$std_image = wp_get_attachment_image_src( $args['image_id'], 'full');
				$vt_image['full'] = $std_image[0];
			}
			$vt_image['alt'] = $alt;
			return $vt_image;
		}
		else
			return false;
	}
	$std_image = wp_get_attachment_image_src( $args['image_id'], 'full');
	if ($std_image) 
		return array( 'url' => $std_image[0], 'full' => $std_image[0], 'width' => $std_image[1], 'height' => $std_image[2], 'alt' => $alt );
	else
		return false;
}

function image_by_custom_field( $args = array() ) {
	if ( !is_array( $args['meta_key'] ) ) {
		$image = get_post_meta( $args['post_id'], $args['meta_key'], true );
	}
	elseif ( is_array( $args['meta_key'] ) ) {
		foreach ( $args['meta_key'] as $meta_key ) {
			$image = get_post_meta( $args['post_id'], $meta_key, true );
			if ( !empty( $image ) )
				break;
		}
	}
	if ( !empty( $image ) )
		return array( 'url' => $image, 'full' => $image );
	return false;
}

function image_by_the_post_thumbnail( $args = array() ) {
	$post_thumbnail_id = get_post_thumbnail_id( $args['post_id'] );
	if ( empty( $post_thumbnail_id ) )
		return false;
	if ( !$args['width'] && $args['height'] ) $args['width'] = 1024;
	if ( $args['width'] && !$args['height'] ) $args['height'] = 1024;
	$alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
	if(!$alt) $alt = get_post_field( 'post_title', $post_thumbnail_id );
	if ( function_exists("gd_info") && $args['size'] != 'full' ) {
		$vt_image = vt_resize( $post_thumbnail_id, '', $args['width'], $args['height'], $args['crop'] );
		if ($vt_image) {
			if ( $args['link_to_image'] ) {
				$std_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
				$vt_image['full'] = $std_image[0];
			}
			$vt_image['alt'] = $alt;
			return $vt_image;
		}
		else
			return false;
	}
	$std_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
	if ($std_image) 
		return array( 'url' => $std_image[0], 'full' => $std_image[0], 'width' => $std_image[1], 'height' => $std_image[2], 'alt' => $alt );
	else
		return false;
}

function image_by_attachment( $args = array() ) {
	$attachments = get_children( array( 'post_parent' => $args['post_id'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) );
	if ( empty( $attachments ) ) {
		if ( 'attachment' == get_post_type( $args['post_id'] ) ) {
			$post_thumbnail_id = $args['post_id'];
		}
	}
	else {
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			if ( ++$i == $args['order_of_image'] ) {
				$post_thumbnail_id = $id;
				break;
			}
		}
	}
	if ( empty( $post_thumbnail_id ) )
		return false;
	if ( !$args['width'] && $args['height'] ) $args['width'] = 1024;
	if ( $args['width'] && !$args['height'] ) $args['height'] = 1024;
	$alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
	if(!$alt) $alt = get_post_field( 'post_title', $post_thumbnail_id );
	if ( function_exists("gd_info") && $args['size'] != 'full' ) {
		$vt_image = vt_resize( $post_thumbnail_id, '', $args['width'], $args['height'], $args['crop'] );
		if ($vt_image) {
			if ( $args['link_to_image'] ) {
				$std_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
				$vt_image['full'] = $std_image[0];
			}
			$vt_image['alt'] = $alt;
			return $vt_image;
		}
		else
			return false;
	}
	$std_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
	if ($std_image) 
		return array( 'url' => $std_image[0], 'full' => $std_image[0], 'width' => $std_image[1], 'height' => $std_image[2], 'alt' => $alt );
	else
		return false;
}

function image_by_scan( $args = array() ) {
	preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );
	// if ( isset( $matches ) && $matches[1][0] )
	if ( isset( $matches ) && $matches[1] )
		return array( 'url' => $matches[1][0], 'full' => $matches[1][0] );
	return false;
}

function image_by_default( $args = array() ) {
	$output = array();
	$output['url'] = $args['default_image'];
	$output['full'] = $args['default_image'];
	if ( isset($args['width']) && $args['width'] )
		$output['width'] = $args['width'];
	if ( isset($args['height']) && $args['height'] )
		$output['height'] = $args['height'];
	return $output;
}

function display_the_image( $args = array(), $image = false ) {
	if ( empty( $image['url'] ) )
		return false;
	extract( $args );
	$image_alt = ( ( !empty( $image['alt'] ) ) ? $image['alt'] : apply_filters( 'the_title', get_post_field( 'post_title', $post_id ) ) );
	$width = ( isset($image['width']) && $image['width'] ? ' width="' . intval( $image['width'] ) . '" ' : '' );
	$height = ( isset($image['height']) && $image['height'] ? ' height="' . intval( $image['height'] ) . '" ' : '' );
	if ( is_array( $meta_key ) ) {
		foreach ( $meta_key as $key )
			$classes[] = str_replace( ' ', '-', strtolower( $key ) );
	}
	$classes[] = 'attachment-'.$size;
	$classes[] = $image_class;
	$class = join( ' ', array_unique( $classes ) );
	if ( !empty( $image['post_thumbnail_id'] ) )
		do_action( 'begin_fetch_post_thumbnail_html', $post_id, $image['post_thumbnail_id'], $size );
	$html = '<img src="' . $image['url'] . '" alt="' . esc_attr( strip_tags( $image_alt ) ) . '" class="' . esc_attr( $class ) . '" '.$width.$height.'/>';
	if ( $link_to_parent ) {
		if ( $image_id && $parent_id )
			$html = '<a '.$link_attr.' href="' . get_permalink( $parent_id ) . '" title="' . $image_alt . '">' . $html . '</a>';
	}
	elseif ( $link_to_post ) {
		$html = '<a '.$link_attr.' href="' . get_permalink( $post_id ) . '" title="' . $image_alt . '">' . $html . '</a>';
	}
	elseif ( $link_to_image ) {
		if ( isset($image['full']) && $image['full'] )
			$html = '<a '.$link_attr.' href="' . $image['full'] . '" title="' . $image_alt . '">' . $html . '</a>';
	}
	elseif ( $link_to_meta ) {
		if ( $metaurl = get_post_meta( $image_id, $link_to_meta, true ) ) {
			$html = '<a '.$link_attr.' href="' . $metaurl . '" title="' . $image_alt . '">' . $html . '</a>';
		}
	}
	elseif ( $link_to ) {
		$html = '<a '.$link_attr.' href="' . $link_to . '" title="' . $image_alt . '">' . $html . '</a>';
	}
	if ( !empty( $image['post_thumbnail_id'] ) )
		do_action( 'end_fetch_post_thumbnail_html', $post_id, $image['post_thumbnail_id'], $size );
	if ( !empty( $image['post_thumbnail_id'] ) )
		$html = apply_filters( 'post_thumbnail_html', $html, $post_id, $image['post_thumbnail_id'], $size, '' );
	return $html;
}

function prima_image_meta_key_save( $args = array(), $image = array() ) {
	if ( empty( $args['meta_key_save'] ) || empty( $image['url'] ) )
		return;
	$meta = get_post_meta( $args['post_id'], $args['meta_key_save'], true );
	if ( empty( $meta ) )
		add_post_meta( $args['post_id'], $args['meta_key_save'], $image['url'] );
	elseif ( $meta !== $image['url'] )
		update_post_meta( $args['post_id'], $args['meta_key_save'], $image['url'], $meta );
}

function prima_gallery( $args = array() ) {
	echo prima_get_gallery( $args );
}

function prima_get_gallery( $args = array() ) {
	global $post;
	$defaults = array(
		'post_id' => $post->ID,
		'parent_id' => $post->ID,
		'size' => false,
		'width' => false,
		'height' => false,
		'crop' => true,
		'image_class' => false,
		'meta_key' => array( 'Thumbnail', 'thumbnail' ), /* only for compatibility purpose */
		'link_to_parent' => false,
		'link_to_post' => false,
		'link_to_image' => false,
		'link_to_meta' => false,
		'link_to' => false,
		'link_attr' => false,
		'before' => '',
		'after' => '',
		'before_container' => '',
		'after_container' => '',
		'callback' => null,
	);
	$args = apply_filters( 'prima_gallery_args', $args );
	$args = wp_parse_args( $args, $defaults );
	/* Check size. */
	if (!$args['size']) {
		if (!$args['width'] && !$args['height']) {
			$args['size'] = 'full';
		}
		else {
			$args['size'] = (int)$args['width'].'x'.(int)$args['height'];
		}
	}
	else {
		if ($args['size'] == 'thumbnail') {
			$args['width'] = get_option('thumbnail_size_w');
			$args['height'] = get_option('thumbnail_size_h');
		}
		elseif ($args['size'] == 'medium') {
			$args['width'] = get_option('medium_size_w');
			$args['height'] = get_option('medium_size_h');
		}
		elseif ($args['size'] == 'large') {
			$args['width'] = get_option('large_size_w');
			$args['height'] = get_option('large_size_h');
		}
		else {
			global $_wp_additional_image_sizes;
			if ( isset($_wp_additional_image_sizes[$args['size']]) ) {
				$args['width'] = $_wp_additional_image_sizes[$args['size']]['width'];
				$args['height'] = $_wp_additional_image_sizes[$args['size']]['height'];
				$args['crop'] = $_wp_additional_image_sizes[$args['size']]['crop'];
			}
		}
	}
	/* Extract the array to allow easy use of variables. */
	extract( $args );
	$images = '';
	$post_thumbnail_id = get_post_thumbnail_id( $args['post_id'] );
	if ( $post_thumbnail_id ) {
		$image_args = $args;
		$image_args['post_id']= $post_thumbnail_id;
		$image_args['image_id']= $post_thumbnail_id;
		if ( $args['link_to_parent'] ) $image_args['parent_id']= $args['post_id'];
		$image = image_by_image_id( $image_args );
		if ($image) {
			$image = display_the_image( $image_args, $image );
			$image = $args['before'].$image.$args['after'];
			$images .= $image;
		}
	}
	$attachments = get_children( array( 'post_parent' => $args['post_id'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	if ( !empty($attachments) ) {
		foreach ( $attachments as $id => $attachment ) {
			if ( $post_thumbnail_id && ( $id == $post_thumbnail_id ) )
				continue;
			$image_args = $args;
			$image_args['post_id']= $id;
			$image_args['image_id']= $id;
			if ( $args['link_to_parent'] ) $image_args['parent_id']= $args['post_id'];
			$image = image_by_image_id( $image_args );
			if ($image) {
				$image = display_the_image( $image_args, $image );
				$image = $args['before'].$image.$args['after'];
				$images .= $image;
			}
		}
	}
	if ($images) {
		$images = $args['before_container'].$images.$args['after_container'];
		/* Allow plugins/theme to override the final output. */
		$images = apply_filters( 'prima_gallery', $images );
		return $images;
	}
	else return false;
}

/*-----------------------------------------------------------------------------------*/
/* vt_resize - Resize images dynamically using wp built in functions
/*-----------------------------------------------------------------------------------*/
/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * php 5.2+
 *
 * Exemplo de uso:
 * 
 * <?php 
 * $thumb = get_post_thumbnail_id(); 
 * $image = vt_resize( $thumb, '', 140, 110, true );
 * ?>
 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
 */
if ( !function_exists('vt_resize') ) {
	function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	
		// this is an attachment, so we have the ID
		if ( $attach_id ) {
		
			$image_src = wp_get_attachment_image_src( $attach_id, "full" );
			$file_path = get_attached_file( $attach_id );
		
		// this is not an attachment, let's use the image url
		} else if ( $img_url ) {
			
			$file_path = parse_url( $img_url );
			$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
			
			//$file_path = ltrim( $file_path['path'], '/' );
			//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
			
			$orig_size = getimagesize( $file_path );
			
			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}
		
		$file_info = pathinfo( $file_path );
	
		// check if file exists
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		if ( !file_exists($base_file) ) {
			$vt_image = array (
				'url' => $image_src[0],
				'width' => $image_src[1],
				'height' => $image_src[2]
			);
			return $vt_image;
		}

		$extension = '.'. $file_info['extension'];
	
		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
		
		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width || $image_src[2] > $height ) {

			if ( $crop == true ) {
			
				$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
				
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
		
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					
					$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
					);
					
					return $vt_image;
				}
			}
			elseif ( $crop == false ) {
			
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
	
				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
				
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
	
					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);
					
					return $vt_image;
				}
			}
	
			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];
			if ( $img_size[1] <= $height ) $height = $img_size[1];
	
			// no cache files - let's finally resize it
			$new_img_path = image_resize( $file_path, $width, $height, $crop );
			if ( !is_wp_error( $new_img_path ) ) { 
			
				$new_img_size = getimagesize( $new_img_path );
				$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
		
				// resized output
				$vt_image = array (
					'url' => $new_img,
					'width' => $new_img_size[0],
					'height' => $new_img_size[1]
				);
				
				return $vt_image;
			}
		}
	
		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $image_src[1],
			'height' => $image_src[2]
		);
		
		return $vt_image;
	}
}
