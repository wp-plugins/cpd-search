// Handles the viewing of PDFs

function CPDViewPropertyPDF() {
	var self = this;

	self.viewError = function(xhr, status, error) {
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
	
		if(xhr != null && xhr.error != null && xhr.error == "AccessDeniedExceptionMsg") {
			// Show registration form
			jQuery('#cpdregistrationform').dialog("open");
		}
	};

	self.viewSuccess = function(data) {
		// Check for failure
		if(!data) {
			return self.viewError(null, "Connection failed", "Server down. Please try again later");
		}
		if(data.error && data.error == "AccessDeniedExceptionMsg") {
			// Show registration form
			return jQuery('#cpdregistrationform').dialog("open");
		}
		if(data.error) {
			return self.viewError(null, data.error, data.error);
		}

		// Point the browser to the PDF file
		window.location.href = data.results.URL;
	};
	
	self.view = function(propref) {
		// Make a 'view property PDF' call to the server
		var postdata = {
			'action': 'cpd_view_property_pdf',
			'property_id': propref,
		};
	
		// Display 'loading...' dialog
		jQuery('#cpdloading').show();
	
		// Send AJAX search request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.viewSuccess,
			error: self.viewError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		jQuery('#cpdloading').hide();
	};
	
	self.click = function() {
		var propref = jQuery(this).parents('.result').attr('id').substr(8);
		self.view(propref);
	};

	self.init = function() {
		jQuery("#cpdsearchresults .buttonpdf").live('click', self.click);
	};
	
	return self;
}

cpdCPDViewPropertyPDF = new CPDViewPropertyPDF();

jQuery(document).ready(function() {
	cpdCPDViewPropertyPDF.init();
});

