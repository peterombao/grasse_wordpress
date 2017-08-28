<?php 


function ev_tax_selectbox_init($taxonomies){


	if(!is_array($taxonomies)) return false;
	
	$str = '';
	foreach($taxonomies as $taxonomy_key => $taxonomy_val){
	
		$args = isset($taxonomy_val['args']) ? $taxonomy_val['args'] : array() ;
		$terms = get_terms($taxonomy_val['name'], $args );
		$label = isset($taxonomy_val['label']) ? '<label class="form_label">'.$taxonomy_val['label'].'</label>' : '' ;
	
		if(count($terms) > 0){ 
			$has_val[] = TRUE;
			$selected = isset($_GET[$taxonomy_val['name']]) ? $_GET[$taxonomy_val['name']] : '';
			
			$str  .= '<div class="form_controls">';
				$str .= $label;
				$str .= '	<div class="form_fields">
								<select name="'.$taxonomy_val['name'].'" class="form_select">';
				$str .= '			<option value="">Select '.$taxonomy_val['label'].'...</option>';
									foreach($terms as $terms_key => $term_val){
										$make_selected = $selected == $term_val->slug ? 'selected="selected"' : '';
										$str .= '<option value="'.$term_val->slug.'" '.$make_selected.'>'.$term_val->name.'</option>';
									}
				$str .= '		</select>
							</div>';
			$str .= '</div>';
		}
	
	}

	return $str;
}