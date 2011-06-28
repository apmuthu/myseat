<div class="twocolumn_wrapper">
 <div class="twocolumn form-height">
  <div class="content detailbig content-height">
	<br/>
	<label><?php echo _booknum;?></label>
	<p><span class='bold'><?php echo $row->reservation_bookingnumber; ?></strong></p>
	<br/>
	<label><?php echo _outlets;?></label>
	<p><?php echo $row->outlet_name; ?></p>
	<br/>
	<label><?php echo _date;?></label>
	<p>
		<?php echo date($general['dateformat'],strtotime($row->reservation_date));?>
	</p>
			<label><?php echo _time; ?></label>
			<p>
				<?php echo formatTime($row->reservation_time,$general['timeformat']); ?>
			</p>
			<label><?php echo _title; ?></label>
			<p>
				<?php getTitleList($row->reservation_title,'disabled');?>
			</p>
			<label><?php echo _guest_name; ?></label>
			<p>
				<span class='bold'>
				<?php
				$_SESSION['reservation_guest_name'] = $row->reservation_guest_name;
				echo $_SESSION['reservation_guest_name']; 
				?></strong>
			</p>
			<label><?php echo _pax; ?></label>
			<p>
				<?php echo $row->reservation_pax; ?>
			</p>
		    <label><?php echo _type; ?></label>
			<p>
					<?php echo getTypeList($row->reservation_hotelguest_yn,'disabled');?>
		    </p>
			<label><?php echo _phone_room; ?></label>
			<p>
				<?php echo $row->reservation_guest_phone; ?>
			</p>
			<label><?php echo _note; ?></label>
			<p>
				<?php echo $row->reservation_notes; ?>
			</p>
			<label><?php echo _author; ?></label>
			<p>
				<?php echo $row->reservation_booker_name; ?>
			</p>
			<br/>
		</div></div></div> <!-- end left column -->

	<!-- Beginn right column -->	
		<div class="twocolumn_wrapper right">
		 <div class="twocolumn form-height">
		  <div class="content detailbig content-height">
			<br/>
			<label><?php echo _adress; ?></label>
			<p>
				<?php echo $row->reservation_guest_adress; ?>
			</p>
			<label><?php echo _area_code; ?></label>
			<p>
				<?php echo $row->reservation_guest_city; ?>
			</p>
			<label><?php echo _email; ?></label>
			<p>
				<?php
					echo $row->reservation_guest_email; 
					if ( $row->reservation_advertise =='YES' ) {
						echo"<img src='images/icons/mail_yes.png' class='mail-icon' title='Advertise allowed'/>";
					}else{
						echo"<img src='images/icons/mail_no.png'  class='mail-icon' title='No advertise'/>";	
					}
				?>
			</p>
			<label><?php echo _discount; ?></label>
			<p>
				<?php echo $row->reservation_discount; ?>
			</p>
			<label><?php echo _parking; ?></label>
			<p>
				<?php echo $row->reservation_parkticket; ?>
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
				$paid = ($row->reservation_bill_paid) ? 1 : 0;
				echo printOnOff($paid,'reservation_bill_paid')."&nbsp;&nbsp;";
				if($paid){
					humanize($row->reservation_bill_paid);
				} 
				echo "<br>"._shipped."<br>";
				$paid = ($row->reservation_billet_sent) ? 1 : 0;
				echo printOnOff($paid,'reservation_billet_sent')."&nbsp;&nbsp;";
				if($paid){
					humanize($row->reservation_billet_sent);
				} 
				?>
				</span>
			</p>
			<label><?php echo _paid_by; ?></label>
			<p>
				<?php getPaidList($row->reservation_bill,'disabled');?>
			</p>
			<label><?php echo _multi_booking; ?></label>
			<p>
				<?php echo $row->start_date;?>
			</p>
			<p>
				<?php echo $row->end_date;?>
			</p>
			<br/>
			<label><?php echo _created; ?></label>
			<p><small>
				<?php echo humanize($row->reservation_timestamp);?>
			</small></p>
			<br/>
		</div></div></div> <!-- end right column -->
</div>