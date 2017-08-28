<?php

add_action( 'wp_head', 'prima_ie_js_html5' );
function prima_ie_js_html5() {
?>
<!--[if lt IE 9]>
<script src="<?php echo PRIMA_URI; ?>/js/html5.js"></script>
<![endif]-->
<?php
}

add_filter( 'body_class', 'prima_style_layout_class' );
function prima_style_layout_class( $classes ) {
	$style = prima_get_setting( 'style', PRIMA_DESIGN_SETTINGS );
	if (!$style) $style = 'boxed';
	$classes[] = 'stylelayout-'.$style;
	return $classes;
}

add_action( 'get_header', 'prima_styles_basic');
function prima_styles_basic() {
	wp_enqueue_style('style-basic', prima_childtheme_file('style-basic.css'), false, '0.1', 'screen, projection');
}

add_action( 'get_header', 'raleway_font');
function raleway_font(){
	wp_enqueue_style('font-raleway', 'http://fonts.googleapis.com/css?family=Raleway', false, '0.1', 'screen, projection');
}

add_action( 'get_header', 'prima_styles_layout');
function prima_styles_layout() {
	$style = prima_get_setting( 'style', PRIMA_DESIGN_SETTINGS );
	if (!$style) $style = 'boxed';
	wp_enqueue_style('style-layout', prima_childtheme_file('style-'.$style.'.css'), false, '0.1', 'screen, projection');
}

add_action( 'get_header', 'prima_styles_shortcodes');
function prima_styles_shortcodes() {
	wp_enqueue_style('style-shortcodes', prima_childtheme_file('style-shortcodes.css'), false, '0.1', 'screen, projection');
}

add_action( 'get_header', 'prima_styles_responsive');
function prima_styles_responsive() {
	$responsive = prima_get_setting( 'responsive', PRIMA_DESIGN_SETTINGS );
	if ( $responsive == 'no' ) return;
	wp_enqueue_style('style-responsive', prima_childtheme_file('style-responsive.css'), false, '0.1', 'screen, projection');
}

add_action( 'wp_head', 'prima_meta_responsive' );
function prima_meta_responsive() {
	$responsive = prima_get_setting( 'responsive', PRIMA_DESIGN_SETTINGS );
	if ( $responsive == 'no' ) return;
?>
<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1">
<?php
}

add_action( 'wp_head', 'prima_ie_js_responsive' );
function prima_ie_js_responsive() {
	$responsive = prima_get_setting( 'responsive', PRIMA_DESIGN_SETTINGS );
	if ( $responsive == 'no' ) return;
?>
<!--[if lt IE 9]>
<script src="<?php echo PRIMA_URI; ?>/js/respond.min.js"></script>
<![endif]-->
<?php
}

add_action( 'wp_footer', 'prima_footer_responsive', 100);
function prima_footer_responsive() {
	$responsive_image = prima_get_setting( 'responsive_image', PRIMA_DESIGN_SETTINGS );
	if ( $responsive_image == 'no' ) return;
	echo '<img src="'.PRIMA_URI.'/images/resize.png" id="resize-symbol" alt="'.__('I am responsive!', 'primathemes').'" />'."\n";
}

add_action( 'get_header', 'prima_styles_theme');
function prima_styles_theme() {
	wp_enqueue_style('style-theme', get_bloginfo('stylesheet_url'), false, '0.1', 'screen, projection');
}

add_filter( 'body_class', 'prima_header_logo_class' );
function prima_header_logo_class( $classes ) {
	$logo = prima_get_setting( 'header_logo' );
	if ($logo) $classes[] = 'header-logo-active';
	return $classes;
}

add_action( 'prima_styles', 'prima_header_logo_styles' );
function prima_header_logo_styles() {
	$logo = prima_get_setting( 'header_logo' );
	
	if (!$logo) return;
	if ( is_ssl() ) $logo = str_replace("http://", "https://", $logo);
	echo 'body.header-logo-active #primarylogo { background: url('.$logo.') no-repeat left center; }';
	$logosize = '';
	$logowidth = prima_get_setting( 'header_logowidth' );
	if ($logowidth) $logosize .= 'width:'.$logowidth.'px;';
	$logoheight = prima_get_setting( 'header_logoheight' );
	if ($logoheight) $logosize .= 'height:'.$logoheight.'px;';
	if ($logosize) 
		echo 'body.header-logo-active #primarylogo, body.header-logo-active #primarylogo a {'.$logosize.'}';
}

function prima_customheader( $id = '', $type = 'post', $name = '' ) {
	if ( $type == 'post' )
		$meta = prima_get_post_meta('_prima_header_custom', $id);
	else 
		$meta = prima_get_taxonomy_meta( $id, $name, '_prima_header_custom' );
	if ( !$meta ) return;
	$class = '';
	echo '<div id="headercustom" class="margin clearfix '.$class.'">';
	echo do_shortcode(shortcode_unautop(wpautop(wptexturize($meta))));
	echo '</div>';
}

add_filter('get_comments_number', 'prima_comment_count', 0);
function prima_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}

add_filter( 'get_search_form', 'prima_custom_search_form' );
function prima_custom_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div>
    <input type="text" value="'.__( 'Search...', 'primathemes' ).'" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr(__( 'Search', 'primathemes' )) .'" />
    </div>
    </form>';
    return $form;
}

add_filter('body_class', 'prima_footer_body_classes');
function prima_footer_body_classes($classes) {
	if( (int)prima_get_setting('footer_top_layout') >= 0 )
		$classes[] = 'footer-top-layout-'.prima_get_setting('footer_top_layout');
	if( (int)prima_get_setting('footer_bottom_layout') >= 0 )
		$classes[] = 'footer-bottom-layout-'.prima_get_setting('footer_bottom_layout');
	return $classes; 
}

add_action('prima_custom_scripts', 'prima_print_ticker_scripts');
function prima_print_ticker_scripts() {
	if ( !prima_get_setting( 'ticker' ) ) return;
	echo 'jQuery(window).load(function() {';
	echo 'jQuery("#topticker").flexslider({';
	if ( prima_get_setting( 'ticker_animation' ) == 'slide' ) {
		echo 'animation: "slide",';
		echo 'controlsContainer: "#topticker-container",';
	}
	elseif ( prima_get_setting( 'ticker_animation' ) == 'fade' ) {
		echo 'animation: "fade",';
	}
	$speed = (int)prima_get_setting( 'ticker_speed' ) ? (int)prima_get_setting( 'ticker_speed' ) : 4000;
	echo 'slideshowSpeed: '.$speed.',';
	$duration = (int)prima_get_setting( 'ticker_duration' ) ? (int)prima_get_setting( 'ticker_duration' ) : 600;
	echo 'animationDuration: '.$duration.',';
	echo 'slideDirection: "horizontal",';
	echo 'slideshow: true,';
	echo 'directionNav: false,';
	echo 'controlNav: false';
	echo '});';
	echo '});';
	echo "\n";
}

add_filter( 'body_class', 'prima_product_hover_class' );
function prima_product_hover_class( $classes ) {
	$hover = prima_get_setting( 'shop_hover' );
	if ($hover) $classes[] = 'products-hover-active';
	return $classes;
}


add_action( 'wp_head', 'prima_meta_iecompatible' );
function prima_meta_iecompatible() {
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
}

add_action( 'prima_styles', 'prima_custom_css3pie' );
function prima_custom_css3pie() {
	echo '.flexslider { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.flex-control-nav li a { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '#minicart a.miniButton { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo 'nav#primary ul.sf-menu li ul.sub-menu li a { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '#header  #headersearchform input.searchinput { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.section h2 span, h2.horizontalheading span { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '#nav-numeric li a { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.button, #comments .reply a, #cancel-comment-reply-link { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '#respond input#submit { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo 'span.onsale, ul.products li div.prodHover span.onsale { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.quantity input.qty, .quantity input.plus, .quantity input.minus { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.old-button { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo 'div.product .woocommerce_tabs ul.tabs { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo 'table.shop_table, table.cart td.actions .coupon .input-text, .cart-collaterals .cart_totals table { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '#payment, form.login { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.widget_price_filter .ui-corner-all .widget_layered_nav ul li.chosen a { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo 'div.contact-form input.txt, div.contact-form textarea, div.contact-form #contactSubmit { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.stylelayout-boxed #container .containerInner { behavior: url('.PRIMA_URI.'/js/PIE.php) } '; 
	echo 'ul.products li div.prodHover { behavior: url('.PRIMA_URI.'/js/PIE.php) } '; 
	echo '#header  img.headerthumb { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo 'article.postblog img.featuredimage { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo 'article.post img { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '#nav-numeric li a  { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.prima_recent_posts li img { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
	echo '.prima_recent_comments li img { behavior: url('.PRIMA_URI.'/js/PIE.php) } ';
}

add_action( 'wp_footer', 'ev_custom_scripts' );
function ev_custom_scripts(){

	wp_enqueue_script('ev-easing', PRIMA_URI. '/js/jquery.easing.min.js',array( 'jquery' ));
	wp_enqueue_script('ev-scripts', PRIMA_URI. '/js/ev-scripts.js',array( 'jquery' ));
}
