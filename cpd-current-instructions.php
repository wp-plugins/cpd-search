<?php

require_once(dirname(__FILE__) . "/cpd-common-search.php");

class CPDCurrentInstructions extends CPDCommonSearch {
	function init() {
		wp_enqueue_script('cpd-common-search-controller', cpd_plugin_dir_url(__FILE__) ."js/cpd-common-search-controller.js");
		wp_enqueue_script('cpd-current-instructions-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-current-instructions-controller.js");
		wp_enqueue_script('cpd-view-property-pdf-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-view-property-pdf-controller.js");
		wp_enqueue_script('cpd-view-property-image-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-view-property-image-controller.js");
		wp_localize_script('cpd-current-instructions-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		add_shortcode('cpd_current_instructions', array('CPDCurrentInstructions', 'instructions'));
		add_action('wp_ajax_cpd_current_instructions', array('CPDCurrentInstructions', 'search_ajax'));
		add_action('wp_ajax_nopriv_cpd_current_instructions', array('CPDCurrentInstructions', 'search_ajax'));
		
		session_start();
		
		cpd_check_agent_sectors();
	}

	function gather_inputs() {
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

	function instructions() {
		// Gather inputs from request/session
		self::gather_inputs();
		$start = $_SESSION['cpd_current_instructions_start'];
		$limit = $_SESSION['cpd_current_instructions_limit'];
		$sectors = $_SESSION['cpd_current_instructions_sectors'];

		// Read in form template from plugin options
		// Read in necessary form template sections from plugin options
		$form = cpd_get_template_contents("common");
		$form .= cpd_get_template_contents("user_registration");
		$form .= cpd_get_template_contents("user_login");
		$form .= cpd_get_template_contents("user_password_reset");
		$form .= cpd_get_template_contents("current_instructions");

		// Add variables to be passed to JS controller
		$form .= ''.
			'<div style="display: none;">'.
			'<span id="pagenum">[pagenum]</span>'.
			'<span id="limit">[limit]</span>'.
			'<span id="trigger">[trigger]</span>'.
			'<span id="pagecount">[pagecount]</span>'.
			'</div>';

		// Add hook to initialise controller code
		$form .= '<script>jQuery(document).ready(function() { cpdCurrentInstructions.init(); });</script>';
		
		// Add sector options
		$options = get_option('cpd-search-options');
		$ci_sectors = explode(",", $options['cpd_ci_sectors']);
		$sectoroptions = cpd_sector_options($ci_sectors, $sectors);
		$form = str_replace("[sectoroptions]", $sectoroptions, $form);

		// Populate form defaults
		$form = str_replace("[sectors]", json_encode($sectors), $form);

		// Add page number and number of pages
		$start = $_SESSION['cpd_current_instructions_start'] * 1;
		$pagenum = floor(($start - 1) / $limit) + 1;
		$form = str_replace("[pagenum]", $pagenum, $form);
		$form = str_replace("[start]", $start, $form);
		$form = str_replace("[limit]", $limit, $form);

		// Add per-page options
		$perpageoptions = cpd_perpage_options($limit);
		$form = str_replace("[perpageoptions]", $perpageoptions, $form);

		// Add theme/plugin base URLs
		$form = str_replace("[pluginurl]", plugins_url(), $form);

		// Add link to client's T&C URL
		$form = str_replace("[termsurl]", $options['cpd_terms_url'], $form);
		
		return $form;
	}

	function search_ajax() {
		global $soapopts;

		// Gather inputs from request/session
		self::gather_inputs();
		$start = $_SESSION['cpd_current_instructions_start'];
		$limit = $_SESSION['cpd_current_instructions_limit'];
		$sectors = $_SESSION['cpd_current_instructions_sectors'];
	
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
			$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
			$headers = cpd_search_wss_security_headers();
			$client->__setSOAPHeaders($headers);
			$searchResponse = $client->SearchProperty($searchRequest);
		}
		catch(Exception $e) {
			if($e->getMessage() == "The security token could not be authenticated or authorized") {
				cpd_search_discard_token();
				return self::search_ajax();
			}
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
			foreach($searchResponse->PropertyList->Property as $record) {
				$row = self::rowFromDB($record);
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
}

CPDCurrentInstructions::init();

?>
