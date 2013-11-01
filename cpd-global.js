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

function fullAddress(property) {
	var address = property.address;
	if(property.buildingnum && property.buildingnum.length > 0) {
		address = property.buildingnum + ' ' + address;
	}
	if(property.buildingname && property.buildingname.length > 0) {
		address = property.buildingname + ' ' + address;
	}
	return address;
}

function tenureDescription(tenure) {
	if(tenure == 'L') {
		return 'Leasehold';
	}
	else if(tenure == 'F') {
		return 'Freehold';
	}
	else {
		return 'Leasehold/Freehold';
	}
}

function sizeDescription(property) {
	var sizefrom = property.sizefrom;
	var sizeto = property.sizeto;
	var sizeunit = property.sizeunit == 1 ? 'sq ft' : 'sq m';
	if(sizefrom == sizeto) {
		return sizefrom + " " + sizeunit;
	}
	else {
		return sizefrom + " to " + sizeto + " " + sizeunit;
	}
}

function _mediaFolder(media) {
	initial = media.uuid.substring(0,1);
	four = media.uuid.substring(0,4);
	return "https://s3.amazonaws.com/cpd-media-live-" + initial + "/" + four + "/" + media.uuid;
}
function mediaDownloadURL(media) {
	return _mediaFolder(media) + "/original/" + media.filename;
}
function mediaSmallThumb(media) {
	return _mediaFolder(media) + "/thumb.jpg";
}
function mediaMediumThumb(media) {
	return _mediaFolder(media) + "/medium.jpg";
}

