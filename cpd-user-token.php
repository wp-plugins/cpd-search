<?php

/**
 * Uses the CPD application token from the settings to request and 'anonymous'
 * user session token to proceed with searches etc. for this user, until they
 * register and obtain their 'own' token.
 */
function cpd_get_user_token(){
	if(isset($_SESSION['cpd_user_token'])) {
		$usertoken = $_SESSION['cpd_user_token'];
		return $usertoken->token->token;
	}

	// Set the given server URL and token details
	$application_token = get_option('cpd_application_token');
	
	// Confirm the connection works by requesting the status of the agent token
	$url = sprintf("%s/agents/visitortoken/", get_option('cpd_rest_url'));
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
	
	// Parse response
	$usertoken = json_decode($rawdata);
	cpd_search_set_user_token($usertoken);
	return $usertoken->token->token;
}

function cpd_search_is_user_registered() {
	$cpd_token = $_COOKIE['cpd_token'];
	return ($cpd_token != null && strlen($cpd_token) == 36);
}

function cpd_search_set_user_token($token) {
	$_SESSION['cpd_user_token'] = $token;
	setcookie("cpd_token", $token->token->token, time() + (7 * 86400), "/");
}

function cpd_search_discard_token() {
	unset($_SESSION['cpd_user_token']);
	unset($_COOKIE['cpd_token']); 
	setcookie("cpd_token", "", time() - 86400, "/");
}

function cpd_search_clear_stale_token_cookie() {
	// If CPD token cookie is stale, remove it now before it causes problems
	// with the PDF/image links running into 403 at the proxy
	if(cpd_search_is_user_registered()) {
		$token = cpd_get_user_token();
		if(!cpd_search_check_token($token)) {
			cpd_search_discard_token();
		}
	}
}

function cpd_search_check_token($token) {
	// Set the given server URL and token details
	
	// Confirm the connection works by requesting the status of the agent token
	$params = array(
		'refresh' => true
	);
	$url = sprintf("%s/agents/statuscheck/?%s", get_option('cpd_rest_url'), http_build_query($params));
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'X-CPD-Token: '.$token
	));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$rawdata = curl_exec($curl);
	$info = curl_getinfo($curl);
	if(curl_errno($curl)) {
		error_log("CURL error: ".curl_error($curl));
		return false;
	}
	curl_close($curl);
	if($info['http_code'] != 200) {
		error_log("Token status check returned status: ".$info['http_code']);
		return false;
	}
	return true;
}

?>
