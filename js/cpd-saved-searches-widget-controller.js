function CPDSavedSearchesWidget() {
	var self = this;
	
	var check_registration_name = /^[A-Za-z0-9_ ]{5,20}$/;

	self.saveSearchSuccess = function(data) {
		jQuery('#cpdsaveasearch').dialog('close');
		
		var objItem = jQuery(".savesearchresultholdingtable1_sidebar:eq(0)").clone();
		jQuery(objItem).find(".search_name").html(data['search_name']);
		jQuery(objItem).find(".date_last_search").html(data['date_last_search']);
		jQuery(objItem).attr("id",data['id']);
		jQuery(objItem).find("input:checkbox").attr("id",data['id']);
		jQuery(objItem).find("img").attr("id",data['id']);
		jQuery(objItem).find(".postcode").html(data['postcode']);
		jQuery(objItem).find(".location").html(data['address']);
		jQuery(objItem).find(".tenure_text").html(data['tenure_text']);
		jQuery(objItem).find(".tenure").html(data['tenure']);
		jQuery(objItem).find(".size").html(data['size']);
		jQuery(objItem).find(".sizefrom").html(data['sizefrom']);
		jQuery(objItem).find(".sizeto").html(data['sizeto']);
		jQuery(objItem).find(".size_units").html(data['size_units']);
		jQuery(objItem).find(".sectors").html(data['sectors']);
		jQuery(objItem).find(".areas").html(data['areas']);
		
		jQuery(objItem).find(".areas").css("display","");
		jQuery(objItem).css("display","");
		jQuery(".savesearchbottomtable_sidebar").before(objItem);	
		jQuery('#cpdsearching span').html("Saving... Please wait a moment.");
		jQuery('#cpdsearching').hide();
				
		self.hide_show();
		self.number_item();
	};
	self.saveSearchError = function(jqXHR, textStatus, errorThrown) {
		alert(textStatus);
	};
	self.saveSearch = function() {
		var search_name = jQuery("#cpdsaveasearch #search_name").val();
		var date_last_search = jQuery("#cpdsaveasearch #date_last_search").val();
		if (!check_registration_name.test(search_name)) {
			return;
		}
		if (!check_registration_name.test(date_last_search)) {
			return;
		}
		
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
		for (var i= 0; i< areas_options.length;i++ ) {
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
			'search_name':search_name,
			'date_last_search':date_last_search,
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			dataType: "json",
			success: self.saveSearchSuccess,
			error: self.saveSearchError,
		};
		jQuery.ajax(ajaxopts);
	};

	self.open_item = function() {
		var input = jQuery('input:checkbox[name="savesearch_select_all[]"]:checked');
		if(input.length != 1) {
			alert("Please chose one result saved");
			return false;
		}
		
		var id = jQuery('input:checkbox:checked').attr("id");
		var postcode = jQuery("#"+id).find("#data_hiden .postcode").text().trim();
		var address = jQuery("#"+id).find("#data_hiden .location").text().trim();
		var tenure = jQuery("#"+id).find("#data_hiden .tenure").text();
		var sectors = jQuery("#"+id).find("#data_hiden .sectors").text().split(",");
		var areas = jQuery("#"+id).find("#data_hiden .areas").text().split(",");
		var sizefrom = jQuery("#"+id).find("#data_hiden .sizefrom").text();
		var sizeto = jQuery("#"+id).find("#data_hiden .sizeto").text();
		var size_text = jQuery("#"+id).find("#data_hiden .size_text").text();
		var size_units = jQuery("#"+id).find("#data_hiden .size_units").text();
	
		jQuery("#form-sidebar #address_sidebar").val(address);
		jQuery("#form-sidebar #postcode_sidebar").val(postcode);
		jQuery("#form-sidebar #tenure_sidebar").val(tenure);
		jQuery("#form-sidebar #sizefrom_sidebar").val(sizefrom);
		jQuery("#form-sidebar #sizeto_sidebar").val(sizeto);
		jQuery("#form-sidebar #sizeunits_sidebar").val(size_units);

		jQuery("#form-sidebar").find("select#sectors_sidebar option").each(function () {
			jQuery(this).removeAttr('selected');
			for(var i = 0; i < sectors.length; i++) {
				if(jQuery(this).val().valueOf() == sectors[i]) {
					jQuery(this).attr('selected','selected');
				}
			}
		});
	
		jQuery("#form-sidebar").find("select#areas_sidebar option").each(function () {
			jQuery(this).removeAttr('selected');
			for(var i = 0;i < areas_split.length; i++) {
				if(jQuery(this).val().valueOf() == areas[i]) {
					jQuery(this).attr('selected','selected');
				}
			}
		});
	
		jQuery("#form-sidebar #submit").click();
		return false;
	};

	self.removeSearch = function(data) {
		var id = jQuery(this).attr("id");
		
		var postdata = {
			'action':'cpd_saved_searches_widget_remove_item_widget_ajax',
			'id':id,
		};
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			dataType: "json",
			success: function(data) {
				jQuery("#cpdsavesearch_sidebar #"+ id).remove();
				self.hide_show();
				self.number_item();
			},
			error: function(data) {alert("Data error");},
		};
		
		jQuery.ajax(ajaxopts);
	};

	self.number_item = function() {
		number = 0;
		jQuery(".savesearchresultholdingtable1_sidebar").each(function() {
			if(!jQuery(this).is(':hidden')) {
				number ++;
			}
		});
		jQuery(".savesearchtoptable_sidebar #number").text(number);
	};

	self.hide_show = function() {
		if(jQuery("#cpdsavesearch_sidebar #savesearchholdingcontainer_sidebar .savesearchresultholdingtable1_sidebar").length > 0) {
			jQuery("#cpdsavesearch_sidebar").show();
			return;
		}
		
		if(jQuery("#cpdsearchnavigationmodel").length == 0) {
			jQuery("#cpdsavesearch_sidebar").hide();
			return;
		}
		
		if(jQuery("#cpdsearchnavigationmodel").is(':hidden')) {
			jQuery("#cpdsavesearch_sidebar").hide();
			return;
		}
		
		if(jQuery("#cpdsavesearch_sidebar #savesearchholdingcontainer_sidebar .savesearchresultholdingtable1_sidebar").length == 0) {
			jQuery("#cpdsavesearch_sidebar").hide();
			return;
		}
		
		jQuery("#cpdsavesearch_sidebar").show();
	};

	/* registration when save search */
	self.check_registration_name = /^[A-Za-z0-9_ ]{5,20}$/;
	self.check_registration_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	self.check_registration_phone = /^[0-9-]{10,20}$/;
	self.check_password =  /^[A-Za-z0-9!@#$%^&amp;*()_]{6,20}$/;

	self.registrationSuccess = function(data) {				
		jQuery("#cpdsaveasearch #cpdregistering").hide();
		jQuery('#cpdsaveasearch .msg').hide();
		// Check for failure
		if(!data.success) {
			return self.registrationError(data, data.error, data.error);
		}

		// Hide registration form, show 'thankyou etc' part		
		jQuery('#cpdsaveasearch .msg').html('Thank you. You have now been registered.');
		jQuery('#cpdsaveasearch .msg').show();
		
		jQuery("#cpdsaveasearch #register").hide();

		// Process nearly registered interests
		cpdRegisterInterest.processQueue();
	};
	
	self.registrationError = function(jqXHR, textStatus, errorThrown) {
		jQuery('#cpdsaveasearch .msg').hide();
		jQuery("#cpdsaveasearch #cpdregistering").hide();
		if(jqXHR != null && jqXHR.error != null && jqXHR.error.indexOf("UserAlreadyExistsException") > -1) {
			// Show login form
			jQuery('#cpdsaveasearch .msg').html("No need to register. There is already an account for this e-mail address. Please try logging in with your existing credentials, or request a password reset if you have forgotten them.");
			jQuery('#cpdsaveasearch .msg').show();
			return;
		}
		if(jqXHR != null && jqXHR.error != null) {
			jQuery('#cpdsaveasearch .msg').html("ERROR: " + jqXHR.error);
			jQuery('#cpdsaveasearch .msg').show();
		}
	
		
		jQuery('#cpdsaveasearch .msg').html("Registration error!");
		jQuery('#cpdsaveasearch .msg').show();
	};
	
	self.registration = function() {		
	
		// Validation checks
		var name = jQuery('#cpdsaveasearch #name').val();
		var email = jQuery('#cpdsaveasearch #email').val();		
		var phone = jQuery('#cpdsaveasearch #phone').val();
		
		if(!self.check_registration_name.test(name)) {
			return;
		}
		if(!self.check_registration_email.test(email)) {
			return;
		}
		if(!self.check_registration_phone.test(phone)) {
			return;
		}
		
		jQuery("#cpdsaveasearch #cpdregistering").show();

		// Prepare to send
		var postdata = {
			'action':'cpd_user_registration',
			'name': jQuery('#cpdsaveasearch #name').val(),
			'email': jQuery('#cpdsaveasearch #email').val(),
			'phone': jQuery('#cpdsaveasearch #phone').val(),
		};

		// Send AJAX registration request to server
		var ajaxopts = {
			type: 'POST',
			url: CPDAjax.ajaxurl,
			data: postdata,
			success: self.registrationSuccess,
			error: self.registrationError,
			dataType: "json"
		};
		jQuery.ajax(ajaxopts);		
	};
	
	self.init = function() {
		jQuery("#cpdsaveasearch").dialog({
			title: "Save Search",
			autoOpen: false,
			height: 500,
			width: 350,
			modal: true,
			buttons: {
				"Save": self.saveSearch,
				"Cancel": function() {
					jQuery(this).dialog("close");
				},
			}
		});
		
		jQuery(".savesearchtopcolumnright_sidebar .btn_remove").live('click',self.removeSearch);
		
		jQuery("#search_name").focusout(function() {
			var search_name = jQuery(this).val();
			if (!check_registration_name.test(search_name)) {
				jQuery("#error-search_name").show().html("please fill search name");
				return;
			}
			jQuery("#error-search_name").hide();
		});
		jQuery("#date_last_search").focusout(function() {
			var date_last_search = jQuery(this).val();
			if (!check_registration_name.test(date_last_search)) {
				jQuery("#error-date_last_search").show().html("please fill note");
				return;
			}
			jQuery("#error-date_last_search").hide();
		});
		
		// Add verification of user name
		jQuery('#cpdsaveasearch #name').focusout(function() {
			var name = jQuery(this).val();
			if (!self.check_registration_name.test(name)){
				jQuery('#cpdsaveasearch  #error-name').show().html("Minimum 5 characters");
				return;
			}
			jQuery('#cpdsaveasearch #error-name').hide();
		});

		// Add verification of email
		jQuery('#cpdsaveasearch #email').focusout(function() {
			var email = jQuery(this).val();
			if (!self.check_registration_email.test(email)) {
				jQuery('#cpdsaveasearch #error-email').show().html("Invalid email address");
				return;
			}
			jQuery('#cpdsaveasearch  #error-email').hide();
		});
			
		// Add verification of phone
		jQuery('#cpdsaveasearch #phone').focusout(function() {
			var phone = jQuery(this).val();
			if (!self.check_registration_phone.test(phone)){
				jQuery('#cpdsaveasearch  #error-phone').show().html("Invalid phone number");
				return;
			}
			jQuery('#cpdsaveasearch  #error-phone').hide();
		});
		
		self.hide_show();
		self.number_item();
		
		jQuery("#savesearch").click(function() {
			jQuery("#cpdsaveasearch").dialog("open");
			return false; 
		});
		
		jQuery("#cpdsaveasearch #button").click(self.registration);
		
		jQuery("#cpdsavesearch_sidebar input[type=checkbox]").live('click',function() {
			var currentCheckbox = jQuery(this);
			var temp = currentCheckbox.attr("checked");
			if(temp == "checked") {
				jQuery("#cpdsavesearch_sidebar input[type=checkbox]").each(function() {
					if(jQuery(this).attr("id") != currentCheckbox.attr("id")) {
						jQuery(this).removeAttr("checked");
					}
				});
			}
		});
	};
	
	return self;
};

cpdSavedSearchesWidget = new CPDSavedSearchesWidget();

jQuery(document).ready(function() {
	cpdSavedSearchesWidget.init();
});
