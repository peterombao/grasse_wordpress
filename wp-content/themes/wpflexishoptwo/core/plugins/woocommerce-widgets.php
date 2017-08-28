<?php

add_action( 'widgets_init', 'prima_register_woocommerce_widgets' );
function prima_register_woocommerce_widgets() {
	register_widget('Prima_ProductAttributes_Widget');
}

class Prima_ProductAttributes_Widget extends WP_Widget {
	function Prima_ProductAttributes_Widget() {
		$widget_ops = array( 'classname' => 'prima_product_attributes', 'description' => __('Display product attribute lists / top level product categories', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-product-attributes' );
		$this->WP_Widget( 'prima-product-attributes', '::Prima - '.__('Product Attributes', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$cat_args = array(
			'taxonomy' => $instance['taxonomy'],
			'title_li' => '',
			'show_count' => $instance['count'],
			'depth' => 1
		);
		if ( $instance['orderby'] == 'order' )
			$cat_args['menu_order'] = 'asc';
		else
			$cat_args['orderby'] = $instance['orderby'];

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		echo '<ul>';
		wp_list_categories( $cat_args );
		echo '</ul>';
		echo $after_widget;
	}
	function update( $new, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new['title']);
		$instance['taxonomy'] = strip_tags($new['taxonomy']);
		$instance['orderby'] = strip_tags($new['orderby']);
		$instance['count'] = !empty($new['count']) ? 1 : 0;
		// $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'taxonomy' => '', 'orderby' => 'order', 'count' => false, 'dropdown' => false );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		$taxonomy_opt = array();
		global $woocommerce;
		$attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
		if ( $attribute_taxonomies ) :
			foreach ($attribute_taxonomies as $tax) :
				if (taxonomy_exists( $woocommerce->attribute_taxonomy_name($tax->attribute_name))) :
					$taxonomy_opt[$woocommerce->attribute_taxonomy_name($tax->attribute_name)] = ucwords($tax->attribute_name);
				endif;
			endforeach;
		endif;
		$taxonomy_opt['product_cat'] = __('Product Cat (Top Level)', 'primathemes');
		prima_widget_select_single( __( 'Attribute:', 'primathemes' ), $this->get_field_id( 'taxonomy' ), $this->get_field_name( 'taxonomy' ), $instance['taxonomy'], $taxonomy_opt, false );
		$orderby_opt = array('order' => __('Category/Attribute Order', 'primathemes'), 'name' => __('Category/Attribute Name', 'primathemes') );
		prima_widget_select_single( __( 'Order by:', 'primathemes' ), $this->get_field_id( 'orderby' ), $this->get_field_name( 'orderby' ), $instance['orderby'], $orderby_opt, false );
		prima_widget_input_checkbox( __( 'Show post counts', 'primathemes' ), $this->get_field_id( 'count' ), $this->get_field_name( 'count' ), checked( $instance['count'], true, false ) );
		// prima_widget_input_checkbox( __( 'Show as dropdown', 'primathemes' ), $this->get_field_id( 'dropdown' ), $this->get_field_name( 'dropdown' ), checked( $instance['dropdown'], true, false ) );
	}
}
