<?php

class CPDViewPropertyImage {
	function ajax() {
		global $soapopts;
		
		// Gather property reference
		$property_id = $_POST['property_id'];
		
		// Perform search
		$options = get_option('cpd-search-options');
		$viewMedia = new ViewMediaType();
		$viewMedia->PropertyID = $property_id;
		$viewMedia->MediaType = "photo";
		$viewMedia->Position = 1;
		$viewMedia->ServiceContext = cpd_search_service_context();
		try {
			// Create the SOAP client
			$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
			$headers = cpd_search_wss_security_headers();
			$client->__setSOAPHeaders($headers);
			$viewMediaResponse = $client->ViewMedia($viewMedia);
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
	
		// Add thumb URL, only if one is available
		if(isset($viewMediaResponse->PropertyMedia)) {
			$mediaList = $viewMediaResponse->PropertyMedia;
			if($propList instanceof PropertyMediaType) {
				$propList = array($propList);
			}
			if(isset($mediaList)) {
				$media = $mediaList;
			}
		}
		
		// Return response as JSON
		$response = array(
			'success' => true,
			'plugin_url' => get_site_url().cpd_plugin_dir_url(__FILE__),
			'results' => $media,
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
}

add_action('wp_ajax_cpd_view_property_image', array('CPDViewPropertyImage', 'ajax'));
add_action('wp_ajax_nopriv_cpd_view_property_image', array('CPDViewPropertyImage', 'ajax'));

?>
