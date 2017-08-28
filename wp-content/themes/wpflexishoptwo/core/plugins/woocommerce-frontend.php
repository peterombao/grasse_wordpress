<?php

define( 'WOOCOMMERCE_USE_CSS', false );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 10, 0);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action( 'get_header', 'prima_products_layout_setup' );
function prima_products_layout_setup() {
	if ( !is_tax() ) return;
	if ( !prima_get_setting( 'layout_archive_product' ) ) return;
	if ( is_tax('product_cat') )
		add_filter( 'prima_get_setting_layout_product_cat', 'prima_products_layout_filter' );
	elseif ( is_tax('product_tag') )
		add_filter( 'prima_get_setting_layout_product_tag', 'prima_products_layout_filter' );
	else {
		$term = get_queried_object();
		if ( strpos($term->taxonomy, 'pa_') === 0 ) 
			add_filter( "prima_get_setting_layout_{$term->taxonomy}", 'prima_products_layout_filter' );
	}
	function prima_products_layout_filter( $setting ) {
		return prima_get_setting( 'layout_archive_product' );
	}
}

add_action( 'get_header', 'prima_products_sidebar_setup' );
function prima_products_sidebar_setup() {
	if ( !is_tax() ) return;
	global $prima_registered_sidebar_areas, $sidebar_area_active;
	if ( !empty( $prima_registered_sidebar_areas ) ) {
		foreach ( $prima_registered_sidebar_areas as $sidebar_area ) {
			if ( prima_get_setting( "sidebar{$sidebar_area['id']}_archive_product" ) ) {
				$sidebar_area_active = $sidebar_area['id'];
				if ( is_tax('product_cat') )
					add_filter( "prima_get_setting_sidebar{$sidebar_area['id']}_product_cat", 'prima_products_sidebar_filter' );
				elseif ( is_tax('product_tag') )
					add_filter( "prima_get_setting_sidebar{$sidebar_area['id']}_product_tag", 'prima_products_sidebar_filter' );
				else {
					$term = get_queried_object();
					if ( strpos($term->taxonomy, 'pa_') === 0 ) 
						add_filter( "prima_get_setting_sidebar{$sidebar_area['id']}_{$term->taxonomy}", 'prima_products_sidebar_filter' );
				}
			}
		}
	}
	
	if(!function_exists('prima_products_sidebar_filter')){
		function prima_products_sidebar_filter( $setting ) {
			global $sidebar_area_active;
			return prima_get_setting( "sidebar{$sidebar_area_active}_archive_product" );
		}
	}
}

add_filter( 'body_class', 'prima_demo_store_class' );
function prima_demo_store_class( $classes ) {
	if ( get_option( 'woocommerce_demo_store' ) == 'yes' )
		$classes[] = 'prima-demo-store-active';
	return $classes;
}

add_filter( 'taxonomy_template', 'prima_attributes_template' );
function prima_attributes_template( $template ) {
	$term = get_queried_object();
	if ( strpos($term->taxonomy, 'pa_') === 0 ) 
		return locate_template( array( "product_taxonomy.php" ) );
	return $template;
}

remove_filter( 'wp_nav_menu_items', 'woocommerce_nav_menu_items', 10, 2 );
add_filter( 'wp_nav_menu_items', 'prima_logout_nav_menu_items', 10, 2 );
function prima_logout_nav_menu_items( $items, $args ) {
	if ( get_option('woocommerce_menu_logout_link')=='yes' && strstr($items, get_permalink(get_option('woocommerce_myaccount_page_id'))) && is_user_logged_in() ) :
		$items .= '<li class="menu-item">'.$args->before.'<a href="'. wp_logout_url( home_url() ) .'">'.$args->link_before.__('Logout', 'primathemes').$args->link_after.'</a>'.$args->after.'</li>';
	endif;
    return $items;
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_thumbnail', 10);
function woocommerce_template_loop_product_thumbnail() {
	woocommerce_get_template('shop/thumbnail.php');
}

remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);
add_action( 'woocommerce_before_subcategory', 'woocommerce_subcategory_thumbnail', 10);
function woocommerce_subcategory_thumbnail( $category ) {
	woocommerce_get_template('shop/thumbnail-cat.php');
}
