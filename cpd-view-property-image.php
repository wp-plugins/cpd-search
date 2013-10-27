<?php

class CPDViewPropertyImage {
	function viewimage() {
		if(!isset($_REQUEST['action']) || $_REQUEST['action'] != 'viewmedia') {
			return;
		}
		
		// Determine first PDF for given property
		$medialink_id = $_REQUEST['medialink_id'];
		if(!$medialink_id) {
			return;
		}
		
		// Fetch media details
		$url = sprintf("%s/visitors/viewmedia/?medialink_id=%d", get_option('cpd_rest_url'), $medialink_id);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, $url);
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
	
	function ajax() {
		// Determine first PDF for given property
		$medialink_id = $_POST['medialink_id'];
		
		// Fetch media details
		$params = array(
			'medialink_id' => $medialink_id,
		);
		$url = sprintf("%s/visitors/viewmedia/?%s", get_option('cpd_rest_url'), http_build_query($params));
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
			exit;
		}
		if($info['http_code'] != 200) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Proxy returned status ".$info['http_code'];
			exit;
		}

		// Return response as JSON
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
}

add_action('template_redirect', array('CPDViewPropertyImage', 'viewmedia'));

add_action('wp_ajax_cpd_view_property_image', array('CPDViewPropertyImage', 'ajax'));
add_action('wp_ajax_nopriv_cpd_view_property_image', array('CPDViewPropertyImage', 'ajax'));

?>
