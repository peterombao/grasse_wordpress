<?php

/* do we need these default wordpress features? */
function prima_standard_setup() {
	add_custom_background();
	add_theme_support( 'custom-header' );
	add_custom_image_header();
}
