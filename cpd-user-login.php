<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

function cpd_user_login_ajax() {
	$options = get_option('cpd-search-options');
	
	// Gather inputs from request
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	
	// Send our search request to the server
	$userAuthentication = new AuthenticateUserType();
	$userAuthentication->Email = $email;
	$userAuthentication->Agent = $options['cpd_agentref'];
	$userAuthentication->Password = $password;
	try {
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);
		$authenticationResponse = $client->AuthenticateUser($userAuthentication);
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
	setcookie("cpd_token", $authenticationResponse->Token);
	setcookie("cpd_token_type", "user");

	// Return response as JSON
	$response = array(
		'success' => true,
		'token' => $authenticationResponse->Token,
		'uid' => $authenticationResponse->User->UID,
		'name' => $authenticationResponse->User->Name,
		'confirmed' => $authenticationResponse->User->Confirmed,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_user_login', 'cpd_user_login_ajax');
add_action('wp_ajax_nopriv_cpd_user_login', 'cpd_user_login_ajax');

?>
