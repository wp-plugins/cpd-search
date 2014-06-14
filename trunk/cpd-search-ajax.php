<?php

class CPDSearchAjax {
	static function init() {
		//wp_enqueue_script(cpd_plugin_dir_url('cpd-search-ajax.js'));
	}

	static function search_ajax() {
		// Gather inputs from request/session
		$criteria = $_POST['criteria'];
		try {
			$response = CPDSearch::search($criteria);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	static function results_ajax() {
		// Gather inputs from request/session
		$search_id = $_GET['search_id'];
		$opts = array(
			'page' => $_GET['page'] * 1,
			'limit' => $_GET['limit'] * 1,
		);
		try {
			$response = CPDSearch::results($search_id, $opts);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	static function add_to_clipboard_ajax() {
		// Gather inputs from request/session
		$property_id = trim($_REQUEST['property_id']);
		try {
			$response = CPDSearch::add_to_clipboard($property_id);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}

	static function remove_from_clipboard_ajax() {
		// Gather inputs from request/session
		$property_id = trim($_REQUEST['property_id']);
		try {
			$response = CPDSearch::remove_from_clipboard($property_id);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	static function add_to_shortlist_ajax() {
		// Gather inputs from request/session
		$property_id = trim($_REQUEST['property_id']);
		try {
			$response = CPDSearch::add_to_shortlist($property_id);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}

	static function remove_from_shortlist_ajax() {
		// Gather inputs from request/session
		$property_id = trim($_REQUEST['property_id']);
		try {
			$response = CPDSearch::remove_from_shortlist($property_id);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	static function register_interest_ajax() {
		// Gather inputs from request/session
		$property_id = trim($_REQUEST['property_id']);
		try {
			$response = CPDSearch::register_interest($property_id);
			$response = array(
				'success' => true,
				'response' => $response,
			);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	static function fetch_agent_sectors_ajax() {
		// Gather inputs from request/session
		$agent_id = trim($_REQUEST['agent_id']);
		try {
			$response = CPDSearch::agent_sectors($agent_id);
			$response = array(
				'success' => true,
				'response' => $response,
			);
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
}

add_action('init', array('CPDSearchAjax', 'init'), 1);

add_action('wp_ajax_nopriv_cpd_search', array('CPDSearchAjax', 'search_ajax'));
add_action('wp_ajax_cpd_search', array('CPDSearchAjax', 'search_ajax'));
add_action('wp_ajax_nopriv_cpd_results', array('CPDSearchAjax', 'results_ajax'));
add_action('wp_ajax_cpd_results', array('CPDSearchAjax', 'results_ajax'));
add_action('wp_ajax_nopriv_cpd_add_to_clipboard', array('CPDSearchAjax', 'add_to_clipboard_ajax'));
add_action('wp_ajax_cpd_add_to_clipboard', array('CPDSearchAjax', 'add_to_clipboard_ajax'));
add_action('wp_ajax_nopriv_cpd_remove_from_clipboard', array('CPDSearchAjax', 'remove_from_clipboard_ajax'));
add_action('wp_ajax_cpd_remove_from_clipboard', array('CPDSearchAjax', 'remove_from_clipboard_ajax'));
add_action('wp_ajax_nopriv_cpd_add_to_shortlist', array('CPDSearchAjax', 'add_to_shortlist_ajax'));
add_action('wp_ajax_cpd_add_to_shortlist', array('CPDSearchAjax', 'add_to_shortlist_ajax'));
add_action('wp_ajax_nopriv_cpd_remove_from_shortlist', array('CPDSearchAjax', 'remove_from_shortlist_ajax'));
add_action('wp_ajax_cpd_remove_from_shortlist', array('CPDSearchAjax', 'remove_from_shortlist_ajax'));
add_action('wp_ajax_nopriv_cpd_register_interest', array('CPDSearchAjax', 'register_interest_ajax'));
add_action('wp_ajax_cpd_register_interest', array('CPDSearchAjax', 'register_interest_ajax'));

