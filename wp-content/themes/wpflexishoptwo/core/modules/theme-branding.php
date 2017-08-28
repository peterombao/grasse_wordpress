<?php
add_filter( 'prima_theme_settings_args', 'prima_theme_branding_settings', 15 );
function prima_theme_branding_settings( $settings ) {
	$settings[] = array( "name" => __('Branding Settings', 'primathemes'),
						"icon" => "branding",
						"type" => "heading");
	$settings[] = array( "name" => __('Custom Favicon', 'primathemes'),
						"desc" => __('16x16px ico, png, jpg, or gif file', 'primathemes'),
						"id" => "icon_favicon",
						"type" => "upload");    
	$settings[] = array( "name" => __('Custom Login Logo', 'primathemes'),
						"desc" => __('310x70px png, jpg or gif file', 'primathemes'),
						"id" => "icon_login",
						"type" => "upload");    
	$settings[] = array( "name" => __('Custom Dashboard Logo', 'primathemes'),
						"desc" => __('32x32px png, jpg or gif file', 'primathemes'),
						"id" => "icon_dashboard",
						"type" => "upload");    
	$settings[] = array( "name" => __('Custom Avatar', 'primathemes'),
						"desc" => __('png, jpg or gif file', 'primathemes'),
						"id" => "icon_avatar",
						"type" => "upload");    
	$settings[] = array( "name" => __('Custom Feed Image', 'primathemes'),
						"desc" => __('90x90px png, jpg or gif file', 'primathemes'),
						"id" => "icon_feed",
						"type" => "upload");    
	$settings[] = array( "name" => __('Default Thumbnail for Facebook Share', 'primathemes'),
						"desc" => __('png, jpg, gif file', 'primathemes'),
						"id" => "icon_facebook",
						"type" => "upload");    
	$settings[] = array( "name" => __('[BETA] Custom iPhone Web Clip Icon', 'primathemes'),
						"desc" => __('57x57px png, jpg, gif file', 'primathemes'),
						"id" => "icon_iphonewebclip",
						"type" => "upload");    
	$settings[] = array( "name" => __('[BETA] Custom iPhone Boot Image', 'primathemes'),
						"desc" => __('57x57px png, jpg, gif file', 'primathemes'),
						"id" => "320x460px png, jpg, gif file",
						"type" => "upload");    
	return $settings;
}
add_filter( 'prima_theme_settings_args', 'prima_theme_contact_settings', 15 );
function prima_theme_contact_settings( $settings ) {
	$settings[] = array( "name" => __('Contact Settings', 'primathemes'),
						"icon" => "branding",
						"type" => "heading");
	$settings[] = array( "name" => __('Google Map Embed Code', 'primathemes'),
						"desc" => __('', 'primathemes'),
						"id" => "google_map",
						"type" => "textarea");    
	return $settings;
}



add_action( 'wp_head', 'prima_head_favicon');
function prima_head_favicon() {
	$icon = prima_get_setting( 'icon_favicon' );
	if ( !$icon ) return;
	echo '<link rel="shortcut icon" type="image/x-icon" href="'.$icon.'" />' . "\n";
}
add_action( 'rss_head', 'prima_rss_image' );
add_action( 'rss2_head', 'prima_rss_image' );
function prima_rss_image() {
	$icon = prima_get_setting( 'icon_feed' );
	if ( !$icon ) return;
	echo "<image>
		<title>" . get_bloginfo('name') . "</title>
		<url>" . $icon . "</url>
		<link>" . home_url() ."</link>
		<width>90</width>
		<height>90</height>
		<description>" . get_bloginfo('description') . "</description>
	</image>";		
}
add_action( 'atom_head', 'prima_atom_image' );
function prima_atom_image() {
	$icon = prima_get_setting( 'icon_feed' );
	if ( !$icon ) return;
	echo "<logo>".$icon."</logo>";
}
add_action( 'wp_head', 'prima_head_facebook');
function prima_head_facebook() {
	$icon = prima_get_setting( 'icon_facebook' );
	if ( is_ssl() ) $icon = str_replace("http://", "https://", $icon);
	if ( is_singular() ) 
		$icon = prima_get_image(array('output'=>'url')) ? prima_get_image(array('output'=>'url')) : $icon;
	if ( $icon )
		echo '<meta property="og:image" content="'.$icon.'" />' . "\n";
}
add_action( 'wp_head', 'prima_head_iphone');
function prima_head_iphone() {
	$iphonewebclip = prima_get_setting( 'icon_iphonewebclip' );
	$iphonebootimage = prima_get_setting( 'icon_iphonebootimage' );
	if ( !$iphonewebclip && !$iphonebootimage ) return;
	if ( is_ssl() ) $iphonewebclip = str_replace("http://", "https://", $iphonewebclip);
	if ( is_ssl() ) $iphonebootimage = str_replace("http://", "https://", $iphonebootimage);
	echo '<meta name="apple-mobile-web-app-capable" content="yes" />' . "\n";
	if ( $iphonewebclip )
		echo '<link rel="apple-touch-icon" href="'.$iphonewebclip.'"/>' . "\n";
	if ( $iphonebootimage )
		echo '<link rel="apple-touch-startup-image" href="'.$iphonebootimage.'"/>' . "\n";
}
add_action( 'login_head', 'prima_login_logo' );
function prima_login_logo() {
	$icon = prima_get_setting( "icon_login" );
	if ( !$icon ) return;
	if ( is_ssl() ) $icon = str_replace("http://", "https://", $icon);
	echo '
	<style type="text/css">
	#login h1 a { background-image: url('.$icon.') !important; }
	</style>
	';
}
add_filter( 'login_headerurl', 'prima_login_headerurl' );
function prima_login_headerurl( $login_headerurl ) {
	if ( !is_multisite() ) return home_url();
	else return $login_headerurl;
}
add_filter( 'avatar_defaults', 'prima_avatar' );
function prima_avatar($avatar_defaults) {
	$icon = prima_get_setting( "icon_avatar" );
	if ( !$icon ) return $avatar_defaults;
	$avatar_defaults[$icon] = __( 'PrimaThemes', 'primathemes' );
	return $avatar_defaults;
}
add_action( 'admin_head', 'prima_admin_logo' );
function prima_admin_logo() {
	$icon = prima_get_setting( "icon_dashboard" );
	if ( !$icon ) return;
	if ( is_ssl() ) $icon = str_replace("http://", "https://", $icon);
	echo '
	<style type="text/css">
	#header-logo { background-image: url('.$icon.') !important; }
	</style>
	';
}
add_action( 'admin_head', 'prima_admin_favicon' );
function prima_admin_favicon() {
	$icon = prima_get_setting( 'icon_favicon' );
	if ( !$icon ) return;
	if ( is_ssl() ) $icon = str_replace("http://", "https://", $icon);
	echo '<link rel="shortcut icon" type="image/x-icon" href="'.$icon.'" />' . "\n";
}
add_filter('admin_footer_text', 'prima_admin_footer');
function prima_admin_footer() {
	global $prima_child_data;
	$theme_data = $prima_child_data;
	echo '<a href="'.$theme_data['URI'].'" title="'.$theme_data['Title'].'">'.$theme_data['Title'].' '.$theme_data['Version'].'</a> | ';
	echo 'Developed by <a href="ht'.'tp://www.primathemes.com" target="_blank">PrimaThemes</a> | Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>';
}


