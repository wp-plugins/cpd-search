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
	register_setting( 'cpd-search-options', 'cpd_password');
	register_setting( 'cpd-search-options', 'cpd_search_results_per_page');
	register_setting( 'cpd-search-options', 'cpd_map_widget_width');
	register_setting( 'cpd-search-options', 'cpd_map_widget_height');
	register_setting( 'cpd-search-options', 'cpd_service_context');
	register_setting( 'cpd-search-options', 'cpd_development_mode');
	
	$options = array(
		'cpd_soap_base_url' => 'http://soap.cpd.co.uk/services/',
		'cpd_agentref' => 'youragentref',
		'cpd_password' => 'password',
		'cpd_map_widget_width' => '640',
		'cpd_map_widget_height' => '480',
		'cpd_search_results_per_page' => '10',
		'cpd_service_context' => 'WordpressPlugin',
		'cpd_development_mode' => false,
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
	
	$template = file_get_contents(dirname(__FILE__)."/inc/".$id."_ui.html");
	if(file_exists(cpd_get_template($id))) {
		$template = file_get_contents(cpd_get_template($id));
	}
	$template = str_replace("[themeurl]", get_template_directory_uri(), $template);
	$template = str_replace("[pluginurl]", home_url().cpd_plugin_dir_url('cpd-search'), $template);
	return $template;
}

?>
