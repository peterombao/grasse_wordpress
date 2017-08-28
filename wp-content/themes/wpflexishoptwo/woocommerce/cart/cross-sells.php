<?php
global $woocommerce_loop, $woocommerce, $product;

$perpage = prima_get_setting('shop_crosssells_perpage');
if ( !$perpage ) $perpage = 2;
$columns = prima_get_setting('shop_crosssells_columns');
if ( !$columns ) $columns = 2;
$woocommerce_loop['columns'] = $columns;

$crosssells = $woocommerce->cart->get_cross_sells();
if (sizeof($crosssells)==0) return;
?>
<div class="cross-sells">
	<h2><?php _e('You may be interested in&hellip;', 'woocommerce') ?></h2>
	<?php
	$args = array(
		'post_type'	=> 'product',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => $perpage,
		'orderby' => 'rand',
		'post__in' => $crosssells
	);
	query_posts($args);
	woocommerce_get_template_part( 'loop', 'shop' );
	wp_reset_query();
	?>
</div>