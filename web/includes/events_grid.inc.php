<!-- Begin example table data -->
<table class="global width-100" cellpadding="0" cellspacing="0">
	<thead>
	    <tr>
			<th><?php echo _date; ?></th>
			<th><?php echo _subject; ?></th>
			<th><?php echo _outlets; ?></th>
			<th><?php echo _open_time; ?></th>
			<th><?php echo _close_time; ?></th>
			<!-- ><th><?php echo _open_to; ?></th> 
			<th><?php echo _contact; ?></th> -->
			<th><?php echo _advertise_start; ?></th>
			<th><?php echo _ticket_price; ?></th>
			<th><?php echo _delete; ?></th>
	    </tr>
	</thead>
	<tbody>
		<?php

		$events =	querySQL('db_propery_events');
		
		if ($events) {
			// remember original session outlet id
			$mem_id = $_SESSION['outletID'];
			foreach($events as $row) {
			$_SESSION['outletID'] = $row->outlet_id;
			echo "<tr id='events-".$row->id."'>";
			echo "<td><span class='bold'>".date($general['dateformat'],strtotime($row->event_date))."</strong></td>
			<td><span class='bold'><a href='?p=6&q=4&btn=3&eventID=".$row->id."'>".$row->subject."</a></strong>
			<a href='main_page.php?p=2&outletID=".$row->outlet_id."&selectedDate=".$row->event_date."' style='margin-left:12px;'>
			<img src='images/icons/arrow.png'/></a>
			</td>
			<td>".querySQL('db_outlet')."</td>
			<td>".formatTime($row->start_time,$general['timeformat'])."</td>
			<td>".formatTime($row->end_time,$general['timeformat'])."</td>";
			//<td>".$row->open_to."</td>
			//<td><small>".$row->contact."</small></td>
			echo "<td>".$row->advertise_start." "._days." "._before."</td>
			<td>".number_format($row->price,2)."</td>
		    <td>
					<a href='#modaldelete' name='events' id='".$row->id."' class='deletebtn'>
					<img src='images/icons/delete_cross.png' alt='"._cancelled."' class='help' title='"._delete."'/>
					</a>
		    	</td>
			</tr>";
			}
			//get back original session outlet id
			$_SESSION['outletID'] = $mem_id;
		}
		?>
	</tbody>
</table>
<!-- End example table data -->