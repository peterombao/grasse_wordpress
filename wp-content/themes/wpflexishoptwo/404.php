<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */

get_header(); ?>

<header id="header" role="banner">
  <div class="margin clearfix">
	<?php prima_page_title( '<h1>', '</h1>' ); ?>
	<?php prima_page_tagline( '<p class="headertagline">', '</p>' ); ?>
  </div>
</header>

<section id="main" role="main">
  <div class="margin">
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
	  <?php get_template_part( 'content', '404' ); ?>
	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
</section>

<?php get_footer(); ?>