<?php global $woocommerce, $woocommerce_loop, $product_categories, $product_category_found, $product_category, $product_category_parent; ?>

<div class="product-image-box">
<a href="<?php echo get_term_link($product_category->slug, 'product_cat'); ?>">
<?php
	global $woocommerce;
	$args = array();
	$args['size'] = 'shop_catalog';
	$image_id = get_woocommerce_term_meta( $product_category->term_id, 'thumbnail_id', true );
	$args['image_id'] = ($image_id) ? $image_id : null;
	$args['the_post_thumbnail'] = false;
	$args['attachment'] = false;
	$args['default_image'] = THEME_URI.'/images/placeholder.png';
	$args['link_to_post'] = false;
	prima_image($args);
	
	$category = $product_category;
	do_action('prima_category_image_box', $category);
?>
</a>
</div>