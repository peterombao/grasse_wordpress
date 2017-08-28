<?php 

add_action('wp_footer', 'prima_print_shortcodes_js', 100);
function prima_print_shortcodes_js() {
	global $prima_shortcodes_js;
	if ( is_array( $prima_shortcodes_js ) ) {
		foreach ( $prima_shortcodes_js as $js ) {
			echo $js;
		}
	}
}

add_action('wp_footer', 'prima_load_custom_scripts', 100);
function prima_load_custom_scripts() {
	echo '<script type="text/javascript">/* Custom Scripts */'."\n";
	do_action('prima_custom_scripts');
	echo '</script>'."\n";
}

add_action('prima_custom_scripts', 'prima_print_shortcodes_scripts', 100);
function prima_print_shortcodes_scripts() {
	global $prima_shortcodes_scripts;
	echo $prima_shortcodes_scripts;
}