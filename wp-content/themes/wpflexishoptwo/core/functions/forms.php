<?php 

function prima_search_form( $args = array() ) {
	$defaults = array(
		'search_text' => __('Search this website&hellip;', 'primathemes'),
		'button_text' => __( 'Search', 'primathemes' ),
		'echo' => false
	);
	$args = apply_filters( 'prima_search_form_args', $args );
	$args = wp_parse_args( $args, $defaults );
	$search_text = esc_js( $args['search_text'] );
	$query_text = get_search_query() ? esc_js( get_search_query() ) : '';
	$button_text = esc_attr( $args['button_text'] );
	$searchform = '
		<form method="get" class="searchform" action="' . home_url() . '/" >
			<input type="text" value="'.$query_text.'" name="s" class="searchtext" placeholder="'.$search_text.'" />
			<input type="submit" class="searchsubmit" value="'. $button_text .'" />
		</form>
	';
	if ($args['echo']) echo $searchform;
	else return $searchform;
}

function prima_feedburner_form( $args = array() ) {
	$defaults = array(
		'id' => '', 
		'language' => __( 'en_US', 'primathemes' ), 
		'input_text' => __( 'Enter your email address...', 'primathemes' ), 
		'button_text' => __( 'Subscribe', 'primathemes' ),
		'echo' => false
	);
	$args = apply_filters( 'prima_search_form_args', $args );
	$args = wp_parse_args( $args, $defaults );
	$id = esc_js( $args['id'] );
	$language = esc_attr( $args['language'] );
	$input_text = esc_attr( $args['input_text'] );
	$button_text = esc_attr( $args['button_text'] );
	$onsubmit = " onsubmit=\"window.open('http://feedburner.google.com/fb/a/mailverify?uri=$id', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true\" ";
	if ( $id ) {
		$feedburnerform = '
			<form class="feedburner-subscribe" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" '. $onsubmit .'>
				<input type="text" value="" class="feedburnertext" placeholder="'.$input_text.'" />
				<input type="hidden" value="'. $id .'" name="uri"/>
				<input type="hidden" name="loc" value="'. $language .'"/>
				<input type="submit" class="feedburnersubmit" value="'. $button_text .'" />
			</form>
		';
	}
	else {
		$feedburnerform = '<p>'.__ ( 'No Feedburner ID was available', 'primathemes' ).'</p>';
	}
	if ($args['echo']) echo $feedburnerform;
	else return $feedburnerform;
}