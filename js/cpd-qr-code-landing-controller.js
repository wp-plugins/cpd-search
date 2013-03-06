// Handles the registration form and dialogs etc

function CPDQRCodeLanding() {
	var self = this;

	self.check_registration_name = /^[A-Za-z0-9_ ]{5,20}$/;
	self.check_registration_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	self.check_registration_phone = /^[0-9-]{10,20}$/;
	self.check_registration_password = /^[A-Za-z0-9_ ]{4,32}$/;

	self.view_pdf_success = function(data) {
		jQuery("#cpdloading").hide();

		// Check for failure
		if(!data.success) {
			return self.view_pdf_error(data, data.error, data.error);
		}

		jQuery("#cpdshowpdf").show();
		jQuery("#cpdshowpdf .directlink").attr('href', data.response.PropertyMedia.URL);
		window.location.href = data.response.PropertyMedia.URL;
	};
	
	self.view_pdf_error = function(jqXHR, textStatus, errorThrown) {
		jQuery("#cpdloading").hide();

		jQuery("#cpderror").show();
		jQuery("#cpderror").html(textStatus);
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
		
		// Set token as cookie
		document.cookie = "cpd_token=" + escape(data.token);
		
		var media_id = jQuery('#media_id').val();
		self.view_pdf(data.token, media_id);
	};
	
	self.registration_error = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdregistering').hide();

		var data = jqXHR;
		if(data != null && data.error != null && data.error.faultstring == "UserAlreadyExistsExceptionMsg") {
			// Show login form
			jQuery(".cpdpart").hide();
			jQuery("#cpdqrloginform").show();
			jQuery('#cpdqrloginform .message').html("No need to register. There is already an account for this e-mail address. Please try logging in with your existing credentials, or request a password reset if you have forgotten them.").show();
			return;
		}
		if(data != null && data.error != null) {
			jQuery('#cpderror').html("ERROR: " + data.error.faultstring);
			jQuery('#cpderror').dialog("open");
			return;
		}

		jQuery('#cpderror').html("Registration error!");
		jQuery('#cpderror').dialog("open");
	};

	self.login_success = function(data) {
		jQuery('#cpdqrloggingin').hide();

		// Check for failure
		if(!data.success) {
			return self.login_error(data, data.error, data.error);
		}

		// Hide registration form, show 'thankyou etc' part
		jQuery('#cpdqrloginform').hide();
		
		// Set token as cookie
		document.cookie = "cpd_token=" + escape(data.token);
		
		var media_id = jQuery('#media_id').val();
		self.view_pdf(data.token, media_id);
	};
	
	self.login_error = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdqrloggingin').hide();

		var data = jqXHR;
		if(data != null && data.error != null) {
			jQuery('#cpderror').html("ERROR: " + data.error.faultstring);
			jQuery('#cpderror').dialog("open");
			return;
		}

		jQuery('#cpderror').html("Login error!");
		jQuery('#cpderror').dialog("open");
	};

	self.password_reset_success = function(data) {
		jQuery('#cpdresettingpassword').hide();

		// Check for failure
		if(!data.success) {
			return self.password_reset_error(data, data.error, data.error);
		}

		// Hide registration form, show 'thankyou etc' part
		jQuery('#cpdpasswordreset').show();
	};
	
	self.password_reset_error = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdresettingpassword').hide();

		var data = jqXHR;
		if(data != null && data.error != null) {
			jQuery('#cpderror').html("ERROR: " + data.error.faultstring);
			jQuery('#cpderror').dialog("open");
			return;
		}

		jQuery('#cpderror').html("Password reset error!");
		jQuery('#cpderror').dialog("open");
	};

	self.show_registering = function(data) {
		jQuery('#cpdregistering').show();
	};

	self.show_logging_in = function(data) {
		jQuery('#cpdloggingin').show();
	};

	self.show_resetting_password = function(data) {
		jQuery('#cpdresettingpassword').show();
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
			'name': name,
			'email': email,
			'phone': phone,
		};

		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			beforeSend : self.show_registering,
			success: self.registration_success,
			error: self.registration_error,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};

	self.login = function() {
		// Validation checks
		var email = jQuery('#cpdqrloginform #email').val();
		var password = jQuery('#cpdqrloginform #password').val();
		if(!self.check_registration_email.test(email)) {
			return;
		}
		if(!self.check_registration_password.test(password)) {
			return;
		}

		// Prepare to send
		var postdata = {
			'action':'cpd_user_login',
			'email': email,
			'password': password,
		};

		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			beforeSend : self.show_logging_in,
			success: self.login_success,
			error: self.login_error,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};

	self.password_reset = function() {
		// Validation checks
		var email = jQuery('#cpdqrpasswordresetform #email').val();
		if(!self.check_registration_email.test(email)) {
			return;
		}

		// Prepare to send
		var postdata = {
			'action':'cpd_password_reset',
			'email': email,
		};

		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			beforeSend : self.show_resetting_password,
			success: self.password_reset_success,
			error: self.password_reset_error,
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
				jQuery('#error-phone').show().html("Invalid UK phone number (eleven digits, no spaces)");
				return;
			}
			jQuery('#error-phone').hide();
		});

		jQuery("#cpdqrregistrationform #submit").click(function() {
			cpdQRCodeLanding.registration();
			return false;
		});
		jQuery("#cpdqrloginform #submit").click(function() {
			cpdQRCodeLanding.login();
			return false;
		});
		jQuery("#cpdqrpasswordresetform #submit").click(function() {
			cpdQRCodeLanding.password_reset();
			return false;
		});
		
		jQuery('.loginlink').click(function() {
			jQuery('.cpdpart').hide();
			jQuery('#cpdqrloginform').show();
			return false;
		});
		jQuery('.registerlink').click(function() {
			jQuery('.cpdpart').hide();
			jQuery('#cpdqrregistrationform').show();
			return false;
		});
		jQuery('.lostpasswordlink').click(function() {
			jQuery('.cpdpart').hide();
			jQuery('#cpdqrpasswordresetform').show();
			return false;
		});
		
		// If user already logged in, go straight to view PDF
		var token = jQuery('#token').val();
		if(token != '') {
			var media_id = jQuery('#media_id').val();
			self.view_pdf(token, media_id);
			
		}
		
		// Show registration form for starters
		jQuery("#cpdloading").hide();
		jQuery("#cpdqrregistrationform").show();
	};
};

cpdQRCodeLanding = new CPDQRCodeLanding();

jQuery(document).ready(function() {
	cpdQRCodeLanding.init();
});

