function cpd_saved_searches(){

	jQuery('#cpdsearching span').html("Save search... Please wait a moment.");
	jQuery('#cpdsearching').show();
	
	var d = new Date();
	var n = d.getTime();
	var size = jQuery("select#sizeunits_sidebar option:selected").val();
	var sectors_options = jQuery("select#sectors_sidebar option:selected");
	var sectors = [];
	
	for (var i = 0; i < sectors_options.length; i++) {
		sectors[i] = sectors_options[i].value;
	}
	
	var tenure = jQuery("select#tenure_sidebar option:selected").val();
	var tenure_text = jQuery("select#tenure_sidebar option:selected").text();
	var size = jQuery("select#sizeunits_sidebar option:selected").val();
	var size_text = jQuery("select#sizeunits_sidebar option:selected").text();
	var sizefrom = jQuery("#form-sidebar #sizefrom_sidebar").val();
	var sizeto = jQuery("#form-sidebar #sizeto_sidebar").val();
	var areas_options = jQuery("select#areas_sidebar option:selected");
	var areas = [];
	for (var i= 0; i< areas_options.length;i++ ){
		areas[i] = areas_options[i].value;
	}
	var address = jQuery('#address_sidebar').val();
	var postcode = jQuery('#postcode_sidebar').val();
	var postdata = {
		'action':'cpd_saved_searches_widget_ajax',
		'tenure': tenure,
		'tenure_text': tenure_text,
		'address': address,
		'postcode': postcode,
		'size':size,
		'size_text':size_text,
		'sizefrom':sizefrom,
		'sizeto':sizeto,
		'sectors':sectors,
		'areas':areas,
		'id':n,
	};
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		dataType: "json",
		success: cpd_saved_searches_success,
		error: function(data){alert("data error");},
	};
	jQuery.ajax(ajaxopts);
}

function cpd_saved_searches_success(data){
	var str = "<li class='content-block' id='" + data['id'] + "'><div><p><input type='checkbox' name='clipboard_select_all[]' id='"+data['id']+"' /><span>Search Name</span><span class='date-last'>Date Last Search</span><img src='wp-content/plugins/cpd-search/images/X.png' id='"+ data['id'] +"' onClick='cpd_remove_saved_searches(this);' width='15' height='13' /></p><p><span>Postcode:</span><span class='postcode'>" + data['postcode'] + "</span><span class='location date-last'>Location:</span><span class='address'> " + data['address'] + "</span></p><p><span>Tenure:</span><span class='tenure_text'> " + data['tenure_text'] + "</span><span class='tenure' style='display:none'>"+ data['tenure'] +"</span><span class='sectors' style='display:none'>"+ data['sectors'] +"</span><span class='areas' style='display:none'>"+ data['areas'] +"</span><span>Size:</span><span>"+ data['sizefrom']+'-'+ data['sizeto'] + data['size_text'] +"</span><span class='sizefrom' style='display:none'>"+data['sizefrom']+"</span><span class='sizeto' style='display:none'>"+data['sizeto']+"</span><span class='size' style='display:none'>"+data['size']+"</span></p></div></li>";
	jQuery("#saved-searches").append(str);
	jQuery('#cpdsearching span').html("Saving... Please wait a moment.");
	jQuery('#cpdsearching').hide();
	jQuery("#saved_searches #number_item_saved").text(jQuery("#saved-searches li").length);
}

//open saved seareches
function cpd_open_saved_searches(){
	
	var input = jQuery('input:checkbox[name="clipboard_select_all[]"]:checked');
	
	if(input.length == 1){
		
		var id = jQuery('input:checkbox:checked').attr("id");
		var postcode = jQuery("#"+id).find(".postcode").text();
		var address = jQuery("#"+id).find(".address").text();
		var tenure = jQuery("#"+id).find(".tenure").text();
		var sectors = jQuery("#"+id).find(".sectors").text();
		var areas = jQuery("#"+id).find(".areas").text();
		var sizefrom = jQuery("#"+id).find(".sizefrom").text();
		var sizeto = jQuery("#"+id).find(".sizeto").text();
		var size = jQuery("#"+id).find(".size").text();
		
	} else{
		
		alert("Please chose one result saved");
		
	}
	
	var areas_split = areas.split(",");
	var sectors_split = sectors.split(",");
		
	jQuery("#form-sidebar").find("#address_sidebar").val(address);
	jQuery("#form-sidebar").find("#postcode_sidebar").val(postcode);
	jQuery("#form-sidebar").find("#tenure_sidebar").val(tenure);
	jQuery("#form-sidebar").find("#sizefrom_sidebar").val(sizefrom);
	jQuery("#form-sidebar").find("#sizeto_sidebar").val(sizeto);
	jQuery("#form-sidebar").find("#sizeunits_sidebar").val(size);
	
	jQuery("#form-sidebar").find("select#sectors_sidebar option").each(function (){
		jQuery(this).removeAttr('selected');
		for(var i = 0;i < sectors_split.length;i++){
			if(jQuery(this).val().valueOf() == sectors_split[i] )
			jQuery(this).attr('selected','selected');
		}
	});
	
	jQuery("#form-sidebar").find("select#areas_sidebar option").each(function (){
		jQuery(this).removeAttr('selected');
		for(var i = 0;i< areas_split.length;i++){
			if(jQuery(this).val().valueOf() == areas_split[i] )
			jQuery(this).attr('selected','selected');
		}
	});
}

//remove saved searches
function cpd_remove_saved_searches(data){
	
	var id = jQuery(data).attr("id");
	var postdata = {
		'action':'cpd_remove_saved_searches_widget_ajax',
		'id':id,
	};
	
	var ajaxopts = {
		type: 'POST',
		url: CPDAjax.ajaxurl,
		data: postdata,
		dataType: "json",
		success: function(data){jQuery("#saved-searches #"+ id).remove();
		jQuery("#saved_searches #number_item_saved").text(jQuery("#saved-searches li").length);
		},
		error: function(data){alert("data error");},
	};
	
	jQuery.ajax(ajaxopts);
}

function cpd_last_result(){
	
	jQuery("#saved-searches li").find("input").attr("checked",false);
	jQuery("#saved-searches li").last().find("input").attr("checked",true);
	cpd_open_saved_searches();
	cpd_search_our_database_submit_form();
	
}

jQuery(document).ready(function(){
	
	jQuery("#saved_searches #number_item_saved").text(jQuery("#saved-searches li").length);
	
})
