<?php 
$slides = new WP_Query( array( 'post_type' => 'slider', 'order' => 'ASC', 'orderby' => 'menu_order' ) );

if ( $slides->have_posts() ) : ?>
	
	<div class="responsive-slider flexslider">
		
		<ul class="slides">
			
		<?php while ( $slides->have_posts() ) : $slides->the_post(); ?>

			<li>
			   
				<div id="slide-<?php echo $slides->post->ID; ?>" class="slide">
					
					<?php //global $post; ?>
							
						<?php //if ( has_post_thumbnail() ) : ?>
							<a href="<?php echo get_post_meta( $slides->post->ID, "_slide_link_url", true ); ?>" title="<?php echo $slides->post->post_title; ?>" >
								<?php the_post_thumbnail( 'full', array( 'class'	=> 'slide-thumbnail' ) ); ?>
							</a>
						<?php //endif; ?>
					
					<h2 class="slide-title" style="display:none"><a href="<?php echo get_post_meta( $slides->post->ID, "_slide_link_url"); ?>" title="<?php echo $slides->post->post_title; ?>" ><?php echo $slides->post->post_title; ?></a></h2>
				
				</div><!-- #slide-x -->
			
			</li>
			
		<?php endwhile; ?>
		
		</ul>
		
	</div><!-- #featured-content -->

<?php endif;

wp_reset_postdata();

?>