<?php 

/*
	
	Beta

*/
function ev_breadcrumb($param = null) {

	$after_item  = isset($param['after_item']) ? $param['after_item'] : "";
	$before_item  = isset($param['before_item']) ? $param['before_item'] : "";
	$separator  = isset($param['separator']) ? $param['separator'] : "";
	$after_breadcrumb = isset($param['after_breadcrumb']) ? $param['after_breadcrumb'] : ""; 
	$before_breadcrumb = isset($param['before_breadcrumb']) ? $param['before_breadcrumb'] : "";
	$after_active = isset($param['after_active']) ? $param['after_active'] : ""; 
	$before_active = isset($param['before_active']) ? $param['before_active'] : "";
	$home_display = isset($param['home_display']) ? $param['home_display'] : "Home";
	$label = isset($param['label']) ? $param['label'] : '';
	$str = '';
	
    if (!is_front_page()) {
        $str .= $before_item.'<a href="'.get_option('home').'">'.$home_display."</a>".$after_item.$separator;
		
        if (is_category() || is_single()) {
			$page_id = get_the_ID();
			$post_type = get_post_type($page_id);
			$taxonomies = get_object_taxonomies($post_type);
			$term->errors = TRUE;
			for($counter = 0; isset($taxonomies[$counter]); $counter++){
				$taxonomy = $taxonomies[$counter];
				
				$exclude_tax = array('pa_size-ml', 'pa_cap', 'pa_size-g', 'product_type');
		
				if(!in_array($taxonomy, $exclude_tax)){
					$terms = wp_get_post_terms($page_id, $taxonomy);
					foreach($terms as $term_key => $term_val){
						$term = get_term($term_val->term_id, $taxonomies[$counter]);
						if(!isset($term->errors)){
							$exclude_term = array('simple');
							if(!in_array($term_val->slug, $exclude_term)){
								$term->permalink =  get_term_link( $term->slug, $taxonomy ); 
								break;
							}
						}				
					}
				}
				
				if(isset($term->permalink )) break;
			}

			if(!isset($term->errors )){
			
				 $str .= $before_item."<a href='".$term->permalink."'>".$term->name."</a>".$after_item.$separator;
			
			}	
			
			if (is_single()) { // for single page. This part is not yet complete.
                //$str .= $separator;
                $str .= $before_item.$before_active.get_the_title().$after_active.$after_item;
            }
			
			
        } elseif (is_page()) { //active
			$str .= $before_item.$before_active.get_the_title().$after_active.$after_title;
        }elseif(is_tax()){
			global $wp_query;
			$term = get_term_by( 'slug', get_query_var($wp_query->query_vars['taxonomy']), $wp_query->query_vars['taxonomy']);	
			$term->permalink =  get_term_link( $term->slug, $term->taxonomy ); 			
			$str .= $before_item.$before_active.$term->name.$after_active.$after_item;
		}
    }else{
		$str .= $before_item.$before_active.$home_display.$after_active.$after_item;
	}
	
	return $label.$before_breadcrumb.$str.$after_breadcrumb;
}


?>