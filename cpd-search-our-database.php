<?php

require_once(dirname(__FILE__) . "/cpd-common-search.php");

class CPDSearchOurDatabase extends CPDCommonSearch {
	function init() {
		wp_enqueue_script('cpd-common-search-controller', cpd_plugin_dir_url(__FILE__) ."js/cpd-common-search-controller.js");
		wp_enqueue_script('cpd-search-our-database-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-search-our-database-controller.js");
		wp_enqueue_script('cpd-view-property-pdf-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-view-property-pdf-controller.js");
		wp_enqueue_script('cpd-view-property-image-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-view-property-image-controller.js");
		wp_enqueue_script('cpd-view-property-image-lightbox-controller', cpd_plugin_dir_url(__FILE__) . "js/lightbox/js/jquery.lightbox-0.5.js");
		wp_enqueue_style('cpd-view-property-image-lightbox-style', cpd_plugin_dir_url(__FILE__) . '/js/lightbox/css/jquery.lightbox-0.5.css');
		wp_localize_script('cpd-search-our-database-controller', 'CPDAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		add_shortcode('cpd_search_our_database', array('CPDSearchOurDatabase', 'search_form'));
		add_action('wp_ajax_cpd_search_our_database', array('CPDSearchOurDatabase', 'search_ajax'));
		add_action('wp_ajax_nopriv_cpd_search_our_database', array('CPDSearchOurDatabase', 'search_ajax'));
		
		session_start();
		
		cpd_check_agent_sectors();
	}

	function search_form() {
		// Gather inputs from request/session
		self::gather_inputs();

		$start = $_SESSION['cpd_search_our_database_start'];
		$limit = $_SESSION['cpd_search_our_database_limit'];
		$sectors = $_SESSION['cpd_search_our_database_sectors'];
		$address = $_SESSION['cpd_search_our_database_address'];
		$areas = $_SESSION['cpd_search_our_database_areas'];
		$sizefrom = $_SESSION['cpd_search_our_database_sizefrom'];
		$sizeto = $_SESSION['cpd_search_our_database_sizeto'];
		$sizeunits = $_SESSION['cpd_search_our_database_sizeunits'];
		$tenure = $_SESSION['cpd_search_our_database_tenure'];
		$postcode = $_SESSION['cpd_search_our_database_postcode'];
	
		// Read in necessary form template sections from plugin options
		$form = cpd_get_template_contents("common");
		$form .= cpd_get_template_contents("user_registration");
		$form .= cpd_get_template_contents("user_login");
		$form .= cpd_get_template_contents("user_password_reset");
		$form .= cpd_get_template_contents("search_our_database");
		
		$search_widget = 0;
		if(isset($_REQUEST['search_widget']))
			$search_widget = 1;
		
		// Add variables to be passed to JS controller
		$form .= ''.
			'<div style="display: none;">'.
			'<span id="pagenum">[pagenum]</span>'.
			'<span id="limit">[limit]</span>'.
			'<span id="sizefrom">[sizefrom]</span>'.
			'<span id="sizeto">[sizeto]</span>'.
			'<span id="sizeunits">[sizeunits]</span>'.
			'<span id="sectors">[sectors]</span>'.
			'<span id="tenure">[tenure]</span>'.
			'<span id="postcode">[postcode]</span>'.
			'<span id="areas">[areas]</span>'.
			'<span id="address">[address]</span>'.
			'<span id="trigger">[trigger]</span>'.
			'<span id="pagecount">[pagecount]</span>'.
			'<span id="search_widget">'.$search_widget.'</span>'.
			'</div>';

		// Add hook to initialise controller code
		$form .= '<script>jQuery(document).ready(function() { cpdSearchOurDatabase.init(); });</script>';

		// Add options for sizeunits pulldown
		$sizeunitoptions = cpd_sizeunit_options($sizeunits);
		$form = str_replace("[sizeunitoptions]", $sizeunitoptions, $form);

		// Add sector options
		$options = get_option('cpd-search-options');
		$sod_sectors = explode(",", $options['cpd_sod_sectors']);
		$sectoroptions = cpd_sector_options($sod_sectors, $sectors);
		$form = str_replace("[sectoroptions]", $sectoroptions, $form);

		// Add tenure options
		$tenureoptions = cpd_tenure_options($tenure);
		$form = str_replace("[tenureoptions]", $tenureoptions, $form);

		// Add options for area pulldown
		$areaoptions = cpd_area_options($areas);
		$form = str_replace("[areaoptions]", $areaoptions, $form);

		// Populate form defaults
		$form = str_replace("[sizefrom]", $sizefrom, $form);
		$form = str_replace("[sizeto]", $sizeto, $form);
		$form = str_replace("[sizeunits]", $sizeunits, $form);
		$form = str_replace("[sectors]", json_encode($sectors), $form);
		$form = str_replace("[tenure]", $tenure, $form);
		$form = str_replace("[postcode]", $postcode, $form);
		$form = str_replace("[areas]", json_encode($areas), $form);
		$form = str_replace("[address]", $address, $form);

		// Add page number and number of pages
		$start = $_SESSION['cpd_search_our_database_start'] * 1;
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
		
		// Determine whether to trigger search or not
		$trigger = false;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$trigger = true;
		}
		else if(isset($_REQUEST['page']) || isset($_REQUEST['limit'])) {
			$trigger = true;
		}
		$form = str_replace("[trigger]", ($trigger ? "yes" : "no"), $form);

		return $form;
	}

	function search_ajax() {
		global $soapopts;

		// Gather inputs from request/session
		self::gather_inputs();
		$start = $_SESSION['cpd_search_our_database_start'];
		$limit = $_SESSION['cpd_search_our_database_limit'];
		$sectors = $_SESSION['cpd_search_our_database_sectors'];
		$address = $_SESSION['cpd_search_our_database_address'];
		$areas = $_SESSION['cpd_search_our_database_areas'];
		$sizefrom = $_SESSION['cpd_search_our_database_sizefrom'];
		$sizeto = $_SESSION['cpd_search_our_database_sizeto'];
		$sizeunits = $_SESSION['cpd_search_our_database_sizeunits'];
		$tenure = $_SESSION['cpd_search_our_database_tenure'];
		$postcode = $_SESSION['cpd_search_our_database_postcode'];
	
		// Send our search request to the server
		$searchCriteria = new SearchCriteriaType();
		$searchCriteria->Start = $start;
		$searchCriteria->Limit = $limit;
		$searchCriteria->DetailLevel = "brief";

		// Check for given criteria
		if(is_array($sectors) && count($sectors) > 0) {
			$searchSectors = new SectorsType();
			$searchSectors->Sector = $sectors;
			$searchCriteria->Sectors = $searchSectors;
		}
		if(!empty($address)) {
			$searchCriteria->Address = $address;
		}
		if(is_array($areas) && count($areas) > 0) {
			$searchCriteria->CPDAreaIDs = $areas;
		}
		if($sizefrom > 0) {
			$searchCriteria->MinSize = $sizefrom;
		}
		if($sizeto > 0) {
			$searchCriteria->MaxSize = $sizeto;
		}
		if($sizeunits > 0) {
			$searchCriteria->SizeUnits = $sizeunits;
		}
		if(!empty($tenure)) {
			$searchCriteria->Tenure = $tenure;
		}
		if(!empty($postcode)){
			$searchCriteria->Postcode = $postcode;
		}
	
		// Perform search
		$searchRequest = new SearchPropertyType();
		$searchRequest->SearchCriteria = $searchCriteria;
		try {
			$options = get_option('cpd-search-options');
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
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			header("Content-Type: application/json");
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
	
	function gather_inputs() {
		// Build the request from session-stored variables and any updated ones
		// passed in with the POST request
		if(isset($_REQUEST['start'])) {
			$_SESSION['cpd_search_our_database_start'] = $_REQUEST['start'];
		}
		if(isset($_REQUEST['limit'])) {
			$_SESSION['cpd_search_our_database_limit'] = $_REQUEST['limit'];
		}
		if(isset($_REQUEST['sectors'])) {
			$_SESSION['cpd_search_our_database_sectors'] = $_REQUEST['sectors'];
		}
		if(isset($_REQUEST['address'])) {
			$_SESSION['cpd_search_our_database_address'] = $_REQUEST['address'];
		}
		if(isset($_REQUEST['areas'])) {
			$_SESSION['cpd_search_our_database_areas'] = $_REQUEST['areas'];
		}
		if(isset($_REQUEST['sizefrom'])) {
			$_SESSION['cpd_search_our_database_sizefrom'] = $_REQUEST['sizefrom'];
		}
		if(isset($_REQUEST['sizeto'])) {
			$_SESSION['cpd_search_our_database_sizeto'] = $_REQUEST['sizeto'];
		}
		if(isset($_REQUEST['sizeunits'])) {
			$_SESSION['cpd_search_our_database_sizeunits'] = $_REQUEST['sizeunits'];
		}
		if(isset($_REQUEST['tenure'])) {
			$_SESSION['cpd_search_our_database_tenure'] = $_REQUEST['tenure'];
		}
		if(isset($_REQUEST['postcode'])) {
			$_SESSION['cpd_search_our_database_postcode'] = $_REQUEST['postcode'];
		}
	
		// Ensure any missing values are defaulted
		if(($_SESSION['cpd_search_our_database_start'] * 1) < 1) {
			$_SESSION['cpd_search_our_database_start'] = 1;
		}
		if(($_SESSION['cpd_search_our_database_limit'] * 1) < 1) {
			$options = get_option('cpd-search-options');
			$_SESSION['cpd_search_our_database_limit'] = $options['cpd_search_results_per_page'];
		}

		// Handle page number requests
		if($_REQUEST['page'] > 0) {
			$pagenum = $_REQUEST['page'] * 1;
			$limit = $_SESSION['cpd_search_our_database_limit'] * 1;
			$start = (($pagenum - 1) * $limit) + 1;
			$_SESSION['cpd_search_our_database_start'] = ($start > 0 ? $start : 1);
		}
	}
}

CPDSearchOurDatabase::init();

?>
