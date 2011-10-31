<?php
if ($_SESSION['button']==2) {
	$row = array();
}else{
	$row = querySQL('event_data_single');	
}

$row['event_date'] = ($row['event_date']) ? $row['event_date'] : date('Y-m-d');
list($sj,$sm,$sd)    = explode("-",$row['event_date']);
$eventdate			 = buildDate($general['dateformat'],$sd,$sm,$sj);
?>
<form method="post" action="?p=6&q=4&btn=1" id="event_form">
	<br/>
	<label><?php echo _date;?></label>
	<p>	
		<div class="date dategroup">
			<div class="text" id="datetext"><?php echo $eventdate; ?></div>
			<input type="text" id="ev_datepicker"/>
			<input type="hidden" name="event_date" id="event_date" value=" <?php echo $row['event_date']; ?> "/>
	    </div>
	</p>	
	<br style='clear:both'><br/>
	<label><?php echo _outlets;?></label>
	<p>
		<div>
			<div class="text"></div>
				<?php getOutletList($row['outlet_id'],'enabled'); ?>
		</div>
	</p>
	<br/>
	<label><?php echo _advertise_start;?></label>
	<p>
		<div >
			<div class='text'></div>
			<select name='advertise_start' id='advertise_start' size='1'>
				<?php
				//set role
				$range = ($row['advertise_start']) ? $row['advertise_start'] : 30;

				for($i = 0, $size = sizeof($adv_range); $i < $size; ++$i)
				{
						//build dropdown
							echo "<option value='".$adv_range[$i]."' ";
							echo ($adv_range[$i]==$range) ? "selected='selected'" : "";
							echo ">".$adv_range[$i]." "._days." "._before."</option>\n";
				}
				?>
			</select>
		</div>
	</p>
	<br/>	
	<label><?php echo _subject;?></label>
	<p>
		<input type="text" name="subject" id="subject" class="required width-97" minlength="6" title=' ' value="<?php echo $row['subject'];?>"/>
	</p>
	<label><?php echo _description;?></label>
	<p>
		<div class="onecolumn">
		<textarea name="description" id="wysiwyg" style="width: 97%;" cols="35" rows="5" class="wysiwyg required" minlength="6" title=' '><?php echo $row['description'];?></textarea>
		</div>
	</p>
	<!--
	<label><?php echo _contact;?></label>
	<p>
		<input type="text" name="contact" id="contact" class="required" title=' ' style='width: 57%;' value="<?php echo $row['contact'];?>"/>
	</p>
	<label><?php echo _open_to;?></label>
	<p>
		<input type="text" name="open_to" id="open_to" class="required" title=' ' value="<?php echo $row['open_to'];?>"/>
	</p>
	-->
	<br/>
	<label><?php echo _ticket_price;?></label>
	<p>
		<input type="text" name="price" id="price" class="required" title=' ' value="<?php echo $row['price'];?>"/>
	</p>
	<label><?php echo _open_time;?></label>
	<p>		 	 	 	 	 	 	
			<?php getTimeList($general['timeformat'], $general['timeintervall'],'start_time',$row['start_time']);?>
	</p>
	<label><?php echo _close_time;?></label>	
	<p>	 	 	 	 	 	 			
			<?php getTimeList($general['timeformat'], $general['timeintervall'],'end_time',$row['end_time']);?>
	</p>

	<?php if ($_SESSION['button']!=2): ?> 
		<br/>	 	 	 	 	 	 
		<small>				
			<?php echo _created." ".humanize($row['created_at']);?>
		</small>
	<?php endif ?>
			<input type="hidden" name="id" value="<?php echo $row['id'];?>">
			<input type="hidden" name="eventID" value="<?php echo $row['eventID'];?>">
			<input type="hidden" name="property_id" value="<?php echo $_SESSION['property'];?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_evnt">
	<br/>
	<div class="center">
		<input type="submit" class="button_dark" value="<?php echo _save;?>">
	</div>
</form>
</div>