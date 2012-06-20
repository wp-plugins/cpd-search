<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-options.php");

function cpd_verify_user_init() {
	wp_enqueue_script('cpd-verify-user-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-verify-user-controller.js");
	wp_localize_script('cpd-verify-user-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

function cpd_verify_user_ajax() {
	// Check verification code has been provided
	$token = $_REQUEST['token'];
	if(!isset($_REQUEST['token']) || $_REQUEST['token'] == "") {
		$response = array(
			'success' => false,
			'error' => "MissingTokenException"
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}

	// Send SOAP request to verification server to return UID.
	$options = get_option('cpd-search-options');
	$soapopts = array('trace' => 1, 'exceptions' => 1);
	$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);

	// Perform lookup
	try {
		$verify = new VerifyUserType();
		$verify->Token = $token;
		$verifyResponse = $client->VerifyUser($verify);
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

	// Get UID from response
	$token = $verifyResponse->Token;
	$response = array(
		'success' => true,
		'token' => $verifyResponse->Token,
		'uid' => $verifyResponse->User->ID,
		'name' => $verifyResponse->User->Name,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_verify_user', 'cpd_verify_user_ajax');
add_action('wp_ajax_nopriv_cpd_verify_user', 'cpd_verify_user_ajax');

function cpd_verify_user() {
	cpd_verify_user_init();
	
	// Read in form template from plugin options
	$form = cpd_get_template_contents("common");
	$form .= cpd_get_template_contents("user_verification");
	
	// Add variables to be passed to JS controller
	$form .= "\n".
		"<div style=\"display: none;\">\n".
		"\t<span id=\"token\">[token]</span>\n".
		"</div>\n";
	
	// Add theme base URL
	$form = str_replace("[themeurl]", get_template_directory_uri(), $form);

	// Pass token through
	$form = str_replace("[token]", $_REQUEST['token'], $form);
	
	return $form;
}

add_shortcode('cpd_verify_user', 'cpd_verify_user');

?>
