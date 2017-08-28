<?php
/**
 * The template for displaying all pages.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */
 /* Template name: news */
 

get_header(); ?>

<div class="margin clearfix page-title"> 
		
	<?php prima_page_title( '<h1>', '</h1>' ); ?>
	<?php prima_page_tagline( '<p class="headertagline">', '</p>' ); ?>
	
  </div>
<section id="main" role="main" class="clearfix">
  <div class="margin">
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
	<ul class="bjpost" style="list-style:none;">
		<?php $the_query = new WP_Query( 'showposts=5' ); ?>
		<?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
			<li class="news-entry">
				<a style="font-family:'helvetica', sans-serif; font-size:28px; font-weight:bold; color:#333333;" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
				<span  style="font-family:'helvetica', sans-serif; font-size:13px;color:#BABABA;" class="entry-date">Posted on <?php the_date(); ?></span>
				<a href="<?php the_permalink() ?>"><?php the_post_thumbnail(); ?></a>
				<span style="font-family:'helvetica', sans-serif; font-size:13px;color:#555555;"><?php the_excerpt(__('(more…)')); ?></span>
			</li>
		<?php endwhile;?>
	</ul>
	  
	<?php 
		$wp_query = null; 
		$wp_query = $temp;
	?>
	
	</div>
	
	<?php prima_sidebar(); ?>
	
	</div>
	
	<?php prima_sidebar( 'mini' ); ?>
	
  </div>
  
</section>

<?php get_footer(); ?>