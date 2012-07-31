// Handles the registration form and dialogs etc

function CPDRegisterInterest() {
	var self = this;

	// Stores 'register interest' events while it's waiting for user to register
	self.registering_interest_refs = [];

	// Stores list of proprefs this user has already registered interest for
	self.registered_interest_refs = [];

	self.init = function() {
		// [TODO] Ask server for list of existing 'basket' properties for user?
	};

	self.clickRegisterInterest = function(obj) {
		var propref = jQuery(this).parents('.result').attr('id').substr(8);
		self.registerInterest(propref);
	};
	
	self.registerInterestSuccess = function(data) {
		if(!data.success) {
			self.registerInterestError(data, data.error, data.error);
			return;
		}
		
		// Remove the propref from the registering list
		var id = "property" + data.propref;
		jQuery("#" + id + " .registeringinterest").hide();
		for(var i = 0; i < self.registering_interest_refs.length; i++) {
			if(data.propref == self.registering_interest_refs[i]) {
				self.registering_interest_refs.splice(i, 1);
			}
		}

		// Add the propref to our registered list
		jQuery("#" + id + " .registeredinterest").show();
		self.registered_interest_refs.push(data.propref);

		// Update basket count and show it
		jQuery("#basketcount").text(self.registered_interest_refs.length);
		jQuery("#basket").show();
	};
	self.registerInterestError = function(jqXHR, textStatus, errorThrown) {
		if(jqXHR != null && jqXHR.error != null && jqXHR.error.indexOf("InvalidTokenException") > -1) {
			// Show registration form
			jQuery('#cpdregistrationform').dialog("open");
			return;
		}
		if(jqXHR != null && jqXHR.error != null && jqXHR.error.indexOf("UserAlreadyExistsException") > -1) {
			// Show login form
			jQuery('#cpdregistrationform').dialog("close");
			jQuery('#cpdloginform').dialog("open");
			return;
		}

		// Show registration form
		jQuery('#cpdregistrationform').dialog("open");
	};
	self.registerInterest = function(propref) {
		var postdata = {
			'action':'cpd_register_interest',
			'propref': propref,
		};
	
		// Replace the 'register interest' button with 'registering interest'.
		var id = "property" + propref;
		jQuery("#" + id + " .registerinterest").hide();
		jQuery("#" + id + " .registeringinterest").show();
	
		// Add the propref to the queue and process it
		self.registering_interest_refs.push(propref);
		self.processQueue();
	};
	self.processQueue = function() {
		var postdata = {
			'action':'cpd_register_interest',
		};
	
		// Remove the propref from the registering list
		for(var i = 0; i < self.registering_interest_refs.length; i++) {
			postdata['propref'] = self.registering_interest_refs[i];

			// Send AJAX registration request to server
			var ajaxopts = {
				type: 'POST',
				url: CPDAjax.ajaxurl,
				data: postdata,
				success: self.registerInterestSuccess,
				error: self.registerInterestError,
				dataType: "json"
			};
			jQuery.ajax(ajaxopts);
		}
	};
	
	// Called by SOD/CI to have their buttons hooked up
	self.update_buttons = function(id, propref) {
		// Activate the register interest, or registered interest button accordingly
		if(self.registering_interest_refs.indexOf(propref) > -1) {
			jQuery("#" + id + " .registerinterest").hide();
			jQuery("#" + id + " .registeringinterest").show();
		}
		else if(self.registered_interest_refs.indexOf(propref) > -1) {
			jQuery("#" + id + " .registerinterest").hide();
			jQuery("#" + id + " .registeredinterest").show();
		}
		else {
			jQuery("#" + id + " .registerinterest").attr("propref", propref);
		}
	};
	
	return self;
}

cpdRegisterInterest = new CPDRegisterInterest();

