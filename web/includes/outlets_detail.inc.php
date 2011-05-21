
<div class="content res-height">
	<label><?= _property;?></label>
	<p><span class='bold'>	 	 				 
		<?= $row->name;?>
	</strong></p>
	<label><?= _name;?></label>
	<p><span class='bold'>
		<?= $row->outlet_name;?>
	</strong></p>
	<label><?= _cuisine_style;?></label>
	<p>
		<?= $cuisines[($row->cuisine_style-1)];?>
	</p>
	<label><?= _description;?></label>
	<p>	 	 	 	 	 	 	 
		<?= trim(utf8_encode($row->outlet_description));?>
	</p>
	<label><?= _description." "._international;?></label>
	<p>	 	 	 	 	 	 	 
		<?= trim(utf8_encode($row->outlet_description_en));?>
	</p>	
	<label><?= _confirmation_email;?></label>
	<p>
		<?= $row->confirmation_email;?>
	</p>
	<label><?= _seats;?></label>
	<p>		 	 	 	 	 	 	
		<?= $row->outlet_max_capacity;?>
	</p>
	<label><?= _tables;?></label>	
	<p>	 	 	 	 	 	 	
		<?= $row->outlet_max_tables;?>
	</p>
	<label>Max. <?= _passerby;?></label>	
	<p>	 	 	 	 	 	 	
		<?= $row->passerby_max_pax;?>
	</p>
	<label><?= _duration;?></label>	
	<p>	 	 	 	 	 	 			
		<?= $row->avg_duration;?> h
	</p>
	<label><?= _webform;?></label>
	<p>		
		<?= printOnOff($row->webform);?>
	</p>
	<br/>
	<label>Booking Links</label>
	<p>		
		<?
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
			<label><?= _season_start;?></label>
			<p>		
				<?= buildDate($general['dateformat_short'],substr($row->saison_start,2,2),substr($row->saison_start,0,2));?>
			</p>			 	 	 	 	 	 	 
			<label><?= _season_end;?></label>	
			<p>		
				<?= buildDate($general['dateformat_short'],substr($row->saison_end,2,2),substr($row->saison_end,0,2));?>
			</p>
			<label><?= _year;?></label>	
			<p>		
				<?= $row->saison_year;?>
			</p>	 	 	 	 	 	 	 
			<br/>
			<label><?= _day_off;?></label>
			<p>		
				<? echo getWeekdays_select($row->outlet_closeday,'disabled'); ?>
			</p>
			<br/>
			<label><?= _general." "._open_time." & "._close_time;?></label>
			<p>		 	 	 	 	 	 	
				<? 
					echo formatTime($row->outlet_open_time,$general['timeformat']);
					echo " - "; 	 	 	 	 	 	
					echo formatTime($row->outlet_close_time,$general['timeformat']);
				?>
			</p>		 	 	 	 	 	 	 		 	 	 	 	 	 	 
			<br/>
			<label><?= _specific." "._open_time." & "._close_time;?></label>
			<p>	
				<table>
 	 	 	 	 <?
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
			<label><?= _break;?></label>
			<p>	
				<table>
 	 	 	 	 <?
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
				<?= _created." ".humanize($row->outlet_timestamp);?>
			</small>
</div>