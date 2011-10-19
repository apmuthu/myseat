
<div class="twocolumn_wrapper" >
 <div class="twocolumn form-height">
  <div class="content detailbig content-height">
  <form method="post" action="ajax/process_reservation.php" id="new_reservation_form">
	<br/>
			<label><?php echo _booknum;?></label>
			<p><span class='bold'><?php echo $row->reservation_bookingnumber; ?></strong></p>
			<br/>
			<label><?php echo _outlets;?></label>
			<p><?php echo $row->outlet_name; ?></p>
			<label><?php echo _move_reservation_to;?></label>
			<p>
						<?php outletList($_SESSION['outletID'],'enabled','reservation_outlet_id'); ?>
			</p>
			<br/>
			<label><?php echo _date;?></label>
			<p>
				<div class="date dategroup">
					<div class="text" id="datetext"><?php echo date($general['datepickerformat'],strtotime($row->reservation_date));?></div>
					<input type="text" id="ev_datepicker"/>
					<input type="hidden" name="reservation_date" id="event_date" value="<?php echo date('Y-m-d',strtotime($row->reservation_date));?>"/>
			    </div>
			</p>
			<br/><br/>
			<label><?php echo _time; ?></label>
			<p>
				<?php getTimeList($general['timeformat'], $general['timeintervall'],'reservation_time',$row->reservation_time,$_SESSION['selOutlet']['outlet_open_time'],$_SESSION['selOutlet']['outlet_close_time'],0,'class="required"');?>
			</p>
			<label><?php echo _title; ?></label>
			<p>
				<?php getTitleList($row->reservation_title);?>
			</p>
			<label><?php echo _guest_name; ?></label>
			<p>
				<span class='bold'>
				<input type="text" name="reservation_guest_name" id="reservation_guest_name" class="required width-250" title=' ' minlength="3" value="<?php echo $_SESSION[reservation_guest_name];?>"/>
</strong>
			</p>
			<label><?php echo _pax; ?></label>
			<p>
				<input type="text" name="reservation_pax" id="reservation_pax" class="required digits width-50" title=' ' value='<?php echo $row->reservation_pax; ?>'/>
			</p>
		    <label><?php echo _type; ?></label>
			<p>
					<?php echo getTypeList($row->reservation_hotelguest_yn,'enabled');?>
		    </p>
			<label><?php echo _phone_room; ?></label>
			<p>
				<input type="text" name="reservation_guest_phone" id="reservation_guest_phone" value='<?php echo $row->reservation_guest_phone; ?>' />
			</p>
			<label><?php echo _note; ?></label>
			<p>
				<textarea name="reservation_notes" id="reservation_notes" rows="5" cols="35" class="width-97"><?php echo trim($row->reservation_notes); ?></textarea>
			</p>
			<label><?php echo _author; ?></label>
			<p>
				<input type="text" name="reservation_booker_name" id="reservation_booker_name" class='required' minlength="3"  maxlength="30" title=' ' value='' />
			</p>
			<br/>
				<input type="submit" class="button_dark" value="<?php echo _save;?>"/></a>
			<br class="clear">
		</div></div></div> <!-- end left column -->

	<!-- Begin right column -->	
		<div class="twocolumn_wrapper right">
		 <div class="twocolumn form-height">
		  <div class="content detailbig content-height">
			<br/>
			<label><?php echo _adress; ?></label>
			<p>
					<input type="text" name="reservation_guest_adress" id="reservation_guest_adress" value='<?php echo $row->reservation_guest_adress; ?>' />
			</p>
			<label><?php echo _area_code; ?></label>
			<p>
				<input type="text" name="reservation_guest_city" id="reservation_guest_city" value='<?php echo $row->reservation_guest_city; ?>' />
			</p>
			<label><?php echo _email; ?></label>
			<p>
				<input type="text" name="reservation_guest_email" id="reservation_guest_email" class="width-250" value='<?php echo $row->reservation_guest_email; ?>'/>
			</p>
			<label><?php echo _discount; ?></label>
			<p>
				<input name="reservation_discount" name="reservation_discount" id="reservation_discount" maxlength="3" class="width-50" value='<?php echo $row->reservation_discount; ?>' />
			</p>
			<label><?php echo _parking; ?></label>
			<p>
				<input name="reservation_parkticket" name="reservation_parkticket" id="reservation_parkticket" maxlength="3" class="width-50" value='<?php echo $row->reservation_parkticket; ?>' />
			</p>
			<!-- <label><?php echo _Tisch; ?></label>
				 <p>
					<?php echo $row->reservation_table; ?>
				 </p>
			-->

			<label><?php echo _payment; ?></label>
			<p>
				<span class="width-250">
				<?php
				echo _paid."<br>";
				echo'<input type="checkbox" name="reservation_bill_paid" value="';
				if ($reservation_bill_paid!=""){echo $reservation_bill_paid;} else {echo date('d.m.Y');} 
				echo'" >&nbsp;&nbsp;';
				echo "<br>"._shipped."<br>";
				echo'<input type="checkbox" name="reservation_billet_sent" value="';
				if ($reservation_billet_sent!=""){echo $reservation_billet_sent;} else {echo date('d.m.Y');}
				echo '" >'; 
				?>
				</span>
			</p>
			<label><?php echo _paid_by; ?></label>
			<p>
				<?php getPaidList($row->reservation_bill);?>
			</p>
			<label><?php echo _created; ?></label>
			<p><small>
				<?php echo humanize($row->reservation_timestamp);?>
			</small></p>
			<br/>
			<input type="hidden" name="reservation_id" value="<?php echo $_SESSION['resID'];?>">
			<!-- <input type="hidden" name="reservation_date" value="<?php echo $row->reservation_date;?>"> -->
			<input type="hidden" name="old_outlet_id" value="<?php echo $row->reservation_outlet_id;?>">
			<input type="hidden" name="reservation_outlet_id" value="<?php echo $_SESSION['outletID'];?>">
			<input type="hidden" name="reservation_bookingnumber" value="<?php echo $row->reservation_bookingnumber;?>">
			<input type="hidden" name="repeat_id" value="<?php echo $row->repeat_id;?>">
			<input type="hidden" name="email_type" value="no">
			<input type="hidden" name="reservation_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_res">
			</form><!-- end form -->
		</div></div></div> <!-- end right column -->

