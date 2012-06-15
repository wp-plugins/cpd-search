<?php

function cpd_geocode_ajax() {
	$longitude = $_POST['longitude'];
	$latitude = $_POST['latitude'];
	$geocode_url = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=".$latitude.",".$longitude."&sensor=true";
	$ch = curl_init($geocode_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	if(!$response) {
		$response = array(
			'success' => false,
			'error' => "Failed to geolocate position."
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	// Examine the response
	$dom = new DOMDocument();
	$dom->loadXML($response);
	if(!$dom) {
		$response = array(
			'success' => false,
			'error' => "Failed to parse geolocation response."
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	$xPath = new DOMXPath($dom);
	$addressNodes = $xPath->query("//result[1]/formatted_address");
	$streetname = "";
	foreach($addressNodes as $node) {
		$streetname .= $node->nodeValue;
	}
	if($streetname == "") {
		$streetname = "Unavailable";
	}
	
	// Return response as JSON
	$response = array(
		'success' => true,
		'location' => $streetname,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_geocode', 'cpd_geocode_ajax');
add_action('wp_ajax_nopriv_cpd_geocode', 'cpd_geocode_ajax');

?>