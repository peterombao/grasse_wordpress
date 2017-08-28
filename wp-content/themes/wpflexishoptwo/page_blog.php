<?php
/**
 * Template Name: Blog
 * Description: A Page Template that show custom Blog page
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */

global $wp_query, $current_page_id, $more;
$orig_query = $wp_query;
$current_page_id = $wp_query->get_queried_object_id();

$content_layout = prima_get_post_meta( 'content_layout', $current_page_id );
if ( !$content_layout ) $content_layout = prima_get_setting( 'content_layout' );

$content_navigation = prima_get_post_meta( 'content_navigation', $current_page_id );
if ( !$content_navigation ) $content_layout = prima_get_setting( 'content_navigation' );

$postsperpage = prima_get_post_meta( 'postsperpage', $current_page_id );
if (!$postsperpage) $postsperpage = get_option( 'posts_per_page' );

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
	
	<?php 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array( 
		'post_type' => array('post'), 
		'posts_per_page' => $postsperpage, 
		'paged' => $paged 
	);
	query_posts( $args );
	$more = 0;
	?>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	  
	  <?php get_template_part( 'content', $content_layout ); ?>

	<?php endwhile; ?>
	
	  <?php get_template_part( 'navigation', $content_navigation ); ?>
	  
	<?php else: ?>
	
	  <?php get_template_part( 'content', '404' ); ?>
	  
	<?php endif; ?>
	
	<?php wp_reset_query(); $orig_query = $wp_query; ?>
	
	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
</section>

<?php get_footer(); ?>