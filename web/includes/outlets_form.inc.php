<?php
if ($_SESSION['button']==2) {
	$row = "";
}
?>
<div class="content form-height">
<form method="post" action="?p=6" id="edit_outlet_form">
	<label><?php echo _property;?></label>
	<p><span class='bold'>	 	 				 
		<?php echo querySQL('db_property');?>
	</strong></p>
	<label><?php echo _name;?></label>
	<p>
		<input type="text" name="outlet_name" id="outlet_name" class="required" title=' ' value="<?php echo $row->outlet_name;?>"/>
	</p>
	<label><?php echo _cuisine_style;?></label>
	<p>
  		<?php echo cuisineDropdown($cuisines,$row->cuisine_style);?>
	</p>
	<br/>
	<label><?php echo _description;?></label>
	<p>
		<textarea name="outlet_description" id="outlet_description" rows="5" cols="35" class="required width-97" title=' '><?php echo trim($row->outlet_description);?></textarea>
	</p>
	<br/>
	<label><?php echo _description." "._international;?></label>
	<p>
		<textarea name="outlet_description_en" id="outlet_description_en" rows="5" cols="35" class="required width-97" title=' '><?php echo trim($row->outlet_description_en);?></textarea>
	</p>	
	<label><?php echo _confirmation_email;?></label>
	<p>
		<input type="text" name="confirmation_email" id="confirmation_email" class="required email" title=' ' value="<?php echo $row->confirmation_email;?>"/>
	</p>
	<label><?php echo _seats;?></label>
	<p>		 	 	 	 	 	 	
		<input type="text" name="outlet_max_capacity" id="outlet_max_capacity" class="required digits" title=' ' value="<?php echo $row->outlet_max_capacity;?>"/>
	</p>
	<label><?php echo _tables;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="outlet_max_tables" id="outlet_max_tables" class="required digits" title=' ' value="<?php echo $row->outlet_max_tables;?>"/>
	</p>
	<label>Max. <?php echo _passerby;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="passerby_max_pax" id="passerby_max_pax" class="digits" title=' ' value="<?php echo $row->passerby_max_pax;?>"/>
	</p>
	<label><?php echo _duration;?></label>	
	<p>	 	 	 	 	 	 			
			<?php getDurationList($general['timeintervall'],'avg_duration',$row->avg_duration);?>
	</p>
	<label><?php echo _password;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="limit_password" id="limit_password" title=' ' value="<?php echo $row->limit_password;?>"/>
	</p>
	<label><?php echo _webform;?></label>
	<p>		
		<?php echo printOnOff($row->webform,'webform','');?>
	</p>
	<br/><br/>		 	 	 	 	 	 		 	 	 	 	 	 				 	 	 	 	 	 	 
	<br class="clear">
		<input type="submit" class="button_dark" value="<?php echo _save;?>"/>		 	 	 	 	 	 	 			 	 	 	 	 	 	
</div></div></div> <!-- end left column -->
<!-- Beginn right column -->	
<div class="twocolumn_wrapper right">
	<div class="twocolumn" >
		<div class="content form-height">
			<label><?php echo _season_start;?></label>
			<p>		
				<?php
				// buildDate($general['dateformat_short'],substr($row->saison_start,2,2),substr($row->saison_start,0,2));
				echo monthDropdown('saison_start_month',substr($row->saison_start,0,2));
				echo " "; 
				echo dayDropdown('saison_start_day',substr($row->saison_start,2,2));
				?>
			</p>			 	 	 	 	 	 	 
			<label><?php echo _season_end;?></label>	
			<p>		
				<?php
				// buildDate($general['dateformat_short'],substr($row->saison_end,2,2),substr($row->saison_end,0,2));
				echo monthDropdown('saison_end_month',substr($row->saison_end,0,2));
				echo " ";
				echo dayDropdown('saison_end_day',substr($row->saison_end,2,2));
				?>
			</p>
			<label><?php echo _year;?></label>	
			<p>
					<?php echo yearDropdown('saison_year',$row->saison_year); ?>
			</p>
			<br/>
			<label><?php echo _day_off;?></label>
			<p>	
				<?php echo getWeekdays_select($row->outlet_closeday); ?>	
			</p>
			<br/>
			<label><?php echo _general." "._open_time." & "._close_time;?></label>
			<p>		 	 	 	 	 	 	
				<?php 
					getTimeList($general['timeformat'], $general['timeintervall'],'outlet_open_time',$row->outlet_open_time);
				   	echo " ";
	 	 	 	   	getTimeList($general['timeformat'], $general['timeintervall'],'outlet_close_time',$row->outlet_close_time);
				?>
			</p>
			<br/>
			<label><?php echo _specific." "._open_time." & "._close_time;?></label>
			<p>	
				<table class='opentime-table'>
 	 	 	 	 <?php
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
			<label><?php echo _break;?></label>
			<p>	
				<table class='opentime-table'>
 	 	 	 	 <?php
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
					<?php echo _created." ".humanize($row->outlet_timestamp);?>
				</small>
			<?php endif ?>	
			<input type="hidden" name="outlet_id" value="<?php echo $row->outlet_id;?>">
			<input type="hidden" name="property_id" value="<?php echo $_SESSION['property'];?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_out">
</form>
</div>