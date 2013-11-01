<?php

class CPDCurrentInstructions {
	function init() {
		wp_enqueue_script('cpd-common-search-controller', plugins_url("cpd-search")."/cpd-common-search.js");
		wp_enqueue_script('cpd-current-instructions-controller', plugins_url("cpd-search")."/cpd-current-instructions.js");
		wp_enqueue_script('cpd-view-property-pdf-controller', plugins_url("cpd-search")."/cpd-view-property-pdf.js");
		wp_enqueue_script('cpd-view-property-image-controller', plugins_url("cpd-search")."/cpd-view-property-image.js");
		
		cpd_check_agent_sectors();
		
		//cpd_search_clear_stale_token_cookie();
	}

	function gather_inputs() {
		// Build the request from session-stored variables and any updated ones
		// passed in with the POST request
		if(isset($_REQUEST['page'])) {
			$page = $_REQUEST['page'] * 1;
			$_SESSION['cpd_current_instructions_page'] = ($page > 0 ? $page : 1);
		}
		if(isset($_REQUEST['limit'])) {
			$_SESSION['cpd_current_instructions_limit'] = $_REQUEST['limit'];
		}
		if(isset($_REQUEST['sectors'])) {
			$_SESSION['cpd_current_instructions_sectors'] = $_REQUEST['sectors'];
		}

		// Ensure any missing values are defaulted
		if(!isset($_SESSION['cpd_current_instructions_page']) || ($_SESSION['cpd_current_instructions_page'] * 1) < 1) {
			$_SESSION['cpd_current_instructions_page'] = 1;
		}
		if(!isset($_SESSION['cpd_current_instructions_limit']) || ($_SESSION['cpd_current_instructions_limit'] * 1) < 1) {
			$_SESSION['cpd_current_instructions_limit'] = get_option('cpd_search_results_per_page');
		}
	}

	function instructions() {
		// Gather inputs from request/session
		self::gather_inputs();
		$page = $_SESSION['cpd_current_instructions_page'];
		$limit = $_SESSION['cpd_current_instructions_limit'];
		$sectors = "";
		if(isset($_SESSION['cpd_current_instructions_sectors'])) {
			$sectors = $_SESSION['cpd_current_instructions_sectors'];
		}

		// Read in form template from plugin options
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
		$ci_sector_ids = explode(",", get_option('cpd_ci_sector_ids'));
		$sectoroptions = cpd_sector_options($ci_sector_ids, $sectors);
		$form = str_replace("[sectoroptions]", $sectoroptions, $form);

		// Populate form defaults
		$form = str_replace("[sectors]", json_encode($sectors), $form);

		// Add page number and number of pages
		$page = $_SESSION['cpd_current_instructions_page'] * 1;
		$form = str_replace("[pagenum]", $page, $form);
		$form = str_replace("[limit]", $limit, $form);

		// Add per-page options
		$perpageoptions = cpd_perpage_options($limit);
		$form = str_replace("[perpageoptions]", $perpageoptions, $form);

		// Add theme/plugin base URLs
		$form = str_replace("[pluginurl]", plugins_url("cpd-search"), $form);

		// Add link to client's T&C URL
		$form = str_replace("[termsurl]", get_option('cpd_terms_url'), $form);
		
		return $form;
	}

	function search_ajax() {
		// Gather inputs from request/session
		self::gather_inputs();
		$page = $_SESSION['cpd_current_instructions_page'];
		$limit = $_SESSION['cpd_current_instructions_limit'];
		$sectors = $_SESSION['cpd_current_instructions_sectors'];
		
		// Send our search request to the server
		$criteria = array();
		
		// Current instructions only searches owner agent's properties
		$criteria['AgentID'] = get_option('cpd_agent_id');
		$criteria['Scope'] = "live";
		
		// Check for given criteria
		if(is_array($sectors) && count($sectors) > 0) {
			$criteria['Sector'] = $sectors;
		}
		else if(strlen($sectors) > 0) {
			$criteria['Sector'] = array($sectors);
		}
		
		// Record search
		$token = cpd_get_user_token();
		$url = sprintf("%s/property/search/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($criteria));
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] != 201) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
	
	function search_page_ajax() {
		$search_id = $_REQUEST['search_id'];
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] * 1 : 1;
		$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] * 1 : 25;
		
		$params = array(
			'search_id' => $search_id,
			'page' => $page,
			'limit' => $limit
		);
		$token = cpd_get_user_token();
		$url = sprintf("%s/property/?%s", get_option('cpd_rest_url'), http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		curl_close($curl);
		
		header( "Content-Type: application/json" );
		echo $rawdata;
		exit;
	}
}

add_action("init", array("CPDCurrentInstructions", "init"));

add_shortcode('cpd_current_instructions', array('CPDCurrentInstructions', 'instructions'));

add_action('wp_ajax_cpd_current_instructions', array('CPDCurrentInstructions', 'search_ajax'));
add_action('wp_ajax_nopriv_cpd_current_instructions', array('CPDCurrentInstructions', 'search_ajax'));
add_action('wp_ajax_cpd_current_instructions_page', array('CPDCurrentInstructions', 'search_page_ajax'));
add_action('wp_ajax_nopriv_cpd_current_instructions_page', array('CPDCurrentInstructions', 'search_page_ajax'));

?>
