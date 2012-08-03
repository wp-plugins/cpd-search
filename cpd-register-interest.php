<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

class CPDRegisterInterest {
	function init() {		
		wp_enqueue_script('cpd-register-interest-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-register-interest-controller.js");
		wp_localize_script('cpd-register-interest-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		add_action('wp_ajax_cpd_register_interest', array('CPDRegisterInterest', 'ajax'));
		add_action('wp_ajax_nopriv_cpd_register_interest', array('CPDRegisterInterest', 'ajax'));
	}
	
	function ajax() {
		global $soapopts;
		
		// Gather inputs from request
		$propref = $_REQUEST['propref'];
	
		// Check we have a user token for this request
		$cpd_token = $_COOKIE['cpd_token'];
		$cpd_token_type = $_COOKIE['cpd_token_type'];
		if($cpd_token_type == null || $cpd_token_type != "user") {
			// Tell the UI controller to show the registration form...
			$response = array(
				'success' => false,
				'error' => "InvalidTokenException: No user token found",
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
	
		// Send our register interest request to the server
		$options = get_option('cpd-search-options');
		$registerInterest = new RegisterInterestType();
		$registerInterest->PropertyID = $propref;
		$registerInterest->ServiceContext = $options['cpd_service_context'];
		try {
			$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
			$headers = wss_security_headers($cpd_token, "");
			$client->__setSOAPHeaders($headers);
			$registerResponse = $client->RegisterInterest($registerInterest);
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
	
		// Return response as JSON
		$response = array(
			'success' => true,
			'propref' => $propref,
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
}

CPDRegisterInterest::init();

?>
