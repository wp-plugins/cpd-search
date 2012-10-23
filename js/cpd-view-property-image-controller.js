// Handles the viewing of PDFs

function CPDViewPropertyImage() {
	var self = this;

	self.viewError = function(xhr, status, error) {
		jQuery('#cpdloading').hide();
		
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
	};

	self.viewSuccess = function(data) {
		jQuery('#cpdloading').hide();
		
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

		// Open the PDF in a new window for the user
		var results = data.results;
		var plugin_url = data.plugin_url;
		var urlImage = results.URL;
		jQuery("#lightbox-show a").attr("href",urlImage);
		jQuery("#lightbox-show a").lightBox({
			imageLoading: plugin_url + 'js/lightbox/images/lightbox-ico-loading.gif',
			imageBtnPrev: plugin_url + 'js/lightbox/images/lightbox-btn-prev.gif',
			imageBtnNext: plugin_url + 'js/lightbox/images/lightbox-btn-next.gif',
			imageBtnClose: plugin_url + 'js/lightbox/images/lightbox-btn-close.gif',
			imageBlank: plugin_url + 'js/lightbox/images/lightbox-blank.gif',
		});
		jQuery("#lightbox-show a").click();
	};
	
	self.view = function(propref) {
		// Make a 'view property PDF' call to the server
		var postdata = {
			'action': 'cpd_view_property_image',
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
	};
	
	self.init = function() {
		jQuery("#cpdsearchresults .photo").live('click',function() {
			var propref = jQuery(this).parents('.result').attr('id').substr(8);
			self.view(propref);
		});
		
		jQuery("#lightbox-show").lightBox();
	};
	
	return self;
}

cpdViewPropertyImage = new CPDViewPropertyImage();

jQuery(document).ready(function() {
	cpdViewPropertyImage.init();
});

