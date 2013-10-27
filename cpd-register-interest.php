<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

class CPDRegisterInterest {
	function init() {
		wp_enqueue_script('cpd-register-interest-controller', plugins_url("cpd-search")."/js/cpd-register-interest-controller.js");
	}
	
	function ajax() {
		// Gather inputs from request
		$propref = $_REQUEST['propref'];
	
		// Check we have a user token for this request
		if(!cpd_search_is_user_registered()) {
			// Tell the UI controller to show the registration form...
			$response = array(
				'success' => false,
				'error' => "AccessDeniedExceptionMsg",
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
	
		// Send our register interest request to the server
		$context = cpd_search_service_context();
		$params = array(
			'property_id' => $propref,
			'context' => $context
		);
		$token = cpd_get_user_token();
		$url = sprintf("%s/visitors/registerinterest/?%s", get_option('cpd_rest_url'), http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 403) {
			header("HTTP/1.1 403 Authentication Failed");
			exit;
		}
		
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
}

add_action('init', array('CPDRegisterInterest', 'init'));

add_action('wp_ajax_cpd_register_interest', array('CPDRegisterInterest', 'ajax'));
add_action('wp_ajax_nopriv_cpd_register_interest', array('CPDRegisterInterest', 'ajax'));

?>
