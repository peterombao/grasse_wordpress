<?php
function prima_webfonts() {
$webfonts = array(
		'arial' => array(
			'value' =>	'arial',
			'label' =>  'Arial',
			'type' =>  '',
			'font' =>	'Arial, sans-serif'
		),
		'arialblack' => array(
			'value' =>	'arialblack',
			'label' =>  'Arial Black',
			'type' =>  '',
			'font' =>	'&quot;Arial Black&quot;, sans-serif'
		),
		'calibri' => array(
			'value' =>	'calibri',
			'label' =>  'Calibri*',
			'type' =>  '',
			'font' =>	'Calibri, Candara, Segoe, Optima, sans-serif'
		),
		'geneva' => array(
			'value' =>	'geneva',
			'label' =>  'Geneva*',
			'type' =>  '',
			'font' =>	'Geneva, Tahoma, Verdana, sans-serif'
		),
		'georgia' => array(
			'value' =>	'georgia',
			'label' =>  'Georgia',
			'type' =>  '',
			'font' =>	'Georgia, serif'
		),
		'gillsans' => array(
			'value' =>	'gillsans',
			'label' =>  'Gill Sans*',
			'type' =>  '',
			'font' =>	'"Gill Sans", "Gill Sans MT", Calibri, sans-serif'
		),
		'helvetica' => array(
			'value' =>	'helvetica',
			'label' =>  'Helvetica*',
			'type' =>  '',
			'font' =>	'"Helvetica Neue", Helvetica, sans-serif'
		),
		'impact' => array(
			'value' =>	'impact',
			'label' =>  'Impact',
			'type' =>  '',
			'font' =>	'Impact, Charcoal, sans-serif'
		),
		'lucida' => array(
			'value' =>	'lucida',
			'label' =>  'Lucida',
			'type' =>  '',
			'font' =>	'"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", sans-serif'
		),
		'myriadpro' => array(
			'value' =>	'myriadpro',
			'label' =>  'Myriad Pro*',
			'type' =>  '',
			'font' =>	'"Myriad Pro", Myriad, sans-serif'
		),
		'palatino' => array(
			'value' =>	'palatino',
			'label' =>  'Palatino',
			'type' =>  '',
			'font' =>	'Palatino, "Palatino Linotype", serif'
		),
		'sans-serif' => array(
			'value' =>	'sans-serif',
			'label' =>  'Sans-Serif',
			'type' =>  '',
			'font' =>	'sans-serif'
		),
		'serif' => array(
			'value' =>	'serif',
			'label' =>  'Serif',
			'type' =>  '',
			'font' =>	'serif'
		),
		'tahoma' => array(
			'value' =>	'tahoma',
			'label' =>  'Tahoma',
			'type' =>  '',
			'font' =>	'Tahoma, Geneva, Verdana, sans-serif'
		),
		'timesnewroman' => array(
			'value' =>	'timesnewroman',
			'label' =>  'Times New Roman',
			'type' =>  '',
			'font' =>	'"Times New Roman", serif'
		),
		'trebuchet' => array(
			'value' =>	'trebuchet',
			'label' =>  'Trebuchet',
			'type' =>  '',
			'font' =>	'"Trebuchet MS", Helvetica, sans-serif'
		),
		'verdana' => array(
			'value' =>	'verdana',
			'label' =>  'Verdana',
			'type' =>  '',
			'font' =>	'Verdana, Geneva, sans-serif'
		)
	);
	return apply_filters('prima_webfonts', $webfonts);
}
function prima_customfonts() {
	$customfonts = array();
	return apply_filters('prima_customfonts', $customfonts);
}
function prima_googlefonts() {
	$googlefonts = array();
	return apply_filters('prima_googlefonts', $googlefonts);
}
