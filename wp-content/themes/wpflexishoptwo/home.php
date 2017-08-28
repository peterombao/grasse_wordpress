<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */
 /* Template name: home */
$theme_url = get_bloginfo('template_url');

wp_enqueue_script('responsive-slider', $theme_url. '/js/responsive-slider.js',array( 'jquery' ));
wp_localize_script( 'responsive-slider', 'slider', array( 	'effect'    => 'fade', 	'delay'  => '7000', 'duration'  => '600', 'start'  => '1', 'controlNav' => TRUE, 'directionNav' => FALSE) );
wp_register_style( 'responsive-slider-style', $theme_url. '/css/responsive-slider.css', array(), '20120208', 'all' ); 
wp_enqueue_style( 'responsive-slider-style' );

get_header(); ?>

<header id="header" role="banner" class="clearfix">
	<div class="clearfix">
		<?php get_template_part('template-responsive-slider', 'responsive-slider'); ?>
	</div>
</header>

<section id="main" role="main" class="clearfix">
	<div class="margin">

		<div class="content-wrap clearfix">

			<div id="content" class="clearfix">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				  
					<?php get_template_part( 'content', prima_get_setting( 'content_layout' ) ); ?>

				<?php endwhile;  else: ?>
				
					<?php get_template_part( 'content', '404' ); ?>
				  
				<?php endif; ?>
			</div>

			<?php prima_sidebar(); ?>

		</div>

		<?php prima_sidebar( 'mini' ); ?>

	</div>
</section>

<?php get_footer(); ?>