<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-search-options.php");

class CPDVerifyUser {
	function init() {
		wp_enqueue_script('cpd-verify-user-controller', plugins_url("cpd-search")."/cpd-verify-user.js");
	}
	
	function form() {
		// Read in form template from plugin options
		$form = cpd_get_template_contents("common");
		$form .= cpd_get_template_contents("user_verification");
		
		// Add variables to be passed to JS controller
		$form .= "\n".
			"<div style=\"display: none;\">\n".
			"\t<span id=\"token\">[token]</span>\n".
			"</div>\n";
		
		// Pass token through
		$form = str_replace("[token]", isset($_REQUEST['token']) ? $_REQUEST['token'] : "", $form);
		
		return $form;
	}
	
	function ajax() {
		// Check verification code has been provided
		if(!isset($_REQUEST['token']) || $_REQUEST['token'] == "") {
			$response = array(
				'success' => false,
				'error' => "MissingTokenException"
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		$token = $_REQUEST['token'];

		// Send registration to server
		$url = sprintf("%s/visitors/verifyuser/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.$context,
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] != 200) {
			header("HTTP/1.0 500 Internal Server Error");
			echo $rawdata;
			exit;
		}
		
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
}

add_action('init', array('CPDVerifyUser','init'));

add_shortcode('cpd_verify_user', array('CPDVerifyUser','form'));

add_action('wp_ajax_cpd_verify_user', array('CPDVerifyUser','ajax'));
add_action('wp_ajax_nopriv_cpd_verify_user', array('CPDVerifyUser','ajax'));

?>
