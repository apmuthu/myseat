<!-- Begin one column box -->
<div class="onecolumn most-recent" id="most_recent">
	
	<div class="header">
	
		<h2><?php echo _recent_reservations;?></h2>

		
	</div>
	
	<div class="content">
		
		<!-- Begin content -->
		<ul class="global">
			
			<?php
			$recent = querySQL('recent');

			if ($recent) {
				foreach($recent as $row) {
					// reservations or storno ?
					$link = ($row->reservation_hidden == 0) ? 'p=2' : 'q=3&s=1';
					$text = ($row->reservation_hidden == 0) ? _booked_ : _canceled_;
					echo "<li><a href=main_page.php?".$link."&selectedDate=".$row->reservation_date.">".$row->reservation_booker_name." ".$text." ".$row->reservation_pax." "._people_." ".$row->reservation_guest_name." "._for_." ".date($general['dateformat'],strtotime($row->reservation_date))." ".formatTime($row->reservation_time,$general['timeformat'])."</a></li>";
				}
			}
			?>
			
		</ul>
		<!-- End content -->
		
	</div>
	
</div></div>
<!-- End one column box -->