// [TODO] move to it's own file
function cpd_property_image_showcase(id, propref) {
	// Go ask for the full image URL
	var postdata = {
		'action':'cpd_view_property_image',
		'propref': propref,
	};
	
	// Display 'loading...' dialog
	jQuery('#cpdloading').show();
	
	// Send AJAX search request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_view_property_image_success,
		error: cpd_view_property_image_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

