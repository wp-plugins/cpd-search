<?php

/**
 * Uses the CPD application token from the settings to request and 'anonymous'
 * user session token to proceed with searches etc. for this user, until they
 * register and obtain their 'own' token.
 */
class CPDSearchToken {
	function get_user_token(){
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
		self::set_user_token($usertoken);
		return $usertoken->token->token;
	}

	function set_user_token($token) {
		$_SESSION['cpd_user_token'] = $token;
		$_COOKIE['cpd_token'] = $token->token->token;
		setcookie("cpd_token", $token->token->token, time() + (7 * 86400), "/");
	}

	function is_user_registered() {
		$cpd_token = $_COOKIE['cpd_token'];
		return ($cpd_token != null && strlen($cpd_token) == 36);
	}

	function discard_token() {
		unset($_SESSION['cpd_user_token']);
		unset($_COOKIE['cpd_token']); 
		setcookie("cpd_token", "", time() - 86400, "/");
	}
}

?>
