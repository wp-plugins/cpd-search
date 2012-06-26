// Main controller logic for initialising and handling the search form activity

var cpd_map_search_results_limit = 500;
var cpd_map_search_results_page_num = 1;
var cpd_map_search_results_num_pages = 1;
var cpd_map_search_results_cache = [];

// Default position for user with no GPS info - Oxford Street
var user_latitude = 51.515206;
var user_longitude = -0.1419640;
var user_radius = 2000; // 2km

// UI elements
var user_map;
var user_circle;
var redIcon = new google.maps.MarkerImage("http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png");
var greenIcon = new google.maps.MarkerImage("http://www.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png");

// Global lists
var cpd_map_search_markers = {};
var cpd_map_search_results = {};
var user_basket = {};

// Flags to prevent help dialogs being repeatedly shown
var cpd_help_dialog_1_shown = true;
var cpd_help_dialog_2_shown = false;

function cpd_map_search_init_error() {
	// Put an error message in the results dialog
	jQuery("#resultsdialog").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
	
	// Clear 'searching...' dialog
	jQuery(".searching").hide();
}

function cpd_map_search_error(xhr, status, error) {
	// Put an error message in the results dialog
	jQuery("#resultsdialog").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
	
	// Clear 'searching...' dialog
	jQuery(".searching").hide();
}

function cpd_map_search_success(data) {
	// Clear existing pins on the map
	for(i in cpd_map_search_markers) {
		var marker = cpd_map_search_markers[i];
		marker.setMap(null);
	}
	
	// Check for non-zero status code
	if(!data.success) {
		alert("Server error occurred. Please try again later.");
		return;
	}

	// Clear 'searching...' notices and initial help message
	jQuery(".searching").hide();
	jQuery("#help_dialog_1").hide();
	cpd_help_dialog_1_shown = false;
	
	// Record results for use in resuls dialog later
	cpd_map_search_results_cache = data.results;

	// Update 'results' heading to include total results
	jQuery("#results_dialog_stats").text(data.total + " properties found.");
	if(data.total > cpd_map_search_results_limit) {
		jQuery("#results_dialog_stats").text(data.total + " properties found - showing only " + cpd_map_search_results_limit + " most recent results.");
	}
	
	// Bail out now if no results found
	if(data.total == 0) {
		jQuery("#no_results_dialog").show();
		return;
	}
	jQuery("#no_results_dialog").hide();
	
	// Add pins to the map for each result received
	var bounds = new google.maps.LatLngBounds();
	for (i in data.results) {
		var property = data.results[i];
		
		// Add a new marker
		var icon = redIcon;
		if(user_basket[property.PropertyID] != null) {
			icon = greenIcon;
		}
		var position = new google.maps.LatLng(property.Latitude, property.Longitude);
		var marker = new google.maps.Marker({
			position: position,
			map: user_map,
			icon: icon
		});
		google.maps.event.addListener(marker, 'click', cpd_map_search_pin_clicked);
		google.maps.event.addListener(marker, 'mouseover', cpd_map_search_pin_mousedover);
		
		// Keep track of marker and result
		cpd_map_search_markers[property.PropertyID] = marker;
		cpd_map_search_results[property.PropertyID] = property;
		
		// Keep track of minimum/maxiumum co-ords for zooming later
		bounds.extend(position);
	}
	
	// Rezoom to fit
	user_map.fitBounds(bounds);
	
	// If not previously shown, show the 'search results' help dialog
	if(!cpd_help_dialog_2_shown) {
		jQuery("#help_dialog_2").show();
		cpd_help_dialog_2_shown = true;
	}
}

function cpd_map_search() {
	jQuery(".searching").show();
	jQuery("#results_dialog").hide();
	
	// Update size/type values
	cpd_size_range_update();
	cpd_sectors_update();
	
	// Gather criteria from form widgets
	var start = Math.floor((cpd_map_search_results_page_num - 1) * cpd_map_search_results_limit) + 1;
	var postdata = {
		'action':'cpd_map_search',
		'start': start,
		'limit': cpd_map_search_results_limit,
		'latitude': user_latitude,
		'longitude': user_longitude,
		'radius': user_radius
	};
	
	// Gather size range criteria
	var minsize = parseInt(jQuery("#sizefrom_value").attr("value"));
	var maxsize = parseInt(jQuery("#sizeto_value").attr("value"));
	if(!isNaN(minsize) && !isNaN(maxsize) && minsize < maxsize) {
		postdata.sizefrom = minsize;
		postdata.sizeto = maxsize;
	}

	// Gather sector criteria
	var types = jQuery("#typeslist li");
	var sectors = [];
	for(i in types) {
		if(types[i].id && types[i].className == 'selected') {
			var type = types[i].id.substring(5);
			sectors.push(type);
		}
	}
	if(sectors.length > 0) {
		postdata.sectors = sectors;
	}
	
	// Send AJAX search request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_map_search_success,
		error: cpd_map_search_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

function cpd_geolocate_success(data) {
	jQuery("#origin_value").text(data.location);
}

function cpd_geolocate_error(data) {
	console.log("Error getting geolocation details: " + data);
}

function cpd_geo_success(position) {
	//user_latitude = position.coords.latitude;
	//user_longitude = position.coords.longitude;
	cpd_map_set_centre();
}

function cpd_geo_error(data) {
	console.log("Error geocoding: " + data);
}

function cpd_sectors_success(data) {
	var sectors_list = data.results;
	var typeslist = jQuery("#typeslist");
	typeslist.empty();
	for(sectorcode in sectors_list) {
		var sectordesc = sectors_list[sectorcode];
		typeslist.append("<li id=\"type-" + sectorcode+ "\" class=\"selected\">" + sectordesc + "</li>");
		jQuery("#type-" + sectorcode).click(cpd_sector_toggle);
	}
	cpd_sectors_update();
}

function cpd_sectors_error(data) {
	alert("Error getting list of property types. Search may not function correctly.");
}

function cpd_sector_toggle() {
	this.className = (this.className == "selected") ? "" : "selected";
	cpd_sectors_update();
}

function cpd_sectors_update() {
	var typeslist = jQuery("#typeslist li");
	var selectedlist = jQuery("#typeslist li.selected");
	if(typeslist.length == selectedlist.length) {
		jQuery("#types_value").text("All");
		return;
	}
	var text = "";
	if(selectedlist.length > 0) {
		for(var i = 0; i < selectedlist.length; i++) {
			text = text + ", " + selectedlist[i].textContent;
		}
		text = text.substring(2);
	}
	else {
		text = "None";
	}
	jQuery("#types_value").text(text);
}

function cpd_map_place_center(location) {
	user_circle.setCenter(location);
	user_latitude = location.lat();
	user_longitude = location.lng();
	cpd_map_search();
	cpd_origin_update();
}

function cpd_radius_slider_slide(event, ui) {
	user_radius = ui.value * 1000;
	cpd_radius_slider_update();
}

function cpd_radius_slider_update() {
	var km = user_radius / 1000;
	jQuery("#radius_value").text(km + "km");
	if(user_circle) {
		user_circle.setRadius(user_radius);
	}
}

function cpd_radius_slider_change(event, ui) {
	user_radius = ui.value * 1000;
	cpd_map_search();
}

function cpd_map_set_centre() {
	user_map.setCenter(new google.maps.LatLng(user_latitude, user_longitude));
	
	cpd_origin_update();
}

function cpd_origin_update() {
	// Send AJAX geocoding request to obtain name for location
	jQuery("#origin_value").text("N/A");
	var postdata = {
		'action':'cpd_geocode',
		'latitude': user_latitude,
		'longitude': user_longitude
	};
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_geolocate_success,
		error: cpd_geolocate_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

function cpd_size_range_clear() {
	jQuery("#sizefrom_value").attr("value", "")
	jQuery("#sizeto_value").attr("value", "")
	jQuery("#size_value").text("All");
}

function cpd_size_range_change(event) {
	// Check if input was valid
	if(isNaN(parseInt(this.value))) {
		jQuery(this).addClass("error");
		jQuery("#size_value").text("All");
		return;
	}
	jQuery(this).removeClass("error");
	cpd_size_range_update();
}

function cpd_size_range_update() {
	var minsize = parseInt(jQuery("#sizefrom_value").attr("value"));
	var maxsize = parseInt(jQuery("#sizeto_value").attr("value"));
	if(isNaN(minsize) || isNaN(maxsize)) {
		jQuery("#size_value").text("N/A");
		return;
	}
	if(minsize > maxsize) {
		jQuery("#size_value").text("N/A");
		return;
	}
	jQuery("#size_value").text(minsize + " to " + maxsize + " m2");
}

function cpd_map_search_pin_mousedover(event) {
	// Identify result moused over by looking up key for Marker
	var propref = -1;
	for(propref in cpd_map_search_markers) {
		var marker = cpd_map_search_markers[propref];
		if(marker === this) {
			break;
		}
	}
	var property = cpd_map_search_results[propref];
	
	// Populate results dialog and display it
	cpd_map_search_update_results_dialog(property);
}

function cpd_map_search_update_results_dialog(property) {
	if(property.ThumbURL) {
		jQuery("#results_dialog_highlight_photo").attr('src', property.ThumbURL);
		jQuery("#results_dialog_highlight_photo").show();
	}
	else {
		jQuery("#results_dialog_highlight_photo").hide();
	}
	jQuery("#results_dialog_propref").text(property.PropertyID);
	jQuery("#results_dialog_size").text(property.SizeDescription);
	jQuery("#results_dialog_type").text(property.SectorDescription);
	jQuery("#results_dialog_address").text(property.Address);
	jQuery("#results_dialog_summary").text(property.BriefSummary);
	jQuery("#results_dialog").show();
}

function cpd_map_search_pin_clicked(event) {
	// Identify result moused over by looking up key for Marker
	var propref = -1;
	for(propref in cpd_map_search_markers) {
		var marker = cpd_map_search_markers[propref];
		if(marker === this) {
			break;
		}
	}
	var property = cpd_map_search_results[propref];
	
	// Toggle basket status and change pin colour
	if(user_basket[propref] == null) {
		cpd_map_search_markers[propref].setIcon(greenIcon);
		user_basket[propref] = property;
	}
	else {
		cpd_map_search_markers[propref].setIcon(redIcon);
		delete user_basket[propref];
	}

	// Update basket dialog
	cpd_map_search_basket_update();
}

function cpd_map_search_basket_update() {
	// Hide or show basket dialog, depending on whether it's empty or not
	var length = Object.keys(user_basket).length;
	if(length <= 0) {
		jQuery(".basket legend").text("No properties in basket");
	}
	else if(length == 1) {
		jQuery(".basket legend").text(length + " property in basket");
	}
	else {
		jQuery(".basket legend").text(length + " properties in basket");
	}

	// Create a new list item for each entry
	for(propref in user_basket) {
		var property = user_basket[propref];
	}
}

function cpd_map_search_ui_init() {
	jQuery("#criteria_dialog_minimised, #criteria_dialog_minimised *").click(function() {
		jQuery("#criteria_dialog").show();
		jQuery("#criteria_dialog_minimised").hide();
	});
	jQuery("#criteria_dialog img.closebutton").click(function() {
		jQuery("#criteria_dialog_minimised").show();
		jQuery("#criteria_dialog").hide();
	});
	jQuery("#results_dialog img.closebutton").click(function() {
		jQuery("#results_dialog").hide();
	});
	jQuery("#radius_button, #radius_button *").click(function() {
		jQuery("#radius_dialog").show();
	});
	jQuery("#radius_dialog img.closebutton").click(function() {
		jQuery("#radius_dialog").hide();
	});
	jQuery("#size_button, #size_button *").click(function() {
		jQuery("#size_dialog").show();
	});
	jQuery("#size_dialog img.closebutton").click(function() {
		jQuery("#size_dialog").hide();
	});
	jQuery("#types_button, #types_button *").click(function() {
		jQuery("#types_dialog").show();
	});
	jQuery("#types_dialog img.closebutton").click(function() {
		jQuery("#types_dialog").hide();
	});
	jQuery("#basket_dialog_minimised, #basket_dialog_minimised *").click(function() {
		jQuery("#basket_dialog").show();
		jQuery("#basket_dialog_minimised").hide();
	});
	jQuery("#basket_dialog img.closebutton").click(function() {
		jQuery("#basket_dialog_minimised").show();
		jQuery("#basket_dialog").hide();
	});
	jQuery("#no_results_dialog *").click(function() {
		jQuery("#no_results_dialog").hide();
	});
	jQuery("#help_dialog_1 *").click(function() {
		jQuery("#help_dialog_1").hide();
	});
	jQuery("#help_dialog_2 *").click(function() {
		jQuery("#help_dialog_2").hide();
	});
	jQuery("#sizerange_clear").click(cpd_size_range_clear);
	jQuery("#refreshbutton").click(cpd_map_search);

	// Set up the radius slider
	jQuery("#radius_slider").slider({
		range: "min",
		value: user_radius / 1000,
		min: 1,
		max: 50,
		slide: cpd_radius_slider_slide,
		change: cpd_radius_slider_change
	});
	
	// Ensure 'size' dialog is interactive
	jQuery("#size_dialog input.sizeinput").keypress(cpd_size_range_change);
	jQuery("#size_dialog input.sizeinput").change(cpd_size_range_change);

	// Make dialogs draggable
	jQuery(".draggable").draggable({
		containment: "window"
	});
}

jQuery(document).ready(function($) {
	// Set up UI events
	cpd_map_search_ui_init();
	
	// Get our current position if possible
	var geo = window.navigator.geolocation;
	if(geo) {
		geo.getCurrentPosition(cpd_geo_success);
	}
	
	// Place the map canvas on the page
	var mapdiv = document.getElementById("map_canvas");
	var map_opts = {
		zoom: 10,
		center: new google.maps.LatLng(user_latitude, user_longitude),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	user_map = new google.maps.Map(mapdiv, map_opts);
	google.maps.event.addListener(user_map, 'click', function(event) {
		cpd_map_place_center(event.latLng);
	});
	
	// Place the radius circle on the map
	var circle_opts = {
		map: user_map,
		clickable: false,
		strokeColor: "#00FF00",
		strokeOpacity: 0.8,
		strokeWeight: 1,
		fillColor: "#00FF00",
		fillOpacity: 0.35,
		center: new google.maps.LatLng(user_latitude, user_longitude),
		radius: user_radius
	};
	user_circle = new google.maps.Circle(circle_opts);
	cpd_radius_slider_update();

	// Load the set of sectors for the 'Types' criteria
	var postdata = {
		'action':'cpd_sectors'
	};
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_sectors_success,
		error: cpd_sectors_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);

	// Clear the 'loading...' dialog, and present criteria
	jQuery(".loading").hide();
	jQuery("#criteria_dialog_minimised").show();
});

