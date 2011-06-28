<style type="text/css" media="screen">
	.timeline	                            {
                                            font-size: 0.8em !important;
                                        	}  
	.timeline li                            {
	                                            width: 1.0em !important;
	                                        }
	.timeline li .label                     {
											    display: none !important;
											}
</style>
<!-- Begin one column box -->
<div class='onecolumn'>
 <div class='header'>
	<a href="?selectedDate=<?php echo buildDate($settings['dbdate'],$sd,$sm,$sj,-1); ?>" class="navgroup">
		&laquo;
	</a>
	<div class="date dategroup">
		<div class="text" id="datetext"><?php echo $_SESSION['selectedDate_user']; ?></div>
		<input type="text" id="datepicker"/>
		<input type="hidden" id="dbdate" value="<?php echo $_SESSION['selectedDate']; ?>"/>
	</div>
	<a href="?selectedDate=<?php echo buildDate($settings['dbdate'],$sd,$sm,$sj,1); ?>" class="navgroup">
		&raquo;
	</a>
	<!-- Begin 2nd level tab -->
	<ul class='second_level_tab'>
		<li>
			<a href='?p=2' class='button_dark'> <?php echo _back;?>
			</a>
		<li/>
	</ul>
	<!-- End 2nd level tab -->
 </div>
<div id='content_wrapper'>
		<?php
			// MESSAGE boxes goes here
			include('includes/messagebox.inc.php'); 
		?>
</div>
</div>
<br class='cl' />
			<?php
			// ** print out the timelines of all outlets **
			// memorize actual selected outlet
			$rem_outlet = $_SESSION['outletID'];
			
			echo"<div class='onecolumn'><div class='header'>\n";
			echo"<div class='dategroup_name'>"._statistics."</div>
			</div>\n<div id='content_wrapper'>";
			
			$outlets = querySQL('db_outlets');
			foreach($outlets as $row) {
			 if ( ($row->saison_start<=$row->saison_end 
				 && $_SESSION['selectedDate_saison']>=$row->saison_start 
				 && $_SESSION['selectedDate_saison']<=$row->saison_end)
				){
					// outlet ID
					$_SESSION['outletID'] = $row->outlet_id;
					// outlet settings
					$rows = querySQL('db_outlet_info');
					if($rows){
						foreach ($rows as $key => $value) {
							$_SESSION['selOutlet'][$key] = $value;
						}
					}

					echo"<div style='min-width:400px !important;'>\n";

					//make each outlet a timeline
					echo"<h5><a href='main_page.php?p=2&outletID=".$row->outlet_id."'>".$row->outlet_name."</a></h5>";
					
					// get outlet maximum capacity
					$maxC = maxCapacity();
					
					include('includes/timeline.inc.php');
					
					echo"<br class='cl' /><br/>\n</div>";
				}
			}
			echo"</div></div><br class='clear' />";
			
			// memorize actual selected outlet
			$_SESSION['outletID'] = $rem_outlet;
			// memorize selected outlet details
			$rows = querySQL('db_outlet_info');
			if($rows){
				foreach ($rows as $key => $value) {
					$_SESSION['selOutlet'][$key] = $value;
				}
			}
			
			// ** print out all reservations **
			echo"<div class='onecolumn'><div class='header'>\n";
			echo"<div class='dategroup_name'>"._confirmed_reservations."</div>
			</div>\n
			<div id='content_wrapper'>\n";
			
			// no waitlist
			$_SESSION['wait'] = 0;
			include('includes/reservations_grid.inc.php');
			
			echo"<br class='cl' /><br/>\n</div></div><br/>";
			
			?>

<br class="clear"/><br/>