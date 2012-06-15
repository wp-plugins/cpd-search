// Handles the registration form and dialogs etc

// Stores 'register interest' events while it's waiting for user to register
var registering_interest_refs = [];

// Stores list of proprefs this user has already registered interest for
var registered_interest_refs = [];

function cpd_register_interest_init() {
	// [TODO] Ask server for list of existing 'basket' properties for user?
}


function cpd_register_interest_process_queue() {
	var postdata = {
		'action':'cpd_register_interest',
	};
	
	// Remove the propref from the registering list
	for(var i = 0; i < registering_interest_refs.length; i++) {
		postdata['propref'] = registering_interest_refs[i];

		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: cpd_register_interest_success,
			error: cpd_register_interest_error,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	}
}

function cpd_register_interest(propref) {
	var postdata = {
		'action':'cpd_register_interest',
		'propref': propref,
	};
	
	// Replace the 'register interest' button with 'registering interest'.
	var id = "property" + propref;
	jQuery("#" + id + " .registerinterest").hide();
	jQuery("#" + id + " .registeringinterest").show();
	
	// Add the propref to the queue and process it
	registering_interest_refs.push(propref);
	cpd_register_interest_process_queue();
}

function cpd_register_interest_error(data) {
	if(data != null && data.error != null && data.error.indexOf("InvalidTokenException") > -1) {
		// Show registration form
		jQuery('#cpdregistrationform').dialog("open");
		return;
	}
	if(data != null && data.error != null && data.error.indexOf("UserAlreadyExistsException") > -1) {
		// Show login form
		jQuery('#cpdregistrationform').dialog("close");
		jQuery('#cpdloginform').dialog("open");
		return;
	}

	// Show registration form
	jQuery('#cpdregistrationform').dialog("open");
}

function cpd_register_interest_success(data) {
	// Check for failure
	if(!data.success) {
		return cpd_register_interest_error(data, data.error, data.error);
	}

	// Replace the 'register interest' button with 'interest registered'.
	var id = "property" + data.propref;

	// Remove the propref from the registering list
	jQuery("#" + id + " .registeringinterest").hide();
	for(var i = 0; i < registering_interest_refs.length; i++) {
		if(data.propref == registering_interest_refs[i]) {
			registering_interest_refs.splice(i, 1);
		}
	}

	// Add the propref to our registered list
	jQuery("#" + id + " .registeredinterest").show();
	registered_interest_refs.push(data.propref);

	// Update basket count and show it
	jQuery("#basketcount").text(registered_interest_refs.length);
	jQuery("#basket").show();
}

