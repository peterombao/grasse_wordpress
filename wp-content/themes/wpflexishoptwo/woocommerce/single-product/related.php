<?php
global $product, $woocommerce_loop, $posts_per_page, $orderby;

$perpage = prima_get_setting('shop_related_perpage');
if ( !$perpage ) $perpage = 4;
$columns = prima_get_setting('shop_related_columns');
if ( !$columns ) $columns = 4;
$woocommerce_loop['columns'] = $columns;

$related = $product->get_related($perpage);
if (sizeof($related)==0) return;
?>
<section id="related-products">
	<h2 class="horizontalheading"><span><?php _e('Related Products', 'woocommerce'); ?></span></h2>
	<?php
	$args = array(
		'post_type'	=> 'product',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => $perpage,
		'orderby' => $orderby,
		'post__in' => $related
	);
	$args = apply_filters('woocommerce_related_products_args', $args);
	query_posts($args);
	woocommerce_get_template_part( 'loop', 'shop' );
	wp_reset_query();
	?>
</section>