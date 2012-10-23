// Handles the registration form and dialogs etc

function CPDPasswordReset() {
	var self = this;

	self.check_registration_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

	self.passwordResetSuccess = function(data) {
		jQuery('#cpdresettingpassword').dialog("close");

		// Check for failure
		if(!data.success) {
			return self.passwordResetError(data, data.error, data.error);
		}

		// Hide passwordReset form
		jQuery('#cpdpasswordresetform').dialog("close");

		// Add visual identification that user is logged in
		jQuery('#cpdpasswordreset').dialog("open");

		// Process nearly registered interests
		cpdRegisterInterest.process_queue();
	};
	self.passwordResetError = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdresettingpassword').dialog("close");
	
		if(jqXHR.error !== undefined && jqXHR.error == "AuthenticationFailedExceptionMsg") {
			// Show passwordReset form
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
	self.passwordReset = function() {
		// Send AJAX registration request to server
		var postdata = {
			'action':'cpd_password_reset',
			'email': jQuery('#cpdpasswordresetform #email').val(),
			'password': jQuery('#cpdpasswordresetform #password').val(),
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.passwordResetSuccess,
			error: self.passwordResetError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	
		// Display 'Logging in...' dialog
		jQuery('#cpdresettingpassword').dialog("open");
	};

	self.init = function() {
		// Initialise password reset form
		jQuery("#cpdpasswordresetform").dialog({
			title: "Password reset",
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"Reset": function() {
					self.passwordReset(this);
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

		// Hook up links
		jQuery('.passwordresetlink').click(function() {
			jQuery('#cpdloginform').dialog("close");
			jQuery('#cpdpasswordresetform').dialog("open");
		});

		// Add verification of email
		jQuery('#cpdregistrationform #email').focusout(function() {
			var email = jQuery(this).val();
			if (!cpdPasswordReset.check_registration_email.test(email)) {
				jQuery('#error-email').show().html("Invalid email address");
				return;
			}
			jQuery('#error-email').hide();
		});
	}
	
	return self;
};

cpdPasswordReset = new CPDPasswordReset();

jQuery(document).ready(function() {
	cpdPasswordReset.init();
});

