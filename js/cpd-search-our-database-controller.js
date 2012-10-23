// Main controller logic for initialising and handling the search form activity

function CPDSearchOurDatabase() {
	var self = new CPDCommonSearchController();

	self.searchError = function(data, status, error) {
		// Clear other dialogs
		jQuery("#cpdloading").hide();
		
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
		
		// Start back at the search form
		jQuery("#cpdsearchform").show();
	};
	
	self.searchSuccess = function(data) {
		// Check for failure
		if(!data) {
			return self.searchError(null, "Connection failed", "Server down. Please try again later");
		}
		if(!data.success) {
			return self.searchError(null, data.error, data.error);
		}
		
		// Clear loading dialog, form and results panel
		jQuery('#cpdloading').hide();
		jQuery("#cpdsearchform").hide();
		jQuery('#cpdsearchresults').empty();
		jQuery("#cpderror").dialog("close");
		
		// Handle no results scenario
		if(data.total < 1) {
			return self.searchError(null, "No results found.", "No properties currently found with this criteria.");
		}
		
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
	};

	self.prev_page = function() {
		jQuery('.navbarprevpage').click(false);
		var page = Number(jQuery("span#pagenum").text()).valueOf() - 1;
		jQuery('span#pagenum').text(page)
		var limit = jQuery("select.limit option:selected").val();
		self.searchDatabase();
	};

	self.next_page = function() {
		jQuery('.navbarnextpage').click(false);
		var page = Number(jQuery("span#pagenum").text()).valueOf() + 1;
		jQuery('span#pagenum').text(page)
		var limit = jQuery("select.limit option:selected").val();
		self.searchDatabase();
	};
	
	self.per_page_changed = function(data) {
		var limit = jQuery("select.limit option:selected").val();
		jQuery('span#limit').text(limit)
		jQuery('span#pagenum').text("1")
		self.searchDatabase();
	}

	self.submitForm = function() {
		// Revert to first page
		jQuery('span#pagenum').html("1")
	
		// Run search
		self.searchDatabase();
	};

	self.searchDatabase = function() {
		// Display 'searching...' dialog
		jQuery('#cpdloading').show();
	
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
		// Clear 'loading...' dialog
		jQuery("#cpdloading").hide();

		// Activate submit button for search form
		jQuery("#cpdsearchform #submit").click(self.submitForm);

		// Display the search form
		jQuery("#cpdsearchform").show();

		// Determine whether user is registered or not
		CPD.userRegistered = jQuery("span#registered").html() == '1';

		var search_widget = Number(jQuery('span#search_widget').text()).valueOf();
		if(search_widget) {
			jQuery("#cpdsearchform").hide();
			jQuery("#cpdsearchform #submit").click();
		}
	};
	
	return self;
}

cpdSearchOurDatabase = new CPDSearchOurDatabase();

