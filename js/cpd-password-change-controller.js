// Handles the registration form and dialogs etc

function CPDPasswordChange() {
	var self = this;

	self.check_password =  /^[A-Za-z0-9!@#$%^&amp;*()_]{6,20}$/;
	
	self.passwordChangeSuccess = function(data) {
		jQuery('#cpdchangingpassword').dialog("close");

		// Check for failure
		if(!data.success) {
			return self.passwordChangeError(data, data.error, data.error);
		}

		// Hide password change form
		jQuery('#cpdpasswordchangeform').hide();

		// Add visual identification that user is logged in
		jQuery('#cpdpasswordchanged').show();

		// Process nearly registered interests
		cpdRegisterInterest.process_queue();
	};
	self.passwordChangeError = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdchangingpassword').dialog("close");
	
		if(jqXHR.error !== undefined && jqXHR.error.indexOf("AuthenticationFailedException") > -1) {
			// Show passwordChange form
			jQuery('#cpderror').html("Authentication failure! Please try again.");
			jQuery('#cpderror').dialog("open");
			return;
		}
		if(jqXHR.error !== undefined) {
			jQuery('#cpderror').html("ERROR: " + jqXHR.error);
			jQuery('#cpderror').dialog("open");
			return;
		}
		jQuery('#cpderror').html(textStatus);
		jQuery('#cpderror').dialog("open");
	};
	self.passwordChange = function() {
		// Send AJAX registration request to server
		var postdata = {
			'action':'cpd_password_change',
			'token': jQuery("#token").text(),
			'password1': jQuery('#cpdpasswordchangeform #password1').val(),
			'password2': jQuery('#cpdpasswordchangeform #password2').val(),
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.passwordChangeSuccess,
			error: self.passwordChangeError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	
		// Display 'Logging in...' dialog
		jQuery('#cpdchangingpassword').dialog("open");
		
		return false;
	};

	self.init = function() {
		// Initialise password change form
		jQuery("#cpdchangingpassword").dialog({
			title: "Requesting password change",
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
		
		// Add verification of password
		jQuery('#cpdpasswordchangeform #password1').focusout(function() {
			var password = jQuery(this).val();
			if (!self.check_password.test(password)){
				jQuery('#error-password1').show().html("Minimum 6 Characters");
				return;
			}
			jQuery('#error-password1').hide();
		});
		jQuery('#cpdpasswordchangeform #password2').focusout(function() {
			var password1 = jQuery('#cpdpasswordchangeform #password1').val();
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
		
		jQuery(".submit").click(self.passwordChange);
	}
	
	return self;
};

cpdPasswordChange = new CPDPasswordChange();

jQuery(document).ready(function() {
	cpdPasswordChange.init();
});

