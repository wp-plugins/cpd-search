// Handles the viewing of PDFs

function cpd_view_property_pdf(propref) {
	// Make a 'view property PDF' call to the server
	var postdata = {
		'action': 'cpd_view_property_pdf',
		'propref': propref,
	};
	
	// Display 'loading...' dialog
	jQuery('#cpdloading').show();
	
	// Send AJAX search request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_view_property_pdf_success,
		error: cpd_view_property_pdf_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
	
}

function cpd_view_property_pdf_error(data) {
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

function cpd_view_property_pdf_success(data) {
	// Check for failure
	if(!data.success) {
		return cpd_view_property_pdf_error(data, data.error, data.error);
	}

	// Open the PDF in a new window for the user
	var id = "property" + data.propref;

	// [TODO] Hide loading dialog...
}

