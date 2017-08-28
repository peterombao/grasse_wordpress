<?php
add_shortcode('prima_vimeo', 'prima_vimeo_shortcode');
function prima_vimeo_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'id' 	=> '30153918',
		'width' 	=> '700',
		'height' 	=> '394',
		'autoplay' 	=> false,
		'loop' 		=> false,
		'portrait' 	=> false,
		'title' 	=> false,
		'byline' 	=> false,
	), $atts));
	if ( $autoplay ) $autoplay = '&amp;autoplay=1';
	if ( $loop ) $loop = '&amp;loop=1';
	if ( !$portrait ) $portrait = '&amp;portrait=0';
	if ( !$title ) $title = '&amp;title=0';
	if ( !$byline ) $byline = '&amp;byline=0';
	$width_wrapper = $width < 1024 ? $width : 1024;
	$content = '<div class="video-wrapper" style="width:'.$width_wrapper.'px;"><div class="video-container"><if'.'rame src="http://player.vimeo.com/video/'.$id.'?wmode=opaque'.$autoplay.$loop.$portrait.$title.$byline.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen allowFullScreen></if'.'rame></div></div>';
	return $content;
}
/*-----------------------------------------------------------------------------------*/
/* Youtube
/*-----------------------------------------------------------------------------------*/
add_shortcode('prima_youtube', 'prima_youtube_shortcode');
function prima_youtube_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'id' 	=> 'chTkQgQKotA',
		'width' 	=> '700',
		'height' 	=> '386',
	), $atts));
	$width_wrapper = $width < 1024 ? $width : 1024;
	$content = '<div class="video-wrapper" style="width:'.$width_wrapper.'px;"><div class="video-container"><if'.'rame width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?wmode=opaque&amp;rel=0" frameborder="0" allowfullscreen></if'.'rame></div></div>';
	return $content;
}

add_shortcode('prima_audio', 'prima_audio_shortcodes');
function prima_audio_shortcodes($atts, $content=null){
	global $prima_shortcodes_scripts, $prima_shortcodes_js;
	if ( !is_array($prima_shortcodes_js) ) $prima_shortcodes_js = array();
	extract(shortcode_atts(array(
		'width' => 640,
		'mp3' => null,
		'ogg' => null
	), $atts));
	$box_id = rand(1000, 9999);
	$output = '';
	if ( $mp3 || $ogg ) {
		$output .= '<div id="jquery_jplayer_'.$box_id.'" class="prima-jp-jplayer"></div>';
		$output .= '<div class="prima-jp-audio-container" style="width:'.$width.'px;">';
		$output .= '<div class="prima-jp-audio">';
		$output .= '<div class="prima-jp-type-single">';
		$output .= '<div id="jp_interface_'.$box_id.'" class="prima-jp-interface">';
		$output .= '<ul class="prima-jp-controls">';
		$output .= '<li><div class="prima-seperator-first"></div></li>';
		$output .= '<li><div class="prima-seperator-second"></div></li>';
		$output .= '<li><a href="#" class="prima-jp-play" tabindex="1">play</a></li>';
		$output .= '<li><a href="#" class="prima-jp-pause" tabindex="1">pause</a></li>';
		$output .= '<li><a href="#" class="prima-jp-mute" tabindex="1">mute</a></li>';
		$output .= '<li><a href="#" class="prima-jp-unmute" tabindex="1">unmute</a></li>';
		$output .= '</ul>';
		$output .= '<div class="prima-jp-progress-container">';
		$output .= '<div class="prima-jp-progress">';
		$output .= '<div class="prima-jp-seek-bar">';
		$output .= '<div class="prima-jp-play-bar"></div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="prima-jp-volume-bar-container">';
		$output .= '<div class="prima-jp-volume-bar">';
		$output .= '<div class="prima-jp-volume-bar-value"></div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$prima_shortcodes_js['jplayer'] = "\n".'<script type="text/javascript" src="'.PRIMA_ADMIN_URI.'/js/jquery.jplayer.min.js"></script>';
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($){';
		$prima_shortcodes_scripts .= 'if($().jPlayer) {';
		$prima_shortcodes_scripts .= '$("#jquery_jplayer_'.$box_id.'").jPlayer({';
		$prima_shortcodes_scripts .= 'ready: function () {';
		$prima_shortcodes_scripts .= '$(this).jPlayer("setMedia", {';
		if ($mp3)
			$prima_shortcodes_scripts .= 'mp3: "'.$mp3.'",';
		if ($ogg)
			$prima_shortcodes_scripts .= 'oga: "'.$ogg.'",';
		$prima_shortcodes_scripts .= 'end: ""';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '},';
		$prima_shortcodes_scripts .= 'swfPath: "'.PRIMA_ADMIN_URI.'/js",';
		$prima_shortcodes_scripts .= 'cssSelector: {
			videoPlay: ".prima-jp-video-play",
			play: ".prima-jp-play",
			pause: ".prima-jp-pause",
			stop: ".prima-jp-stop",
			seekBar: ".prima-jp-seek-bar",
			playBar: ".prima-jp-play-bar",
			mute: ".prima-jp-mute",
			unmute: ".prima-jp-unmute",
			volumeBar: ".prima-jp-volume-bar",
			volumeBarValue: ".prima-jp-volume-bar-value",
			volumeMax: ".prima-jp-volume-max",
			currentTime: ".prima-jp-current-time",
			duration: ".prima-jp-duration",
			fullScreen: ".prima-jp-full-screen",
			restoreScreen: ".prima-jp-restore-screen",
			repeat: ".prima-jp-repeat",
			repeatOff: ".prima-jp-repeat-off",
			gui: ".prima-jp-gui",
			noSolution: ".prima-jp-no-solution"
		},';
		$prima_shortcodes_scripts .= 'cssSelectorAncestor: "#jp_interface_'.$box_id.'",';
		$prima_shortcodes_scripts .= 'supplied: "'.( $ogg ? 'oga, ' : '' ).( $mp3 ? 'mp3, ' : '' ).'all"';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '}';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}
add_shortcode('prima_video', 'prima_video_shortcodes');
function prima_video_shortcodes($atts, $content=null){
	global $prima_shortcodes_scripts, $prima_shortcodes_js;
	if ( !is_array($prima_shortcodes_js) ) $prima_shortcodes_js = array();
	extract(shortcode_atts(array(
		'width' => 640,
		'height' => 264,
		'poster' => null,
		'm4v' => null,
		'ogv' => null
	), $atts));
	$box_id = rand(1000, 9999);
	$output = '';
	if ( $m4v || $ogv ) {
		$output .= '<div id="jquery_jplayer_'.$box_id.'" class="prima-jp-jplayer prima-jp-jplayer-video" style="width:'.$width.'px;height:'.$height.'px;"></div>';
		$output .= '<div class="prima-jp-video-container" style="width:'.$width.'px;">';
		$output .= '<div class="prima-jp-video">';
		$output .= '<div class="prima-jp-type-single">';
		$output .= '<div id="jp_interface_'.$box_id.'" class="prima-jp-interface">';
		$output .= '<ul class="prima-jp-controls">';
		$output .= '<li><div class="prima-seperator-first"></div></li>';
		$output .= '<li><div class="prima-seperator-second"></div></li>';
		$output .= '<li><a href="#" class="prima-jp-play" tabindex="1">play</a></li>';
		$output .= '<li><a href="#" class="prima-jp-pause" tabindex="1">pause</a></li>';
		$output .= '<li><a href="#" class="prima-jp-mute" tabindex="1">mute</a></li>';
		$output .= '<li><a href="#" class="prima-jp-unmute" tabindex="1">unmute</a></li>';
		$output .= '</ul>';
		$output .= '<div class="prima-jp-progress-container">';
		$output .= '<div class="prima-jp-progress">';
		$output .= '<div class="prima-jp-seek-bar">';
		$output .= '<div class="prima-jp-play-bar"></div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="prima-jp-volume-bar-container">';
		$output .= '<div class="prima-jp-volume-bar">';
		$output .= '<div class="prima-jp-volume-bar-value"></div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$prima_shortcodes_js['jplayer'] = "\n".'<script type="text/javascript" src="'.PRIMA_ADMIN_URI.'/js/jquery.jplayer.min.js"></script>';
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($){';
		$prima_shortcodes_scripts .= 'if($().jPlayer) {';
		$prima_shortcodes_scripts .= '$("#jquery_jplayer_'.$box_id.'").jPlayer({';
		$prima_shortcodes_scripts .= 'ready: function () {';
		$prima_shortcodes_scripts .= '$(this).jPlayer("setMedia", {';
		if ($m4v)
			$prima_shortcodes_scripts .= 'm4v: "'.$m4v.'",';
		if ($ogv)
			$prima_shortcodes_scripts .= 'ogv: "'.$ogv.'",';
		if ($poster)
			$prima_shortcodes_scripts .= 'poster: "'.$poster.'",';
		$prima_shortcodes_scripts .= 'end: ""';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '},';
		$prima_shortcodes_scripts .= 'swfPath: "'.PRIMA_ADMIN_URI.'/js",';
		$prima_shortcodes_scripts .= 'cssSelector: {
			videoPlay: ".prima-jp-video-play",
			play: ".prima-jp-play",
			pause: ".prima-jp-pause",
			stop: ".prima-jp-stop",
			seekBar: ".prima-jp-seek-bar",
			playBar: ".prima-jp-play-bar",
			mute: ".prima-jp-mute",
			unmute: ".prima-jp-unmute",
			volumeBar: ".prima-jp-volume-bar",
			volumeBarValue: ".prima-jp-volume-bar-value",
			volumeMax: ".prima-jp-volume-max",
			currentTime: ".prima-jp-current-time",
			duration: ".prima-jp-duration",
			fullScreen: ".prima-jp-full-screen",
			restoreScreen: ".prima-jp-restore-screen",
			repeat: ".prima-jp-repeat",
			repeatOff: ".prima-jp-repeat-off",
			gui: ".prima-jp-gui",
			noSolution: ".prima-jp-no-solution"
		},';
		$prima_shortcodes_scripts .= 'cssSelectorAncestor: "#jp_interface_'.$box_id.'",';
		$prima_shortcodes_scripts .= 'supplied: "'.( $ogv ? 'ogv, ' : '' ).( $m4v ? 'm4v, ' : '' ).'all"';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '}';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}
