// Shared between SOD and CI controllers

function CPDCommonSearchController() {
	var self = this;

	self.page = 1;
	self.limit = 25;
	
	self.populateRow = function(row, property) {
		var propref = property.propref.toString();
		var id = "property" + propref;
		row.attr("id", id);
		
		// Populate it
		jQuery(".propref", row).html(propref);
		jQuery(".typedesc", row).html(property.sector.name);
		jQuery(".sizedesc", row).html(sizeDescription(property));
		jQuery(".postcode", row).html("N/A");
		jQuery(".areadesc", row).html("N/A");
		if(property.postcode) {
			jQuery(".postcode", row).html(property.postcode.postcode);
			if(property.postcode.cpd_area) {
				jQuery(".areadesc", row).html(property.postcode.cpd_area.name);
			}
		}
		jQuery(".tenuredesc", row).html(tenureDescription(property.tenure));
		jQuery(".address", row).html(property.address);
		jQuery(".summary", row).html(property.brief);
		jQuery(".epc", row).html(property.epc ? property.epc : "-");
		
		jQuery(".photo", row).html("(No photo)");
		for(var i in property.medialinks) {
			var medialink = property.medialinks[i];
			if(medialink.type != 'photo') {
				continue;
			}
			jQuery(".photo", row).html("<img src=\"" + mediaSmallThumb(medialink.media) + "\"/>");
		}
		
		jQuery(".buttonpdf", row).hide();
		for(var i in property.medialinks) {
			var medialink = property.medialinks[i];
			if(medialink.type != 'pdf') {
				continue;
			}
			var buttonpdf = jQuery(".buttonpdf", row);
			buttonpdf.attr('id', 'medialink' + medialink.id);
			buttonpdf.show();
			buttonpdf.click(cpdViewPropertyPDF.click);
		}
		
		// Hook up buttonsidebar
		jQuery(".clipboardadd", row).click(cpdClipboardWidget.clickAdd);
		jQuery(".registerinterest", row).click(cpdRegisterInterest.clickRegisterInterest);
		cpdRegisterInterest.update_buttons(id, propref);
	};
	
	self.addNavigation = function(total) {
		var navbar = jQuery('#cpdsearchnavigationmodel');
		jQuery('.navbarresultcount', navbar).html(total);
		jQuery('.navbarpagenum', navbar).html(self.page);

		var pagecount = Math.floor((total - 1) / self.limit) + 1;
		jQuery('.navbarpagecount', navbar).html(pagecount);
		jQuery("select.limit").val(self.limit);

		if(pagecount > 1 && self.page > 1) {
			jQuery('.navbarprevpage', navbar).show();
		}
		else {
			jQuery('.navbarprevpage', navbar).hide();
		}
		if(pagecount > 1 && self.page < pagecount) {
			jQuery('.navbarnextpage', navbar).show();
		}
		else {
			jQuery('.navbarnextpage', navbar).hide();
		}
		
		jQuery('#cpdsearchresults').prepend(navbar.clone().show());
		jQuery('#cpdsearchresults').append(navbar.clone().show());
		
		jQuery('.navbarprevpage').click(self.prev_page);
		jQuery('.navbarnextpage').click(self.next_page);
		jQuery("select.limit").change(self.per_page_changed);
	};

	self.prev_page = function() {
		//jQuery('.navbarprevpage').click(false);
		self.page = Math.max(self.page - 1, 1);
		self.searchPage();
	};

	self.next_page = function() {
		//jQuery('.navbarnextpage').click(false);
		var maxpages = Math.floor((self.count - 1) / self.limit) + 1;
		self.page = Math.min(self.page + 1, maxpages);
		self.searchPage();
	};
	
	self.per_page_changed = function(data) {
		self.page = 1;
		self.limit = parseInt(jQuery("select.limit option:selected").val());
		self.searchPage();
	};

	return self;
}

