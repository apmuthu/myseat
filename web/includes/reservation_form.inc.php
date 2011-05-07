
<div class="twocolumn_wrapper" >
 <div class="twocolumn form-height">
  <div class="content detailbig content-height">
  <form method="post" action="ajax/process_reservation.php" id="new_reservation_form">
	<br/>
			<label><?= _booknum;?></label>
			<p><span class='bold'><?= $row->reservation_bookingnumber; ?></strong></p>
			<br/>
			<label><?= _outlets;?></label>
			<p><?= $row->outlet_name; ?></p>
			<label><?= _move_reservation_to;?></label>
			<p>
						<? outletList($_SESSION['outletID'],'enabled','reservation_outlet_id'); ?>
			</p>
			<br/>
			<label><?= _date;?></label>
			<p>
				<div class="date dategroup">
					<div class="text" id="datetext"><?= date($general['datepickerformat'],strtotime($row->reservation_date));?></div>
					<input type="text" id="ev_datepicker"/>
					<input type="hidden" name="reservation_date" id="event_date" value="<?= date('Y-m-d',strtotime($row->reservation_date));?>"/>
			    </div>
			</p>
			<br/><br/>
			<label><?= _time; ?></label>
			<p>
				<? getTimeList($general['timeformat'], $general['timeintervall'],'reservation_time',$row->reservation_time,$_SESSION['selOutlet']['outlet_open_time'],$_SESSION['selOutlet']['outlet_close_time'],0,'class="required"');?>
			</p>
			<label><?= _title; ?></label>
			<p>
				<? getTitleList($row->reservation_title);?>
			</p>
			<label><?= _guest_name; ?></label>
			<p>
				<span class='bold'>
				<input type="text" name="reservation_guest_name" id="reservation_guest_name" class="required widht-250" title=' ' minlength="3" value="<?= $_SESSION[reservation_guest_name];?>"/>
</strong>
			</p>
			<label><?= _pax; ?></label>
			<p>
				<input type="text" name="reservation_pax" id="reservation_pax" class="required digits width-50" title=' ' value='<?= $row->reservation_pax; ?>'/>
			</p>
		    <label><?= _type; ?></label>
			<p>
					<?= getTypeList($row->reservation_hotelguest_yn,'enabled');?>
		    </p>
			<label><?= _phone_room; ?></label>
			<p>
				<input type="text" name="reservation_guest_phone" id="reservation_guest_phone" value='<?= $row->reservation_guest_phone; ?>' />
			</p>
			<label><?= _note; ?></label>
			<p>
				<textarea name="reservation_notes" id="reservation_notes" rows="5" cols="35" class="width-97"><?= trim($row->reservation_notes); ?></textarea>
			</p>
			<label><?= _author; ?></label>
			<p>
				<input type="text" name="reservation_booker_name" id="reservation_booker_name" class='required' minlength="3"  maxlength="30" title=' ' value='' />
			</p>
			<br/>
				<input type="submit" class="button_dark" value="<?= _save;?>"/></a>
			<br class="clear">
		</div></div></div> <!-- end left column -->

	<!-- Beginn right column -->	
		<div class="twocolumn_wrapper right">
		 <div class="twocolumn form-height">
		  <div class="content detailbig content-height">
			<br/>
			<label><?= _adress; ?></label>
			<p>
					<input type="text" name="reservation_guest_adress" id="reservation_guest_adress" value='<?= $row->reservation_guest_adress; ?>' />
			</p>
			<label><?= _area_code; ?></label>
			<p>
				<input type="text" name="reservation_guest_city" id="reservation_guest_city" value='<?= $row->reservation_guest_city; ?>' />
			</p>
			<label><?= _email; ?></label>
			<p>
				<input type="text" name="reservation_guest_email" id="reservation_guest_email" class="width-250" value='<?= $row->reservation_guest_email; ?>'/>
			</p>
			<label><?= _discount; ?></label>
			<p>
				<input name="reservation_discount" name="reservation_discount" id="reservation_discount" maxlength="3" class="width-50" value='<?= $row->reservation_discount; ?>' />
			</p>
			<label><?= _parking; ?></label>
			<p>
				<input name="reservation_parkticket" name="reservation_parkticket" id="reservation_parkticket" maxlength="3" class="width-50" value='<?= $row->reservation_parkticket; ?>' />
			</p>
			<!-- <label><?= _Tisch; ?></label>
				 <p>
					<?= $row->reservation_table; ?>
				 </p>
			-->

			<label><?= _payment; ?></label>
			<p>
				<span class="width-250">
				<?
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
			<label><?= _paid_by; ?></label>
			<p>
				<? getPaidList($row->reservation_bill);?>
			</p>
			<label><?= _created; ?></label>
			<p><small>
				<?=  humanize($row->reservation_timestamp);?>
			</small></p>
			<br/>
			<input type="hidden" name="reservation_id" value="<?= $_SESSION['resID'];?>">
			<!-- <input type="hidden" name="reservation_date" value="<?= $row->reservation_date;?>"> -->
			<input type="hidden" name="old_outlet_id" value="<?= $row->reservation_outlet_id;?>">
			<input type="hidden" name="reservation_outlet_id" value="<?= $_SESSION['outletID'];?>">
			<input type="hidden" name="reservation_bookingnumber" value="<?= $row->reservation_bookingnumber;?>">
			<input type="hidden" name="repeat_id" value="<?= $row->repeat_id;?>">
			<input type="hidden" name="email_type" value="no">
			<input type="hidden" name="reservation_ip" value="<?= $_SERVER['REMOTE_ADDR'];?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_res">
			</form><!-- end form -->
		</div></div></div> <!-- end right column -->

<script type="text/javascript" charset="utf-8">

	jQuery(document).ready(function($) {
		$("#reservation_outlet_id").change(function(){
		    window.location.href='?resedit=1&outletID=' + this.value;
		  });
	});

</script>

