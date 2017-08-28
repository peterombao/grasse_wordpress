<?php
/**
 * The template for displaying all single products.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */

get_header(); ?>

<?php
$cat_id = get_categ_id($post->ID);

$cat_new = explode(',', $cat_id);

//echo $cat_id;
foreach($cat_new as $cat_id_new){

	if($cat_id_new == '16'){
		$title = "pet bottles";
		}else if($cat_id_new == '17'){
		$title ="glass bottle";
		}else if($cat_id_new == '18'){
		$title ="jars";
		}else if($cat_id_new == '19'){
		$title = "fragrances";
		}else if($cat_id == '20'){
		$title = "compounds";
	}
}
?>

<div class="margin clearfix page-title"> 

	<h2 style="margin-top: 15px; margin-bottom: 15px;"><?php echo $title; ?></h2>
	
</div>

<section id="main" role="main" class="clearfix">
  <div class="margin">
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
	  
	<?php do_action('woocommerce_before_main_content'); ?>

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		
		<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>
		
	<?php endwhile; ?>

	<?php do_action('woocommerce_after_main_content');?>

	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
</section>

<?php get_footer(); ?>