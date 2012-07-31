// Main controller logic for initialising and handling the search form activity

function CPDSearchOurDatabase() {
	self = new CPDCommonSearchController();

	self.searchSuccess = function(data) {
		// Check for failure
		if(!data.success) {
			return self.search_error(null, data.error, data.error);
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
		
		// Count pages
		var pagenum = Number(jQuery('span#pagenum').text()).valueOf();
		if(isNaN(pagenum)) {
			pagenum = 1;
		}
		var limit = Number(jQuery('span#limit').text()).valueOf();
		if(isNaN(limit)) {
			limit = 20;
		}
		jQuery("select.limit").val(limit);
		jQuery("select.limit option").each(function() { 
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
			var row = jQuery("#cpdsearchresultmodel").clone();
			self.populateRow(row, property)
			
			// Add to the results table
			jQuery(".resultnum", row).html(resultnum++);
			jQuery('#cpdsearchresults').append(row.show());
		}
		
		// Add navigationbars
		self.addNavigation(pagenum, pagecount, data.total);
		jQuery("select.limit").change(self.per_page_changed);
		jQuery(".navbarprevpage").click(self.prev_page);
		jQuery(".navbarnextpage").click(self.next_page);
		
		jQuery("select.limit").change(self.per_page_changed);
		
		// Clear loading dialog and hide form
		cpdClipboardWidget.hide_show();
		cpdSavedSearchesWidget.hide_show();
		jQuery('#cpdsearching').hide();
		jQuery('#cpdsearchform').hide();
	};
	self.searchError = function(data, status, error) {
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
		
		// Clear other dialogs
		jQuery("#cpdsearching").hide();
		jQuery("#cpdloading").hide();
		
		// Start back at the search form
		jQuery("#cpdsearchform").show();
	};

	self.prev_page = function() {
		jQuery('.navbarprevpage').click(false);
		var page = Number(jQuery("span#pagenum").text()).valueOf() - 1;
		jQuery('span#pagenum').text(page)
		var limit = jQuery("select.limit option:selected").val();
		self.search_database();
	};

	self.next_page = function() {
		jQuery('.navbarnextpage').click(false);
		var page = Number(jQuery("span#pagenum").text()).valueOf() + 1;
		jQuery('span#pagenum').text(page)
		var limit = jQuery("select.limit option:selected").val();
		self.search_database();
	};
	
	self.per_page_changed = function(data) {
		var limit = jQuery("select.limit option:selected").val();
		jQuery('span#limit').text(limit)
		jQuery('span#pagenum').text("1")
		self.search_database();
	}

	self.submit_form = function() {
		// Revert to first page
		jQuery('span#pagenum').html("1")
	
		// Run search
		self.search_database();
	};

	self.search_database = function() {
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
			success: self.searchSuccess,
			error: self.searchError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};
	
	self.init = function() {
		// Activate submit button for search form
		jQuery("#submit").click(self.submit_form);
		
		// Clear 'loading...' dialog
		jQuery("#cpdloading").hide();

		// Start with initial page of results, if so configured
		var trigger = jQuery("span#trigger").html() == "yes";
		if(trigger) {
			return self.search_database();
		}
		
		// Display the search form
		jQuery("#cpdsearchform").show();
	};
	
	return self;
}

cpdSearchOurDatabase = new CPDSearchOurDatabase();

jQuery(document).ready(function() {
	cpdSearchOurDatabase.init();
});

