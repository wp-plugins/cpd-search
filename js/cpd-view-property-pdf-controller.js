// Handles the viewing of PDFs

function CPDViewPropertyPDF() {
	var self = this;

	self.viewError = function(xhr, status, error) {
		if(xhr.status == 403) {
			// Show registration form
			jQuery('#cpdregistrationform').dialog("open");
			return;
		}
		
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
	};

	self.viewSuccess = function(data) {
		// Allow the redirect to go through
		window.location.href = data.media_url;
	};
	
	self.view = function(medialink_id) {
		// Make a 'view property PDF' call to the server
		var postdata = {
			'action': 'cpd_view_property_pdf',
			'medialink_id': medialink_id,
		};
	
		// Display 'loading...' dialog
		jQuery('#cpdloading').show();
	
		// Send AJAX search request to server
		self.view_allowed = false;
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.viewSuccess,
			error: self.viewError,
			dataType: "json",
			async: false
		};
		jQuery.ajax(ajaxopts);
		jQuery('#cpdloading').hide();
		
		return self.view_allowed;
	};
	
	self.click = function() {
		var medialink_id = jQuery(this).attr('id').substr(9);
		return self.view(medialink_id);
	};

	self.init = function() {
		//jQuery("#cpdsearchresults .buttonpdf").live('click', self.click);
	};
	
	return self;
}

cpdViewPropertyPDF = new CPDViewPropertyPDF();

jQuery(document).ready(function() {
	cpdViewPropertyPDF.init();
});

