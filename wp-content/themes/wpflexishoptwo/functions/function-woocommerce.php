<?php

add_action( 'after_setup_theme', 'prima_shop_add_theme_support' );
function prima_shop_add_theme_support() {
	add_post_type_support( 'product', 'prima-layout-settings' );
	if ( !class_exists("wpSEO") && !class_exists("All_in_One_SEO_Pack") && !class_exists("HeadSpace_Plugin") && !class_exists("Platinum_SEO_Pack") ) {
		add_post_type_support( 'product', 'prima-seo-settings' );
	}
	add_theme_support( 'prima-sidebar-settings' );
}



function prima_get_menu_childs( $menu_name ) {
	global $post;
	
	$locations = get_nav_menu_locations();
	if ( !$locations || !isset( $locations[$menu_name] ) )
		return false;

	$menu = wp_get_nav_menu_items( $locations[$menu_name] ); // All the "upper" menu items array
	$menuID = 0;                                             // We need to find $menuID that corresponds to the post ID
	foreach ( $menu as $item )
	{
		if ( $item->object_id == $post->ID )
			$menuID = $item->ID;
	}
	// #menuID is menu item ID that that corresponds to specific post ID
	// We must be careful here not to have multiple menu items with the same post ID

	foreach ( $menu as $item )
	{
		if ( $item->menu_item_parent == $menuID )
			return true;
	}

	return false;
}

function prima_minicart_in_wp_3_5()
{
	global $woocommerce;
	$cart_count = $woocommerce->cart->cart_contents_count;
	$items .= '<ul id="topnavmenu">';
	$items .= '<li id="basketlink">';
	$items .= '<a class="basket" href="'.$woocommerce->cart->get_cart_url().'">'.sprintf( __('Your Basket (%d)', 'primathemes'), $cart_count ).'</a>';
	if( !is_cart() && !is_checkout() ) :
		$items .= '<div id="minicart">';
		$items .= '<h4 class="minicart-cartcount">'.sprintf(_n('<strong>%d</strong> item', '<strong>%d</strong> items', $cart_count, 'primathemes'), $cart_count).' <a class="right" href="'.$woocommerce->cart->get_cart_url().'">'.__('View Cart &rarr;', 'primathemes').'</a></h4>';
		ob_start();
			$instance = array('title' => '', 'number' => 999);
			$args = array('before_widget' => '<div class="widget_shopping_cart">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget_title">', 'title' => '', 'after_title' => '</h4>');
			if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
			  $prima_minicart = new WooCommerce_Widget_Cart();
			} else {
			  $prima_minicart = new WC_Widget_Cart();
			}
			$prima_minicart->number = $instance['number'];
			$prima_minicart->widget($args,$instance);
		$items .= ob_get_clean();
		if ( $cart_count > 0 ) 
			$items .= '<a class="miniButton" href="'.$woocommerce->cart->get_checkout_url().'">'.__('Checkout', 'primathemes').'</a>';
		else 
			$items .= '<a class="miniButton" href="'.get_permalink(get_option('woocommerce_shop_page_id')).'">'.__('Visit Shop', 'primathemes').'</a>';
		$items .= '</div>';
	endif;
    $items .= '</li>';
    $items .= '</ul>';
    echo $items;
}

add_filter( 'wp_nav_menu_items', 'prima_minicart_nav_menu_items', 10, 2 );
function prima_minicart_nav_menu_items( $items, $args ) {
	if ( !prima_get_setting( 'minicart' ) )
		return $items;
	if ( $args->theme_location != 'topnav-menu' && $args->theme_location != 'loggedin-topnav-menu' )
		return $items;
	global $woocommerce;
	$cart_count = $woocommerce->cart->cart_contents_count;
	$items .= '<li id="basketlink">';
	$items .= '<a class="basket" href="'.$woocommerce->cart->get_cart_url().'">'.sprintf( __('Your Basket (%d)', 'primathemes'), $cart_count ).'</a>';
	if( !is_cart() && !is_checkout() ) :
		$items .= '<div id="minicart">';
		$items .= '<h4 class="minicart-cartcount">'.sprintf(_n('<strong>%d</strong> item', '<strong>%d</strong> items', $cart_count, 'primathemes'), $cart_count).' <a class="right" href="'.$woocommerce->cart->get_cart_url().'">'.__('View Cart &rarr;', 'primathemes').'</a></h4>';
		ob_start();
			$instance = array('title' => '', 'number' => 999);
			$args = array('before_widget' => '<div class="widget_shopping_cart">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget_title">', 'title' => '', 'after_title' => '</h4>');
			
			if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
			  $prima_minicart = new WooCommerce_Widget_Cart();
			} else {
			  $prima_minicart = new WC_Widget_Cart();
			}
			
			$prima_minicart->number = $instance['number'];
			$prima_minicart->widget($args,$instance);
		$items .= ob_get_clean();
		if ( $cart_count > 0 ) 
			$items .= '<a class="miniButton" href="'.$woocommerce->cart->get_checkout_url().'">'.__('Checkout', 'primathemes').'</a>';
		else 
			$items .= '<a class="miniButton" href="'.get_permalink(get_option('woocommerce_shop_page_id')).'">'.__('Visit Shop', 'primathemes').'</a>';
		$items .= '</div>';
	endif;
    $items .= '</li>';
    return $items;
}

add_filter('loop_shop_per_page', 'prima_shop_perpage');
function prima_shop_perpage() {
	$perpage = prima_get_setting( 'shop_perpage' );
	if ( !$perpage ) $perpage = 12;
    return $perpage;
}

add_filter('loop_shop_columns', 'prima_shop_columns');
function prima_shop_columns() {
	$columns = prima_get_setting( 'shop_columns' );
	if ( !$columns ) $columns = 4;
    return $columns;
}

if ( prima_get_setting('shop_sale_hide') ) {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
}
else {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10);
}

if ( prima_get_setting('shop_price_hide') ) {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
}
else {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10);
}

if ( prima_get_setting('shop_addtocart') ) {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}
else {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

if ( prima_get_setting('product_sale_hide') )
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

if ( prima_get_setting('product_price_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

if ( prima_get_setting('product_excerpt_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

if ( prima_get_setting('product_addtocart_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

if ( prima_get_setting('product_meta_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

if ( prima_get_setting('product_datatabs') == 'right' ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
	add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60);
}

if ( prima_get_setting('shop_related_hide') ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products' );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}

if ( prima_get_setting('shop_upsells_hide') ) {
	remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display' );
	remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 20 );
}

if ( prima_get_setting('shop_crosssells_hide') ) {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 20 );
}

add_filter( 'wp_title', 'woo_page_title', 20, 3);
function woo_page_title( $title, $sep = '', $seplocation = '' ) {
	global $wp_query;
	$seotitle = '';
	if (function_exists( 'is_post_type_archive' ) && is_post_type_archive('product') ) {
		$postid = woocommerce_get_page_id('shop');
		$posttype = $wp_query->post->post_type;
		$seotitle_inpost = prima_get_post_meta( "_prima_title", $postid );
		if ( $seotitle_inpost ) $seotitle = $seotitle_inpost;
		if ( !$seotitle && prima_get_setting( "title_{$posttype}", PRIMA_SEO_SETTINGS ) )
			$seotitle = str_replace("%posttitle%", $wp_query->post->post_title, prima_get_setting( "title_{$posttype}", PRIMA_SEO_SETTINGS ));
  }
  if ($seotitle) return $seotitle;
	else return $title;
}
	

add_filter('add_to_cart_fragments', 'prima_topnav_addtocart_fragment');
function prima_topnav_addtocart_fragment( $fragments ) {
	global $woocommerce;
	$cart_count = $woocommerce->cart->cart_contents_count;
	$fragments['#basketlink a.basket'] = '<a class="basket" href="'.$woocommerce->cart->get_cart_url().'">'.sprintf( __('Your Basket (%d)', 'primathemes'), $cart_count ).'</a>';
	return $fragments;
}

add_filter('add_to_cart_fragments', 'prima_minicartcount_fragment');
function prima_minicartcount_fragment( $fragments ) {
	global $woocommerce;
	$cart_count = $woocommerce->cart->cart_contents_count;
	$fragments['#minicart h4.minicart-cartcount'] = '<h4 class="minicart-cartcount">'.sprintf(_n('<strong>%d</strong> item', '<strong>%d</strong> items', $cart_count, 'primathemes'), $cart_count).' <a class="right" href="'.$woocommerce->cart->get_cart_url().'">'.__('View Cart &rarr;', 'primathemes').'</a></h4>';
	return $fragments;
}