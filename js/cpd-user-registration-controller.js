// Handles the registration form and dialogs etc

var check_registration_name = /^[A-Za-z0-9_ ]{5,20}$/;
var check_registration_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i 
var check_registration_password =  /^[A-Za-z0-9!@#$%^&amp;*()_]{6,20}$/;
var check_registration_phone = /^[0-9-]{10,20}$/;

function cpd_user_registration_success(data) {
	jQuery('#cpdregistering').dialog("close");

	// Check for failure
	if(!data.success) {
		return cpd_register_interest_error(data, data.error, data.error);
	}

	// Hide registration form, show 'thankyou etc' part
	jQuery('#cpdregistrationform').dialog("close");
	jQuery('#cpdregistered').dialog("open");

	// Process nearly registered interests
	cpd_register_interest_process_queue();
}

function cpd_user_registration_error(data) {
	jQuery('#cpdregistering').dialog("close");
	
	if(data != null && data.error != null && data.error.indexOf("UserAlreadyExistsException") > -1) {
		// Show login form
		jQuery('#cpderror').html("No need to register. This user already exists! Please try logging in with your existing credentials, or request a password reset if you have forgotten them.");
		jQuery('#cpderror').dialog("open");
		return;
	}
	if(data != null && data.error != null) {
		alert(data.error);
	}

	jQuery('#cpderror').html("Registration error!");
	jQuery('#cpderror').dialog("open");
}

function cpd_user_registration() {
	// Validation checks
	var name = jQuery('#cpdregistrationform #name').val();
	var email = jQuery('#cpdregistrationform #email').val();
	var password1 = jQuery('#cpdregistrationform #password1').val();
	var password2 = jQuery('#cpdregistrationform #password2').val();
	var phone = jQuery('#cpdregistrationform #phone').val();
	if(!check_registration_name.test(name)) {
		return;
	}
	if(!check_registration_email.test(email)) {
		return;
	}
	if(!check_registration_password.test(password1) || !check_registration_password.test(password2) || password1 != password2) {
		return;
	}
	if(!check_registration_phone.test(phone)) {
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
	
	// Display 'registering...' dialog
	jQuery('#cpdregistering').dialog("open");
	
	// Send AJAX registration request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
        beforeSend : show_waitting,
		success: cpd_user_registration_success,
		error: cpd_user_registration_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}
function show_waitting(data)
{
    jQuery('#cpdregistering').show();
}

function cpd_user_login_success(data) {
	jQuery('#cpdloggingin').dialog("close");

	// Check for failure
	if(!data.success) {
		return cpd_user_login_error(data, data.error, data.error);
	}

	// Hide login form
	jQuery('#cpdloginform').dialog("close");

	// Add visual identification that user is logged in
	jQuery('#cpdloggedin').dialog("open");

	// Process nearly registered interests
	cpd_register_interest_process_queue();
}

function cpd_user_login_error(data) {
	jQuery('#cpdloggingin').dialog("close");
	
	if(data != null && data.error != null && data.error.indexOf("AuthenticationFailedException") > -1) {
		// Show login form
		jQuery('#cpderror').html("Authentication failure! Please try again.");
		jQuery('#cpderror').dialog("open");
		return;
	}
	if(data != null && data.error != null) {
		alert(data.error);
	}
}

function cpd_user_login() {
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
}

function cpd_password_reset_success(data) {
	jQuery('#cpdresettingpassword').dialog("close");

	// Check for failure
	if(!data.success) {
		return cpd_password_reset_error(data, data.error, data.error);
	}

	// Hide password reset form
	jQuery('#cpdpasswordresetform').dialog("close");

	// Add visual identification that user is logged in
	jQuery('#cpdpasswordreset').dialog("open");
}

function cpd_password_reset_error(data) {
	jQuery('#cpdresettingpassword').dialog("close");
	
	if(data != null && data.error != null && data.error.indexOf("UnconfirmedUserException") > -1) {
		// Show login form
		jQuery('#cpderror').html("Unconfirmed user! Please check your e-mail for a validation token, or try re-registering.");
		jQuery('#cpderror').dialog("open");
		return;
	}
	if(data != null && data.error != null) {
		alert(data.error);
	}
}

function cpd_password_reset() {
	var postdata = {
		'action':'cpd_password_reset',
		'email': jQuery('#cpdpasswordresetform #email').val(),
	};
	
	// Display 'Logging in...' dialog
	jQuery('#cpdresettingpassword').dialog("open");
	
	// Send AJAX password reset request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_password_reset_success,
		error: cpd_password_reset_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

jQuery(document).ready(function() {
	// Initialise registration form and confirmation
	jQuery("#cpdregistrationform").dialog({
		title: "User registration",
		autoOpen: false,
		height: 540,
		width: 420					,
		resizable: false,
		modal: true,
		buttons: {
			"Register": cpd_user_registration,
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

	// Initialise password reset form
	jQuery("#cpdpasswordresetform").dialog({
		title: "Password reset",
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"Reset": function() {
				cpd_password_reset(this);
			},
			"Cancel": function() {
				jQuery(this).dialog("close");
			}
		}
	});
	jQuery("#cpdresettingpassword").dialog({
		title: "Requesting password reset",
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
	jQuery("#cpdpasswordreset").dialog({
		title: "Password reset",
		autoOpen: false,
		height: 200,
		width: 350,
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
	jQuery('.passwordresetlink').click(function() {
		jQuery('#cpdloginform').dialog("close");
		jQuery('#cpdpasswordresetform').dialog("open");
	});

	// Add verification of user name
	jQuery('#cpdregistrationform #name').focusout(function() {
		var name = jQuery(this).val();
		if (!check_registration_name.test(name)){
			jQuery('#error-name').show().html("Minimum 5 characters");
			return;
		}
		jQuery('#error-name').hide();
	});

	// Add verification of email
	jQuery('#cpdregistrationform #email').focusout(function() {
		var email = jQuery(this).val();
		if (!check_registration_email.test(email)) {
			jQuery('#error-email').show().html("Invalid email address");
			return;
		}
		jQuery('#error-email').hide();
	});

	// Add verification of password
	jQuery('#cpdregistrationform #password1').focusout(function() {
		var password = jQuery(this).val();
		if (!check_registration_password.test(password)){
			jQuery('#error-password1').show().html("Minimum 6 Characters");
			return;
		}
		jQuery('#error-password1').hide();
	});
	jQuery('#cpdregistrationform #password2').focusout(function() {
		var password1 = jQuery('#cpdregistrationform #password1').val();
		var password2 = jQuery(this).val();				
		if (!check_registration_password.test(password2)){
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
		if (!check_registration_phone.test(phone)){
			jQuery('#error-phone').show().html("Invalid phone number");
			return;
		}
		jQuery('#error-phone').hide();					
	});

});

