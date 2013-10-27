// Main controller logic for initialising and handling the search form activity

function CPDSearchOurDatabase() {
	var self = new CPDCommonSearchController();

	self.searchError = function(data, status, error) {
		// Clear loading dialog
		jQuery("#cpdloading").hide();
		
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
		
		// Start back at the search form
		jQuery("#cpdsearchform").show();
	};
	
	self.searchSuccess = function(data) {
		// Clear loading dialog
		jQuery("#cpdloading").hide();
		
		// Remember search id
		self.search_id = data.id;
		
		// Kick off first page
		self.searchPage();
	};
	
	self.searchPageError = function(data, status, error) {
		// Clear loading dialog
		jQuery("#cpdloading").hide();
		
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
		
		// Start back at the search form
		jQuery("#cpdsearchform").show();
	};
	
	self.searchPageSuccess = function(data) {
		// Clear loading dialog, form and results panel
		jQuery('#cpdloading').hide();
		jQuery("#cpdsearchform").hide();
		jQuery('#cpdsearchresults').empty();
		jQuery("#cpderror").dialog("close");
		
		// Handle no results scenario
		self.count = data.count;
		if(data.count < 1) {
			return self.searchError(null, "No results found.", "No properties currently found with this criteria.");
		}
		
		// Count pages
		var start = ((self.page - 1) * self.limit) + 1;
		var pagecount = Math.floor((data.count - 1) / self.limit) + 1;
		
		// Determine sector(s) selected
		var sectors_json = jQuery('span#sectors').html();
		var sectors = jQuery.parseJSON(sectors_json);
		
		// Loop for each result adding to the table
		var resultnum = ((self.page - 1) * self.limit) + 1;
		for (var i in data.results) {
			var property = data.results[i];
			var id = "property" + property.propref;
			
			// Clone a result model for this result
			var row = jQuery("#cpdsearchresultmodel").clone();
			self.populateRow(row, property)
			
			// Add to the results table
			jQuery(".resultnum", row).html(resultnum++);
			jQuery('#cpdsearchresults').append(row.show());
		}
		
		// Add navigationbars
		self.addNavigation(data.count);
	};

	self.submitForm = function() {
		// Revert to first page
		self.page = 1;
		self.searchDatabase();
	};

	self.searchDatabase = function() {
		// Display 'searching...' dialog
		jQuery('#cpdloading').show();
	
		// Gather criteria from form widgets
		var sizefrom = jQuery('input#sizefrom').val();
		var sizeto = jQuery('input#sizeto').val();
		var sizeunits = jQuery('select#sizeunits').val();
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
			'page': self.page,
			'limit': self.limit,
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
	
	self.searchPage = function() {
		// Display 'searching...' dialog
		jQuery('#cpdloading').show();
	
		// Send AJAX search request to server
		var params = {
			'action': 'cpd_search_our_database_page',
			'search_id': self.search_id,
			'page': self.page,
			'limit': self.limit
		};
		var ajaxopts = {
			type: 'GET',
			url: CPDAjax.ajaxurl,
			data: params,
			success: self.searchPageSuccess,
			error: self.searchPageError,
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

