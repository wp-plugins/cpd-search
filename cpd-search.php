<?php

/*
Plugin Name: CPD Search
Plugin URI: http://www.cpd.co.uk/cpd-search/
Description: Provides a range of page/post tags and widgets that can be used to add commercial property database searches into pages. Uses the CPD REST API.
Version: 1.7.2
Author: The CPD Team
Author URI: http://www.cpd.co.uk/
Text Domain: cpd-search

Copyright 2011-2013 The CPD Team. All rights reserved. Every last one of them.
*/

//define('WP_DEBUG', true);

// Code for the admin settings page
require_once(dirname(__FILE__) . "/cpd-search-options.php");

class CPDSearch {
	function init() {
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
	
		// Set up CPD javascript global
		wp_enqueue_script('cpd-global', plugins_url("cpd-search")."/cpd-global.js", array(), "", false);
		wp_localize_script('cpd-global', 'CPDAjax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'agentid' => get_option('cpd_agent_id'),
		));

		// CPD custom CSS
		wp_enqueue_style('cpd-search', plugins_url("cpd-search")."/css/cpd-search-style.css");

		// Third-party CSS
		wp_enqueue_style('cpd-jquery-ui', plugins_url("cpd-search")."/css/jquery-ui-1.8.16.custom.css");

		if(!session_id()) {
			session_start();
		}
	}
}

add_action('init', array('CPDSearch', 'init'), 1);

// Utility functions
require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-user-token.php");
require_once(dirname(__FILE__) . "/cpd-geocode.php");

// User management handlers
require_once(dirname(__FILE__) . "/cpd-user-registration.php");
require_once(dirname(__FILE__) . "/cpd-verify-user.php");
require_once(dirname(__FILE__) . "/cpd-user-login.php");
require_once(dirname(__FILE__) . "/cpd-password-reset.php");
require_once(dirname(__FILE__) . "/cpd-password-change.php");

// Search handlers
require_once(dirname(__FILE__) . "/cpd-current-instructions.php");
require_once(dirname(__FILE__) . "/cpd-search-our-database.php");
require_once(dirname(__FILE__) . "/cpd-register-interest.php");
//require_once(dirname(__FILE__) . "/cpd-view-property-image.php");
require_once(dirname(__FILE__) . "/cpd-view-property-pdf.php");

// Landing page for QR code advertisments
require_once(dirname(__FILE__) . "/cpd-qr-code-landing.php");

// Sidebar widgets
require_once(dirname(__FILE__) . "/cpd-search-form-widget.php");
require_once(dirname(__FILE__) . "/cpd-clipboard-widget.php");
require_once(dirname(__FILE__) . "/cpd-saved-searches-widget.php");
require_once(dirname(__FILE__) . "/cpd-login-widget.php");

?>
