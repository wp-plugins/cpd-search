CPDShortlistWidget = function() {
	var self = this;
	
	self.noPropsMessage = "No properties shortlisted.";
	
	self.init = function() {
		self.w = jQuery(".cpdshortlist");
		self.refresh();
	};
	
	self.refresh = function() {
		if(!CPDShortlist || CPDShortlist.length < 1) {
			self.w.html(self.noPropsMessage);
		}
		else {
			self.refreshList();
		}
		
		// Allow caller to update their bits
		if(self.refreshCallback) {
			self.refreshCallback();
		}
	};
	self.refreshList = function() {
		self.w.empty();
		for(var i in CPDShortlist) {
			var entry = CPDShortlist[i];
			var row = self.addRow(entry);
			self.w.append(row.show());
		}
	};
	self.addRow = function(entry) {
		var modelrow = jQuery("#cpdshortlistentrymodel").clone();
		modelrow.attr('id', 'shortlist-' + entry.propertyid);
		jQuery(".propref", modelrow).html(entry.propertyid);
		jQuery(".address", modelrow).html(entry.address);
		return modelrow;
	};

	self.add = function(propref) {
		return cpdSearch.addToShortlist(propref, self.addSuccess, self.addError);
	};
	self.addSuccess = function(data) {
		CPDShortlist = data;
		self.refresh();
	};
	self.addError = function(jqXHR, textStatus, errorThrown) {
		alert(textStatus);
	};
	
	self.remove = function(propref) {
		return cpdSearch.removeFromShortlist(propref, self.removeSuccess, self.removeError);
	};
	self.removeSuccess = function(data) {
		CPDShortlist = data;
		self.refresh();
	};
	self.removeError = function(jqXHR, textStatus, errorThrown) {
		alert(textStatus);
	};
	
	return self;
};

