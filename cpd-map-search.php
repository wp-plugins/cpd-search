<?php

function cpd_map_search_form() {
	$options = get_option('cpd-search-options');

	$form = file_get_contents(dirname(__FILE__) . "/inc/map_search_ui.html");
	$form = str_replace("[cpd_map_widget_width]", $options['cpd_map_widget_width'], $form);
	$form = str_replace("[cpd_map_widget_height]", $options['cpd_map_widget_height'], $form);
	$form = str_replace("[plugin_url]", cpd_plugin_dir_url(__FILE__), $form);

	return $form;
}

add_shortcode('cpd_map_search_form', 'cpd_map_search_form');

function cpd_map_search_ajax() {
	// Create the SOAP client
	$options = get_option('cpd-search-options');
	$soapopts = array('trace' => 1, 'exceptions' => 1);
	$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
	$headers = wss_security_headers($options['cpd_agentref'], $options['cpd_password']);
	$client->__setSOAPHeaders($headers);

	// Send our search request to the server
	$searchCriteria = new SearchCriteriaType();
	$searchCriteria->Start = $_POST['start'];
	$searchCriteria->Limit = $_POST['limit'];
	$searchCriteria->DetailLevel = "brief";
	
	// Set up geographical limits
	if(isset($_POST['longitude']) && isset($_POST['latitude'])) {
		$searchRadius = new RadiusProximityType();
		$searchRadius->Longitude = $_POST['longitude'];
		$searchRadius->Latitude = $_POST['latitude'];
		$searchRadius->Radius = $_POST['radius'];
		$searchCriteria->RadiusProximity = $searchRadius;
	}
	
	// Check for given criteria
	if(isset($_POST['sectors'])) {
		$searchSectors = new SectorsType();
		$searchSectors->Sector = $_POST['sectors'];
		$searchCriteria->Sectors = $searchSectors;
	}
	if(isset($_POST['address'])) {
		$searchCriteria->Address = $_POST['address'];
	}
	if(isset($_POST['cpdarea'])) {
		$searchCriteria->CPDAreaIDs = $_POST['cpdarea'];
	}
	if(isset($_POST['sizefrom'])) {
		$searchCriteria->MinSize = $_POST['sizefrom'];
	}
	if(isset($_POST['sizeto'])) {
		$searchCriteria->MaxSize = $_POST['sizeto'];
	}

	// Perform search
	$searchRequest = new SearchPropertyType();
	$searchRequest->SearchCriteria = $searchCriteria;
	try {
		$searchResponse = $client->SearchProperty($searchRequest);
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
		if($propList instanceof PropertyMediaType) {
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

add_action('wp_ajax_cpd_map_search', 'cpd_map_search_ajax');
add_action('wp_ajax_nopriv_cpd_map_search', 'cpd_map_search_ajax');

?>
