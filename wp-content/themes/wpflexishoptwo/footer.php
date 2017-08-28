<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */
?>

<?php if ( !prima_get_setting( 'hidefooter' ) ) : ?>
<footer id="footer" class="clearfix">

  <?php 
  if( (int)prima_get_setting('footer_top_layout') > 0 ) :
    get_sidebar( 'footer-top' );
  endif;
  ?>

  <?php 
  if( (int)prima_get_setting('footer_bottom_layout') > 0 ) :
    get_sidebar( 'footer-bottom' );
  endif;
  ?>

  <?php if ( !prima_get_setting( 'hidefootercredits' ) ) : ?>
  <div id="footercredits" class="clearfix">
	<div class="margin">
	  <div class="footerleft">
		<?php $footerlogo = prima_get_setting( 'footer_logo' ); ?>
		<?php if ( $footerlogo ) echo '<div class="footerlogo"><img alt="'.get_bloginfo( 'name' ).'" src="'.$footerlogo.'"></div>'; ?>
		<div class="footercopyright">
		  <?php echo do_shortcode(prima_get_setting( 'footer_copyright' )).'Powered by <a href="http://www.evolve.ph" target="_blank">Evolve Manila</a>.'; ?>
		</div>
	  </div>
	  <div class="footerright">
		<div class="footerMenu">
		  <?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'fallback_cb' => '', 'echo' => true, 'depth' => 1, 'container' => false, 'menu_id' => 'footerMenu', 'menu_class' => 'footerMenu' ) ); ?>	
		</div>
		<div class="social-icons">
			<?php prima_setting('footer_facebook', null, '<a href="%setting%" class="social-facebook" target="_blank">Facebook</a>'); ?>
			<?php prima_setting('footer_twitter', null, '<a href="%setting%" class="social-twitter" target="_blank">Twitter</a>'); ?>
			<?php prima_setting('footer_rss', null, '<a href="%setting%" class="social-rss" target="_blank">RSS</a>'); ?>
		</div>
	  </div>
	</div>
  </div>
  <?php endif; ?>	
  
</footer>
<?php endif; ?>	
	
</div> <!--! end of .containerInner -->
</div> <!--! end of #container -->

<?php wp_footer(); ?>
</body>
</html>