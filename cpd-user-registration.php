<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

class CPDUserRegistration {
	function init() {
		wp_enqueue_script('cpd-user-registration-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-user-registration-controller.js");
		wp_localize_script('cpd-user-registration-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		add_action('wp_ajax_cpd_user_registration', array('CPDUserRegistration', 'ajax'));
		add_action('wp_ajax_nopriv_cpd_user_registration', array('CPDUserRegistration', 'ajax'));
	}
	
	function ajax() {
		global $soapopts;
	
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
		$userRegistration->ServiceContext = $options['cpd_service_context'];
		try {
			$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);
			$registrationResponse = $client->RegisterUser($userRegistration);
		}
		catch(Exception $e) {
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
}

CPDUserRegistration::init();

?>
