<?php

add_filter( 'widget_text', 'do_shortcode' );

function prima_page_title( $before='', $after='', $echo = true ) {
	global $wp_query;
	$pagetitle = '';
	if ( is_404() )
		$pagetitle = __('404 - Not Found :(', 'primathemes');
	elseif ( is_search() )
		$pagetitle = sprintf( __( 'Search Results for "%1$s"', 'primathemes' ), esc_attr( get_search_query() ) );
	elseif ( is_home() && get_option('show_on_front') != 'page' ) {
		$pagetitle = get_bloginfo( 'name' );
	}
	elseif ( is_home() && get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) {
		$post_id = $wp_query->get_queried_object_id();
		$pagetitle = get_post_field( 'post_title', $post_id );
	}
	elseif ( is_author() )
		$pagetitle = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
	elseif ( is_date() ) {
		if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
			$pagetitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'g:i a', $domain ) ) );
		elseif ( get_query_var( 'minute' ) )
			$pagetitle = sprintf( __( 'Archive for minute %1$s', $domain ), get_the_time( __( 'i', $domain ) ) );
		elseif ( get_query_var( 'hour' ) )
			$pagetitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'g a', $domain ) ) );
		elseif ( is_day() )
			$pagetitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'F jS, Y', $domain ) ) );
		elseif ( get_query_var( 'w' ) )
			$pagetitle = sprintf( __( 'Archive for week %1$s of %2$s', $domain ), get_the_time( __( 'W', $domain ) ), get_the_time( __( 'Y', $domain ) ) );
		elseif ( is_month() )
			$pagetitle = sprintf( __( 'Archive for %1$s', $domain ), single_month_title( ' ', false) );
		elseif ( is_year() )
			$pagetitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'Y', $domain ) ) );
	}
	elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
		$post_type = get_post_type_object( get_query_var( 'post_type' ) );
		$pagetitle = $post_type->labels->name;
	}
	elseif ( is_category() || is_tag() || is_tax() ) {
		$term = $wp_query->get_queried_object();
		$pagetitle = $term->name;
	}
	elseif ( is_singular() ) {
		$post_id = $wp_query->get_queried_object_id();
		$pagetitle = get_post_field( 'post_title', $post_id );
	}
	if ( $pagetitle )
		$pagetitle = $before.$pagetitle.$after;
	if ( $echo )
		echo $pagetitle;
	else
		return $pagetitle;
}

function prima_page_tagline( $before='', $after='', $echo = true ) {
	global $wp_query;
	$description = '';
	if ( is_404() )
		$description = __('Sorry, but the page you were trying to view does not exist.', 'primathemes');
	elseif ( is_home() && get_option('show_on_front') != 'page' ) {
		$description = get_bloginfo( 'description' );
	}
	elseif ( is_home() && get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) {
		$post_id = $wp_query->get_queried_object_id();
		$description = get_metadata( 'post', $post_id, '_prima_tagline', true );
	}
	elseif ( is_archive() ) {
		if ( is_author() )
			$description = get_the_author_meta( 'description', get_query_var( 'author' ) );
		elseif ( is_category() || is_tag() || is_tax() )
			$description = term_description( '', get_query_var( 'taxonomy' ) );
		/*
		elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
			$description = $post_type->description;
		}
		*/
	}
	elseif ( is_singular() ) {
		$post_id = $wp_query->get_queried_object_id();
		$description = get_metadata( 'post', $post_id, '_prima_tagline', true );
	}
	$description = strip_tags($description);
	if ( $description )
		$description = $before.$description.$after;
	if ( $echo )
		echo $description;
	else
		return $description;
}

function prima_post_tagline( $before='', $after='', $echo = true ) {
	global $wp_query;
	$description = '';
	$description = prima_get_post_meta( "_prima_tagline" );
	$description = strip_tags($description);
	if ( $description )
		$description = $before.$description.$after;
	if ( $echo )
		echo $description;
	else
		return $description;
}

function prima_get_content_limit($max_char, $more_link_text = '[more...]', $stripteaser = 0) {
	$content = get_the_content('', $stripteaser);
	$content = strip_tags(strip_shortcodes($content), '<script>,<style>');
	$content = trim(preg_replace('#<(s(cript|tyle)).*?</\1>#si', '', $content));
	$content = trim( $content );
	if ( strlen($content) > $max_char ) {
		$content = substr($content, 0, $max_char + 1);
		$content = trim(substr($content, 0, strrpos($content, ' ')));
		$content .= __( ' &hellip;', 'primathemes' );
	}
	if ( $more_link_text ) {
		$link = apply_filters( 'get_the_content_more_link', sprintf( '<a href="%s" class="more-link">%s</a>', get_permalink(), $more_link_text ) );
		$output = sprintf('<p>%s %s</p>', $content, $link);
	}
	else {
		$link = '';
		$output = sprintf('<p>%s</p>', $content);
	}
	return apply_filters('prima_get_content_limit', $output, $content, $link, $max_char);
}

function prima_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0) {
	$content = prima_get_content_limit($max_char, $more_link_text, $stripteaser);
	echo apply_filters('prima_content_limit', $content);
}

function prima_get_excerpt_limit($max_char, $more_link_text = '[more...]') {
	$content = get_the_excerpt();
	$content = strip_tags(strip_shortcodes($content), '<script>,<style>');
	$content = trim(preg_replace('#<(s(cript|tyle)).*?</\1>#si', '', $content));
	$content = trim( $content );
	if ( strlen($content) > $max_char ) {
		$content = substr($content, 0, $max_char + 1);
		$content = trim(substr($content, 0, strrpos($content, ' ')));
		$content .= __( ' &hellip;', 'primathemes' );
	}
	if ( $more_link_text ) {
		$link = apply_filters( 'get_the_excerpt_more_link', sprintf( '<a href="%s" class="more-link">%s</a>', get_permalink(), $more_link_text ) );
		$output = sprintf('<p>%s %s</p>', $content, $link);
	}
	else {
		$link = '';
		$output = sprintf('<p>%s</p>', $content);
	}
	return apply_filters('prima_get_excerpt_limit', $output, $content, $link, $max_char);
}

function prima_excerpt_limit($max_char, $more_link_text = '(more...)') {
	$content = prima_get_excerpt_limit($max_char, $more_link_text);
	echo apply_filters('prima_excerpt_limit', $content);
}

add_filter( 'excerpt_length', 'prima_excerpt_length' );
function prima_excerpt_length( $length ) {
	return 70;
}

add_filter( 'get_the_excerpt', 'prima_custom_excerpt_more' );
function prima_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= prima_continue_reading_link();
	}
	return $output;
}

add_filter( 'excerpt_more', 'prima_auto_excerpt_more' );
function prima_auto_excerpt_more( $more ) {
	return ' &hellip;' . prima_continue_reading_link();
}

function prima_continue_reading_link() {
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . __( 'Read More &rarr;', 'primathemes' ) . '</a>';
}

function prima_pagination($pages = '', $range = 2) {  
	$output = '';
    $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         $output .= "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) $output .= "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) $output .= "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 $output .= ($paged == $i)? "<span class='current'>".$i." . </span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i." . </a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) $output .= "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) $output .= "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         $output .= "</div>\n";
     }
	 return $output;
}
