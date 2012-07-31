// Shared between SOD and CI controllers

function CPDCommonSearchController() {
	var self = this;
	
	self.populateRow = function(row, property) {
		var propref = property.PropertyID.toString();
		var id = "property" + propref;
		row.attr("id", id);
		
		// Populate it
		jQuery(".propref", row).html(propref);
		jQuery(".typedesc", row).html(property.SectorDescription);
		jQuery(".sizedesc", row).html(property.SizeDescription);
		jQuery(".areadesc", row).html(property.RegionName);
		jQuery(".tenuredesc", row).html(property.TenureDescription);
		jQuery(".address", row).html(property.Address);
		jQuery(".summary", row).html(property.BriefSummary);
		if(property.ThumbURL === undefined) {
			jQuery(".photo", row).html("(No photo)");
		}
		else {
			jQuery(".photo", row).html("<img src=\"" + property.ThumbURL + "\"/>");
			jQuery(".photo", row).after("<a id=\"photolink\"></a>");
			jQuery(".photo", row).appendTo("#" + id + " #photolink");
			jQuery(".photo", row).click(function() {
				var propref = this.attributes.getNamedItem("propref").nodeValue;
				self.viewPropertyImage(id, propref);
			});
		}
		
		if(property.PDFMediaID !== undefined) {
			jQuery(".buttonpdf", row).show();
			jQuery(".buttonpdf", row).attr("mediaid", property.PDFMediaID);
			jQuery(".buttonpdf", row).click(function() {
				var media_id = this.attributes.getNamedItem("mediaid").nodeValue;
				cpd_view_property_pdf(media_id);
			});
		}
		
		// Hook up buttonsidebar
		jQuery(".clipboardadd", row).click(cpdClipboardWidget.clickAdd);
		jQuery(".registerinterest", row).click(cpdRegisterInterest.clickRegisterInterest);
		cpdRegisterInterest.update_buttons(id, propref);
	};
	
	self.viewPropertyImageSuccess = function(data) {
		id = "property" + data.id;
		image_url = data.image_url;
		jQuery('#' + id + ' #photolink').attr('href', image_url);
		jQuery('#' + id + ' #photolink').lightBox();
	};
	self.viewPropertyImageError = function(data) {
		alert("Unable to load image for property.");
	};
	self.viewPropertyImage = function(id, propref) {
		// Go ask for the full image URL
		var postdata = {
			'action':'cpd_viewPropertyImage',
			'propref': propref,
		};
	
		// Display 'loading...' dialog
		jQuery('#cpdsearching').show();
	
		// Send AJAX search request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.viewPropertyImageSuccess,
			error: self.viewPropertyImageError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};

	self.addNavigation = function(pagenum, pagecount, total) {
		var navbar = jQuery('#cpdsearchnavigationmodel');
		jQuery('.navbarresultcount', navbar).html(total);
		jQuery('.navbarpagenum', navbar).html(pagenum);
		jQuery('.navbarpagecount', navbar).html(pagecount);
		if(pagecount > 1 && pagenum > 1) {
			jQuery('.navbarprevpage', navbar).show();
		}
		else {
			jQuery('.navbarprevpage', navbar).hide();
		}
		if(pagecount > 1 && pagenum < pagecount) {
			jQuery('.navbarnextpage', navbar).show();
		}
		else {
			jQuery('.navbarnextpage', navbar).hide();
		}
		jQuery('#cpdsearchresults').prepend(navbar.clone().show());
		jQuery('#cpdsearchresults').append(navbar.clone().show());
	};

	return self;
}

