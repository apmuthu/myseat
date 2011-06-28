<!-- Begin reservation table data -->
<table class="global resv-table" cellpadding="0" cellspacing="0">
	<thead>
	    <tr <?php if($waitlist){echo"class='waitlist-header'";} ?>>
	    	<th><?php echo _time; ?></th>
			<th></th>
			<th><?php echo _guest_name; ?></th>
			<th><?php echo _pax; ?></th>
			<th><?php echo _phone_room; ?></th>
			<th><?php echo _type; ?></th>
			<th> 
			<?php
			 	if ($_SESSION['page'] == 1) {
			 		echo _outlets;
			 	}else{
					echo _note;
				} 
			?>
			</th>
			<th class='noprint'><?php echo _author; ?></th>
			<?php
			if($_SESSION['wait'] == 0){
				echo "<th style='width:2%'>"._table."</th>";
			}
			?>
	    	<th style='width:3%'><?php echo _status; ?></th>
			<th class='noprint'></th>
			<th class='noprint' style='width:2%'></th>
	    </tr>
	</thead>
	<tbody>
		<?php
		// Clear reservation variable
		$reservations ='';
		
		if ($_SESSION['page'] == 1) {
			$reservations =	querySQL('all_reservations');
		}else{
			$reservations =	querySQL('reservations');
		}
		
		if ($reservations) {
			
			// reset total counters
			$tablesum = 0;
			$guestsum = 0;
			
			//start printing out reservation grid
			foreach($reservations as $row) {
				// reservation ID
				$id = $row->reservation_id;
				$_SESSION['reservation_guest_name'] = $row->reservation_guest_name;
				// check if reservation is tautologous
				$tautologous = querySQL('tautologous');
				
			echo "<tr id='res-".$id."'>";
			echo "<td";
			// reservation after maitre message
			if ($row->reservation_timestamp > $maitre['maitre_timestamp'] && $maitre['maitre_comment_day']!='') {
				echo " class='tautologous' title='"._sentence_13."' ";
			}
			// daylight coloring
			if ($row->reservation_time > $daylight_evening){
				echo " class='evening' ";
			}else if ($row->reservation_time > $daylight_noon){
				echo " class='afternoon' ";
			}else if ($row->reservation_time < $daylight_noon){
				echo " class='morning' ";
			}
			
			echo "><strong>".formatTime($row->reservation_time,$general['timeformat'])."</strong></td>
			<td>".printTitle($row->reservation_title)."</td>
			<td>
			<strong><a href='?p=102&resID=".$id."'"; 
			// color guest name if tautologous
			if($tautologous>1){echo" class='tautologous tipsy' title='"._tautologous_booking."'";}
			echo ">".$row->reservation_guest_name."</a></strong>";
			if ($row->repeat_id !=0)
	            {
	            //print out recurring symbol
	            echo "&nbsp;<img src='images/icons/arrow-repeat.png' alt='"._recurring.
					 "' title='"._recurring."' class='tipsy' border='0' >";
	            }
				// old reservations symbol
				if( (strtotime($row->reservation_timestamp) + $general['old_days']*86400) <= time() ){
					echo "<img src='images/icons/clock-ex.png' class='help tipsy right-side' title='"._sentence_11."' />";
				}
			echo"</td>
			<td><strong>".$row->reservation_pax."</strong></td>
			<td>".$row->reservation_guest_phone."</td>
			<td>".$row->reservation_hotelguest_yn."</td>
			<td>";
				if ($_SESSION['page'] == 1) {
			 		echo $row->outlet_name;
			 	}else{
					echo $row->reservation_notes;
				}
			echo "</td>
			<td class='noprint'>".$row->reservation_booker_name."</td>";
			if($_SESSION['wait'] == 0){
				echo "<td class='big tb_nr'><div id='reservation_table-".$id."' class='inlineedit'>".$row->reservation_table."</div></td>";
			}
			echo "<td><div class='noprint'>";
				getStatusList($id, $row->reservation_status);
			echo "</div></td>";
			echo "<td class='noprint'>";
			echo "<small>".humanize($row->reservation_timestamp)."</small>";
			echo "</td>";
			echo "<td class='noprint'>";
			// DELETE BUTTON
			if ( current_user_can( 'Reservation-Delete' ) && $q!=3 ){
		    	echo"<a href='#modalsecurity' name='".$row->repeat_id."' id='".$id."' class='delbtn'>
					<img src='images/icons/delete_cross.png' alt='"._cancelled."' class='help' title='"._delete."'/></a>";
			}
			// MOVE BUTTON
			//	echo "<a href=''><img src='images/icons/arrow.png' alt='move' class='help' title='"._move_reservation_to."'/></a>";
			
			// WAITLIST ALLOW BUTTON
			if($_SESSION['wait'] == 1){
				$leftspace = leftSpace(substr($row->reservation_time,0,5), $availability);
				if($leftspace >= $row->reservation_pax && $_SESSION['outlet_max_tables']-$tbl_availability[substr($row->reservation_time,0,5)] >= 1){	    
					echo"&nbsp;<a href='#' name='".$id."' class='alwbtn'><img src='images/icons/icon_accept.png' name='".$id."' alt='"._allow."' class='help' title='"._allow."'/></a>";
				}
			}
		echo"</td></tr>";
		$tablesum ++;
		$guestsum += $row->reservation_pax;
			}
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="bold right-side-text"><?php echo _guest_summary;?></td>
			<td class="bold"><?php echo $guestsum;?></td>
			<td class="bold right-side-text"><?php echo _tables_summary;?></td>
			<td class="bold"><?php echo $tablesum;?></td>
			<td></td>
			<td></td>
			<?php
			if($_SESSION['wait'] == 0){
				echo "<td></td>";
			}
			?>
			<td class="noprint"></td>
			<td class="noprint"></td>
			<td class="noprint"></td>	
		</tr>
	</tfoot>
</table>
<!-- End reservation table data -->