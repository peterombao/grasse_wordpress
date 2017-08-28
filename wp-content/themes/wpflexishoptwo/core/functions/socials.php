<?php

/**
* Twitter Functions 
* 
* Credits:
* - Genesis Framework by StudioPress http://studiopress.com
* - Snipe.net http://www.snipe.net/2009/09/php-twitter-clickable-links/ 
**/

function prima_twitter( $args = array() ) {
	echo prima_get_twitter( $args );
}

function prima_get_twitter( $args = array() ) {
	$defaults = array(
		'username' => 'primathemes', 
		'limit' => 3, 
		'interval' => 600, 
	);
	$args = apply_filters( 'prima_twitter_args', $args );
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	$output = '';
	$output .= '<ul>';
	$tweets = get_transient( $username . '-' . $limit . '-' . $interval );
	if ( ! $tweets ) {
		$twitter = wp_remote_retrieve_body(
			wp_remote_request(
				sprintf( 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=%s&count=%s&trim_user=1', $username, $limit ),
				array( 'timeout' => 60, )
			)
		);
		$json = json_decode( $twitter );
		if ( !$twitter )
			$tweets[] = '<li>' . __( 'The Twitter API is taking too long to respond. Please try again later.', 'primathemes' ) . '</li>';
		elseif ( is_wp_error( $twitter ) )
			$tweets[] = '<li>' . __( 'There was an error while attempting to contact the Twitter API. Please try again.', 'primathemes' ) . '</li>';
		elseif ( is_object( $json ) && $json->error )
			$tweets[] = '<li>' . __( 'The Twitter API returned an error while processing your request. Please try again.', 'primathemes' ) . '</li>';
		else {
			foreach ( (array) $json as $tweet ) {
				$timeago = sprintf( __( 'about %s ago', 'primathemes' ), human_time_diff( strtotime( $tweet->created_at ) ) );
				$timeago_link = sprintf( '<a href="%s" rel="nofollow">%s</a>', esc_url( sprintf( 'http://twitter.com/%s/status/%s', $username, $tweet->id_str ) ), esc_html( $timeago ) );
				$tweets[] = '<li>' . prima_tweet_linkify( $tweet->text ) . ' <span style="font-size: 85%;">' . $timeago_link . '</span></li>' . "\n";
			}
			$tweets = array_slice( (array) $tweets, 0, (int) $limit );
			$time = ( absint( $interval ) * 60 );
			set_transient( $username.'-'.$limit.'-'.$interval, $tweets, $time );
		}
	}
	foreach( (array) $tweets as $tweet )
		$output .= $tweet;

	$output .= '</ul>';
	return $output;
}

function prima_tweet_linkify($tweet) {
	$tweet = preg_replace_callback( '~(?<!\w)(https?://\S+\w|www\.\S+\w|@\w+|#\w+)|[<>&]~u', 'prima_tweet_clickable', html_entity_decode($tweet, ENT_QUOTES, 'UTF-8'));
	return $tweet;
}
function prima_tweet_clickable($m) {
	$m = htmlspecialchars($m[0]);
	if ($m[0] === '#') {
		$m = substr($m, 1);
		return "<a href='http://twitter.com/search?q=%23$m'>#$m</a>";
	} 
	elseif ($m[0] === '@') {
		$m = substr($m, 1);
		return "@<a href='http://twitter.com/$m'>$m</a>";
	} 
	elseif ($m[0] === 'w') {
		return "<a href='http://$m'>$m</a>";
	}
	elseif ($m[0] === 'h') {
		return "<a href='$m'>$m</a>";
	} 
	else {
		return $m;
	}
}