<?php
/*
Plugin Name: CPD Search
Plugin URI: http://www.cpd.co.uk/cpd-search/
Description: Provides a range of tags that can be used to add commercial property database searches into pages. Uses the CPD SOAP API.
Version: 1.2.2
Author: The CPD Team
Author URI: http://www.cpd.co.uk/
Text Domain: cpd-search

 Copyright 2011-2012 The CPD Team. All rights reserved. Every last one of them.
*/

define('WP_DEBUG', true);

// SOAP client helper code
require_once(dirname(__FILE__) . "/lib/CPDPropertyService.php");
require_once(dirname(__FILE__) . "/lib/AgentService.php");
require_once(dirname(__FILE__) . "/lib/UserService.php");
require_once(dirname(__FILE__) . "/lib/securityHeader.php");

// Code for the admin settings page
require_once(dirname(__FILE__) . "/cpd-options.php");

// Workaround for symlinked plugins (in development)...
function cpd_plugin_dir_url($file) { return "/wp-content/plugins/cpd-search/"; }

// Hack to enable PHP sessions
add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');
function myStartSession() {
	if(!session_id()) {
		session_start();
	}
}
function myEndSession() {
	session_destroy();
}

function cpd_jquery_init() {
	if (is_admin()) {
		return;
	}
	
	// Google Maps setup
	wp_enqueue_script('google-maps', "http://maps.googleapis.com/maps/api/js?sensor=false", array(), "", false);
	
	// JQuery UI setup
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-slider', cpd_plugin_dir_url(__FILE__) . "js/jquery.ui.slider.js", array('jquery-ui-widget'), "", true);
	wp_enqueue_script('jquery-ui-dialog', cpd_plugin_dir_url(__FILE__) . "js/jquery.ui.dialog.js", array('jquery-ui-widget'), "", true);
	wp_enqueue_script('jquery-ui-draggable', cpd_plugin_dir_url(__FILE__) . "js/jquery.ui.draggable.js", array('jquery-ui-widget'), "", true);
	
	// CPD custom CSS
	wp_enqueue_style('cpd-search', cpd_plugin_dir_url(__FILE__) . "css/cpd-search-style.css");

	// Third-party CSS
	wp_enqueue_style('cpd-jquery', cpd_plugin_dir_url(__FILE__) . "css/jquery-ui-1.8.16.custom.css");
	
}
//add_action('init', 'cpd_jquery_init');
cpd_jquery_init();

// Add shortcodes and AJAX handlers for embedding forms and their results
require_once(dirname(__FILE__) . "/cpd-register-interest.php");
require_once(dirname(__FILE__) . "/cpd-user-registration.php");
require_once(dirname(__FILE__) . "/cpd-user-login.php");
require_once(dirname(__FILE__) . "/cpd-password-reset.php");
require_once(dirname(__FILE__) . "/cpd-current-instructions.php");
require_once(dirname(__FILE__) . "/cpd-search-our-database.php");
require_once(dirname(__FILE__) . "/cpd-verify-user.php");
require_once(dirname(__FILE__) . "/cpd-view-property-image.php");
require_once(dirname(__FILE__) . "/cpd-map-search.php");
require_once(dirname(__FILE__) . "/cpd-search-form-widget.php");

// AJAX handlers to back the form up
require_once(dirname(__FILE__) . "/cpd-geocode.php");
require_once(dirname(__FILE__) . "/cpd-sectors.php");

?>
