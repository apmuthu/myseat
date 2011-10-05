<!-- jQuery Javascript Framework / If using a HTTPS connection do not load via CND -->
<!--
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript">!window.jQuery && document.write(unescape("%3Cscript src='js/jquery-1.4.4.min.js' type='text/javascript'%3E%3C/script%3E"))</script>
<script type="text/javascript">!window.jQuery.ui && document.write(unescape("%3Cscript src='js/jquery-ui-1.8.10.custom.min.js' type='text/javascript'%3E%3C/script%3E"))</script>
-->
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.10.custom.min.js"></script>

	<!--[if IE]>
		<script type="text/javascript" src="js/excanvas.js"></script>
	<![endif]-->
	<!-- Javascript at the bottom for fast page loading --> 
	<script type="text/javascript" src="js/plugins.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.0.js"></script>
	<script type="text/javascript" src="js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="lang/jquery.ui.datepicker-<?php echo substr($_SESSION['language'],0,2);?>.js"></script>	
	
	<script type="text/javascript">
	
	// remember the old datepicker function
	var _gotoToday = jQuery.datepicker._gotoToday;
		// datepicker is directly inside the jQuery object, so override that
		jQuery.datepicker._gotoToday = function(a){
	    var target = jQuery(a);
	    var inst = this._getInst(target[0]);
	    // call the old function, so default behaviour is kept
	    _gotoToday.call(this, a);
	    // now do an additional call to _selectDate which will set the date and close
	    // close the datepicker (if it is not inline)
	    jQuery.datepicker._selectDate(a, 
	    jQuery.datepicker._formatDate(inst,inst.selectedDay, inst.selectedMonth, inst.selectedYear));
	}
	
$(document).ready(function() {
		// Change of outlet ID in edit mode
		$("#reservation_outlet_id").change(function(){
		    window.location.href='?resedit=1&outletID=' + this.value;
		  });
		
		// Preload images
		$.preloadCssImages();
		
		// Realtime reservation updates with arte plugin
		$.arte({'ajax_url': 'ajax/realtime.php?lastid=<?php echo $_SESSION['max_id']; ?>', 'on_success': update_field, 'time': 5000}).start();
			function update_field(data)
			{
				if ( data > 0 ) {
				$("#realtimeupdate").html(
					"<div class='alert_warning'><p style='margin-bottom:10px;'><a href='main_page.php?selectedDate=<?php echo $_SESSION['selectedDate']; ?>'>(" + data + ") <?php echo _new_entry;?></a></p></div>"
					);	
				};
			}
		
		// edit-toogle buttons
		$('#editToggle').click(function() {
          $('#show').toggle();
          $('#edit').toggle();
        });
		// Setup datepicker input
		$("#datepicker").datepicker({
			nextText: '&raquo;',
			prevText: '&laquo;',
			firstDay: 1,
			numberOfMonths: 2,
			gotoCurrent: true,
			altField: '#dbdate',
			altFormat: 'yy-mm-dd',
			defaultDate: 0,
			showButtonPanel: true,
			dateFormat: '<?php echo $general['datepickerformat'];?>',
			regional: '<?php echo substr($_SESSION['language'],0,2);?>',
			onSelect: function(dateText, inst) { window.location.href="?selectedDate="+$("#dbdate").val(); }
		});
		// Setup datepickers export
		<?php if($_SESSION['page']=='4'):?>
			$("#s_datepicker").datepicker({
				nextText: '&raquo;',
				prevText: '&laquo;',
				firstDay: 1,
				numberOfMonths: 1,
				gotoCurrent: true,
				altField: '#s_dbdate',
				altFormat: 'yy-mm-dd',
				defaultDate: 0,
				dateFormat: '<?php echo $general['datepickerformat'];?>',
				regional: '<?php echo substr($_SESSION['language'],0,2);?>'
			});
			$("#e_datepicker").datepicker({
				nextText: '&raquo;',
				prevText: '&laquo;',
				showAnim: 'slideDown',
				firstDay: 1,
				numberOfMonths: 1,
				gotoCurrent: true,
				defaultDate: 0,
				altField: '#e_dbdate',
				altFormat: 'yy-mm-dd',
				dateFormat: '<?php echo $general['datepickerformat'];?>',
				regional: '<?php echo substr($_SESSION['language'],0,2);?>'
			});
			//$("#datepicker").datepicker('setDate', new Date ( "<?php echo $pickerDate; ?>" ));
		<?php endif ?>
		// Setup recurring date input
		$("#recurring_date").datepicker({
			nextText: '&raquo;',
			prevText: '&laquo;',
			firstDay: 1,
			numberOfMonths: 1,
			gotoCurrent: true,
			defaultDate: 0,
			altField: '#recurring_dbdate',
			altFormat: 'yy-mm-dd',
			defaultDate: 0,
			dateFormat: '<?php echo $general['datepickerformat'];?>',
			regional: '<?php echo substr($_SESSION['language'],0,2);?>'
		});
		//$("#recurring_date").datepicker('setDate', new Date ( "<?php echo $_SESSION['selectedDate']; ?>" ));
		// Setup event datepicker
		$("#ev_datepicker").datepicker({
			nextText: '&raquo;',
			prevText: '&laquo;',
			firstDay: 1,
			numberOfMonths: 1,
			gotoCurrent: true,
			altField: '#event_date',
			altFormat: 'yy-mm-dd',
			defaultDate: 0,
			dateFormat: '<?php echo $general['datepickerformat'];?>',
			regional: '<?php echo substr($_SESSION['language'],0,2);?>'
		});
		
		<?php if($_SESSION['page']=='3'):?>
			/* Data Graph */
			setTimeout(function(){ 

				// Setup graph 1
		    	$('#graph_week').visualize({
					width: '760px',
					height: '240px',
					colors: ['#2398C9']
				}).appendTo('#graph_wrapper1');

				// Setup graph 2
		    	$('#graph_month').visualize({
					type: 'area',
					width: '760px',
					height: '240px',
					colors: ['#2398C9', '#C0DB1E']
				}).appendTo('#graph_wrapper2');

				// Setup graph 3
		    	$('#graph_type').visualize({
					type: 'pie',
					width: '760px',
					height: '240px',
					colors: ['#2398C9','#C0DB1E','#ee8310','#be1e2d','#666699','#ee8310','#92d5ea','#8d10ee','#5a3b16','#26a4ed']
				}).appendTo('#graph_wrapper3');
				
				// Setup graph 4
		    	$('#graph_weekday').visualize({
					width: '760px',
					height: '240px',
					colors: ['#C0DB1E']
				}).appendTo('#graph_wrapper4');

				$('.visualize').trigger('visualizeRefresh');

			}, 200);
		<?php endif ?>
		<?php if($_SESSION['page']=='6'):?>
				/* JWYSIWYG Editor for description textarea */
				$('#wysiwyg').wysiwyg();
		<?php endif ?>
	});
	</script>
  </body>
</html>