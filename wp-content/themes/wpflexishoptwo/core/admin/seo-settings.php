<?php
add_action( 'admin_menu', 'prima_seo_settings_init' );
function prima_seo_settings_init() {
	global $menu, $prima, $prima_child_data;
	$theme_data = $prima_child_data;
	$prima->seo_settings = add_theme_page( sprintf( __( '%1$s SEO Settings', 'primathemes' ), $theme_data['Name'] ), __( 'SEO Settings', 'primathemes' ), apply_filters( "prima_settings_capability", 'edit_theme_options' ), 'primathemes-seo', 'prima_seo_settings_page' );
	add_action( "load-{$prima->seo_settings}", 'prima_theme_settings_enqueue_script' );
	add_action( "load-{$prima->seo_settings}", 'prima_theme_settings_enqueue_style' );
}
add_action('admin_init', 'prima_register_seo_settings');
function prima_register_seo_settings() {
	register_setting( PRIMA_SEO_SETTINGS, PRIMA_SEO_SETTINGS, 'prima_save_seo_settings' );
	add_option( PRIMA_SEO_SETTINGS, prima_default_seo_settings(), '', 'yes' );
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-seo' )
		return;
	if ( prima_get_setting('reset',PRIMA_SEO_SETTINGS) ) {
		update_option( PRIMA_SEO_SETTINGS, prima_default_seo_settings() );
		wp_redirect( admin_url( 'themes.php?page=primathemes-seo&reset=true' ) );
		exit;
	}
}
function prima_seo_settings() {
	$settings = array();
	return apply_filters( 'prima_seo_settings_args', $settings );
}
function prima_default_seo_settings() {
	$settings = prima_seo_settings();
	if ( false === $settings ) return false;
	$defaut_settings = array();
	foreach ($settings as $setting) {
		if (isset($setting['id'])) $defaut_settings[$setting['id']] = isset($setting['std']) ? $setting['std'] : '';
	}
	return $defaut_settings;
}
function prima_save_seo_settings( $settings ) {
	return apply_filters( "prima_validate_seo_settings", $settings );
}
function prima_seo_settings_page() {
	global $prima, $prima_child_data;
	$theme_data = $prima_child_data; ?>
	<div class="wrap" id="prima-settings">
        <form method="post" enctype="multipart/form-data" action="options.php">
		<?php settings_fields( PRIMA_SEO_SETTINGS ); ?>
        <div class="prima-settings-header">
            <div class="prima_header_title"><?php printf( __( '%1$s SEO Settings', 'primathemes' ), $theme_data['Name'] ); ?></div>
            <input type="submit" class="button-save button-primary submit-button" value="<?php _e( 'Save Settings', 'primathemes' ); ?>" />
            <input type="submit" name="<?php echo PRIMA_SEO_SETTINGS; ?>[reset]"  class="button-reset button-highlighted submit-button reset-button" value="<?php _e( 'Reset Settings', 'primathemes' ); ?>" />
        </div>
        <?php
		if ( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {  
			echo '<div id="prima-settings-message" class="">'.__('SEO Settings Saved', 'primathemes').'</div>';
		}
		elseif ( isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] == 'true') {  
			echo '<div id="prima-settings-message" class="">'.__('SEO Settings Saved', 'primathemes').'</div>';
		}
		elseif ( isset( $_REQUEST['reset'] ) && $_REQUEST['reset'] == 'true' ) {
			echo '<div id="prima-settings-message" class="">'.__('SEO Settings Reset', 'primathemes').'</div>';
		}
		?>
        <div id="prima-main">
	        <div id="prima-nav">
				<ul>
					<?php prima_settings_menu_generator( prima_seo_settings(), PRIMA_SEO_SETTINGS ); ?>
				</ul>		
			</div>
			<div id="prima-content">
				<?php prima_settings_generator( prima_seo_settings(), PRIMA_SEO_SETTINGS ); ?>
	        </div>
	        <div class="clear"></div>
        </div>
        <div class="prima-settings-footer">
            <input type="submit" class="button-save button-primary submit-button" value="<?php _e( 'Save Settings', 'primathemes' ); ?>" />
            <input type="submit" name="<?php echo PRIMA_SEO_SETTINGS; ?>[reset]"  class="button-reset button-highlighted submit-button reset-button" value="<?php _e( 'Reset Settings', 'primathemes' ); ?>" />
        </div>
    </form>
	<?php 
    // $settings = get_option( PRIMA_SEO_SETTINGS ); print_r($settings); 
    // echo '<br/><br/>';print_r(prima_default_theme_settings()); 
    // echo '<br/><br/>';print_r(prima_theme_settings()); 
    ?>
	</div><!-- .wrap -->
	<?php
}
