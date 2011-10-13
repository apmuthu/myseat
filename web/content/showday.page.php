
	<!-- Begin 1st level tab -->
	<ul class="first_level_tab">
		<li>
		<a href="main_page.php?q=1" <?php if ($q == 1 || $q == 4) { echo " class='active'";}else{ echo " class='inactive'"; }?> >
				<?php 
				if ($q == 4){
					echo _search;	
				}else{
					echo _reservations;
				} 
				?>
			</a>
		</li>
		<?php 
		if ( $today_date <= $_SESSION['selectedDate'] && $dayoff == 0 && current_user_can('Reservation-New') ){
			echo'<li><a href="main_page.php?q=2"';
				if ($q == 2) { echo " class='active'";}else{ echo " class='inactive'"; } 
				echo'>'. _add_reservation;
				if ($_SESSION['selOutlet']['limit_password']!='') { echo "&nbsp<img src='../PLC/img/lock.png'/ height='12px'>";}
				echo '</a></li>';
		} 
		?>
		<?php if ($q != 4):?>
		<li>
			<a href="main_page.php?q=3&s=1" <?php if ($q == 3) { echo " class='active'";}else{ echo " class='inactive'"; }?> >
				<?php echo _canceled_reservations; ?>
			</a>
		</li>
		<?php endif; ?>
	</ul>	
	<!-- End 1st level tab -->
	
	<br class="clear"/>
	
	<!-- Begin one column box -->
	<div class="onecolumn">
		
		<div class="header">
			<?php if ($searchquery == ''){ ?>
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
			<div class="dategroup_name">
				<a href="?selectedDate=<?php echo $_SESSION['selectedDate']; ?>">
					<?php
					echo $_SESSION['selOutlet']['outlet_name'].
					" - ".strftime("%A",strtotime($_SESSION['selectedDate'])); 
					?>
				</a>
			</div>
			<!-- Begin 2nd level tab -->
			<ul class="second_level_tab noprint">
				<li>
					<a href="#" id="outlet_detail_button">
						<?php echo _detail;?>
					</a>
				</li>
				<li>
					<a id="cxlbuttontrigger" href="ajax/cxllist.php">CXL</a>
				</li>
				<?php if ($q == 1){ ?>
				<li>
					<a href="javascript:window.print()">
						<!-- <img src="images/menu-icons/printer.png" alt="Print"> -->
						<?php echo _print;?>
					</a>
				</li>
				<?php } ?>
			</ul>
			<!-- End 2nd level tab -->
			<?php }else{ ?>
			<div class="dategroup_name">
				<?php echo _search_results;?>
			</div>
			<!-- Begin 2nd level tab -->
			<ul class="second_level_tab">
				<li>
					<a href="main_page.php?q=1" id="search_back_button">
						<?php echo _back;?>
					</a>
				</li>
			</ul>
			<!-- End 2nd level tab -->
			<?php } ?>
		</div>

		<!-- Daily outlets details -->
		<div id='daily_outlets_details'>
			<?php include('includes/daily_outlet_details.inc.php'); ?>
		</div>		

		<!-- ALERT & MESSAGE boxes goes here -->
			<?php
			if($searchquery == '' && $_SESSION['storno'] == 0){
				echo "<div class='noprint'>"; 
					include('includes/messagebox.inc.php'); 
				echo "</div>";
			} 
			$_SESSION['errors'] = array();
			$_SESSION['messages'] = array();
			?>

		<!-- CAPACITY timeline goes here -->
		<?php

		if(($q=='1' || $q=='2') && $dayoff == 0){
		echo "<div class='timeline-section' id='timeline'>";	
			include('includes/timeline.inc.php');
		echo "</div><br class='clear'/>";	
		}
		?>
		
		<!-- Begin nomargin -->
		<div class="content">
			
			<?php
			// ** content
			switch($q){
				case '1':
				 if($dayoff == 0){	
					// confirmed
					echo "<br/><h3>"._confirmed_reservations."</h3>";
					include('includes/reservations_grid.inc.php');
		
					// waitlist
					$_SESSION['wait'] = 1;
					$waitlist =	querySQL('reservations');
					if($waitlist){
						echo "<br/><br/><h3>"._wait_list."</h3>";
						include('includes/reservations_grid.inc.php');
					}
				 }else{
					echo "<h2 class='dayoff'>"._day_off."</h2>";
				 }
				break;
				case '2':
					// new
					include('includes/new.inc.php');
				break;
				case '3':
					// cancelled/storno
					include('includes/reservations_grid.inc.php');
				break;
				case '4':
					// search
					include('includes/search_grid.inc.php');
				break;
			}
			echo"<br/>";
			include('includes/manual_lines.inc.php');
			//most recent reservations
			include('includes/recent.inc.php');
			?>
			<br/>
			<br class="clear"/>
		</div>
		<!-- End nomargin -->
</div>
<br class="clear"/>