// Shared between SOD and CI controllers

function cpd_view_property_image_error(data) {
	alert("Unable to load image for property.");
}

function cpd_view_property_image_success(data) {
	// Nice...
	id = "property" + data.id;
	image_url = data.image_url;
	jQuery('#' + id + ' #photolink').attr('href', image_url);
	jQuery('#' + id + ' #photolink').lightBox();
}
