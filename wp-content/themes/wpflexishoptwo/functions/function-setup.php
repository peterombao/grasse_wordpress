<?php
add_action( 'after_setup_theme', 'prima_add_theme_support', 5 );
function prima_add_theme_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'menus' );
	add_theme_support( 'widgets' );
	// add_theme_support( 'editor-style' ); add_editor_style();
	
	add_theme_support( 'post-thumbnails' );

	add_post_type_support( 'post', 'prima-layout-settings' );
	add_post_type_support( 'page', 'prima-layout-settings' );
	add_post_type_support( 'portfolio', 'prima-layout-settings' );
	prima_add_taxonomy_support( 'category', 'prima-layout-settings' );
	prima_add_taxonomy_support( 'post_tag', 'prima-layout-settings' );
	prima_add_taxonomy_support( 'portfolio_type', 'prima-layout-settings' );

	add_theme_support( 'prima-theme-branding' );
	add_theme_support( 'prima-theme-feed' );
	add_theme_support( 'prima-theme-scripts' );

	add_theme_support( 'prima-design-settings' );

	if ( !class_exists("wpSEO") && !class_exists("All_in_One_SEO_Pack") && !class_exists("HeadSpace_Plugin") && !class_exists("Platinum_SEO_Pack") ) {
		add_theme_support( 'prima-seo-settings' );
		add_theme_support( 'prima-seo-title' );
		add_theme_support( 'prima-seo-description' );
		add_theme_support( 'prima-seo-keywords' );
		add_theme_support( 'prima-seo-indexation' );
		add_theme_support( 'prima-seo-canonical' );
		add_post_type_support( 'post', 'prima-seo-settings' );
		add_post_type_support( 'page', 'prima-seo-settings' );
		add_post_type_support( 'portfolio', 'prima-seo-settings' );
	}
	
	add_theme_support( 'prima-sidebar-settings' );
}

add_action('init', 'prima_register_slider_type');
function prima_register_slider_type() 
{
	$slider_labels = array(
		'name' => __('Sliders', 'primathemes'),
		'singular_name' => __('Slider', 'primathemes'),
		'add_new' => __('Add New', 'primathemes'),
		'add_new_item' => __('Add New Slider', 'primathemes'),
		'edit_item' => __('Edit Slider', 'primathemes'),
		'new_item' => __('New Slider', 'primathemes'),
		'view_item' => __('View Slider', 'primathemes'),
		'search_items' => __('Search Sliders', 'primathemes'),
		'not_found' =>  __('No sliders found', 'primathemes'),
		'not_found_in_trash' => __('No sliders found in Trash', 'primathemes'), 
		'parent_item_colon' => ''
	);
	$slider_args = array(
		'labels' => $slider_labels,
		'public' => true,
		'rewrite' => array('slug' => 'slider', 'with_front' => false),
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title', 'thumbnail')
	); 
	register_post_type('slider', $slider_args);
}

add_action( 'admin_head', 'prima_icon_slider_type' );
function prima_icon_slider_type() {
    ?>
    <style type="text/css" media="screen">
        #menu-posts-slider .wp-menu-image {
            background: url(<?php echo trailingslashit(PRIMA_CUSTOM_URI) ?>images/slider.png) no-repeat 6px -17px !important;
        }
		#menu-posts-slider:hover .wp-menu-image, #menu-posts-slider.wp-has-current-submenu .wp-menu-image {
            background-position:6px 7px!important;
        }
    </style>
	<?php 
}

add_action( 'init', 'prima_register_menus', 5 );
function prima_register_menus() {
	register_nav_menu( 'topnav-menu', __( 'Top Menu', 'primathemes' ) );
	register_nav_menu( 'loggedin-topnav-menu', __( 'Logged In Top Menu', 'primathemes' ) );
	register_nav_menu( 'header-menu', __( 'Header Menu', 'primathemes' ) );
	register_nav_menu( 'footer-menu', __( 'Footer Menu', 'primathemes' ) );
}

add_action( 'init', 'prima_register_layouts', 5 );
function prima_register_layouts() {
	global $prima_default_layout;
	$prima_default_layout = array(
			'id' => 'content-sidebar', 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/layout-default.png'
		);
    prima_register_layout(
		array(
			'id' => 'content-sidebar', 
			'label' => __("Content - Sidebar", 'primathemes'), 'description' => '', 
			'sidebar' => true, 
			'sidebarmini' => false, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/content-sidebar.png'
		));
    prima_register_layout(
		array(
			'id' => 'sidebar-content', 
			'label' => __("Sidebar - Content", 'primathemes'), 'description' => '', 
			'sidebar' => true, 
			'sidebarmini' => false, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/sidebar-content.png'
		));
    prima_register_layout(
		array(
			'id' => 'sidebarmini-content-sidebar', 
			'label' => __("Sidebar Mini - Content - Sidebar", 'primathemes'), 'description' => '', 
			'sidebar' => true, 
			'sidebarmini' => true, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/sidebarmini-content-sidebar.png'
		));
    prima_register_layout(
		array(
			'id' => 'sidebar-content-sidebarmini', 
			'label' => __("Sidebar - Content - Sidebar Mini", 'primathemes'), 'description' => '', 
			'sidebar' => true, 
			'sidebarmini' => true, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/sidebar-content-sidebarmini.png'
		));
    prima_register_layout(
		array(
			'id' => 'content-sidebar-sidebarmini', 
			'label' => __("Content - Sidebar - Sidebar Mini", 'primathemes'), 'description' => '', 
			'sidebar' => true, 
			'sidebarmini' => true, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/content-sidebar-sidebarmini.png'
		));
    prima_register_layout(
		array(
			'id' => 'sidebarmini-sidebar-content', 
			'label' => __("Sidebar Mini - Sidebar - Content", 'primathemes'), 'description' => '', 
			'sidebar' => true, 
			'sidebarmini' => true, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/sidebarmini-sidebar-content.png'
		));
    prima_register_layout(
		array(
			'id' => 'content-sidebarmini', 
			'label' => __("Content - Sidebar Mini", 'primathemes'), 'description' => '', 
			'sidebar' => false, 
			'sidebarmini' => true, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/content-sidebarmini.png'
		));
    prima_register_layout(
		array(
			'id' => 'sidebarmini-content', 
			'label' => __("Sidebar Mini - Content", 'primathemes'), 'description' => '', 
			'sidebar' => false, 
			'sidebarmini' => true, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/sidebarmini-content.png'
		));
    prima_register_layout(
		array(
			'id' => 'full-width-content', 
			'label' => __("Full Width Content", 'primathemes'), 'description' => '', 
			'sidebar' => false, 
			'sidebarmini' => false, 
			'image' => trailingslashit(PRIMA_CUSTOM_URI).'images/full-width-content.png'
		));
}

add_action( 'init', 'prima_register_sidebar_areas', 5 );
function prima_register_sidebar_areas() {
	prima_register_sidebar_area( array(
		'id' => '',
		'label' => __( 'Main Sidebar Area', 'primathemes' )
	));
	prima_register_sidebar_area( array(
		'id' => 'mini',
		'label' => __( 'Mini Sidebar Area', 'primathemes' )
	));
}

add_action( 'init', 'prima_register_sidebars', 5 );
function prima_register_sidebars() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'primathemes' ),
		'id' => 'sidebar',
		'description' => __( 'Main sidebar area', 'primathemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-container widget-sidebar %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'Mini Sidebar', 'primathemes' ),
		'id' => 'sidebarmini',
		'description' => __( 'Mini sidebar area', 'primathemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-container widget-sidebar %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	if( (int)prima_get_setting('footer_top_layout') >= 10 ) :
		register_sidebar(array(
			'name' => __('Footer Top #1', 'primathemes'),
			'id' => 'footer-top-1', 
			'description' => __( 'First footer top widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
	if( (int)prima_get_setting('footer_top_layout') >= 20 ) :
		register_sidebar(array(
			'name' => __('Footer Top #2', 'primathemes'),
			'id' => 'footer-top-2', 
			'description' => __( 'Second footer top widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
	if( (int)prima_get_setting('footer_top_layout') >= 30 ) :
		register_sidebar(array(
			'name' => __('Footer Top #3', 'primathemes'),
			'id' => 'footer-top-3', 
			'description' => __( 'Third footer top widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
	if( (int)prima_get_setting('footer_top_layout') >= 40 ) :
		register_sidebar(array(
			'name' => __('Footer Top #4', 'primathemes'),
			'id' => 'footer-top-4', 
			'description' => __( 'Fourth footer top widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
	if( (int)prima_get_setting('footer_bottom_layout') >= 10 ) :
		register_sidebar(array(
			'name' => __('Footer Bottom #1', 'primathemes'),
			'id' => 'footer-bottom-1', 
			'description' => __( 'First footer bottom widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
	if( (int)prima_get_setting('footer_bottom_layout') >= 20 ) :
		register_sidebar(array(
			'name' => __('Footer Bottom #2', 'primathemes'),
			'id' => 'footer-bottom-2', 
			'description' => __( 'Second footer bottom widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
	if( (int)prima_get_setting('footer_bottom_layout') >= 30 ) :
		register_sidebar(array(
			'name' => __('Footer Bottom #3', 'primathemes'),
			'id' => 'footer-bottom-3', 
			'description' => __( 'Third footer bottom widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
	if( (int)prima_get_setting('footer_bottom_layout') >= 40 ) :
		register_sidebar(array(
			'name' => __('Footer Bottom #4', 'primathemes'),
			'id' => 'footer-bottom-4', 
			'description' => __( 'Fourth footer bottom widget area', 'primathemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-container %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
	endif;
}

