<?
if ($_SESSION['button']==2) {
	$row = "";
}
?>
<div class="content form-height">
<form method="post" action="?p=6" id="edit_outlet_form">
	<label><?= _property;?></label>
	<p><span class='bold'>	 	 				 
		<?= querySQL('db_property');?>
	</strong></p>
	<label><?= _name;?></label>
	<p>
		<input type="text" name="outlet_name" id="outlet_name" class="required" title=' ' value="<?= $row->outlet_name;?>"/>
	</p>
	<label><?= _cuisine_style;?></label>
	<p>
  		<?= cuisineDropdown($cuisines,$row->cuisine_style);?>
	</p>
	<br/>
	<label><?= _description;?></label>
	<p>
		<textarea name="outlet_description" id="outlet_description" rows="5" cols="35" class="required width-97" title=' '><?= trim($row->outlet_description);?></textarea>
	</p>
	<br/>
	<label><?= _description." "._international;?></label>
	<p>
		<textarea name="outlet_description_en" id="outlet_description_en" rows="5" cols="35" class="required width-97" title=' '><?= trim($row->outlet_description_en);?></textarea>
	</p>	
	<label><?= _confirmation_email;?></label>
	<p>
		<input type="text" name="confirmation_email" id="confirmation_email" class="required email" title=' ' value="<?= $row->confirmation_email;?>"/>
	</p>
	<label><?= _seats;?></label>
	<p>		 	 	 	 	 	 	
		<input type="text" name="outlet_max_capacity" id="outlet_max_capacity" class="required digits" title=' ' value="<?= $row->outlet_max_capacity;?>"/>
	</p>
	<label><?= _tables;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="outlet_max_tables" id="outlet_max_tables" class="required digits" title=' ' value="<?= $row->outlet_max_tables;?>"/>
	</p>
	<label>Max. <?= _passerby;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="passerby_max_pax" id="passerby_max_pax" class="digits" title=' ' value="<?= $row->passerby_max_pax;?>"/>
	</p>
	<label><?= _duration;?></label>	
	<p>	 	 	 	 	 	 			
			<? getDurationList($general['timeintervall'],'avg_duration',$row->avg_duration);?>
	</p>
	<label><?= _webform;?></label>
	<p>		
		<?= printOnOff($row->webform,'webform','');?>
	</p>
	<br/><br/>		 	 	 	 	 	 		 	 	 	 	 	 				 	 	 	 	 	 	 
	<br class="clear">
		<input type="submit" class="button_dark" value="<?= _save;?>"/>		 	 	 	 	 	 	 			 	 	 	 	 	 	
</div></div></div> <!-- end left column -->
<!-- Beginn right column -->	
<div class="twocolumn_wrapper right">
	<div class="twocolumn" >
		<div class="content form-height">
			<label><?= _season_start;?></label>
			<p>		
				<?
				// buildDate($general['dateformat_short'],substr($row->saison_start,2,2),substr($row->saison_start,0,2));
				echo monthDropdown('saison_start_month',substr($row->saison_start,0,2));
				echo " "; 
				echo dayDropdown('saison_start_day',substr($row->saison_start,2,2));
				?>
			</p>			 	 	 	 	 	 	 
			<label><?= _season_end;?></label>	
			<p>		
				<?
				// buildDate($general['dateformat_short'],substr($row->saison_end,2,2),substr($row->saison_end,0,2));
				echo monthDropdown('saison_end_month',substr($row->saison_end,0,2));
				echo " ";
				echo dayDropdown('saison_end_day',substr($row->saison_end,2,2));
				?>
			</p>
			<label><?= _year;?></label>	
			<p>
					<?= yearDropdown('saison_year',$row->saison_year); ?>
			</p>
			<br/>
			<label><?= _day_off;?></label>
			<p>	
				<? echo getWeekdays_select($row->outlet_closeday); ?>	
			</p>
			<br/>
			<label><?= _general." "._open_time." & "._close_time;?></label>
			<p>		 	 	 	 	 	 	
				<? getTimeList($general['timeformat'], $general['timeintervall'],'outlet_open_time',$row->outlet_open_time);
				   echo " ";
	 	 	 	   getTimeList($general['timeformat'], $general['timeintervall'],'outlet_close_time',$row->outlet_close_time);
				?>
			</p>
			<br/>
			<label><?= _specific." "._open_time." & "._close_time;?></label>
			<p>	
				<table class='opentime-table'>
 	 	 	 	 <?
					$day = strtotime("next Monday");
					for ($i=1; $i <= 7; $i++) { 
						$weekday = date("w",$day);
						$field_open = $weekday.'_open_time';
						$field_close = $weekday.'_close_time';
						echo "<tr><td>".date("l",$day)."&nbsp;</td><td>";
						getTimeList($general['timeformat'], $general['timeintervall'],$field_open,$row->$field_open);
						echo " ";
						getTimeList($general['timeformat'], $general['timeintervall'],$field_close,$row->$field_close);
						echo "<br/></td></tr>";
						$day = $day + 86400;
					}
 	 	 	 	 ?>	
				</table>
			</p>
			<br/>
			<label><?= _break;?></label>
			<p>	
				<table class='opentime-table'>
 	 	 	 	 <?
					$day = strtotime("next Monday");
					for ($i=1; $i <= 7; $i++) { 
						$weekday = date("w",$day);
						$field_open = $weekday.'_open_break';
						$field_close = $weekday.'_close_break';
						echo "<tr><td>".date("l",$day)."&nbsp;</td><td>";
						getTimeList($general['timeformat'], $general['timeintervall'],$field_open,$row->$field_open);
						echo " ";
						getTimeList($general['timeformat'], $general['timeintervall'],$field_close,$row->$field_close);
						echo "<br/></td></tr>";
						$day = $day + 86400;
					}
 	 	 	 	 ?>	
				</table>
			</p>	 	 	 	 	 	 	 	 	 	 	 	 	 	 		 	 	 	 	 	 	 	
			<br/><br/>
			<?php if ($_SESSION['button']!=2): ?> 	 	 	 	 	 	 
				<small>				
					<?= _created." ".humanize($row->outlet_timestamp);?>
				</small>
			<?php endif ?>	
			<input type="hidden" name="outlet_id" value="<?= $row->outlet_id;?>">
			<input type="hidden" name="property_id" value="<?= $_SESSION['property'];?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_out">
</form>
</div>