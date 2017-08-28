<?php

define('EV_THEME_ROOT', get_theme_root());

if(!is_admin()) {
	//require( EV_THEME_ROOT.'/wpflexishoptwo/functions.php' );
}

function get_categ_id($post_id){
    $terms = get_the_terms( $post_id, 'product_cat' );						

    if ( $terms && ! is_wp_error( $terms ) ) {     

    	$draught_links = array();    

    	foreach ( $terms as $term ) {

    		$draught_links[] = $term->term_id;

    	}      						

    	$catID = join( ", ", $draught_links );

      

    }

    return $catID;

}

function get_page_id($page_name){
	global $wpdb;
	$page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
	return $page_name;
}


?>