<?php

/*
Plugin Name: CPD Search
Plugin URI: http://www.cpd.co.uk/cpd-search/
Description: Provides a thin layer to the CPD REST API, via PHP/AJAX methods.
Version: 3.0.6
Author: The CPD Team
Author URI: http://www.cpd.co.uk/
Text Domain: cpd-search

Copyright 2011-2013 The CPD Team. All rights reserved. Every last one of them.
*/

//define('WP_DEBUG', true);

// User token management functions
require_once(dirname(__FILE__) . "/cpd-user-token.php");

// Code for the admin settings page
require_once(dirname(__FILE__) . "/cpd-search-options.php");

// Some AJAX-related utility functions
require_once(dirname(__FILE__) . "/cpd-search-ajax.php");

// Workaround for symlinked plugins (in development)...
if(!defined("cpd_plugin_dir_url")) {
	function cpd_plugin_dir_url($file) { return "/wp-content/plugins/cpd-search/".$file; }
}

// Utility functions
class CPDSearchUserAlreadyExistsException extends Exception {}
class CPDSearchUserNotRegisteredException extends Exception {}
class CPDSearchAgentNotAllowedVisitorsException extends Exception {}

class CPDSearch {
	static function init() {
		// JQuery UI setup
		wp_enqueue_script('jquery');
		
		// Set up CPD javascript global config
		wp_enqueue_script('cpd-search', cpd_plugin_dir_url("cpd-search.js"), array(), "", false);
		wp_localize_script('cpd-search', 'CPDSearchConfig', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
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
		if($info['http_code'] == 405) {
			throw new CPDSearchAgentNotAllowedVisitorsException();
		}
		if($info['http_code'] != 201) {
			throw new Exception("Server connection failed: ".$info['http_code']);
		}
		
		// Store new token as a cookie
		$usertoken = json_decode($rawdata);
		CPDSearchToken::set_user_token($usertoken);
		
		// Ensure there is a clipboard in session memory
		if(!isset($_SESSION['cpd_clipboard'])) {
			$_SESSION['cpd_clipboard'] = CPDSearch::create_clipboard();
		}
		
		return $usertoken;
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
		$params = array(
			'property_id' => $propertyid,
		);
		$url = sprintf("%s/users/clipboards/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, $url);
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
	
	/**
	 * @throws CPDSearchUserNotRegisteredException if user is not yet
	 *   registered.
	 */
	static function fetch_clipboard($clipboard_id) {
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
		$clipboard = $_SESSION['cpd_clipboard'];
		$clipboardid = $clipboard['clipboard_id'];
		
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

}

add_action('init', array('CPDSearch', 'init'), 1);

?>
