<?php 
function prima_widget_label( $label, $id ) {
	echo "<label for='{$id}'>{$label}</label>";
}
function prima_widget_input_checkbox( $label, $id, $name, $checked ) {
	echo "\n\t\t\t<p>";
	echo "<label for='{$id}'>";
	echo "<input type='checkbox' id='{$id}' name='{$name}' {$checked} /> ";
	echo "{$label}</label>";
	echo '</p>';
}
function prima_widget_textarea( $label, $id, $name, $value ) {
	echo "\n\t\t\t<p>";
	prima_widget_label( $label, $id );
	echo "<textarea id='{$id}' name='{$name}' rows='3' cols='10' class='widefat'>" . strip_tags( $value ) . "</textarea>";
	echo '</p>';
}
function prima_widget_input_text( $label, $id, $name, $value ) {
	echo "\n\t\t\t<p>";
	prima_widget_label( $label, $id );
	echo "<input type='text' id='{$id}' name='{$name}' value='" . strip_tags( $value ) . "' class='widefat' />";
	echo '</p>';
}
function prima_widget_input_text_small( $label, $id, $name, $value ) {
	echo "\n\t\t\t<p>";
	prima_widget_label( $label, $id );
	echo "<input type='text' id='{$id}' name='{$name}' value='" . strip_tags( $value ) . "' size='6' class='code' />";
	echo '</p>';
}
function prima_widget_select_multiple( $label, $id, $name, $value, $options, $blank_option ) {
	$value = (array) $value;
	if ( $blank_option && is_array( $options ) )
		$options = array_merge( array( '' ), $options );
	echo "\n\t\t\t<p>";
	prima_widget_label( $label, $id );
	echo "<select id='{$id}' name='{$name}[]' multiple='multiple' size='4' class='widefat' style='height:5.0em;'>";
	foreach ( $options as $option_value => $option_label )
		echo "<option value='" . ( ( $option_value ) ? $option_value : $option_label ) . "'" . ( ( in_array( $option_value, $value ) || in_array( $option_label, $value ) ) ? " selected='selected'" : '' ) . ">{$option_label}</option>";
	echo '</select>';
	echo '</p>';
}
function prima_widget_select_single( $label, $id, $name, $value, $options, $blank_option, $class = '' ) {
	$class = ( ( $class ) ? $class : 'widefat;' );
	if ( $blank_option )
		$options = array_merge( array( '' ), $options );
	echo "\n\t\t\t<p>";
	prima_widget_label( $label, $id );
	echo "<select id='{$id}' name='{$name}' class='{$class}'>";
	foreach ( $options as $option_value => $option_label ) {
		$option_value = (string) $option_value;
		$option_label = (string) $option_label;
		echo "<option value='" . ( ( $option_value ) ? $option_value : $option_label ) . "'" . ( ( $value == $option_value || $value == $option_label ) ? " selected='selected'" : '' ) . ">{$option_label}</option>";
	}
	echo '</select>';
	echo '</p>';
}

