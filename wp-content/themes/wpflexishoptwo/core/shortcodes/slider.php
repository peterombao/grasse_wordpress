<?php

add_shortcode('prima_slider', 'prima_slider_shortcode');
function prima_slider_shortcode( $atts ) {
	global $prima_shortcodes_scripts, $prima_shortcodes_js;
	if ( !is_array($prima_shortcodes_js) ) $prima_shortcodes_js = array();
	extract(shortcode_atts(array(
		'id' 	=> '',
		'width' 	=> null,
		'height' 	=> null,
		'animation' => 'fade',
		'speed' 	=> '4000',
		'duration' 	=> '600',
		'direction' => 'no',
		'control' 	=> 'no',
	), $atts));
	$box_id = rand(1000, 9999);
	$content = prima_get_gallery( array( 
		'post_id' => $id,
		'width' => $width,
		'height' => $height,
		'link_to_meta' => 'link_url',
		'before' => '<li>',
		'after' => '</li>',
		'before_container' => '<div id="slider-container-'.$box_id.'" class="flexslider-container clearfix"><div id="slider-'.$box_id.'" class="flexslider clearfix"><ul class="slides">',
		'after_container' => '</ul></div></div>'
	) );
	$prima_shortcodes_scripts .= 'jQuery(window).load(function() {';
	$prima_shortcodes_scripts .= 'jQuery("#slider-'.$box_id.'").flexslider({';
	$prima_shortcodes_scripts .= 'pauseOnHover: "true",';
	if ( $animation == 'slide' ) {
		$prima_shortcodes_scripts .= 'animation: "slide",';
		$prima_shortcodes_scripts .= 'controlsContainer: "#slider-container-'.$box_id.'",';
	}
	elseif ( $animation == 'fade' ) {
		$prima_shortcodes_scripts .= 'animation: "fade",';
	}
	$prima_shortcodes_scripts .= 'slideshowSpeed: '.$speed.',';
	$prima_shortcodes_scripts .= 'animationDuration: '.$duration.',';
	$direction = $direction == 'yes' ? 'true' : 'false';
	$prima_shortcodes_scripts .= 'directionNav: '.$direction.',';
	$control = $control == 'yes' ? 'true' : 'false';
	$prima_shortcodes_scripts .= 'controlNav: '.$control.',';
	$prima_shortcodes_scripts .= 'slideDirection: "horizontal",';
	$prima_shortcodes_scripts .= 'slideshow: true';
	$prima_shortcodes_scripts .= '});';
	$prima_shortcodes_scripts .= '});';
	$prima_shortcodes_scripts .= "\n";
	return $content;
}
