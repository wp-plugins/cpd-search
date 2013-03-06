// Accessible by all CPD javascript controllers

function CPD() {
	var self = this;
	
	self.userRegistered = false;
	
	return self;
}

CPD = new CPD();

// Workaround for Array.indexOf in MSIE <= 8
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(elt /*, from*/) {
		var len = this.length >>> 0;

		var from = Number(arguments[1]) || 0;
		from = (from < 0)
				 ? Math.ceil(from)
				 : Math.floor(from);
		if (from < 0)
			from += len;

		for (; from < len; from++) {
			if (from in this && this[from] === elt)
				return from;
		}
		return -1;
	};
}
