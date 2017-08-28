<?php
global $post, $woocommerce, $product_image_id;

$args = array( 'post_type' 	=> 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post->ID, 'post_mime_type'=> 'image', 'orderby'=> 'menu_order', 'order'	=> 'ASC' );
if ( $product_image_id ) $args['post__not_in'] = array($product_image_id);

$attachments = get_children( $args );
if ( empty( $attachments ) ) return;
?>

<div class="thumbnails">
	<?php	
	$loop = 0;
	$columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
	foreach ( $attachments as $id => $attachment ) :
		if (get_post_meta($id, '_woocommerce_exclude_image', true)==1) continue;
		$loop++;
		$class = 'zoom ';
		if ($loop==1 || ($loop-1)%$columns==0) $class .= 'first';
		if ($loop%$columns==0) $class .= 'last';

		$args = array();
		$args['size'] = 'shop_thumbnail';
		$args['image_id'] = $id;
		$args['link_to_image'] = true;
		$args['link_attr'] = ' class="'.$class.'" rel="thumbnails" ';
		prima_image($args);
	endforeach;
	?>
</div>