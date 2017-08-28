<?php get_header(); ?>

<div class="margin clearfix page-title"> 

	<h2 style="margin-top: 15px; margin-bottom: 15px;"><?php echo get_the_title(); ?></h2>
	
</div>

<section id="main" role="main" class="clearfix">
	<div class="margin">
  
		<div class="content-wrap clearfix">
	  
			<div id="content" class="clearfix">
			  
				<?php the_content(); ?>

			</div>
			
			<?php prima_sidebar(); ?>
		
		</div>
		
		<?php prima_sidebar( 'mini' ); ?>
	
	</div>
</section>

<?php get_footer(); ?>