<?php

add_action( 'init', 'prima_add_shortcode_button' );
add_filter( 'tiny_mce_version', 'prima_refresh_mce' );

function prima_add_shortcode_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') :
		add_filter('mce_external_plugins', 'prima_add_shortcode_tinymce_plugin');
		add_filter('mce_buttons_3', 'prima_register_shortcode_button');
	endif;
}

function prima_register_shortcode_button($buttons) {
	array_push($buttons, "prima_shortcodes_columns_button");
	array_push($buttons, "prima_shortcodes_typography_button");
	array_push($buttons, "prima_shortcodes_forms_button");
	array_push($buttons, "prima_shortcodes_media_button");
	array_push($buttons, "prima_shortcodes_slider_button");
	array_push($buttons, "prima_shortcodes_products_button");
	return $buttons;
}

function prima_add_shortcode_tinymce_plugin($plugin_array) {
	global $prima;
	$plugin_array['PrimaShortcodesColumns'] = PRIMA_ADMIN_URI . '/js/columns.sg.js';
	$plugin_array['PrimaShortcodesTypography'] = PRIMA_ADMIN_URI . '/js/typography.sg.js';
	$plugin_array['PrimaShortcodesForms'] = PRIMA_ADMIN_URI . '/js/forms.sg.js';
	$plugin_array['PrimaShortcodesMedia'] = PRIMA_ADMIN_URI . '/js/media.sg.js';
	$plugin_array['PrimaShortcodesSlider'] = PRIMA_ADMIN_URI . '/js/slider.sg.js';
	$plugin_array['PrimaShortcodesProducts'] = PRIMA_ADMIN_URI . '/js/products.sg.js';
	return $plugin_array;
}

function prima_refresh_mce($ver) {
	$ver += 3;
	return $ver;
}
