<?php

global $woocommerce_loop;

$woocommerce_loop['loop'] = 0;
$woocommerce_loop['show_products'] = true;



if (!isset($woocommerce_loop['columns']) || !$woocommerce_loop['columns']) $woocommerce_loop['columns'] = apply_filters('loop_shop_columns', 4);

?>

<?php //do_action('woocommerce_before_shop_loop'); ?>

<?php if (have_posts()) : ?>

<ul class="products">

	<?php 
	
	if (have_posts()) : while (have_posts()) : the_post(); 
	
		global $product;
		
		
		if (!$product->is_visible()) continue; 
		
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
			
		</li><?php 
		
	endwhile; endif;
	
	?>

</ul>

<?php else : ?>

	<?php echo '<p class="info">'.__('No products found which match your selection.', 'primathemes').'</p>'; ?>

<?php endif; ?>

<?php do_action('woocommerce_after_shop_loop'); ?>