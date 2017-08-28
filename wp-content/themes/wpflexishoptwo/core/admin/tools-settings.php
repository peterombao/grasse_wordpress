<?php
/*
* Tools Settings
* 
* Export/Import Settings File using JSON
* Credits: GenesisFramework
*/
add_action( 'admin_menu', 'prima_tools_settings_init', 15 );
function prima_tools_settings_init() {
	global $menu, $prima, $prima_child_data;
	$theme_data = $prima_child_data;
	$prima->tools_settings = add_theme_page( sprintf( __( '%1$s Tools Settings', 'primathemes' ), $theme_data['Name'] ), __( 'Tools Settings', 'primathemes' ), apply_filters( "prima_settings_capability", 'edit_theme_options' ), 'primathemes-tools', 'prima_tools_settings_page' );
	add_action( "load-{$prima->tools_settings}", 'prima_theme_settings_enqueue_script' );
	add_action( "load-{$prima->tools_settings}", 'prima_theme_settings_enqueue_style' );
}
function prima_tools_settings() {
	$settings = array();
	$settings["theme-settings"] = array( "name" => __('Theme Settings', 'primathemes'),
						"id" => "theme-settings",
						"table" => PRIMA_THEME_SETTINGS);
	if ( current_theme_supports('prima-design-settings') )
		$settings["design-settings"] = array( "name" => __('Design Settings', 'primathemes'),
							"id" => "design-settings",
							"table" => PRIMA_DESIGN_SETTINGS);
	if ( current_theme_supports('prima-seo-settings') )
		$settings["seo-settings"] = array( "name" => __('SEO Settings', 'primathemes'),
							"id" => "seo-settings",
							"table" => PRIMA_SEO_SETTINGS);
	if ( current_theme_supports('prima-sidebar-settings') )
		$settings["sidebar-settings"] = array( "name" => __('Sidebar Settings', 'primathemes'),
							"id" => "sidebar-settings",
							"table" => PRIMA_SIDEBAR_SETTINGS);
	return apply_filters( 'prima_tools_settings_args', $settings );
}
add_action( 'admin_init', 'primathemes_export' );
function primathemes_export() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-tools' )
		return;
	if ( empty( $_REQUEST['primathemes-export'] ) )
		return;
	check_admin_referer('primathemes-export');
	global $prima_child_data;
	$theme_data = $prima_child_data;
	$export_settings = prima_tools_settings();
	$settings = array();
	if ( $_REQUEST['primathemes-export'] === 'all' ) {
		foreach ($export_settings as $export_setting) {
			$settings[$export_setting['table']] = get_option( $export_setting['table'] );
		}
		$prefix = str_replace(' ', '', ucwords($theme_data['Name'])).'-all-settings';
	}
	else {
		$settings = array(
			$export_settings[$_REQUEST['primathemes-export']]['table'] => get_option( $export_settings[$_REQUEST['primathemes-export']]['table'] )
		);
		$prefix = str_replace(' ', '', ucwords($theme_data['Name'])).'-'.$export_settings[$_REQUEST['primathemes-export']]['id'];
	}
	if ( !$settings ) return;
    $output = json_encode( (array)$settings );
    header( 'Content-Description: File Transfer' );
    header( 'Cache-Control: public, must-revalidate' );
    header( 'Pragma: hack' );
    header( 'Content-Type: text/plain' );
    header( 'Content-Disposition: attachment; filename="' . $prefix . '-' . date("Ymd-His") . '.json"' );
    header( 'Content-Length: ' . strlen($output) );
    echo $output;
    exit;
}
add_action( 'admin_init', 'primathemes_import' );
function primathemes_import() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-tools' )
		return;
	if ( empty( $_REQUEST['primathemes-import'] ) )
		return;
	check_admin_referer('primathemes-import');
	$overrides = array( 'test_form' => false, 'test_type' => false );
	$_FILES['primathemes-import-upload']['name'] .= '.txt';
	$file = wp_handle_upload( $_FILES['primathemes-import-upload'], $overrides );
	if ( isset( $file['error'] ) ) {
		wp_redirect( admin_url( 'themes.php?page=primathemes-tools&error=true' ) );
		exit;
	}
	$upload = wp_remote_retrieve_body(
		wp_remote_request( $file['url'], 
			array( 'timeout' => 100, )
		)
	);
	$options = json_decode( $upload, true );
	if ( !$options ) {
		wp_redirect( admin_url( 'themes.php?page=primathemes-tools&error=true' ) );
		exit;
	}
	foreach ( (array)$options as $key => $settings ) {
		update_option( $key, $settings );
	}
	wp_redirect( admin_url( 'themes.php?page=primathemes-tools&imported=true' ) );
	exit;
	
}
function prima_tools_settings_page() {
	global $prima, $prima_child_data;
	$theme_data = $prima_child_data; ?>
	<div class="wrap" id="prima-settings">
        <div class="prima-settings-header">
            <div class="prima_header_title"><?php printf( __( '%1$s Tools Settings', 'primathemes' ), $theme_data['Name'] ); ?></div>
        </div>
        <?php
		if ( isset($_REQUEST['imported']) && $_REQUEST['imported'] == 'true') {  
			echo '<div id="prima-settings-message" class="">'.__('Settings successfully imported!', 'primathemes').'</div>';
			if ( current_theme_supports('prima-design-settings') ) {
				prima_design_output();
			}
		}
		elseif ( isset( $_REQUEST['error'] ) && $_REQUEST['error'] == 'true' ) {
			echo '<div id="prima-settings-message" class="">'.__('There was a problem importing your settings. Please Try again.', 'primathemes').'</div>';
		}
		?>
        <div id="prima-main">
	        <div id="prima-nav">
				<ul>
					<li class="export-settings">
                    <a title="<?php _e('Export Settings', 'primathemes'); ?>" href="#prima-option-export-settings"><?php _e('Export Settings', 'primathemes'); ?></a>
                    </li>
					<li class="import-settings">
                    <a title="<?php _e('Import Settings', 'primathemes'); ?>" href="#prima-option-import-settings"><?php _e('Import Settings', 'primathemes'); ?></a>
                    </li>
            	</ul>		
			</div>
			<div id="prima-content">
                <div id="prima-option-export-settings" class="group">
                    <h2><?php _e('Export Settings', 'primathemes'); ?></h2>
                    <div class="section section-text ">
                    <h3 class="heading"><?php _e('Export Settings File', 'primathemes'); ?></h3>
                    <div class="option">
						<p><?php _e('When you click the button below, PrimaThemes will generate a JSON file for you to save to your computer.', 'primathemes'); ?></p>
						<p><?php _e('Once you have saved the download file, you can use the import function on another site to import this data.', 'primathemes'); ?></p>
						<p>
							<form method="post" action="<?php echo admin_url('admin.php?page=primathemes-tools'); ?>">
								<?php wp_nonce_field('primathemes-export'); ?>
								<select name="primathemes-export">
									<option value="all"><?php _e('All Settings', 'primathemes'); ?></option>
                                    <?php foreach (prima_tools_settings() as $setting) {
										echo '<option value="'.$setting['id'].'">'.$setting['name'].'</option>';
									} ?>
								</select>
								<input type="submit" class="button button-primary" value="<?php _e('Download Export File', 'primathemes'); ?>" />
							</form>
						</p>
                    	<div class="clear"></div>
                	</div>
                    </div>
                </div>            
                <div id="prima-option-import-settings" class="group">
                    <h2><?php _e('Import Settings', 'primathemes'); ?></h2>
                    <div class="section section-text ">
                    <h3 class="heading"><?php _e('Import Settings File', 'primathemes'); ?></h3>
                    <div class="option">
						<p><?php _e('Upload the data file from your computer (.json) and we\'ll import your settings.', 'primathemes'); ?></p>
						<p><?php _e('Choose the file from your computer and click "Upload and Import"', 'primathemes'); ?></p>
						<p>
							<form enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin.php?page=primathemes-tools'); ?>">
								<?php wp_nonce_field('primathemes-import'); ?>
								<input type="hidden" name="primathemes-import" value="1" />
								<label for="primathemes-import-upload"><?php sprintf( __('Upload File: (Maximum Size: %s)', 'primathemes'), ini_get('post_max_size') ); ?></label>
								<input type="file" id="primathemes-import-upload" name="primathemes-import-upload" size="25" />
								<input type="submit" class="button button-primary" value="<?php _e('Upload file and import', 'primathemes'); ?>" />
							</form>
						</p>
                    	<div class="clear"></div>
                	</div>
                    </div>
                </div>            
	        </div>
	        <div class="clear"></div>
        </div>
        <div class="prima-settings-footer">
        </div>
	</div><!-- .wrap -->
	<?php
}
