<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-options.php");

class CPDPasswordReset {
	function init() {
		wp_enqueue_script('cpd-password-reset-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-password-reset-controller.js");
		wp_localize_script('cpd-password-reset-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		add_action('wp_ajax_cpd_password_reset', array('CPDPasswordReset', 'reset_ajax'));
		add_action('wp_ajax_nopriv_cpd_password_reset', array('CPDPasswordReset', 'reset_ajax'));
	}
	
	function reset_ajax() {
		global $soapopts;
		
		$options = get_option('cpd-search-options');
	
		// Gather inputs from request
		$email = $_REQUEST['email'];
	
		// Send our search request to the server
		$passwordRequest = new PasswordResetType();
		$passwordRequest->Email = $email;
		$passwordRequest->Agent = $options['cpd_agentref'];
		try {
			$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);
			$passwordResponse = $client->PasswordReset($passwordRequest);
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
		setcookie("cpd_token", "");
		setcookie("cpd_token_type", "");

		// Notify user of success
		$response = array(
			'success' => true,
			'response' => $passwordResponse,
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
}

CPDPasswordReset::init();

?>
