<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

class CPDQRCodeLanding {
	function init() {
		wp_enqueue_script('cpd-qr-code-landing-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-qr-code-landing-controller.js");
		wp_localize_script('cpd-qr-code-landing-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		add_shortcode('cpd_qr_code_landing', array('CPDQRCodeLanding', 'show_form'));
		add_action('wp_ajax_cpd_qr_code_register_user', array('CPDQRCodeLanding', 'ajax_register_user'));
		add_action('wp_ajax_nopriv_cpd_qr_code_register_user', array('CPDQRCodeLanding', 'ajax_register_user'));
		add_action('wp_ajax_cpd_qr_code_login_user', array('CPDQRCodeLanding', 'ajax_login_user'));
		add_action('wp_ajax_nopriv_cpd_qr_code_login_user', array('CPDQRCodeLanding', 'ajax_login_user'));
		add_action('wp_ajax_cpd_qr_code_view_pdf', array('CPDQRCodeLanding', 'ajax_view_property_pdf'));
		add_action('wp_ajax_nopriv_cpd_qr_code_view_pdf', array('CPDQRCodeLanding', 'ajax_view_property_pdf'));
	}
	
	function show_form() {
		global $soapopts;
		
		if(!isset($_REQUEST["id"])) {
			echo '<p class="error">No \'id\' provided.</p>';
			return;
		}
		$id = $_REQUEST["id"];
		
		// Fetch property
		$searchCriteria = new SearchCriteriaType();
		$searchCriteria->Start = 1;
		$searchCriteria->Limit = 1;
		$searchCriteria->DetailLevel = "brief";
		$searchCriteria->PropertyIDs = array($id);
	
		// Get agent to request properties as
		$options = get_option('cpd-search-options');
		$searchCriteria->Agent = $options['cpd_agentref'];
	
		// Perform search
		$searchRequest = new SearchPropertyType();
		$searchRequest->SearchCriteria = $searchCriteria;
		try {
			$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
			$headers = cpd_search_wss_security_headers();
			$client->__setSOAPHeaders($headers);
			$searchResponse = $client->SearchProperty($searchRequest);
		}
		catch(SoapFault $sf) {
			echo $sf->getMessage();
			return;
		}
		catch(Exception $e) {
			echo $e;
			return;
		}
	
		// Filter results to avoid sending sensitive fields over the wire
		$results = array();
		if(!isset($searchResponse->PropertyList->Property)) {
			echo '<p class="error">Property not found for id \''.$id.'\'</p>';
			return;
		}
		
		// Examine the property found
		$prop = $searchResponse->PropertyList->Property[0];
		if(!is_array($prop->PropertyMedia)) {
			echo '<p class="error">Property does not have any associated media.</p>';
			return;
		}
		$media_id = 0;
		foreach($prop->PropertyMedia as $media) {
			if($media->Type != "pdf" || $media->Position != 1) {
				continue;
			}
			$media_id = $media->MediaID;
		}
		if($media_id < 1) {
			echo '<p class="error">Property does not have an associated PDF.</p>';
			return;
		}
		
		// If the user is already logged in with a valid token, just send them
		// the PDF directly.
		$token = '';
		if(isset($_COOKIE['cpd_token'])) {
			$token = $_COOKIE['cpd_token'];
		}
		
		// Pass the interesting bits through in the form template
		$form = cpd_get_template_contents("qr_code_landing");
		$form .= cpd_get_template_contents("common");
//		$form .= cpd_get_template_contents("user_login");
//		$form .= cpd_get_template_contents("user_password_reset");
		$form = str_replace("[token]", $token, $form);
		$form = str_replace("[propref]", $id, $form);
		$form = str_replace("[address]", $prop->Address, $form);
		$form = str_replace("[media_id]", $media_id, $form);
		echo $form;
	}
	
	function ajax_register_user() {
		global $soapopts;
	
		// Gather form inputs	
		$name = $_REQUEST['name'];
		$email = $_REQUEST['email'];
		$phone = $_REQUEST['phone'];
		
		// If already logged in, no need to re-register
		if(isset($_COOKIE['cpd_token'])) {
			$response = array(
				'success' => true,
				'token' => $_COOKIE['cpd_token'],
				'name' => $name,
				'confirmed' => true
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}

		// Send our search request to the server
		$userRegistration = new RegisterUserType();
		$userRegistration->Name = $name;
		$userRegistration->Email = $email;
		$userRegistration->Phone = $phone;
		
		// Mark this registration as coming from this agent/application
		$options = get_option('cpd-search-options');
		$userRegistration->Agent = $options['cpd_agentref'];
		$userRegistration->ServiceContext = cpd_search_service_context();
		
		try {
			$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);
			$registrationResponse = $client->RegisterUser($userRegistration);
		}
		catch(Exception $e) {
			$response = array(
				'success' => false,
				'error' => $e
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		
		// Store token as a cookie
		cpd_search_set_user_token($registrationResponse->Token);
		
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
	
	function ajax_view_property_pdf() {
		global $soapopts;

		$media_id = $_REQUEST['media_id'];
		
		// Perform search
		$options = get_option('cpd-search-options');
		$viewMedia = new ViewingMediaType();
		$viewMedia->MediaID = $media_id;
		$viewMedia->ServiceContext = cpd_search_service_context();
		
		try {
			$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
			$headers = cpd_search_wss_security_headers();
			$client->__setSOAPHeaders($headers);
			$viewMediaResponse = $client->ViewingMedia($viewMedia);
		}
		catch(Exception $e) {
			$response = array(
				'success' => false,
				'error' => $e
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		header( "Content-Type: application/json" );
		$response = array(
			'success' => true,
			'response' => $viewMediaResponse
		);
		echo json_encode($response);
		exit;
	}
}

CPDQRCodeLanding::init();

?>
