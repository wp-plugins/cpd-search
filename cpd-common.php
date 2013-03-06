<?php

/**
 * Code and template for handling plugin settings.
 */

$cpd_templates = array(
	"common" => "Common Dialogs",
	"user_registration" => "User Registration",
	"user_verification" => "User Verification",
	"user_login" => "User Login",
	"user_password_change" => "User Password Change",
	"current_instructions" => "Current Instructions",
	"search_our_database" => "Search Our Database",
	"search_form_widget" => "Search Form Widget",
);

function cpd_search_admin_init() {
	$options = get_option('cpd-search-options');
	if(count($options) > 0) {
		return;
	}
	
	// Register settings
	register_setting( 'cpd-search-options', 'cpd_soap_base_url');
	register_setting( 'cpd-search-options', 'cpd_agentref');
	register_setting( 'cpd-search-options', 'cpd_application_token');
	register_setting( 'cpd-search-options', 'cpd_search_results_per_page');
	register_setting( 'cpd-search-options', 'cpd_map_widget_width');
	register_setting( 'cpd-search-options', 'cpd_map_widget_height');
	register_setting( 'cpd-search-options', 'cpd_ci_sectors');
	register_setting( 'cpd-search-options', 'cpd_sod_sectors');
	register_setting( 'cpd-search-options', 'cpd_service_context');
	register_setting( 'cpd-search-options', 'cpd_development_mode');
	register_setting( 'cpd-search-options', 'cpd_unregistered_pdfs');
	
	$options = array(
		'cpd_soap_base_url' => 'http://soap.cpd.co.uk/services/',
		'cpd_agentref' => 'youragentref',
		'cpd_application_token' => 'yourapplicationtoken',
		'cpd_map_widget_width' => '640',
		'cpd_map_widget_height' => '480',
		'cpd_search_results_per_page' => '10',
		'cpd_service_context' => 'WordpressPlugin',
		'cpd_development_mode' => false,
		'cpd_unregistered_pdfs' => false,
	);

	// Default an option for each template from it's template
	global $cpd_templates;
	foreach($cpd_templates as $id => $name) {
		register_setting( 'cpd-search-options', "cpd_".$id."_html");
		$form = file_get_contents(dirname(__FILE__) . "/inc/".$id."_ui.html");
		$options["cpd_".$id."_ui"] = $form;
	}

	update_option('cpd-search-options', $options);
}

function cpd_search_admin_menu() {
	add_options_page(
		__('CPD Search', 'cpd-search'),
		__('CPD Search', 'cpd-search'),
		'manage_options',
		__FILE__,
		'cpd_search_options_page');
}

function cpd_get_template($id) {
	return get_template_directory()."/cpdtemplates/".$id."_ui.html";
}

function cpd_get_template_uri($id) {
	return get_template_directory_uri()."/cpdtemplates/".$id."_ui.html";
}

function cpd_get_template_contents($id) {
	$options = get_option('cpd-search-options');
	if(file_exists(cpd_get_template($id)) && !$options['cpd_development_mode']) {
		$template = file_get_contents(cpd_get_template($id));
	}
	else {
		$template = file_get_contents(dirname(__FILE__)."/inc/".$id."_ui.html");
	}
	$template = str_replace("[themeurl]", get_template_directory_uri(), $template);
	$template = str_replace("[pluginurl]", home_url().cpd_plugin_dir_url('cpd-search'), $template);
	return $template;
}

function cpd_search_service_context() {
	$options = get_option('cpd-search-options');
	$serviceContext = $options['cpd_service_context'];
	if($serviceContext == "") {
		return "WordpressPlugin";
	}
	return $serviceContext;
}

function cpd_search_is_user_registered() {
	$cpd_token = $_COOKIE['cpd_token'];
	return ($cpd_token != null && strlen($cpd_token) == 36);
}

function cpd_search_wss_security_headers() {
	$options = get_option('cpd-search-options');
	$cpd_token = $_COOKIE['cpd_token'];
	if($cpd_token != "" && cpd_search_is_user_registered()) {
		return wss_security_headers($cpd_token, '');
	}
	return wss_security_headers($options['cpd_application_token'], "");
}

function cpd_search_set_user_token($token) {
	setcookie("cpd_token", $token, time() + (7 * 86400), "/");
}

function cpd_search_discard_token() {
	setcookie("cpd_token", "", time() - 86400, "/");
	unset($_COOKIE['cpd_token']); 
}

function cpd_check_agent_sectors() {
	global $soapopts;
	
	if(isset($_SESSION['cpd_agent_sectors'])) {
		return;
	}
	
	$request = new GetSectorsType();
	$request->AllSectors = true;
	
	try {
		$options = get_option('cpd-search-options');
		$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
		$headers = cpd_search_wss_security_headers();
		$client->__setSOAPHeaders($headers);
		$sectorsResponse = $client->GetSectors($request);
		$sectors = array();
		foreach($sectorsResponse->SectorList->Sector as $sector) {
			$sectors[$sector->SectorCode] = $sector->SectorDescription;
		}
		$_SESSION['cpd_agent_sectors'] = $sectors;
	}
	catch(Exception $e) {
		error_log("Error getting sectors for agent: " . $e->getMessage());
	}
}

?>
