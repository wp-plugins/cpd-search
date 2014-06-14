// Collection of globally accessible utility functions
CPD = {
	setCookie: function(name, value) {
		var date = new Date();
		date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
		document.cookie = name + "=" + value + expires + "; path=/";
	},

	getCookie: function(name) {
		if (document.cookie.length <= 0) {
			return "";
		}
		c_start = document.cookie.indexOf(name + "=");
		if (c_start < 0) {
			return "";
		}
		c_start = c_start + name.length + 1;
		c_end = document.cookie.indexOf(";", c_start);
		if (c_end == -1) {
			c_end = document.cookie.length;
		}
		return unescape(document.cookie.substring(c_start, c_end));
	}

}

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
