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
$ev_post_id = get_the_ID();
$ev_is_single = is_single($ev_post_id);
get_header(); ?>

<div class="margin clearfix page-title">
	<h1 class="<?php if ( is_tax('product_cat') && prima_get_taxonomy_meta( $term->term_id, 'product_cat', '_prima_header_hide' ) ) echo 'headertitlehide'; ?>">

		<?php prima_producttax_image( array( 
				'term_id' => $term->term_id, 
				'width' => 82, 
				'height' => 82, 
				'image_class' => 'headerthumb', 
		) ); ?>
		<?php 
			if ( is_tax('product_cat')){
				prima_page_title( '', '' ); 
			}else{
				$product_term = wp_get_post_terms( $post->ID, 'product_cat');
				if(isset($product_term[0]->name)){
					echo ucwords(strtolower($product_term[0]->name));
				}else{
					prima_page_title( '', '' ); 
				}
			}
		?>

	</h1>

</div>
  <?php if (is_tax('product_cat')) prima_customheader($term->term_id,'taxonomy','product_cat'); ?>
  


<section id="main" role="main" class="clearfix">
  <div class="margin">
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
		<?php if(is_tax('product_cat')): ?>
			<?php require_once( locate_template( array('product_taxonomy.php') ) ); ?>
		<?php else: ?>
			<?php woocommerce_content(); ?>
			
		<?php endif; ?>
	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
</section>

<?php 
if($ev_is_single){

	$post_terms = wp_get_post_terms( $ev_post_id, 'product_cat'); 
	
	if(count($post_terms) > 0){
		$terms_id = array();
		foreach($post_terms as $post_term_key => $post_term_val){
			$terms_id[] = $post_term_val->term_id;
		}

		$tax_query[] =array('taxonomy' => 'product_cat', 'terms' =>$post_terms[0]->slug, 'field' => 'slug');
		
		$related_args = array(
			'post_type' => 'product',
			'tax_query' => $tax_query,
			'post_status' => 'publish',
			'post__not_in' => array($ev_post_id),
			'posts_per_page' => 5
		);		
		
		query_posts($related_args);
		
		echo "<div class='related_products'>";
			echo '<h2 class="header2">related products</h2>';
			get_template_part( 'ev_filter_loop', 'ev_filter_loop' ); 
		echo "</div>";
	}
}
?>

<?php get_footer(); ?>