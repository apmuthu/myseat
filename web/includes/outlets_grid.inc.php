<!-- Begin example table data -->
<table class="global width-100" cellpadding="0" cellspacing="0">
	<thead>
	    <tr>
			<th>ID</th>
			<th><?php echo _name; ?></th>
			<th><?php echo _seats; ?></th>
			<th><?php echo _tables; ?></th>
			<th><?php echo _open_time; ?></th>
			<th><?php echo _duration; ?></th>
			<th><?php echo _season_start; ?></th>
			<th><?php echo _year; ?></th>
			<th><?php echo _cuisine_style; ?></th>
	    	<th><?php echo _webform; ?></th>
			<th><?php echo _delete; ?></th>
	    </tr>
	</thead>
	<tbody>
		<?php
			if($_SESSION['button'] == 1){
				$outlets =	querySQL('db_all_outlets');
			}else if($_SESSION['button'] == 3){
				$outlets =	querySQL('db_all_outlets_old');
			}
		
		if ($outlets) {
			foreach($outlets as $row) {
			$pr_year = ($row->saison_year==0) ? '&nbsp;' : $row->saison_year;
			
			echo "<tr id='outlet-".$row->outlet_id."'>";
			echo "<td>".$row->outlet_id."</td>
			<td><span class='bold'><a href='?p=101&outletID=".$row->outlet_id."'>".$row->outlet_name."</a></strong></td>
			<td><span class='bold'>".$row->outlet_max_capacity."</strong></td>
			<td><span class='bold'>".$row->outlet_max_tables."</strong></td>
			<td>".formatTime($row->outlet_open_time,$general['timeformat'])." - ".
			formatTime($row->outlet_close_time,$general['timeformat'])."</td>
			<td>".$row->avg_duration."</td>
			<td>".buildDate($general['dateformat_short'],substr($row->saison_start,2,2),substr($row->saison_start,0,2))." - ".
			buildDate($general['dateformat_short'],substr($row->saison_end,2,2),substr($row->saison_end,0,2))."</td>
			<td>".$pr_year."</td>
			<td><small>".$cuisines[($row->cuisine_style-1)]."</small></td>
			<td>".printOnOff($row->webform)."</td>
		    <td>
					<a href='#modaldelete' name='outlets' id='".$row->outlet_id."' class='deletebtn'>
					<img src='images/icons/delete_cross.png' alt='"._cancelled."' class='help' title='"._delete."'/>
					</a>
		    	</td>
			</tr>";
			}
		}
		?>
	</tbody>
</table>
<!-- End example table data -->