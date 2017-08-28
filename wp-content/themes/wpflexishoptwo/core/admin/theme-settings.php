<?php
add_action( 'admin_menu', 'prima_theme_settings_init' );
function prima_theme_settings_init() {
	global $menu, $prima, $prima_child_data;
	$theme_data = $prima_child_data;
	$prima->theme_settings = add_theme_page( sprintf( __( '%1$s Theme Settings', 'primathemes' ), $theme_data['Name'] ), __( 'Theme Settings', 'primathemes' ), apply_filters( "prima_settings_capability", 'edit_theme_options' ), 'primathemes', 'prima_theme_settings_page' );
	add_action( "load-{$prima->theme_settings}", 'prima_theme_settings_enqueue_script' );
	add_action( "load-{$prima->theme_settings}", 'prima_theme_settings_enqueue_style' );
	add_filter( 'media_upload_tabs', 'prima_theme_settings_upload_tabs', 100 );
	add_action( "admin_head-media-upload-popup", 'prima_theme_settings_upload_head' );
}
add_action('admin_init', 'prima_register_theme_settings');
function prima_register_theme_settings() {
	register_setting( PRIMA_THEME_SETTINGS, PRIMA_THEME_SETTINGS, 'prima_save_theme_settings' );
	add_option( PRIMA_THEME_SETTINGS, prima_default_theme_settings(), '', 'yes' );
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes' )
		return;
	if ( prima_get_setting('reset') ) {
		update_option( PRIMA_THEME_SETTINGS, prima_default_theme_settings() );
		wp_redirect( admin_url( 'themes.php?page=primathemes&reset=true' ) );
		exit;
	}
}
function prima_theme_settings() {
	$settings = array();
	return apply_filters( 'prima_theme_settings_args', $settings );
}
function prima_default_theme_settings() {
	$settings = prima_theme_settings();
	if ( false === $settings ) return false;
	$defaut_settings = array();
	foreach ($settings as $setting) {
		if (isset($setting['id'])) $defaut_settings[$setting['id']] = isset($setting['std']) ? $setting['std'] : '';
	}
	return $defaut_settings;
}
function prima_save_theme_settings( $settings ) {
	return apply_filters( "prima_validate_theme_settings", $settings );
}
function prima_theme_settings_page() {
	global $prima, $prima_child_data;
	$theme_data = $prima_child_data; ?>
	<div class="wrap" id="prima-settings">
        <form method="post" enctype="multipart/form-data" action="options.php">
		<?php settings_fields( PRIMA_THEME_SETTINGS ); ?>
        <div class="prima-settings-header">
            <div class="prima_header_title"><?php printf( __( '%1$s Theme Settings', 'primathemes' ), $theme_data['Name'] ); ?></div>
            <input type="submit" class="button-save button-primary submit-button" value="<?php _e( 'Save Settings', 'primathemes' ); ?>" />
            <input type="submit" name="<?php echo PRIMA_THEME_SETTINGS; ?>[reset]"  class="button-reset button-highlighted submit-button reset-button" value="<?php _e( 'Reset Settings', 'primathemes' ); ?>" />
        </div>
        <?php
		if ( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {
			echo '<div id="prima-settings-message" class="">'.__('Theme Settings Saved', 'primathemes').'</div>';
		}
		elseif ( isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] == 'true') {
			echo '<div id="prima-settings-message" class="">'.__('Theme Settings Saved', 'primathemes').'</div>';
		}
		elseif ( isset( $_REQUEST['reset'] ) && $_REQUEST['reset'] == 'true' ) {
			echo '<div id="prima-settings-message" class="">'.__('Theme Settings Reset', 'primathemes').'</div>';
		}
		?>
        <div id="prima-main">
	        <div id="prima-nav">
				<ul>
					<?php prima_settings_menu_generator( prima_theme_settings(), PRIMA_THEME_SETTINGS ); ?>
				</ul>
			</div>
			<div id="prima-content">
				<?php prima_settings_generator( prima_theme_settings(), PRIMA_THEME_SETTINGS ); ?>
	        </div>
	        <div class="clear"></div>
        </div>
        <div class="prima-settings-footer">
            <input type="submit" class="button-save button-primary submit-button" value="<?php _e( 'Save Settings', 'primathemes' ); ?>" />
            <input type="submit" name="<?php echo PRIMA_THEME_SETTINGS; ?>[reset]"  class="button-reset button-highlighted submit-button reset-button" value="<?php _e( 'Reset Settings', 'primathemes' ); ?>" />
        </div>
    </form>
	<?php
    // $settings = get_option( PRIMA_THEME_SETTINGS ); print_r($settings);
    // echo '<br/><br/>';print_r(prima_default_theme_settings());
    // echo '<br/><br/>';print_r(prima_theme_settings());
    ?>
	</div><!-- .wrap -->
	<?php
}
function prima_theme_settings_enqueue_script() {
	global $prima;
    add_thickbox();
	wp_enqueue_script('jquery-ui-core');
	wp_register_script('jquery-ui-datepicker', PRIMA_ADMIN_URI.'/js/ui.datepicker.js', array( 'jquery-ui-core' ));
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_script('jquery-input-mask', PRIMA_ADMIN_URI.'/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	wp_enqueue_script('prima-colorpicker', PRIMA_ADMIN_URI . '/js/colorpicker.js', array(), '0.1', FALSE);
	wp_enqueue_script('prima-admin', PRIMA_ADMIN_URI . '/js/admin.js', array('jquery','media-upload','thickbox'), '0.1', FALSE);
	$params = array(
		'pageHook'      => $prima->theme_settings,
		'primaAdminUri' => PRIMA_ADMIN_URI,
		'warnUnsaved'   => __('The changes you made will be lost if you navigate away from this page.', 'primathemes'),
		'warnReset'     => __('Are you sure you want to reset theme settings?', 'primathemes')
	);
	wp_localize_script('prima-admin', 'prima', $params);
    // remove GD star rating conflicts
    wp_deregister_style( 'gdsr-jquery-ui-core' );
    wp_deregister_style( 'gdsr-jquery-ui-theme' );
}
function prima_theme_settings_enqueue_style() {
	wp_enqueue_style( 'prima-admin', PRIMA_ADMIN_URI . '/css/admin.css', false, 0.1, 'screen' );
	wp_enqueue_style( 'prima-colorpicker', PRIMA_ADMIN_URI . '/css/colorpicker.css', false, 0.1, 'screen' );
	wp_enqueue_style( 'prima-datepicker', PRIMA_ADMIN_URI . '/css/jquery-ui-datepicker.css', false, 0.1, 'screen' );
}
function prima_theme_settings_upload_tabs ( $tabs ) {
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
function prima_theme_settings_upload_head () {
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
/**
* Form Generator
* Credits: WooFramework, OptionTree, Options Framework
**/
function prima_settings_generator( $options, $field ) {
    $counter = 0;
	foreach ($options as $value) {
		$counter++;
		$val = '';
		//Start Heading
		if ( $value['type'] != "heading" && $value['type'] != "note" )
		{
			$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			//echo '<div class="section section-'. $value['type'] .'">'."\n".'<div class="option-inner">'."\n";
			echo '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			echo '<h3 class="heading">'. htmlspecialchars_decode( $value['name'] ) .'</h3>'."\n";
			echo '<div class="option">'."\n" . '<div class="controls">'."\n";

		}
		//End Heading
		$select_value = '';
		switch ( $value['type'] ) {
		case 'text':
			$val = ( prima_get_setting($value['id'],$field) != "" ) ? prima_get_setting($value['id'],$field) : '';
			echo '<input class="prima-input" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'" type="'. $value['type'] .'" value="'. esc_attr( $val ) .'" />';
		break;
		case 'select':
			echo '<div class="select_wrapper"><select class="prima-input" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'">';
			$select_value = stripslashes(prima_get_setting($value['id'],$field));
			foreach ($value['options'] as $option) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';}
			     }
				 else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				 echo '<option'. $selected .'>';
				 echo $option;
				 echo '</option>';
			 }
			 echo '</select></div>';
		break;
		case 'select2':
			echo '<div class="select_wrapper"><select class="prima-input" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'">';
			$select_value = stripslashes(prima_get_setting($value['id'],$field));
			foreach ($value['options'] as $option => $name) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';}
			     }
				 else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				 echo '<option'. $selected .' value="'.$option.'">';
				 echo $name;
				 echo '</option>';
			 }
			 echo '</select></div>';
		break;
		case 'font':
			echo '<div class="select_wrapper"><select class="prima-input" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'">';
			$selected = stripslashes(prima_get_setting($value['id'],$field));
			echo '<option style="padding-right: 10px;" value="">'.__("Default Font", 'primathemes').'</option>';
			$webfonts = prima_webfonts();
			if ( $webfonts ) {
				echo '<optgroup label="--- Standard Fonts ---">';
				foreach ($webfonts as $option ) {
					$label = $option['label'];
					if ( $selected == $option['value'] ) // Make default first in list
						echo "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					else
						echo "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
				}
				echo '</optgroup>';
			}
			$googlefonts = prima_googlefonts();
			if ( $googlefonts ) {
				echo '<optgroup label="--- Google Fonts ---">';
				foreach ($googlefonts as $option ) {
					$label = $option['label'];
					if ( $selected == $option['value'] ) // Make default first in list
						echo "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					else
						echo "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
				}
				echo '</optgroup>';
			}
			 echo '</select></div>';
			$customfonts = prima_customfonts();
			if ( $customfonts ) {
				echo '<optgroup label="--- Custom Fonts ---">';
				foreach ($customfonts as $option ) {
					$label = $option['label'];
					if ( $selected == $option['value'] ) // Make default first in list
						echo "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					else
						echo "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
				}
				echo '</optgroup>';
			}
		break;
		case 'calendar':
			$val = ( prima_get_setting($value['id'],$field) != "" ) ? prima_get_setting($value['id'],$field) : $value['std'];
            echo '<input class="prima-input-calendar" type="text" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'" value="'.$val.'">';
		break;
		case 'time':
			$val = ( prima_get_setting($value['id'],$field) != "" ) ? prima_get_setting($value['id'],$field) : $value['std'];
			echo '<input class="prima-input-time" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'" type="text" value="'. $val .'" />';
		break;
		case 'textarea':
			$cols = '8';
			$ta_value = '';
			if(isset($value['std'])) {
				// $ta_value = $value['std'];
				if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])) { $cols = $ta_options['cols']; }
					else { $cols = '8'; }
				}

			}
			$std = prima_get_setting($value['id'],$field);
			if( $std != "") { $ta_value = stripslashes( $std ); }
			echo '<textarea class="prima-input" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'" cols="'. $cols .'" rows="8">'.esc_textarea( $ta_value ).'</textarea>';
		break;
		case 'wysiwyg':
			$ta_value = '';
			if(isset($value['std'])) {
				$ta_value = $value['std'];
			}
			$std = prima_get_setting($value['id'],$field);
			if( $std != "") { $ta_value = stripslashes( $std ); }
			$args = array(
				'textarea_name' => $field.'['.$value['id'].']',
				'wpautop' => true,
				'textarea_rows'=> 10,
				'media_buttons' => true
			);
			wp_editor( $ta_value, $field.'-'.$value['id'], $args );
		break;
		case "radio":
			$select_value = prima_get_setting($value['id'],$field);
			foreach ($value['options'] as $key => $option)
			{
				$checked = '';
				if($select_value != '') {
					if ( $select_value == $key) { $checked = ' checked'; }
				}
				else {
					if ($value['std'] == $key) { $checked = ' checked'; }
				}
				echo '<input class="prima-input prima-radio" type="radio" name="'.$field.'['.$value['id'].']" value="'. $key .'" '. $checked .' />' . $option .'<br />';
			}
		break;
		case "checkbox":
			$std = $value['std'];
			$saved_std = prima_get_setting($value['id'],$field);
			$checked = '';
			if($saved_std == 'true') { $checked = 'checked="checked"'; }
			else { $checked = '';}
			echo '<input type="checkbox" class="checkbox prima-input" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'" value="true" '. $checked .' />';
		break;
		case "multicheck":
			$std =  $value['std'];
			foreach ($value['options'] as $key => $option) {
				$prima_key = $value['id'] . '_' . $key;
				$saved_std = prima_get_setting($prima_key,$field);
				if($saved_std == 'true'){ $checked = 'checked="checked"'; }
				else{ $checked = ''; }
				echo '<input type="checkbox" class="checkbox prima-input" name="'.$field.'['.$prima_key.']" id="'.$field.'-'.$prima_key.'" value="true" '. $checked .' /><label for="'. $prima_key .'">'. $option .'</label><br />';
			}
		break;
		case "upload":
			$formid = $field.'-'.$value['id'];
			$page_id = prima_framework_post_id( $value['id'] );
			if ( !$page_id )
			{
				$_p = array();
				$_p['post_title'] = $value['id'];
				$_p['post_status'] = 'private';
				$_p['post_type'] = 'primaframework';
				$_p['comment_status'] = 'closed';
				$_p['ping_status'] = 'closed';
				$page_id = wp_insert_post( $_p );
			}
			$saved_std = prima_get_setting($value['id'],$field);
			$uploadclass = ( $saved_std ) ? 'has-file' : '';
			echo '<input type="text" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'" value="'.$saved_std.'" class="upload '.$uploadclass.'" />';
			echo '<input id="upload_'.$field.'-'.$value['id'].'" class="upload_button button button-highlighted" type="button" value="Upload" rel="'.$page_id.'" />';
			echo '<div class="screenshot" id="'.$field.'-'.$value['id'].'_image">';
			  if ( $saved_std != '' )
			  {
				$remove = '<a href="#" class="remove">Remove</a>';
				$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $saved_std );
				if ( $image )
				{
				  echo '<img src="'.$saved_std.'" alt="" />'.$remove.'';
				}
				else
				{
				  $parts = explode( "/", $saved_std );
				  for( $i = 0; $i < sizeof($parts); ++$i )
				  {
					$title = $parts[$i];
				  }
				  echo '<div class="no_image"><a href="'.$saved_std.'">'.$title.'</a>'.$remove.'</div>';
				}
			  }
			echo '</div>';
		break;
		case "upload_min":

		break;
		case "color":
			$val = $value['std'];
			$stored  = prima_get_setting($value['id'],$field);
			if ( $stored != "") { $val = $stored; }
			echo '<script type="text/javascript" language="javascript">'."\n";
			echo 'jQuery(document).ready(function(){'."\n";
			echo "jQuery('#".$field."-".$value['id']."_picker').children('div').css('backgroundColor', '".$val."');
						jQuery('#".$field."-".$value['id']."_picker').ColorPicker({
						color: '".$val."',
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							//jQuery(this).css('border','1px solid red');
							jQuery('#".$field."-".$value['id']."_picker').children('div').css('backgroundColor', '#' + hex);
							jQuery('#".$field."-".$value['id']."_picker').next('input').attr('value','#' + hex);
						}
					});
			});
			</script>";
			echo '<div id="'.$field.'-'.$value['id'].'_picker" class="colorSelector"><div></div></div>';
			echo '<input class="prima-color" name="'.$field.'['.$value['id'].']" id="'.$field.'-'.$value['id'].'" type="text" value="'. $val .'" />';
		break;
		case "typography":
			$default = $value['std'];
			$typography_stored = prima_get_setting($value['id'],$field);
			/* Font Size */
			$val = $default['size'];
			if ( $typography_stored['size'] != "") { $val = $typography_stored['size']; }
			if ( $typography_stored['unit'] == 'px'){ $show_px = ''; $show_em = ' style="display:none" '; $name_px = ' name="'.$field.'['.$value['id'].'][size]" '; $name_em = ''; }
			else if ( $typography_stored['unit'] == 'em'){ $show_em = ''; $show_px = 'style="display:none"'; $name_em = ' name="'.$field.'['.$value['id'].'][size]" '; $name_px = ''; }
			else { $show_px = ''; $show_em = ' style="display:none" '; $name_px = ' name="'.$field.'['.$value['id'].'][size]" '; $name_em = ''; }
			echo '<select class="prima-typography prima-typography-size prima-typography-size-px"  id="'.$field.'-'.$value['id'].'_size" '. $name_px . $show_px .'>';
				for ($i = 9; $i < 71; $i++){
					if($val == strval($i)){ $active = 'selected="selected"'; } else { $active = ''; }
					echo '<option value="'. $i .'" ' . $active . '>'. $i .'</option>'; }
			echo '</select>';
			echo '<select class="prima-typography prima-typography-size prima-typography-size-em" id="'.$field.'-'.$value['id'].'_size" '. $name_em . $show_em.'>';
				$em = 0.5;
				for ($i = 0; $i < 39; $i++){
					if ($i <= 24)			// up to 2.0em in 0.1 increments
						$em = $em + 0.1;
					elseif ($i >= 14 && $i <= 24)		// Above 2.0em to 3.0em in 0.2 increments
						$em = $em + 0.2;
					elseif ($i >= 24)		// Above 3.0em in 0.5 increments
						$em = $em + 0.5;
					if($val == strval($em)){ $active = 'selected="selected"'; } else { $active = ''; }
					//echo ' '. $value['id'] .' val:'.floatval($val). ' -> ' . floatval($em) . ' $<br />' ;
					echo '<option value="'. $em .'" ' . $active . '>'. $em .'</option>'; }
			echo '</select>';

			/* Font Unit */
			$val = $default['unit'];
			if ( $typography_stored['unit'] != "") { $val = $typography_stored['unit']; }
				$em = ''; $px = '';
			if($val == 'em'){ $em = 'selected="selected"'; }
			if($val == 'px'){ $px = 'selected="selected"'; }
			echo '<select class="prima-typography prima-typography-unit" name="'.$field.'['.$value['id'].'][unit]" id="'.$field.'-'.$value['id'].'_unit">';
			echo '<option value="px" '. $px .'">px</option>';
			echo '<option value="em" '. $em .'>em</option>';
			echo '</select>';

			/* Font Face */
			$val = $default['face'];
			if ( isset($typography_stored['face']) && $typography_stored['face'] != "")
				$val = $typography_stored['face'];

			$font01 = '';
			$font02 = '';
			$font03 = '';
			$font04 = '';
			$font05 = '';
			$font06 = '';
			$font07 = '';
			$font08 = '';
			$font09 = '';
			$font10 = '';
			$font11 = '';
			$font12 = '';
			$font13 = '';
			$font14 = '';
			$font15 = '';

			// Google webfonts
			$font16 = '';
			$font17 = '';
			$font18 = '';
			$font19 = '';
			$font20 = '';
			$font21 = '';
			$font22 = '';
			$font23 = '';
			$font24 = '';
			$font25 = '';
			$font26 = '';
			$font27 = '';
			$font28 = '';
			$font29 = '';
			$font30 = '';
			$font31 = '';
			$font32 = '';
			$font33 = '';
			$font34 = '';
			$font35 = '';
			$font36 = '';
			$font37 = '';
			$font38 = '';

			$fontstack = '';

			if (strpos($val, 'Arial, sans-serif') !== false){ $font01 = 'selected="selected"'; }
			if (strpos($val, 'Verdana, Geneva') !== false){ $font02 = 'selected="selected"'; }
			if (strpos($val, 'Trebuchet') !== false){ $font03 = 'selected="selected"'; }
			if (strpos($val, 'Georgia') !== false){ $font04 = 'selected="selected"'; }
			if (strpos($val, 'Times New Roman') !== false){ $font05 = 'selected="selected"'; }
			if (strpos($val, 'Tahoma, Geneva') !== false){ $font06 = 'selected="selected"'; }
			if (strpos($val, 'Palatino') !== false){ $font07 = 'selected="selected"'; }
			if (strpos($val, 'Helvetica') !== false){ $font08 = 'selected="selected"'; }
			if (strpos($val, 'Calibri') !== false){ $font09 = 'selected="selected"'; }
			if (strpos($val, 'Myriad') !== false){ $font10 = 'selected="selected"'; }
			if (strpos($val, 'Lucida') !== false){ $font11 = 'selected="selected"'; }
			if (strpos($val, 'Arial Black') !== false){ $font12 = 'selected="selected"'; }
			if (strpos($val, 'Gill') !== false){ $font13 = 'selected="selected"'; }
			if (strpos($val, 'Geneva, Tahoma') !== false){ $font14 = 'selected="selected"'; }
			if (strpos($val, 'Impact') !== false){ $font15 = 'selected="selected"'; }

			// Google webfonts
			if (strpos($val, 'Cantarell') !== false){ $font16 = 'selected="selected"'; }
			if (strpos($val, 'Cardo') !== false){ $font17 = 'selected="selected"'; }
			if (strpos($val, 'Crimson Text') !== false){ $font18 = 'selected="selected"'; }
			if (strpos($val, 'Droid Sans') !== false){ $font19 = 'selected="selected"'; }
			if (strpos($val, 'Droid Sans Mono') !== false){ $font20 = 'selected="selected"'; }
			if (strpos($val, 'Droid Serif') !== false){ $font21 = 'selected="selected"'; }
			if (strpos($val, 'IM Fell DW Pica') !== false){ $font22 = 'selected="selected"'; }
			if (strpos($val, 'Inconsolata') !== false){ $font23 = 'selected="selected"'; }
			if (strpos($val, 'Josefin Sans Std Light') !== false){ $font24 = 'selected="selected"'; }
			if (strpos($val, 'Lobster') !== false){ $font25 = 'selected="selected"'; }
			if (strpos($val, 'Molengo') !== false){ $font26 = 'selected="selected"'; }
			if (strpos($val, 'Nobile') !== false){ $font27 = 'selected="selected"'; }
			if (strpos($val, 'OFL Sorts Mill Goudy TT') !== false){ $font28 = 'selected="selected"'; }
			if (strpos($val, 'Old Standard TT') !== false){ $font29 = 'selected="selected"'; }
			if (strpos($val, 'Reenie Beanie') !== false){ $font30 = 'selected="selected"'; }
			if (strpos($val, 'Tangerine') !== false){ $font31 = 'selected="selected"'; }
			if (strpos($val, 'Vollkorn') !== false){ $font32 = 'selected="selected"'; }
			if (strpos($val, 'Yanone Kaffeesatz') !== false){ $font33 = 'selected="selected"'; }
			if (strpos($val, 'Cuprum') !== false){ $font34 = 'selected="selected"'; }
			if (strpos($val, 'Neucha') !== false){ $font35 = 'selected="selected"'; }
			if (strpos($val, 'Neuton') !== false){ $font36 = 'selected="selected"'; }
			if (strpos($val, 'PT Sans') !== false){ $font37 = 'selected="selected"'; }
			if (strpos($val, 'Philosopher') !== false){ $font38 = 'selected="selected"'; }

			echo '<select class="prima-typography prima-typography-face" name="'.$field.'['.$value['id'].'][face]" id="'.$field.'-'.$value['id'].'_face">';
			echo '<option value="Arial, sans-serif" '. $font01 .'>Arial</option>';
			echo '<option value="Verdana, Geneva, sans-serif" '. $font02 .'>Verdana</option>';
			echo '<option value="&quot;Trebuchet MS&quot;, Tahoma, sans-serif"'. $font03 .'>Trebuchet</option>';
			echo '<option value="Georgia, serif" '. $font04 .'>Georgia</option>';
			echo '<option value="&quot;Times New Roman&quot;, serif"'. $font05 .'>Times New Roman</option>';
			echo '<option value="Tahoma, Geneva, Verdana, sans-serif"'. $font06 .'>Tahoma</option>';
			echo '<option value="Palatino, &quot;Palatino Linotype&quot;, serif"'. $font07 .'>Palatino</option>';
			echo '<option value="&quot;Helvetica Neue&quot;, Helvetica, sans-serif" '. $font08 .'>Helvetica*</option>';
			echo '<option value="Calibri, Candara, Segoe, Optima, sans-serif"'. $font09 .'>Calibri*</option>';
			echo '<option value="&quot;Myriad Pro&quot;, Myriad, sans-serif"'. $font10 .'>Myriad Pro*</option>';
			echo '<option value="&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, sans-serif"'. $font11 .'>Lucida</option>';
			echo '<option value="&quot;Arial Black&quot;, sans-serif" '. $font12 .'>Arial Black</option>';
			echo '<option value="&quot;Gill Sans&quot;, &quot;Gill Sans MT&quot;, Calibri, sans-serif" '. $font13 .'>Gill Sans*</option>';
			echo '<option value="Geneva, Tahoma, Verdana, sans-serif" '. $font14 .'>Geneva*</option>';
			echo '<option value="Impact, Charcoal, sans-serif" '. $font15 .'>Impact</option>';

			// Google webfonts
			echo '<option value="">-- Google Fonts --</option>';
			echo '<option value="Cantarell, Arial, serif" '. $font16 .'>Cantarell</option>';
			echo '<option value="Cardo, Arial, serif" '. $font17 .'>Cardo</option>';
			echo '<option value="Crimson Text, Arial, serif" '. $font18 .'>Crimson Text</option>';
			echo '<option value="Droid Sans, Arial, serif" '. $font19 .'>Droid Sans</option>';
			echo '<option value="Droid Sans Mono, Arial, serif" '. $font20 .'>Droid Sans Mono</option>';
			echo '<option value="Droid Serif, Arial, serif" '. $font21 .'>Droid Serif</option>';
			echo '<option value="IM Fell DW Pica, Arial, serif" '. $font22 .'>IM Fell DW Pica</option>';
			echo '<option value="Inconsolata, Arial, serif" '. $font23 .'>Inconsolata</option>';
			echo '<option value="Josefin Sans Std Light, Arial, serif" '. $font24 .'>Josefin Sans Std Light</option>';
			echo '<option value="Lobster, Arial, serif" '. $font25 .'>Lobster</option>';
			echo '<option value="Molengo, Arial, serif" '. $font26 .'>Molengo</option>';
			echo '<option value="Nobile, Arial, serif" '. $font27 .'>Nobile</option>';
			echo '<option value="OFL Sorts Mill Goudy TT, Arial, serif" '. $font28 .'>OFL Sorts Mill Goudy TT</option>';
			echo '<option value="Old Standard TT, Arial, serif" '. $font29 .'>Old Standard TT</option>';
			echo '<option value="Reenie Beanie, Arial, serif" '. $font30 .'>Reenie Beanie</option>';
			echo '<option value="Tangerine, Arial, serif" '. $font31 .'>Tangerine</option>';
			echo '<option value="Vollkorn, Arial, serif" '. $font32 .'>Vollkorn</option>';
			echo '<option value="Yanone Kaffeesatz, Arial, serif" '. $font33 .'>Yanone Kaffeesatz</option>';
			echo '<option value="Cuprum, Arial, serif" '. $font34 .'>Cuprum</option>';
			echo '<option value="Neucha, Arial, serif" '. $font35 .'>Neucha</option>';
			echo '<option value="Neuton, Arial, serif" '. $font36 .'>Neuton</option>';
			echo '<option value="PT Sans, Arial, serif" '. $font37 .'>PT Sans</option>';
			echo '<option value="Philosopher, Arial, serif" '. $font38 .'>Philosopher</option>';

			$new_stacks = get_option('framework_prima_font_stack');

			if(!empty($new_stacks)){
				echo '<option value="">-- Custom Font Stacks --</option>';
				foreach($new_stacks as $name => $stack){
					if (strpos($val, $stack) !== false){ $fontstack = 'selected="selected"'; } else { $fontstack = ''; }
					echo '<option value="'. stripslashes(htmlentities($stack)) .'" '.$fontstack.'>'. str_replace('_',' ',$name).'</option>';
				}

			}

			echo '</select>';

			/* Font Weight */
			$val = $default['style'];
			if ( $typography_stored['style'] != "") { $val = $typography_stored['style']; }
				$normal = ''; $italic = ''; $bold = ''; $bolditalic = '';
			if($val == 'normal'){ $normal = 'selected="selected"'; }
			if($val == 'italic'){ $italic = 'selected="selected"'; }
			if($val == 'bold'){ $bold = 'selected="selected"'; }
			if($val == 'bold italic'){ $bolditalic = 'selected="selected"'; }
			echo '<select class="prima-typography prima-typography-style" name="'.$field.'['.$value['id'].'][style]" id="'.$field.'-'.$value['id'].'_style">';
			echo '<option value="normal" '. $normal .'>Normal</option>';
			echo '<option value="italic" '. $italic .'>Italic</option>';
			echo '<option value="bold" '. $bold .'>Bold</option>';
			echo '<option value="bold italic" '. $bolditalic .'>Bold/Italic</option>';
			echo '</select>';
			/* Font Color */
			$val = $default['color'];
			if ( $typography_stored['color'] != "") { $val = $typography_stored['color']; }
			echo '<script type="text/javascript" language="javascript">'."\n";
			echo 'jQuery(document).ready(function(){'."\n";
			echo "jQuery('#".$field."-".$value['id']."_color_picker').children('div').css('backgroundColor', '".$val."');
						jQuery('#".$field."-".$value['id']."_color_picker').ColorPicker({
						color: '".$val."',
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							//jQuery(this).css('border','1px solid red');
							jQuery('#".$field."-".$value['id']."_color_picker').children('div').css('backgroundColor', '#' + hex);
							jQuery('#".$field."-".$value['id']."_color_picker').next('input').attr('value','#' + hex);
						}
					});
			});
			</script>";
			echo '<div id="'.$field.'-'.$value['id'].'_color_picker" class="colorSelector"><div></div></div>';
			echo '<input class="prima-color prima-typography prima-typography-color" name="'.$field.'['.$value['id'].'][color]" id="'.$field.'-'.$value['id'].'_color" type="text" value="'. $val .'" />';
		break;
		case "border":
			$default = $value['std'];
			$border_stored = prima_get_setting($value['id'],$field);
			/* Border Width */
			if( !isset($default['width']) ) $default['width'] = '';
			if( !isset($default['style']) ) $default['style'] = '';
			if( !isset($default['color']) ) $default['color'] = '';
			$val = $default['width'];
			if ( isset($border_stored['width']) && $border_stored['width'] != "") { $val = $border_stored['width']; }
			echo '<select class="prima-border prima-border-width" name="'.$field.'['.$value['id'].'][width]" id="'.$field.'-'.$value['id'].'_width">';
				echo '<option value="">'.__('Default', 'primathemes').'</option>';
				for ($i = 0; $i < 21; $i++){
					if( $val != '' && $val == $i){ $active = 'selected="selected"'; } else { $active = ''; }
					echo '<option value="'. $i .'" ' . $active . '>'. $i .'px</option>'; }
			echo '</select>';
			/* Border Style */
			$val = $default['style'];
			if ( isset($border_stored['style']) && $border_stored['style'] != "") { $val = $border_stored['style']; }
				$solid = ''; $dashed = ''; $dotted = '';
			if($val == 'solid'){ $solid = 'selected="selected"'; }
			if($val == 'dashed'){ $dashed = 'selected="selected"'; }
			if($val == 'dotted'){ $dotted = 'selected="selected"'; }
			echo '<select class="prima-border prima-border-style" name="'.$field.'['.$value['id'].'][style]" id="'.$field.'-'.$value['id'].'_style">';
			echo '<option value="">'.__('Default', 'primathemes').'</option>';
			echo '<option value="solid" '. $solid .'>'.__('Solid', 'primathemes').'</option>';
			echo '<option value="dashed" '. $dashed .'>'.__('Dashed', 'primathemes').'</option>';
			echo '<option value="dotted" '. $dotted .'>'.__('Dotted', 'primathemes').'</option>';
			echo '</select>';
			/* Border Color */
			$val = $default['color'];
			if ( isset($border_stored['color']) && $border_stored['color'] != "") { $val = $border_stored['color']; }
			echo '<script type="text/javascript" language="javascript">'."\n";
			echo 'jQuery(document).ready(function(){'."\n";
			echo "jQuery('#".$field."-".$value['id']."_color_picker').children('div').css('backgroundColor', '".$val."');
						jQuery('#".$field."-".$value['id']."_color_picker').ColorPicker({
						color: '".$val."',
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							//jQuery(this).css('border','1px solid red');
							jQuery('#".$field."-".$value['id']."_color_picker').children('div').css('backgroundColor', '#' + hex);
							jQuery('#".$field."-".$value['id']."_color_picker').next('input').attr('value','#' + hex);
						}
					});
			});
			</script>";
			echo '<div id="'.$field.'-'.$value['id'].'_color_picker" class="colorSelector"><div></div></div>';
			echo '<input class="prima-color prima-border prima-border-color" name="'.$field.'['.$value['id'].'][color]" id="'.$field.'-'.$value['id'].'_color" type="text" value="'. $val .'" />';
		break;
		case "background":
			$default = $value['std'];
			$background_stored = prima_get_setting($value['id'],$field);
			/* Background Color */
			if( !isset($default['color']) ) $default['color'] = '';
			if( !isset($default['repeat']) ) $default['repeat'] = '';
			if( !isset($default['vposition']) ) $default['vposition'] = '';
			if( !isset($default['hposition']) ) $default['hposition'] = '';
			if( !isset($default['image']) ) $default['image'] = '';
			$val = $default['color'];
			if ( isset($background_stored['color']) && $background_stored['color'] != "") { $val = $background_stored['color']; }
			echo '<script type="text/javascript" language="javascript">'."\n";
			echo 'jQuery(document).ready(function(){'."\n";
			echo "jQuery('#".$field."-".$value['id']."_color_picker').children('div').css('backgroundColor', '".$val."');
						jQuery('#".$field."-".$value['id']."_color_picker').ColorPicker({
						color: '".$val."',
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							//jQuery(this).css('background','1px solid red');
							jQuery('#".$field."-".$value['id']."_color_picker').children('div').css('backgroundColor', '#' + hex);
							jQuery('#".$field."-".$value['id']."_color_picker').next('input').attr('value','#' + hex);
						}
					});
			});
			</script>";
			echo '<div id="'.$field.'-'.$value['id'].'_color_picker" class="colorSelector"><div></div></div>';
			echo '<input class="prima-color prima-background prima-background-color" name="'.$field.'['.$value['id'].'][color]" id="'.$field.'-'.$value['id'].'_color" type="text" value="'. $val .'" />';
			/* Background Repeat */
			$val = $default['repeat'];
			if ( isset($background_stored['repeat']) && $background_stored['repeat'] != "") { $val = $background_stored['repeat']; }
				$norepeat = ''; $repeat = ''; $repeatx = ''; $repeaty = '';
			if($val == 'no-repeat'){ $norepeat = 'selected="selected"'; }
			if($val == 'repeat'){ $repeat = 'selected="selected"'; }
			if($val == 'repeat-x'){ $repeatx = 'selected="selected"'; }
			if($val == 'repeat-y'){ $repeaty = 'selected="selected"'; }
			echo '<select class="prima-background prima-background-repeat" name="'.$field.'['.$value['id'].'][repeat]" id="'.$field.'-'.$value['id'].'_repeat">';
			echo '<option value="no-repeat" '. $norepeat .'>no-repeat</option>';
			echo '<option value="repeat" '. $repeat .'>repeat</option>';
			echo '<option value="repeat-x" '. $repeatx .'>repeat-x</option>';
			echo '<option value="repeat-y" '. $repeaty .'>repeat-y</option>';
			echo '</select>';
			/* Background V Position */
			$val = $default['vposition'];
			if ( isset($background_stored['vposition']) && $background_stored['vposition'] != "") { $val = $background_stored['vposition']; }
				$top = ''; $center = ''; $bottom = '';
			if($val == 'top'){ $top = 'selected="selected"'; }
			if($val == 'center'){ $center = 'selected="selected"'; }
			if($val == 'bottom'){ $bottom = 'selected="selected"'; }
			echo '<select class="prima-background prima-background-vposition" name="'.$field.'['.$value['id'].'][vposition]" id="'.$field.'-'.$value['id'].'_vposition">';
			echo '<option value="top" '. $top .'>top</option>';
			echo '<option value="center" '. $center .'>center</option>';
			echo '<option value="bottom" '. $bottom .'>bottom</option>';
			echo '</select>';
			/* Background H Position */
			$val = $default['hposition'];
			if ( isset($background_stored['hposition']) && $background_stored['hposition'] != "") { $val = $background_stored['hposition']; }
				$left = ''; $center = ''; $right = '';
			if($val == 'left'){ $left = 'selected="selected"'; }
			if($val == 'center'){ $center = 'selected="selected"'; }
			if($val == 'right'){ $right = 'selected="selected"'; }
			echo '<select class="prima-background prima-background-hposition" name="'.$field.'['.$value['id'].'][hposition]" id="'.$field.'-'.$value['id'].'_hposition">';
			echo '<option value="left" '. $left .'>left</option>';
			echo '<option value="center" '. $center .'>center</option>';
			echo '<option value="right" '. $right .'>right</option>';
			echo '</select>';
			/* Background Image */
			$formid = $field.'-'.$value['id'].'_image';
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
			$val = $default['image'];
			if ( isset($background_stored['image']) && $background_stored['image'] != "") { $val = $background_stored['image']; }
			$uploadclass = ( $val ) ? 'has-file' : '';
			echo '<input type="text" name="'.$field.'['.$value['id'].'][image]" id="'.$field.'-'.$value['id'].'_image" value="'.$val.'" class="upload '.$uploadclass.'" />';
			echo '<input id="upload_'.$field.'-'.$value['id'].'_image" class="upload_button button button-highlighted" type="button" value="Upload" rel="'.$page_id.'" />';
			echo '<div class="screenshot" id="'.$field.'-'.$value['id'].'_image_image">';
			  if ( $val != '' )
			  {
				$remove = '<a href="#" class="remove">Remove</a>';
				$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $val );
				if ( $image )
				{
				  echo '<img src="'.$val.'" alt="" />'.$remove.'';
				}
				else
				{
				  $parts = explode( "/", $val );
				  for( $i = 0; $i < sizeof($parts); ++$i )
				  {
					$title = $parts[$i];
				  }
				  echo '<div class="no_image"><a href="'.$val.'">'.$title.'</a>'.$remove.'</div>';
				}
			  }
			echo '</div>';
		break;
		case "images":
			$i = 0;
			$select_value = prima_get_setting($value['id'],$field);
			foreach ($value['options'] as $key => $option)
			{
				$i++;
				$checked = '';
				$selected = '';
				if($select_value != '') {
					if ( $select_value == $key) { $checked = ' checked'; $selected = 'prima-radio-img-selected'; }
				}
				else {
					if ($value['std'] == $key) { $checked = ' checked'; $selected = 'prima-radio-img-selected'; }
					elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'prima-radio-img-selected'; }
					elseif ($i == 1  && $value['std'] == '') { $checked = ' checked'; $selected = 'prima-radio-img-selected'; }
					else { $checked = ''; }
				}
				echo '<span>';
				echo '<input type="radio" id="prima-radio-img-' . $value['id'] . $i . '" class="checkbox prima-radio-img-radio" value="'.$key.'" name="'.$field.'['.$value['id'].']" '.$checked.' />';
				echo '<div class="prima-radio-img-label">'. $key .'</div>';
				echo '<img src="'.$option.'" alt="'.$key.'" class="prima-radio-img-img '. $selected .'" onClick="document.getElementById(\'prima-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				echo '</span>';

			}
		break;
		case "info":
			$default = $value['std'];
			echo '<div class="prima-info-box">';
			echo $default;
			echo '</div>';
		break;
		case "heading":
			if($counter >= 2){
			   echo '</div>'."\n";
			}
			$jquery_click_hook = preg_replace('/[^A-Za-z0-9]/', '', strtolower($value['name']) );
			$jquery_click_hook = "prima-option-" . $jquery_click_hook;
			$iconclass = isset( $value['icon'] ) ? 'class="'.$value['icon'].'"' : '';
			echo '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
		break;
		}
		// if TYPE is an array, formatted into smaller inputs... ie smaller values
		if ( is_array($value['type'])) {
			foreach($value['type'] as $array){
				$id = $array['id'];
				$std = $array['std'];
				$saved_std = prima_get_setting($id,$field);
				if($saved_std != $std){$std = $saved_std;}
				$meta = $array['meta'];
				if($array['type'] == 'text') { // Only text at this point
					 echo '<input class="input-text-small prima-input" name="'.$field.'['.$id.']" id="'.$field.'-'.$id.'" type="text" value="'. htmlspecialchars( stripslashes( $std ), ENT_QUOTES) .'" />';
					 echo '<span class="meta-two">'.$meta.'</span>';
				}
			}
		}
		if ( $value['type'] != "heading" && $value['type'] != "note" ) {
			if ( $value['type'] != "checkbox" ) { echo '<br/>'; }
			if(!isset($value['desc'])){ $explain_value = ''; } else{ $explain_value = $value['desc']; }
			echo '</div><div class="explain">'. htmlspecialchars_decode( $explain_value ) .'</div>'."\n";
			echo '<div class="clear"> </div></div></div>'."\n";
		}
		elseif ( $value['type'] == "note" ) {
			$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			//echo '<div class="section section-'. $value['type'] .'">'."\n".'<div class="option-inner">'."\n";
			echo '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			echo '<h3 class="heading">'. htmlspecialchars_decode( $value['name'] ).'</h3>'."\n";
			echo '<div class="option">'. htmlspecialchars_decode( $value['desc'] );
			echo '<div class="clear"> </div></div></div>'."\n";
		}
	}
	echo '</div>';
}

function prima_settings_menu_generator( $options, $field ) {
    $counter = 0;
	foreach ($options as $value) {
		switch ( $value['type'] ) {
		case "heading":
			$jquery_click_hook = preg_replace('/[^A-Za-z0-9]/', '', strtolower($value['name']) );
			$jquery_click_hook = "prima-option-" . $jquery_click_hook;
			$iconclass = isset( $value['icon'] ) ? 'class="'.$value['icon'].'"' : '';
			echo '<li '.$iconclass.'><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
		break;
		}
	}
}
