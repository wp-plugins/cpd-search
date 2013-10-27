// Main controller logic for initialising and handling the clipboard widget

function CPDClipboardWidget() {
	var self = this;
	
	self.clipboard = {};
	
	self.clickAdd = function(obj) {
		var propref = jQuery(this).parents('.result').attr('id').substr(8);
		self.add(propref);
	};
	
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
		
		jQuery(".clipboardtopcolumnright_sidebar .btn_close").click(self.remove);
	};
	
	self.addError = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdadding').fadeOut();
		
		// Show error message
		jQuery("#cpderror").html('<p class="error">Failed to add clipboard entry: ' + textStatus + '</p>');
		jQuery("#cpderror").dialog("open");
	};
	self.addSuccess = function(data) {
		jQuery('#cpdadding').fadeOut();
		
		self.clipboard = data.results;
		
		// Handle no results scenario
		jQuery("#form-sidebar .savedcount").text(data.total);
		if(data.total < 1) {
			alert("No results found.");
			return;
		}
		
		self.refreshClipboardView();
	}
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
		
		jQuery('#cpdadding').fadeIn();
	};

	self.removeError = function(data, status, error) {
		jQuery('#cpdremoving').fadeOut();
		
		jQuery("#cpderror").html('<p class="error">Failed to remove clipboard entry: ' + data.error + '</p>');
		jQuery("#cpderror").dialog("open");
	};
	self.removeSuccess = function(propref) {
		jQuery('#cpdremoving').fadeOut();
		
		jQuery("#clipboardholdingcontainer_sidebar #clipboard" + propref).remove();
		
		self.refreshClipboardView();
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
			success: self.removeSuccess,
			error: self.removeError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);

		jQuery('#cpdremoving').fadeIn();
	}

	self.pushpostError = function(data, status, error) {
		jQuery('#cpdremoving').fadeOut();
		
		jQuery("#cpderror").html('<p class="error">Failed to create post: ' + data.error + '</p>');
		jQuery("#cpderror").dialog("open");
	};
	self.pushpostSuccess = function(data) {
		if(!data) {
			return cpd_server_unavailable();
		}
		if (data.success != true) { 
			self.pushpostError("Post failed", "Post failed", null);
		}
		
		location.href = data.results['link'];
	};
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
			success: pushpostSuccess,
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

	self.init = function() {
		jQuery(".cbx_clipboard").live('click',self.select_obj);
		jQuery("#cbx_clipboard_all").click(self.select_all);
		jQuery("#clipboardpreviewrow").click(self.pushpost);
		jQuery(".clipboardtopcolumnright_sidebar .btn_close").click(self.remove);
		
		self.refreshClipboardView();
	};
};

cpdClipboardWidget = new CPDClipboardWidget();

jQuery(document).ready(function() {
	cpdClipboardWidget.init();
});

