<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-search-options.php");

class CPDPasswordReset {
	function init() {
		wp_enqueue_script('cpd-password-reset-controller', plugins_url("cpd-search")."/cpd-password-reset.js");
	}
	
	function reset_ajax() {
		// Gather inputs from request
		$email = $_REQUEST['email'];
		
		// Send request to verification server to return UID.
		$token = get_option('cpd_application_token');
		$request = array(
			'email' => $email,
		);
		$url = sprintf("%s/visitors/passwordreset/?%s", get_option('cpd_rest_url'), http_build_query($request));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.cpd_search_service_context(),
			//'Content-Type: application/json'
		));
		//curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 405) {
			header("HTTP/1.0 405 Method Not Allowed");
			echo $rawdata;
			exit;
		}
		if($info['http_code'] != 200) {
			header("HTTP/1.0 500 Internal Server Error");
			echo $rawdata;
			exit;
		}
		
		// Return response as JSON
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
}

add_action('init', array('CPDPasswordReset', 'init'));

add_action('wp_ajax_cpd_password_reset', array('CPDPasswordReset', 'reset_ajax'));
add_action('wp_ajax_nopriv_cpd_password_reset', array('CPDPasswordReset', 'reset_ajax'));

?>
