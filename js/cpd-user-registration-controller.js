// Handles the registration form and dialogs etc

function CPDUserRegistration() {
	var self = this;

	self.check_registration_name = /^[A-Za-z0-9_ ]{5,20}$/;
	self.check_registration_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	self.check_registration_phone = /^[0-9-]{10,20}$/;
	self.check_password =  /^[A-Za-z0-9!@#$%^&amp;*()_]{6,20}$/;

	self.registrationSuccess = function(data) {
		jQuery('#cpdregistering').hide();

		// Check for failure
		if(!data.success) {
			return self.registrationError(data, data.error, data.error);
		}

		// Hide registration form, show 'thankyou etc' part
		jQuery('#cpdregistrationform').dialog("close");
		jQuery('#cpdregistered').dialog("open");

		// Process nearly registered interests
		cpdRegisterInterest.processQueue();
	};
	self.registrationError = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdregistering').hide();
	
		if(jqXHR != null && jqXHR.error != null && jqXHR.error.indexOf("UserAlreadyExistsException") > -1) {
			// Show login form
			jQuery('#cpderror').html("No need to register. There is already an account for this e-mail address. Please try logging in with your existing credentials, or request a password reset if you have forgotten them.");
			jQuery('#cpderror').dialog("open");
			return;
		}
		if(jqXHR != null && jqXHR.error != null) {
			jQuery('#cpderror').html("ERROR: " + jqXHR.error);
			jQuery('#cpderror').dialog("open");
		}

		jQuery('#cpderror').html("Registration error!");
		jQuery('#cpderror').dialog("open");
	};
	self.registration = function() {
		// Validation checks
		var name = jQuery('#cpdregistrationform #name').val();
		var email = jQuery('#cpdregistrationform #email').val();
		var password1 = jQuery('#cpdregistrationform #password1').val();
		var password2 = jQuery('#cpdregistrationform #password2').val();
		var phone = jQuery('#cpdregistrationform #phone').val();
		if(!self.check_registration_name.test(name)) {
			return;
		}
		if(!self.check_registration_email.test(email)) {
			return;
		}
		if(!self.check_password.test(password1) || !self.check_password.test(password2) || password1 != password2) {
			return;
		}
		if(!self.check_registration_phone.test(phone)) {
			return;
		}

		// Prepare to send
		var postdata = {
			'action':'cpd_user_registration',
			'name': jQuery('#cpdregistrationform #name').val(),
			'email': jQuery('#cpdregistrationform #email').val(),
			'password': jQuery('#cpdregistrationform #password1').val(),
			'phone': jQuery('#cpdregistrationform #phone').val(),
		};

		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.registrationSuccess,
			error: self.registrationError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		jQuery('#cpdregistering').show();
	};

	self.loginSuccess = function(data) {
		jQuery('#cpdloggingin').dialog("close");

		// Check for failure
		if(!data.success) {
			return self.loginError(data, data.error, data.error);
		}

		// Hide login form
		jQuery('#cpdloginform').dialog("close");

		// Add visual identification that user is logged in
		jQuery('#cpdloggedin').dialog("open");

		// Process nearly registered interests
		cpdRegisterInterest.process_queue();
	};
	self.loginError = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdloggingin').dialog("close");
	
		if(jqXHR != null && jqXHR.error != null && jqXHR.error.indexOf("AuthenticationFailedException") > -1) {
			// Show login form
			jQuery('#cpderror').html("Authentication failure! Please try again.");
			jQuery('#cpderror').dialog("open");
			return;
		}
		if(jqXHR != null && jqXHR.error != null) {
			alert(jqXHR.error);
		}
	};
	self.login = function() {
		var postdata = {
			'action':'cpd_user_login',
			'email': jQuery('#cpdloginform #email').val(),
			'password': jQuery('#cpdloginform #password').val(),
		};
	
		// Display 'Logging in...' dialog
		jQuery('#cpdloggingin').dialog("open");
	
		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: cpd_user_login_success,
			error: cpd_user_login_error,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};

	self.init = function() {
		// Initialise registration form and confirmation
		jQuery("#cpdregistrationform").dialog({
			title: "User registration",
			autoOpen: false,
			height: 540,
			width: 420,
			resizable: false,
			modal: true,
			buttons: {
				"Register": self.registration,
				"Cancel": function() {
					jQuery(this).dialog("close");
				}
			}
		});
		jQuery("#cpdregistering").dialog({
			title: "Registering user",
			autoOpen: false,
			height: 200,
			width: 350,
			modal: true,
			buttons: {
				"Cancel": function() {
					jQuery(this).dialog("close");
				}
			}
		});
		jQuery("#cpdregistered").dialog({
			title: "User registered",
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"OK": function() {
					jQuery(this).dialog("close");
				}
			}
		});

		// Initialise login form and confirmation
		jQuery("#cpdloginform").dialog({
			title: "Login existing user",
			autoOpen: false,
			height: 300,
			width: 390,
			modal: true,
			buttons: {
				"Login": function() {
					cpd_user_login(this);
				},
				"Cancel": function() {
					jQuery(this).dialog("close");
				}
			}
		});
		jQuery("#cpdloggingin").dialog({
			title: "Logging in",
			autoOpen: false,
			height: 200,
			width: 350,
			modal: true,
			buttons: {
				"Cancel": function() {
					jQuery(this).dialog("close");
				}
			}
		});
		jQuery("#cpdloggedin").dialog({
			title: "User logged in",
			autoOpen: false,
			height: 300,
			width: 250,
			modal: true,
			buttons: {
				"OK": function() {
					jQuery(this).dialog("close");
				}
			}
		});

		// Initialise error dialog
		jQuery("#cpderror").dialog({
			title: "Error",
			autoOpen: false,
			height: 200,
			width: 350,
			modal: true,
			buttons: {
				"Cancel": function() {
					jQuery(this).dialog("close");
				}
			}
		});

		// Hook up links
		jQuery('.registrationlink').click(function() {
			jQuery('#cpdloginform').dialog("close");
			jQuery('#cpdregistrationform').dialog("open");
		});
		jQuery('.loginlink').click(function() {
			jQuery('#cpdregistrationform').dialog("close");
			jQuery('#cpdloginform').dialog("open");
		});

		// Add verification of user name
		jQuery('#cpdregistrationform #name').focusout(function() {
			var name = jQuery(this).val();
			if (!self.check_registration_name.test(name)){
				jQuery('#error-name').show().html("Minimum 5 characters");
				return;
			}
			jQuery('#error-name').hide();
		});

		// Add verification of email
		jQuery('#cpdregistrationform #email').focusout(function() {
			var email = jQuery(this).val();
			if (!self.check_registration_email.test(email)) {
				jQuery('#error-email').show().html("Invalid email address");
				return;
			}
			jQuery('#error-email').hide();
		});

		// Add verification of password
		jQuery('#cpdregistrationform #password1').focusout(function() {
			var password = jQuery(this).val();
			if (!self.check_password.test(password)){
				jQuery('#error-password1').show().html("Minimum 6 Characters");
				return;
			}
			jQuery('#error-password1').hide();
		});
		jQuery('#cpdregistrationform #password2').focusout(function() {
			var password1 = jQuery('#cpdregistrationform #password1').val();
			var password2 = jQuery(this).val();
			if (!self.check_password.test(password2)){
				jQuery('#error-password2').show().html("Minimum 6 Characters");
				return;
			}
			else if(password1 != password2){
				jQuery('#error-password2').show().html("Passwords don't match");
				return;
			}
			jQuery('#error-password1').hide();
			jQuery('#error-password2').hide();
		});
	
		// Add verification of phone
		jQuery('#cpdregistrationform #phone').focusout(function() {
			var phone = jQuery(this).val();
			if (!self.check_registration_phone.test(phone)){
				jQuery('#error-phone').show().html("Invalid phone number");
				return;
			}
			jQuery('#error-phone').hide();
		});
	};
	
	return self;
};

cpdUserRegistration = new CPDUserRegistration();

jQuery(document).ready(function() {
	cpdUserRegistration.init();
});

