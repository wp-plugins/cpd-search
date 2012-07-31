// Main controller logic for initialising and handling the clipboard widget

function CPDClipboardWidget() {
	var self = this;
	
	self.clipboard = {};
	
	self.clickAdd = function(obj) {
		var propref = jQuery(this).parents('.result').attr('id').substr(8);
		self.add(propref);
	};
	
	self.addSuccess = function(data) {
		if(!data.success) {
			return self.addError(null, data.error, data.error);
		}
		self.clipboard = data.results;
		
		// Handle no results scenario
		jQuery("#form-sidebar .savedcount").text(data.total);
		if(data.total < 1) {
			alert("No results found.");
			return;
		}
		
		self.refreshClipboardView();		
	}
	
	self.refreshClipboardView = function() {
		// Loop for each result adding to the table
		jQuery("#contentbox").empty();
		for (i in self.clipboard) {
			var property = self.clipboard[i];
			var propref = property.PropertyID.toString();
			var id = "clipboard" + propref;
			
			var row = jQuery("#clipboardwidgetmodelrow").clone();
			row.attr("id", id);
			jQuery(".name", row).html(property.Address);
			jQuery(".postcode", row).html(property.Postcode);
			jQuery(".location", row).html(property.Location);
			jQuery(".tenure", row).html(property.TenureDescription);
			jQuery(".size", row).html(property.SizeDescription);
			
			jQuery("#contentbox").append(row.show());
		}
		
		jQuery('#cpdsearching').hide();
		jQuery(".clipboardtopcolumnright_sidebar .btn_close").click(self.remove);
		self.hide_show();
	};
	self.addError = function(data, status, error) {
		// Show error message
		jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
		jQuery("#cpderror").dialog("open");
		
		jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
		jQuery('#cpdsearching').hide();
	};
	self.add = function(propref) {
		var postdata = {
			'action':'cpd_clipboard_widget_add_ajax',
			'propref': propref
		};
		
		// Send AJAX search request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.addSuccess,
			error: self.addError,
			dataType: "json"
		};
		
		jQuery.ajax(ajaxopts);
		
		jQuery('#cpdsearching span').html("Adding to clipboard... Please wait a moment.");
		jQuery('#cpdsearching').show();
	};

	self.removeSuccess = function(propref) {
		jQuery("#clipboardholdingcontainer_sidebar #clipboard" + propref).remove();
		jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
		jQuery('#cpdsearching').hide();
		self.refreshClipboardView();	
		self.hide_show();		
	};
	self.removeError = function(data) {
		jQuery("#cpderror").html("<p class='error'>Search failed! " + data.error + ")</p>");
		jQuery("#cpderror").dialog("open");
		jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
		jQuery('#cpdsearching').hide();
	};
	self.remove = function(obj) {		
		var propref = jQuery(this).parents('table').attr('id').substr(9);
		var postdata = {
			'action':'cpd_clipboard_widget_delete_ajax',
			'propref': propref
		};
		
		// Send AJAX search request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.removeSuccess(propref),
			error: self.removeError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);

		jQuery('#cpdsearching span').html("Deleting item from clipboard... Please wait a moment.");
		jQuery('#cpdsearching').show();
	}

	self.pushpost = function() {
		jQuery('#cpdsearching span').html("Posting clipboard contents... Please wait a moment.");
		jQuery('#cpdsearching').show();
		
		var input = [];
		var id = [];
		var input = jQuery('input:checkbox[name="cbx_clipboard[]"]:checked');
		for (var i = 0; i< input.length;i++){
			 id[i] = jQuery(input[i]).parents('table').attr('id').substr(9);	
		}
		if(id.length == 0)
		{
			alert("Please select an item to post");
			jQuery('#cpdsearching').hide();
			return false;
		}
		
		var postdata = {
			'action':'cpd_clipboard_widget_pushpost_ajax',
			'id':id,
		};
		var ajaxopts = {
			url : CPDAjax.ajaxurl,
			type: 'POST',
			data: postdata,		
			success: function(data){
				if (data.success==true){ 
					var link = data.results['link'];
					location.href = link;
				}else
					alert(data.error);
			},
			error: function(data){
				alert(data.error);
			},
			dataType:"json"
		};
		jQuery.ajax(ajaxopts);
	
		return false;
	};

	self.select_all = function() {
		if(jQuery(this).is(':checked')) {
			jQuery("#contentbox #cbx_clipboard").attr('checked','checked');
		}
		else {
			jQuery("#contentbox #cbx_clipboard").removeAttr('checked');
		}
	};

	self.select_obj = function() {
		var i = 0;
		var j = 0;
		
		jQuery("#contentbox #cbx_clipboard").each(function() {
			if(jQuery(this).is(':checked')) {
				j++;
			}
			i++;
		});
		
		if(i == j) {
			jQuery("#cbx_clipboard_all").attr('checked','checked');
		}
		else {
			jQuery("#cbx_clipboard_all").removeAttr('checked');
		}
	};

	self.hide_show = function() {
		if(jQuery("#cpdclipboard_sidebar #contentbox table").length > 0) {
			jQuery("#cpdclipboard_sidebar #contentbox table").show();
			jQuery("#cpdclipboard_sidebar").show();
			return;
		}
		
		if(jQuery("#cpdsearchnavigationmodel").length == 0) {
			jQuery("#cpdclipboard_sidebar").hide();
			return;
		}
		
		if(jQuery("#cpdsearchnavigationmodel").is(':hidden')) {
			jQuery("#cpdclipboard_sidebar").hide();
			return;
		}

		if(jQuery("#cpdclipboard_sidebar #contentbox table").length == 0) {
			jQuery("#cpdclipboard_sidebar").hide();
			return;
		}
		
		jQuery("#cpdclipboard_sidebar #contentbox table").show();
		jQuery("#cpdclipboard_sidebar").show();
	};
	
	self.init = function() {
		self.hide_show();
		
		jQuery(".cbx_clipboard").live('click',self.select_obj);
		
		jQuery("#cbx_clipboard_all").click(self.select_all);
		
		jQuery("#clipboardpreviewrow").click(self.pushpost);
		
		jQuery(".clipboardtopcolumnright_sidebar .btn_close").click(self.remove);
	};
};

cpdClipboardWidget = new CPDClipboardWidget();

jQuery(document).ready(function() {
	cpdClipboardWidget.init();
});

