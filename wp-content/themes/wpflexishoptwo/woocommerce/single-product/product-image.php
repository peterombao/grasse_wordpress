<div class="images">

	<?php
	global $post, $woocommerce, $product_image_id;
	$product_image_id = get_post_thumbnail_id( $post->ID );
	if ( !$product_image_id ) {
		$attachments = get_children( array( 'post_type' 	=> 'attachment', 'numberposts' 	=> 1, 'post_status' => null, 'post_parent' => $post->ID, 'post_mime_type'=> 'image', 'orderby'=> 'menu_order', 'order'	=> 'ASC' ) );
		if ( !empty( $attachments ) ) { reset( $attachments ); $product_image_id = key( $attachments ); }
	}

	$args = array();
	$args['size'] = 'shop_single';
	if ( $product_image_id )
		$args['image_id'] = $product_image_id;
	$args['default_image'] = THEME_URI.'/images/placeholder.png';
	$args['link_to_image'] = true;
	$args['link_attr'] = ' itemprop="image" class="zoom" rel="thumbnails" ';
	prima_image($args);

	do_action('woocommerce_product_thumbnails');
	?>

</div>