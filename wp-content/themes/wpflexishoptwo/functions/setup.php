<?php

/* Set the content width based on the theme's design and stylesheet  */
if ( ! isset( $content_width ) )
	$content_width = 662;

/* Show PrimaThemes page after activate  */
if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	wp_redirect( admin_url( 'admin.php?page=primathemes' ) );
	exit;
}

class PrimaThemes {

	function PrimaThemes() {
		$this->init();
	}
	
	function init() {
		$this->constants();
		$this->locale();
		$this->functions();
		$this->shortcodes();
		$this->widgets();
		$this->frontend();
		$this->plugins();
		$this->themesetup();
		add_action( 'after_setup_theme', array( &$this, 'modules' ), 12 );
		add_action( 'wp_loaded', array( &$this, 'admin' ) );
	}
	
	/* Defines the constant paths for use */
	function constants() {
		/* Define Database Constants */
		if( !defined('PRIMA_THEME_SETTINGS') ) 
			define( 'PRIMA_THEME_SETTINGS', 'primathemes' );
		if( !defined('PRIMA_DESIGN_SETTINGS') ) 
			define( 'PRIMA_DESIGN_SETTINGS', 'primathemes_design' );
		if( !defined('PRIMA_SEO_SETTINGS') ) 
			define( 'PRIMA_SEO_SETTINGS', 'primathemes_seo' );
		if( !defined('PRIMA_SIDEBAR_SETTINGS') ) 
			define( 'PRIMA_SIDEBAR_SETTINGS', 'primathemes_sidebar' );
			
		/* Define Parent Theme Constants */
		define( 'PRIMA_DIR', get_template_directory() );
		define( 'PRIMA_URI', get_template_directory_uri() );
		define( 'PRIMA_CORE_DIR', trailingslashit(PRIMA_DIR) . 'core' );
		define( 'PRIMA_CORE_URI', trailingslashit(PRIMA_URI) . 'core' );
		define( 'PRIMA_ADMIN_DIR', trailingslashit(PRIMA_CORE_DIR) . 'admin' );
		define( 'PRIMA_ADMIN_URI', trailingslashit(PRIMA_CORE_URI) . 'admin' );
		define( 'PRIMA_CUSTOM_DIR', trailingslashit(PRIMA_DIR) . 'functions' );
		define( 'PRIMA_CUSTOM_URI', trailingslashit(PRIMA_URI) . 'functions' );

		/* Define Child Theme Constants */
		define( 'THEME_DIR', get_stylesheet_directory() );
		define( 'THEME_URI', get_stylesheet_directory_uri() );
	}
	
	/* Load the theme textdomain. */
	function locale() {
		$locale = get_locale();
		load_theme_textdomain( 'primathemes', THEME_DIR . '/languages' );
		/* Load locale-specific functions file. */
		$locale_functions = locate_template( array( "languages/{$locale}.php", "{$locale}.php" ) );
		if ( !empty( $locale_functions ) && is_readable( $locale_functions ) )
			require_once( $locale_functions );
	}
	
	/* Loads the core theme functions. */
	function functions() {
		$this->require_prima_core ( 'functions/core.php' );
		$this->require_prima_core ( 'functions/standard.php' );
		$this->require_prima_core ( 'functions/layouts.php' );
		$this->require_prima_core ( 'functions/sidebars.php' );
		$this->require_prima_core ( 'functions/images.php' );
		$this->require_prima_core ( 'functions/fonts.php' );
		$this->require_prima_core ( 'functions/shortcodes.php' );
		$this->require_prima_core ( 'functions/files.php' );
		$this->require_prima_core ( 'functions/forms.php' );
		$this->require_prima_core ( 'functions/socials.php' );
	}
	
	/* Loads the core theme widgets. */
	function widgets() {
		$this->require_prima_core ( 'widgets/contents.php' );
		$this->require_prima_core ( 'widgets/forms.php' );
		$this->require_prima_core ( 'widgets/socials.php' );
	}
	
	/* Loads the core theme shortcodes. */
	function shortcodes() {
		if ( !is_admin() ) {
			$this->require_prima_core ( 'shortcodes/theme.php' );
			$this->require_prima_core ( 'shortcodes/post.php' );
			$this->require_prima_core ( 'shortcodes/general.php' );
			$this->require_prima_core ( 'shortcodes/conditional.php' );
			$this->require_prima_core ( 'shortcodes/typography.php' );
			$this->require_prima_core ( 'shortcodes/forms.php' );
			$this->require_prima_core ( 'shortcodes/media.php' );
			$this->require_prima_core ( 'shortcodes/slider.php' );
		}
	}

	/* Load frontend files. */
	function frontend() {
		if ( !is_admin() ) {
			$this->require_prima_core ( 'frontend/head.php' );
			$this->require_prima_core ( 'frontend/content.php' );
			$this->require_prima_core ( 'frontend/attachment.php' );
			$this->require_prima_core ( 'frontend/footer.php' );
		}
	}

	/* Loads the core theme modules. */
	function modules() {
		$this->require_prima_core ( 'modules/theme-feed.php', 'prima-theme-feed' );
		$this->require_prima_core ( 'modules/theme-scripts.php', 'prima-theme-scripts' );
		$this->require_prima_core ( 'modules/theme-branding.php', 'prima-theme-branding' );
		if ( current_theme_supports('prima-seo-settings') ) {
			$this->require_prima_core ( 'modules/seo-title.php', 'prima-seo-title' );
			$this->require_prima_core ( 'modules/seo-description.php', 'prima-seo-description' );
			$this->require_prima_core ( 'modules/seo-keywords.php', 'prima-seo-keywords' );
			$this->require_prima_core ( 'modules/seo-indexation.php', 'prima-seo-indexation' );
			$this->require_prima_core ( 'modules/seo-canonical.php', 'prima-seo-canonical' );
		}
	}
	
	/* Load admin files. */
	function admin() {
		if ( is_admin() ) {
			add_editor_style();
			$this->require_prima_admin ( 'admin.php' );
			$this->require_prima_admin ( 'postmeta-settings.php' );
			$this->require_prima_admin ( 'taxmeta-settings.php' );
			$this->require_prima_admin ( 'widget-settings.php' );
			$this->require_prima_admin ( 'theme-settings.php' );
			$this->require_prima_admin ( 'design-settings.php', 'prima-design-settings' );
			$this->require_prima_admin ( 'sidebar-settings.php', 'prima-sidebar-settings' );
			$this->require_prima_admin ( 'seo-settings.php', 'prima-seo-settings' );
			$this->require_prima_admin ( 'tools-settings.php' );
		}
	}

	/* Load plugin files. */
	function plugins() {
		if ( class_exists( 'woocommerce' ) ) { 
			$this->require_prima_core ( 'plugins/woocommerce-functions.php' );
			$this->require_prima_core ( 'plugins/woocommerce-widgets.php' );
			if (  is_admin() ) $this->require_prima_core ( 'plugins/woocommerce-admin.php' );
			if ( !is_admin() ) $this->require_prima_core ( 'plugins/woocommerce-frontend.php' );
			if ( !is_admin() ) $this->require_prima_core ( 'plugins/woocommerce-shortcodes.php' );
		}
	}
	
	/* Load theme setup files. */
	function themesetup() {
		$this->require_prima_custom ( 'function-setup.php' );
		if (  is_admin() ) {
			$this->require_prima_custom ( 'function-settings.php' );
			$this->require_prima_custom ( 'function-designs.php' );
		}
		if ( !is_admin() ) {
			$this->require_prima_custom ( 'function-frontend.php' );
		}
		if ( class_exists( 'woocommerce' ) ) {
			$this->require_prima_custom ( 'function-woocommerce.php' );
		}
	}
	
	function require_prima_core( $file, $support = '' ) {
		if ( $support )
			require_if_theme_supports( $support, trailingslashit(PRIMA_CORE_DIR) . $file );
		else 
			require_once( trailingslashit(PRIMA_CORE_DIR) . $file );
	}
	
	function require_prima_admin( $file, $support = '' ) {
		if ( $support )
			require_if_theme_supports( $support, trailingslashit(PRIMA_ADMIN_DIR) . $file );
		else 
			require_once( trailingslashit(PRIMA_ADMIN_DIR) . $file );
	}
	
	function require_prima_custom( $file, $support = '' ) {
		if ( file_exists( trailingslashit(PRIMA_CUSTOM_DIR) . $file ) ) {
			if ( $support )
				require_if_theme_supports( $support, trailingslashit(PRIMA_CUSTOM_DIR) . $file );
			else 
				require_once( trailingslashit(PRIMA_CUSTOM_DIR) . $file );
		}
	}
	
}

$prima = new PrimaThemes();