<?php

require_once(dirname(__FILE__) . "/cpd-common.php");

class CPDQRCodeLanding {
	function show_form() {
		wp_enqueue_script('cpd-qr-code-landing-controller', plugins_url("cpd-search")."cpd-qr-code-landing.js");
		
		if(!isset($_REQUEST["id"])) {
			echo '<p class="error">No \'id\' provided.</p>';
			return;
		}
		$property_id = $_REQUEST["id"];
		
		// Fetch property
		$url = sprintf("%s/visitors/viewproperty/?property_id=%d", get_option('cpd_rest_url'), $property_id);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.cpd_get_user_token()
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if(curl_errno($curl)) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Curl error: ".curl_error($curl);
			exit;
		}
		curl_close($curl);
		if($info['http_code'] == 403) {
			header( "HTTP/1.1 403 Authentication Required" );
			echo "Proxy returned status ".$info['http_code'];
			exit;
		}
		if($info['http_code'] != 200) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Proxy returned status ".$info['http_code'];
			exit;
		}
		$response = json_decode($rawdata);
		
		// [TODO] Tie up this lot...
		
		// Redirect user to actual PDF url
		/*
		header( "Location: ".$response->media_url);
		echo $response->media_url;
		exit;
		*/
		
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
		$form = str_replace("[property_id]", $id, $form);
		$form = str_replace("[address]", $prop->Address, $form);
		$form = str_replace("[media_id]", $media_id, $form);
		echo $form;
	}
	
	function ajax_register_user() {
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

		// Mark this registration as coming from this agent/application
		$registration = array(
			'name' => $_REQUEST['name'],
			'email' => $_REQUEST['email'],
			'password' => $_REQUEST['password'],
			'phone' => $_REQUEST['phone'],
		);
		
		// Send registration to server
		$token = cpd_get_user_token();
		$url = sprintf("%s/visitors/registeruser/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.cpd_search_qrcode_service_context(),
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($registration));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
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
	
	function ajax_view_property_pdf() {
		// Determine first PDF for given property
		$medialink_id = $_REQUEST['medialink_id'];
		if(!$medialink_id) {
			return;
		}
		
		// Fetch media details
		$url = sprintf("%s/visitors/viewmedia/?medialink_id=%d", get_option('cpd_rest_url'), $medialink_id);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.cpd_get_user_token()
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if(curl_errno($curl)) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Curl error: ".curl_error($curl);
			exit;
		}
		curl_close($curl);
		if($info['http_code'] == 403) {
			header( "HTTP/1.1 403 Authentication Required" );
			echo "Proxy returned status ".$info['http_code'];
			exit;
		}
		if($info['http_code'] != 200) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Proxy returned status ".$info['http_code'];
			exit;
		}
		$response = json_decode($rawdata);
		
		// Redirect user to actual PDF url
		header( "Location: ".$response->media_url);
		echo $response->media_url;
		exit;
	}
}

add_shortcode('cpd_qr_code_landing', array('CPDQRCodeLanding', 'show_form'));

add_action('wp_ajax_cpd_qr_code_register_user', array('CPDQRCodeLanding', 'ajax_register_user'));
add_action('wp_ajax_nopriv_cpd_qr_code_register_user', array('CPDQRCodeLanding', 'ajax_register_user'));
add_action('wp_ajax_cpd_qr_code_login_user', array('CPDQRCodeLanding', 'ajax_login_user'));
add_action('wp_ajax_nopriv_cpd_qr_code_login_user', array('CPDQRCodeLanding', 'ajax_login_user'));
add_action('wp_ajax_cpd_qr_code_view_pdf', array('CPDQRCodeLanding', 'ajax_view_property_pdf'));
add_action('wp_ajax_nopriv_cpd_qr_code_view_pdf', array('CPDQRCodeLanding', 'ajax_view_property_pdf'));

?>
