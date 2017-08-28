<?php
add_action( 'admin_menu', 'prima_create_post_meta_box' );
function prima_create_post_meta_box() {
	global $prima_child_data;
	$theme_data = $prima_child_data;
	$post_types = get_post_types( array( 'show_ui' => true ), 'objects' );
	foreach ( $post_types as $type ) {
		$post_type_name = $type->labels->singular_name ? $type->labels->singular_name : $type->labels->name;
		if ( post_type_supports( $type->name, "excerpt" ) ) {
			remove_meta_box('postexcerpt', $type->name, 'core');
			add_meta_box('postexcerpt', __('Summaries (Manual Excerpts)', 'primathemes'), 'prima_excerpt_meta_box', $type->name, 'normal', 'high');
		}
		if ( prima_meta_box_normal_args( $type->name ) )
			add_meta_box( "prima-meta-box-normal", sprintf( __( '%1$s Settings', 'primathemes' ), $post_type_name ), 'prima_post_meta_box_normal', $type->name, 'normal', 'low' );
		if ( prima_meta_box_template_args( $type->name ) )
			add_meta_box( "prima-meta-box-template", sprintf( __( '%1$s Template Settings', 'primathemes' ), $post_type_name ), 'prima_post_meta_box_template', $type->name, 'normal', 'low' );
		if ( current_theme_supports( 'prima-seo-settings' ) && post_type_supports( $type->name, "prima-seo-settings" ) && prima_meta_box_seo_args( $type->name ) )
			add_meta_box( "prima-meta-box-seo", sprintf( __( '%1$s SEO Settings', 'primathemes' ), $post_type_name ), 'prima_post_meta_box_seo', $type->name, 'normal', 'low' );
		if ( prima_meta_box_side_args( $type->name ) )
			add_meta_box( "prima-meta-box-side", sprintf( __( '%1$s Settings', 'primathemes' ), $post_type_name ), 'prima_post_meta_box_side', $type->name, 'side', 'low' );
		add_action( 'save_post', 'prima_save_post_meta_box', 10, 2 );
	}
	add_action( "load-post.php", 'prima_meta_box_enqueue_script' );
	add_action( "load-post-new.php", 'prima_meta_box_enqueue_script' );
	add_action( "load-post.php", 'prima_meta_box_enqueue_style' );
	add_action( "load-post-new.php", 'prima_meta_box_enqueue_style' );
	add_filter( 'media_upload_tabs', 'prima_meta_box_upload_tabs', 100 );
	add_action( "admin_head-media-upload-popup", 'prima_meta_box_upload_head' );
	add_filter( 'media_upload_tabs', 'prima_meta_box_uploadgallery_tabs', 100 );
	add_action( "admin_head-media-upload-popup", 'prima_meta_box_uploadgallery_head' );
	add_filter( 'attachment_fields_to_edit', 'attachment_fields_gallery_to_edit', 10, 2);
	add_filter( 'attachment_fields_to_save', 'attachment_fields_gallery_to_save', 10, 2);
}
function prima_meta_box_normal_args( $type = '' ) {
	$meta = array();
	if ( empty( $type ) ) $type = 'post';
	$meta = apply_filters( "prima_posts_meta_box_normal_args", $meta, $type );
	return apply_filters( "prima_{$type}_meta_box_normal_args", $meta, $type );
}
function prima_meta_box_seo_args( $type = '' ) {
	$meta = array();
	if ( empty( $type ) ) $type = 'post';
	$meta = apply_filters( "prima_posts_meta_box_seo_args", $meta, $type );
	return apply_filters( "prima_{$type}_meta_box_seo_args", $meta, $type );
}
function prima_meta_box_template_args( $type = '' ) {
	$meta = array();
	if ( empty( $type ) ) $type = 'post';
	$meta = apply_filters( "prima_posts_meta_box_template_args", $meta, $type );
	return apply_filters( "prima_{$type}_meta_box_template_args", $meta, $type );
}
function prima_meta_box_side_args( $type = '' ) {
	$meta = array();
	if ( empty( $type ) ) $type = 'post';
	$meta = apply_filters( "prima_posts_meta_box_side_args", $meta, $type );
	return apply_filters( "prima_{$type}_meta_box_side_args", $meta, $type );
}
function prima_post_meta_box_normal( $object, $box ) {
	$meta = prima_meta_box_normal_args( $object->post_type );
	if ( !$meta ) return;
	echo '<input type="hidden" name="prima_'.$object->post_type.'_meta_box_nonce" value="'.wp_create_nonce( basename( __FILE__ ) ).'" />';
	prima_meta_generator( $meta, 'post', $object->ID );
}
function prima_post_meta_box_template( $object, $box ) {
	$meta = prima_meta_box_template_args( $object->post_type );
	if( ($object->post_type=='page') && isset($object->page_template) && ($object->page_template!='') ) {
		$template = str_replace( '.php', '', $object->page_template );
		$meta = apply_filters( "prima_{$template}_meta_box_args", $meta );
	}
	if ( !$meta ) return;
	echo '<input type="hidden" name="prima_'.$object->post_type.'_meta_box_nonce" value="'.wp_create_nonce( basename( __FILE__ ) ).'" />';
	prima_meta_generator( $meta, 'post', $object->ID );
}
function prima_post_meta_box_seo( $object, $box ) {
	$meta = prima_meta_box_seo_args( $object->post_type );
	if ( !$meta ) return;
	echo '<input type="hidden" name="prima_'.$object->post_type.'_meta_box_nonce" value="'.wp_create_nonce( basename( __FILE__ ) ).'" />';
	prima_meta_generator( $meta, 'post', $object->ID );
}
function prima_post_meta_box_side( $object, $box ) {
	$meta = prima_meta_box_side_args( $object->post_type );
	if ( !$meta ) return;
	echo '<input type="hidden" name="prima_'.$object->post_type.'_meta_box_nonce" value="'.wp_create_nonce( basename( __FILE__ ) ).'" />';
	prima_meta_generator( $meta, 'post', $object->ID );
}
function prima_save_post_meta_box( $post_id, $post ) {
	if ( !isset( $_POST["prima_{$post->post_type}_meta_box_nonce"] ) || !wp_verify_nonce( $_POST["prima_{$post->post_type}_meta_box_nonce"], basename( __FILE__ ) ) )
		return $post_id;
	$post_type = get_post_type_object( $post->post_type );
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;
	$metadata = prima_meta_box_normal_args( $post->post_type );
	if ( prima_meta_box_template_args( $post->post_type ) )
		$metadata = array_merge($metadata, prima_meta_box_template_args( $post->post_type ));
	if ( current_theme_supports( 'prima-seo-settings' ) && post_type_supports( $post->post_type, "prima-seo-settings" ) && prima_meta_box_seo_args( $post->post_type ) )
		$metadata = array_merge($metadata, prima_meta_box_seo_args( $post->post_type ));
	$metadata = array_merge($metadata, prima_meta_box_side_args( $post->post_type ));
	if( ($post->post_type=='page') && isset($post->page_template) && ($post->page_template!='') ) {
		$template = str_replace( '.php', '', $post->page_template );
		$metadata_pagetemplate = array();
		$metadata_pagetemplate = apply_filters( "prima_{$template}_meta_box_args", $metadata_pagetemplate );
		// print_r($metadata_pagetemplate);
		$metadata = array_merge($metadata, $metadata_pagetemplate);
	}	
	if ( $metadata ) {
		foreach ( $metadata as $meta ) {
			if ( isset( $_POST[ $meta['name'] ] ) && $_POST[ $meta['name'] ] ) {
				// $new_meta_value = stripslashes( $_POST[ $meta['name'] ] );
				$new_meta_value = $_POST[ $meta['name'] ];
				update_post_meta( $post_id, $meta['name'], $new_meta_value );
			}
			else {
				delete_post_meta( $post_id, $meta['name'] );
			}
		}
	}
}
function prima_meta_box_enqueue_script() {
	global $hook_suffix;
    add_thickbox();
	wp_enqueue_script('jquery-ui-core');
	wp_register_script('jquery-ui-datepicker', PRIMA_ADMIN_URI.'/js/ui.datepicker.js', array( 'jquery-ui-core' ));
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_script('jquery-input-mask', PRIMA_ADMIN_URI.'/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	// wp_enqueue_script('prima-colorpicker', PRIMA_ADMIN_URI . '/js/colorpicker.js', array(), '0.1', FALSE);
	wp_enqueue_script('prima-metabox', PRIMA_ADMIN_URI . '/js/metabox.js', array('jquery','media-upload','thickbox'), '0.1', FALSE);
	$params = array(
		'pageHook'      => $hook_suffix,
		'primaAdminUri' => PRIMA_ADMIN_URI
	);
	wp_localize_script('prima-metabox', 'prima', $params);
    // remove GD star rating conflicts
    wp_deregister_style( 'gdsr-jquery-ui-core' );
    wp_deregister_style( 'gdsr-jquery-ui-theme' );
}
function prima_meta_box_enqueue_style() {
	wp_enqueue_style( 'prima-metabox', PRIMA_ADMIN_URI . '/css/metabox.css', false, 0.1, 'screen' );
	// wp_enqueue_style( 'prima-colorpicker', PRIMA_ADMIN_URI . '/css/colorpicker.css', false, 0.1, 'screen' );
	wp_enqueue_style( 'prima-datepicker', PRIMA_ADMIN_URI . '/css/jquery-ui-datepicker.css', false, 0.1, 'screen' );
}
function prima_meta_box_upload_tabs ( $tabs ) {
	global $wpdb;
	$post_id = intval($_REQUEST['post_id']);
	if ( !isset($_REQUEST['post_id']) ) return $tabs;
	if ( !isset($_REQUEST['is_primathemes']) ) return $tabs;
	$tabs = array(
		'type' => __('Upload', 'primathemes')
	);
	$attachments = intval( $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent = %d", $post_id ) ) );
	if ( !empty($attachments) ) {
		$tabs['gallery'] = sprintf(__('Previously Uploaded (%s)', 'primathemes'), "<span id='attachments-count'>$attachments</span>");
	}
	return $tabs;
}
function prima_meta_box_uploadgallery_tabs ( $tabs ) {
	global $wpdb;
	$post_id = intval($_REQUEST['post_id']);
	if ( !isset($_REQUEST['post_id']) ) return $tabs;
	if ( !isset($_REQUEST['is_primathemesgallery']) ) return $tabs;
	$tabs = array(
		'type' => __('Upload', 'primathemes')
	);
	$attachments = intval( $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent = %d", $post_id ) ) );
	if ( !empty($attachments) ) {
		$tabs['gallery'] = sprintf(__('Manage Images (%s)', 'primathemes'), "<span id='attachments-count'>$attachments</span>");
	}
	return $tabs;
}
function prima_meta_box_upload_head () {
	$post_id = intval($_REQUEST['post_id']);
	if ( !isset($_REQUEST['post_id']) ) return;
	if ( !isset($_REQUEST['is_primathemes']) ) return;
?>
	<script type="text/javascript">
	<!--
	jQuery(document).ready( function($) {
		$( '.savesend input.button, .media-item #go_button' ).attr( 'value', 'Use this File' );
		$( 'a.wp-post-thumbnail, div#gallery-settings, tr.post_title, tr.image_alt, tr.post_excerpt, tr.post_content, tr.url, tr.align, #media-upload p.ml-submit' ).hide();
		$( '.savesend a.del-link' ).click ( function () {
			var continueButton = $( this ).next( '.del-attachment' ).children( 'a.button[id*="del"]' );
			var continueHref = continueButton.attr( 'href' );
			continueHref = continueHref + '&is_primathemes=1';
			continueButton.attr( 'href', continueHref );
		} );
        $('input#html-upload').live("click", function () {
			var actionForm = $( this ).parents( 'form' );
			var actionUrl = actionForm.attr( 'action' );
			actionUrl = actionUrl + '&is_primathemes=1';
			actionForm.attr( 'action', actionUrl );
		} );
	});
	-->
	</script>
    <style type="text/css">
		a.wp-post-thumbnail, div#gallery-settings, tr.post_title, tr.image_alt, tr.post_excerpt, tr.post_content, tr.url, tr.align, #media-upload p.ml-submit { display: none; visibility: hidden; }
	</style>
<?php
}
function prima_meta_box_uploadgallery_head () {
	$post_id = intval($_REQUEST['post_id']);
	if ( !isset($_REQUEST['post_id']) ) return;
	if ( !isset($_REQUEST['is_primathemesgallery']) ) return;
?>
	<script type="text/javascript">
	<!--
	jQuery(document).ready( function($) {
		$( 'a.wp-post-thumbnail, div#gallery-settings, tr.post_excerpt, tr.image_alt, tr.url, tr.align, tr.image-size, .submit .savesend input.button' ).hide();
		$( '.savesend a.del-link' ).click ( function () {
			var continueButton = $( this ).next( '.del-attachment' ).children( 'a.button[id*="del"]' );
			var continueHref = continueButton.attr( 'href' );
			continueHref = continueHref + '&is_primathemesgallery=1';
			continueButton.attr( 'href', continueHref );
		} );
        $('input#html-upload').live("click", function () {
			var actionForm = $( this ).parents( 'form' );
			var actionUrl = actionForm.attr( 'action' );
			actionUrl = actionUrl + '&is_primathemesgallery=1';
			actionForm.attr( 'action', actionUrl );
		} );
        $('#media-upload p.ml-submit #save-all, #media-upload p.ml-submit #save').live("click", function () {
			var actionForm = $( this ).parents( 'form' );
			var actionUrl = actionForm.attr( 'action' );
			actionUrl = actionUrl + '&is_primathemesgallery=1';
			actionForm.attr( 'action', actionUrl );
		} );
	});
	-->
	</script>
    <style type="text/css">
		a.wp-post-thumbnail, div#gallery-settings, tr.post_excerpt, tr.image_alt, tr.url, tr.align, tr.image-size, .submit .savesend input.button { display: none; visibility: hidden; }
	</style>
<?php
}
function attachment_fields_gallery_to_edit( $form_fields, $post) {
	if ( !isset($_REQUEST['is_primathemesgallery']) ) return $form_fields;
    $form_fields["link_url"]["label"] = __("Link URL",'primathemes');  
    $form_fields["link_url"]["input"] = "text";  
    $form_fields["link_url"]["value"] = get_post_meta($post->ID, "link_url", true);  
	return $form_fields;
}
function attachment_fields_gallery_to_save( $post, $attachment ) {  
    if( isset($attachment['link_url']) ){  
        update_post_meta($post['ID'], 'link_url', $attachment['link_url']);  
    }  
    return $post;  
}  
function prima_meta_generator( $options, $type = 'post', $type_id, $type_name = '' ) {
    echo '<table class="form-table prima_meta_table">'."\n";
    foreach ($options as $meta) {
		$meta_id = "prima-" . $meta["name"];
		$meta_name = $meta["name"];
		if ( $type == 'post') $meta_value = prima_get_post_meta( $meta_name, $type_id );
		elseif ( $type == 'taxonomy') $meta_value = prima_get_taxonomy_meta( $type_id, $type_name, $meta_name );
		if (empty($meta_value) && isset($meta['std'])) $meta_value = $meta['std'];
		// echo $meta_name.":".$meta_value.'</br>';
		if($meta['type'] == 'info'){
			echo "\t".'<tr style="background:#f8f8f8; font-size:11px; line-height:1.5em;">';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td>'.$meta['desc'].'</td>'."\n";
			echo "\t".'</tr>'."\n";  
		}
		elseif($meta['type'] == 'text'){
			$add_class = ''; $add_counter = '';
			if( isset($meta['counter']) && $meta['counter'] == true ){$add_class = 'prima-word-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>';}
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td><input class="meta_input_text '.$add_class.'" type="'.$meta['type'].'" value="'.esc_attr( $meta_value ).'" name="'.$meta_name.'" id="'.$meta_id.'"/>';
			echo '<span class="meta_desc">'.$meta['desc'] .' '. $add_counter .'</span></td>'."\n";
			echo "\t".'</tr>'."\n";  
						  
		}
		elseif($meta['type'] == 'text_small'){
			$add_class = ''; $add_counter = '';
			if( isset($meta['counter']) && $meta['counter'] == true ){$add_class = 'prima-word-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>';}
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td><input size="8" class="'.$add_class.'" type="text" value="'.esc_attr( $meta_value ).'" name="'.$meta_name.'" id="'.$meta_id.'"/>';
			echo '<span class="meta_desc_inline">'.$meta['desc'] .' '. $add_counter .'</span></td>'."\n";
			echo "\t".'</tr>'."\n";  
						  
		}
		elseif ($meta['type'] == 'textarea'){
			$add_class = ''; $add_counter = '';
			if( isset($meta['counter']) && $meta['counter'] == true ){$add_class = 'prima-word-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>';}
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td><textarea class="meta_input_textarea '.$add_class.'" name="'.$meta_name.'" id="'.$meta_id.'">' . esc_textarea( $meta_value ) . '</textarea>';
			echo '<span class="meta_desc">'.$meta['desc'] .' '. $add_counter.'</span></td>'."\n";
			echo "\t".'</tr>'."\n";  
		}
		elseif ($meta['type'] == 'wysiwyg'){
			$add_class = ''; $add_counter = '';
			if( isset($meta['counter']) && $meta['counter'] == true ){$add_class = 'prima-word-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>';}
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td>';
			echo '<span class="meta_desc">'.$meta['desc'] .' '. $add_counter.'</span></td>'."\n";
			echo "\t".'</tr>'."\n";  
			echo "\t".'<tr>';
			echo "\t\t".'<td colspan="2" class="wysiwygbox">';
			$args = array(
				'textarea_name' => $meta_name,
				'wpautop' => true,
				'textarea_rows'=> 10,
				'media_buttons' => true
			);
			wp_editor( $meta_value, $meta_id, $args );
			echo '</td>'."\n";
			echo "\t".'</tr>'."\n";  
		}
		elseif ($meta['type'] == 'calendar'){
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td><input class="meta_input_calendar" type="text" name="'.$meta_name.'" id="'.$meta_id.'" value="'.$meta_value.'">';
			echo '<span class="meta_desc">'.$meta['desc'].'</span></td>'."\n";
			echo "\t".'</tr>'."\n";  
		}
		elseif ($meta['type'] == 'time'){
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td><input class="meta_input_time" type="'.$meta['type'].'" value="'.$meta_value.'" name="'.$meta_name.'" id="'.$meta_id.'"/>';
			echo '<span class="meta_desc">'.$meta['desc'].'</span></td>'."\n";
			echo "\t".'</tr>'."\n"; 
		}
		elseif ($meta['type'] == 'select'){
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td><select class="meta_input_select" id="'.$meta_id.'" name="'. $meta_name .'">';
			// echo '<option value="">Select to return to default</option>';
			$array = $meta['options'];
			if($array){
				foreach ( $array as $id => $option ) {
					$selected = '';
					if(isset($meta['default']))  {                            
						if($meta['default'] == $option && empty($meta_value)){$selected = 'selected="selected"';} 
						else  {$selected = '';}
					}
					if($meta_value == $option){$selected = 'selected="selected"';}
					else  {$selected = '';}  
					echo '<option value="'. $option .'" '. $selected .'>' . $option .'</option>';
				}
			}
			echo '</select><span class="meta_desc">'.$meta['desc'].'</span></td>'."\n";
			echo "\t".'</tr>'."\n";
		}
		elseif ($meta['type'] == 'select2'){
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td><select class="meta_input_select" id="'.$meta_id.'" name="'. $meta_name .'">';
			$array = $meta['options'];
			if($array){
				foreach ( $array as $id => $option ) {
					$selected = '';
					if(isset($meta['default']))  {                            
						if($meta['default'] == $id && empty($meta_value)){$selected = 'selected="selected"';} 
						else  {$selected = '';}
					}
					if($meta_value == $id){$selected = 'selected="selected"';}
					else  {$selected = '';}  
					echo '<option value="'. $id .'" '. $selected .'>' . $option .'</option>';
				}
			}
			echo '</select><span class="meta_desc">'.$meta['desc'].'</span></td>'."\n";
			echo "\t".'</tr>'."\n";
		}
		elseif ($meta['type'] == 'checkbox'){
			if($meta_value == 'true') { $checked = ' checked="checked"';} else {$checked='';}
			$meta_class = ( isset( $meta['class'] ) && $meta['class'] ) ? ' class="'.$meta['class'].'" ' : '';
			echo "\t".'<tr>';
			if ( isset( $meta['onecol'] ) && $meta['onecol'] ) { 
				echo "\t\t".'<td colspan="2" '.$meta_class.'><input type="checkbox" '.$checked.' class="meta_input_checkbox" value="true"  id="'.$meta_id.'" name="'. $meta_name .'" />';
			}
			else {
				echo "\t\t".'<th class="meta_names" '.$meta_class.'><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
				echo "\t\t".'<td '.$meta_class.'><input type="checkbox" '.$checked.' class="meta_input_checkbox" value="true"  id="'.$meta_id.'" name="'. $meta_name .'" />';
			}
			echo '<span class="meta_desc" style="display:inline">'.$meta['desc'].'</span></td>'."\n";
			echo "\t".'</tr>'."\n";
		}
		elseif ($meta['type'] == 'radio'){
			$array = $meta['options'];
			if($array){
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td>';
			foreach ( $array as $id => $option ) {
				if($meta_value == $id) { $checked = ' checked';} else {$checked='';}
					echo '<input type="radio" '.$checked.' value="' . $id . '" class="meta_input_radio"  name="'. $meta_name .'" />';
					echo '<span class="meta_input_radio_desc" style="display:inline">'. $option .'</span><div class="meta_spacer"></div>';
				}
				echo "\t".'</tr>'."\n";    
			 }
		}
		elseif ($meta['type'] == 'images')
		{
			$i = 0;
			$select_value = '';
			$layout = '';
			foreach ($meta['options'] as $key => $option) 
				 { 
				 $i++;
				 $checked = '';
				 $selected = '';
				 if($meta_value != '') {
					if ($meta_value == $key) { $checked = ' checked'; $selected = 'meta-radio-img-selected'; }
				 } 
				 else {
					if ($option['std'] == $key) { $checked = ' checked'; } 
					elseif ($i == 1) { $checked = ' checked'; $selected = 'meta-radio-img-selected'; }
					else { $checked=''; }
					
				 }
					$layout .= '<div class="meta-radio-img-label">';			
					$layout .= '<input type="radio" id="meta-radio-img-' . $meta_name . $i . '" class="checkbox meta-radio-img-radio" value="'.$key.'" name="'. $meta_name.'" '.$checked.' />';
					$layout .= '&nbsp;' . $key .'<div class="meta_spacer"></div></div>';
					$layout .= '<img src="'.$option.'" alt="" class="meta-radio-img-img '. $selected .'" onClick="document.getElementById(\'meta-radio-img-'. $meta["name"] . $i.'\').checked = true;" />';
				}
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td class="meta_fields">';
			echo $layout;
			echo '<span class="meta_desc">'.$meta['desc'].'</span></td>'."\n";
			echo "\t".'</tr>'."\n"; 
		}
		elseif($meta['type'] == 'postupload')
		{
			if($type == 'post')
			{
				global $post;
				echo "\t".'<tr>';
				echo "\t\t".'<th class="meta_names"><label for="'.$meta["name"].'">'.$meta['label'].'</label></th>'."\n";
				echo "\t\t".'<td class="meta_fields">';
				// $formid = 'meta-tax-'.$type_id.'-'.$meta_name;
				$formid = str_replace("_prima_", "", $meta_name);
				$page_id = $post->ID;
				$uploadclass = ( $meta_value ) ? 'has-file' : '';
				echo '<input type="text" name="'.$meta_name.'" id="'.$meta_id.'" value="'.$meta_value.'" class="upload '.$uploadclass.'" />';
				echo '<input id="upload_'.$meta_id.'" class="upload_button button button_highlighted" type="button" value="' . __( 'Upload', 'primathemes' ) . '" rel="'.$page_id.'" />';
				echo '<div class="screenshot" id="'.$meta_id.'_image">';
				  if ( $meta_value != '' ) 
				  { 
					$remove = '<a href="javascript:(void);" class="remove">Remove</a>';
					$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta_value );
					if ( $image ) 
					{
					  echo '<img src="'.$meta_value.'" alt="" />'.$remove.'';
					} 
					else 
					{
					  $parts = explode( "/", $meta_value );
					  for( $i = 0; $i < sizeof($parts); ++$i ) 
					  {
						$title = $parts[$i];
					  }
					  echo '<div class="no_image"><a href="'.$meta_value.'">'.$title.'</a>'.$remove.'</div>';
					}
				  }
				echo '</div>';
				echo "\t\t".'<span class="meta_desc" style="clear:both">'.$meta['desc'].'</span>'."\n";
				echo '</td>'."\n";
				echo "\t".'</tr>'."\n";
			}
		}
		elseif($meta['type'] == 'upload')
		{
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta["name"].'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td class="meta_fields">';
			// $formid = 'meta-tax-'.$type_id.'-'.$meta_name;
			$formid = str_replace("_prima_", "", $meta_name);
			$page_id = prima_framework_post_id( $formid );
			if ( !$page_id ) 
			{
				$_p = array();
				$_p['post_title'] = $formid;
				$_p['post_status'] = 'private';
				$_p['post_type'] = 'primaframework';
				$_p['comment_status'] = 'closed';
				$_p['ping_status'] = 'closed';
				$page_id = wp_insert_post( $_p );
			}
			$uploadclass = ( $meta_value ) ? 'has-file' : '';
			echo '<input type="text" name="'.$meta_name.'" id="'.$meta_id.'" value="'.$meta_value.'" class="upload '.$uploadclass.'" />';
			echo '<input id="upload_'.$meta_id.'" class="upload_button button button_highlighted" type="button" value="' . __( 'Upload', 'primathemes' ) . '" rel="'.$page_id.'" />';
			echo '<div class="screenshot" id="'.$meta_id.'_image">';
			  if ( $meta_value != '' ) 
			  { 
				$remove = '<a href="javascript:(void);" class="remove">Remove</a>';
				$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta_value );
				if ( $image ) 
				{
				  echo '<img src="'.$meta_value.'" alt="" />'.$remove.'';
				} 
				else 
				{
				  $parts = explode( "/", $meta_value );
				  for( $i = 0; $i < sizeof($parts); ++$i ) 
				  {
					$title = $parts[$i];
				  }
				  echo '<div class="no_image"><a href="'.$meta_value.'">'.$title.'</a>'.$remove.'</div>';
				}
			  }
			echo '</div>';
			echo "\t\t".'<span class="meta_desc" style="clear:both">'.$meta['desc'].'</span>'."\n";
			echo '</td>'."\n";
			echo "\t".'</tr>'."\n";
		}
		elseif($meta['type'] == 'postgallery')
		{
			if($type == 'post')
			{
				global $post;
				echo "\t".'<tr>';
				echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
				echo "\t\t".'<td>';
				echo "\t\t".'<span class="meta_desc" style="clear:both">'.$meta['desc'].'</span>'."\n";
				$attachments = get_children( array( 'post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
				if ( !empty($attachments) ) {
					echo '<p>';
					foreach ( $attachments as $id => $attachment ) {
						list($src) = wp_get_attachment_image_src($id,'thumbnail');
						echo '<img src="'.$src.'" alt="" width="80" height="80" />';
					}
					echo '</p>';
				}
				echo '<p style="clear:both">';
				echo '<input id="upload_'.$meta_id.'" class="upload_gallery_button button button_highlighted" type="button" value="' . __( 'Manage Images', 'primathemes' ) . '" rel="'.$post->ID.'" />';
				echo '<input type="submit" value="' . __( 'Save/Update', 'primathemes' ) . '" class="button" name="save"></p>';
				echo '</td>'."\n";
				echo "\t".'</tr>'."\n";  
			}
		}
		elseif($meta['type'] == 'gallery')
		{
			if($type == 'post')
				$formid = 'gallerypost-'.$type_id;
			else
				$formid = 'gallerytax-'.$type_id;
			$page_id = prima_framework_post_id( $formid );
			if ( !$page_id ) 
			{
				$_p = array();
				$_p['post_title'] = $formid;
				$_p['post_status'] = 'private';
				$_p['post_type'] = 'primaframework';
				$_p['comment_status'] = 'closed';
				$_p['ping_status'] = 'closed';
				$page_id = wp_insert_post( $_p );
			}
			global $post;
			echo "\t".'<tr>';
			echo "\t\t".'<th class="meta_names"><label for="'.$meta_id.'">'.$meta['label'].'</label></th>'."\n";
			echo "\t\t".'<td>';
			echo "\t\t".'<span class="meta_desc" style="clear:both">'.$meta['desc'].'</span>'."\n";
			$attachments = get_children( array( 'post_parent' => $page_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
			if ( !empty($attachments) ) {
				echo '<p>';
				foreach ( $attachments as $id => $attachment ) {
					list($src) = wp_get_attachment_image_src($id,'thumbnail');
					echo '<img src="'.$src.'" alt="" width="80" height="80" />';
				}
				echo '</p>';
			}
			echo '<p style="clear:both">';
			echo '<input id="upload_'.$meta_id.'" class="upload_gallery_button button button_highlighted" type="button" value="' . __( 'Manage Images', 'primathemes' ) . '" rel="'.$page_id.'" />';
			echo '<input type="submit" value="' . __( 'Save/Update', 'primathemes' ) . '" class="button" name="save"></p>';
			echo '</td>'."\n";
			echo "\t".'</tr>'."\n";  
		}
	}
    echo '</table>'."\n\n";
}

function prima_excerpt_meta_box($post) {
	$args = array(
		'textarea_name' => 'excerpt',
		'wpautop' => true,
		'media_buttons' => true,
		'textarea_rows'=> 10,
		'tabindex' => 6
	);
	?>
	<label class="screen-reader-text" for="excerpt"><?php _e('Excerpt', 'primathemes') ?></label>
	<?php wp_editor( $post->post_excerpt, 'excerpt', $args ); ?>
	<p><?php _e('Excerpts are optional hand-crafted summaries of your content that can be used in your theme.', 'primathemes'); ?></p>
<?php
}
