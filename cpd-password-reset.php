<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

function cpd_password_reset_ajax() {
	$options = get_option('cpd-search-options');
	
	// Gather inputs from request
	$email = $_REQUEST['email'];
	
	// Send our search request to the server
	$passwordRequest = new PasswordResetType();
	$passwordRequest->Email = $email;
	$passwordRequest->Agent = $options['cpd_agentref'];
	try {
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);
		$passwordResponse = $client->PasswordReset($passwordRequest);
	}
	catch(Exception $e) {
		file_put_contents("/tmp/debugme", print_r($client, true));
		$response = array(
			'success' => false,
			'error' => $e->getMessage()
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	// Store token as a cookie
	setcookie("cpd_token", "");
	setcookie("cpd_token_type", "");

	// Return response as JSON
	$response = array(
		'success' => true,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_password_reset', 'cpd_password_reset_ajax');
add_action('wp_ajax_nopriv_cpd_password_reset', 'cpd_password_reset_ajax');

function cpd_password_reset() {
	// Check verification code has been provided
	$token = $_REQUEST['token'];
	if(!isset($_REQUEST['token']) || $_REQUEST['token'] == "") {
		return cpd_password_reset_no_token_provided();
	}

	// If this is being posted, send the SOAP request
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Compare passwords
		$token = $_POST['token'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		if($password1 != $password2) {
			$e = new Exception("Passwords don't match.");
			return cpd_password_reset_form($token, $e);
		}
		if(strlen($password1) < 6) {
			$e = new Exception("Password too short.");
			return cpd_password_reset_form($token, $e);
		}
		
		// Send SOAP request to verification server to return UID.
		$options = get_option('cpd-search-options');
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);

		// Perform lookup
		try {
			$reset = new PasswordChangeType();
			$reset->Token = $token;
			$reset->Password = $password1;
			$resetResponse = $client->PasswordChange($reset);
		}
		catch(Exception $e) {
			return cpd_password_reset_handle_exception($e);
		}

		// Notify user of success
		return cpd_password_changed($user);
	}
	
	// Send the password form
	return cpd_password_reset_form($token);
}

add_shortcode('cpd_password_reset', 'cpd_password_reset');

?>
