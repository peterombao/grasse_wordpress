<?php 
global $woocommerce, $woocommerce_loop, $product_category; 

$product_category = $category;

if ( empty( $woocommerce_loop['loop'] ) ) 
	$woocommerce_loop['loop'] = 0;
	
if ( empty( $woocommerce_loop['columns'] ) ) 
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
	
$woocommerce_loop['loop']++;

$product_class = $woocommerce_loop['loop']%2 ? ' odd' : ' even';
if ($woocommerce_loop['loop']%$woocommerce_loop['columns']==0) $product_class .= ' last';
if (($woocommerce_loop['loop']-1)%$woocommerce_loop['columns']==0) $product_class .= ' first';
?>
<li class="product sub-category <?php echo $product_class; ?>">

	<?php do_action('woocommerce_before_subcategory', $category); ?>

	<a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>">

		<?php do_action('woocommerce_before_subcategory_title', $category); ?>

		<h3><?php echo $category->name; ?> <?php if ($category->count>0) : ?><mark class="count">(<?php echo $category->count; ?>)</mark><?php endif; ?></h3>

		<?php do_action('woocommerce_after_subcategory_title', $category); ?>

	</a>

	<?php do_action('woocommerce_after_subcategory', $category); ?>

</li>
	