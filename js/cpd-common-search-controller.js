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
		jQuery(".epc", row).html(property.EPC ? property.EPC : "-");
		if(property.ImageThumbURL === undefined) {
			jQuery(".photo", row).html("(No photo)");
		}
		else {
			jQuery(".photo", row).html("<img src=\"" + property.ImageThumbURL + "\"/>");
		}
		
		if(property.PDFMediaID === undefined) {
			jQuery(".buttonpdf", row).hide();
		}
		else {
			jQuery(".buttonpdf", row).show();
		}
		
		// Hook up buttonsidebar
		jQuery(".clipboardadd", row).click(cpdClipboardWidget.clickAdd);
		jQuery(".registerinterest", row).click(cpdRegisterInterest.clickRegisterInterest);
		cpdRegisterInterest.update_buttons(id, propref);
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

