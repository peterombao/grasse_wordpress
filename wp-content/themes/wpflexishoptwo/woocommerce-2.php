<?php
/**
 * The template for displaying all pages.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */

global $wp_query;
$term = get_term_by( 'slug', get_query_var($wp_query->query_vars['taxonomy']), $wp_query->query_vars['taxonomy']);
 
get_header(); ?>

<header id="header" role="banner" class="clearfix">

  <div id="headerTitle" class="margin clearfix <?php if ( is_tax('product_cat') && prima_get_taxonomy_meta( $term->term_id, 'product_cat', '_prima_header_hide' ) ) echo 'headertitlehide'; ?>">adada
	
	<?php prima_productsearch( 'headersearchform' ); ?>
	
	<?php prima_producttax_image( array( 
			'term_id' => $term->term_id, 
			'width' => 82, 
			'height' => 82, 
			'image_class' => 'headerthumb', 
	) ); ?>
	
	<?php prima_page_title( '<h1>', '</h1>' ); ?>
	<?php prima_page_tagline( '<p class="headertagline">', '</p>' ); ?>

  </div>
  
  <?php 
    if ( is_tax('product_cat' ) && !prima_get_taxonomy_meta( $term->term_id, 'product_cat', '_prima_header_hide' ) ) : 
	  $subcategories = wp_list_categories( array( 'taxonomy' => 'product_cat', 
		'title_li' => '', 'show_count' => 1, 'hide_empty' => 1, 'echo' => false,
		'show_option_none' => '', 'depth' => 1, 'child_of' => $term->term_id ) );
	  if ( $subcategories ) : 
  ?>
  <div id="headersubcategories" class="clearfix">
    <ul>
	  <li class="title"><h5><?php _e( 'Sub Categories:', 'primathemes' ); ?> </h5></li>
	  <?php echo $subcategories; ?>
	</ul>
  </div>
  <?php
      endif;
    endif;
  ?>

  <?php if (is_tax('product_cat')) prima_customheader($term->term_id,'taxonomy','product_cat'); ?>
  
</header>

<section id="main" role="main" class="clearfix">
  <div class="margin">
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
		<?php woocommerce_content(); ?>
	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
</section>

<?php get_footer(); ?>