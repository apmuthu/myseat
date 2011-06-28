
<div class="content res-height">
	<label><?php echo _property;?></label>
	<p><span class='bold'>	 	 				 
		<?php echo $row->name;?>
	</strong></p>
	<label><?php echo _name;?></label>
	<p><span class='bold'>
		<?php echo $row->outlet_name;?>
	</strong></p>
	<label><?php echo _cuisine_style;?></label>
	<p>
		<?php echo $cuisines[($row->cuisine_style-1)];?>
	</p>
	<label><?php echo _description;?></label>
	<p>	 	 	 	 	 	 	 
		<?php echo trim(utf8_encode($row->outlet_description));?>
	</p>
	<label><?php echo _description." "._international;?></label>
	<p>	 	 	 	 	 	 	 
		<?php echo trim(utf8_encode($row->outlet_description_en));?>
	</p>	
	<label><?php echo _confirmation_email;?></label>
	<p>
		<?php echo $row->confirmation_email;?>
	</p>
	<label><?php echo _seats;?></label>
	<p>		 	 	 	 	 	 	
		<?php echo $row->outlet_max_capacity;?>
	</p>
	<label><?php echo _tables;?></label>	
	<p>	 	 	 	 	 	 	
		<?php echo $row->outlet_max_tables;?>
	</p>
	<label>Max. <?php echo _passerby;?></label>	
	<p>	 	 	 	 	 	 	
		<?php echo $row->passerby_max_pax;?>
	</p>
	<label><?php echo _duration;?></label>	
	<p>	 	 	 	 	 	 			
		<?php echo $row->avg_duration;?> h
	</p>
	<label><?php echo _webform;?></label>
	<p>		
		<?php echo printOnOff($row->webform);?>
	</p>
	<br/>
	<label>Booking Links</label>
	<p>		
		<?php
		echo "<br/><span class='bold'>".$row->outlet_name." :</span><br/>";
		echo "<input type='text' name='' class='width-450' value=' ".$global_basedir."contactform/index.php?so=ON&prp=".$row->property_id."&outletID=".$row->outlet_id."'/>";
		echo "<br/><span class='bold'>"._property." :</span><br/>";
		echo "<input type='text' name='' class='width-450' value='".$global_basedir."contactform/index.php?prp=".$row->property_id."'/>";
		echo "<br/><span class='bold'>Reclame Box :</span><br/>";
		echo "<textarea class='script-box'>".stripslashes("
<script type='text/javascript'>
	var propertyID = '".$row->property_id."';
	var outletID = '".$row->outlet_id."';
</script>
<script src='".$global_basedir."widget/reclamebox.php'></script>").
		"</textarea>"; 
		?>
	</p>	 	 	 	 	 	 	
</div></div></div> <!-- end left column -->
<!-- Beginn right column -->	
<div class="twocolumn_wrapper right">
	<div class="twocolumn" >
		<div class="content res-height">		 	 	 	 	 	 		 	 	 	 	 	 				 	 	 	 	 	 	 
			<label><?php echo _season_start;?></label>
			<p>		
				<?php echo buildDate($general['dateformat_short'],substr($row->saison_start,2,2),substr($row->saison_start,0,2));?>
			</p>			 	 	 	 	 	 	 
			<label><?php echo _season_end;?></label>	
			<p>		
				<?php echo buildDate($general['dateformat_short'],substr($row->saison_end,2,2),substr($row->saison_end,0,2));?>
			</p>
			<label><?php echo _year;?></label>	
			<p>		
				<?php echo $row->saison_year;?>
			</p>	 	 	 	 	 	 	 
			<br/>
			<label><?php echo _day_off;?></label>
			<p>		
				<?php echo getWeekdays_select($row->outlet_closeday,'disabled'); ?>
			</p>
			<br/>
			<label><?php echo _general." "._open_time." & "._close_time;?></label>
			<p>		 	 	 	 	 	 	
				<?php
					echo formatTime($row->outlet_open_time,$general['timeformat']);
					echo " - "; 	 	 	 	 	 	
					echo formatTime($row->outlet_close_time,$general['timeformat']);
				?>
			</p>		 	 	 	 	 	 	 		 	 	 	 	 	 	 
			<br/>
			<label><?php echo _specific." "._open_time." & "._close_time;?></label>
			<p>	
				<table>
 	 	 	 	 <?php
					$day = strtotime("next Monday");
					for ($i=1; $i <= 7; $i++) { 
						$weekday = date("w",$day);
						$field_open = $weekday.'_open_time';
						$field_close = $weekday.'_close_time';
						echo "<tr><td><div class='bold'>".date("l",$day)."</div></td><td class='padding-left-20'>".
						date('H:i',strtotime($row->$field_open))." - ".date('H:i',strtotime($row->$field_close)).
						"<br/></td></tr>";
						$day = $day + 86400;
					}
 	 	 	 	 ?>	
				</table>
			</p>
			<br/><br/>
			<label><?php echo _break;?></label>
			<p>	
				<table>
 	 	 	 	 <?php
					$day = strtotime("next Monday");
					for ($i=1; $i <= 7; $i++) { 
						$weekday = date("w",$day);
						$field_open = $weekday.'_open_break';
						$field_close = $weekday.'_close_break';
						echo "<tr><td><div class='bold'>".date("l",$day)."</div></td><td class='padding-left-20'>".
						date('H:i',strtotime($row->$field_open))." - ".date('H:i',strtotime($row->$field_close)).
						"<br/></td></tr>";
						$day = $day + 86400;
					}
 	 	 	 	 ?>	
				</table>
			</p>	 	 	 	 	 	 	 	 	 	 	 	 	 	 		 	 	 	 	 	 	 		 	 	 	 	 	 
			<br/><br/>
			<small>				
				<?php echo _created." ".humanize($row->outlet_timestamp);?>
			</small>
</div>