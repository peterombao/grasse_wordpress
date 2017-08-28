<?php 
/*
	Template Name: Contact
*/

wp_enqueue_script('google-map-api',  'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false',array( 'jquery' ), '3.0', true);
add_action('wp_footer', 'ev_google_map', 100);
get_header(); ?>

<div class="margin clearfix page-title" style="margin-bottom:0px">
	<h1><?php echo get_the_title(); ?></h1>
</div>

<div id="map-canvas"></div>

<section id="main" role="main" class="clearfix">
	<div class="margin">
		<div class="content-wrap clearfix">
  
			<div id="content" class="clearfix bj-page">
		  
				<article id="post-<?php echo $post->ID; ?>" class="post-<?php echo $post->ID; ?> page type-page status-publish hentry instock">
					<div class="entry">

						<div class="postcontent">
							<?php echo do_shortcode(get_the_content());?>
						</div>
						
					</div>
				</article>
				
			</div>
			<?php prima_sidebar(); ?>
		</div>
	</div>
</section>


<?php get_footer(); ?>
