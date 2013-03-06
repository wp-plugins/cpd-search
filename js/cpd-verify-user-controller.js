function CPDVerifyUser() {
	var self = this;

	self.verifyTokenSuccess = function(data) {
		jQuery('#cpdverifyuser').dialog("close");

		// Check for failure
		if(!data) {
			return self.verifyTokenError(null, "Connection failed", "Server down. Please try again later");
		}
		if(data.error) {
			return self.verifyTokenError(null, data.error, data.error);
		}

		// Add visual identification that verification passed
		jQuery('#cpduserverified').show();
	};
	self.verifyTokenError = function(data) {
		jQuery('#cpdverifyuser').dialog("close");
	
		if(data != null && data.error != null && data.error == "UserAlreadyConfirmedExceptionMsg") {
			jQuery('#cpduseralreadyconfirmed').show();
			return;
		}

		// Add visual identification that verification passed
		jQuery('#cpdverifyuserfailed').show();
	};
	self.verifyToken = function(token) {
		var postdata = {
			'action':'cpd_verify_user',
			'token': token,
		};
	
		// Display 'Verifying...' dialog
		jQuery('#cpdverifyuser').hide();
		jQuery('#cpdverifyuser').dialog("open");
	
		// Send AJAX request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.verifyTokenSuccess,
			error: self.verifyTokenError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		jQuery("#cpdverifyuser").show();
	};
	
	self.init = function() {
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
		self.verifyToken(token);
	};
	
	return self;
}

cpdVerifyUser = new CPDVerifyUser();

jQuery(document).ready(function() {
	cpdVerifyUser.init();
});

