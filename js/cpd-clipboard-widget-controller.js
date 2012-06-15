// Main controller logic for initialising and handling the search form activity


jQuery(document).ready(function() {
	cpd_clipboard_widget_check_show_scroll();
	cpd_clipboard_widget_hide_show();
});


function cpd_clipboard_widget_hide_show()
{
	if(jQuery("#cpdsearchnavigationmodel").length == 0)
	{
		jQuery("#clipboard").hide();
		return;
	}
	if(jQuery("#cpdsearchnavigationmodel").is(':hidden'))
	{
		jQuery("#clipboard").hide();
	}
	else
	{
		jQuery("#clipboard").show();
	}
}

function cpd_clipboard_widget_sellect_all(obj)
{	
	if(jQuery(obj).is(':checked'))
	{
		jQuery("#clipboard_list li #cbx_clipboard").attr('checked','checked');
	}
	else
	{
		jQuery("#clipboard_list li #cbx_clipboard").removeAttr('checked');
	}
}

function cpd_clipboard_widget_sellect_obj(obj)
{	
	var i = 0;
	var j = 0;
	jQuery("#clipboard_list li #cbx_clipboard").each(function(){
		if(jQuery(this).is(':checked'))
		{
			j++;
		}
		i++;
	});
	
	if(i == j)
	{
		jQuery("#cbx_clipboard_all").attr('checked','checked');
	}
	else
	{
		jQuery("#cbx_clipboard_all").removeAttr('checked');
	}
}

function cpd_clipboard_widget_success(data)
{
	if(!data.success) {
		return cpd_clipboard_widget_error(null, data.error, data.error);
	}

	// Handle no results scenario
	if(data.total < 1) {
		alert("No results found.");
		return;
	}
	
		// Loop for each result adding to the table
		
	for (i in data.results) {
		
		var property = data.results[i];
		var propref = property.PropertyID.toString();
		var id = "property" + propref;
		
		var size = property.SizeDescription;
		var name = property.Address;
		var PropertyID = property.PropertyID;
		var tenure = property.TenureDescription;
		var postcode = property.Postcode;
		var location = property.Location;
		
		var str = "<li propref_clipboard='" + PropertyID + "' id='" + PropertyID + "'><div class='checkbox'><input type='checkbox' name='cbx_clipboard' id='cbx_clipboard' onclick='cpd_clipboard_widget_sellect_obj(this);' /></div><div class='content-block'><p class='name'><span><img src='wp-content/plugins/cpd-search/images/btn_close.png' propref_clipboard='" + PropertyID + "' class='btn_close' onClick='cpd_clipboard_widget_delete(this);' width='15' height='13' /></span> " + name + "</p><p><span>Postcode:</span> " + postcode + "</p><p><span>Location:</span> " + location + "</p><p><span>Tenure:</span> " + tenure + "</p><p><span>Size:</span> " + size + "</p></div></li>";
		
		
		jQuery("#clipboard_list .no-item").remove();
		
		jQuery("#clipboard_list").append(str);
		
		jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
		jQuery('#cpdsearching').hide();
		cpd_clipboard_widget_check_show_scroll();
	}
}

function cpd_clipboard_widget_check_show_scroll()
{
	var list_li = jQuery("#clipboard #clipboard_list li");
	jQuery("#clipboard #clipboard_list li").css('border-bottom','1px solid #DADADA');
	jQuery("#clipboard #number_item").text(list_li.length);
	
	if(list_li.length <= 4)
	{
		jQuery("#clipboard .block").css('overflow-y','hidden');
		var height = 0;
		for(var i = 0;i<list_li.length;i++)
		{
			height += jQuery(list_li[i]).height();
		}
		jQuery("#clipboard .block").css('height',jQuery("#clipboard #clipboard_list").height());
		jQuery("#clipboard .clipboard-content").css('height',height + 160);
		jQuery("#clipboard .content-block").css('width',175);
	}
	else
	{
		jQuery("#clipboard .block").css('overflow-y','hidden');
		var height = 0;
		for(var i = 0;i<4;i++)
		{
			height += jQuery(list_li[i]).height() + 7;
		}
		jQuery("#clipboard .block").css('height',height);
		jQuery("#clipboard .clipboard-content").css('height',height + 160);
		jQuery("#clipboard .block").css('overflow-y','scroll');
		jQuery("#clipboard .content-block").css('width',160);
		
	}
	
	if(list_li.length > 0)
	{	
		jQuery(list_li[list_li.length - 1]).css('border-bottom','none');
	}
}

function cpd_clipboard_widget_error(data, status, error) {
	// Show error message
	jQuery("#cpderror").html("<p class='error'>Search failed! " + error + " (" + status + ")</p>");
	jQuery("#cpderror").dialog("open");
	
	jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
	jQuery('#cpdsearching').hide();
}

function cpd_clipboard_widget(obj) {
	
	jQuery('#cpdsearching span').html("Add to clipboard... Please wait a moment.");
	jQuery('#cpdsearching').show();
	
	var propref = jQuery(obj).attr("propref");	
	
	var postdata = {
		'action':'cpd_clipboard_widget_ajax',
		'propref': propref
	};
	
	// Send AJAX search request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: cpd_clipboard_widget_success,
		error: cpd_clipboard_widget_error,
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

function cpd_clipboard_widget_delete(obj)
{
	jQuery('#cpdsearching span').html("Delete item on clipboard... Please wait a moment.");
	jQuery('#cpdsearching').show();
	
	var propref = jQuery(obj).attr("propref_clipboard");	
	
	var postdata = {
		'action':'cpd_clipboard_widget_delete_ajax',
		'propref': propref
	};
	
	// Send AJAX search request to server
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		success: function(data){
			
			jQuery("#clipboard_list #" + propref).remove();
			
			jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
			jQuery('#cpdsearching').hide();
			cpd_clipboard_widget_check_show_scroll();
		},
		error: function(data){
			jQuery("#cpderror").html("<p class='error'>Search failed! " + data.error + ")</p>");
			jQuery("#cpderror").dialog("open");
			
			jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
			jQuery('#cpdsearching').hide();
		},
		dataType: "json"
	};
	jQuery.ajax(ajaxopts);
}

