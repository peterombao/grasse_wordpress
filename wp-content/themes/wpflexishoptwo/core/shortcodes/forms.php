<?php

add_shortcode( 'search_form', 'prima_search_form_shortcode' );
function prima_search_form_shortcode($attr){
	$attr = shortcode_atts( array( 'search_text' => __('Search this website&hellip;', 'primathemes'), 'button_text' => __( 'Search', 'primathemes' ) ), $attr );
	$attr['echo'] = false;
	return prima_search_form( $attr );
}

add_shortcode( 'feedburner_form', 'prima_feedburner_form_shortcode' );
function prima_feedburner_form_shortcode($attr){
	$attr = shortcode_atts( array( 'id' => '', 'language' => __( 'en_US', 'primathemes' ), 'input_text' => __( 'Enter your email address...', 'primathemes' ), 'button_text' => __( 'Subscribe', 'primathemes' ) ), $attr );
	$attr['echo'] = false;
	return prima_feedburner_form( $attr );
}

add_shortcode( 'prima_contact_form', 'prima_contactform_shortcode' );
function prima_contactform_shortcode ( $atts, $content = null ) {
	$defaults = array(
					'email' => get_bloginfo('admin_email'),
					'subject' => __( 'Message via the contact form', 'primathemes' ),
					'button_text' => __( 'Submit', 'primathemes' )
					);

	extract( shortcode_atts( $defaults, $atts ) );

	$html = '';
	$error_messages = array();

	$emailSent = false;
	if ( ( count( $_POST ) > 3 ) && isset( $_POST['submitted'] ) ) {
	
		if ( isset ( $_POST['contactName'] ) && $_POST['contactName'] != '' )
			$message_name = $_POST['contactName'];
		else 
			$error_messages['contactName'] = __( 'Please enter your name', 'primathemes' );
		
		if ( isset ( $_POST['contactEmail'] ) && $_POST['contactEmail'] != '' && is_email( $_POST['contactEmail'] ) )
			$message_email = $_POST['contactEmail'];
		else 
			$error_messages['contactEmail'] = __( 'Please enter your email address (and please make sure it\'s valid)', 'primathemes' );
			
		if ( isset ( $_POST['contactMessage'] ) && $_POST['contactMessage'] != '' )
			$message_body = $_POST['contactMessage'] . "\n\r\n\r";
		else 
			$error_messages['contactMessage'] = __( 'Please enter your message', 'primathemes' );
		
		if ( count( $error_messages ) ) {} 
		else {
			$headers = 'From: ' . $message_name . ' <' . $message_email . '>' . "\r\n" . 'Reply-To: ' . $message_email;
			$emailSent = wp_mail($email, $subject, $message_body, $headers);

			// Send a copy of the e-mail to the sender, if specified.
			if ( isset( $_POST['sendCopy'] ) && $_POST['sendCopy'] == 'true' ) {
				$subject = __( '[COPY]', 'primathemes' ) . $subject;
				$headers = 'From: ' . $message_name . ' <' . $message_email . '>' . "\r\n" . 'Reply-To: ' . $message_email;
				$emailSent = wp_mail($message_email, $subject, $message_body, $headers);
			}
		}
	}

	/* Generate the form HTML.
	--------------------------------------------------*/
	$html .= '<div class="contact-form">' . "\n";

	/* Display message HTML if necessary.
	--------------------------------------------------*/

	if( isset( $emailSent ) && $emailSent == true ) {
		$html .= do_shortcode( '[box color="green"]' . __( 'Your email was successfully sent.', 'primathemes' ) . '[/box]' );
		$html .= '<span class="has_sent hide"></span>' . "\n";
	}

	if( count( $error_messages ) ) {
		$html .= do_shortcode( '[box color="red"]' . __( 'There were one or more errors while submitting the form.', 'primathemes' ) . '[/box]' );
	}

	if( $email == '' ) {
		$html .= do_shortcode( '[box color="red"]' . __( 'E-mail has not been setup properly. Please add your contact e-mail!', 'primathemes' ) . '[/box]' );
	}

	if ( $email == '' ) {} 
	else {
		$html .= '<form action="" id="contactForm" method="post">' . "\n";
		$html .= '<fieldset class="forms">' . "\n";

		$contactName = '';
		if( isset( $_POST['contactName'] ) ) { $contactName = $_POST['contactName']; }
		$contactEmail = '';
		if( isset( $_POST['contactEmail'] ) ) { $contactEmail = $_POST['contactEmail']; }
		$contactMessage = '';
		if( isset( $_POST['contactMessage'] ) ) { $contactMessage = stripslashes( $_POST['contactMessage'] ); }

		$html .= '<p class="fieldContactName">' . "\n";
		$html .= '<input placeholder="' . __( 'Your Name', 'primathemes' ) . '" type="text" name="contactName" id="contactName" value="' . esc_attr( $contactName ) . '" class="txt requiredField" />' . "\n";
		if( array_key_exists( 'contactName', $error_messages ) ) {
			$html .= '<span class="contactError">' . $error_messages['contactName'] . '</span>' . "\n";
		}
		$html .= '</p>' . "\n";

		$html .= '<p class="fieldContactEmail">' . "\n";
		$html .= '<input placeholder="' . __( 'Your Email', 'primathemes' ) . '" type="text" name="contactEmail" id="contactEmail" value="' . esc_attr( $contactEmail ) . '" class="txt requiredField email" />' . "\n";
		if( array_key_exists( 'contactEmail', $error_messages ) ) {
			$html .= '<span class="contactError">' . $error_messages['contactEmail'] . '</span>' . "\n";
		}
		$html .= '</p>' . "\n";

		$html .= '<p class="fieldContactMessage">' . "\n";
		$html .= '<textarea placeholder="' . __( 'Your Message', 'primathemes' ) . '" name="contactMessage" id="contactMessage" rows="20" cols="30" class="textarea requiredField">' . esc_textarea( $contactMessage ) . '</textarea>' . "\n";
		if( array_key_exists( 'contactMessage', $error_messages ) ) {
			$html .= '<span class="contactError">' . $error_messages['contactMessage'] . '</span>' . "\n";
		}
		$html .= '</p>' . "\n";

		$sendCopy = '';
		if(isset($_POST['sendCopy']) && $_POST['sendCopy'] == true) {
			$sendCopy = ' checked="checked"';
		}
		$html .= '<p class="inline"><input type="checkbox" name="sendCopy" id="sendCopy" value="true"' . $sendCopy . ' /><label for="sendCopy">' . __( 'Send a copy of this email to yourself', 'primathemes' ) . '</label></p>' . "\n";

		$checking = '';
		if(isset($_POST['checking'])) {
			$checking = $_POST['checking'];
		}

		$html .= '<p class="screenReader"><label for="checking" class="screenReader">' . __('If you want to submit this form, do not enter anything in this field', 'primathemes') . '</label><input type="text" name="checking" id="checking" class="screenReader" value="' . esc_attr( $checking ) . '" /></p>' . "\n";

		$html .= '<p class="buttons"><input type="hidden" name="submitted" id="submitted" value="true" /><input id="contactSubmit" type="submit" value="' . $button_text . '" /></p>';

		$html .= '</fieldset>' . "\n";
		$html .= '</form>' . "\n";

		$html .= '</div><!--/.post .contact-form-->' . "\n";

	}
	
	return $html;
	
}

