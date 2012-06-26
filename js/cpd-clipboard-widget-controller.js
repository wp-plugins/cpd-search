// Main controller logic for initialising and handling the search form activity


jQuery(document).ready(function() {
	cpd_clipboard_widget_check_show_scroll();
	cpd_clipboard_widget_hide_show();
});


function cpd_clipboard_widget_hide_show()
{
	if(jQuery("#cpdsearchnavigationmodel").length == 0)
	{
		jQuery("#cpdclipboard_sidebar").hide();
		return;
	}
	if(jQuery("#cpdsearchnavigationmodel").is(':hidden'))
	{
		jQuery("#cpdclipboard_sidebar").hide();
	}
	else
	{
		jQuery("#cpdclipboard_sidebar").show();
	}
}

function cpd_clipboard_widget_sellect_all(obj)
{	
	if(jQuery(obj).is(':checked'))
	{
		jQuery("#contentbox #cbx_clipboard:gt(0)").attr('checked','checked');
	}
	else
	{
		jQuery("#contentbox #cbx_clipboard").removeAttr('checked');
	}
}

function cpd_clipboard_widget_sellect_obj(obj)
{	
	var i = 0;
	var j = 0;
	
	jQuery("#contentbox #cbx_clipboard").each(function(){
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

	var objItem = jQuery(".clipboardresultholdingtable1_sidebar:eq(0)").clone();
	
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
		
		jQuery(objItem).attr("propref_clipboard",PropertyID);
		jQuery(objItem).attr("id",PropertyID);
		jQuery(objItem).find("img").attr("propref_clipboard",PropertyID);
		jQuery(objItem).find("input").attr("propref_clipboard",PropertyID);
		jQuery(objItem).find(".name").html(name);
		jQuery(objItem).find(".Postcode").html(postcode);
		jQuery(objItem).find(".Location").html(location);
		jQuery(objItem).find(".Tenure").html(tenure);
		jQuery(objItem).find(".Size").html(size);
		jQuery(objItem).css("display","");
		jQuery(".clipboardresultholdingtable1_sidebar").last().after(objItem);	
		jQuery('#cpdsearching span').html("Searching... Please wait a moment.");
		jQuery('#cpdsearching').hide();
		cpd_clipboard_widget_check_show_scroll();
	}
}

function cpd_clipboard_widget_check_show_scroll()
{
	var list_li = jQuery("#contentbox .clipboardresultholdingtable1_sidebar:gt(0)");
	jQuery("#contentbox clipboardresultholdingtable1_sidebar").css('border-bottom','1px solid #DADADA');
	jQuery("#form-sidebar #number_item").text(list_li.length);
	
	if(list_li.length <= 4)
	{
		jQuery("#contentbox").css('overflow-y','hidden');
		var height = 0;
		for(var i = 0;i<list_li.length;i++)
		{
			height += jQuery(list_li[i]).height();
		}
		jQuery("#contentbox").css('height',height);
	}
	else
	{
		jQuery("#contentbox").css('overflow-y','hidden');
		var height = 0;
		for(var i = 0;i<4;i++)
		{
			height += jQuery(list_li[i]).height() + 7;
		}
		jQuery("#contentbox").css('height',height);
		jQuery("#contentbox").css('overflow-y','scroll');
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
	
	jQuery('#cpdsearching span').html("Adding to clipboard... Please wait a moment.");
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
	jQuery('#cpdsearching span').html("Deleting item from clipboard... Please wait a moment.");
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
			
			jQuery("#clipboardholdingcontainer_sidebar #" + propref).remove();
			
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

function cpd_clipboard_widget_pushpost(){
	
	jQuery('#cpdsearching span').html("Posting clipboard contents... Please wait a moment.");
	jQuery('#cpdsearching').show();
	var input = [];
	var id = [];
	var input = jQuery('input:checkbox[name="cbx_clipboard[]"]:checked');
	for (var i = 0; i< input.length;i++){
		 id[i] = jQuery(input[i]).attr("propref_clipboard");	
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
	jQuery('#cpdsearching').hide();
	
	return false;
}
