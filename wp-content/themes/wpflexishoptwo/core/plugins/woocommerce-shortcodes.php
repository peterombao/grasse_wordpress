<?php 
/* Advanced WooCommerce shortcodes */

add_shortcode('prima_products', 'prima_products_shortcodes');
add_shortcode('prima_featured_products', 'prima_products_shortcodes');
add_shortcode('prima_onsale_products', 'prima_products_shortcodes');
add_shortcode('prima_bestsellers_products', 'prima_products_shortcodes');
add_shortcode('prima_products_in_category', 'prima_products_shortcodes');
add_shortcode('prima_products_in_tag', 'prima_products_shortcodes');
add_shortcode('prima_products_in_attribute', 'prima_products_shortcodes');
add_shortcode('prima_products_with_skus', 'prima_products_shortcodes');
add_shortcode('prima_products_with_ids', 'prima_products_shortcodes');
add_shortcode('prima_product_categories', 'prima_product_taxonomies_shortcodes');
add_shortcode('prima_product_attributes', 'prima_product_taxonomies_shortcodes');

function prima_products_shortcodes($atts, $content=null, $code=""){
	global $woocommerce, $prima_products_atts;
	
	$default_atts = array(
		'title' => '', 
		'numbers' => '4', 
		'columns' => '4',
		'orderby' => 'date', 
		'order' => 'desc',
		'image_width' => $woocommerce->get_image_size('shop_catalog_image_width'),
		'image_height' => $woocommerce->get_image_size('shop_catalog_image_height'),
		'image_crop' => ( get_option('woocommerce_catalog_image_crop')==1 ? 'yes' : 'no' ),
		'product_image' => 'yes',
		'product_saleflash' => 'yes',
		'product_title' => 'yes',
		'product_price' => 'yes',
		'product_button' => 'no',
	);
	
	$query_args = array(
		'post_type'	=> 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'meta_query' => array(
			array(
				'key' => '_visibility',
				'value' => array('catalog', 'visible'),
				'compare' => 'IN'
			)
		)
	);
	
	switch($code){
		case 'prima_products':
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
		break;
		case 'prima_featured_products':
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			$query_args['meta_query'][] = array(
				'key' => '_featured',
				'value' => 'yes'
			);
		break;
		case 'prima_onsale_products':
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			$meta_query = array();
		    $meta_query[] = array(
		    	'key' => '_sale_price',
		        'value' 	=> 0,
				'compare' 	=> '>',
				'type'		=> 'NUMERIC'
		    );
	
			$on_sale = get_posts(array(
				'post_type' 		=> array('product', 'product_variation'),
				'posts_per_page' 	=> -1,
				'post_status' 		=> 'publish',
				'meta_query' 		=> $meta_query,
				'fields' 			=> 'id=>parent'
			));
			
			$product_ids_on_sale = array_unique(array_merge(array_values($on_sale), array_keys($on_sale)));
			$product_ids_on_sale[] = 0;
			
			$query_args['post__in'] = $product_ids_on_sale;

			if (get_option('woocommerce_hide_out_of_stock_items')=='yes') :
				$query_args['meta_query'][] = array(
					'key' 		=> '_stock_status',
					'value' 	=> 'instock',
					'compare' 	=> '='
				);
			endif;
		break;
		case 'prima_bestsellers_products':
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			$query_args['meta_key'] = 'total_sales';
			$query_args['orderby'] = 'meta_value';
		break;
		case 'prima_products_in_category':
			$default_atts['category'] = '';
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			if ( !$atts['category'] ) return '<p class="info">'.__('No defined product category slug in the current shortcode.', 'primathemes').'</p>';
				
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'terms' => array( esc_attr($atts['category']) ),
					'field' => 'slug',
					'operator' => 'IN'
				)
			);
		break;
		case 'prima_products_in_tag':
			$default_atts['tag'] = '';
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			if ( !$atts['tag'] ) return '<p class="info">'.__('No defined product tag slug in the current shortcode.', 'primathemes').'</p>';
				
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_tag',
					'terms' => array( esc_attr($atts['tag']) ),
					'field' => 'slug',
					'operator' => 'IN'
				)
			);
		break;
		case 'prima_products_in_attribute':
			$atts_orig = $atts;
			
			$default_atts['attribute'] = '';
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			if ( !$atts['attribute'] ) return '<p class="info">'.__('No defined product attribute in the current shortcode.', 'primathemes').'</p>';
			
			$taxonomy = $woocommerce->attribute_taxonomy_name($atts['attribute']);
			if ( !taxonomy_exists($taxonomy) ) return '<p class="info">'.sprintf( __('There is no "%s" product attribute in the current shop.', 'primathemes'), $atts['attribute']).'</p>';
			
			if ( !isset($atts_orig[$atts['attribute']]) ) return '<p class="info">'.sprintf( __('There is no defined "%s" product attribute in the current shop.', 'primathemes'), $atts['attribute']).'</p>';
			
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'terms' => array( esc_attr($atts_orig[$atts['attribute']]) ),
					'field' => 'slug',
					'operator' => 'IN'
				)
			);
		break;
		case 'prima_products_with_skus':
			$default_atts['numbers'] = -1;
			$default_atts['skus'] = '';
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			if ( !$atts['skus'] ) return '<p class="info">'.__('No defined SKUs in the current shortcode.', 'primathemes').'</p>';
				
			$skus = explode(',', $atts['skus']);
			array_walk($skus, create_function('&$val', '$val = trim($val);'));
			$query_args['meta_query'][] = array(
				'key' => '_sku',
				'value' => $skus,
				'compare' => 'IN'
			);
		break;
		case 'prima_products_with_ids':
			$default_atts['numbers'] = -1;
			$default_atts['ids'] = '';
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_products_atts = $atts;
			
			if ( !$atts['ids'] ) return '<p class="info">'.__('No defined IDs in the current shortcode.', 'primathemes').'</p>';
				
			$ids = explode(',', $atts['ids']);
			array_walk($ids, create_function('&$val', '$val = trim($val);'));
			$query_args['post__in'] = $ids;
		break;
	}
			
	$query_args['posts_per_page'] = $atts['numbers'];
	$query_args['orderby'] = $atts['orderby'];
	$query_args['order'] = $atts['order'];
	
	query_posts($query_args);
	ob_start();
	woocommerce_get_template_part( 'loop-shop', 'shortcode' );
	wp_reset_query();
	
	return ob_get_clean();
}


function prima_product_taxonomies_shortcodes($atts, $content=null, $code=""){
	global $woocommerce, $prima_productcats_atts, $product_categories;
	
	$default_atts = array(
		'title' => '', 
		'hide_empty' => 'yes',
		'numbers' => '', 
		'columns' => '4',
		'orderby' => '', 
		'order' => '',
		'parent' => '0',
		'image_width' => $woocommerce->get_image_size('shop_catalog_image_width'),
		'image_height' => $woocommerce->get_image_size('shop_catalog_image_height'),
		'image_crop' => ( get_option('woocommerce_catalog_image_crop')==1 ? 'yes' : 'no' ),
		'show_image' => 'yes',
		'show_title' => 'yes',
		'show_count' => 'yes',
	);
	
	$cat_args = array();
	$cat_args['menu_order'] = 'ASC';
	$cat_args['pad_counts'] = 1;
	
	switch($code){
		case 'prima_product_categories':
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_productcats_atts = $atts;
			
			$cat_args['taxonomy'] = 'product_cat';
			if ( $atts['parent'] ) $cat_args['parent'] = $atts['parent'];
		break;
		case 'prima_product_attributes':
			$default_atts['attribute'] = '';
			$atts = shortcode_atts( $default_atts, $atts);
			$prima_productcats_atts = $atts;
			
			if ( !$atts['attribute'] ) return '<p class="info">'.__('No defined product attribute taxonomy in the current shortcode.', 'primathemes').'</p>';
			
			$taxonomy = $woocommerce->attribute_taxonomy_name($atts['attribute']);
			if ( !taxonomy_exists($taxonomy) ) return '<p class="info">'.sprintf( __('There is no "%s" product attribute taxonomy in the current shop.', 'primathemes'), $atts['attribute']).'</p>';

			$cat_args['taxonomy'] = $taxonomy;
		break;
	}

	$cat_args['hide_empty'] = ( $atts['hide_empty'] == 'yes' ? true : false );
	if ( $atts['numbers'] ) $cat_args['number'] = $atts['numbers'];
	if ( $atts['orderby'] ) $cat_args['orderby'] = $atts['orderby'];
	if ( $atts['order'] ) $cat_args['order'] = $atts['order'];
	
	$product_categories = get_categories( $cat_args );

	if ($product_categories) :
	
		ob_start();
		woocommerce_get_template_part( 'loop-product-cats', 'shortcode' );
		
		return ob_get_clean();
	endif;
}