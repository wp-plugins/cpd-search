// Main controller logic for initialising and handling the search form activity

function cpd_search_our_database_init_error() {
	// Show error message
	jQuery("#cpderror").html("<p class='error'>Initialisation failed! " + error + " (" + status + ")</p>");
	jQuery("#cpderror").dialog("open");
	
	// Clear other dialogs
	jQuery("#cpdsearching").hide();
	jQuery("#cpdsearchform").hide();
	jQuery("#cpdloading").hide();
}

function cpd_search_our_database_error(data, status, error) {
	// Show error message
	jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
	jQuery("#cpderror").dialog("open");
	
	// Clear other dialogs
	jQuery("#cpdsearching").hide();
	jQuery("#cpdsearchform").hide();
	jQuery("#cpdloading").hide();
}

function cpd_search_our_database_success(data) {
	// Check for failure
	if(!data.success) {
		return cpd_search_our_database_error(null, data.error, data.error);
	}

	// Handle no results scenario
	if(data.total < 1) {
		alert("No results found.");
		return;
	}
	
	// Clear results panel
	jQuery('#cpdsearchresults').empty();
	jQuery("#cpdsearchform").hide();
	jQuery("#cpderror").dialog("close");
	var roles = jQuery("span#role").text();
	// Count pages
	var pagenum = Number(jQuery('span#pagenum').text()).valueOf();
	if(isNaN(pagenum)) {
		pagenum = 1;
	}
	var limit = Number(jQuery('span#limit').text()).valueOf();
	if(isNaN(limit)) {
		limit = 20;
	}
	jQuery("select#limit").val(limit);
	jQuery("select#limit option").each(function() { 
		jQuery(this).removeAttr('selected');
		if(Number(this.text).valueOf() == limit)
			jQuery(this).attr('selected','selected');
	});
	var start = ((pagenum - 1) * limit) + 1;
	var pagecount = Math.floor((data.total - 1) / limit) + 1;
	
	// Determine sector(s) selected
	var sectors_json = jQuery('span#sectors').html();
	var sectors = jQuery.parseJSON(sectors_json);
	
	// Loop for each result adding to the table
	var resultnum = start;
	for (i in data.results) {
		var property = data.results[i];
		var propref = property.PropertyID.toString();
		var id = "property" + propref;
		
		// Clone a result model for this result
		var result = jQuery("#cpdsearchresultmodel").clone().attr("id", id);
		
		// Add to the results table
		jQuery('#cpdsearchresults').append(result);
		result.show();
		
		// Populate it
		jQuery("#" + id + " #resultnum").html(resultnum++);
		jQuery("#" + id + " #propref").html(propref);
		jQuery("#" + id + " #typedesc").html(property.SectorDescription);
		jQuery("#" + id + " #sizedesc").html(property.SizeDescription);
		jQuery("#" + id + " #areadesc").html(property.RegionName);
		jQuery("#" + id + " #tenuredesc").html(property.TenureDescription);
		jQuery("#" + id + " #address").html(property.Address);
		jQuery("#" + id + " #summary").html(property.BriefSummary);
		jQuery("#" + id + " .clipboardadd").attr("propref", propref);
		jQuery("#" + id + " .buttonpdf").attr("propref", propref);
		
		if(property.ThumbURL === undefined) {
			jQuery("#" + id + " #photo").html("(No photo)");
		}
		else {
			jQuery("#" + id + " #photo").html("<img src=\"" + property.ThumbURL + "\"/>");
			jQuery("#" + id + " #photo").after("<a id=\"photolink\"></a>");
			jQuery("#" + id + " #photo").appendTo("#" + id + " #photolink");
			jQuery("#" + id + " #photo").attr("propref", propref);
			jQuery("#" + id + " #photo").click(function() {
				var propref = this.attributes.getNamedItem("propref").nodeValue;
				cpd_property_image_showcase(id, propref);
			});
		}
		
		// Activate the register interest, or registered interest button accordingly
		if(registering_interest_refs.indexOf(propref) > -1) {
			jQuery("#" + id + " .registerinterest").hide();
			jQuery("#" + id + " .registeringinterest").show();
		}
		else if(registered_interest_refs.indexOf(propref) > -1) {
			jQuery("#" + id + " .registerinterest").hide();
			jQuery("#" + id + " .registeredinterest").show();
		}
		else {
			jQuery("#" + id + " .registerinterest").attr("propref", propref);
			jQuery("#" + id + " .registerinterest").click(function() {
				var propref = this.attributes.getNamedItem("propref").nodeValue;
				cpd_register_interest(propref);
			});
		}

		if(property.PDFMediaID !== undefined) {
			jQuery("#" + id + " .buttonpdf").show();
			jQuery("#" + id + " .buttonpdf").attr("mediaid", property.PDFMediaID);
			jQuery("#" + id + " .buttonpdf").click(function() {
				var media_id = this.attributes.getNamedItem("mediaid").nodeValue;
				cpd_view_property_pdf(media_id);
			});
		}
	}
	
	// Add navigation bars
	var navbar = jQuery('#cpdsearchnavigationmodel');
	jQuery('#cpdsearchresults').prepend(navbar.clone().show());
	jQuery('#cpdsearchresults').append(navbar.clone().show());
	jQuery('.navbarresultcount').html(data.total);
	jQuery('.navbarpagenum').val(pagenum);
	jQuery('.navbarpagecount').html(pagecount);
	jQuery('#pagecount').html(pagecount);
	
	if(pagecount > 1 && pagenum > 1) {
		jQuery('.navbarprevpage').show().click(cpd_search_our_database_prev_page);
	}
	else {
		jQuery('.navbarprevpage').hide();
	}
	if(pagecount > 1 && pagenum < pagecount) {
		jQuery('.navbarnextpage').show().click(cpd_search_our_database_next_page);
	}
	else {
		jQuery('.navbarnextpage').hide();
	}

	// Clear loading dialog and hide form
	cpd_clipboard_widget_hide_show();
	jQuery('#cpdsearching').hide();
	jQuery('#cpdsearchform').hide();
}

function cpd_search_our_database_prev_page() {
	jQuery('.navbarprevpage').click(function() {
		return false;
	});
	var page = Number(jQuery("span#pagenum").text()).valueOf() - 1;
	jQuery('span#pagenum').text(page)
	var limit = jQuery("select#limit option:selected").val();
	cpd_search_our_database();
}

function cpd_search_our_database_number_page(obj, e) {
	if(typeof e == 'undefined' && window.event) {
		e = window.event;
	}
	if(e.keyCode != 13) {
		return;
	}
	var page = Number(obj.value).valueOf();
	var pagecount = Number(jQuery("#pagecount").text()).valueOf();
	if(page > pagecount) {
		page = pagecount;
	}
	jQuery('span#pagenum').text(page)		
	var limit = jQuery("select#limit option:selected").val();
	cpd_search_our_database();
}

function cpd_search_our_database_next_page() {
	jQuery('.navbarnextpage').click(function() { return false; });
	var page = Number(jQuery("span#pagenum").text()).valueOf() + 1;
	jQuery('span#pagenum').text(page)
	var limit = jQuery("select#limit option:selected").val();
	cpd_search_our_database();
}

function cpd_search_our_database_per_page_changed(obj) {
	page = 1;
	jQuery('span#pagenum').text(page)
	var limit  = obj.value;
	jQuery('span#limit').text(limit);
	cpd_search_our_database();
}

function cpd_search_our_database_submit_form() {
	// Revert to first page
	jQuery('span#pagenum').html("1")
	
	// Run search
	cpd_search_our_database();
}

function cpd_search_our_database() {
	// Display 'searching...' dialog
	jQuery('#cpdsearching').show();
	//jQuery('#cpdsearching').dialog("open");
	
	// Determine start result number and page length
	var pagenum = Number(jQuery('span#pagenum').text()).valueOf();
	var limit = Number(jQuery('span#limit').text()).valueOf();
	var start = ((pagenum - 1) * limit) + 1;
	
	// Gather criteria from form widgets
	var sizefrom = jQuery('input#sizefrom').val();
	var sizeto = jQuery('input#sizeto').val();
	var sizeunits = jQuery('input#sizeunits').val();
	var sectors_options = jQuery("select#sectors option:selected");
	var sectors = [];
	for (var i = 0; i < sectors_options.length; i++) {
		sectors[i] = sectors_options[i].value;
	}
	var areas_options = jQuery("select#areas option:selected");
	var areas = [];
	for (var i = 0; i < areas_options.length; i++) {
		areas[i] = areas_options[i].value;
	}
	var tenure = jQuery('select#tenure option:selected').val();
	var address = jQuery('input#address').val();
	var postcode = jQuery('input#postcode').val();
	var postdata = {
		'action':'cpd_search_our_database',
		'start': start,
		'limit': limit,
		'sizefrom': sizefrom,
		'sizeto': sizeto,
		'sizeunits': sizeunits,
		'sectors': sectors,
		'areas': areas,
		'tenure': tenure,
		'address': address,
		'postcode': postcode,
	};
	
	// Send AJAX search request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_search_our_database_success,
		error: cpd_search_our_database_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
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

jQuery(document).ready(function() {
	// Activate submit button for search form
	jQuery("#submit").click(cpd_search_our_database_submit_form);
	
	// Clear 'loading...' dialog
	jQuery("#cpdloading").hide();

	// Start with initial page of results, if so configured
	var trigger = jQuery("span#trigger").html() == "yes";
	if(trigger) {
		return cpd_search_our_database();
	}

	// Display the search form
	jQuery("#cpdsearchform").show();
});
