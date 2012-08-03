<?php

/*
Plugin Name: CPD Search
Plugin URI: http://www.cpd.co.uk/cpd-search/
Description: Provides a range of page/post tags and widgets that can be used to add commercial property database searches into pages. Uses the CPD SOAP API.
Version: 1.4.4
Author: The CPD Team
Author URI: http://www.cpd.co.uk/
Text Domain: cpd-search

Copyright 2011-2012 The CPD Team. All rights reserved. Every last one of them.
*/

//define('WP_DEBUG', true);

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
	
	// Google Maps setup
	wp_enqueue_script('google-maps', "http://maps.googleapis.com/maps/api/js?sensor=false", array(), "", false);
	
	// JQuery UI setup
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-slider');
	
	// CPD custom CSS
	wp_enqueue_style('cpd-search', cpd_plugin_dir_url(__FILE__) . "css/cpd-search-style.css");

	// Third-party CSS
	wp_enqueue_style('cpd-jquery-ui', cpd_plugin_dir_url(__FILE__) . "css/jquery-ui-1.8.16.custom.css");
}

//add_action('init', 'cpd_jquery_init');
cpd_jquery_init();

// User management handlers
require_once(dirname(__FILE__) . "/cpd-user-registration.php");
require_once(dirname(__FILE__) . "/cpd-verify-user.php");
require_once(dirname(__FILE__) . "/cpd-user-login.php");
require_once(dirname(__FILE__) . "/cpd-password-reset.php");
require_once(dirname(__FILE__) . "/cpd-password-change.php");

// Search handlers
require_once(dirname(__FILE__) . "/cpd-current-instructions.php");
require_once(dirname(__FILE__) . "/cpd-search-our-database.php");
require_once(dirname(__FILE__) . "/cpd-map-search.php");
require_once(dirname(__FILE__) . "/cpd-register-interest.php");

// Landing page for QR code advertisments
require_once(dirname(__FILE__) . "/cpd-qr-code-landing.php");

// Sidebar widgets
require_once(dirname(__FILE__) . "/cpd-search-form-widget.php");
require_once(dirname(__FILE__) . "/cpd-clipboard-widget.php");
require_once(dirname(__FILE__) . "/cpd-saved-searches-widget.php");

// Utility functions
require_once(dirname(__FILE__) . "/cpd-geocode.php");
require_once(dirname(__FILE__) . "/cpd-sectors.php");

?>
