<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-options.php");

class CPDPasswordChange {
	function init() {
		wp_enqueue_script('cpd-password-change-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-password-change-controller.js");
		wp_localize_script('cpd-password-change-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		add_shortcode('cpd_password_change', array('CPDPasswordChange', 'change_form'));
		add_action('wp_ajax_cpd_password_change', array('CPDPasswordChange', 'change_ajax'));
		add_action('wp_ajax_nopriv_cpd_password_change', array('CPDPasswordChange', 'change_ajax'));
	}
	
	function change_form() {
		// Read in form template from plugin options
		$form = cpd_get_template_contents("common");
		$form .= cpd_get_template_contents("user_password_change");
		
		// Check a token was provided
		if(!isset($_REQUEST['token'])) {
			return '<p class="error">No token provided! Please check your link.<p>';
		}
		
		// Add variables to be passed to JS controller
		$form .= "\n".
			"<div style=\"display: none;\">\n".
			"\t<span id=\"token\">[token]</span>\n".
			"</div>\n";
		
		// Pass token through
		$form = str_replace("[token]", $_REQUEST['token'], $form);
		file_put_contents("/tmp/debug", $form);
		
		return $form;
	}
	
	function change_ajax() {
		global $soapopts;
		
		// Check verification code has been provided
		$token = $_REQUEST['token'];
		if(!isset($_REQUEST['token']) || $_REQUEST['token'] == "") {
			$response = array(
				'success' => false,
				'error' => "Not token provided."
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		
		// Compare passwords
		$token = $_POST['token'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		if($password1 != $password2) {
			$response = array(
				'success' => false,
				'error' => "Passwords don't match."
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		if(strlen($password1) < 6) {
			$response = array(
				'success' => false,
				'error' => "Passwords are too short."
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
	
		// Send SOAP request to verification server to return UID.
		$options = get_option('cpd-search-options');
		$client = new UserService($options['cpd_soap_base_url']."UserService?wsdl", $soapopts);

		// Perform lookup
		try {
			$change = new PasswordChangeType();
			$change->Token = $token;
			$change->Password = $password1;
			$changeResponse = $client->PasswordChange($change);
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

		// Notify user of success
		$response = array(
			'success' => true,
			'response' => $changeResponse,
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
}

CPDPasswordChange::init();

?>
