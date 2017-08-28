<?php
add_filter( 'prima_theme_settings_args', 'prima_theme_general_settings' );
function prima_theme_general_settings( $settings ) {
	$settings["general"] = array( "name" => __('General', 'primathemes'),
						"icon" => "general",
						"type" => "heading");
	global $prima_registered_layouts, $prima_default_layout;
	$layouts = array();
	if ($prima_registered_layouts) { foreach ( $prima_registered_layouts as $layout ) { 
		$layouts[$layout['id']] = $layout['image'];
	} }
	if ( 0 != count( $layouts ) ) {
		$settings["layout_default"] = array( "name" => __('Default Layout', 'primathemes'),
							"desc" => "",
							"class" => "onecolumn",
							"id" => "layout_default",
							"std" => $prima_default_layout['id'],
							"type" => "images",
							"options" => $layouts );
	}
	$settings['content_layout'] = array( "name" => __('Default Content Layout', 'primathemes'),
						"desc" => __('It will be used for default blog, post category, post tag, archive, and search result page.', 'primathemes'),
						"id" => "content_layout",
						"std" => '',
						"type" => "select2",
						"options" => array ( 
							'' => __('Full Text', 'primathemes'),
							'excerpt' => __('Summary', 'primathemes'),
							'featured' => __('Featured Image + Summary', 'primathemes'),
							'thumbnailleft' => __('Left Thumbnail + Summary', 'primathemes'),
							'thumbnailright' => __('Right Thumbnail + Summary', 'primathemes'),
						) );
	$settings['content_navigation'] = array( "name" => __('Default Content Navigation', 'primathemes'),
						"desc" => null,
						"id" => "content_navigation",
						"std" => '',
						"type" => "select2",
						"options" => array ( 
							'prevnext' => __('Previous Page - Next Page', 'primathemes'),
							'oldernewer' => __('Older Posts - Newer Posts', 'primathemes'),
							'numeric' => __('Numeric Navigation', 'primathemes'),
						) );
	return $settings;
}

add_filter( 'prima_theme_settings_args', 'prima_theme_topnav_settings' );
function prima_theme_topnav_settings( $settings ) {
	$settings["topnav"] = array( "name" => __('Top Navigation', 'primathemes'),
						"icon" => "topnav",
						"type" => "heading");
	$settings['topnavigation'] = array( "name" => __('Top Navigation', 'primathemes'),
						"desc" => __('Enable top navigation area', 'primathemes'),
						"id" => "topnavigation",
						"std" => "true",
						"type" => "checkbox");
	$settings[] = array( "name" => __('Top Ticker', 'primathemes'),
						"std" => __('Top Ticker area shows latest news (blog posts).', 'primathemes') . ' <a href="'. admin_url('edit.php') .'">' . __('Manage News / Blog Posts', 'primathemes') . '</a>',
						"type" => "info");
	$settings['ticker'] = array( "name" => __('Top Ticker', 'primathemes'),
						"desc" => __('Show top ticker.', 'primathemes'),
						"id" => "ticker",
						"std" => "true",
						"type" => "checkbox");
	$settings['ticker_number'] = array( "name" => __('Number of Latest News', 'primathemes'),
						"desc" => null,
						"id" => "ticker_number",
						"std" => '5',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	$settings['ticker_animation'] = array( "name" => __('Ticker Animation Type', 'primathemes'),
						"desc" => null,
						"id" => "ticker_animation",
						"std" => 'slide',
						"type" => "select2",
						"options" => array ( 'slide'=>'slide', 'fade'=>'fade' ) );
	$settings['ticker_speed'] = array( "name" => __('Ticker Slideshow Speed', 'primathemes'),
						"desc" => __('The speed of the slideshow cycling, in milliseconds.', 'primathemes'),
						"id" => "ticker_speed",
						"std" => "4000",
						"type" => "text");    
	$settings['ticker_duration'] = array( "name" => __('Ticker Animation Duration', 'primathemes'),
						"desc" => __('The speed of animations, in milliseconds.', 'primathemes'),
						"id" => "ticker_duration",
						"std" => "600",
						"type" => "text");    
	$settings[] = array( "name" => __('Top Menu', 'primathemes'),
						"std" => sprintf(__('Visit your <a href="%1$s">Menus Page</a> to configure your Top Menu.', 'primathemes'), admin_url('nav-menus.php')),
						"type" => "info");
	$settings['topmenu'] = array( "name" => __('Top Menu', 'primathemes'),
						"desc" => __('Show top menu.', 'primathemes'),
						"id" => "topmenu",
						"std" => "true",
						"type" => "checkbox");
	$settings['minicart'] = array( "name" => __('Mini Shopping Cart', 'primathemes'),
						"desc" => __('Show mini shopping cart in the end of Top Menu.', 'primathemes'),
						"id" => "minicart",
						"std" => "true",
						"type" => "checkbox");
	return $settings;
}

add_filter( 'prima_theme_settings_args', 'prima_theme_logo_settings' );
function prima_theme_logo_settings( $settings ) {
	$settings["logo"] = array( "name" => __('Logo + Header Menu', 'primathemes'),
						"icon" => "logo",
						"type" => "heading");
	$settings["header_logo"] = array( "name" => __('Logo Image URL', 'primathemes'),
						"desc" => __('png, jpg or gif file.', 'primathemes'),
						"id" => "header_logo",
						"type" => "upload");    
	$settings["header_logowidth"] = array( "name" => __('Logo Image Width', 'primathemes'),
						"desc" => __('px', 'primathemes'),
						"id" => "header_logowidth",
						"std" => "210",
						"type" => "text");    
	$settings["header_logoheight"] = array( "name" => __('Logo Image Height', 'primathemes'),
						"desc" => __('px', 'primathemes'),
						"id" => "header_logoheight",
						"std" => "24",
						"type" => "text");    
	$settings["mobile_header_logo"] = array( "name" => __('Mobile Logo Image URL', 'primathemes'),
						"desc" => __('png, jpg or gif file.', 'primathemes'),
						"id" => "mobile_header_logo",
						"type" => "upload");    	
	$settings[] = array( "name" => __('Header Menu', 'primathemes'),
						"std" => sprintf(__('Visit your <a href="%1$s">Menus Page</a> to configure your Header Menu.', 'primathemes'), admin_url('nav-menus.php')),
						"type" => "info");
											
	return $settings;
}

add_filter( 'prima_theme_settings_args', 'prima_theme_shop_settings' );
function prima_theme_shop_settings( $settings ) {
	$settings["shop"] = array( "name" => __('Shop / Products Page', 'primathemes'),
						"icon" => "layout",
						"type" => "heading");
	$settings["shop_tagline"] = array( "name" => __('Shop / Products Page Tagline', 'primathemes'),
						"desc" => null,
						"std" => __('This is short tagline for your Products Page. You can change it from Theme Settings', 'primathemes'),
						"id" => "shop_tagline",
						"type" => "text");
	global $prima_registered_layouts, $prima_default_layout;
	$layouts = array();
	if ($prima_registered_layouts) { foreach ( $prima_registered_layouts as $layout ) { 
		$layouts[$layout['id']] = $layout['image'];
	} }
	if ( 0 != count( $layouts ) ) {
		$layouts = array_merge( array( '' => $prima_default_layout['image'] ), $layouts );
		$settings["layout_archive_product"] = array( "name" => __('Default Shop / Products Page Layout', 'primathemes'),
							"desc" => "",
							"class" => "onecolumn",
							"id" => "layout_archive_product",
							"std" => "",
							"type" => "images",
							"options" => $layouts );
	}
	global $prima_registered_sidebar_areas;
	if ( !empty( $prima_registered_sidebar_areas ) ) {
		$sidebars = stripslashes_deep( get_option( PRIMA_SIDEBAR_SETTINGS ) );
		$sidebars_opt = array ( '' => __('Default','primathemes') ); 
		foreach ( (array)$sidebars as $id => $info ) {
			if ( $id != '0' ) {
				$sidebars_opt[$id] = $info['name']; 
			}
		}
		foreach ( $prima_registered_sidebar_areas as $sidebar_area ) {
			$settings["sidebar{$sidebar_area['id']}_archive_product"] = array( "name" => $sidebar_area['label'],
								"desc" => '<a href="'.admin_url('themes.php?page=primathemes-sidebars').'">'.__( 'Manage Sidebars', 'primathemes' ).'</a> &middot; <a href="'.admin_url('widgets.php').'">'.__( 'Manage Widgets', 'primathemes' ).'</a>',
								"id" => "sidebar{$sidebar_area['id']}_archive_product",
								"std" => '',
								"type" => "select2",
								"options" => $sidebars_opt );
		}
	}
	$settings["shop_perpage"] = array( "name" => __('Products Per Page', 'primathemes'),
						"desc" => null,
						"std" => '12',
						"id" => "shop_perpage",
						"type" => "text");
	$settings["shop_columns"] = array( "name" => __('Products Columns Per Row', 'primathemes'),
						"desc" => null,
						"id" => "shop_columns",
						"std" => '4',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	$settings['shop_sale'] = array( "name" => __('Products Sale Flash', 'primathemes'),
						"desc" => __('Hide products sale flash.', 'primathemes'),
						"id" => "shop_sale_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['shop_title'] = array( "name" => __('Products Title', 'primathemes'),
						"desc" => __('Hide products title.', 'primathemes'),
						"id" => "shop_title_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['shop_price'] = array( "name" => __('Products Price', 'primathemes'),
						"desc" => __('Hide products price below product title.', 'primathemes'),
						"id" => "shop_price_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['shop_addtocart'] = array( "name" => __('Products &quot;Add To Cart&quot; Button', 'primathemes'),
						"desc" => __('Show &quot;Add To Cart&quot; button', 'primathemes'),
						"id" => "shop_addtocart",
						"std" => false,
						"type" => "checkbox");
	$settings['shop_hover'] = array( "name" => __('Products Hover Zoom Effect', 'primathemes'),
						"desc" => __('Show products hover zoom effect', 'primathemes'),
						"id" => "shop_hover",
						"std" => false,
						"type" => "checkbox");
	return $settings;
}

add_filter( 'prima_theme_settings_args', 'prima_theme_product_settings' );
function prima_theme_product_settings( $settings ) {
	$settings["product"] = array( "name" => __('Single Product Page', 'primathemes'),
						"icon" => "layout",
						"type" => "heading");
	global $prima_registered_layouts, $prima_default_layout;
	$layouts = array();
	if ($prima_registered_layouts) { foreach ( $prima_registered_layouts as $layout ) { 
		$layouts[$layout['id']] = $layout['image'];
	} }
	if ( 0 != count( $layouts ) ) {
		$layouts = array_merge( array( '' => $prima_default_layout['image'] ), $layouts );
		$settings["layout_product"] = array( "name" => __('Default Single Product Page Layout', 'primathemes'),
							"desc" => "",
							"class" => "onecolumn",
							"id" => "layout_product",
							"std" => "",
							"type" => "images",
							"options" => $layouts );
	}
	global $prima_registered_sidebar_areas;
	if ( !empty( $prima_registered_sidebar_areas ) ) {
		$sidebars = stripslashes_deep( get_option( PRIMA_SIDEBAR_SETTINGS ) );
		$sidebars_opt = array ( '' => __('Default','primathemes') ); 
		foreach ( (array)$sidebars as $id => $info ) {
			if ( $id != '0' ) {
				$sidebars_opt[$id] = $info['name']; 
			}
		}
		foreach ( $prima_registered_sidebar_areas as $sidebar_area ) {
			$settings["sidebar{$sidebar_area['id']}_product"] = array( "name" => $sidebar_area['label'],
								"desc" => '<a href="'.admin_url('themes.php?page=primathemes-sidebars').'">'.__( 'Manage Sidebars', 'primathemes' ).'</a> &middot; <a href="'.admin_url('widgets.php').'">'.__( 'Manage Widgets', 'primathemes' ).'</a>',
								"id" => "sidebar{$sidebar_area['id']}_product",
								"std" => '',
								"type" => "select2",
								"options" => $sidebars_opt );
		}
	}
	$settings['product_sale'] = array( "name" => __('Product Sale Flash', 'primathemes'),
						"desc" => __('Hide product sale flash.', 'primathemes'),
						"id" => "product_sale_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['product_price'] = array( "name" => __('Product Price', 'primathemes'),
						"desc" => __('Hide product price.', 'primathemes'),
						"id" => "product_price_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['product_excerpt'] = array( "name" => __('Product Excerpt', 'primathemes'),
						"desc" => __('Hide product excerpt / summaries / short description.', 'primathemes'),
						"id" => "product_excerpt_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['product_addtocart'] = array( "name" => __('Product &quot;Add To Cart&quot; Button', 'primathemes'),
						"desc" => __('Hide &quot;Add To Cart&quot; button.', 'primathemes'),
						"id" => "product_addtocart_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['product_meta'] = array( "name" => __('Product Meta (Categories/Tags)', 'primathemes'),
						"desc" => __('Hide product meta (categories/tags).', 'primathemes'),
						"id" => "product_meta_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['product_datatabs'] = array( "name" => __('Product Data Tabs Position', 'primathemes'),
						"desc" => null,
						"id" => "product_datatabs",
						"std" => 'bottom',
						"type" => "select2",
						"options" => array ( 'bottom'=>__('Bottom (default)', 'primathemes'), 'right'=>__('Right Column', 'primathemes') ) );
	$settings['shop_related'] = array( "name" => __('Related Products', 'primathemes'),
						"std" => __( 'Related products are found from category and tag in random order.', 'primathemes' ),
						"type" => "info");
	$settings['shop_related_hide'] = array( "name" => __('Related Products', 'primathemes'),
						"desc" => __('Hide related products on single product page', 'primathemes'),
						"id" => "shop_related_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['shop_related_perpage'] = array( "name" => __('Number of Related Products', 'primathemes'),
						"desc" => null,
						"id" => "shop_related_perpage",
						"std" => '4',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	$settings['shop_related_columns'] = array( "name" => __('Related Products Columns Per Row', 'primathemes'),
						"desc" => null,
						"id" => "shop_related_columns",
						"std" => '4',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	$settings['shop_upsells'] = array( "name" => __('Up Sells', 'primathemes'),
						"std" => __( 'Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive. You can edit up sells when editing your products.', 'primathemes' ),
						"type" => "info");
	$settings['shop_upsells_hide'] = array( "name" => __('Up Sells', 'primathemes'),
						"desc" => __('Hide up sells products on single product page', 'primathemes'),
						"id" => "shop_upsells_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['shop_upsells_perpage'] = array( "name" => __('Number of Up Sells', 'primathemes'),
						"desc" => null,
						"id" => "shop_upsells_perpage",
						"std" => '4',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	$settings['shop_upsells_columns'] = array( "name" => __('Up Sells Columns Per Row', 'primathemes'),
						"desc" => null,
						"id" => "shop_upsells_columns",
						"std" => '4',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	return $settings;
}


add_filter( 'prima_theme_settings_args', 'prima_theme_cart_settings' );
function prima_theme_cart_settings( $settings ) {
	$settings["cart"] = array( "name" => __('Cart Page', 'primathemes'),
						"icon" => "layout",
						"type" => "heading");
	$settings['shop_crosssells'] = array( "name" => __('Cross Sells', 'primathemes'),
						"std" => __( 'Cross-sells are products which you promote in the cart, based on the current product. You can edit cross sells when editing your products.', 'primathemes' ),
						"type" => "info");
	$settings['shop_crosssells_hide'] = array( "name" => __('Cross Sells', 'primathemes'),
						"desc" => __('Hide cross sells products on single product page', 'primathemes'),
						"id" => "shop_crosssells_hide",
						"std" => false,
						"type" => "checkbox");
	$settings['shop_crosssells_perpage'] = array( "name" => __('Number of Cross Sells', 'primathemes'),
						"desc" => null,
						"id" => "shop_crosssells_perpage",
						"std" => '2',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	$settings['shop_crosssells_columns'] = array( "name" => __('Cross Sells Columns Per Row', 'primathemes'),
						"desc" => null,
						"id" => "shop_crosssells_columns",
						"std" => '2',
						"type" => "select2",
						"options" => array ( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10' ) );
	return $settings;
}

add_filter( 'prima_theme_settings_args', 'prima_theme_footer_settings' );
function prima_theme_footer_settings( $settings ) {
	$settings["footer"] = array( "name" => __('Footer', 'primathemes'),
						"icon" => "footer",
						"type" => "heading");
	$settings["footer_top_layout"] = array( "name" => __('Footer Top Widgets Layout', 'primathemes'),
						"desc" => "",
						"class" => "onecolumn",
						"id" => "footer_top_layout",
						"std" => '0',
						"type" => "images",
						"options" => array(
							'0' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-0.png',
							'10' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-10.png',
							'20' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-20.png',
							'21' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-21.png',
							'22' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-22.png',
							'30' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-30.png',
							'31' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-31.png',
							'32' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-32.png',
							'40' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-40.png',
						) );
	$settings["footer_bottom_layout"] = array( "name" => __('Footer Bottom Widgets Layout', 'primathemes'),
						"desc" => "",
						"class" => "onecolumn",
						"id" => "footer_bottom_layout",
						"std" => '0',
						"type" => "images",
						"options" => array(
							'0' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-0.png',
							'10' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-10.png',
							'20' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-20.png',
							'21' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-21.png',
							'22' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-22.png',
							'30' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-30.png',
							'31' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-31.png',
							'32' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-32.png',
							'40' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-footer-40.png',
						) );
	$settings["footer_logo"] = array( "name" => __('Footer Logo', 'primathemes'),
						"desc" => __('png, jpg or gif file.', 'primathemes'),
						"id" => "footer_logo",
						"type" => "upload");    
	$settings["footer_copyright"] = array( "name" => __('Copyright', 'primathemes'),
						"desc" => __( 'Available Shortcodes:', 'primathemes' ).'<code>[the-year]</code>, <code>[site-link]</code>, <code>[wp-link]</code>, <code>[theme-link]</code>, <code>[loginout-link]</code>, <code>[query-counter]</code>',
						"std" => __( '&#169; [year] PrimaThemes. Powered by [theme-link], naturally.', 'primathemes' ),
						"id" => "footer_copyright",
						"type" => "textarea");
	$settings["footer_facebook"] = array( "name" => __('Facebook URL', 'primathemes'),
						"desc" => '',
						"std" => '',
						"id" => "footer_facebook",
						"type" => "text");
	$settings["footer_twitter"] = array( "name" => __('Twitter URL', 'primathemes'),
						"desc" => 'For example: '.'<br/>'.'http://twitter.com/primathemes.com',
						"std" => '',
						"id" => "footer_twitter",
						"type" => "text");
	$settings["footer_rss"] = array( "name" => __('RSS URL', 'primathemes'),
						"desc" => 'For example: '.'<br/>'.get_bloginfo('rss2_url'),
						"std" => '',
						"id" => "footer_rss",
						"type" => "text");
	return $settings;
}

add_filter( 'prima_post_meta_box_template_args', 'prima_meta_box_header_args' );
add_filter( 'prima_page_meta_box_template_args', 'prima_meta_box_header_args' );
add_filter( 'prima_product_meta_box_template_args', 'prima_meta_box_header_args' );
function prima_meta_box_header_args( $meta ) {
	$meta['header_tagline'] = array ( "name" => "_prima_tagline",
								"label" => __("Header Tagline", 'primathemes'),
								"type" => "text",
								"desc" => __("Tagline is displayed below page title.", 'primathemes') );
	return $meta;
}

add_filter( 'prima_page_meta_box_template_args', 'prima_meta_box_customheader_args' );
add_filter( 'prima_product_cat_meta_box_template_args', 'prima_meta_box_customheader_args' );
function prima_meta_box_customheader_args( $meta ) {
	$meta['header_hide'] = array ( "name" => "_prima_header_hide",
								"label" => __("Hide Default Header", 'primathemes'),
								"type" => "checkbox",
								"std" => false,
								"desc" => __("This option hide default page title and tagline. Use it when you use custom header below.", 'primathemes') );
	$meta['header_custom'] = array ( "name" => "_prima_header_custom",
								"label" => __("Custom Header", 'primathemes'),
								"type" => "wysiwyg",
								"desc" => __("You can add any content (HTML, shortcodes) for your custom header.", 'primathemes') );
	return $meta;
}

add_filter( 'prima_page_blog_meta_box_args', 'prima_custom_page_blog_meta_box_args' );
function prima_custom_page_blog_meta_box_args( $meta ) {
	$meta['page-blog-contentlayout'] = array ( "name" => "content_layout",
								"label" => __("Content Layout", 'primathemes'),
								"type" => "select2",
								"std" => "",
								"options" => array ( 
									'default' => __('Full Text', 'primathemes'),
									'excerpt' => __('Summary', 'primathemes'),
									'featured' => __('Featured Image + Summary', 'primathemes'),
									'thumbnailleft' => __('Left Thumbnail + Summary', 'primathemes'),
									'thumbnailright' => __('Right Thumbnail + Summary', 'primathemes'),
								) );
	$meta['page-blog-contentnavigation'] = array ( "name" => "content_navigation",
								"label" => __("Content Navigation", 'primathemes'),
								"type" => "select2",
								"std" => "",
								"options" => array ( 
									'prevnext' => __('Previous Page - Next Page', 'primathemes'),
									'oldernewer' => __('Older Posts - Newer Posts', 'primathemes'),
									'numeric' => __('Numeric Navigation', 'primathemes'),
								) );
	$meta['page-blog-postsperpage'] = array ( "name" => "postsperpage",
								"label" => __("Posts Per Page", 'primathemes'),
								"type" => "text",
								"std" => '',
								"desc" => '' );
	return $meta;
}

add_action( 'admin_head-post.php', 'prima_page_blog_head' );
add_action( 'admin_head-post-new.php', 'prima_page_blog_head' );
function prima_page_blog_head() {
	global $post_ID;
	if (!$post_ID) return;
	$prima_template = prima_get_post_meta( '_wp_page_template', $post_ID );
	if ( $prima_template !== 'page_blog.php' ) return;
    ?>
    <style type="text/css" media="screen">
		#postdivrich, #postexcerpt { display: none; visibility: hidden; }
    </style>
	<?php 
}

add_filter( 'prima_product_meta_box_normal_args', 'prima_custom_product_meta_box_args' );
function prima_custom_product_meta_box_args( $meta ) {
	$meta['multiple-product-images'] = array (	"name" => "_product_images",
								"label" => __("Multiple Product Images", 'primathemes'),
								"type" => "postgallery",
								"desc" => __("Add multiple images to this product.", 'primathemes') );
	return $meta;
}

add_filter( 'prima_slider_meta_box_normal_args', 'prima_custom_slider_meta_box_args' );
function prima_custom_slider_meta_box_args( $meta ) {
	$meta['slider-images'] = array (	"name" => "slider_images",
								"label" => __("Slider Images", 'primathemes'),
								"type" => "postgallery",
								"desc" => null );
	return $meta;
}
