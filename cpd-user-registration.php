<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

function cpd_user_registration_ajax() {
	$options = get_option('cpd-search-options');
	
	// Gather inputs from request
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	$phone = $_REQUEST['phone'];
	
	// Send our search request to the server
	$userRegistration = new RegisterUserType();
	$userRegistration->Name = $name;
	$userRegistration->Email = $email;
	$userRegistration->Phone = $phone;
	$userRegistration->Agent = $options['cpd_agentref'];
	$userRegistration->Password = $password;
	try {
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);
		$registrationResponse = $client->RegisterUser($userRegistration);
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
	setcookie("cpd_token", $registrationResponse->Token);
	setcookie("cpd_token_type", "user");

	// Return response as JSON
	$response = array(
		'success' => true,
		'token' => $registrationResponse->Token,
		'uid' => $registrationResponse->User->UID,
		'name' => $registrationResponse->User->Name,
		'confirmed' => $registrationResponse->User->Confirmed,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_user_registration', 'cpd_user_registration_ajax');
add_action('wp_ajax_nopriv_cpd_user_registration', 'cpd_user_registration_ajax');

?>
