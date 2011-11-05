  <li class="dropdown">
    <a href="#" class="dropdown-toggle"><?php echo _outlets; ?></a>
    <ul class="dropdown-menu">
		<?php
			$valid_outlets = array();
			$outlets = querySQL('db_outlets');
			foreach($outlets as $row) {
			 if ( ($row->saison_start<=$row->saison_end 
				 && $_SESSION['selectedDate_saison']>=$row->saison_start 
				 && $_SESSION['selectedDate_saison']<=$row->saison_end)
				) {
				echo"<li>\n<a href='?p=2&outletID=".$row->outlet_id."'>".$row->outlet_name."</a>\n</li>\n";
				$valid_outlets[] = $row->outlet_id;
				}
			}
		?>
    </ul>
  </li>
