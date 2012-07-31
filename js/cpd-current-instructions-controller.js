// Main controller logic for initialising and handling the search form activity

function CPDCurrentInstructions() {
	self = CPDCommonSearchController();

	self.searchError = function(xhr, status, error) {
		// Put an error message in the results dialog
		jQuery("#cpdsearcherror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpdsearcherror").show();
		
		// Clear 'searching...' dialog
		jQuery("#cpdsearching").hide();
		jQuery('#cpdsearchform').show();
	};

	self.searchSuccess = function(data) {
		// Check for failure
		if(!data.success) {
			return self.searchError(null, data.error, data.error);
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
		jQuery("select.limit option").each(function() { 
			jQuery(this).removeAttr('selected');
			if(Number(this.text).valueOf() == limit)
				jQuery(this).attr('selected','selected');
		});
		var start = ((pagenum - 1) * limit) + 1;
		var pagecount = Math.floor((data.total - 1) / limit) + 1;

		// Loop for each result adding to the tableropref
		var resultnum = start;
		for (i in data.results) {
			var property = data.results[i];
		
			// Clone a result model for this result
			var row = jQuery("#cpdsearchresultmodel").clone();
			self.populateRow(row, property)
			
			// Add to the results table
			jQuery('#cpdsearchresults').append(row.show());
			jQuery(".resultnum", row).html(resultnum++);
		}
		
		// Add navigationbars
		self.addNavigation(pagenum, pagecount, data.total);
		jQuery("select.limit").change(self.per_page_changed);
		jQuery(".navbarprevpage").click(self.prev_page);
		jQuery(".navbarnextpage").click(self.next_page);
		
		// Clear loading dialog and hide form
		jQuery('#cpdsearching').hide();
		jQuery('#cpdsearchform').show();
	};

	self.update_hash = function() {
		var type = jQuery("select.sectors option:selected").val();
		var page = Number(jQuery("span#pagenum").text()).valueOf();
		var limit = jQuery("select.limit option:selected").val();
		window.location.hash = "sector=" + type + "&page=" + page + "&limit=" + limit;
	};

	self.sector_changed = function() {
		var type = jQuery("select.sectors option:selected").val();

		jQuery('#cpdsearching').show();
		self.update_hash();
		self.search_database();
	};

	self.prev_page = function() {
		jQuery('.navbarprevpage').click(function() { return false; });

		var page = Number(jQuery("span#pagenum").text()).valueOf() - 1;
		jQuery('span#pagenum').text(page);

		jQuery('#cpdsearching').show();
		self.search_database();
	};

	self.next_page = function() {
		jQuery('.navbarnextpage').click(function() { return false; });

		var page = Number(jQuery("span#pagenum").text()).valueOf() + 1;
		jQuery('span#pagenum').text(page)

		jQuery('#cpdsearching').show();
		self.search_database();
	};

	self.per_page_changed = function(data) {
		var limit = jQuery("select.limit option:selected").val();
		jQuery('span#limit').text(limit)
		jQuery('span#pagenum').text("1")
		jQuery('#cpdsearching').show();
		self.search_database();
	};

	self.search_database = function() {
		// Determine start result number and page length
		var pagenum = Math.floor(jQuery('span#pagenum').html());
		var limit = Math.floor(jQuery("span#limit").html());
		var start = ((pagenum - 1) * limit) + 1;
	
		// Gather criteria from form widgets
		var sectors = jQuery("select.sectors option:selected").val();
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
			success: self.searchSuccess,
			error: self.searchError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
	};

	self.getURLParameter = function(name) {
		return decodeURI(
			(RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
		);
	};
	
	self.init = function() {
		// Show the search form
		jQuery("#cpdsearchform").show();
		
		// Hide the loading dialog
		jQuery("#cpdloading").hide();

		// Hook up inputs
		jQuery("select.sectors").change(self.sector_changed);
		
		// Perform initial search
		cpdCurrentInstructions.search_database();
	};
	
	return self;
}

cpdCurrentInstructions = new CPDCurrentInstructions();

jQuery(document).ready(function() {
	cpdCurrentInstructions.init();
});
