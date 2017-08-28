<?php
/**
 * The template for displaying Product Archive pages.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */

get_header(); ?>

<div class="margin clearfix page-title"> 
			
	<?php 
		if (!is_search()) :
			$shop_page = get_post( woocommerce_get_page_id('shop') );
			$shop_page_title = apply_filters('the_title', (get_option('woocommerce_shop_page_title')) ? get_option('woocommerce_shop_page_title') : $shop_page->post_title);
			$shop_page_content = $shop_page->post_content;
		else :
			$shop_page_title = __('Search Results:', 'woocommerce') . ' &ldquo;' . get_search_query() . '&rdquo;'; 
			if (get_query_var('paged')) $shop_page_title .= ' &mdash; ' . __('Page', 'woocommerce') . ' ' . get_query_var('paged');
			$shop_page_content = '';
		endif;
	?>
	
	<h1><?php echo $shop_page_title ?></h1>
	
	<?php prima_setting( 'shop_tagline', null, '<p class="headertagline">%setting%</p>' ); ?>
	
</div>
<section id="main" role="main" class="clearfix" style="margin-bottom:20px;">
  <div class="margin">
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
	  
	  <?php do_action('woocommerce_before_main_content'); ?>
		
		<?php echo apply_filters('the_content', $shop_page_content); ?>

		<?php woocommerce_get_template_part( 'loop', 'shop' ); ?>
		
		<?php do_action('woocommerce_pagination'); ?>

	  <?php do_action('woocommerce_after_main_content'); ?>

	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
</section>

<?php get_footer(); ?>