<?php
add_action( 'admin_menu', 'prima_design_settings_init' );
function prima_design_settings_init() {
	global $menu, $prima, $prima_child_data;
	$theme_data = $prima_child_data;
	$prima->design_settings = add_theme_page( sprintf( __( '%1$s Design Settings', 'primathemes' ), $theme_data['Name'] ), __( 'Design Settings', 'primathemes' ), apply_filters( "prima_settings_capability", 'edit_theme_options' ), 'primathemes-design', 'prima_design_settings_page' );
	add_action( "load-{$prima->design_settings}", 'prima_theme_settings_enqueue_script' );
	add_action( "load-{$prima->design_settings}", 'prima_theme_settings_enqueue_style' );
}
add_action('admin_init', 'prima_register_design_settings');
function prima_register_design_settings() {
	register_setting( PRIMA_DESIGN_SETTINGS, PRIMA_DESIGN_SETTINGS, 'prima_save_design_settings' );
	add_option( PRIMA_DESIGN_SETTINGS, prima_default_design_settings(), '', 'yes' );
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-design' )
		return;
	if ( prima_get_setting('reset',PRIMA_DESIGN_SETTINGS) ) {
		update_option( PRIMA_DESIGN_SETTINGS, prima_default_design_settings() );
		wp_redirect( admin_url( 'themes.php?page=primathemes-design&reset=true' ) );
		exit;
	}
}
function prima_design_settings() {
	$settings = array();
	return apply_filters( 'prima_design_settings_args', $settings );
}
add_filter( 'prima_design_settings_args', 'prima_design_customcss_settings', 101 );
function prima_design_customcss_settings( $settings ) {
	$settings[] = array( "name" => __('Custom CSS Code', 'primathemes'),
						"icon" => "customcss",
						"type" => "heading");
	$settings[] = array( "name" => __('Custom CSS Text', 'primathemes'),
						"desc" => __( 'Add custom CSS code quickly to your theme', 'primathemes' ),
						"std" => '',
						"id" => "custom_css",
						"type" => "textarea");
	return $settings;
}
function prima_default_design_settings() {
	$settings = prima_design_settings();
	if ( false === $settings ) return false;
	$defaut_settings = array();
	foreach ($settings as $setting) {
		if (isset($setting['id'])) $defaut_settings[$setting['id']] = isset($setting['std']) ? $setting['std'] : '';
	}
	return $defaut_settings;
}
function prima_save_design_settings( $settings ) {
	return apply_filters( "prima_validate_design_settings", $settings );
}
function prima_design_settings_page() {
	global $prima, $prima_child_data;
	$theme_data = $prima_child_data; ?>
	<div class="wrap" id="prima-settings">
        <form method="post" enctype="multipart/form-data" action="options.php">
		<?php settings_fields( PRIMA_DESIGN_SETTINGS ); ?>
        <div class="prima-settings-header">
            <div class="prima_header_title"><?php printf( __( '%1$s Design Settings', 'primathemes' ), $theme_data['Name'] ); ?></div>
            <input type="submit" class="button-save button-primary submit-button" value="<?php _e( 'Save Settings', 'primathemes' ); ?>" />
            <input type="submit" name="<?php echo PRIMA_DESIGN_SETTINGS; ?>[reset]"  class="button-reset button-highlighted submit-button reset-button" value="<?php _e( 'Reset Settings', 'primathemes' ); ?>" />
        </div>
        <?php
		if ( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {
			echo '<div id="prima-settings-message" class="">'.__('Design Settings Saved', 'primathemes').'</div>';
			prima_design_output();
		}
		elseif ( isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] == 'true') {  
			echo '<div id="prima-settings-message" class="">'.__('Design Settings Saved', 'primathemes').'</div>';
			prima_design_output();
		}
		elseif ( isset( $_REQUEST['reset'] ) && $_REQUEST['reset'] == 'true' ) {
			echo '<div id="prima-settings-message" class="">'.__('Design Settings Reset', 'primathemes').'</div>';
			prima_design_output();
		}
		?>
        <div id="prima-main">
	        <div id="prima-nav">
				<ul>
					<?php prima_settings_menu_generator( prima_design_settings(), PRIMA_DESIGN_SETTINGS ); ?>
				</ul>		
			</div>
			<div id="prima-content">
				<?php prima_settings_generator( prima_design_settings(), PRIMA_DESIGN_SETTINGS ); ?>
	        </div>
	        <div class="clear"></div>
        </div>
        <div class="prima-settings-footer">
            <input type="submit" class="button-save button-primary submit-button" value="<?php _e( 'Save Settings', 'primathemes' ); ?>" />
            <input type="submit" name="<?php echo PRIMA_DESIGN_SETTINGS; ?>[reset]"  class="button-reset button-highlighted submit-button reset-button" value="<?php _e( 'Reset Settings', 'primathemes' ); ?>" />
        </div>
	<?php 
    // $settings = get_option( PRIMA_DESIGN_SETTINGS ); print_r($settings); 
    // echo '<br/><br/>';print_r(prima_default_theme_settings()); 
    // echo '<br/><br/>';print_r(prima_theme_settings()); 
    ?>
    </form>
	</div><!-- .wrap -->
	<?php
}
function prima_design_output() {
	$settings = array();
	$settings = apply_filters( 'prima_design_settings_args', $settings );
	if ( !$settings ) return;
	$output = '';
	foreach ($settings as $setting) {
		if( $setting['type']== 'color' ) {
			if ( isset($setting['selector']) && ($setting['selector']!='') ) {
				$attribute =  ( isset($setting['attribute']) && ($setting['attribute']!='') ) ? $setting['attribute'] : 'color';
				$important =  ( isset($setting['important']) && $setting['important'] ) ? ' !important' : '';
				$value = prima_get_setting( $setting['id'], PRIMA_DESIGN_SETTINGS );
				if ( $value ) $output .= $setting['selector'].'{'.$attribute.':'.$value.$important.'}';
			}
		}
		elseif( $setting['type']== 'background' ) {
			if ( isset($setting['selector']) && ($setting['selector']!='') ) {
				$value = prima_get_setting( $setting['id'], PRIMA_DESIGN_SETTINGS );
				if ( $value ) {
					$bgcolor = isset($value['color']) && $value['color'] ? $value['color'] : '';
					$bgimage = isset($value['image']) && $value['image'] ? 'url("'.$value['image'].'")' : '';
					if ( $bgcolor || $bgimage ) {
						$bgrepeat = $bgimage && isset($value['repeat']) ? $value['repeat'] : '';
						$bghposition = $bgimage && isset($value['hposition']) ? $value['hposition'] : '';
						$bgvposition = $bgimage && isset($value['vposition']) ? $value['vposition'] : '';
						$output .= $setting['selector'].'{background:'.$bgcolor.' '.$bgimage.' '.$bgrepeat.' '.$bghposition.' '.$bgvposition.'}';
						if ( isset($setting['selector_color']) && ($setting['selector_color']!='') ) {
							$output .= $setting['selector_color'].'{background:'.$bgcolor.'}';
						}
					}
				}
			}
		}
		elseif( $setting['type']== 'border' ) {
			if ( isset($setting['selector']) && ($setting['selector']!='') ) {
				$value = prima_get_setting( $setting['id'], PRIMA_DESIGN_SETTINGS );
				if ( $value ) {
					$attribute =  ( isset($setting['attribute']) && ($setting['attribute']!='') ) ? $setting['attribute'] : 'border';
					$bordercolor = isset($value['color']) && $value['color'] ? $attribute.'-color:'.$value['color'].';' : '';
					$borderwidth = isset($value['width']) && $value['width'] ? $attribute.'-width:'.$value['width'].'px;' : '';
					$borderstyle = isset($value['style']) && $value['style'] ? $attribute.'-style:'.$value['style'].';' : '';
					$output .= $setting['selector'].'{'.$bordercolor.' '.$borderwidth.' '.$borderstyle.' }';
				}
			}
		}
		elseif( $setting['type']== 'font' ) {
			if ( isset($setting['selector']) && ($setting['selector']!='') ) {
				$value = prima_get_setting( $setting['id'], PRIMA_DESIGN_SETTINGS );
				if ( $value ) {
					$fonts = array_merge(prima_webfonts(), prima_customfonts(), prima_googlefonts());
					$fonts[$value]['font'] = str_replace('&quot;', '"', $fonts[$value]['font']);
					$output .= $setting['selector'].'{font-family:'.$fonts[$value]['font'].'}';
				}
			}
		}
	}
	update_option( PRIMA_DESIGN_SETTINGS.'_output', $output );
	$fonts = array_merge(prima_webfonts(), prima_customfonts(), prima_googlefonts());
	$fonts_output = '';
	foreach ($settings as $setting) {
		if( $setting['type']== 'font' ) {
			$getfont = $setting['id'];
			$fontsetting = prima_get_setting( $getfont, PRIMA_DESIGN_SETTINGS );  
			if ( $fontsetting && $fonts[$fontsetting]['type'] == 'google' ) {
				$googlefont = $fonts[$fontsetting]['label'];
				$googlefont = str_replace(" ","+",$googlefont);	
				$googlefontstyle = $fonts[$fontsetting]['style'] ? ':'.$fonts[$fontsetting]['style'] : '';
				$fonts_output .= '<link href="http://fonts.googleapis.com/css?family=' . $googlefont . $googlefontstyle . '" rel="stylesheet" type="text/css" />'."\n";
			}
		}
	}
	$fonts_output = str_replace('|"','"',$fonts_output);
	update_option( PRIMA_DESIGN_SETTINGS.'_fonts', $fonts_output );
}
