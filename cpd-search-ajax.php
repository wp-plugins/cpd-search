<?php

class CPDSearchAjax {
	function search_ajax() {
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
	
	function results_ajax() {
		// Gather inputs from request/session
		$search_id = $_POST['search_id'];
		try {
			$response = CPDSearch::results($search_id, array());
		}
		catch(Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$response = $e->getMessage();
		}
		
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	function add_to_clipboard_ajax() {
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

	function remove_from_clipboard_ajax() {
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
	
	function register_interest_ajax() {
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
	
}

add_action('wp_ajax_cpd_add_to_clipboard', array('CPDSearchAjax', 'add_to_clipboard_ajax'));
add_action('wp_ajax_nopriv_cpd_add_to_clipboard', array('CPDSearchAjax', 'add_to_clipboard_ajax'));
add_action('wp_ajax_cpd_remove_from_clipboard', array('CPDSearchAjax', 'remove_from_clipboard_ajax'));
add_action('wp_ajax_nopriv_cpd_remove_from_clipboard', array('CPDSearchAjax', 'remove_from_clipboard_ajax'));
add_action('wp_ajax_cpd_register_interest', array('CPDSearchAjax', 'register_interest_ajax'));
add_action('wp_ajax_nopriv_cpd_register_interest', array('CPDSearchAjax', 'register_interest_ajax'));

?>
