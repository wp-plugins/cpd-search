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
		// Gather inputs from request
		$request = array(
			'email' => $_REQUEST['email'],
			'password' => $_REQUEST['password'],
			'agentref' => get_option('cpd_agentref'),
		);
		
		// Send registration to server
		$token = cpd_get_user_token();
		$url = sprintf("%s/visitors/login/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.cpd_search_service_context(),
			//'Content-Type: application/json'
		));
		//curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 403) {
			header("HTTP/1.0 403 Authentication Failed");
			echo $rawdata;
			exit;
		}
		if($info['http_code'] != 200) {
			header("HTTP/1.0 500 Internal Server Error");
			echo $rawdata;
			exit;
		}
		
		// Store new token as a cookie
		$response = json_decode($rawdata);
		cpd_search_set_user_token($response);

		// Return response as JSON
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
	
	function logoutajax() {
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
				'error' => $e
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}	
	}
}

CPDUserLogin::init();

?>
