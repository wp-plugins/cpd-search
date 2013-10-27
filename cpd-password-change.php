<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-search-options.php");

class CPDPasswordChange {
	function init() {
		wp_enqueue_script('cpd-password-change-controller', plugins_url("cpd-search")."/js/cpd-password-change-controller.js");
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
		
		return $form;
	}
	
	function change_ajax() {
		// Check verification code has been provided
		$token = $_REQUEST['token'];
		if(!isset($_REQUEST['token']) || $_REQUEST['token'] == "") {
			$response = array(
				'success' => false,
				'error' => "No token provided."
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
		
		// Send request to verification server to return UID.
		$request = array(
			'password' => $password1
		);
		$url = sprintf("%s/visitors/passwordchange/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			//'Content-Type: application/json'
		));
		//curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] != 200) {
			header("HTTP/1.0 500 Internal Server Error");
			echo $rawdata;
			exit;
		}
		
		// Store new token as a cookie
		$response = json_decode($rawdata);
		
		// Return response as JSON
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
}

add_action('init', array('CPDPasswordChange', 'init'));

add_shortcode('cpd_password_change', array('CPDPasswordChange', 'change_form'));

add_action('wp_ajax_cpd_password_change', array('CPDPasswordChange', 'change_ajax'));
add_action('wp_ajax_nopriv_cpd_password_change', array('CPDPasswordChange', 'change_ajax'));

?>
