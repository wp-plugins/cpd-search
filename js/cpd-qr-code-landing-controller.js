// Handles the registration form and dialogs etc

function CPDQRCodeLanding() {
	var self = this;

	self.check_registration_name = /^[A-Za-z0-9_ ]{5,20}$/;
	self.check_registration_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	self.check_registration_phone = /^[0-9-]{10,20}$/;

	self.view_pdf_success = function(data) {
		// Check for failure
		if(!data.success) {
			return self.view_pdf_error(data, data.error, data.error);
		}

		window.location.href = data.response.PropertyMedia.URL;
	};
	
	self.view_pdf_error = function(jqXHR, textStatus, errorThrown) {
		alert("Unable to load PDF!");
	};
	
	self.view_pdf = function(token, media_id) {
		// Use the token to ask for a PDF
		// Make a 'view property PDF' call to the server
		var postdata = {
			'action': 'cpd_qr_code_view_pdf',
			'token': token,
			'media_id': media_id,
		};
	
		// Display 'loading...' dialog
		jQuery('#cpdloading').show();
	
		// Send AJAX search request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.view_pdf_success,
			error: self.view_pdf_error,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};

	self.registration_success = function(data) {
		jQuery('#cpdregistering').hide();

		// Check for failure
		if(!data.success) {
			return self.registration_error(data, data.error, data.error);
		}

		// Hide registration form, show 'thankyou etc' part
		jQuery('#cpdqrregistrationform').hide();
		
		var media_id = jQuery('#media_id').val();
		self.view_pdf(data.token, media_id);
	};
	
	self.registration_error = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdregistering').hide();

		var data = jqXHR;
		if(data != null && data.error != null && data.error == "UserAlreadyExistsExceptionMsg") {
			// Show login form
			jQuery('#cpderror').html("No need to register. There is already an account for this e-mail address. Please try logging in with your existing credentials, or request a password reset if you have forgotten them.");
			jQuery('#cpderror').show();
			return;
		}
		if(data != null && data.error != null) {
			jQuery('#cpderror').html("ERROR: " + data.error);
			jQuery('#cpderror').show();
		}

		jQuery('#cpderror').html("Registration error!");
		jQuery('#cpderror').show();
	};

	self.show_waiting = function(data) {
		jQuery('#cpdregistering').show();
	};

	self.registration = function() {
		// Validation checks
		var name = jQuery('#cpdqrregistrationform #name').val();
		var email = jQuery('#cpdqrregistrationform #email').val();
		var phone = jQuery('#cpdqrregistrationform #phone').val();
		if(!self.check_registration_name.test(name)) {
			return;
		}
		if(!self.check_registration_email.test(email)) {
			return;
		}
		if(!self.check_registration_phone.test(phone)) {
			return;
		}

		// Prepare to send
		var postdata = {
			'action':'cpd_qr_code_register_user',
			'name': jQuery('#cpdqrregistrationform #name').val(),
			'email': jQuery('#cpdqrregistrationform #email').val(),
			'phone': jQuery('#cpdqrregistrationform #phone').val(),
		};

		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			beforeSend : self.show_waiting,
			success: self.registration_success,
			error: self.registration_error,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};

	self.init = function() {
		// Add verification of user name
		jQuery('#cpdqrregistrationform #name').focusout(function() {
			var name = jQuery(this).val();
			if (!cpdQRCodeLanding.check_registration_name.test(name)){
				jQuery('#error-name').show().html("Minimum 5 characters");
				return;
			}
			jQuery('#error-name').hide();
		});

		// Add verification of email
		jQuery('#cpdqrregistrationform #email').focusout(function() {
			var email = jQuery(this).val();
			if (!cpdQRCodeLanding.check_registration_email.test(email)) {
				jQuery('#error-email').show().html("Invalid email address");
				return;
			}
			jQuery('#error-email').hide();
		});

		// Add verification of phone
		jQuery('#cpdqrregistrationform #phone').focusout(function() {
			var phone = jQuery(this).val();
			if (!cpdQRCodeLanding.check_registration_phone.test(phone)){
				jQuery('#error-phone').show().html("Invalid phone number");
				return;
			}
			jQuery('#error-phone').hide();
		});

		jQuery("#cpdqrregistrationform #submit").click(function() {
			cpdQRCodeLanding.registration();
			return false;
		});
	};
};

cpdQRCodeLanding = new CPDQRCodeLanding();

jQuery(document).ready(function() {
	cpdQRCodeLanding.init();
});

