<?php
global $woocommerce, $prima_productcats_atts, $product_categories;
$atts = $prima_productcats_atts;

$prima_catalog_loop = 0;
$prima_catalog_column = $atts['columns'];

if ( trim( $atts['title'] ) ) echo '<h2 class="horizontalheading"><span>' . $atts['title'] . '</span></h2>';
?>

<ul class="products products-col-<?php echo $prima_catalog_column ?> ">

	<?php 
	foreach ($product_categories as $category) :

		$product_category_found = true;
		$product_category = $category;

		$prima_catalog_loop++;
		$product_class = $prima_catalog_loop%2 ? ' odd' : ' even';
		if ($prima_catalog_loop%$prima_catalog_column==0) $product_class .= ' last';
		if (($prima_catalog_loop-1)%$prima_catalog_column==0) $product_class .= ' first';
		?>
		<li class="product sub-category <?php echo $product_class; ?>">

			<?php if ( $atts['show_image'] == 'yes' ) : ?>
			<div class="product-image-box">
			<a href="<?php echo get_term_link($category->slug, $category->taxonomy); ?>">
			<?php
				global $woocommerce;
				$args = array();
				$img_args['width'] = $atts['image_width'];
				$img_args['height'] = $atts['image_height'];
				$img_args['crop'] = ( $atts['image_crop'] == 'yes' ? true : false );
				$image_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
				$img_args['image_id'] = ($image_id) ? $image_id : null;
				$img_args['the_post_thumbnail'] = false;
				$img_args['attachment'] = false;
				$img_args['default_image'] = THEME_URI.'/images/placeholder.png';
				$img_args['link_to_post'] = false;
				prima_image($img_args);
			?>
			</a>
			</div>
			<?php endif; ?>
			
			<?php if ( $atts['show_title'] == 'yes' ) : ?>
			<a href="<?php echo get_term_link($category->slug, $category->taxonomy); ?>">
				<h3>
				<?php echo $category->name; ?>
				<?php if ( $atts['show_count'] == 'yes' ) : ?>
					<mark class="count">(<?php echo $category->count; ?>)</mark>
				<?php endif; ?>
				</h3>
			</a>
			<?php endif; ?>

		</li><?php

	endforeach;
	?>

</ul>