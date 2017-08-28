<?php
global $product, $woocommerce_loop;

if ( $woocommerce_loop['show_products'] === false )
	return; 

if ( empty( $woocommerce_loop['loop'] ) ) 
	$woocommerce_loop['loop'] = 0;

if ( !isset($woocommerce_loop['columns']) || !$woocommerce_loop['columns'] ) 
	$woocommerce_loop['columns'] = apply_filters('loop_shop_columns', 4);

if ( ! $product->is_visible() ) 
	return; 

$woocommerce_loop['loop']++;

$product_class = $woocommerce_loop['loop']%2 ? ' odd' : ' even';
if ($woocommerce_loop['loop']%$woocommerce_loop['columns']==0) $product_class .= ' last';
if (($woocommerce_loop['loop']-1)%$woocommerce_loop['columns']==0) $product_class .= ' first';
?>
		
<li class="product<?php echo $product_class; ?>">
	
	<?php do_action('woocommerce_before_shop_loop_item'); ?>
	
	<?php if ( !prima_get_setting('shop_title_hide') ) : ?>
	<a href="<?php the_permalink(); ?>">
		<?php do_action('woocommerce_before_shop_loop_item_title'); ?>
		<h3><?php the_title(); ?></h3>
		<?php do_action('woocommerce_after_shop_loop_item_title'); ?>
	</a>
	<?php endif; ?>

	<?php do_action('woocommerce_after_shop_loop_item'); ?>
	
</li>

