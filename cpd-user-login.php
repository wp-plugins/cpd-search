<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

class CPDUserLogin {
	function init() {
		add_action('wp_ajax_cpd_user_login', array('CPDUserLogin', 'ajax'));
		add_action('wp_ajax_nopriv_cpd_user_login', array('CPDUserLogin', 'ajax'));
		add_action('wp_ajax_cpd_user_logout', array('CPDUserLogin', 'logoutajax'));
		add_action('wp_ajax_nopriv_cpd_user_logout', array('CPDUserLogin', 'logoutajax'));
	}
	
	function ajax() {
		global $soapopts;
		
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
			$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);
			$authenticationResponse = $client->AuthenticateUser($userAuthentication);
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
		cpd_search_set_user_token($authenticationResponse->Token);

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
	
	function logoutajax() {
		global $soapopts;
		$options = get_option('cpd-search-options');
		try {
			cpd_search_discard_token();
			
			// Return response as JSON
			$response = array(
				'success' => true
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
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
	}
}

CPDUserLogin::init();

?>
