<?php

function cpd_sectors_ajax() {
	// Create the SOAP client
	$options = get_option('cpd-search-options');
	$soapopts = array('trace' => 1, 'exceptions' => 1);
	$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
	$headers = wss_security_headers($options['cpd_agentref'], $options['cpd_password']);
	$client->__setSOAPHeaders($headers);

	// Perform lookup
	try {
		$sectorsResponse = $client->GetSectors();
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
	foreach($sectorsResponse->SectorList->Sector as $entry) {
		$results[$entry->SectorCode] = $entry->SectorDescription;
	} 
	
	// Return response as JSON
	$response = array(
		'success' => true,
		'total' => count($results),
		'results' => $results,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_sectors', 'cpd_sectors_ajax');
add_action('wp_ajax_nopriv_cpd_sectors', 'cpd_sectors_ajax');

?>