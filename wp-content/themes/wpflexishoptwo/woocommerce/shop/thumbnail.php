<div class="product-image-box">
<a href="<?php the_permalink() ?>">
<?php
	global $woocommerce;
	$args = array();
	$args['size'] = 'shop_catalog';
	$args['default_image'] = THEME_URI.'/images/placeholder.png';
	$args['image_scan'] = false;
	$args['link_to_post'] = false;
	prima_image($args);
	do_action('prima_product_image_box'); 
?>
</a>
</div>
