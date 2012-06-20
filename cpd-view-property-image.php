<?php

function cpd_view_property_image_ajax() {
	// Gather property reference
	$media_id = $_POST['media_id'];
	
	// Perform search
	$viewMedia = new ViewingMediaType();
	$viewMedia->MediaID = $media_id;	
	$viewMedia->ServiceContext = SERVICE_CONTEXT;
	try {
		// Create the SOAP client
		$options = get_option('cpd-search-options');
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
		$headers = wss_security_headers($options['cpd_agentref'], $options['cpd_password']);
		$client->__setSOAPHeaders($headers);
		$viewMediaResponse = $client->ViewingMedia($viewMedia);
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
	
	// Filter results to avoid sending sensitive fields over the wire
	$results = array();
	if(isset($searchResponse->PropertyList->Property)) {
		// Workaround for PITA in PHP SOAP parser...
		$propList = $searchResponse->PropertyList->Property;
		if($propList instanceof PropertyType) {
			$propList = array($propList);
		}
		foreach($propList as $record) {
			$row = array();
			$row['PropertyID'] = $record->PropertyID;
			$row['SectorDescription'] = $record->SectorDescription;
			$row['SizeDescription'] = $record->SizeDescription;
			$row['TenureDescription'] = $record->TenureDescription;
			$row['BriefSummary'] = $record->BriefSummary;
			$row['Address'] = $record->Address;
			$row['Latitude'] = $record->Latitude;
			$row['Longitude'] = $record->Longitude;
			$row['RegionName'] = $record->RegionName;
			
			// Add thumb URL, only if one is available
			if(isset($record->PropertyMedia)) {
				$mediaList = $record->PropertyMedia;
				if($propList instanceof PropertyMediaType) {
					$propList = array($propList);
				}
				foreach($mediaList as $media) {
//					if($media->Type == "photo" && $media->Position == 1) {
					if($media->Position == 1) {
						$row['ThumbURL'] = $media->ThumbURL;
						break;
					}
				}
			}

			$results[] = $row;
		}
	}
	
	// Return response as JSON
	$response = array(
		'success' => true,
		'total' => $searchResponse->ResultCount,
		'results' => $results,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_view_property_image', 'cpd_view_property_image_ajax');
add_action('wp_ajax_nopriv_cpd_view_property_image', 'cpd_view_property_image_ajax');

?>
