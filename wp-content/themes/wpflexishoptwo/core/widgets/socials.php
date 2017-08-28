<?php

add_action( 'widgets_init', 'prima_register_social_widgets' );
function prima_register_social_widgets() {
	register_widget('Prima_Twitter_Widget');
	register_widget('Prima_Flickr_Widget');
}

class Prima_Twitter_Widget extends WP_Widget {
	function Prima_Twitter_Widget() {
		$widget_ops = array( 'classname' => 'prima_twitter', 'description' => __('Display most recent Twitter feed', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-twitter' );
		$this->WP_Widget( 'prima-twitter', '::Prima - '.__('Twitter Feed', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$attr = array( 'username' => $instance['usernames'], 'limit' => $instance['limit'], 'interval' => $instance['interval'] );
		prima_twitter( $attr );
		echo $after_widget;
	}
	function update( $new, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new['title'] );
		$instance['usernames'] = strip_tags( $new['usernames'] );
		$instance['limit'] = strip_tags( $new['limit'] );
		$instance['interval'] = strip_tags( $new['interval'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'usernames' => 'primathemes', 'limit' => '3', 'interval' => '10' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Twitter username:', 'primathemes'), $this->get_field_id( 'usernames' ), $this->get_field_name( 'usernames' ), $instance['usernames'] );
		prima_widget_input_text( __('Number of tweets:', 'primathemes'), $this->get_field_id( 'limit' ), $this->get_field_name( 'limit' ), $instance['limit'] );
		$interval = array('5' => __('5 minutes', 'primathemes'), '10' => __('10 minutes', 'primathemes'), '15' => __('15 minutes', 'primathemes'), '30' => __('30 minutes', 'primathemes'),  '60' => __('1 hour', 'primathemes'),
'120' => __('1 hour', 'primathemes'), '240' => __('4 hour', 'primathemes'), '720' => __('12 hour', 'primathemes'), '1440' => __('24 hours', 'primathemes') );
		prima_widget_select_single( __( 'Load new Tweets every:', 'primathemes' ), $this->get_field_id( 'interval' ), $this->get_field_name( 'interval' ), $instance['interval'], $interval, false );
	}
}

class Prima_Flickr_Widget extends WP_Widget {
	function Prima_Flickr_Widget() {
		$widget_ops = array( 'classname' => 'prima_flickr', 'description' => __('Display photos from Flickr', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-flickr' );
		$this->WP_Widget( 'prima-flickr', '::Prima - '.__('Flickr', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$source = $instance['source'];
		$user_ID = $instance['user_ID'];
		$group_ID = $instance['user_ID'];
		$set_ID = $instance['set_ID'];
		$tag = $instance['tag'];
		$display = $instance['display'];
		$number = $instance['number'];
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$params = 'count='.$number.'.&amp;display='.$display.'&amp;size=s&amp;layout=x';
		if ($source=='user')
			$params .= '&amp;source=user&amp;user='.$user_ID;
		elseif ($source=='group')
			$params .= '&amp;source=group&amp;group='.$group_ID;
		elseif ($source=='user_set')
			$params .= '&amp;source=user_set&amp;set='.$set_ID;
		elseif ($source=='all_tag')
			$params .= '&amp;source=all_tag&amp;tag='.$tag;
		echo '<div id="prima-flickr-wrapper" class="group"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?'.$params.'"></script></div>';
		echo $after_widget;
	}
	function update( $new, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new['title'] );
		$instance['source'] = strip_tags( $new['source'] );
		$instance['user_ID'] = strip_tags( $new['user_ID'] );
		$instance['group_ID'] = strip_tags( $new['group_ID'] );
		$instance['set_ID'] = strip_tags( $new['set_ID'] );
		$instance['tag'] = strip_tags( trim($new['tag']) );
		$instance['display'] = strip_tags( $new['display'] );
		$instance['number'] = (int)$new['number'];
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => __('Flickr Photos', 'primathemes'), 'source' => 'user', 'user_ID' => '', 'group_ID' => '', 'set_ID' => '', 'tag' => '', 'display' => 'latest', 'number' => 10 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		$source_opt = array('user' => __('User', 'primathemes'), 'group' => __('Group', 'primathemes'), 'user_set' => __('User Set', 'primathemes'), 'all_tag' => __('Tag', 'primathemes'));
		prima_widget_select_single( __( 'Source:', 'primathemes' ), $this->get_field_id( 'source' ), $this->get_field_name( 'source' ), $instance['source'], $source_opt, false );
		prima_widget_input_text( __('User ID:', 'primathemes'), $this->get_field_id( 'user_ID' ), $this->get_field_name( 'user_ID' ), $instance['user_ID'] );
		echo '<p><small>'.__('* find your user ID using', 'primathemes').' <a href="ht'.'tp://www.idgettr.com" target="_blank">idGettr</a></small></p>';
		prima_widget_input_text( __('Group ID:', 'primathemes'), $this->get_field_id( 'group_ID' ), $this->get_field_name( 'group_ID' ), $instance['group_ID'] );
		prima_widget_input_text( __('Set ID:', 'primathemes'), $this->get_field_id( 'set_ID' ), $this->get_field_name( 'set_ID' ), $instance['set_ID'] );
		prima_widget_input_text( __('Tag (separated by comma):', 'primathemes'), $this->get_field_id( 'tag' ), $this->get_field_name( 'tag' ), $instance['tag'] );
		$display_opt = array('latest' => __('Latest', 'primathemes'), 'random' => __('Random', 'primathemes'));
		prima_widget_select_single( __( 'Display:', 'primathemes' ), $this->get_field_id( 'display' ), $this->get_field_name( 'display' ), $instance['display'], $display_opt, false );
		prima_widget_input_text_small( __('Number of photos (from 1 to 10):', 'primathemes'), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
	}
}
