<?php

add_action( 'widgets_init', 'prima_register_form_widgets' );
function prima_register_form_widgets() {
	register_widget('Prima_FeedburnerForm_Widget');
}

class Prima_FeedburnerForm_Widget extends WP_Widget {
	function Prima_FeedburnerForm_Widget() {
		$widget_ops = array( 'classname' => 'prima_feedburnerform', 'description' => __('Display Feedburner subscription form', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-feedburnerform' );
		$this->WP_Widget( 'prima-feedburnerform', '::Prima - '.__('Feedburner Form', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		if ( $instance['id'] && $instance['intro_text'] )
			echo wpautop( $instance['intro_text'] );
		$attr = array( 'id' => $instance['id'], 'input_text' => $instance['input_text'], 'button_text' => $instance['button_text'], 'echo' => true );
		prima_feedburner_form( $attr );
		echo $after_widget;
	}
	function update( $new, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new['title'] );
		$instance['intro_text'] = strip_tags( $new['intro_text'] );
		$instance['id'] = strip_tags( $new['id'] );
		$instance['input_text'] = strip_tags( $new['input_text'] );
		$instance['button_text'] = strip_tags( $new['button_text'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'intro_text' => '', 'id' => '', 'input_text' => __( 'Enter your email address...', 'primathemes' ), 'button_text' => __( 'Subscribe', 'primathemes' ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Introduction text:', 'primathemes'), $this->get_field_id( 'intro_text' ), $this->get_field_name( 'intro_text' ), $instance['intro_text'] );
		prima_widget_input_text( __('Feedburner ID:', 'primathemes'), $this->get_field_id( 'id' ), $this->get_field_name( 'id' ), $instance['id'] );
		prima_widget_input_text( __('Input text:', 'primathemes'), $this->get_field_id( 'input_text' ), $this->get_field_name( 'input_text' ), $instance['input_text'] );
		prima_widget_input_text( __('Button text:', 'primathemes'), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), $instance['button_text'] );
	}
}

class Prima_SearchForm_Widget extends WP_Widget {
	function Prima_SearchForm_Widget() {
		$widget_ops = array( 'classname' => 'prima_searchform', 'description' => __('Display search form', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-searchform' );
		$this->WP_Widget( 'prima-searchform', '::Prima - '.__('Search Form', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$attr = array( 'search_text' => $instance['search_text'], 'button_text' => $instance['button_text'], 'echo' => true );
		prima_search_form( $attr );
		echo $after_widget;
	}
	function update( $new, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new['title'] );
		$instance['search_text'] = strip_tags( $new['search_text'] );
		$instance['button_text'] = strip_tags( $new['button_text'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'search_text' => __('Search this website&hellip;', 'primathemes'), 'button_text' => __( 'Search', 'primathemes' ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Input text:', 'primathemes'), $this->get_field_id( 'search_text' ), $this->get_field_name( 'search_text' ), $instance['search_text'] );
		prima_widget_input_text( __('Button text:', 'primathemes'), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), $instance['button_text'] );
	}
}
