<?php
global $woocommerce_loop, $woocommerce, $product;

$perpage = prima_get_setting('shop_upsells_perpage');
if ( !$perpage ) $perpage = 4;
$columns = prima_get_setting('shop_upsells_columns');
if ( !$columns ) $columns = 4;
$woocommerce_loop['columns'] = $columns;

$upsells = $product->get_upsells();
if (sizeof($upsells)==0) return;
?>
<section id="upsell-products">
	<h2 class="horizontalheading"><span><?php _e('You may also like&hellip;', 'woocommerce') ?></span></h2>
	<?php
	$args = array(
		'post_type'	=> 'product',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => $perpage,
		'orderby' => 'rand',
		'post__in' => $upsells
	);
	query_posts($args);
	woocommerce_get_template_part( 'loop', 'shop' );
	wp_reset_query();
	?>
</section>