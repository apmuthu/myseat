<!-- Begin reservation table data -->
<br/>
<table class="global resv-table-small" cellpadding="0" cellspacing="0">
<!--
	<thead>
	    <tr <?php if($waitlist){echo"class='waitlist-header'";} ?>>
	    	<th><?php echo _time; ?></th>
			<th><?php echo _pax; ?></th>
			<th style='width:20%'><?php echo _guest_name; ?></th>
			<th style='width:20%'> 
			<?php
			 	if ($_SESSION['page'] == 1) {
			 		echo _outlets;
			 	}else{
					echo _note;
				} 
			?>
			</th>
			<?php
			if($_SESSION['wait'] == 0){
				echo "<th>"._table."</th>";
			}
			?>
	    	<th><?php echo _status; ?></th>
			<th class='noprint'><?php echo _author; ?></th>
			<th class='noprint'></th>
	    </tr>
	</thead>
-->	
	<tbody>
		<tr></tr>
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
			// daylight coloring
			if ($row->reservation_time > $daylight_evening){
				echo " class='evening noprint'";
			}else if ($row->reservation_time > $daylight_noon){
				echo " class='afternoon noprint'";
			}else if ($row->reservation_time < $daylight_noon){
				echo " class='morning noprint'";
			}
			
			echo " style='width:10px !important; padding:0px;'>&nbsp;</td>";
			echo "<td id='tb_time'";
			// reservation after maitre message
			if ($row->reservation_timestamp > $maitre['maitre_timestamp'] && $maitre['maitre_comment_day']!='') {
				echo " class='tautologous' title='"._sentence_13."' ";
			}
			echo ">";
			echo "<strong>".formatTime($row->reservation_time,$general['timeformat'])."</strong></td>";
			echo "<td id='tb_pax'><strong class='big'>".$row->reservation_pax."</strong>&nbsp;&nbsp;<span class='noprint'>";
			printType($row->reservation_hotelguest_yn);
				//echo "<img src='images/icons/user-silhouette.png' class='middle'/>";
			echo "</span></td><td style='width:20%' id='tb_name'><span class='noprint'>".printTitle($row->reservation_title)."</span><strong> <a id='detlbuttontrigger' href='ajax/guest_detail.php?id=".$id."'"; 
			// color guest name if tautologous
			if($tautologous>1){echo" class='tautologous tipsy' title='"._tautologous_booking."'";}
			echo ">".$row->reservation_guest_name."</a></strong>";
			
			// old reservations symbol
			if( (strtotime($row->reservation_timestamp) + $general['old_days']*86400) <= time() ){
				echo "<img src='images/icons/clock-bolt.png' class='help tipsyold middle smicon' title='"._sentence_11."' />";
			}
			// recurring symbol
			if ($row->repeat_id !=0) {
	            echo "&nbsp;<img src='images/icons/loop-alt.png' alt='"._recurring.
					 "' title='"._recurring."' class='tipsy' border='0' >";
	        }
	
			echo"</td><td style='width:30%' id='tb_note'>";
				if ($_SESSION['page'] == 1) {
			 		echo $row->outlet_name;
			 	}else{
					echo $row->reservation_notes;
				}
			echo "</td>";
			if($_SESSION['wait'] == 0){
				echo "<td class='big tb_nr' style='width:85px;' id='tb_table'><img src='images/icons/table_II.png' class='tipsy leftside noprint' title='"._table."' /><div id='reservation_table-".$id."' class='inlineedit'>".$row->reservation_table."</div></td>";
			}
			echo "<td class='noprint'><div>";
				getStatusList($id, $row->reservation_status);
			echo "</div></td>";
			echo "<td class='noprint'>";
			echo "<small>".$row->reservation_booker_name." | ".humanize($row->reservation_timestamp)."</small>";
			echo "</td>";
			echo "<td class='noprint'>";
			// MOVE BUTTON
			//	echo "<a href=''><img src='images/icons/arrow.png' alt='move' class='help' title='"._move_reservation_to."'/></a>";
			
			// WAITLIST ALLOW BUTTON
			if($_SESSION['wait'] == 1){
				$leftspace = leftSpace(substr($row->reservation_time,0,5), $availability);
				if($leftspace >= $row->reservation_pax && $_SESSION['outlet_max_tables']-$tbl_availability[substr($row->reservation_time,0,5)] >= 1){	    
					echo"&nbsp;<a href='#' name='".$id."' class='alwbtn'><img src='images/icons/check-alt.png' name='".$id."' alt='"._allow."' class='help' title='"._allow."'/></a>&nbsp;&nbsp;";
				}
			}
			// EDIT/DETAIL BUTTON
			echo "<a href='?p=102&resID=".$id."'><img src='images/icons/pen-fill.png' alt='"._detail."' class='help' title='"._detail."'/></a>&nbsp;&nbsp;";
			// DELETE BUTTON
			if ( current_user_can( 'Reservation-Delete' ) && $q!=3 ){
		    	echo"<a href='#modalsecurity' name='".$row->repeat_id."' id='".$id."' class='delbtn'>
					<img src='images/icons/delete.png' alt='"._cancelled."' class='help' title='"._delete."'/></a>";
			}
		echo"</td></tr>";
		$tablesum ++;
		$guestsum += $row->reservation_pax;
			}
		}
		?>
	</tbody>
	<tfoot>
		<tr style="border:1px #000;">
			<td class=" noprint"></td><td></td>
			<td colspan="2" class="bold"><?php echo $guestsum;?>&nbsp;&nbsp;<?php echo _guest_summary;?></td>
			<td></td>
			<td colspan="2" class="bold"><?php echo $tablesum;?>&nbsp;&nbsp;<?php echo _tables_summary;?></td>
			<?php
			if($_SESSION['wait'] == 0){
				//echo "<td></td>";
			}
			?>
		</tr>
	</tfoot>
</table>
<!-- End reservation table data -->