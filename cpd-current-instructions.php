<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-register-interest.php");

function cpd_current_instructions_init() {
	wp_enqueue_script('cpd-current-instructions-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-current-instructions-controller.js");
	wp_localize_script('cpd-current-instructions-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

function cpd_current_instructions_gather_inputs() {
	// Build the request from session-stored variables and any updated ones
	// passed in with the POST request
	if(isset($_REQUEST['start'])) {
		$_SESSION['cpd_current_instructions_start'] = $_REQUEST['start'];
	}
	if(isset($_REQUEST['limit'])) {
		$_SESSION['cpd_current_instructions_limit'] = $_REQUEST['limit'];
	}
	if(isset($_REQUEST['sectors'])) {
		$_SESSION['cpd_current_instructions_sectors'] = $_REQUEST['sectors'];
	}

	// Ensure any missing values are defaulted
	if(($_SESSION['cpd_current_instructions_start'] * 1) < 1) {
		$_SESSION['cpd_current_instructions_start'] = 1;
	}
	if(($_SESSION['cpd_current_instructions_limit'] * 1) < 1) {
		$options = get_option('cpd-search-options');
		$_SESSION['cpd_current_instructions_limit'] = $options['cpd_search_results_per_page'];
	}

	// Handle page number requests
	if($_REQUEST['page'] > 0) {
		$pagenum = $_REQUEST['page'] * 1;
		$limit = $_SESSION['cpd_current_instructions_limit'] * 1;
		$start = (($pagenum - 1) * $limit) + 1;
		$_SESSION['cpd_current_instructions_start'] = ($start > 0 ? $start : 1);
	}
}

function cpd_current_instructions() {
	cpd_current_instructions_init();
	cpd_register_interest_init();
	
	// Gather inputs from request/session
	cpd_current_instructions_gather_inputs();
	$start = $_SESSION['cpd_current_instructions_start'];
	$limit = $_SESSION['cpd_current_instructions_limit'];
	$sectors = $_SESSION['cpd_current_instructions_sectors'];

	// Read in form template from plugin options
	// Read in necessary form template sections from plugin options
	$form = cpd_get_template_contents("common");
	$form .= cpd_get_template_contents("user_registration");
	$form .= cpd_get_template_contents("user_login");
	$form .= cpd_get_template_contents("current_instructions");

	// Add variables to be passed to JS controller
	$form .= "\n".
		"<div style=\"display: none;\">\n".
		"\t<span id=\"pagenum\">[pagenum]</span>\n".
		"\t<span id=\"limit\">[limit]</span>\n".
		"\t<span id=\"trigger\">[trigger]</span>\n".
		"\t<span id=\"pagecount\">[pagecount]</span>\n".
		"</div>\n";

	// Add sector options
	$sectoroptions = cpd_sector_options($sectors);
	$form = str_replace("[sectoroptions]", $sectoroptions, $form);

	// Add per-page options
	$perpageoptions = cpd_perpage_options($limit);
	$form = str_replace("[perpageoptions]", $perpageoptions, $form);

	// Populate form defaults
	$form = str_replace("[sectors]", json_encode($sectors), $form);

	// Add page number and number of pages
	$start = $_SESSION['cpd_current_instructions_start'] * 1;
	$pagenum = floor(($start - 1) / $limit) + 1;
	$form = str_replace("[pagenum]", $pagenum, $form);
	$form = str_replace("[start]", $start, $form);
	$form = str_replace("[limit]", $limit, $form);

	// Add theme base URL
	$form = str_replace("[themeurl]", get_template_directory_uri(), $form);

	return $form;
}

add_shortcode('cpd_current_instructions', 'cpd_current_instructions');

function cpd_current_instructions_ajax() {
	// Gather inputs from request/session
	cpd_current_instructions_gather_inputs();
	$start = $_SESSION['cpd_current_instructions_start'];
	$limit = $_SESSION['cpd_current_instructions_limit'];
	$sectors =  $_SESSION['cpd_current_instructions_sectors'];
	
	// Send our search request to the server
	$searchCriteria = new SearchCriteriaType();
	$searchCriteria->Start = $start;
	$searchCriteria->Limit = $limit;
	$searchCriteria->DetailLevel = "brief";
	
	// Current instructions only searches owner agent's properties
	$options = get_option('cpd-search-options');
	$searchCriteria->Agent = $options['cpd_agentref'];
	
	// Check for given criteria
	if(is_array($sectors) && count($sectors) > 0) {
		$searchSectors = new SectorsType();
		$searchSectors->Sector = $sectors;
		$searchCriteria->Sectors = $searchSectors;
	}
	else if(strlen($sectors) > 0) {
		$searchSectors = new SectorsType();
		$searchSectors->Sector = array($sectors);
		$searchCriteria->Sectors = $searchSectors;
	}

	// Perform search
	$searchRequest = new SearchPropertyType();
	$searchRequest->SearchCriteria = $searchCriteria;
	try {
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
		$headers = wss_security_headers($options['cpd_agentref'], $options['cpd_password']);
		$client->__setSOAPHeaders($headers);
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
						$row['MediaType'] = $media->Type;
						$row['MediaURL'] = $media->URL;
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

add_action('wp_ajax_cpd_current_instructions', 'cpd_current_instructions_ajax');
add_action('wp_ajax_nopriv_cpd_current_instructions', 'cpd_current_instructions_ajax');

?>
