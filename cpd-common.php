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

function cpd_get_template($id) {
	return get_template_directory()."/cpdtemplates/".$id."_ui.html";
}

function cpd_get_template_uri($id) {
	return get_template_directory_uri()."/cpdtemplates/".$id."_ui.html";
}

function cpd_get_template_contents($id) {
	if(file_exists(cpd_get_template($id)) && !get_option('cpd_development_mode')) {
		$template = file_get_contents(cpd_get_template($id));
	}
	else {
		$template = file_get_contents(dirname(__FILE__)."/inc/".$id."_ui.html");
	}
	$template = str_replace("[themeurl]", get_template_directory_uri(), $template);
	$template = str_replace("[pluginurl]", plugins_url('cpd-search'), $template);
	return $template;
}

function cpd_search_service_context() {
	$serviceContext = get_option('cpd_service_context');
	if($serviceContext == "") {
		return "WordpressPlugin";
	}
	return $serviceContext;
}

function cpd_search_qrcode_service_context() {
	$serviceContext = get_option('cpd_qrcode_service_context');
	if($serviceContext == "") {
		return "WordpressPlugin";
	}
	return $serviceContext;
}

function cpd_check_agent_sectors() {
	if(isset($_SESSION['cpd_agent_sectors'])) {
		$sectors = $_SESSION['cpd_agent_sectors'];
		if(count($sectors) > 0) {
			return;
		}
	}
	
	$application_token = get_option('cpd_application_token');
	$cpd_rest_url = get_option('cpd_rest_url');
	if(!$cpd_rest_url) {
		$cpd_rest_url = "https://apps.cpd.co.uk/restapi/v1";
	}
	$url = sprintf("%s/property/sectors/", $cpd_rest_url);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'X-CPD-Token: '.$application_token
	));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$rawdata = curl_exec($curl);
	$info = curl_getinfo($curl);
	curl_close($curl);
	if($info['http_code'] != 200) {
		throw new Exception("Invalid HTTP status: ".$info['http_code']);
	}
	
	$response = json_decode($rawdata);
	$sectors = array();
	foreach($response->results as $sector) {
		$sectors[$sector->id] = $sector->name;
	}
	if(count($sectors) > 0) {
		$_SESSION['cpd_agent_sectors'] = $sectors;
	}
}

?>
