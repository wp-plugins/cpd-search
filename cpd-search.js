CPDSearch = function() {
	var self = this;
	
	self.search = function(criteria, success_cb, failure_cb) {
		// Send AJAX request to handler
		var postdata = {
			'action': 'cpd_search',
			'criteria': criteria,
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDSearchConfig.ajaxurl,
			data: postdata,
			success: success_cb,
			error: failure_cb,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		return false;
	};
	
	self.addToClipboard = function(property_id, success_cb, failure_cb) {
		// Send AJAX request to handler
		var postdata = {
			'action': 'cpd_add_to_clipboard',
			'property_id': property_id,
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDSearchConfig.ajaxurl,
			data: postdata,
			success: success_cb,
			error: failure_cb,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		return false;
	};
	
	self.removeFromClipboard = function(property_id, success_cb, failure_cb) {
		// Send AJAX request to handler
		var postdata = {
			'action': 'cpd_remove_from_clipboard',
			'property_id': property_id,
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDSearchConfig.ajaxurl,
			data: postdata,
			success: success_cb,
			error: failure_cb,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		return false;
	};
	
	self.addToShortlist = function(property_id, success_cb, error_cb) {
		var ajaxopts = {
			type: 'GET',
			url: CPDSearchConfig.ajaxurl,
			data: {
				'action': 'cpd_add_to_shortlist',
				'property_id': property_id
			},
			success: success_cb,
			error: error_cb,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		return false;
	};
	
	self.removeFromShortlist = function(property_id, success_cb, error_cb) {
		var ajaxopts = {
			type: 'GET',
			url: CPDSearchConfig.ajaxurl,
			data: {
				'action': 'cpd_remove_from_shortlist',
				'property_id': property_id
			},
			success: success_cb,
			error: error_cb,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		return false;
	};
	
	self.registerInterest = function(id, success_cb, failure_cb) {
		// Send AJAX request to handler
		var postdata = {
			'action': 'cpd_register_interest',
			'property_id': id,
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDSearchConfig.ajaxurl,
			data: postdata,
			success: success_cb,
			error: failure_cb,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);
		return false;
	};
	
	return self;
}

var cpdSearch;

jQuery().ready(function() {
	if(cpdSearch == null) {
		cpdSearch = new CPDSearch();
		//cpdSearch.init();
	}
});

