<div id="content_wrapper">
<br/>
	<div class="twocolumn_wrapper">
	 <div class="twocolumn">
	  <div class="content res-height">
<!-- Beginn left column -->	
<form method="post" action="ajax/process_reservation.php" id="new_reservation_form">
		<br/>
		<p>
		<label><?php echo _time; ?>*</label><br/>
				<?php getTimeList($general['timeformat'], $general['timeintervall'],'reservation_time','',$_SESSION['selOutlet']['outlet_open_time'],$_SESSION['selOutlet']['outlet_close_time'],1,'required');?>
		</p>
		<br/>
		<p>
		<label><?php echo _title; ?>*</label><br/>
				<?php getTitleList();?>
		</p>
		<br/>
		<p>
		<label><?php echo _guest_name; ?>&deg;*</label><br/>
			<input type="text" name="reservation_guest_name" id="reservation_guest_name" class="required width-250" title=' ' minlength="3"/>
		</p>
		<br/>
		<p>
		<label><?php echo _pax; ?>*</label><br/>
			<input type="text" name="reservation_pax" id="reservation_pax" class="required digits width-50" title=' ' />
		</p>
		<br/>
		<p>
	    <label><?php echo _type; ?>*</label><br/>
				<?php getTypeList();?>
		</p>
		<br/>
		<p>
		<label><?php echo _phone_room; ?></label><br/>
			<input type="text" name="reservation_guest_phone" id="reservation_guest_phone"/>
		</p>
		<br/>
		<p>
		<label><?php echo _email; ?>&deg;</label><br/>
				<input type="text" name="reservation_guest_email" id="reservation_guest_email" />
				<small>&nbsp;<?php echo _fill_out; ?></small><br>
				<small>
					<?php echo _confirmation_email.": "; ?>
					<input type="radio" name="email_type" checked="checked" value="no"/><?php echo _no_; ?>
					<input type="radio" name="email_type" value="loc"/><?php echo _english; ?>
					<input type="radio" name="email_type" value="en"/><?php echo _international; ?>
					<input type="hidden" name="reservation_advertise" value=""/>
				</small><br>
				<input type="checkbox" name="reservation_advertise" id="reservation_advertise" value="YES"/>&nbsp;&nbsp;<small><?php echo _reservation_advertise; ?></small>
		</p>
		<br/>
		<p>
		<label><?php echo _note; ?></label><br/>
			<textarea name="reservation_notes" id="reservation_notes" rows="5" cols="35" class="width-97"></textarea>
		</p>
		<br/>
		<p>
		<label><?php echo _author; ?>&deg;*</label><br/>
			<input type="text" name="reservation_booker_name" id="reservation_booker_name" class='required' minlength="3"  maxlength="30" title=' ' />
		</p>
		<br/>
		<?php 
			if ($_SESSION['selOutlet']['limit_password']!='') {
		?>
			<p>
			<label><?php echo _enter_password; ?>*</label><br/>
				<input type="text" name="limit_password" id="limit_password" class="required width-97" style="float:left;"/>
				<div id="status"></div>
			</p>
		<?php } ?>
		<br/><br/>
		<div class="center">
			<input id="submit_btn" type="submit" class="button_dark" value="<?php echo _save;?>">
			<br/>
			<?php 
				if($special_event_subject!=''){
					echo "<br/><p><strong>"._reservations." "._for_." ".$special_event_subject." !</strong></p>";
				}
			?>
		</div>
	</div></div></div> <!-- end left column -->

<!-- Beginn right column -->	
	<div class="twocolumn_wrapper right">
	 <div class="twocolumn" >
	  <div class="content res-height">
		<br/>
		<p>
		<label><?php echo _adress; ?></label><br/>
				<input type="text" name="reservation_guest_adress" id="reservation_guest_adress" />
		</p>
		<br/>
		<p>
		<label><?php echo _area_code; ?></label><br/>
				<input type="text" name="reservation_guest_city" id="reservation_guest_city" />
		</p>
		<br/>
		<p>
		<label><?php echo _discount; ?></label><br/>
					<input name="reservation_discount" name="reservation_discount" id="reservation_discount" maxlength="3" class="width-50"/>
		</p>
		<br/>
		<p>
		<label><?php echo _parking; ?></label><br/>
					<input name="reservation_parkticket" name="reservation_parkticket" id="reservation_parkticket" maxlength="3" class="width-50"/>
		</p>
		<br/>
		<p>
		<label><?php echo _payment; ?></label><br/>
			<span class="width-250">
			<?php echo _paid;?>
			<input type="checkbox" class="margin-right-20" name="reservation_bill_paid" value="<?php if ($reservation_bill_paid!=""){echo $reservation_bill_paid;} else {echo date($general['dateformat']);} ?>" />
			<?php echo _shipped;?>
			<input type="checkbox" name="reservation_billet_sent" value="<?php if ($reservation_billet_sent!=""){echo $reservation_billet_sent;} else {echo date($general['dateformat']);} ?>" /> 
			</span>
		</p>
		<br/>
		<p>
		<label><?php echo _paid_by; ?></label><br/>
					<?php getPaidList();?>
		</p>
		<br/>
		<p>
		<label><?php echo _multi_booking; ?></label>
		<div class="date dategroup">
			<div class="text" id="recurring_text"></div>
			<input type="text" name="recurring_date" id="recurring_date"/>
			<input type="hidden" name="recurring_dbdate" id="recurring_dbdate" value="<?php echo $_SESSION['selectedDate']; ?>"/>
		</div>
		<br/>
		<input type="radio" class="radio" name="recurring_span" value="1" checked="checked"/><img src='images/icons/calendar-select-days-span.png' alt='daily' title='Daily'>&nbsp;
		<input type="radio" class="radio" name="recurring_span" value="7"><img src='images/icons/calendar-select-days.png' alt='weekly' title='Weekly'/>
		</p>
	</div></div></div> <!-- end right column -->

	<input type="hidden" name="reservation_outlet_id" value="<?php echo $_SESSION['outletID'];?>"/>
	<input type="hidden" name="reservation_timestamp" value="<?php echo date('Y-m-d H:i:s');?>"/>
	<input type="hidden" name="reservation_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>"/>
	<input type="hidden" name="token" value="<?php echo $token; ?>" />
	<input type="hidden" name="action" value="save_res"/>
 </form><!-- end form -->
 <br class="clear">
</div> <!-- end content wrapper -->
<br/><br/><br/>

<script type="text/javascript">
$(document).ready(function(){
	
	if($("#limit_password")){
	//	$("#submit_btn").hide(); //disable
	}
	
	$("#limit_password").keyup(function() {
	var pwd = $("#limit_password").val();
	if(pwd.length >= 3) {
	  $("#status").html('<img align="absmiddle" src="images/ajax-loader.gif" />');
	
	  $.ajax({
		type: "POST",
		url: "ajax/check_password.php",
		data: "password="+ pwd,
		success: function(msg){
			$("#status").ajaxComplete(function(event, request){
					
					if( msg.length < 4 ){
						$(this).html("&nbsp;<img align='absmiddle' src='images/icons/icon_accept.png' />" + msg);
						//$("#submit_btn").show(); //enabled
					}else{
							$(this).html(msg);
							//$("#submit_btn").hide(); //disable
					}
			});
		}
	  });
	}
  });
	
});
</script>