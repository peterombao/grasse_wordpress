<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie6 oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html class="ie7 oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie8 oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

	<?php wp_head(); ?>

	<link rel="shortcut icon" href="<?php echo get_bloginfo('template_url'); ?>/images/favicon.ico" />
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "48e069e6-65c7-4220-aaba-ed5beb91b2b5", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
</head>

<body <?php body_class(); ?>>
  
  <div id="container">
  <div class="containerInner clearfix">
  
	<?php if ( !prima_get_setting( 'hidetopbanner' ) ) : ?>
    <div id="banner" class="clearfix">
	
		<?php if ( prima_get_setting( 'topmenu' ) ) : ?>
			<div id="topnavright" class="clearfix">
				<div class="topnav_container">
					<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" id="mobile_logo"><img src="<?php echo prima_get_setting('mobile_header_logo')?>" /><span><?php echo get_bloginfo('name'); ?></span></a>
					<span class="mobile-nav-btn"><span></span><span></span><span></span></span>
					<?php 
					if ( is_user_logged_in() && has_nav_menu('loggedin-topnav-menu') )
						wp_nav_menu( array( 'theme_location' => 'loggedin-topnav-menu', 'fallback_cb' => '', 'echo' => true, 'depth' => 1, 'container' => false, 'menu_id' => 'topnavmenu', 'menu_class' => 'topnavmenu', 'link_before' => '<span>', 'link_after' => '</span>' ) );  
					else
						wp_nav_menu( array( 'theme_location' => 'topnav-menu', 'fallback_cb' => '', 'echo' => true, 'depth' => 1, 'container' => false, 'menu_id' => 'topnavmenu', 'menu_class' => 'topnavmenu', 'link_before' => '<span>', 'link_after' => '</span>'  ) );  
					?>
					
					<div id="headersearchform">
						<form><?php prima_productsearch( 'headersearchform' ); ?></form>
					</div>
				</div>
			</div>
		<?php endif; ?>	
	
		<div class="mobile_site_name">
			<h2><?php echo get_bloginfo('name'); ?></h2>
		</div>
	
		<?php if ( prima_get_setting( 'topnavigation' ) ) : ?>
			<nav id="topnav" class="clearfix">
			
				<div class="margin">
					<h2 id="primarylogo"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h2>
					
								
					<?php if ( !prima_get_setting( 'hideprimary' ) ) : ?>
						<nav id="primary" class="">
						 
							<a href="#" class="mobile_cat_menu"><label>Product Categories</label><span class="drop_arrow"></span></a>
							<?php wp_nav_menu( array( 	'theme_location' => 'header-menu', 
																		'fallback_cb' => '', 
																		'echo' => true, 
																		'container' => false, 
																		'menu_id' => 'primaryMenu', 
																		'menu_class' => 'sf-menu primaryMenu' ) ); ?>				
						</nav>
					<?php endif; ?>					
						
				  
				</div>
			</nav>
	    <?php endif; ?>

		<?php echo '<div class="breadcrumb_container">'.ev_breadcrumb(array('label' => '<span class="breadcrumb_label">You are here:</span>','before_item' => '<li>', 'after_item' => '</li>', 'separator' => '<li class="separator"><span></span></li>', 'before_breadcrumb' => '<ul class="breadcrumb">', 'after_breadcrumb' => '</ul>', 'after_active' => '</span>', 'before_active' => '<span class="active">')).'</div>'; ?>
    </div>
	<?php endif; ?>	
