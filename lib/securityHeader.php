<?php

define("WSS_SEC_NS", 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd');
define("WSS_UTIL_NS", 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');
define("CPD_NONCE_TYPE", 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary');
define("CPD_PASS_TYPE", 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest');

	function wss_security_headers($username, $password) {
		//Preparing WebService Security SOAP Header

		$created = date("Y-m-d\TH:i:s\Z");
		$nonce = wss_secure_random(16); 
		$digest = base64_encode(sha1($nonce.$created.$password, true));

		$nos = '<Nonce EncodingType="' . CPD_NONCE_TYPE . '">'.base64_encode($nonce).'</Nonce>';
		$p = '<Password Type="' . CPD_PASS_TYPE . '">'.$digest.'</Password>';

		// Create the token in a request
		$token = new stdClass;
		$token->Created = new SOAPVar($created, XSD_STRING, null, null, null, WSS_UTIL_NS);
		$token->Nonce = new SOAPVar($nos, XSD_ANYXML);
		$token->Username = new SOAPVar($username, XSD_STRING, null, null, null, WSS_SEC_NS);
		$token->Password = new SOAPVar($p, XSD_ANYXML);
		
		// Wrap the token in a request
		$wsec = new stdClass;
		$wsec->UsernameToken = new SoapVar($token, SOAP_ENC_OBJECT, null, null, null, WSS_SEC_NS);
		
		// Add as a SOAP header
		$headers = new SOAPHeader(WSS_SEC_NS, 'Security', $wsec, true);
		return $headers;
	}

	// To generate the random nonce
	function wss_secure_random($length) {
		$rnd = '';
		if(function_exists('openssl_random_pseudo_bytes')) {
			$rnd = openssl_random_pseudo_bytes($length, $strong);
			if($strong === TRUE)
				return $rnd;
		}
		for ($i = 0; $i < $length; $i++) {
			$sha= sha1(mt_rand());
			$char= mt_rand(0,30);
			$rnd.= chr(hexdec($sha[$char].$sha[$char+1]));
		}
		return $rnd;
	}

?>
