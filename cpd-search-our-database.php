<?php

class CPDSearchOurDatabase {
	function init() {
		wp_enqueue_script('cpd-common-search-controller', plugins_url("cpd-search")."/js/cpd-common-search-controller.js");
		wp_enqueue_script('cpd-search-our-database-controller', plugins_url("cpd-search")."/js/cpd-search-our-database-controller.js");
		wp_enqueue_script('cpd-view-property-pdf-controller', plugins_url("cpd-search")."/js/cpd-view-property-pdf-controller.js");
		wp_enqueue_script('cpd-view-property-image-controller', plugins_url("cpd-search")."/js/cpd-view-property-image-controller.js");
		wp_enqueue_script('cpd-view-property-image-lightbox-controller', plugins_url("cpd-search")."/js/lightbox/js/jquery.lightbox-0.5.js");
		wp_enqueue_style('cpd-view-property-image-lightbox-style', plugins_url("cpd-search")."//js/lightbox/css/jquery.lightbox-0.5.css");
		
		cpd_check_agent_sectors();
		
		//cpd_search_clear_stale_token_cookie();
	}

	function search_form() {
		// Read in necessary form template sections from plugin options
		$form = cpd_get_template_contents("common");
		$form .= cpd_get_template_contents("user_registration");
		$form .= cpd_get_template_contents("user_login");
		$form .= cpd_get_template_contents("user_password_reset");
		$form .= cpd_get_template_contents("search_our_database");
		
		$search_widget = 0;
		if(isset($_REQUEST['search_widget']))
			$search_widget = 1;
		
		// Add hook to initialise controller code
		$form .= '<script>jQuery(document).ready(function() { cpdSearchOurDatabase.init(); });</script>';

		// Add options for sizeunits pulldown
		$sizeunitoptions = cpd_sizeunit_options($sizeunits);
		$form = str_replace("[sizeunitoptions]", $sizeunitoptions, $form);

		// Add sector options
		$sod_sector_ids = explode(",", get_option('cpd_sod_sector_ids'));
		$sectoroptions = cpd_sector_options($sod_sector_ids, $sectors);
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
		$page = $_SESSION['cpd_search_our_database_page'] * 1;
		$form = str_replace("[pagenum]", $page, $form);
		$form = str_replace("[limit]", $limit, $form);

		// Add per-page options
		$perpageoptions = cpd_perpage_options($limit);
		$form = str_replace("[perpageoptions]", $perpageoptions, $form);

		// Add theme/plugin base URLs
		$form = str_replace("[pluginurl]", plugins_url("cpd-search"), $form);
		
		// Add link to client's T&C URL
		$form = str_replace("[termsurl]", get_option('cpd_terms_url'), $form);
		
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
		// Gather inputs from request/session
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] * 1 : 1;
		$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] * 1 : 25;
		$sectors = isset($_REQUEST['sectors']) ? $_REQUEST['sectors'] : "";
		$address = isset($_REQUEST['address']) ? $_REQUEST['address'] : "";
		$areas = isset($_REQUEST['areas']) ? $_REQUEST['areas'] : "";
		$sizefrom = isset($_REQUEST['sizefrom']) ? $_REQUEST['sizefrom'] : "";
		$sizeto = isset($_REQUEST['sizeto']) ? $_REQUEST['sizeto'] : "";
		$sizeunits = isset($_REQUEST['sizeunits']) ? $_REQUEST['sizeunits'] : "";
		$tenure = isset($_REQUEST['tenure']) ? $_REQUEST['tenure'] : "";
		$postcode = isset($_REQUEST['postcode']) ? $_REQUEST['postcode'] : "";
		
		// Send our search request to the server
		$criteria = array();
		
		// Check for given criteria
		if(is_array($sectors) && count($sectors) > 0) {
			$criteria['Sector'] = $sectors;
		}
		else if(strlen($sectors) > 0) {
			$criteria['Sector'] = array($sectors);
		}
		if(!empty($address)) {
			$criteria['Address'] = $address;
		}
		if(is_array($areas) && count($areas) > 0) {
			$criteria['AreaID'] = $areas;
		}
		if($sizefrom > 0) {
			$criteria['SizeFrom'] = $sizefrom;
		}
		if($sizeto > 0) {
			$criteria['SizeTo'] = $sizeto;
		}
		if($sizeunits > 0) {
			$criteria['SizeUnits'] = $sizeunits;
		}
		if(!empty($tenure)) {
			$criteria['Tenure'] = $tenure;
		}
		if(!empty($postcode)){
			$criteria['Postcode'] = $postcode;
		}
		$criteria['Scope'] = "live";
		
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
			header("HTTP/1.0 500 Internal Server Error");
			echo "Server connection failed: ".$info['http_code'];
			exit;
		}
		$search = json_decode($rawdata);
		
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

add_action('init', array('CPDSearchOurDatabase', 'init'));

add_shortcode('cpd_search_our_database', array('CPDSearchOurDatabase', 'search_form'));

add_action('wp_ajax_cpd_search_our_database', array('CPDSearchOurDatabase', 'search_ajax'));
add_action('wp_ajax_nopriv_cpd_search_our_database', array('CPDSearchOurDatabase', 'search_ajax'));
add_action('wp_ajax_cpd_search_our_database_page', array('CPDSearchOurDatabase', 'search_page_ajax'));
add_action('wp_ajax_nopriv_cpd_search_our_database_page', array('CPDSearchOurDatabase', 'search_page_ajax'));

?>
