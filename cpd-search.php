<?php

/*
Plugin Name: CPD Search
Plugin URI: http://www.cpd.co.uk/wordpress-plugins/
Description: Provides a thin layer to the CPD REST API, via PHP/AJAX methods.
Version: 3.2.2
Author: The CPD Team
Author URI: http://www.cpd.co.uk/
Text Domain: cpd-search

Copyright 2011-2014 The CPD Team. All rights reserved. Every last one of them.
*/

//define('WP_DEBUG', true);

// Workaround for symlinked plugins (in development)...
if(!defined("cpd_plugin_dir_url")) {
	function cpd_plugin_dir_url($file) { return "/wp-content/plugins/cpd-search/".$file; }
}

// User token management functions
require_once(dirname(__FILE__) . "/cpd-user-token.php");

// Code for the admin settings page
require_once(dirname(__FILE__) . "/cpd-search-options.php");

// Some AJAX-related utility functions
require_once(dirname(__FILE__) . "/cpd-search-ajax.php");

// A widget for displaying the shortlist/basket/clipboard
require_once(dirname(__FILE__) . "/cpd-shortlist-widget.php");

// Utility functions
class CPDSearchUserAlreadyExistsException extends Exception {}
class CPDSearchUserNotRegisteredException extends Exception {}
class CPDSearchAgentNotAllowedVisitorsException extends Exception {}
class CPDSearchUserLoginFailedException extends Exception {}
class CPDSearchInvalidTokenException extends Exception {}

class CPDSearch {
	static function init() {
		// JQuery UI setup
		wp_enqueue_script('jquery');
		
		// Set up CPD javascript global config
		wp_enqueue_script('cpd-global', cpd_plugin_dir_url("cpd-global.js"), array(), "", false);
		
		// Set up CPD javascript controller for shortlist panel
		wp_enqueue_script('cpd-shortlist-widget', cpd_plugin_dir_url("cpd-shortlist-widget.js"), array(), "", false);
		
		// Set up CPD javascript global config
		$is_ssl = isset($_SERVER['HTTPS']);
		wp_enqueue_script('cpd-search', cpd_plugin_dir_url("cpd-search.js"), array(), "", false);
		wp_localize_script('cpd-search', 'CPDSearchConfig', array(
			'ajaxurl' => admin_url('admin-ajax.php', $is_ssl),
			'context' => get_option('cpd_service_context'),
			'agent_id' => get_option('cpd_agent_id'),
			'agent_ref' => get_option('cpd_agent_ref'),
			'agent_name' => get_option('cpd_agent_name'),
			'results_per_page' => get_option('cpd_results_per_page'),
		));
		
		// Ensure sessions are running
		if(!session_id()) {
			session_start();
		}
	}
	
	static function service_context() {
		$serviceContext = get_option('cpd_service_context');
		if($serviceContext == "") {
			return "WordpressPlugin";
		}
		return $serviceContext;
	}

	static function search($criteria) {
		// Record search
		$token = CPDSearchToken::get_user_token();
		$url = sprintf("%s/property/search/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($criteria));
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] != 201) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$search = json_decode($rawdata);
		
		// Record the search id in the session for later
		$_SESSION['cpdSearchId'] = $search->id;
		
		// Record and return results
		return $search;
	}
	
	static function last_search_id() {
		return isset($_SESSION['cpdSearchId']) ? $_SESSION['cpdSearchId'] * 1 : 0;
	}
	
	static function results($search_id, $opts) {
		$page = isset($opts['page']) ? $opts['page'] * 1 : 1;
		$limit = isset($opts['limit']) ? $opts['limit'] * 1 : 25;
		
		$params = array(
			'search_id' => $search_id,
			'page' => $page,
			'limit' => $limit
		);
		$token = CPDSearchToken::get_user_token();
		$url = sprintf("%s/property/?%s", get_option('cpd_rest_url'), http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$results = json_decode($rawdata);
		
		// Record and return results
		return $results;
	}
	
	/**
	 * @link https://www.cpd.co.uk/api/visitors/register/
	 * @throws CPDSearchUserAlreadyExistsException if e-mail is already
	 *  registered.
	 */
	static function register_visitor($visitor) {
		// Send visitor registration to server
		$token = get_option('cpd_application_token');
		$url = sprintf("%s/visitors/registeruser/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($visitor));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 409) {
			throw new CPDSearchUserAlreadyExistsException();
		}
		if($info['http_code'] == 402) {
			throw new CPDSearchAgentNotAllowedVisitorsException();
		}
		if($info['http_code'] != 201) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		// Store new token as a cookie
		$usertoken = json_decode($rawdata);
		CPDSearchToken::set_user_token($usertoken);
		
		return $usertoken;
	}
	
	/**
	 * @throws CPDSearchInvalidTokenException if token is invalid (expires/used)
	 */
	static function verify_user($token) {
		// Send visitor registration to server
		$url = sprintf("%s/visitors/verifyuser/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchInvalidTokenException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		return $usertoken;
	}
	
	/**
	 * @throws CPDSearchUserLoginFailedException if login/password is incorrect
	 */
	static function login_visitor($email, $password) {
		$login = array(
			'email' => $email,
			'password' => $password,
		);
		
		// Send visitor registration to server
		$token = get_option('cpd_application_token');
		$url = sprintf("%s/visitors/login/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $login);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchUserLoginFailedException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		// Store new token as a cookie
		$usertoken = json_decode($rawdata);
		CPDSearchToken::set_user_token($usertoken);
		
		return $usertoken;
	}
	
	/**
	 * @throws CPDSearchInvalidTokenException if session token is not valid
	 */
	static function logout_visitor() {
		$token = CPDSearchToken::get_user_token();
		$url = sprintf("%s/visitors/logout/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 401) {
			throw new CPDSearchInvalidTokenException();
		}
		if($info['http_code'] != 204) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		// Discard redundant token cookie
		CPDSearchToken::discard_token();
	}
	
	/**
	 * @throws CPDSearchUserLoginFailedException if login/password is incorrect
	 */
	static function reset_password($email) {
		$params = array(
			'email' => $email,
		);
		
		// Send visitor registration to server
		$token = get_option('cpd_application_token');
		$url = sprintf("%s/visitors/passwordreset/?%s", get_option('cpd_rest_url'), http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
			//'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		return true;
	}
	
	/**
	 * @throws CPDSearchInvalidTokenException if token is invalid (expires/used)
	 */
	static function change_password($token, $password) {
		$params = array(
			'password' => $password,
		);
		
		// Send visitor registration to server
		$url = sprintf("%s/visitors/passwordchange/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
			//'Content-Type: application/json'
		));
		//curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($login));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchInvalidTokenException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		return true;
	}
	
	static function view_property($propertyid) {
		$token = CPDSearchToken::get_user_token();
		$params = array(
			'property_id' => $propertyid
		);
		$url = sprintf("%s/visitors/viewproperty/?%s", get_option('cpd_rest_url'), http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchUserNotRegisteredException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$notification = json_decode($rawdata);
		
		// Record and return results
		return $notification;
	}
	
	/**
	 * @throws CPDSearchUserNotRegisteredException if user is not yet
	 *   registered.
	 */
	static function view_pdf($medialink_id) {
		$token = CPDSearchToken::get_user_token();
		$params = array(
			'medialink_id' => $medialink_id,
		);
		$url = sprintf("%s/visitors/viewmedia/?%s", get_option('cpd_rest_url'), http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchUserNotRegisteredException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$medialink = json_decode($rawdata);
		
		// Record and return results
		return $medialink;
	}
	
	/**
	 * @throws CPDSearchUserNotRegisteredException if user is not yet
	 *   registered.
	 */
	static function register_interest($propertyid) {
		$token = CPDSearchToken::get_user_token();
		$params = array(
			'property_id' => $propertyid
		);
		$url = sprintf("%s/visitors/registerinterest/?%s", get_option('cpd_rest_url'), http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] == 401) {
			throw new CPDSearchUserNotRegisteredException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$property = json_decode($rawdata);
		
		// Record and return results
		return $property;
	}
	
	/**
	 * @throws CPDSearchUserNotRegisteredException if user is not yet
	 *   registered.
	 */
	static function create_clipboard() {
		$token = CPDSearchToken::get_user_token();
		$url = sprintf("%s/users/clipboards/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchUserNotRegisteredException();
		}
		if($info['http_code'] != 201) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$clipboard = json_decode($rawdata, true);
		
		// Record and return results
		$_SESSION['cpd_clipboard'] = $clipboard;
		return $clipboard['clipboard_id'];
	}
	
	static function clipboard_id() {
		$clipboard = $_SESSION['cpd_clipboard'];
		return $clipboard['clipboard_id'];
	}

	/**
	 * @throws CPDSearchUserNotRegisteredException if user is not yet
	 *   registered.
	 */
	static function fetch_clipboard($clipboard_id) {
		if($clipboard_id < 1) {
			return CPDSearch::create_clipboard();
		}
		
		$token = CPDSearchToken::get_user_token();
		$url = sprintf("%s/users/clipboards/%d/", get_option('cpd_rest_url'), $clipboard_id);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchUserNotRegisteredException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$clipboard = json_decode($rawdata, true);
		
		// Record and return results
		$_SESSION['cpd_clipboard'] = $clipboard;
		return $clipboard;
	}
	
	/**
	 * @throws CPDSearchUserNotRegisteredException if user is not yet
	 *   registered.
	 */
	static function add_to_clipboard($propertyid) {
		// Identify existing clipboard, if we have one
		if(!isset($_SESSION['cpd_clipboard'])) {
			$clipboard_id = CPDSearch::create_clipboard();
		}
		else {
			$clipboard = $_SESSION['cpd_clipboard'];
			$clipboard_id = $clipboard['clipboard_id'];
		}
		
		$token = CPDSearchToken::get_user_token();
		$params = array(
			'property_id' => $propertyid,
			'action' => 'add'
		);
		$url = sprintf("%s/users/clipboards/%d/?%s", get_option('cpd_rest_url'), $clipboard_id, http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchUserNotRegisteredException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$clipboard = json_decode($rawdata, true);
		
		// Record and return results
		$_SESSION['cpd_clipboard'] = $clipboard;
		return $clipboard;
	}
	
	/**
	 * @throws CPDSearchUserNotRegisteredException if user is not yet
	 *   registered.
	 */
	static function remove_from_clipboard($propertyid) {
		$clipboardid = CPDSearch::clipboard_id();
		
		$token = CPDSearchToken::get_user_token();
		$params = array(
			'property_id' => $propertyid,
			'action' => 'remove'
		);
		$url = sprintf("%s/users/clipboards/%s/?%s", get_option('cpd_rest_url'), $clipboardid, http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] == 403) {
			throw new CPDSearchUserNotRegisteredException();
		}
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$clipboard = json_decode($rawdata, true);
		
		// Record and return results
		$_SESSION['cpd_clipboard'] = $clipboard;
		return $clipboard;
	}
	
	static function fetch_shortlist() {
		$shortlist = $_SESSION['cpdShortlist'];
		if(!$shortlist) {
			$shortlist = array();
			$_SESSION['cpdShortlist'] = $shortlist;
		}
		return $shortlist;
	}
	
	static function add_to_shortlist($propertyid) {
		// No need if already present
		$shortlist = self::fetch_shortlist();
		foreach($shortlist as $idx => $entry) {
			if($entry['propertyid'] == $propertyid) {
				return $shortlist;
			}
		}
		
		// Look up the address/brief details
		$token = CPDSearchToken::get_user_token();
		$url = sprintf("%s/property/%d/", get_option('cpd_rest_url'), $propertyid);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
			'X-CPD-Context: '.CPDSearch::service_context(),
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$property = json_decode($rawdata);
		
		// Add to the shortlist, with the address
		$entry = array(
			'propertyid' => $property->propref,
			'address' => $property->address,
		);
		$shortlist[] = $entry;
		$_SESSION['cpdShortlist'] = $shortlist;
		return $shortlist;
	}
	
	static function remove_from_shortlist($propertyid) {
		// No need if not already present
		$shortlist = self::fetch_shortlist();
		foreach($shortlist as $idx => $entry) {
			if($entry['propertyid'] == $propertyid) {
				unset($shortlist[$idx]);
			}
		}
		$_SESSION['cpdShortlist'] = $shortlist;
		return $shortlist;
	}
	
	/**
	 * Fetch a list of sectors pertinent to a particular agent.
	 */
	static function fetch_agent_sectors($agent_id) {
		// TODO: simple caching mech for efficiency/speed
		$token = CPDSearchToken::get_user_token();
		$url = sprintf("%s/property/sectors/?agent_id=%d&live=true", get_option('cpd_rest_url'), $agent_id);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.$token,
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if($info['http_code'] != 200) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		$agent_sectors = json_decode($rawdata, true);
		
		// Record and return results
		$_SESSION['cpd_agent_sectors'] = $agent_sectors;
		return $agent_sectors;
	}
	
	static function generate_password() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < 8; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	static function fullAddress($prop) {
		$address = $prop->address;
		if($prop->buildingnum) {
			$address = $prop->buildingnum." ".$address;
		}
		if($prop->buildingname) {
			$address = $prop->buildingname." ".$address;
		}
		return $address;
	}

	static function sizeDescription($property) {
		$sizefrom = $property->sizefrom;
		$sizeto = $property->sizeto;
		$sizeunit = $property->sizeunit == 1 ? 'sq ft' : 'sq m';
		if($sizefrom == $sizeto) {
			return $sizefrom." ".$sizeunit;
		}
		else {
			return $sizefrom." to ".$sizeto." ".$sizeunit;
		}
	}

	static function areaDescription($property) {
		if($property->postcode && $property->postcode->cpd_area) {
			return $property->postcode->cpd_area->name;
		}
		return "N/A";
	}

	static function sectorsDescription($property) {
		if(!$property->sectors || count($property->sectors) < 1) {
			return "N/A";
		}
		$desc = "";
		foreach($property->sectors as $sector) {
			$desc .= ", ".$sector->name;
		}
		return substr($desc, 2);
	}

	static function tenureDescription($tenure) {
		if($tenure == "L") {
			return "Leasehold";
		}
		else if($tenure == "F") {
			return "Freehold";
		}
		return "Leasehold/Freehold";
	}

	static function _cpd_media_folder($media) {
		$initial = substr($media->uuid, 0, 1);
		$four = substr($media->uuid, 0, 4);
		return sprintf("https://s3.amazonaws.com/cpd-media-live-%s/%s/%s", $initial, $four, $media->uuid);
	}
	static function mediaUrl($media) {
		return self::_cpd_media_folder($media)."/original/".$media->filename;
	}
	static function smallThumbUrl($media) {
		return self::_cpd_media_folder($media)."/thumb.jpg";
	}
	static function mediumThumbUrl($media) {
		return self::_cpd_media_folder($media)."/medium.jpg";
	}

	static function sector_ids() {
		return array(
			"1" => "Offices",
			"2" => "Serviced Offices",
			"3" => "Shops",
			"4" => "Industrial Warehousing",
			"5" => "Business Units",
			"6" => "Restaurant/Takeaway",
			"7" => "Pubs",
			"8" => "Leisure",
			"9" => "Retail Warehousing",
			"10" => "Showrooms",
			"11" => "Motor Related",
			"12" => "Mixed/Commercial",
			"13" => "Medical",
			"14" => "Studio/Gallery",
			"15" => "Arts/Crafts",
			"16" => "Live/Work Unit",
			"17" => "Education",
			"18" => "Storage",
			"19" => "Land/Site",
			"20" => "Hall/Misc",
			"21" => "Garden Centers"
		);
	}

	static function area_ids() {
		return array(
			'15' => 'London (E)',
			'73' => 'London (Clerkenwell)',
			'6' => 'London (EC)',
			'14' => 'London (N)',
			'13' => 'London (NW)',
			'11' => 'London (SE)',
			'10' => 'London (SW)',
			'7' => 'London (SW1 Knightsbridge)',
			'8' => 'London (SW1 St James)',
			'9' => 'London (SW1 Victoria)',
			'12' => 'London (W)',
			'2' => "London (W1 Fitzrovia",
			'72' => "London (W1 Noho)",
			'3' => "London (W1 Soho)",
			'4' => "London (W1 Mayfair)",
			'1' => "London (W1 Marylebone)",
			'74' => "London (W1 Portland Place/Regent St)",
			'5' => "London (WC)",
			'73' => "London (Clerkenwell)",
			'17' => 'Bedfordshire',
			'19' => 'Berkshire',
			'18' => 'Buckinghamshire',
			'52' => 'Cambridgeshire',
			'37' => 'Channel Islands',
			'63' => 'Cheshire',
			'30' => 'City of Bristol',
			'61' => 'Cleveland',
			'36' => 'Cornwall',
			'67' => 'Cumbria',
			'46' => 'Derbyshire',
			'35' => 'Devon',
			'34' => 'Dorset',
			'59' => 'Durham',
			'20' => 'Essex',
			'32' => 'Gloucestershire',
			'65' => 'Greater Manchester',
			'23' => 'Hampshire',
			'44' => 'Hereford and Worcester',
			'24' => 'Hertfordshire',
			'57' => 'Humberside',
			'68' => 'Isle of Man',
			'29' => 'Isle of Wight',
			'71' => 'Jersey',
			'21' => 'Kent',
			'64' => 'Lancashire',
			'47' => 'Leicestershire',
			'49' => 'Lincolnshire',
			'66' => 'Merseyside',
			'25' => 'Middlesex',
			'53' => 'Norfolk',
			'70' => 'North Ireland',
			'50' => 'Northamptonshire',
			'62' => 'Northumberland',
			'48' => 'Nottinghamshire',
			'26' => 'Oxfordshire',
			'51' => 'Rutland',
			'69' => 'Scotland',
			'42' => 'Shropshire',
			'33' => 'Somerset',
			'43' => 'Staffordshire',
			'54' => 'Suffolk',
			'16' => 'Surrey',
			'22' => 'Sussex (East)',
			'28' => 'Sussex (West)',
			'60' => 'Tyne and Wear',
			'39' => 'Wales (Middle)',
			'38' => 'Wales (North)',
			'40' => 'Wales (South)',
			'45' => 'Warwickshire',
			'41' => 'West Midlands',
			'31' => 'Wiltshire',
			'55' => 'Yorkshire (North)',
			'58' => 'Yorkshire (South)',
			'56' => 'Yorkshire (West)'
		);
	}
}

add_action('init', array('CPDSearch', 'init'), 1);

