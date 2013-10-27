// Main controller logic for initialising and handling the search form activity

function CPDCurrentInstructions() {
	var self = CPDCommonSearchController();

	self.searchError = function(xhr, status, error) {
		// Clear 'searching...' dialog
		jQuery("#cpdsearching").hide();
		
		// Put an error message in the results dialog
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
	};

	self.searchSuccess = function(data) {
		// Clear 'searching...' dialog
		jQuery("#cpdsearching").hide();
		
		// Remember search id
		self.search_id = data.id;
		
		// Kick off first page
		self.searchPage();
	};
	
	self.searchPageError = function(xhr, status, error) {
		// Clear 'searching...' dialog
		jQuery("#cpdsearching").hide();
		
		// Put an error message in the results dialog
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
	};

	self.searchPageSuccess = function(data) {
		// Clear loading dialog and results panel
		jQuery('#cpdsearching').hide();
		jQuery('#cpdsearchresults').empty();
		
		// Handle no results scenario
		self.count = data.count;
		if(data.count < 1) {
			return self.searchError(null, "No results found.", "No properties currently available in this sector.");
		}
		
		// Count pages
		var start = ((self.page - 1) * self.limit) + 1;
		var pagecount = Math.floor((data.count - 1) / self.limit) + 1;

		// Loop for each result adding to the table
		var resultnum = ((self.page - 1) * self.limit) + 1;
		for (var i in data.results) {
			var property = data.results[i];
			var id = "property" + property.propref;
			
			// Clone a result model for this result
			var row = jQuery("#cpdsearchresultmodel").clone();
			self.populateRow(row, property);
			
			// Add to the results table
			jQuery(".resultnum", row).html(resultnum++);
			jQuery('#cpdsearchresults').append(row.show());
		}
		
		// Add navigationbars
		self.addNavigation(data.count);
	};

	self.sector_changed = function() {
		self.type = jQuery("select.sectors option:selected").val();
		self.page = 1;
		self.searchDatabase();
	};

	self.searchDatabase = function() {
		// Update hash
		//window.location.hash = "sector=" + self.type + "&page=" + self.page + "&limit=" + self.limit;

		// Gather criteria from form widgets
		var sectors = jQuery("select.sectors option:selected").val();
		var postdata = {
			'action':'cpd_current_instructions',
			'page': self.page,
			'limit': self.limit,
			'sectors': sectors,
		};
	
		// Display 'loading...' dialog
		jQuery('#cpdsearching').show();
	
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
		jQuery('#cpdsearching').show();
	
		// Send AJAX search request to server
		var params = {
			'action': 'cpd_current_instructions_page',
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
		// Show the search form
		jQuery("#cpdsearchform").show();

		// Hide the loading dialog
		jQuery("#cpdloading").hide();

		// Hook up inputs that change the sector/page
		jQuery("select.sectors").change(self.sector_changed);

		// Determine whether user is registered or not
		CPD.userRegistered = jQuery("span#registered").html() == '1';

		// Load the initial results view
		self.searchDatabase();
	};
	
	return self;
}

cpdCurrentInstructions = new CPDCurrentInstructions();

