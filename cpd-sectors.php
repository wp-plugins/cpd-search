<?php

class CPDSectors {
	function ajax() {
		global $soapopts;

		// Create the SOAP client
		$options = get_option('cpd-search-options');
		$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
		$headers = cpd_search_wss_security_headers();
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
}

add_action('wp_ajax_cpd_sectors', array('CPDSectors', 'ajax'));
add_action('wp_ajax_nopriv_cpd_sectors', array('CPDSectors', 'ajax'));

?>
