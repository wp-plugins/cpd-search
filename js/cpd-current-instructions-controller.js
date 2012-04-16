// Main controller logic for initialising and handling the search form activity

function cpd_current_instructions_init_error() {
	// Put an error message in the results dialog
	jQuery("#cpdsearcherror").html("<p class='error'>Initialisation failed! " + error + " (" + status + ")</p>");
	jQuery("#cpdsearcherror").show();
	
	// Clear 'loading...' dialog
	jQuery("#cpdloading").hide();
}

function cpd_current_instructions_error(xhr, status, error) {
	// Put an error message in the results dialog
	jQuery("#cpdsearcherror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
	jQuery("#cpdsearcherror").show();

	// Clear 'searching...' dialog
	jQuery("#cpdsearching").hide();
	jQuery('#cpdsearchform').show();
}

function cpd_property_image_showcase(id, propref) {
	// Go ask for the full image URL
	var postdata = {
		'action':'cpd_view_property_image',
		'propref': propref,
	};
	
	// Display 'loading...' dialog
	jQuery('#cpdsearching').show();
	
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

function cpd_current_instructions_success(data) {
	// Check for failure
	if(!data.success) {
		return cpd_current_instructions_error(null, data.error, data.error);
	}

	// Handle no results scenario
	if(data.total < 1) {
		alert("No results found.");
		return;
	}
	
	// Clear results panel
	jQuery('#cpdsearchresults').empty();
	
	// Count pages
	var pagenum = Number(jQuery('span#pagenum').text()).valueOf();
	var limit = Number(jQuery('span#limit').text()).valueOf();
	var start = ((pagenum - 1) * limit) + 1;
	var pagecount = Math.floor((data.total - 1) / limit) + 1;
	
	// Loop for each result adding to the tableropref
	var resultnum = start;
	for (i in data.results) {
		var property = data.results[i];
		var id = "property" + property.PropertyID;
		
		// Clone a result model for this result
		var result = jQuery("#cpdsearchresultmodel").clone().attr("id", id);
		
		// Add to the results table
		jQuery('#cpdsearchresults').append(result);
		result.show();
		
		// Populate it
		jQuery("#" + id + " #resultnum").html(resultnum++);
		jQuery("#" + id + " #propref").html(property.PropertyID);
		jQuery("#" + id + " #typedesc").html(property.SectorDescription);
		jQuery("#" + id + " #sizedesc").html(property.SizeDescription);
		jQuery("#" + id + " #areadesc").html(property.RegionName);
		jQuery("#" + id + " #tenuredesc").html(property.TenureDescription);
		jQuery("#" + id + " #address").html(property.Address);
		jQuery("#" + id + " #summary").html(property.BriefSummary);
		if(property.ThumbURL === undefined) {
			jQuery("#" + id + " #photo").html("(No photo)");
		}
		else {
			jQuery("#" + id + " #photo").html("<img src=\"" + property.ThumbURL + "\"/>");
			jQuery("#" + id + " #photo").after("<a id=\"photolink\"></a>");
			jQuery("#" + id + " #photo").appendTo("#" + id + " #photolink");
			jQuery("#" + id + " #photo").click(function() {
				cpd_property_image_showcase(id, property.PropertyID);
			});
		}
	}
	
	// Add navigation bars
	var navbar = jQuery('#cpdsearchnavigationmodel');
	jQuery('#cpdsearchresults').prepend(navbar.clone().show());
	jQuery('#cpdsearchresults').append(navbar.clone().show());
	jQuery('.navbarresultcount').html(data.total);
	jQuery('.navbarpagenum').html(pagenum);
	jQuery('.navbarpagecount').html(pagecount);
	if(pagecount > 1 && pagenum > 1) {
		jQuery('.navbarprevpage').show().click(cpd_current_instructions_prev_page);
	}
	else {
		jQuery('.navbarprevpage').hide();
	}
	if(pagecount > 1 && pagenum < pagecount) {
		jQuery('.navbarnextpage').show().click(cpd_current_instructions_next_page);
	}
	else {
		jQuery('.navbarnextpage').hide();
	}

	// Clear loading dialog and hide form
	jQuery('#cpdsearching').hide();
	jQuery('#cpdsearchform').show();
}

function cpd_current_instructions_update_hash() {
	var type = jQuery("select#sectors option:selected").val();
	var page = Number(jQuery("span#pagenum").text()).valueOf();
	var limit = jQuery("select#limit option:selected").val();
	window.location.hash = "sector=" + type + "&page=" + page + "&limit=" + limit;
}

function cpd_current_instructions_sector_changed() {
	var type = jQuery("select#sectors option:selected").val();

	jQuery('#cpdsearching').show();
	cpd_current_instructions_update_hash();
	cpd_current_instructions();
}

function cpd_current_instructions_prev_page() {
	jQuery('.navbarprevpage').click(function() { return false; });

	var page = Number(jQuery("span#pagenum").text()).valueOf() - 1;
	jQuery('span#pagenum').text(page);

	jQuery('#cpdsearching').show();
	cpd_current_instructions_update_hash();
	cpd_current_instructions();
}

function cpd_current_instructions_next_page() {
	jQuery('.navbarnextpage').click(function() { return false; });

	var page = Number(jQuery("span#pagenum").text()).valueOf() + 1;
	jQuery('span#pagenum').text(page)

	jQuery('#cpdsearching').show();
	cpd_current_instructions_update_hash();
	cpd_current_instructions();
}

function cpd_current_instructions_per_page_changed() {
	var limit = jQuery("select#limit option:selected").val();
	jQuery('span#perpage').text(limit);

	jQuery('#cpdsearching').show();
	cpd_current_instructions_update_hash();
	cpd_current_instructions();
}

function cpd_current_instructions() {
	// Determine start result number and page length
	var pagenum = Math.floor(jQuery('#pagenum').html());
	var limit = Math.floor(jQuery("#limit").html());
	var start = ((pagenum - 1) * limit) + 1;
	
	// Gather criteria from form widgets
	var sectors = jQuery("select#sectors option:selected").val();
	var postdata = {
		'action':'cpd_current_instructions',
		'start': start,
		'limit': limit,
		'sectors': sectors,
	};
	
	// Display 'loading...' dialog
	jQuery('#cpdsearching').show();
	
	// Send AJAX search request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_current_instructions_success,
		error: cpd_current_instructions_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

function getURLParameter(name) {
	return decodeURI(
		(RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
	);
}

jQuery(document).ready(function() {
	// Show the search form
	jQuery("#cpdsearchform").show();

	// Hide the loading dialog
	jQuery("#cpdloading").hide();
	
	// Perform initial search
	cpd_current_instructions();
	
});
