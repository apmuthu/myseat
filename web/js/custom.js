$(function(){
	
	$('#topbar').dropdown();
	$('.tipsyold').tipsy({gravity: 'w'});
	$('.tipsy').tipsy();
	
	$(document).click(function(){
		$('.popup').css('display', 'none');
		$('.popup').parent().find('a').removeClass('selected');
	});
	
	$('table.global tbody tr').mouseenter(function(){
		$(this).css('background', '#f6f6f6');
	});
	
	$('table.global tbody tr').mouseleave(function(){
		$(this).css('background', '#ffffff');
	});
	
	$(window).resize(function() {
 		 $('.wysiwyg').css('width', '100%');
	});
	
	// Setup style of select
	//$('div.option div.text').html($('option:selected').text());
	$('div.option').each(function(){
		var value = $(this).find('option:selected').text();
	            $(this).find('div.text').attr("innerHTML", value);
	        });
	
	$('div.option').find('select:first').change(function(){
		//$(this).parent().find('div.text').html($(this).val());
		$(this).parent().find('div.text').html($('option:selected', this).text());
	});
	//$('div.option div.text').html($('option:selected').text());
	$('div.option_xl').each(function(){
		var value = $(this).find('option:selected').text();
	            $(this).find('div.text').attr("innerHTML", value);
	        });
	
	$('div.option_xl').find('select:first').change(function(){
		//$(this).parent().find('div.text').html($(this).val());
		$(this).parent().find('div.text').html($('option:selected', this).text());
	});
	
	// Setup style of input file
	$('div.file').find('input:first').change(function(){
		$(this).css('top', '-18px');
		var filename = $(this).val().replace(/^.*\\/, '').substr(0, 24);
		$(this).parent().find('div.text').html(filename);
	});
	
	$('.media_photos li').mouseenter(function(){
		$(this).find('.action').css('visibility', 'visible');
	});
	
	$('.media_photos li').mouseleave(function(){
		$(this).find('.action').css('visibility', 'hidden');
	});
	
	$('div.date').find('input:first').change(function(){
		if (BrowserDetect.browser != "Explorer")
		{
			$(this).css('top', '-23px');
		}
		else
		{
			if(BrowserDetect.version > 7)
			{
				$(this).css('top', '-23px');
			}
			else
			{
				$(this).css('top', '-12px');
			}
		}
		
		$(this).parent().find('div.text').html($(this).val());
	});
	
	// Setup click to hide all alert boxes
	$('.alert_warning').click(function(){
		$(this).fadeOut('fast');
	});
	$('.alert_tip').click(function(){
		$(this).fadeOut('fast');
	});
	$('.alert_success').click(function(){
		$(this).fadeOut('fast');
	});
	$('.alert_error').click(function(){
		$(this).fadeOut('fast');
	});
	$('.alert_ads').click(function(){
		$(this).fadeOut('fast');
	});
	
	// Setup modal window for all photos
	$('.media_photos li a[rel=slide]').fancybox({
		padding: 0, 
		titlePosition: 'outside', 
		overlayColor: '#333333', 
		overlayOpacity: .2
	});
	
});

$(document).ready(function() {
	
	// Find all the input elements with title attributes and add hint to it
    $('input[title!=""]').hint();
	jQuery.validator.messages.required = "";

    // Start validation with 'new' reservation form
	$("#new_reservation_form").validate({
			highlight: function(element, errorClass) {
				$(element).addClass(errorClass).parent('.option').addClass('error_select');
			}
		});
   // Start validation with 'general settings' form
	$("#general_settings_form").validate({
			highlight: function(element, errorClass) {
				$(element).addClass(errorClass).parent('.option').addClass('error_select');
			}
		});
	// Start validation with 'outlet edit' form	
	$("#edit_outlet_form").validate({
			highlight: function(element, errorClass) {
				$(element).addClass(errorClass).parent('.option').addClass('error_select');
			}
		});
	// Start validation with 'events' form	
	$("#event_form").validate({
			highlight: function(element, errorClass) {
				$(element).addClass(errorClass).parent('.option').addClass('error_select');
			}
		});
	// Start validation with 'new user' form	
	$("#user_form").validate({
			rules: {
				password2: {
					required: true,
					equalTo: "#password"
				}
			},
			messages: {
				password2: {
					equalTo: "Enter the same password as above"
				}
			},
			highlight: function(element, errorClass) {
				$(element).addClass(errorClass).parent('.option').addClass('error_select');
			}
		});
	// Start validation with 'property edit' form	
	$("#property_form").validate({
			highlight: function(element, errorClass) {
				$(element).addClass(errorClass).parent('.option').addClass('error_select');
			}
		});
	// remove error class on change of select element
	$("#reservation_time").change(function() { 
		        $(this).parent('.option').removeClass('error_select');
		});
	$("#reservation_title").change(function() { 
			    $(this).parent('.option').removeClass('error_select');
		});
	$("#reservation_hotelguest_yn").change(function() { 
				    $(this).parent('.option').removeClass('error_select');
			});
	//initial setup of selected date in input field and div
		if (BrowserDetect.browser != "Explorer")
		{
			$("#datepicker").css('top', '-21px');
			$("#s_datepicker").css('top', '-21px');
			$("#e_datepicker").css('top', '-21px');
			$("#ev_datepicker").css('top', '-21px');
			//$("#recurring_date").css('top', '-21px');
		}
		else
		{
			if(BrowserDetect.version > 7)
			{
				$("#datepicker").css('top', '-21px');
				$("#s_datepicker").css('top', '-21px');
				$("#e_datepicker").css('top', '-21px');
				$("#ev_datepicker").css('top', '-21px');
				//$("#recurring_date").css('top', '-21px');
			}
			else
			{
				$("#datepicker").css('top', '-10px');
				$("#s_datepicker").css('top', '-10px');
				$("#e_datepicker").css('top', '-10px');
				$("#ev_datepicker").css('top', '-10px');
				$("#recurring_date").css('top', '-10px');
			}
		}
		
		//initial setup of selected date in input field and div
			if (BrowserDetect.browser != "Explorer")
			{
				$(".option select").css('top', '-21px');
			}
			else
			{
				if(BrowserDetect.version > 7)
				{
					$(".option select").css('top', '-21px');
				}
				else
				{
					$(".option select").css('top', '-10px');
				}
			}
		
		//initial setup of selected date in input field and div
			if (BrowserDetect.browser != "Explorer")
			{
				$(".option_xl select").css('top', '-21px');
			}
			else
			{
				if(BrowserDetect.version > 7)
				{
					$(".option_xl select").css('top', '-21px');
				}
				else
				{
					$(".option_xl select").css('top', '-10px');
				}
			}
	
	//fade out message box
	$('#messageBox').fadeTo(2500,1).fadeOut(1000);
	
	
	// outlet detail slider
	//$("#outlet_detail_slider").hide();
	    $('#outlet_detail_button').click(function() {
	    $('#outlet_detail_slider').slideToggle(500);
	    return false;
	});

	//activate Autocomplete
	 $("#reservation_guest_name").autocomplete({
		source: 'ajax/autocomplete_res.php', 
		minLength: 2,
		select: function(event, ui) {
			$('#reservation_guest_name').val(ui.item.value);
			$('#reservation_guest_adress').val(ui.item.reservation_guest_adress);
			$('#reservation_guest_city').val(ui.item.reservation_guest_city);
			$('#reservation_guest_email').val(ui.item.reservation_guest_email);
			$('#reservation_guest_phone').val(ui.item.reservation_guest_phone);
			if(ui.item.reservation_advertise == "YES"){
				$('#reservation_advertise').attr('checked', true);
			}
			
		}
	  });
	 $("#reservation_booker_name").autocomplete({
		source:'ajax/autocomplete.php?field=reservation_booker_name',
		minLength: 2
	  });
	$("#reservation_guest_email").autocomplete({
		source:'ajax/autocomplete.php?field=reservation_guest_email',
		minLength: 2,
		select: function (event, ui) {
		                    if (ui.item) {
		                        $("#reservation_guest_email").val(ui.item.value);
		                    }
		                }
	  });

	// delete button modal message
	$(".delbtn").fancybox({
		'titleShow' : false,        
		'modal' : true,
		onStart  :   function(selectedArray, 
		selectedIndex, selectedOpts) {
				del_id  = selectedArray[ selectedIndex ].id; 
				del_rep = selectedArray[ selectedIndex ].name;
				del_row	= $("#" + del_id).parents('tr:first');
				
				/* hide 'delete series' button at single entry */
				if (del_rep == 0 ) {
					$('#button_al').css("display","none");
				}
	        }
    });// delete button END

	// Delete action
	 $('#modalsecurity .send-button').click(function () {
	 var author_value = $('#modalsecurity #conf-author').val();
	 var del_button = $(this).attr('name');
		// Modal message send form
		 if(author_value.length>2){
				$.ajax({
					url: 'ajax/modify_entry.php',
					data: 'action=DEL&cellid=' + del_id + '&button=' + del_button + '&repeatid=' + del_rep + '&author=' + author_value,
					type: 'post',
					cache: false,
					dataType: 'html',
					success: function(original_element){
						$.fancybox.close();
						$(del_row).fadeOut(800, function() { $(this).remove(); }); //Remove the row
						window.location.reload();
					}
				});
		 } //end if 
	});// Modal message send form END

	// delete button modal message
	$(".deletebtn").fancybox({
		'titleShow' : false,        
		'modal' : true,
		onStart  :   function(selectedArray, 
		selectedIndex, selectedOpts) {
				del_id  = selectedArray[ selectedIndex ].id; 
				del_type = selectedArray[ selectedIndex ].name;
				del_row	= $("#" + del_id).parents('tr:first');
	        }
    });// delete button END

	// Delete action
	 $('#modaldelete .send-button').click(function () {
		// Modal message send form
				$.ajax({
					url: 'ajax/delete.php',
					data: 'action=DEL&cellid=' + del_id + '&type=' + del_type,
					type: 'post',
					cache: false,
					dataType: 'html',
					success: function(original_element){
						$.fancybox.close();
						$(del_row).fadeOut(800, function() { $(this).remove(); }); //Remove the row
					}
				});//end $.ajax
	});// Modal message send form END

	// Allow action
	 $(".alwbtn").click(function () {
	 var id = $(this).attr('name');
				$.ajax({
					url: 'ajax/modify_entry.php',
					data: 'action=ALW&cellid=' + id ,
					type: 'post',
					cache: false,
					dataType: 'html',
					success: function(original_element){
						window.location.reload();
					}
				}); 
	});// Modal message send form END
	
	/* Reservation Status dropdownbox */
	$(".status_dbox").change(function(){ 
		var status_id = $(this).attr('id');
		var selected = $(this).val();
		status_id = status_id.substring(5,30);
		$.ajax({
		type: "POST",
		url: "ajax/modify_status.php",
		data: 'value=' + selected + '&id=' + status_id,
		success: function(result){
			location.reload();
		}
		});
	}); // Reservation Status dropdownbox END

	/* Dayoff Status checkbox */
	
	$("#outlet_child_dayoff").change(function(){ 
		var status_id = $(this).attr('name');
		var value = $(this).val();
		$.ajax({
		type: "POST",
		url: "ajax/modify_dayoff.php",
		data: 'value=' + value + '&id=' + status_id,
		success: function(result){
			location.reload();
		}
		});
		return true;
	}); 
	
	
	/* InlineEdit activation */
	$("#modaltabletrigger").fancybox();
	
	$('.inlineedit').editable('ajax/inline_edit.php', { 
	    type       : 'text',
	    cancel     : 'Cancel',
	    submit     : 'OK',
		placeholder: '...',
		cssclass   : 'inherit',
		onblur     : 'ignore', 
	    indicator  : '<img src="images/ajax-loader.gif">',
		callback		: function(original_element, html, value){
			// Check for unique table number and warn
			var array = [];
			var i = 0;
			$(".tb_nr").each(function () {
		   		if( $(this).text() == original_element ){
					i = i + 1;
					if( i == 2 ){
						/* Deactivated double Table number warning */
						/* $("#modaltabletrigger").trigger('click'); */
						return false;
					}
				}
			});
			}
	});
	
		// check username

		$("#username").change(function() {
		var usr = $("#username").val();
		if(usr.length >= 3) {
		  $("#status").html('<img align="absmiddle" src="images/ajax-loader.gif" />');

		  $.ajax({
			type: "POST",
			url: "ajax/check_username.php",
			data: "username="+ usr,
			success: function(msg){

				$("#status").ajaxComplete(function(event, request){
					if(msg.length <= 4){
						$("#username").removeClass('error');
						$("#username").addClass('blur');
						$(this).html(' <img align="absmiddle" src="images/icons/icon_accept.png" /> ' + msg);
					}else{
						$("#username").removeClass('blur');
						$("#username").addClass('error');
						/* $("#username").val(''); */
						$(this).html(msg);
					}
				});
			}
		  });
		}
	  });
	
	// Open CXL List
	$("a#cxlbuttontrigger").fancybox({
			'hideOnContentClick': true
	});
	// Open guest/reservation details
	$("a#detlbuttontrigger").fancybox({
			'hideOnContentClick': true
	});

	//wysiwyg css 100%
	$('.wysiwyg').css('width', '100%');
	
    
});