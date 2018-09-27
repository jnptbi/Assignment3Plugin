<?php
/*
Plugin Name: TGS Contact Form
Plugin URI: #
Description: Custom Contact Form plugin which has fixed form fields.
Version: 1.0
Author: Think Green Solar
Author URI: #
License: GPL2 
*/

function custom_form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Name<i>*</i> : <br/>';
	echo '<input type="text" name="ccf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["ccf-name"] ) ? esc_attr( $_POST["ccf-name"] ) : '' ) . '" size="40" required />';
	echo '</p>';
	echo '<p>';
	echo 'Email<i>*</i> : <br/>';
	echo '<input type="email" name="ccf-email" value="' . ( isset( $_POST["ccf-email"] ) ? esc_attr( $_POST["ccf-email"] ) : '' ) . '" size="40" required />';
	echo '</p>';
	echo '<p>';
	echo 'Subject<i>*</i> : <br/>';
	echo '<input type="text" name="ccf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["ccf-subject"] ) ? esc_attr( $_POST["ccf-subject"] ) : '' ) . '" size="40" required />';
	echo '</p>';
	echo '<p>';
	echo 'Message<i>*</i> : <br/>';
	echo '<textarea rows="10" cols="35" name="ccf-message" required>' . ( isset( $_POST["ccf-message"] ) ? esc_attr( $_POST["ccf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><input type="submit" name="ccf-submitted" value="Send"></p>';
	echo '</form>';
}

function send_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['ccf-submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["ccf-name"] );
		$email   = sanitize_email( $_POST["ccf-email"] );
		$subject = sanitize_text_field( $_POST["ccf-subject"] );
		$message = esc_textarea( $_POST["ccf-message"] );

		// get the blog administrator's email address
		$to = get_option( 'admin_email' );

		$headers = "From: $name <$email>" . "\r\n";

		// Wordpress Email function
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p>Thanks for contacting Think Green Solar, you will receive a response soon.</p>';
			echo '</div>';
		} else {
			echo 'There is an unexpected error occurred';
		}
	}
}

function ccf_shortcode() {
	ob_start();
	send_mail();
	custom_form_code();

	return ob_get_clean();
}

add_shortcode( 'custom_contact_form', 'ccf_shortcode' );
