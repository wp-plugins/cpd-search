<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

class CPDUserRegistration {
	function init() {
		wp_enqueue_script('cpd-user-registration-controller', plugins_url("cpd-search")."/cpd-user-registration.js");
	}
	
	function ajax() {
		// Gather inputs from request
		$registration = array(
			'name' => $_REQUEST['name'],
			'email' => $_REQUEST['email'],
			'password' => $_REQUEST['password'],
			'phone' => $_REQUEST['phone'],
		);
		
		// Send registration to server
		$token = get_option('cpd_application_token');
		$url = sprintf("%s/visitors/registeruser/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.cpd_search_service_context(),
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($registration));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 400) {
			header("HTTP/1.0 400 Bad Request");
			echo $rawdata;
			exit;
		}
		if($info['http_code'] == 409) {
			header("HTTP/1.0 409 Conflict");
			echo $rawdata;
			exit;
		}
		if($info['http_code'] != 201) {
			header("HTTP/1.0 500 Internal Server Error");
			echo $rawdata;
			exit;
		}
		
		// Store new token as a cookie
		$usertoken = json_decode($rawdata);
		cpd_search_set_user_token($usertoken);

		header("HTTP/1.0 201 Created");
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
}

add_action('init', array('CPDUserRegistration', 'init'));

add_action('wp_ajax_cpd_user_registration', array('CPDUserRegistration', 'ajax'));
add_action('wp_ajax_nopriv_cpd_user_registration', array('CPDUserRegistration', 'ajax'));

?>
