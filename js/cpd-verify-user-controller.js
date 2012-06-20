function cpd_verify_token_success(data) {
	jQuery('#cpdverifyuser').dialog("close");

	// Check for failure
	if(!data.success) {
		return cpd_verify_token_error(data, data.error, data.error);
	}

	// Add visual identification that verification passed
	jQuery('#cpduserverified').show();
}

function cpd_verify_token_error(data) {
	jQuery('#cpdverifyuser').dialog("close");
	
	if(data != null && data.error != null && data.error.indexOf("InvalidTokenException") > -1) {
		// Show registration form
		jQuery('#cpdverifyuserfailed').show();
		return;
	}
	if(data != null && data.error != null && data.error.indexOf("UserAlreadyExistsException") > -1) {
		// Show registration form
		jQuery('#cpdverifyuserfailed').show();
		return;
	}

	// Add visual identification that verification passed
	jQuery('#cpdverifyuserfailed').show();
}

function cpd_verify_token(token) {
	var postdata = {
		'action':'cpd_verify_user',
		'token': token,
	};
	
	// Display 'Verifying...' dialog
	jQuery('#cpdverifyuser').hide();
	jQuery('#cpdverifyuser').dialog("open");
	
	// Send AJAX password reset request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_verify_token_success,
		error: cpd_verify_token_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

jQuery(document).ready(function() {
	// Initialise various dialogs
	jQuery("#cpdverifyuser").dialog({
		title: "User verification",
		autoOpen: false,
		height: 150,
		width: 350,
		resizable: false,
		modal: true,
		buttons: {
			"Cancel": function() {
				jQuery(this).dialog("close");
			}
		}
	});

	jQuery("#cpdloading").hide();
	
	// Get token string and post it to AJAX method
	var token = jQuery("#token").text();
	cpd_verify_token(token);
});

