<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @package WooCommerce
 * @since WooCommerce 1.6
 * @todo prepend class names with wc-
 */
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_show_product_images hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary">
	
	
		<?php //do_action( 'woocommerce_single_product_summary'); ?>
		<?php prima_page_title('<h1 class="product-title">','</h1>'); ?>
		<div id="ev_product_price"><?php echo woocommerce_template_single_price(); ?></div>
		<?php	woocommerce_template_single_add_to_cart(); ?>
		
		<div class="ev_sharethis">
			<span class='st_sharethis_hcount' displayText='ShareThis'></span>
			<span class='st_facebook_hcount' displayText='Facebook'></span>
			<span class='st_twitter_hcount' displayText='Tweet'></span>
			<span class='st_pinterest_hcount' displayText='Pinterest'></span>			
		</div>
		<?php //woocommerce_template_single_sharing(); ?>
		<?php 	//prima_page_tagline( '<p class="headertagline">', '</p>' );
				
				woocommerce_output_product_data_tabs();
				woocommerce_template_single_meta();
		 ?>
		<!--<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>-->

	</div><!-- .summary -->
	<div class="clear"></div> <!-- end -->
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>