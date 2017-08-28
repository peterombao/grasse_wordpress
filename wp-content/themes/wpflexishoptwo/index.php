<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */

get_header(); ?>

<header id="header" role="banner" class="clearfix">
  <div class="margin clearfix">
	<?php prima_page_title( '<h1>', '</h1>' ); ?>
	<?php prima_page_tagline( '<p class="headertagline">', '</p>' ); ?>
  </div>
</header>

<section id="main" role="main" class="clearfix">
  <div class="margin">
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	  
	  <?php get_template_part( 'content', prima_get_setting( 'content_layout' ) ); ?>

	<?php endwhile; ?>
	
	  <?php get_template_part( 'navigation', prima_get_setting( 'content_navigation' ) ); ?>
	  
	<?php else: ?>
	
	  <?php get_template_part( 'content', '404' ); ?>
	  
	<?php endif; ?>
	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
</section>

<?php get_footer(); ?>