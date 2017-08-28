<?php

add_filter( 'woocommerce_attribute_show_in_nav_menus', 'prima_attribute_show_in_nav_menus' );
function prima_attribute_show_in_nav_menus( $show ) {
	return true;
}

add_action('product_tag_add_form_fields', 'woocommerce_add_category_thumbnail_field');
add_action('product_tag_edit_form_fields', 'woocommerce_edit_category_thumbnail_field', 10,2);
add_filter('manage_edit-product_tag_columns', 'woocommerce_product_cat_columns');
add_filter('manage_product_tag_custom_column', 'woocommerce_product_cat_column', 10, 3);	

global $woocommerce;
$attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
if ( $attribute_taxonomies ) :
	foreach ($attribute_taxonomies as $tax) :
		add_action("{$woocommerce->attribute_taxonomy_name($tax->attribute_name)}_add_form_fields", 'woocommerce_add_category_thumbnail_field');
		add_action("{$woocommerce->attribute_taxonomy_name($tax->attribute_name)}_edit_form_fields", 'woocommerce_edit_category_thumbnail_field', 10,2);
		add_filter("manage_edit-{$woocommerce->attribute_taxonomy_name($tax->attribute_name)}_columns", 'woocommerce_product_cat_columns');
		add_filter("manage_{$woocommerce->attribute_taxonomy_name($tax->attribute_name)}_custom_column", 'woocommerce_product_cat_column', 10, 3);	
	endforeach;
endif;