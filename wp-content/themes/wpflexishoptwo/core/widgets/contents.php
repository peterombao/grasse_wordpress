<?php

add_action( 'widgets_init', 'prima_register_content_widgets' );
function prima_register_content_widgets() {
	register_widget('Prima_RecentPosts_Widget');
	register_widget('Prima_RecentComments_Widget');
}

class Prima_RecentPosts_Widget extends WP_Widget {
	function Prima_RecentPosts_Widget() {
		$widget_ops = array( 'classname' => 'prima_recent_posts', 'description' => __('Display most recent posts', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-recent-posts' );
		$this->WP_Widget( 'prima-recent-posts', '::Prima - '.__('Recent Posts', 'primathemes'), $widget_ops, $control_ops );
		$this->alt_option_name = 'prima_recent_posts';
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$number = (int)$instance['number'];
		$image = $instance['image'] ? '1' : '0';
		$image_width = (int)$instance['image_width'];
		$image_height = (int)$instance['image_height'];
		$postmeta = $instance['postmeta'] ? '1' : '0';
		$postexcerpt = $instance['postexcerpt'] ? '1' : '0';
		$excerpt_length = (int)$instance['excerpt_length'];
 		$output = '';
		$output .= $before_widget;
		if ( $title ) $output .= $before_title . $title . $after_title;
		$args = array(
			'post_type'	=> 'post',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' => $number
		);
		query_posts($args);
		if (have_posts()) :
			$output .= '<ul>';
		while (have_posts()) : the_post();
			$output .= '<li>';
			if ( $image )
				$output .= prima_get_image( array ( 'width' => $image_width, 'height' => $image_height ) );
			$output .= '<h3><a href="'.get_permalink().'" title="" rel="bookmark">'.get_the_title().'</a></h3>';
			if ( $postmeta )
				$output .= sprintf( '<p class="postmeta">'.__( 'Posted <span class="metadate">on %1$s</span>', 'primathemes' ).'</p>', get_the_date(), '<a class="url fn n" href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'" title="">'.get_the_author_meta( 'display_name' ).'</a>' );
			if ( $postexcerpt )
				$output .= prima_get_excerpt_limit($excerpt_length,false);
			$output .= '</li>';
		endwhile;
			$output .= '</ul>';
		endif;
		wp_reset_query();
		$output .= $after_widget;
		echo $output;
	}
	function update( $new, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new['title'] );
		$instance['number'] = ( (int)$new['number'] > 0 ? (int)$new['number'] : '3' );
		$instance['image'] = ( isset( $new['image'] ) ? 1 : 0 );
		$instance['image_width'] = ( (int)$new['image_width'] > 0 ? (int)$new['image_width'] : '50' );
		$instance['image_height'] = ( (int)$new['image_height'] > 0 ? (int)$new['image_height'] : '50' );
		$instance['postmeta'] = ( isset( $new['postmeta'] ) ? 1 : 0 );
		$instance['postexcerpt'] = ( isset( $new['postexcerpt'] ) ? 1 : 0 );
		$instance['excerpt_length'] = ( (int)$new['excerpt_length'] > 0 ? (int)$new['excerpt_length'] : '75' );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => 'Recent Posts', 'number' => 3, 'image' => true, 'image_width' => 50, 'image_height' => 50, 'postmeta' => true, 'postexcerpt' => true, 'excerpt_length' => 75 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text_small( __('Number of posts to show:', 'primathemes'), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
		prima_widget_input_checkbox( __( 'Show image', 'primathemes' ), $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), checked( $instance['image'], true, false ) );
		prima_widget_input_text_small( __('Image width (px):', 'primathemes'), $this->get_field_id( 'image_width' ), $this->get_field_name( 'image_width' ), $instance['image_width'] );
		prima_widget_input_text_small( __('Image height (px):', 'primathemes'), $this->get_field_id( 'image_height' ), $this->get_field_name( 'image_height' ), $instance['image_height'] );
		prima_widget_input_checkbox( __( 'Show post meta', 'primathemes' ), $this->get_field_id( 'postmeta' ), $this->get_field_name( 'postmeta' ), checked( $instance['postmeta'], true, false ) );
		prima_widget_input_checkbox( __( 'Show post excerpt', 'primathemes' ), $this->get_field_id( 'postexcerpt' ), $this->get_field_name( 'postexcerpt' ), checked( $instance['postexcerpt'], true, false ) );
		prima_widget_input_text_small( __('Post excerpt length:', 'primathemes'), $this->get_field_id( 'excerpt_length' ), $this->get_field_name( 'excerpt_length' ), $instance['excerpt_length'] );
	}
}

class Prima_RecentComments_Widget extends WP_Widget {
	function Prima_RecentComments_Widget() {
		$widget_ops = array( 'classname' => 'prima_recent_comments', 'description' => __('Display most recent comments', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-recent-comments' );
		$this->WP_Widget( 'prima-recent-comments', '::Prima - '.__('Recent Comments', 'primathemes'), $widget_ops, $control_ops );
		$this->alt_option_name = 'prima_recent_comments';
		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}
	function flush_widget_cache() {
		wp_cache_delete('prima_recent_comments', 'widget');
	}
	function widget( $args, $instance ) {
		global $comments, $comment;
		$cache = wp_cache_get('prima_recent_comments', 'widget');
		if ( ! is_array( $cache ) )
			$cache = array();
		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$number = (int)$instance['number'];
		$avatar = $instance['avatar'] ? '1' : '0';
		$avatar_size = (int)$instance['avatar_size'];
		$excerpt_length = (int)$instance['excerpt_length'];
 		$output = '';
		$output .= $before_widget;
		if ( $title ) $output .= $before_title . $title . $after_title;
		$comments = get_comments(array( 'number' => $number, 'status' => 'approve', 'type' => 'comment' ));
		if ($comments) {
			$output .= '<ul>';
			foreach ($comments as $comment) :
				$comment_link = get_comment_link($comment->comment_ID);
				$output .= ( $avatar ? '<li class="comment-with-avatar">' : '<li class="group">' );
				if ( $avatar ) $output .= '<a href="'. $comment_link.'">'.get_avatar($comment, $size=$avatar_size).'</a>';
				$output .= '<a href="'.$comment_link.'"><strong>'.$comment->comment_author.'</strong>:</a> '.substr(get_comment_excerpt( $comment->comment_ID ), 0, $excerpt_length).'&hellip;';
				$output .= '</li>';
			endforeach;
			$output .= '</ul>';
		}
		else {
			$output .= __( 'No comments were found', 'primathemes' );
		}
		$output .= $after_widget;
		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('prima_recent_comments', $cache, 'widget');
	}
	function update( $new, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new['title'] );
		$instance['number'] = ( (int)$new['number'] > 0 ? (int)$new['number'] : '3' );
		$instance['avatar'] = ( isset( $new['avatar'] ) ? 1 : 0 );
		$instance['avatar_size'] = ( (int)$new['avatar_size'] > 0 ? (int)$new['avatar_size'] : '48' );
		$instance['excerpt_length'] = ( (int)$new['excerpt_length'] > 0 ? (int)$new['excerpt_length'] : '75' );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => 'Recent Comments', 'number' => 3, 'avatar' => true, 'avatar_size' => 48, 'excerpt_length' => 75 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text_small( __('Number of comments to show:', 'primathemes'), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
		prima_widget_input_checkbox( __( 'Show avatar', 'primathemes' ), $this->get_field_id( 'avatar' ), $this->get_field_name( 'avatar' ), checked( $instance['avatar'], true, false ) );
		prima_widget_input_text_small( __('Avatar size (px):', 'primathemes'), $this->get_field_id( 'avatar_size' ), $this->get_field_name( 'avatar_size' ), $instance['avatar_size'] );
		prima_widget_input_text_small( __('Comment excerpt length:', 'primathemes'), $this->get_field_id( 'excerpt_length' ), $this->get_field_name( 'excerpt_length' ), $instance['excerpt_length'] );
	}
}

